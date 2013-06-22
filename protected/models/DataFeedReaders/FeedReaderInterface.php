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

    public function keywordSearch($keyword);

    public function getRemoteDataset($dataset);
}