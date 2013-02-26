<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wiserd
 * Date: 17/02/13
 * Time: 16:15
 * To change this template use File | Settings | File Templates.
 */

class DataAdapter {



    public function Fill(DataTable $dataTable)
    {

    }

    public function Read($queryResult)
    {
        $count = pg_num_rows($queryResult);
        Log::toFile('results returned : ' . $count);

        $toReturn = array();

        while ($row = pg_fetch_object($queryResult)) {
         //   Log::toFile('row : ' . print_r($row, true));
//            Log::toFile('column count : ' . count($row));
    //        Log::toFile('keys : ' . print_r(array_keys($row), true));
    //        Log::toFile('values : ' . print_r(array_values($row), true));

            $toReturn[] = $row;
        }

   //     Log::toFile('return search object array : ' + print_r($toReturn, true));
        return $toReturn;
    }

}
?>