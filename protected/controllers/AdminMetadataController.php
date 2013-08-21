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

    function actiongetDataEntryOptionLists() {

        $returnArray = array();

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

    function actionInsertDC(){
        Log::toFile(print_r($_POST, true));

        $identifier = "N/A";
        if(isset($_POST['identifier'])) {
            $identifier = $_POST['identifier'];
        }
        $title = "N/A";
        if(isset($_POST['title'])) {
            $title = $_POST['title'];
        }
        $creator = "N/A";
        if(isset($_POST['creator'])) {
            $creator = $_POST['creator'];
        }
        $subject = "N/A";
        if(isset($_POST['subject'])) {
            $subject = $_POST['subject'];
        }
        $description = "N/A";
        if(isset($_POST['description'])) {
            $description = $_POST['description'];
        }
        $publisher = "N/A";
        if(isset($_POST['publisher'])) {
            $publisher = $_POST['publisher'];
        }
        $contributor = "N/A";
        if(isset($_POST['contributor'])) {
            $contributor = $_POST['contributor'];
        }

        $dateObject = new DateTime('now');
        $date = $dateObject->format('Y-m-d H:i:s');
        if(isset($_POST['date'])) {
            $date = $_POST['date'];
        }
        $type = "N/A";
        if(isset($_POST['type'])) {
            $type = $_POST['type'];
        }
        $format = "N/A";
        if(isset($_POST['format'])) {
            $format = $_POST['format'];
        }
        $source = "N/A";
        if(isset($_POST['source'])) {
            $source = $_POST['source'];
        }
        $language = "N/A";
        if(isset($_POST['language'])) {
            $language = $_POST['language'];
        }
        $relation = "N/A";
        if(isset($_POST['relation'])) {
            $relation = $_POST['relation'];
        }
        $coverage = "N/A";
        if(isset($_POST['coverage'])) {
            $coverage = $_POST['coverage'];
        }
        $rights = "N/A";
        if(isset($_POST['rights'])) {
            $rights = $_POST['rights'];
        }
        $user_id = "N/A";
        if(isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
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

//        $results = DataAdapter::DefaultExecuteAndRead($dbInsert, "Survey_Data");
    }

}
