<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 26/02/13
 * Time: 16:20
 * To change this template use File | Settings | File Templates.
 */


        $pattern = "/\"{name:(.*), data/";
        $replacement = '{"name":"$1", "data"';
        $subject = '{"Name":"Wales","lat":"52.5","lon":"-3.5","Rank":1,"Score":2,"Country":"GB","Gazetteer":"geonames:2634895","Occurences":"14","wordStats":"{name:Wales - dash, data:[{"page":"Page 0","count":0},{"page":"Page 1","count":1},{"page":"Page 2","count":0},{"page":"Page 3","count":0},{"page":"Page 4","count":0},{"page":"Page 5","count":0},{"page":"Page 6","count":0},{"page":"Page 7","count":0},{"page":"Page 8","count":0},{"page":"Page 9","count":0},{"page":"Page 10","count":0},{"page":"Page 11","count":0},{"page":"Page 12","count":0},{"page":"Page 13","count":0},{"page":"Page 14","count":0},{"page":"Page 15","count":0},{"page":"Page 16","count":0},{"page":"Page 17","count":0},{"page":"Page 18","count":0},{"page":"Page 19","count":1},{"page":"Page 20","count":1},{"page":"Page 21","count":0},{"page":"Page 22","count":0},{"page":"Page 23","count":0},{"page":"Page 24","count":1},{"page":"Page 25","count":0},{"page":"Page 26","count":1},{"page":"Page 27","count":0},{"page":"Page 28","count":0},{"page":"Page 29","count":1},{"page":"Page 30","count":1},{"page":"Page 31","count":0},{"page":"Page 32","count":0},{"page":"Page 33","count":2},{"page":"Page 34","count":0},{"page":"Page 35","count":0},{"page":"Page 36","count":0},{"page":"Page 37","count":5},{"page":"Page 38","count":0},{"page":"Page 39","count":0},{"page":"Page 40","count":0},{"page":"Page 41","count":0},{"page":"Page 42","count":0}]}"}';

        $result = preg_replace($pattern, $replacement, $subject);

        $result = substr($result, 0, -2) . "}";

        echo ($result);

