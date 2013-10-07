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
        $conn = getDBConnections::getDBConnection($DB);

        if ($conn !== false) {
            $cmd = pg_query($conn, $query);
        } else {
            throw new Exception();
        }

        return DataAdapter::Read($cmd);
    }

    public static function Read($queryResult)
    {
        $count = pg_num_rows($queryResult);
//        Log::toFile('DataAdapter sees x results : ' . $count);

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
//        Log::toFile('DataAdapter sees x results : ' . $count);

        if (pg_num_rows($rs)) {
            $r = pg_fetch_row($rs);
            return $r[0];
        }
        return null;
    }

    public static function defaultExecuteScalar($query, $DB = "Geoportal") {
//        Log::toFile('Query Scalar (default) : ' . $query );
        $rs = pg_query(getDBConnections::getDBConnection($DB), $query);

        if (pg_num_rows($rs)) {
            $r = pg_fetch_row($rs);
            return $r[0];
        }
    }

    public static function DefaultPDOExecuteAndRead($dbQuery, $values, $DBName = "Geoportal")
    {

        $connString = "host=" . variables::$databaseAddr . " port=". variables::$databasePort .
            " dbname=" . $DBName . " user=" . variables::$databaseUsername . " password=" . variables::$databasePassword;
        $pdo = new PDO('pgsql:' . $connString);

        $PDOstatement = $pdo->prepare($dbQuery);

        $statementComplete = $PDOstatement->execute($values);

        $success = ($statementComplete == 1 ? True : False);
        Log::toFile("PDO output : "
            . $PDOstatement->queryString . " : errorcode "
            . $PDOstatement->errorCode() . " : success "
            . $success
            . " : errorinfo " . print_r($PDOstatement->errorInfo(), true));

        $returnObject = new QueryObject();
        $returnObject->resultObject = $PDOstatement->fetchAll(PDO::FETCH_OBJ);
        $returnObject->errorCode = $PDOstatement->errorCode();
        $returnObject->errorInfo = $PDOstatement->errorInfo();
        $returnObject->resultSuccess = $success;

        return $returnObject;
    }

}

class QueryObject {
    public $resultObject;
    public $resultSuccess;
    public $errorCode;
    public $errorInfo;
}

?>