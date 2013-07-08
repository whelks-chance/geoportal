<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 22/06/13
 * Time: 11:44
 * To change this template use File | Settings | File Templates.
 */

interface FeedReaderInterface
{

    public function getFeedName();

    public function keywordSearch($keyword);

    public function getRemoteDataset($datasetID, $boundaryID, $measuresID, $recordLimit, $recordOffset);

    public function getRemoteVariables($datasetID);

    public function getRemoteGeographies($datasetID, $topGeography);

    public function getRegionBreakdown($datasetID, $regionID);

    public function getRemoteDatasetDownloadURL($datasetID, $boundaryID, $measuresID, $format);

}