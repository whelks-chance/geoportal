<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 14/08/13
 * Time: 15:11
 * To change this template use File | Settings | File Templates.
 */
class AdminMetadataController extends Controller
{

    function actionbuildNewSurveyLinks() {

        $this->upsetSurveyProjectAndVisibility($_POST['sid'], $_POST['projectID']);

        $returnArray['success'] = true;

        echo json_encode($returnArray);
    }


    function actionaddUserToProject() {
        $userID = "";
        if(isset($_POST['userID'])) {
            $userID = $_POST['userID'];
        }
        $projectID = "";
        if(isset($_POST['projectID'])) {
            $projectID = $_POST['projectID'];
        }

        $results = array();
        if ($userID != "" && $projectID != "") {

//      Create Project

            $upsetVisibility = "INSERT INTO projectusers(
            userid, projectid) VALUES ('" . $userID . "', '" . $projectID . "');";
            $results2 = DataAdapter::DefaultExecuteAndRead($upsetVisibility, "Geoportal");

            $results['success'] = true;
        }

        echo json_encode($results);
    }

    function actiongetUsersProjects() {

        $userObject = Yii::app()->user;
        $username = $userObject->getName();

        $projectsQuery = "SELECT pu.projectid, p.projectname
            FROM alphausersdetails a, projectusers pu, project p
            where username='" . $username . "' and CAST (a.id AS text) = pu.userid
            and pu.projectid = p.projectid;";
        $results = DataAdapter::DefaultExecuteAndRead($projectsQuery, "Geoportal");
        $projectsArray = array();
        foreach ($results as $project) {
            $projectArray['projectid'] = trim($project->projectid);
            $projectArray['projectname'] = trim($project->projectname);
            $projectsArray[] = $projectArray;
        }
        $returnArray['usersProjects'] = $projectsArray;

        echo json_encode($returnArray);
    }

    function actioncreateProject() {
        $projectID = "";
        if(isset($_POST['projectID'])) {
            $projectID = $_POST['projectID'];
        }
        $projectName = "";
        if(isset($_POST['projectName'])) {
            $projectName = $_POST['projectName'];
        }

        $results = array();
        if ($projectID != "" && $projectName != "") {

//      Create Project

            $upsetVisibility = "INSERT INTO project(
            projectid, projectname) VALUES ('" . $projectID . "', '" . $projectName . "');";
            $results2 = DataAdapter::DefaultExecuteAndRead($upsetVisibility, "Geoportal");

            $results['success'] = true;
        }

        echo json_encode($results);
    }


    function actionchangeSurveyVisibility() {
        $surveyID = "";
        if(isset($_POST['surveyID'])) {
            $surveyID = $_POST['surveyID'];
        }
        $visibilityID = "";
        if(isset($_POST['visibilityID'])) {
            $visibilityID = $_POST['visibilityID'];
        }

        if ($surveyID != "" && $visibilityID != "") {

//      Set visibility of survey

            $upsetVisibility = "Update surveyvisibility Set visibilitystateid='" . $visibilityID . "'
            Where surveyid='" . $surveyID . "';
            Insert into surveyvisibility(surveyid, visibilitystateid)
                Select '" . $surveyID . "', 'st002' Where Not Exists
                (Select 1 From surveyvisibility Where
            surveyid='" . $surveyID . "');";
            $results2 = DataAdapter::DefaultExecuteAndRead($upsetVisibility, "Geoportal");

        }
        $results['success'] = true;

        echo json_encode($results);
    }


    function actionaddSurveyToProject() {
        $surveyID = "";
        if(isset($_POST['surveyID'])) {
            $surveyID = $_POST['surveyID'];
        }
        $projectID = "";
        if(isset($_POST['projectID'])) {
            $projectID = $_POST['projectID'];
        }

        if ($surveyID != "" && $projectID != "") {

            $this->upsetSurveyProjectAndVisibility($surveyID, $projectID);

        }
        $results['success'] = true;

        echo json_encode($results);
    }

    function upsetSurveyProjectAndVisibility($surveyID, $projectID) {
//        UpSet survey to be owned by project

        $upsetSurveyProject = "Update surveyownership Set projectid='" . $projectID . "' Where
             surveyid='" . $surveyID . "';
            Insert into surveyownership(surveyid, projectid)
                Select '" . $surveyID . "', '" . $projectID . "' Where Not Exists
                (Select 1 From surveyownership Where
            surveyid='" . $surveyID . "');";
        $results1 = DataAdapter::DefaultExecuteAndRead($upsetSurveyProject, "Geoportal");

//      Set visibility of survey

        $upsetVisibility = "Update surveyvisibility Set visibilitystateid='st002'
            Where surveyid='" . $surveyID . "';
            Insert into surveyvisibility(surveyid, visibilitystateid)
                Select '" . $surveyID . "', 'st002' Where Not Exists
                (Select 1 From surveyvisibility Where
            surveyid='" . $surveyID . "');";
        $results2 = DataAdapter::DefaultExecuteAndRead($upsetVisibility, "Geoportal");
    }

    function actiongetUserProjectData() {

        $returnArray = array();

        $recordedSurveyOwnershipVisibilityQuery = "SELECT servis.surveyid, servis.visibilitystateid,
         visstat.visibilitystatename, so.projectid, proj.projectname
FROM surveyvisibility servis, visibilitystates visstat, surveyownership so, project proj where
servis.visibilitystateid = visstat.visibilitystateid and
so.surveyid = servis.surveyid and
proj.projectid = so.projectid;";
        $results = DataAdapter::DefaultExecuteAndRead($recordedSurveyOwnershipVisibilityQuery, "Geoportal");

        $visibilityArray = array();
        foreach ($results as $survey) {
            $surveyArray['surveyid'] = trim($survey->surveyid);
            $surveyArray['visibilitystateid'] = trim($survey->visibilitystateid);
            $surveyArray['visibilitystatename'] = trim($survey->visibilitystatename);
            $surveyArray['projectid'] = trim($survey->projectid);
            $surveyArray['projectname'] = trim($survey->projectname);
            $visibilityArray[] = $surveyArray;
        }
        $returnArray['surveyVisibilityTotal'] = sizeof($visibilityArray);
        $returnArray['surveyVisibility'] = $visibilityArray;

        echo json_encode($returnArray);
    }

    function actiongetDataEntryOptionLists() {

        $returnArray = array();

        if(isset($_POST['users'])) {
            $userQuery = "SELECT id, username FROM alphausersdetails;";
            $results = DataAdapter::DefaultExecuteAndRead($userQuery, "Geoportal");
            $usersArray = array();
            foreach ($results as $user) {
                $userArray['id'] = trim($user->id);
                $userArray['username'] = trim($user->username);
                $usersArray[] = $userArray;
            }
            $returnArray['users'] = $usersArray;
        }

        if(isset($_POST['surveys'])) {
            $surveyQuery = "SELECT surveyid FROM survey;";
            $results = DataAdapter::DefaultExecuteAndRead($surveyQuery, "Survey_Data");
            $surveysArray = array();
            foreach ($results as $survey) {
                $surveyArray['surveyid'] = trim($survey->surveyid);
                $surveysArray[] = $surveyArray;
            }
            $returnArray['surveys'] = $surveysArray;
        }


        if(isset($_POST['projects'])) {
            $projectsQuery = "SELECT projectid, projectname FROM project;";
            $results = DataAdapter::DefaultExecuteAndRead($projectsQuery, "Geoportal");
            $projectsArray = array();
            foreach ($results as $project) {
                $projectArray['projectid'] = trim($project->projectid);
                $projectArray['projectname'] = trim($project->projectname);
                $projectsArray[] = $projectArray;
            }
            $returnArray['projects'] = $projectsArray;
        }


        if(isset($_POST['visibilities'])) {
            $visStatesQuery = "SELECT visibilitystateid, visibilitystatename FROM visibilitystates;";
            $results = DataAdapter::DefaultExecuteAndRead($visStatesQuery, "Geoportal");
            $visArray = array();
            foreach ($results as $visibility) {
                $visibilityArray['vis_id'] = trim($visibility->visibilitystateid);
                $visibilityArray['vis_name'] = trim($visibility->visibilitystatename);
                $visArray[] = $visibilityArray;
            }
            $returnArray['visibility'] = $visArray;
        }


        if(isset($_POST['route_type'])) {
            //route types
            $routeQuery = "SELECT * FROM route_type;";
            $results = DataAdapter::DefaultExecuteAndRead($routeQuery, "Survey_Data");

            $routeTypes = array();
            foreach ($results as $routeInfo) {
                $routeArray['routetypeid'] = trim($routeInfo->routetypeid);
                $routeArray['routetype_description'] = trim($routeInfo->routetype_description);
                $routeArray['routetype'] = trim($routeInfo->routetype);
                $routeTypes[] = $routeArray;
            }
            $returnArray['routeTypes'] = $routeTypes;
        }


        if(isset($_POST['response_type'])) {
//response type
            $responseTypeQuery = "SELECT responseid, response_name FROM response_type;";
            $results = DataAdapter::DefaultExecuteAndRead($responseTypeQuery, "Survey_Data");

            $responseTypes = array();
            foreach ($results as $responseInfo) {
                $responseArray['responseid'] = trim($responseInfo->responseid);
                $responseArray['response_name'] = trim($responseInfo->response_name);
                $responseTypes[] = $responseArray;
            }
            $returnArray['responseTypes'] = $responseTypes;
        }


        if(isset($_POST['q_type'])) {
//question type
            $questionTypeQuery = 'SELECT q_typeid, q_type_text, "q_typeDesc" FROM q_type;';
            $results = DataAdapter::DefaultExecuteAndRead($questionTypeQuery, "Survey_Data");

            Log::toFile(print_r($results, true));

            $questionTypes = array();
            foreach ($results as $questionInfo) {
                $questionArray['q_typeid'] = trim($questionInfo->q_typeid);
                $questionArray['q_type_text'] = trim($questionInfo->q_type_text);
                $questionArray['q_typeDesc'] = trim($questionInfo->q_typeDesc);
                $questionTypes[] = $questionArray;
            }
            $returnArray['questionTypes'] = $questionTypes;

        }

        if(isset($_POST['dublincore_type'])) {
//dcType type
            $dcTypeQuery = "SELECT dctypeid, dc_type_title FROM dublincore_type;";
            $results = DataAdapter::DefaultExecuteAndRead($dcTypeQuery, "Survey_Data");

            $dcTypeTypes = array();
            foreach ($results as $dcTypeInfo) {
                $dcTypeType['dctypeid'] = trim($dcTypeInfo->dctypeid);
                $dcTypeType['dc_type_title'] = trim($dcTypeInfo->dc_type_title);
                $dcTypeTypes[] = $dcTypeType;
            }
            $returnArray['dcTypes'] = $dcTypeTypes;

        }

        if(isset($_POST['dublincore_format'])) {
//dcFormat type
            $dcFormatQuery = "SELECT dcformatid, dc_format_title FROM dublincore_format;";
            $results = DataAdapter::DefaultExecuteAndRead($dcFormatQuery, "Survey_Data");

            $dcFormatTypes = array();
            foreach ($results as $dcFormatInfo) {
                $dcFormatArray['dcformatid'] = trim($dcFormatInfo->dcformatid);
                $dcFormatArray['dc_format_title'] = trim($dcFormatInfo->dc_format_title);
                $dcFormatTypes[] = $dcFormatArray;
            }
            $returnArray['dcFormatTypes'] = $dcFormatTypes;

        }

        if(isset($_POST['dublincore_language'])) {
//dcLang type
            $dcLangQuery = "SELECT dclangid, dc_language_title FROM dublincore_language;";
            $results = DataAdapter::DefaultExecuteAndRead($dcLangQuery, "Survey_Data");

            $dcLangTypes = array();
            foreach ($results as $dcLangInfo) {
                $dcLangArray['dclangid'] = trim($dcLangInfo->dclangid);
                $dcLangArray['dc_language_title'] = trim($dcLangInfo->dc_language_title);
                $dcLangTypes[] = $dcLangArray;
            }
            $returnArray['dcLangs'] = $dcLangTypes;

        }

        if(isset($_POST['group_tags'])) {
//group_tags
            $group_tagsQuery = "SELECT tagid, tgroupid, tag_text FROM group_tags;";
            $results = DataAdapter::DefaultExecuteAndRead($group_tagsQuery, "Survey_Data");

            $group_tagsTypes = array();
            foreach ($results as $group_tagsInfo) {
                $group_tagsArray['tagid'] = trim($group_tagsInfo->tagid);
                $group_tagsArray['tgroupid'] = trim($group_tagsInfo->tgroupid);
                $group_tagsArray['tag_text'] = trim($group_tagsInfo->tag_text);
                $group_tagsTypes[] = $group_tagsArray;
            }
            $returnArray['group_tags'] = $group_tagsTypes;

        }

        if(isset($_POST['spatial_level'])) {
//spatial_level
            $spatial_levelQuery = "SELECT code, level FROM spatial_level;";
            $results = DataAdapter::DefaultExecuteAndRead($spatial_levelQuery, "Survey_Data");

            $spatial_levelTypes = array();
            foreach ($results as $group_tagsInfo) {
                $spatial_levelArray['code'] = trim($group_tagsInfo->code);
                $spatial_levelArray['level'] = trim($group_tagsInfo->level);
                $spatial_levelTypes[] = $spatial_levelArray;
            }
            $returnArray['spatial_levels'] = $spatial_levelTypes;

        }

        if(isset($_POST['survey_frequency'])) {
//survey_frequency
            $survey_frequencyQuery = "SELECT svyfreqid, svy_frequency_title FROM survey_frequency;";
            $results = DataAdapter::DefaultExecuteAndRead($survey_frequencyQuery, "Survey_Data");

            $survey_frequencyTypes = array();
            foreach ($results as $survey_frequencyInfo) {
                $survey_frequencyArray['svyfreqid'] = trim($survey_frequencyInfo->svyfreqid);
                $survey_frequencyArray['svy_frequency_title'] = trim($survey_frequencyInfo->svy_frequency_title);
                $survey_frequencyTypes[] = $survey_frequencyArray;
            }
            $returnArray['survey_frequency'] = $survey_frequencyTypes;

        }

        if(isset($_POST['thematic_groups'])) {
//thematic_groups
            $thematic_groupsQuery = "SELECT tgroupid, grouptitle, groupdescription FROM thematic_groups;";
            $results = DataAdapter::DefaultExecuteAndRead($thematic_groupsQuery, "Survey_Data");

            $thematic_groupsTypes = array();
            foreach ($results as $thematic_groupsInfo) {
                $thematic_groupsArray['tgroupid'] = trim($thematic_groupsInfo->tgroupid);
                $thematic_groupsArray['grouptitle'] = trim($thematic_groupsInfo->grouptitle);
                $thematic_groupsArray['groupdescription'] = trim($thematic_groupsInfo->groupdescription);
                $thematic_groupsTypes[] = $thematic_groupsArray;
            }
            $returnArray['thematic_groups'] = $thematic_groupsTypes;

        }


        echo json_encode($returnArray);
    }

    function actiongetDCinfo() {

        $dcInfoQuery = "SELECT surveyid, identifier, title FROM dc_info;";

        $results = DataAdapter::DefaultExecuteAndRead($dcInfoQuery, "Survey_Data");

        Log::toFile(print_r($results, true));

        $addDCInfoArray = array();

        foreach ($results as $dcInfo) {
//            Log::toFile("Survey : " . print_r($surveyData, true));
            $dcInfoArray['sid'] = trim($dcInfo->surveyid);
            $dcInfoArray['wid'] = trim($dcInfo->identifier);
            $dcInfoArray['name'] = trim($dcInfo->title);
            $addDCInfoArray[] = $dcInfoArray;
        }

        $returnArray['DublinCoreId'] = $addDCInfoArray;

        echo json_encode($returnArray);
    }

    function actiongetSurveyQuestions() {
        $SID = "";
        if(isset($_POST['SID'])) {
            $SID = $_POST['SID'];
        }

//        $dcInfoQuery = "SELECT FROM survey_questions_link, questions where surveyid='" . $SID . "';";

        $surveyQuestionQuery = "select q.qid, q.questionnumber from questions q join survey_questions_link surql on q.qid = surql.qid where surql.surveyid='" . $SID . "';";

        $results = DataAdapter::DefaultExecuteAndRead($surveyQuestionQuery, "Survey_Data");

        Log::toFile(print_r($results, true));

        $allQuestionArray = array();

        foreach ($results as $dcInfo) {
//            Log::toFile("Survey : " . print_r($surveyData, true));
            $questionArray['QuestionName'] = trim($dcInfo->questionnumber);
            $questionArray['QuestionID'] = trim($dcInfo->qid);
            $allQuestionArray[] = $questionArray;
        }

        $returnArray['questionData'] = $allQuestionArray;

        echo json_encode($returnArray);
    }

    function actioninsertSurvey() {
        $surveyID = "N/A";
        if(isset($_POST['surveyID'])) {
            $surveyID = $_POST['surveyID'];
        }

        $surveyTitle = "N/A";
        if(isset($_POST['surveyTitle'])) {
            $surveyTitle = $_POST['surveyTitle'];
        }

        $surveyCollector = "N/A";
        if(isset($_POST['surveyCollector'])) {
            $surveyCollector = $_POST['surveyCollector'];
        }

        $surveyStart = "now";
        if(isset($_POST['surveyStart'])) {
            $surveyStart = $_POST['surveyStart'];
        }

        $surveyEnd = "now";
        if(isset($_POST['surveyEnd'])) {
            $surveyEnd = $_POST['surveyEnd'];
        }

        $surveyFrequency = "N/A";
        if(isset($_POST['surveyFrequency'])) {
            $surveyFrequency = $_POST['surveyFrequency'];
        }

        $surveySeries = "N/A";
        if(isset($_POST['surveySeries'])) {
            $surveySeries = $_POST['surveySeries'];
        }

        $surveyNotes = "N/A";
        if(isset($_POST['surveyNotes'])) {
            $surveyNotes = $_POST['surveyNotes'];
        }

        $surveyLocation = "N/A";
        if(isset($_POST['surveyLocation'])) {
            $surveyLocation = $_POST['surveyLocation'];
        }

        $surveyURL = "N/A";
        if(isset($_POST['surveyURL'])) {
            $surveyURL = $_POST['surveyURL'];
        }

        $surveyDataCollectionMethod = "N/A";
        if(isset($_POST['surveyDataCollectionMethod'])) {
            $surveyDataCollectionMethod = $_POST['surveyDataCollectionMethod'];
        }

        $surveyCollectionSituation = "N/A";
        if(isset($_POST['surveyCollectionSituation'])) {
            $surveyCollectionSituation = $_POST['surveyCollectionSituation'];
        }

        $surveySamplingProcedure = "N/A";
        if(isset($_POST['surveySamplingProcedure'])) {
            $surveySamplingProcedure = $_POST['surveySamplingProcedure'];
        }

        $surveySamplingError = "N/A";
        if(isset($_POST['surveySamplingError'])) {
            $surveySamplingError = $_POST['surveySamplingError'];
        }

        $surveySampleSize = "N/A";
        if(isset($_POST['surveySampleSize'])) {
            $surveySampleSize = $_POST['surveySampleSize'];
        }

        $surveyResponseRate = "N/A";
        if(isset($_POST['surveyResponseRate'])) {
            $surveyResponseRate = $_POST['surveyResponseRate'];
        }

        $surveyWeighting = "N/A";
        if(isset($_POST['surveyWeighting'])) {
            $surveyWeighting = $_POST['surveyWeighting'];
        }

        $long = "No";
        if(isset($_POST['long'])) {
            $long = $_POST['long'];
        }

        $identifier = "N/A";
        if(isset($_POST['wid'])) {
            $identifier = $_POST['wid'];
        }

        $username = "";
        $userObject = Yii::app()->user;
        $username = $userObject->getName();

        $collectionstartdate = "now";
        $collectionenddate = "now";
        $dataproduct = "";
        $dataproductid = "";
        $created = "now";
        $short_title = "";
        $spatialdata = "false";

        $dbInsert = "INSERT INTO survey(
            surveyid, identifier, survey_title, datacollector, collectionstartdate,
            collectionenddate, moc_description, samp_procedure, collectionsituation,
            surveyfrequency, surveystartdate, surveyenddate, des_weighting,
            samplesize, responserate, descriptionofsamplingerror, dataproduct,
            dataproductid, location, link, notes, user_id, created, updated,
            long, short_title, spatialdata)
    VALUES (";

        $dbInsert .= "'" . $surveyID . "', '" . $identifier . "', '" . $surveyTitle . "', '" . $surveyCollector . "', Timestamp '" . $collectionstartdate . "', Timestamp '" .
            $collectionenddate . "', '" . $surveyDataCollectionMethod . "', '" . $surveySamplingProcedure . "', '" . $surveyCollectionSituation . "', '" .
            $surveyFrequency . "', Timestamp '" . $surveyStart . "', Timestamp '" . $surveyEnd . "', '" . $surveyWeighting . "', '" .
            $surveySampleSize . "', '" . $surveyResponseRate . "', '" . $surveySamplingError . "', '" . $dataproduct . "', '" .
            $dataproductid . "', '" . $surveyLocation . "', '" . $surveyURL . "', '" . $surveyNotes . "', '" . $username . "', Timestamp '" .
            $created . "', Timestamp 'now', '" . $long . "', '" . $short_title . "', '" . $spatialdata;

        $dbInsert .= "');";

        Log::toFile($dbInsert);

        $returnArray['success'] = true;
        $returnArray['surveyInsert'] = $dbInsert;

        $results = DataAdapter::DefaultExecuteAndRead($dbInsert, "Survey_Data");

        echo json_encode($returnArray);

    }

    function actionInsertDC (){
        Log::toFile(print_r($_POST, true));

        $username = "";
        $userObject = Yii::app()->user;
        $username = $userObject->getName();

        $identifier = "N/A";
        if(isset($_POST['dcWiserdID'])) {
            $identifier = $_POST['dcWiserdID'];
        }
        $title = "N/A";
        if(isset($_POST['dcTitle'])) {
            $title = $_POST['dcTitle'];
        }
        $creator = "N/A";
        if(isset($_POST['dcCreator'])) {
            $creator = $_POST['dcCreator'];
        }
        $subject = "N/A";
        if(isset($_POST['dcSubject'])) {
            $subject = $_POST['dcSubject'];
        }
        $description = "N/A";
        if(isset($_POST['dcDescription'])) {
            $description = $_POST['dcDescription'];
        }
        $publisher = "N/A";
        if(isset($_POST['dcPublisher'])) {
            $publisher = $_POST['dcPublisher'];
        }
        $contributor = "N/A";
        if(isset($_POST['dcContributor'])) {
            $contributor = $_POST['dcContributor'];
        }

        $dateObject = new DateTime('now');
        $date = $dateObject->format('Y-m-d H:i:s');
        if(isset($_POST['dcDate'])) {
            $date = $_POST['dcDate'];
        }
        $type = "N/A";
        if(isset($_POST['dcType'])) {
            $type = $_POST['dcType'];
        }
        $format = "N/A";
        if(isset($_POST['dcFormat'])) {
            $format = $_POST['dcFormat'];
        }
        $source = "N/A";
        if(isset($_POST['dcSource'])) {
            $source = $_POST['dcSource'];
        }
        $language = "N/A";
        if(isset($_POST['dcLanguage'])) {
            $language = $_POST['dcLanguage'];
        }
        $relation = "N/A";
        if(isset($_POST['dcRelation'])) {
            $relation = $_POST['dcRelation'];
        }
        $coverage = "N/A";
        if(isset($_POST['dcCoverage'])) {
            $coverage = $_POST['dcCoverage'];
        }
        $rights = "N/A";
        if(isset($_POST['dcRights'])) {
            $rights = $_POST['dcRights'];
        }
        $user_id = "N/A";
        if($username != "") {
            $user_id = $username;
        }
        $created = $date;
        if(isset($_POST['created'])) {
            $created = $_POST['created'];
        }
        $updated = $date;
        if(isset($_POST['updated'])) {
            $updated = $_POST['updated'];
        }


        $dbInsert = "INSERT INTO dc_info(
            identifier, title, creator, subject, description, publisher,
            contributor, date, type, format, source, language, relation,
            coverage, rights, user_id, created, updated)";
        $dbInsert .= " VALUES ('";

        $dbInsert .= $identifier . "', '" . $title . "', '" . $creator . "', '" . $subject . "', '" .
            $description . "', '" . $publisher . "', '" . $contributor . "', TIMESTAMP '" . $date . "', '" .
            $type . "', '" . $format . "', '" . $source . "', '" . $language . "', '" . $relation . "', '" .
            $coverage . "', '" . $rights . "', '" . $user_id . "'" .
            ", TIMESTAMP '" . $created . "', TIMESTAMP 'now"; // . $updated;

        $dbInsert .= "');";

        Log::toFile($dbInsert);

        $returnArray['success'] = true;
        $returnArray['dcInsert'] = $dbInsert;

        $results = DataAdapter::DefaultExecuteAndRead($dbInsert, "Survey_Data");

        echo json_encode($returnArray);
    }

    function actioninsertQuestion() {
        $QuestionSurveyID = "N/A";
        if(isset($_POST['QuestionSurveyID'])) {
            $QuestionSurveyID = $_POST['QuestionSurveyID'];
        }

        $QuestionID = "N/A";
        if(isset($_POST['QuestionID'])) {
            $QuestionID = $_POST['QuestionID'];
        }

        $QuestionNumber = "N/A";
        if(isset($_POST['QuestionNumber'])) {
            $QuestionNumber = $_POST['QuestionNumber'];
        }

        $QuestionText = "N/A";
        if(isset($_POST['QuestionText'])) {
            $QuestionText = $_POST['QuestionText'];
        }

        $QuestionNotesPrompts = "N/A";
        if(isset($_POST['QuestionNotesPrompts'])) {
            $QuestionNotesPrompts = $_POST['QuestionNotesPrompts'];
        }

        $QuestionVariable = "N/A";
        if(isset($_POST['QuestionVariable'])) {
            $QuestionVariable = $_POST['QuestionVariable'];
        }

        $QuestionThematicGroups = "N/A";
        if(isset($_POST['QuestionThematicGroups'])) {
            $QuestionThematicGroups = $_POST['QuestionThematicGroups'];
        }

        $QuestionThematicTags = "N/A";
        if(isset($_POST['QuestionThematicTags'])) {
            $QuestionThematicTags = $_POST['QuestionThematicTags'];
        }

        $QuestionType = "N/A";
        if(isset($_POST['QuestionType'])) {
            $QuestionType = $_POST['QuestionType'];
        }

        $QuestionLinkedFrom = "N/A";
        if(isset($_POST['QuestionLinkedFrom'])) {
            $QuestionLinkedFrom = $_POST['QuestionLinkedFrom'];
        }

        $QuestionSubOf = "N/A";
        if(isset($_POST['QuestionSubOf'])) {
            $QuestionSubOf = $_POST['QuestionSubOf'];
        }

        $qtext_index = "N/A";
        if(isset($_POST['q_text'])) {
            $qtext_index = $_POST['q_text'];
        }

        $username = "";
        $userObject = Yii::app()->user;
        $username = $userObject->getName();

        $questionInsertQuery = "INSERT INTO questions(
            qid, literal_question_text, questionnumber, thematic_groups,
            thematic_tags, link_from, subof, type, variableid, notes, user_id,
            created, updated, qtext_index)
    VALUES ('";

        $questionInsertQuery .= $QuestionID . "', '" . $QuestionText . "', '" . $QuestionNumber . "', '" . $QuestionThematicGroups . "', '" .
            $QuestionThematicTags . "', '" . $QuestionLinkedFrom . "', '" . $QuestionSubOf . "', '" . $QuestionType . "', '" .
            $QuestionVariable . "', '" . $QuestionNotesPrompts . "', '" . $username .
            "', Timestamp 'now', Timestamp 'now', '" . $qtext_index . "');";

        Log::toFile($questionInsertQuery);

        $returnArray['success'] = true;
        $returnArray['questionInsert'] = $questionInsertQuery;

        $results = DataAdapter::DefaultExecuteAndRead($questionInsertQuery, "Survey_Data");


        $surveyQuestionLinkQuery = "INSERT INTO survey_questions_link( surveyid, qid, pk) VALUES ('" .
            $QuestionSurveyID . "', '" . $QuestionID . "', 0);";
        $results = DataAdapter::DefaultExecuteAndRead($surveyQuestionLinkQuery, "Survey_Data");


        echo json_encode($returnArray);
    }

}
