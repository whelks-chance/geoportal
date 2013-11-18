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

        $returnArray['success'] = "true";

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

            if(RoleManager::hasPermission('addUserToProject', null)) {

                $upsetVisibility = "INSERT INTO projectusers(
            userid, projectid) VALUES (:userID, :projectID);";
//                $results2 = DataAdapter::DefaultExecuteAndRead($upsetVisibility, "Geoportal");

                $values = array( ":userID" => $userID, ":projectID" => $projectID);

                DataAdapter::DefaultPDOExecuteAndRead($upsetVisibility, $values);

                $results['success'] = "true";
            } else {
                $results['success'] = false;
                $results['message'] = "You do not have permission to perform this action";
            }

        }

        echo json_encode($results);
    }

    function actionaddUserToRole() {
        $userID = "";
        if(isset($_POST['userID'])) {
            $userID = $_POST['userID'];
        }
        $roleName = "";
        if(isset($_POST['roleName'])) {
            $roleName = $_POST['roleName'];
        }

        $results = array();
        if ($userID != "" && $roleName != "") {
            if(RoleManager::hasPermission('changeUserRole', null)) {

                RoleManager::changeRole($userID, $roleName);
                $results['success'] = "true";
            } else {
                $results['success'] = false;
                $results['message'] = "You do not have permission to perform this action";
            }
        }

        echo json_encode($results);
    }

    function actiongetUsersProjects() {

        $userObject = Yii::app()->user;
        $username = $userObject->getName();

        $returnArray['usersProjects'] = $this::getUsersProjects($username);

        echo json_encode($returnArray);
    }

    static function getUsersProjects( $username ) {
        $projectsQuery = "SELECT pu.projectid, p.projectname
            FROM alphausersdetails a, projectusers pu, project p
            where username=:username and CAST (a.id AS text) = pu.userid
            and pu.projectid = p.projectid;";

        $values = array(":username" => $username );

//        $results = DataAdapter::DefaultExecuteAndRead($projectsQuery, "Geoportal");
        $results = DataAdapter::DefaultPDOExecuteAndRead($projectsQuery, $values);
        $projectsArray = array();
        foreach ($results->resultObject as $project) {
            $projectArray['projectid'] = trim($project->projectid);
            $projectArray['projectname'] = trim($project->projectname);
            $projectsArray[] = $projectArray;
        }
//        $returnArray['usersProjects'] = $projectsArray;

        return $projectsArray;
    }

//    function deleteme() {
//        //hacky stuff
//
////warning, this sets everything GLOBAL - don't do this if theres any project stuff in there
//        $userQuery = "SELECT surveyid FROM survey;";
//        $results = DataAdapter::DefaultExecuteAndRead($userQuery, "Survey_Data");
//        $surveysArray = array();
//        foreach ($results as $user) {
//            $surveyArray['surveyid'] = trim($user->surveyid);
//            $surveysArray[] = $surveyArray;
//        }
//
//        foreach ($surveysArray as $survey) {
//            $ownQuery = "SELECT surveyid, projectid FROM surveyownership where surveyid='" . $survey['surveyid'] . "';";
//            $visresults = DataAdapter::DefaultExecuteAndRead($ownQuery, "Geoportal");
//
//            if(sizeof($visresults) == 0) {
//                $visQuery = "Insert into surveyownership(surveyid, projectid) Values ('" . $survey['surveyid'] . "', 'proj_0001');";
//                $visresults = DataAdapter::DefaultExecuteAndRead($visQuery, "Geoportal");
//            }
//
//            $visQuery = "SELECT surveyid, visibilitystateid FROM surveyvisibility where surveyid='" . $survey['surveyid'] . "';";
//            $visresults = DataAdapter::DefaultExecuteAndRead($visQuery, "Geoportal");
//
//            if(sizeof($visresults) == 0) {
//                $visQuery = "Insert into surveyvisibility(surveyid, visibilitystateid) Values ('" . $survey['surveyid'] . "', 'st001');";
//                $visresults = DataAdapter::DefaultExecuteAndRead($visQuery, "Geoportal");
//            }
//
//        }
//
//delete hacky stuff
//    }

    function actioncreateProject() {

//        $this->deleteme();

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

            if(RoleManager::hasPermission('createProject', null)) {
                $this::createProject($projectID, $projectName);

                $results['success'] = "true";
            } else {
                $results['success'] = false;
                $results['message'] = "You do not have permission to perform this action";
            }
        }

        echo json_encode($results);
    }

    private function createProject($projectID, $projectName) {
        $upsetVisibility = "INSERT INTO project(
            projectid, projectname) VALUES (:projectID, :projectName);";

        $values = array(":projectID" => $projectID, ":projectName" => $projectName);
        $results = DataAdapter::DefaultPDOExecuteAndRead($upsetVisibility, $values);
        return $results->resultSuccess;

//        $results2 = DataAdapter::DefaultExecuteAndRead($upsetVisibility, "Geoportal");
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

            if(RoleManager::hasPermission('changeRecordVisibility', null)) {
                $this::setVisibility($surveyID, $visibilityID);

                $results['success'] = "true";
            } else {
                $results['success'] = false;
                $results['message'] = "You do not have permission to perform this action";
            }

        }

        echo json_encode($results);
    }

    function setVisibility($surveyID, $visibilityID) {
        $upsetVisibility = "Update surveyvisibility Set visibilitystateid=:visibilityID
            Where surveyid=:surveyID ;
            Insert into surveyvisibility(surveyid, visibilitystateid)
                Select :surveyID, 'st002' Where Not Exists
                (Select 1 From surveyvisibility Where
            surveyid=:surveyID);";

        $values = array(":visibilityID" => $visibilityID, ":surveyID" => $surveyID);

        $results = DataAdapter::DefaultPDOExecuteAndRead($upsetVisibility, $values);
        return $results->resultSuccess;
//        $results2 = DataAdapter::DefaultExecuteAndRead($upsetVisibility, "Geoportal");
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
            if(RoleManager::hasPermission('changeRecordVisibility', null)) {
                $this->upsetSurveyProjectAndVisibility($surveyID, $projectID);

                $results['success'] = "true";
            } else {
                $results['success'] = false;
                $results['message'] = "You do not have permission to perform this action";
            }

        } else {
            $results['success'] = false;
            $results['message'] = "Must provide a surveyID and a projectID";
        }

        echo json_encode($results);
    }

    function upsetSurveyProjectAndVisibility($surveyID, $projectID) {
//        UpSet survey to be owned by project

        $upsetSurveyProject = "Update surveyownership Set projectid=:projectID  Where
             surveyid=:surveyID ;
            Insert into surveyownership(surveyid, projectid)
                Select :surveyID , :projectID  Where Not Exists
                (Select 1 From surveyownership Where
            surveyid=:surveyID);";

        $values = array(":projectID" => $projectID, ":surveyID" => $surveyID);
        $results = DataAdapter::DefaultPDOExecuteAndRead($upsetSurveyProject, $values);
//        $results1 = DataAdapter::DefaultExecuteAndRead($upsetSurveyProject, "Geoportal");

//      Set visibility of survey

        $upsetVisibility = "Update surveyvisibility Set visibilitystateid='st002'
            Where surveyid=:surveyID ;
            Insert into surveyvisibility(surveyid, visibilitystateid)
                Select :surveyID, 'st002' Where Not Exists
                (Select 1 From surveyvisibility Where
            surveyid=:surveyID);";

                $values = array(":projectID" => $projectID, ":surveyID" => $surveyID);
            $results = DataAdapter::DefaultPDOExecuteAndRead($upsetVisibility, $values);
//        $results2 = DataAdapter::DefaultExecuteAndRead($upsetVisibility, "Geoportal");
    }

    function actiongetUserProjectData() {

        $returnArray = array();

        $recordedSurveyOwnershipVisibilityQuery = "SELECT servis.surveyid, servis.visibilitystateid,
         visstat.visibilitystatename, so.projectid, proj.projectname
FROM surveyvisibility servis, visibilitystates visstat, surveyownership so, project proj where
servis.visibilitystateid = visstat.visibilitystateid and
so.surveyid = servis.surveyid and
proj.projectid = so.projectid;";
//        $results = DataAdapter::DefaultExecuteAndRead($recordedSurveyOwnershipVisibilityQuery, "Geoportal");

        $results = DataAdapter::DefaultPDOExecuteAndRead($recordedSurveyOwnershipVisibilityQuery);

        $visibilityArray = array();
        foreach ($results->resultObject as $survey) {
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

        if(isset($_POST['roles'])) {

            $userID = Yii::app()->user->getID();

            Log::toFile("*" . $userID . "*");

            $auth=Yii::app()->authManager;

            $roleArray['assignments'] = $auth->getAuthAssignments($userID);

            $roleArray['surveyEntry'] = RoleManager::hasPermission("createRecordandDC", null);
            $roleArray['qualEntry'] = RoleManager::hasPermission("createRecordandDC", null);
            $roleArray['management'] = RoleManager::hasPermission("hubAdmin", null);

            $roleArray['user'] = Yii::app()->user->getID();
//            $roleArray['authorisedActions'] = RoleManager::getAuthorisedActions($userID);
            $roleArray['allRoles'] = RoleManager::getAllRoles();

            $returnArray['roles'] = $roleArray;
        }

        if(isset($_POST['users'])) {
            $userQuery = "SELECT id, username FROM alphausersdetails;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($userQuery);
//            $results = DataAdapter::DefaultExecuteAndRead($userQuery, "Geoportal");
            $usersArray = array();
            foreach ($results->resultObject as $user) {
                $userArray['id'] = trim($user->id);
                $userArray['username'] = trim($user->username);
                $usersArray[] = $userArray;
            }
            $returnArray['users'] = $usersArray;
        }

        if(isset($_POST['surveys'])) {
            $surveyQuery = "SELECT surveyid FROM survey;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($surveyQuery, null, "Survey_Data");
//            $results = DataAdapter::DefaultExecuteAndRead($surveyQuery, "Survey_Data");
            $surveysArray = array();
            foreach ($results->resultObject as $survey) {
                $surveyArray['surveyid'] = trim($survey->surveyid);
                $surveysArray[] = $surveyArray;
            }
            $returnArray['surveys'] = $surveysArray;
        }


        if(isset($_POST['projects'])) {
            $projectsQuery = "SELECT projectid, projectname FROM project;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($projectsQuery);
//            $results = DataAdapter::DefaultExecuteAndRead($projectsQuery, "Geoportal");
            $projectsArray = array();
            foreach ($results->resultObject as $project) {
                $projectArray['projectid'] = trim($project->projectid);
                $projectArray['projectname'] = trim($project->projectname);
                $projectsArray[] = $projectArray;
            }
            $returnArray['projects'] = $projectsArray;
        }


        if(isset($_POST['visibilities'])) {
            $visStatesQuery = "SELECT visibilitystateid, visibilitystatename FROM visibilitystates;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($visStatesQuery);
//            $results = DataAdapter::DefaultExecuteAndRead($visStatesQuery, "Geoportal");
            $visArray = array();
            foreach ($results->resultObject as $visibility) {
                $visibilityArray['vis_id'] = trim($visibility->visibilitystateid);
                $visibilityArray['vis_name'] = trim($visibility->visibilitystatename);
                $visArray[] = $visibilityArray;
            }
            $returnArray['visibility'] = $visArray;
        }


        if(isset($_POST['route_type'])) {
            //route types
            $routeQuery = "SELECT * FROM route_type;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($routeQuery, null, "Survey_Data");
//            $results = DataAdapter::DefaultExecuteAndRead($routeQuery, "Survey_Data");

            $routeTypes = array();
            foreach ($results->resultObject as $routeInfo) {
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
            $results = DataAdapter::DefaultPDOExecuteAndRead($responseTypeQuery, null, "Survey_Data");
//            $results = DataAdapter::DefaultExecuteAndRead($responseTypeQuery, "Survey_Data");

            $responseTypes = array();
            foreach ($results->resultObject as $responseInfo) {
                $responseArray['responseid'] = trim($responseInfo->responseid);
                $responseArray['response_name'] = trim($responseInfo->response_name);
                $responseTypes[] = $responseArray;
            }
            $returnArray['responseTypes'] = $responseTypes;
        }


        if(isset($_POST['q_type'])) {
//question type
            $questionTypeQuery = 'SELECT q_typeid, q_type_text, "q_typeDesc" FROM q_type;';
            $results = DataAdapter::DefaultPDOExecuteAndRead($questionTypeQuery, null, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($questionTypeQuery, "Survey_Data");
//            Log::toFile(print_r($results, true));

            $questionTypes = array();
            foreach ($results->resultObject as $questionInfo) {
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
            $results = DataAdapter::DefaultPDOExecuteAndRead($dcTypeQuery, null, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($dcTypeQuery, "Survey_Data");

            $dcTypeTypes = array();
            foreach ($results->resultObject as $dcTypeInfo) {
                $dcTypeType['dctypeid'] = trim($dcTypeInfo->dctypeid);
                $dcTypeType['dc_type_title'] = trim($dcTypeInfo->dc_type_title);
                $dcTypeTypes[] = $dcTypeType;
            }
            $returnArray['dcTypes'] = $dcTypeTypes;

        }

        if(isset($_POST['dublincore_format'])) {
//dcFormat type
            $dcFormatQuery = "SELECT dcformatid, dc_format_title FROM dublincore_format;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($dcFormatQuery, null, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($dcFormatQuery, "Survey_Data");

            $dcFormatTypes = array();
            foreach ($results->resultObject as $dcFormatInfo) {
                $dcFormatArray['dcformatid'] = trim($dcFormatInfo->dcformatid);
                $dcFormatArray['dc_format_title'] = trim($dcFormatInfo->dc_format_title);
                $dcFormatTypes[] = $dcFormatArray;
            }
            $returnArray['dcFormatTypes'] = $dcFormatTypes;

        }

        if(isset($_POST['dublincore_language'])) {
//dcLang type
            $dcLangQuery = "SELECT dclangid, dc_language_title FROM dublincore_language;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($dcLangQuery, null, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($dcLangQuery, "Survey_Data");

            $dcLangTypes = array();
            foreach ($results->resultObject as $dcLangInfo) {
                $dcLangArray['dclangid'] = trim($dcLangInfo->dclangid);
                $dcLangArray['dc_language_title'] = trim($dcLangInfo->dc_language_title);
                $dcLangTypes[] = $dcLangArray;
            }
            $returnArray['dcLangs'] = $dcLangTypes;

        }

        if(isset($_POST['group_tags'])) {
//group_tags
            $group_tagsQuery = "SELECT tagid, tgroupid, tag_text FROM group_tags;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($group_tagsQuery, null, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($group_tagsQuery, "Survey_Data");

            $group_tagsTypes = array();
            foreach ($results->resultObject as $group_tagsInfo) {
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
            $results = DataAdapter::DefaultPDOExecuteAndRead($spatial_levelQuery, null, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($spatial_levelQuery, "Survey_Data");

            $spatial_levelTypes = array();
            foreach ($results->resultObject as $group_tagsInfo) {
                $spatial_levelArray['code'] = trim($group_tagsInfo->code);
                $spatial_levelArray['level'] = trim($group_tagsInfo->level);
                $spatial_levelTypes[] = $spatial_levelArray;
            }
            $returnArray['spatial_levels'] = $spatial_levelTypes;

        }

        if(isset($_POST['survey_frequency'])) {
//survey_frequency
            $survey_frequencyQuery = "SELECT svyfreqid, svy_frequency_title FROM survey_frequency;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($survey_frequencyQuery, null, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($survey_frequencyQuery, "Survey_Data");

            $survey_frequencyTypes = array();
            foreach ($results->resultObject as $survey_frequencyInfo) {
                $survey_frequencyArray['svyfreqid'] = trim($survey_frequencyInfo->svyfreqid);
                $survey_frequencyArray['svy_frequency_title'] = trim($survey_frequencyInfo->svy_frequency_title);
                $survey_frequencyTypes[] = $survey_frequencyArray;
            }
            $returnArray['survey_frequency'] = $survey_frequencyTypes;

        }

        if(isset($_POST['thematic_groups'])) {
//thematic_groups
            $thematic_groupsQuery = "SELECT tgroupid, grouptitle, groupdescription FROM thematic_groups;";
            $results = DataAdapter::DefaultPDOExecuteAndRead($thematic_groupsQuery, null, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($thematic_groupsQuery, "Survey_Data");

            $thematic_groupsTypes = array();
            foreach ($results->resultObject as $thematic_groupsInfo) {
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
        $results = DataAdapter::DefaultPDOExecuteAndRead($dcInfoQuery, null, "Survey_Data");

//        $results = DataAdapter::DefaultExecuteAndRead($dcInfoQuery, "Survey_Data");

//        Log::toFile(print_r($results, true));

        $addDCInfoArray = array();

        foreach ($results->resultObject as $dcInfo) {
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

        $surveyQuestionQuery = "select q.qid, q.questionnumber from questions q
        join survey_questions_link surql on lower(q.qid) = lower(surql.qid) where surql.surveyid=:SID;";

        $values = array(":SID" => $SID);

        $results = DataAdapter::DefaultPDOExecuteAndRead($surveyQuestionQuery, $values, "Survey_Data");

//        $results = DataAdapter::DefaultExecuteAndRead($surveyQuestionQuery, "Survey_Data");

//        Log::toFile(print_r($results, true));

        $allQuestionArray = array();

        foreach ($results->resultObject as $dcInfo) {
//            Log::toFile("Survey : " . print_r($surveyData, true));
            $questionArray['QuestionName'] = trim($dcInfo->questionnumber);
            $questionArray['QuestionID'] = trim($dcInfo->qid);
            $allQuestionArray[] = $questionArray;
        }

        $returnArray['questionData'] = $allQuestionArray;

        echo json_encode($returnArray);
    }

    function actioncheckRecordExists() {

        $recordID = "";
        if(isset($_POST['recordID'])) {
            $recordID = $_POST['recordID'];
        }

        $recordType = "";
        if(isset($_POST['recordType'])) {
            $recordType = $_POST['recordType'];
        }

        $returnArray = array();
        $checkRecordExistsQuery = "";

        switch ($recordType) {
            case "survey" :
                //check survey
                $checkRecordExistsQuery = "Select Count (surveyid) From survey Where surveyid=:recordID;";
                break;
            case "survey_dc" :
                //check dc exists
                $checkRecordExistsQuery = "Select Count (identifier) From dc_info Where identifier=:recordID;";
                break;
            case "survey_question" :
                // check question exists
                $checkRecordExistsQuery = "Select Count (qid) From questions Where qid=:recordID;";
                break;
            case "survey_response" :
                // check response
                $checkRecordExistsQuery = "Select Count (responseid) From responses Where responseid=:recordID;";
                break;
            case "response_table" :
                $checkRecordExistsQuery = "Select Count (qid) From responseTable Where qid=:recordID;";
        }

        $values = array(":recordID" => $recordID);

        $result = DataAdapter::DefaultPDOExecuteAndRead($checkRecordExistsQuery, $values, "Survey_Data");
//        $result = DataAdapter::DefaultExecuteAndRead($checkRecordExistsQuery, "Survey_Data");

        Log::toFile(print_r($result, true));
        $count = $result->resultObject[0]->count;

        if ($count > 0) {

            $returnArray['exists'] = true;
            $returnArray['success'] = true;
            $returnArray['msg'] = "Record with id " . trim($recordID) . " already exists.";

            $surveyid = "";
            switch ($recordType) {
                case "survey" :
                    $surveyid = $recordID;
                    break;

                case "survey_dc" :
                    $getSurveyForWIDQuery = "Select surveyid from survey where identifier=:recordID;";
                            $values = array(":recordID" => $recordID);
                       $result = DataAdapter::DefaultPDOExecuteAndRead($getSurveyForWIDQuery, $values, "Survey_Data");
//                    $result = DataAdapter::DefaultExecuteAndRead($getSurveyForWIDQuery, "Survey_Data");
                    $surveyid = trim( $result->resultObject[0]->surveyid );
                    break;

                case "survey_question" :
                    $getSurveyForWIDQuery = "Select surveyid from survey_questions_link where qid=:recordID;";
                            $values = array(":recordID" => $recordID);
                    $result = DataAdapter::DefaultPDOExecuteAndRead($getSurveyForWIDQuery, $values, "Survey_Data");

//                    $result = DataAdapter::DefaultExecuteAndRead($getSurveyForWIDQuery, "Survey_Data");
                    $surveyid = trim( $result->resultObject[0]->surveyid );
                    break;
            }

            $getProjectForRecordQuery = "Select p.projectid, projectname from surveyownership so,
            project p where so.surveyid=:surveyid and p.projectid=so.projectid;";

                    $values = array(":surveyid" => $surveyid);

            $result = DataAdapter::DefaultPDOExecuteAndRead($getProjectForRecordQuery, $values);

//            $result = DataAdapter::DefaultExecuteAndRead($getProjectForRecordQuery, "Geoportal");

            if (sizeof($result->resultObject) > 0) {
                $returnArray['projectid'] = $result->resultObject[0]->projectid;
                $returnArray['projectname'] = $result->resultObject[0]->projectname;
            }

        } else {
            $returnArray['exists'] = false;
            $returnArray['failure'] = true;
            $returnArray['msg'] = "No record with id " . $recordID . " found.";
        }

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

        Log::toFile(print_r($_POST, true));
        $project = "";
        if(isset($_POST['projectID'])) {
            $project = $_POST['projectID'];
        }

        $update = false;
        if(isset($_POST['update'])) {
            $update = true;
        }

        Log::toFile("update " . $update);
        $params['projectID'] = $project;
        if ( $update && RoleManager::hasPermission('updateRecordandDC', $params) ) {
            $allowed = true;
        } elseif (RoleManager::hasPermission('createRecordandDC', $params)) {
            $allowed = true;
        } else {
            $allowed = false;
        }

        $collectionstartdate = "now";
        $collectionenddate = "now";
        $dataproduct = "";
        $dataproductid = "";
        $short_title = "";
        $spatialdata = "false";

        if ( $allowed ) {

            if ( $update ) {

                $dbInsert = 'UPDATE survey
        SET survey_title=:surveyTitle, identifier=:identifier, datacollector=:surveyCollector,
        collectionstartdate=now(), collectionenddate=now(),
        moc_description=:surveyDataCollectionMethod, samp_procedure=:surveySamplingProcedure,
        collectionsituation=:surveyCollectionSituation, surveyfrequency=:surveyFrequency,
        surveystartdate=(cast(:surveyStartDate as timestamp)), surveyenddate=(cast(:surveyEndDate as timestamp)),
        des_weighting=:surveyWeighting, samplesize=:surveySampleSize, responserate=:surveyResponseRate,
        descriptionofsamplingerror=:surveySamplingError, dataproduct=:dataProduct, dataproductid=:dataProductID,
        location=:surveyLocation, link=:surveyURL, notes=:surveyNotes, user_id=:username,
        updated=now(), "long"=:long, short_title=:shortTitle,
        spatialdata=:spatialData WHERE surveyid=:surveyID;';

//                created shouldnt change
// created=(cast(:created as timestamp)),

            } else {

                $dbInsert = "INSERT INTO survey(
            surveyid, identifier, survey_title, datacollector, collectionstartdate,
            collectionenddate, moc_description, samp_procedure, collectionsituation,
            surveyfrequency, surveystartdate, surveyenddate, des_weighting,
            samplesize, responserate, descriptionofsamplingerror, dataproduct,
            dataproductid, location, link, notes, user_id, created, updated,
            long, short_title, spatialdata) VALUES (
    :surveyID, :identifier, :surveyTitle, :surveyCollector, now(), now(),
    :surveyDataCollectionMethod, :surveySamplingProcedure, :surveyCollectionSituation,
    :surveyFrequency, (cast(:surveyStartDate as timestamp)), (cast(:surveyEndDate as timestamp)),
    :surveyWeighting, :surveySampleSize,:surveyResponseRate, :surveySamplingError, :dataProduct, :dataProductID,
    :surveyLocation, :surveyURL, :surveyNotes,:username, now(), now(), :long, :shortTitle, :spatialData);";

            }
            Log::toFile($dbInsert);

            $values = array('surveyID' => $surveyID, 'identifier' => $identifier, 'surveyTitle' => $surveyTitle,
                'surveyCollector' => $surveyCollector,
//                'collectionStartDate' => $collectionstartdate, 'collectionEndDate' => $collectionenddate,
                'surveyDataCollectionMethod' => $surveyDataCollectionMethod,
                'surveySamplingProcedure' => $surveySamplingProcedure,
                'surveyCollectionSituation' => $surveyCollectionSituation,
                'surveyFrequency' => $surveyFrequency, 'surveyStartDate' => $surveyStart, 'surveyEndDate' => $surveyEnd,
                'surveyWeighting' => $surveyWeighting, 'surveySampleSize' => $surveySampleSize,
                'surveyResponseRate' => $surveyResponseRate, 'surveySamplingError' => $surveySamplingError,
                'dataProduct' => $dataproduct, 'dataProductID' => $dataproductid, 'surveyLocation' => $surveyLocation,
                'surveyURL' => $surveyURL, 'surveyNotes' => $surveyNotes, 'username' => $username,
//                'created' => $created,
//                'now' => $now,
                'long' => $long, 'shortTitle' => $short_title, 'spatialData' => $spatialdata);

            $resultObject = DataAdapter::DefaultPDOExecuteAndRead($dbInsert, $values, "Survey_Data");
//            $results = DataAdapter::DefaultExecuteAndRead($dbInsert, "Survey_Data");

            $returnArray['success'] = "true";
            $returnArray['surveyInsert'] = $dbInsert;
            $returnArray['changedRows'] = $resultObject->resultObject;

        } else {
            $returnArray['success'] = false;
            $returnArray['message'] = "You do not have permission to perform this action";
        }

        echo json_encode($returnArray);

    }




    function actionInsertDC (){
        $returnArray = array();

        $username = Yii::app()->user->getName();

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

        Log::toFile(print_r($_POST, true));
        $project = "";
        if(isset($_POST['projectID'])) {
            $project = $_POST['projectID'];
        }

        $update = false;
        if(isset($_POST['update'])) {
            $update = true;
        }

        Log::toFile("update " . $update);
        $params['projectID'] = $project;
        if ( $update && RoleManager::hasPermission('updateRecordandDC', $params) ) {
            $allowed = true;
        } elseif (RoleManager::hasPermission('createRecordandDC', $params)) {
            $allowed = true;
        } else {
            $allowed = false;
        }

        if ( $allowed ) {

            if ( $update ) {
                $dbInsert = "UPDATE dc_info Set identifier=:identifier, title=:title ,
                    creator=:creator , subject=:subject ,
                    description=:description ,
                    publisher=:publisher ,
                    contributor=:contributor, date=(cast(:date as timestamp)),
                    type=:type , format=:format ,
                    source=:source, language=:language ,
                    relation=:relation , coverage=:coverage ,
                    rights=:rights , user_id=:user_id ,
                    created=(cast(:created as timestamp)), updated=now()
                    WHERE identifier=:identifier;";

                $values = array(":identifier" => $identifier, ":title" => $title, ":creator" => $creator, ":subject" => $subject,
                    ":description" => $description, ":publisher" => $publisher,  ":contributor" => $contributor, ":date" => $date,
                    ":type" => $type, ":format" => $format,  ":source" => $source, ":language" => $language, ":relation" => $relation,
                    ":coverage" => $coverage, ":rights" => $rights, ":user_id" => $user_id, ":created" => $created);
            } else {


                $dbInsert = "INSERT INTO dc_info(
            identifier, title, creator, subject, description, publisher,
            contributor, date, type, format, source, language, relation,
            coverage, rights, user_id, created, updated) VALUES (identifier, :title , :creator ,
                 :subject , :description ,
                  :publisher , :contributor ,
                   (cast(:date as timestamp)), :type , :format ,
                    :source , :language ,
                     :relation , :coverage ,
                     :rights , :user_id, (cast(:created as timestamp)), now();)"; // . $updated;


                $values = array(":identifier" => $identifier, ":title" => $title, ":creator" => $creator,
                    ":subject" => $subject,
                    ":description" => $description, ":publisher" => $publisher, ":contributor" => $contributor,
                ":date" => $date, ":type" => $type, ":format" => $format, ":source" => $source,
                    ":language" => $language,
                ":relation" => $relation, ":rights" => $rights, ":coverage" => $coverage, ":user_id" => $user_id,
                    ":created" => $created
                );

            }

            Log::toFile($dbInsert);

            $result = DataAdapter::DefaultPDOExecuteAndRead($dbInsert, $values, "Survey_Data");

//            $results = DataAdapter::DefaultExecuteAndRead($dbInsert, "Survey_Data");

            $returnArray['success'] = $result->resultSuccess;
            $returnArray['dcInsert'] = $dbInsert;

        } else {
            $returnArray['success'] = false;
            $returnArray['message'] = "User permission error";
        }
        echo json_encode($returnArray);
    }

    function actioninsertResponse() {

        $questionID = "N/A";
        if(isset($_POST['questionID'])) {
            $questionID = $_POST['questionID'];
        }

        $responseID = "N/A";
        if(isset($_POST['responseID'])) {
            $responseID = $_POST['responseID'];
        }

        $responseType = "N/A";
        if(isset($_POST['responseType'])) {
            $responseType = $_POST['responseType'];
        }

        $responseText = "N/A";
        if(isset($_POST['responseText'])) {
            $responseText = $_POST['responseText'];
        }

        $responseTableID = "N/A";
        if(isset($_POST['responseTableID'])) {
            $responseTableID = $_POST['responseTableID'];
        }

        $responseChecks = "N/A";
        if(isset($_POST['responseChecks'])) {
            $responseChecks = $_POST['responseChecks'];
        }

        $responseVariables = "N/A";
        if(isset($_POST['responseVariables'])) {
            $responseVariables = $_POST['responseVariables'];
        }

        $responseRouting = "N/A";
        if(isset($_POST['responseRouting'])) {
            $responseRouting = $_POST['responseRouting'];
        }

        $routetype = "N/A";

        $username = "";
        $userObject = Yii::app()->user;
        $username = $userObject->getName();

        Log::toFile(print_r($_POST, true));
        $project = "";
        if(isset($_POST['projectID'])) {
            $project = $_POST['projectID'];
        }

        $update = false;
        if(isset($_POST['update'])) {
            $update = true;
        }

        Log::toFile("update " . $update);
        $params['projectID'] = $project;
        if ( $update && RoleManager::hasPermission('updateQuestionAndResponse', $params) ) {
            $allowed = true;
        } elseif (RoleManager::hasPermission('addResponseToSurvey', $params)) {
            $allowed = true;
        } else {
            $allowed = false;
        }

        if ( $allowed ) {

            $values = array(":responseid" => $responseID, ":responsetext" => $responseText,
            ":response_type" => $responseType, ":routetype" => $routetype, ":table_ids" => $responseTableID,
            ":computed_var" => $responseVariables, ":checks" => $responseChecks, ":route_notes" => $responseRouting,
            ":user_id" => $username);

            if ( $update ) {
                $dbQuery = "UPDATE responses
            SET responseid=:responseid, responsetext=:responsetext, response_type=:response_type, routetype=:routetype,
            table_ids=:table_ids, computed_var=:computed_var, checks=:checks, route_notes=:route_notes,
            user_id=:user_id, updated=now() WHERE responseid=:responseid;";

//                $resultObject = DataAdapter::DefaultPDOExecuteAndRead($dbQuery, $values, "Survey_Data");
                $result = DataAdapter::DefaultPDOExecuteAndRead($dbQuery, $values, "Survey_Data");
                $returnArray['success'] = $result->resultSuccess;

            } else {

                $dbQuery = "INSERT INTO responses(
            responseid, responsetext, response_type, routetype, table_ids,
            computed_var, checks, route_notes, user_id, created, updated)
            VALUES (:responseid, :responsetext, :response_type, :routetype, :table_ids,
            :computed_var, :checks, :route_notes, :user_id, now(), now());";

//                $insertResponseQuery = "INSERT INTO responses(
//                    responseid, responsetext, response_type, routetype, table_ids,
//                    computed_var, checks, route_notes, user_id, created, updated)
//                    VALUES ('" . $responseID . "', '" . $responseText . "', '" . $responseType . "', '" .
//                    $routetype . "', '" . $responseTableID . "', '" .
//                    $responseVariables . "', '" . $responseChecks . "', '" . $responseRouting . "', '" . $username .
//                    "', Timestamp 'now', Timestamp 'now');";
                Log::toFile($dbQuery);


                $returnArray['questionInsert'] = $dbQuery;

//                $results = DataAdapter::DefaultExecuteAndRead($dbQuery, "Survey_Data");
                $resultObject = DataAdapter::DefaultPDOExecuteAndRead($dbQuery, $values, "Survey_Data");

                $questionResponseLinkQuery = "INSERT INTO questions_responses_link( qid, responseid)
                  VALUES (:questionID, :responseID);";

                $values = array(":questionsID" => $questionID, ":responseID" => $responseID);
//                    $questionID . "', '" . $responseID . "');";

                $resultObject = DataAdapter::DefaultPDOExecuteAndRead($questionResponseLinkQuery, $values, "Survey_Data");
//                Log::toFile($questionResponseLinkQuery);
//                $results = DataAdapter::DefaultExecuteAndRead($questionResponseLinkQuery, "Survey_Data");

                $returnArray['resqueslink'] = $questionResponseLinkQuery;
                $returnArray['success'] = $resultObject->resultSuccess;
            }

        } else {
            $returnArray['success'] = false;
            $returnArray['message'] = "User permission error";
        }

        echo json_encode($returnArray);

    }

    function actioninsertResponseTable() {

        $QuestionSurveyID = "N/A";
        if(isset($_POST['QuestionSurveyID'])) {
            $QuestionSurveyID = $_POST['QuestionSurveyID'];
        }

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
            $QuestionNumber = trim($_POST['QuestionNumber']);
        }

        $QuestionText = "N/A";
        if(isset($_POST['QuestionText'])) {
            $QuestionText = trim($_POST['QuestionText']);
        }

        $QuestionNotesPrompts = "N/A";
        if(isset($_POST['QuestionNotesPrompts'])) {
            $QuestionNotesPrompts = trim($_POST['QuestionNotesPrompts']);
        }

        $QuestionVariable = "N/A";
        if(isset($_POST['QuestionVariable'])) {
            $QuestionVariable = trim($_POST['QuestionVariable']);
        }

        $QuestionThematicGroups = "N/A";
        if(isset($_POST['QuestionThematicGroups'])) {
            $QuestionThematicGroups = trim($_POST['QuestionThematicGroups']);
        }

        $QuestionThematicTags = "N/A";
        if(isset($_POST['QuestionThematicTags'])) {
            $QuestionThematicTags = trim($_POST['QuestionThematicTags']);
        }

        $QuestionType = "N/A";
        if(isset($_POST['QuestionType'])) {
            $QuestionType = trim($_POST['QuestionType']);
        }

        $QuestionLinkedFrom = "N/A";
        if(isset($_POST['QuestionLinkedFrom'])) {
            $QuestionLinkedFrom = trim($_POST['QuestionLinkedFrom']);
        }

        $QuestionSubOf = "N/A";
        if(isset($_POST['QuestionSubOf'])) {
            $QuestionSubOf = trim($_POST['QuestionSubOf']);
        }


        $project = "";
        if(isset($_POST['projectID'])) {
            $project = $_POST['projectID'];
        }


        $update = false;
        if(isset($_POST['update'])) {
            $update = true;
        }
        Log::toFile(print_r($_POST, true));
        Log::toFile($update);

        $params['projectID'] = $project;
        if ( $update && RoleManager::hasPermission('updateQuestionAndResponse', $params) ) {
            $allowed = true;
        } elseif (RoleManager::hasPermission('addQuestionsToSurvey', $params)) {
            $allowed = true;
        } else {
            $allowed = false;
        }

        $username = "";
        $userObject = Yii::app()->user;
        $username = $userObject->getName();

        if ( $allowed ) {

            if ( $update ) {
                $dbUpdateQuery = "UPDATE questions Set qid=:QuestionID , literal_question_text=:QuestionText ,
                    questionnumber=:QuestionNumber ,
                    thematic_groups=:QuestionThematicGroups ,
                    thematic_tags=:QuestionThematicTags ,
                    link_from=:QuestionLinkedFrom ,
                    subof=:QuestionSubOf , type=:QuestionType ,
                    variableid=:QuestionVariable , notes=:QuestionNotesPrompts , user_id=:username ,
                    updated=now() ,
                    qtext_index=to_tsvector('english', :QuestionText ) WHERE qid=:QuestionID;";

                $values = array(":QuestionID" => $QuestionID, ":QuestionText" => $QuestionText, ":QuestionNumber" => $QuestionNumber,
                ":QuestionThematicGroups" => $QuestionThematicGroups, ":QuestionThematicTags" => $QuestionThematicTags,
                    ":QuestionLinkedFrom" => $QuestionLinkedFrom, ":QuestionSubOf" => $QuestionSubOf, ":QuestionType" => $QuestionType,
                ":QuestionVariable" => $QuestionVariable, ":QuestionNotesPrompts" => $QuestionNotesPrompts, ":username" => $username);

                $results = DataAdapter::DefaultPDOExecuteAndRead($dbUpdateQuery, $values, "Survey_Data");
//                $results = DataAdapter::DefaultExecuteAndRead($dbUpdateQuery, "Survey_Data");

                $returnArray['questionInsert'] = $dbUpdateQuery;
                $returnArray['success'] = $results->resultSuccess;
            } else {

                $questionInsertQuery = "INSERT INTO questions(
            qid, literal_question_text, questionnumber, thematic_groups,
            thematic_tags, link_from, subof, type, variableid, notes, user_id,
            created, updated, qtext_index) VALUES (:QuestionID, :QuestionText , :QuestionNumber ,
                :QuestionThematicGroups ,
                :QuestionThematicTags ,
                :QuestionLinkedFrom ,
                :QuestionSubOf , :QuestionType ,
                :QuestionVariable ,
                :QuestionNotesPrompts , :username ,
                now(), now(), qtext_index=to_tsvector('english', :QuestionText ) );";

                $values = array( ":QuestionID" => $QuestionID,
                    ":QuestionText" => $QuestionText, ":QuestionNumber" => $QuestionNumber, ":QuestionThematicGroups" => $QuestionThematicGroups,
                    ":QuestionThematicTags" => $QuestionThematicTags, ":QuestionLinkedFrom" => $QuestionLinkedFrom,
                    ":QuestionSubOf" => $QuestionSubOf, ":QuestionType" => $QuestionType, ":QuestionVariable" => $QuestionVariable,
                    ":QuestionNotesPrompts" => $QuestionNotesPrompts, ":username" => $username,

                );

                Log::toFile($questionInsertQuery);
                $results = DataAdapter::DefaultPDOExecuteAndRead($questionInsertQuery, $values, "Survey_Data");

//                $results = DataAdapter::DefaultExecuteAndRead($questionInsertQuery, "Survey_Data");

                $surveyQuestionLinkQuery = "INSERT INTO survey_questions_link( surveyid, qid, pk)
                VALUES (:QuestionSurveyID , :QuestionID , 0);";

                $values = array(":QuestionSurveyID" => $QuestionSurveyID, ":QuestionID" => $QuestionID);

                $results = DataAdapter::DefaultPDOExecuteAndRead($surveyQuestionLinkQuery, $values, "Survey_Data");

//                $results = DataAdapter::DefaultExecuteAndRead($surveyQuestionLinkQuery, "Survey_Data");

                $returnArray['questionInsert'] = $questionInsertQuery;
                $returnArray['linkquestionsurveyquery'] = $surveyQuestionLinkQuery;
                $returnArray['success'] = $results->resultSuccess;
            }
        } else {
            $returnArray['success'] = false;
            $returnArray['message'] = "User permission error";
        }

        echo json_encode($returnArray);
    }

}
