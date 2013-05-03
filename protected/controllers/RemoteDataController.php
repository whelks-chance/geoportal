<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 28/04/13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */

class RemoteDataController extends Controller {

    public static function curlURL($url) {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        return $output;
    }

    public function actiongetRemoteSources() {
        $remoteSources = array();

        $source = array();
        $source['name'] = "nomisweb";
        $source['url'] = "http://www.nomisweb.co.uk/api/v01/";
        $source['wordsearch'] = "http://www.nomisweb.co.uk/api/v01/dataset/def.sdmx.json?search=*wiserd_dataportal_replace_me*";

        $remoteSources[] = $source;

        echo json_encode($remoteSources);
    }

    public function actiongetRemoteData() {
        $boundaryID = $_POST['BoundaryID'];
        $datasetID = $_POST['DatasetID'];
        $measuresID = $_POST['MeasuresID'];


        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $datasetID . ".data.json?";
        $url .= "geography=" . $boundaryID;
        $url .= "&&measures" . $measuresID;

//        Log::toFile($url);

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        echo json_encode($decoded);

    }

    public function actiongetRegionBreakdown() {
        //        https://www.nomisweb.co.uk/api/v01/dataset/NM_621_1.data.csv?geography=2092957700
//        https://www.nomisweb.co.uk/api/v01/dataset/NM_621_1/geography/2092957700.def.sdmx.json
        $regionID = $_POST['RegionID'];
        $datasetID = $_POST['DatasetID'];

        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $datasetID . "/geography/" . $regionID . ".def.sdmx.json";

//        Log::toFile($url);

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

//        Log::toFile(print_r($decoded, true));

        $allFound = array();

        if($decoded->structure->codelists != null){

            foreach($decoded->structure->codelists->codelist as $codelist) {
                foreach($codelist->code as $code) {

//                    $typeID = "";
//
//                    foreach($code->annotations->annotation as $region) {
//
//                        if ( $region->annotationtitle == "TypeCode") {
//                            $typeID = $region->annotationtext;
//                        }
//
//                    }

                    $regionName = $code->description->value;

                    $foundRegion = array();
                    $foundRegion["id"] = $code->value;
                    $foundRegion["name"] = $regionName;

                    $allFound[] = $foundRegion;
                }
            }
        }

        echo json_encode($allFound);
    }

    public function actiongetRemoteDataset() {

        $dataset = $_POST['Dataset'];

        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $dataset . "/geography.def.sdmx.json";

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        $regions = array();

        if($decoded->structure->codelists != null){

            foreach($decoded->structure->codelists->codelist as $codelist) {
                foreach($codelist->code as $code) {

                    $regionName = $code->description->value;

                    $foundRegion = array();
                    $foundRegion["id"] = $code->value;
                    $foundRegion["name"] = $regionName;

                    $regions[] = $foundRegion;
                }
            }
        }

        //variables info

        $url = "https://www.nomisweb.co.uk/api/v01/dataset/" . $dataset . "/measures.def.sdmx.json";

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

        $measures = array();

        if($decoded->structure->codelists != null){

            foreach($decoded->structure->codelists->codelist as $codelist) {
                foreach($codelist->code as $code) {

                    $measureName = $code->description->value;

                    $foundMeasure = array();
                    $foundMeasure["id"] = $code->value;
                    $foundMeasure["name"] = $measureName;

                    $measures[] = $foundMeasure;
                }
            }
        }

        $allFound['regions'] = $regions;
        $allFound['measures'] = $measures;

        echo json_encode($allFound);
    }

    public function actiondoKeywordSearch() {

        $keyword = $_POST['Keyword'];

        $url = "http://www.nomisweb.co.uk/api/v01/dataset/def.sdmx.json?search=*" . $keyword . "*";

        $output = RemoteDataController::curlURL($url);

        $decoded = json_decode($output, false);

//        Log::toFile(print_r($decoded, true));

        $allFound = array();

        if($decoded->structure->keyfamilies != null){

            foreach($decoded->structure->keyfamilies->keyfamily as $family) {
                $foundWord = array();
                $foundWord["id"] = $family->id;
                $foundWord["name"] = $family->name->value;

                $allFound[] = $foundWord;
            }
        }

        echo json_encode($allFound);
    }

}