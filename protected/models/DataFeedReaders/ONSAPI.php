<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 04/11/13
 * Time: 19:01
 * To change this template use File | Settings | File Templates.
 */

class ONSAPIReader implements  FeedReaderInterface {

    public function getFeedName()
    {
        return "ONS API";
    }

    public function keywordSearch($keyword)
    {
        $url = "http://data.ons.gov.uk/ons/api/data/concepts?context=Census&apikey=nXEq4Q1NvH&find=" . $keyword;

        $urlArray = explode('wiserd_dataportal_replace_me', $url);

        $searchURL = $urlArray[0] . $keyword;

        if(sizeof($urlArray) > 1) {
            $searchURL .= $urlArray[1];
        }

        $output = RemoteDataController::curlURL($searchURL);

        Log::toFile(print_r($output, true));

        $decoded = simplexml_load_string($output);

        Log::toFile(print_r($decoded, true));

        $returnArray = array();
        foreach ($decoded->conceptList->concept as $concept) {
            $foundWord = array();
            $foundWord["id"] = (string)$concept->id;
            $foundWord["name"] = (string)$concept->names->name[0];

            $returnArray[] = $foundWord;
        }

        return $returnArray;
    }

    public function getRemoteDataset($datasetID, $boundaryID, $measuresID, $recordLimit, $recordOffset)
    {
        // TODO: Implement getRemoteDataset() method.
    }

    public function getRemoteVariables($datasetID)
    {
        // TODO: Implement getRemoteVariables() method.
    }

    public function getRemoteGeographies($datasetID, $topGeography)
    {
        if ($topGeography === "") {
            $topGeography = "K04000001"; //England and Wales from ONS geogs, tidier than ness!!
        }

        $datasetID = "QS418EW";
        $url = "http://data.ons.gov.uk/ons/api/data/hierarchy/" . $datasetID .
            "/parent/" . $topGeography ."?context=Census&apikey=nXEq4Q1NvH&geog=2011WARDH&levels=3";

        Log::toFile($url);

        $output = RemoteDataController::curlURL($url);

        $regionXML = simplexml_load_string($output);

        Log::toFile(print_r($regionXML, true));


        $regions = array();

        if($regionXML->Areas != null){

            foreach($regionXML->geographyList->item as $area) {

                $foundMeasure = array();
                $foundMeasure["id"] = (string) $area->itemCode;

//                $displayName = (string) $variableType->Name;
//                $displayUnit = (string) $variableType->MeasurementUnit->Name;

                $foundMeasure["name"] = (string) $area->labels->label[0];

                $regions[] = $foundMeasure;

            }
        }

        $allFound['regions'] = $regions;

        return $allFound;
    }

    public function getRegionBreakdown($datasetID, $regionID)
    {
        // TODO: Implement getRegionBreakdown() method.
    }

    public function getRemoteDatasetDownloadURL($datasetID, $boundaryID, $measuresID, $format)
    {
        // TODO: Implement getRemoteDatasetDownloadURL() method.
    }
}