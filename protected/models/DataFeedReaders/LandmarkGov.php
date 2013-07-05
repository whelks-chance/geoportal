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
        foreach ($decoded->listnodes as $listnode) {
            Log::toFile($listnode->name);
            $toReturn[] = $listnode->name;
        }
        return $toReturn;
    }

    public function getRemoteDataset($datasetID, $boundaryID, $measuresID)
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
}
