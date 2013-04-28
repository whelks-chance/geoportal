<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 28/04/13
 * Time: 15:23
 * To change this template use File | Settings | File Templates.
 */

class RemoteDataController extends Controller {

    public function actiongetRemoteSources() {
        $remoteSources = array();

        $source = array();
        $source['name'] = "nomisweb";
        $source['url'] = "http://www.nomisweb.co.uk/api/v01/";
        $source['wordsearch'] = "http://www.nomisweb.co.uk/api/v01/dataset/def.sdmx.json?search=*wiserd_dataportal_replace_me*";

        $remoteSources[] = $source;

        echo json_encode($remoteSources);
    }

    public function actiondoKeywordSearch() {

        $keyword = $_POST['Keyword'];

        $url = "http://www.nomisweb.co.uk/api/v01/dataset/def.sdmx.json?search=*" . $keyword . "*";
//        $response = http_get(url, array("timeout"=>1), $info);
//        print_r($info);

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

        $decoded = json_decode($output, true);

        echo json_encode($decoded);
    }

}