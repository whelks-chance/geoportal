<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 04/07/13
 * Time: 16:29
 * To change this template use File | Settings | File Templates.
 */
class LandmarkGov implements FeedReaderInterface
{

    public function keywordSearch($keyword)
    {

        $url = 'http://api.landmarkgovernment.co.uk/data/datasets/xml?';
        $output = RemoteDataController::curlURL($url);

        $decoded = simplexml_load_string($output);

        Log::toFile(print_r($decoded, true));
        $toReturn = array();
        foreach ($decoded->listnodes->listnode as $node) {
            Log::toFile($node->name);
            $thisNode['name'] = (string) $node->name;
            $thisNode['id'] = (string) $node->name;
            $thisNode["wiserd"] = "";
            $thisNode["wiserd_survey"] = "";
            $toReturn[] = $thisNode;
        }
        return $toReturn;
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
        // TODO: Implement getRemoteGeographies() method.
    }

    public function getRegionBreakdown($datasetID, $regionID)
    {
        // TODO: Implement getRegionBreakdown() method.
    }

    public function getFeedName()
    {
        return 'landmark.gov';
    }

    public function getRemoteDatasetDownloadURL($datasetID, $boundaryID, $measuresID, $format)
    {
        // TODO: Implement getRemoteDatasetDownloadURL() method.
    }
}
