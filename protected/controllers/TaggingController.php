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
        echo microtime(true);
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
        require_once("RdfAPI.php");

        $taggingText = "";
        if(isset($_POST['tagText'])) {
            $taggingText = $_POST['tagText'];
        }

        $cnt = TaggingController::WordCount($taggingText);

        $openCalais = new OpenCalais2();
        $tags = $openCalais->getTags("", $taggingText);

        $metadata = array();

        $metadata['wordCounts'] = $cnt;
        $metadata['success'] = true;
        $metadata['tags'] = $tags;

        echo (json_encode($metadata));
    }


    Public function WordCount($str) {

        $counts = array();


        $word = "";
        $add = True;
        $ch = '';

        $str = strtolower($str);

//        for ($index = 0; $index < strlen($str) - 1; $index++) {
//            $c = $str{$index};
//            if (ctype_alpha($c)) {
//
//            }
//        }

        $remove = array("\n", "\r\n", "\r", "<p>", "</p>", "<h1>", "</h1>", ".", ",", "\"");
        $str = str_replace($remove, ' ', $str);
        $stringArray = explode(" ", $str);

        foreach ($stringArray as $word) {
            if( strlen($word) > 4) {
                if (array_key_exists($word, $counts)){
                    $counts[$word] = $counts[$word] +1;
                } else {
                    $counts[$word] = 1;
                }
            }
        }

        $popularWords = array();
        foreach ($counts as $key=>$value) {
            if($counts[$key] > 1) {
                $popularWords[$key] = $value;
            }
        }

        $counts = $popularWords;

        Log::toFile("array length " . sizeof($counts));
        Log::toFile(json_encode($counts));

//PhraseCount(str)

        Return $counts;
    }
}