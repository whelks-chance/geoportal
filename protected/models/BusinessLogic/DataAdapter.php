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

    public static function DefaultExecuteAndRead($query, $DB = "Geoportal") {
        Log::toFile('Query (default) : ' . $query );
        $cmd = pg_query(getDBConnections::getDBConnection($DB), $query);

        return DataAdapter::Read($cmd);
    }

    public static function Read($queryResult)
    {
        $count = pg_num_rows($queryResult);
        Log::toFile('DataAdapter sees x results : ' . $count);

        $toReturn = array();

        while ($row = pg_fetch_object($queryResult)) {
            //   Log::toFile('row : ' . print_r($row, true));
//            Log::toFile('column count : ' . count($row));
            //        Log::toFile('keys : ' . print_r(array_keys($row), true));
            //        Log::toFile('values : ' . print_r(array_values($row), true));

            $toReturn[] = $row;
        }
//        Log::toFile('Data Object : ' . print_r($toReturn, true));
        //     Log::toFile('return search object array : ' + print_r($toReturn, true));
        return $toReturn;
    }

    public function execute_scalar($cnn, $pgsql) {

//        Log::toFile('Executing scalar ' . $pgsql);
        $rs = pg_query($cnn, $pgsql);

        $count = pg_num_rows($rs);
        Log::toFile('DataAdapter sees x results : ' . $count);

        if (pg_num_rows($rs)) {
            $r = pg_fetch_row($rs);
            return $r[0];
        }
        return null;
    }

    public static function defaultExecuteScalar($query, $DB = "Geoportal") {
        Log::toFile('Query Scalar (default) : ' . $query );
        $rs = pg_query(getDBConnections::getDBConnection($DB), $query);

        if (pg_num_rows($rs)) {
            $r = pg_fetch_row($rs);
            return $r[0];
        }
    }

}
?>