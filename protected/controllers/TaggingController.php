<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 17/07/13
 * Time: 14:31
 * To change this template use File | Settings | File Templates.
 */

class TaggingController extends Controller{


    public function actiongetTimeNowTicks() {
        $today = date("Y-m-d H:i:s");
        $micro = microtime(true);
        $str = str_replace(".", '', $micro);

        $times = array();
        $times['micro'] = $str;
        $times['format'] = $today;

        if(Yii::app()->user->checkAccess('user'))
        {
            $times['admin'] = "looks like you're a regular user";
        }

        echo json_encode($times);
    }

    public function actiongetTags() {

        $taggingText = "";
        if(isset($_POST['tagText'])) {
            $taggingText = $_POST['tagText'];
        }

        $cnt = TaggingController::WordCount($taggingText);

        echo (json_encode($cnt));
//        $cnt = WordCount(removeAdj(Text));

//        Dim DT As New DataTable
//        DT.Columns.Add(New DataColumn("Word", System.Type.GetType("System.String")))
//        DT.Columns.Add(New DataColumn("Count", System.Type.GetType("System.Double")))
//
//
//        For Each wrd As System.Collections.Generic.KeyValuePair(Of String, Integer) In cnt
//            If wrd.Value > 5 Then
//                Dim row As DataRow = DT.NewRow()
//                row.Item(0) = wrd.Key
//                row.Item(1) = wrd.Value.ToString()
//                DT.Rows.Add(row)
//            End If
//            Next
//
//
//        wrdStore.DataSource = DT
//        wrdStore.DataBind()
//
//        winWrdCnt.Show()


    }

    public function actioncreateTaggingMetadata() {

        define("RDFAPI_INCLUDE_DIR", Yii::app()->basePath . '/models/RDFPHP/api/');
        Log::toFile(RDFAPI_INCLUDE_DIR);

        Yii::import('application.models.RDFPHP.api.*');
        if( ! require_once("RdfAPI.php") ) {
            Log::toFile("failed to require RdfAPI.php");
        }

        if( ! include_once("RdfAPI.php") ) {
            Log::toFile("failed to include RdfAPI.php");
        }

        $taggingText = "";
        if(isset($_POST['tagText'])) {
            $taggingText = $_POST['tagText'];
        }


        $openCalais = new OpenCalais2();
//        $tags = $openCalais->getTags("", $taggingText);
        $calaisModel = $openCalais->textToMemModel($taggingText);
        $tags = $openCalais->getAllTags($calaisModel);
        $geoWords = $openCalais->getGeoWords($tags);




        define("ALCHEMYAPI_INCLUDE_DIR", Yii::app()->basePath . '/models/alchemyapi/');
        Log::toFile(ALCHEMYAPI_INCLUDE_DIR);

        Yii::import('application.models.alchemyapi.*');
        if( ! require_once("alchemyapi.php") ) {
            Log::toFile("failed to require alchemyapi.php");
        }

        if( ! include_once("alchemyapi.php") ) {
            Log::toFile("failed to include alchemyapi.php");
        }

        $alchemyapi = new AlchemyAPI();
        $response = $alchemyapi->entities('text', $taggingText, array('sentiment'=>1));

        $alchamyEntities = array();
        if ($response['status'] == 'OK') {
            foreach ($response['entities'] as $entity) {

                $taggingEntity = array();
                $taggingEntity['name'] = $entity['text'];
                $taggingEntity['type'] = $entity['type'];
                $taggingEntity['latitude'] = "";
                $taggingEntity['longitude'] = "";
                $taggingEntity['save'] = True;

                if(array_key_exists("disambiguated", $entity)) {
                    if(array_key_exists("geo", $entity["disambiguated"])) {
                        $alchamyEntities[] = $entity;
                        $exploded = explode(" ", $entity['text']);
                        $geoWords[] = strtolower($exploded[0]);
                        Log::toFile("entity " . print_r($entity, true));

                        $geos = explode(" ", $entity['disambiguated']['geo']);

                        $taggingEntity['latitude'] = $geos[0];
                        $taggingEntity['longitude'] = $geos[1];
                    }
                }
                $tags[] = $taggingEntity;
            }
        } else {
            Log::toFile('Error in the entity extraction call: ' . $response['statusInfo']);
        }


        $response = $alchemyapi->concepts('text', $taggingText, null);
        $alchamyConcepts = array();
        if ($response['status'] == 'OK') {
            foreach ($response['concepts'] as $concept) {

                $taggingConcept = array();
                $taggingConcept['name'] = $concept['text'];
                $taggingConcept['type'] = "Concept";
                $taggingConcept['latitude'] = "";
                $taggingConcept['longitude'] = "";
                $taggingConcept['save'] = True;

                if(array_key_exists("geo", $concept)) {
                    $alchamyConcepts[] = $concept;
                    $geoWords[] = strtolower($concept['text']);
                    Log::toFile("concept " . print_r($concept, true));

                    $geos = explode(" ", $concept['geo']);

                    $taggingConcept['latitude'] = $geos[0];
                    $taggingConcept['longitude'] = $geos[1];
                }

                $tags[] = $taggingConcept;
            }
        } else {
            Log::toFile('Error in the concept tagging call: ' . $response['statusInfo']);
        }

        $cnt = TaggingController::WordCount($taggingText, $geoWords);

        $metadata = array();

        $tsquery = "Select to_tsvector('english', :doc)";
        $values = array(":doc" => $taggingText);
        $tsdata = DataAdapter::DefaultPDOExecuteAndRead($tsquery, $values, "Qual_Data");

        $metadata['tsvector'] = $tsdata->resultObject[0];
        $metadata['wordCounts'] = $cnt;
        $metadata['success'] = true;

        $tagJsonMess = "";
        foreach($tags as $tag) {

            $geoObject = array();
            $geoObject['Name'] = $tag['name'];
            $geoObject['lat'] = "";
            $geoObject['lon'] = "";
            $geoObject['Occurances'] = 1;


            if(array_key_exists("latitude", $tag) && $tag['latitude'] != "") {
                $geoObject['lat'] = $tag['latitude'];
                $geoObject['lon'] = $tag['longitude'];
            }

            foreach($cnt['counts'] as $wordCount) {
                if($wordCount['word'] == strtolower($tag['name'])) {
                    $geoObject['Occurances'] = $wordCount['count'];

                    $data = array();
                    $page = 1;
                    foreach($cnt['countInPage'] as $pageArray) {

                        $countInPage = 0;
                        if(array_key_exists(strtolower($tag['name']), $pageArray)) {
                            $countInPage = $pageArray[strtolower($tag['name'])];
                        }

                        $pageData['page'] = "Page " . $page;
                        $pageData['count'] = $countInPage;
                        $data[] = $pageData;
                        $page++;

                    }

                    $wordStats = array();
                    $wordStats['name'] = $tag['name'];
                    $wordStats['data'] = $data;
                    $geoObject['wordStats'] = $wordStats;

                    $tagJsonMess .= (json_encode($geoObject) . ",");

                    break;
                }
            }

        }

        $tags['length'] = sizeof($tags);
        $metadata['tags'] = $tags;
        $metadata['jsonmess'] = "[" . $tagJsonMess . "]";

//        $metadata['geowords'] = $geoWords;
//        $metadata['alchamyEntities'] = $alchamyEntities;
//        $metadata['alchamyConcepts'] = $alchamyConcepts;

        echo (json_encode($metadata));
    }


    Public function WordCount($str, $importantWords = array()) {

//        $importantWords = array();

        $counts = array();

        $str = strtolower($str);

        $remove = array("\n", "\r\n", "\r", "<p>", "</p>", "<h1>", "</h1>", ".", ",", "\"", ":");
        $str = str_replace($remove, ' ', $str);

        $str = preg_replace("/[^A-Za-z ]/", '', $str);

        $stringArray = explode(" ", $str);

        $ignoreWords = stopwords::getStopWords();

        foreach ($stringArray as $word) {
            if (in_array(($word), $ignoreWords)) {

            } else {
                if( strlen($word) > 2) {
                    if (array_key_exists($word, $counts)){
                        $counts[$word] = $counts[$word] +1;
                    } else {
                        $counts[$word] = 1;
                    }
                }
            }
        }

        $popularWords = array();
        $frequencyModifier = sizeof($stringArray) / sizeof($counts) * 0.9;
        foreach ($counts as $key=>$value) {
            if($counts[$key] > $frequencyModifier or in_array($key, $importantWords)) {
                $word = array();
                $word['word'] = $key;
                $word['count'] = $value;
                $word['save'] = True;
                $popularWords[] = $word;

                $importantWords[] = $key;
            }
        }


        $currentPage = 1;
        $pages = array();
        $wordInPageCount = 0;

        for ($i = 0; $i < sizeof($stringArray); $i++) {

            $word = $stringArray[$i];
            if (in_array(($word), $ignoreWords)) {

            } else {
                if( in_array($word, $importantWords)) {

                    if (!array_key_exists($currentPage, $pages)){
                        $pages[$currentPage] = array();
                    }

                    if (array_key_exists($word, $pages[$currentPage])){
                        $pages[$currentPage][$word] = $pages[$currentPage][$word] +1;
                    } else {
                        $pages[$currentPage][$word] = 1;
                    }
                }
            }
            if($wordInPageCount > 500) {
                $wordInPageCount = 0;
                $currentPage++;

            }
            $wordInPageCount++;
        }



        $returnArray = array();
        $returnArray['counts'] = $popularWords;
        $returnArray['countInPage'] = $pages;
        Return $returnArray;
    }

    public function actionsaveTaggingMetadata() {
        Log::toFile("tagging : " . print_r($_POST, true));
//        echo '{"wordCounts":{"counts":[{"word":"united","count":3,"save":true},{"word":"nations","count":5,"save":true},{"word":"organization","count":4,"save":true},{"word":"international","count":5,"save":true},{"word":"security","count":3,"save":true},{"word":"economic","count":3,"save":true},{"word":"social","count":3,"save":true},{"word":"world","count":5,"save":true},{"word":"agencies","count":3,"save":true},{"word":"council","count":3,"save":true},{"word":"york","count":1,"save":true},{"word":"geneva","count":1,"save":true},{"word":"nairobi","count":1,"save":true},{"word":"vienna","count":1,"save":true}]}}';

//        echo '{"tags":{"0":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/Position","type":"Position","name":"General"},"1":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/er\/Geo\/City","type":"City","docId":"http:\/\/d.opencalais.com\/dochash-1\/a9704e94-7a3d-3baa-95c7-9b6b2aadaf2f","subject":"http:\/\/d.opencalais.com\/genericHasher-1\/7e4899ce-0b04-3b6d-b654-619d756d9bf5","name":"Vienna,Austria","shortname":"Vienna","containedbycountry":"Austria","latitude":"48.20875","longitude":"16.372583"},"2":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/City","type":"City","name":"Vienna"},"3":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/Position","type":"Position","name":"Secretary-General"},"4":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/er\/Geo\/City","type":"City","docId":"http:\/\/d.opencalais.com\/dochash-1\/a9704e94-7a3d-3baa-95c7-9b6b2aadaf2f","subject":"http:\/\/d.opencalais.com\/genericHasher-1\/dac01a6a-17b6-3d90-8c03-c3cc9a38a41c","name":"Nairobi,Nairobi Province,Kenya","shortname":"Nairobi","containedbystate":"Nairobi Province","containedbycountry":"Kenya","latitude":"-1.28333333333","longitude":"36.8166666667"},"5":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/Organization","type":"Organization","name":"United Nations","organizationtype":"governmental civilian","nationality":"N\/A"},"6":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/City","type":"City","name":"Geneva"},"7":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/er\/Geo\/City","type":"City","docId":"http:\/\/d.opencalais.com\/dochash-1\/a9704e94-7a3d-3baa-95c7-9b6b2aadaf2f","subject":"http:\/\/d.opencalais.com\/genericHasher-1\/a7bb1c56-9579-350a-8f99-e98c0fe18a45","name":"Geneva,Canton of Geneva,Switzerland","shortname":"Geneva","containedbystate":"Canton of Geneva","containedbycountry":"Switzerland","latitude":"46.2","longitude":"6.15"},"8":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/Organization","type":"Organization","name":"Economic and Social Council","organizationtype":"governmental civilian","nationality":"N\/A"},"9":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/Position","type":"Position","name":"officer"},"10":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/City","type":"City","name":"York"},"11":{"22-rdf-syntax-ns#type":"http:\/\/s.opencalais.com\/1\/type\/em\/e\/City","type":"City","name":"Nairobi"},"12":{"name":"Organisation des Nations Unies","type":"Organization","latitude":"","longitude":""},"13":{"name":"United Nations Trusteeship Council","type":"Organization","latitude":"","longitude":""},"14":{"name":"League of Nations","type":"Organization","latitude":"","longitude":""},"15":{"name":"Economic and Social Council","type":"Organization","latitude":"","longitude":""},"16":{"name":"World War II","type":"FieldTerminology","latitude":"","longitude":""},"17":{"name":"World Health Organization","type":"Organization","latitude":"","longitude":""},"18":{"name":"economic development","type":"FieldTerminology","latitude":"","longitude":""},"19":{"name":"Security Council","type":"Organization","latitude":"","longitude":""},"20":{"name":"United Nations Children\'s Fund","type":"Organization","latitude":"","longitude":""},"21":{"name":"civil rights","type":"FieldTerminology","latitude":"","longitude":""},"22":{"name":"human rights","type":"FieldTerminology","latitude":"","longitude":""},"23":{"name":"International Court of Justice","type":"Organization","latitude":"52.0866","longitude":"4.2955"},"24":{"name":"General Assembly","type":"Organization","latitude":"","longitude":""},"25":{"name":"Non-governmental organizations","type":"FieldTerminology","latitude":"","longitude":""},"26":{"name":"South Korea","type":"Country","latitude":"","longitude":""},"27":{"name":"New York City","type":"City","latitude":"40.71666666666667","longitude":"-74.0"},"28":{"name":"WFP","type":"Organization","latitude":"","longitude":""},"29":{"name":"Geneva","type":"City","latitude":"","longitude":""},"30":{"name":"principal","type":"JobTitle","latitude":"","longitude":""},"31":{"name":"official","type":"JobTitle","latitude":"","longitude":""},"32":{"name":"UNICEF","type":"Organization","latitude":"","longitude":""},"33":{"name":"Secretary-General","type":"JobTitle","latitude":"","longitude":""},"34":{"name":"Vienna","type":"City","latitude":"","longitude":""},"35":{"name":"officer","type":"JobTitle","latitude":"","longitude":""},"36":{"name":"Nairobi","type":"City","latitude":"","longitude":""},"37":{"name":"United Nations","type":"Concept","latitude":"","longitude":""},"38":{"name":"United Nations General Assembly","type":"Concept","latitude":"","longitude":""},"39":{"name":"Human rights","type":"Concept","latitude":"","longitude":""},"40":{"name":"World Health Organization","type":"Concept","latitude":"","longitude":""},"41":{"name":"World War II","type":"Concept","latitude":"","longitude":""},"42":{"name":"United Nations Economic and Social Council","type":"Concept","latitude":"","longitude":""},"43":{"name":"League of Nations","type":"Concept","latitude":"","longitude":""},"44":{"name":"World Food Programme","type":"Concept","latitude":"","longitude":""},"length":45}}';

        $returnArray = array();
        $returnArray['success'] = true;
        echo json_encode($returnArray);

    }
}