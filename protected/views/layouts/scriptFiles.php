<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 14/02/13
 * Time: 13:17
 * To change this template use File | Settings | File Templates.
 */


include('javascript.php');
include('variables.php')
?>

<script charset="utf-8" type="text/javascript">

    var captureURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Register/CaptureUserEvalDetails';
    var loginURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Account/LogOn';
    var ResetPWURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Account/ResetPW';
    var regURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Register/Register';
    var checkNameFreeURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Register/checkNameFree';

    var blogRSSUrl = '<? echo Yii::app()->baseUrl ?>/index.php?r=Site/getBlogRSS';

    var accountURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Account/getMyDetails';
    var bioUpdateURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Account/UpdateMyDetails';
    var ChangePWURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Account/ChangePassword';
    var uploadHandler = '<? echo Yii::app()->baseUrl ?>/index.php?r=Upload/UploadFile';
    var VerifySearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Search/verifySearch';
    var simpleSearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Search/SimpleSearch';
    var qualSearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Search/QualSimpleSearch';
    var advancedSearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdvancedSearch/AdvancedSearch';
    var advancedSpatialSearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdvancedSearch/AdvancedSpatialSearch';

    var advancedSearchMetaURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdvancedSearch/AdvancedSearchMeta';
    var remoteSourceURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/getRemoteSources';
    var remoteDataKeywordSearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/doKeywordSearch';
    var remoteDataSetURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/getRemoteDataset';
    var remoteRegionURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/getRegionBreakdown';
    var remoteGetDataURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/getRemoteData';
    var remoteGetVariablesURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/getRemoteVariables';
    var remoteGetGeographiesURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/getRemoteDatasetGeographies';
    var remoteDatasetDownloadURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/getRemoteDatasetDownloadUrl';


    var findQuestionLinkURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/findQuestionLinks';

    var getWiserdQuestions = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdvancedSearch/getKeywordQuestions';
    var linkRemoteQuestion = '<? echo Yii::app()->baseUrl ?>/index.php?r=RemoteData/linkRemoteQuestion';

    var BugUrl = '<? echo Yii::app()->baseUrl ?>/index.php?r=Message/submitAbug';
    var SpatialUnitsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialUnits';
    var SpatialSubUnitsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialSubUnit';
    var SpatialRefSubUnitsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialRefSubUnit';
    var SpatialRefIndSubUnitsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getRefSpatialIndivudalUnits';
    var SpatialLabelsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialLabel';
    var createLegendURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/createLegend';
    var createLayerLogoURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/createLayerLogo';

    var SpatialSearch = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/SpatialSearch';
    var VerifySpatialSearch = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/VerifySpatialSearch';
    var DynamicSLD = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/DynamicSLD';
    var ChoroURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getChoroFields';
    var SpatialMinMax = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getMinMax';
    var genSpatialDataURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/GenerateSpatialData';
    var QmetaURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getQMetaDataRecords';
    var SmetaURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getSMetaDataRecords';
    var RmetaURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getRMetaDataRecords';
    var DCmetaURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getDCMetaDataRecords';
    var QDCmetaURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getQDCMetaDataRecords';
    var responseTableURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getResponseTable';
    var fieldsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getFields';
    var QuestionsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Search/getQuestions';
    var genQualSpatialDataURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/GenerateQualSpatialData';
    var QualWordsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getQualWordCounts';
    var getPlaces = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getPlaces';
    var getTagsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=MetaData/getCloud';
    var getSpatialDataSets = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialDataSets';

    var getTaggingTags = '<? echo Yii::app()->baseUrl ?>/index.php?r=Tagging/getTags';
    var createTaggingMetadata = '<? echo Yii::app()->baseUrl ?>/index.php?r=Tagging/createTaggingMetadata';
    var getTimeTicks = '<? echo Yii::app()->baseUrl ?>/index.php?r=Tagging/getTimeNowTicks';
    var saveTaggingMetadata = '<? echo Yii::app()->baseUrl ?>/index.php?r=Tagging/saveTaggingMetadata';

    var dataOptionLists = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/getDataEntryOptionLists';
    var surveyVisibility = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/getUserProjectData';
    var addSurveyToProject = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/addSurveyToProject';
    var changeSurveyVisibility = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/changeSurveyVisibility';
    var createProject = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/createProject';
    var addUserToProject = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/addUserToProject';
    var addUserToRole = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/addUserToRole';

    var getUsersProjects = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/getUsersProjects';
    var insertDC = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/insertDC';
    var insertSurvey = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/insertSurvey';
    var insertQuestion = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/insertQuestion';
    var insertResponse = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/insertResponse';
    var getDCids = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/getDCinfo';
    var getSurveyQuestions = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/getSurveyQuestions';
    var checkRecordExists = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/checkRecordExists';

    var buildNewSurveyLinks = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdminMetadata/buildNewSurveyLinks';

    var geoserverWMS = '<? echo variables::$geoserverRoot ?>';
    var geoportalAddr = '<? echo variables::geoportalAddr() ?>';


</script>




<?

?>