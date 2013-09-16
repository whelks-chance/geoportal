<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 04/09/13
 * Time: 15:46
 * To change this template use File | Settings | File Templates.
 */

class RoleManager {

    public static function buildAuthHierachy() {
        /*
         * Edit this function and run once when new roles are needed.
         * Creates a hierachy in protected/data/auth.php
         */


        $auth=Yii::app()->authManager;

        $userRole=$auth->createRole('user');
        $projectUserRole=$auth->createRole('projectUser');
        $dataEntryRole=$auth->createRole('projectDataEntry');
        $hubAdminRole=$auth->createRole('hubAdmin');
        $superAdminRole=$auth->createRole('superAdmin');

//            1
        $auth->createOperation('searchPublicRecords','Search publically visibile records');
        $userRole->addChild('searchPublicRecords');

//            2
        $auth->createOperation('searchPrivateRecords','Search privately visibile records');
        $projectUserRole->addChild('searchPrivateRecords');


        $bizRule = 'return RoleManager::isProjectMember($params);';
//            3
        $auth->createOperation('createRecordandDC','Create records and Dublin Core');
        $dataEntryRole->addChild('createRecordandDC');
        $auth->createOperation('requestRecordVisibilityChange','Request change to records visibility');
        $dataEntryRole->addChild('requestRecordVisibilityChange');
        $auth->createOperation('addResponseToSurvey','Add response to Survey');
        $dataEntryRole->addChild('addResponseToSurvey');
        $auth->createOperation('addQuestionsToSurvey','Add questions to Survey');
        $dataEntryRole->addChild('addQuestionsToSurvey');
        $auth->createOperation('updateRecordandDC','Update record or records DC');
        $dataEntryRole->addChild('updateRecordandDC');
        $auth->createOperation('updateQuestionAndResponse','Update a question or response');
        $dataEntryRole->addChild('updateQuestionAndResponse');
        $auth->createOperation('deleteRecord','Delete a record');
        $dataEntryRole->addChild('deleteRecord');

//            4
        $auth->createOperation('createProject','Create a new Project', $bizRule);
        $hubAdminRole->addChild('createProject');
        $auth->createOperation('addUserToProject','Add a user to a Project');
        $hubAdminRole->addChild('addUserToProject');
        $auth->createOperation('moveSurveyToProject','Change project a survey belongs to');
        $hubAdminRole->addChild('moveSurveyToProject');
        $auth->createOperation('changeRecordVisibility','Change a records visibility');
        $hubAdminRole->addChild('changeRecordVisibility');

//            5
        $auth->createOperation('changeUserRole','Change a users role');
        $superAdminRole->addChild('changeUserRole');


        $superAdminRole->addChild('hubAdmin');
        $hubAdminRole->addChild('projectDataEntry');
        $dataEntryRole->addChild('projectUser');
        $projectUserRole->addChild('user');


        //fake role is assigned purely to check if this hierachy has been loaded.
        // The init method checks for this user/ role configuration
        $fakeRole=$auth->createRole('fakerole');
        $auth->assign('fakerole', 'fakeuser');


        $auth->assign('superAdmin', 'a');

        $auth->save();
    }

    static function init() {
        $auth=Yii::app()->authManager;
        if( $auth->checkAccess('fakerole', 'fakeuser') != true) {
            $auth->clearAll();
            RoleManager::buildAuthHierachy();
        }
    }


    public static function hasPermission($task, $params)
    {
        RoleManager::init();
        $userID = Yii::app()->user->getID();

        if($params == null) {
            $params = array();
        }

        $params['task'] = $task;
        if(Yii::app()->user->checkAccess($task, $params)) {
            return true;
        } else {

            Log::toFile("User : " . $userID . " failed perm check for task : " . $task . " : " . print_r($params, true));

            return false;
        }

//        if($task == "createRecordandDC") {
//            if(Yii::app()->user->checkAccess('createRecordandDC')) {
//
//                $projects = AdminMetadataController::getUsersProjects($userID);
//
//                foreach($projects as $project) {
//                    if($project['projectid'] == $params['projectID']) {
//                        return true;
//                    }
//                }
//                return false;
//            }
//        }

// assume you probably shouldn't allow it
        return false;
    }

    public static function getAuthorisedActions($userID)
    {
        RoleManager::init();

        if($userID == "") {
            return array();
        } else {

            $auth=Yii::app()->authManager;
//        return $auth->getAuthAssignments( $userID );
            return $auth->getAuthItems($userID);
        }
    }

    public static function getAllRoles()
    {
        RoleManager::init();
        $auth=Yii::app()->authManager;
        $roles = $auth->getRoles();

//        Log::toFile("Roles " . print_r($roles, true));

        $returnRoles = array();
        foreach($roles as $role=>$roleObject) {
            $returnRole['name'] = $role;
            $returnRoles[] = $returnRole;
        }
        return $returnRoles;
    }

    public static function removeAllAuthorisedActions($userID)
    {
        $auth=Yii::app()->authManager;
        $currentActions = $auth->getAuthAssignments( $userID );;

        foreach($currentActions as $action=>$actionObject) {
            $auth->revoke($action, $userID);
        }
    }

    public static function changeRole($userID, $roleName)
    {
        RoleManager::init();
        $auth=Yii::app()->authManager;

//        Log::toFile(print_r($auth->getRoles(), true));

        //Users can't hold more than a single role, this tidys up
        RoleManager::removeAllAuthorisedActions($userID);

        $auth->assign($roleName, $userID);
        $auth->save();

        $updateRoleQuery = "Update alphausersdetails set role=( Select roleid from roles where name='" .
            $roleName . "') where username='" . $userID . "';";
        $results2 = DataAdapter::DefaultExecuteAndRead($updateRoleQuery, "Geoportal");


    }

    public static function isProjectMember($params)
    {
        Log::toFile("bizrule for task : " . print_r($params, true));
        return true;
    }

    public static function initUser($username)
    {
        RoleManager::init();

        $getRoleQuery = "Select roles.name as rolename From alphausersdetails, roles WHERE username='"
            . $username . "' and role = roleid;";

        $results2 = DataAdapter::DefaultExecuteAndRead($getRoleQuery, "Geoportal");

        if (sizeof($results2) > 0) {
            RoleManager::changeRole($username, $results2[0]->rolename);

//            $auth=Yii::app()->authManager;
//            $auth->assign($results2[0]->rolename, $username);
//            $auth->save();
        }

    }


}