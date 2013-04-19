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
    var regURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Register/Register';
    var accountURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Account/getMyDetails';
    var bioUpdateURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Account/UpdateMyDetails';
    var ChangePWURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Account/ChangePassword';
    var uploadHandler = '<? echo Yii::app()->baseUrl ?>/index.php?r=Upload/UploadFile';
    var VerifySearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Search/verifySearch';
    var simpleSearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Search/SimpleSearch';
    var qualSearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=Search/QualSimpleSearch';
    var advancedSearchURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdvancedSearch/AdvancedSearch';
    var advancedSearchMetaURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=AdvancedSearch/AdvancedSearchMeta';

    var BugUrl = '<? echo Yii::app()->baseUrl ?>/index.php?r=Message/submitAbug';
    var SpatialUnitsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialUnits';
    var SpatialSubUnitsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialSubUnit';
    var SpatialRefSubUnitsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialRefSubUnit';
    var SpatialRefIndSubUnitsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getRefSpatialIndivudalUnits';
    var SpatialLabelsURL = '<? echo Yii::app()->baseUrl ?>/index.php?r=SpatialData/getSpatialLabel';
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

    var geoserverWMS = '<? echo variables::$geoserverRoot ?>';
    var geoportalAddr = '<? echo variables::$geoportalAddr ?>';


</script>




<?

?>