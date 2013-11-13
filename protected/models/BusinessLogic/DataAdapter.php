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



    public static function isTrustedTableName( $riskyTableName , $db) {

        $singleNameArray = array();
        $singleNameArray[] = $riskyTableName;
        $trustedNames = DataAdapter::areTrustedTableNames($singleNameArray, $db );
        if (sizeof( $trustedNames ) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function areTrustedTableNames($riskyTablenameArray, $db)
    {
        $acceptableTablenameArray = array();

        $allTables = "SELECT table_name FROM information_schema.tables
        WHERE table_schema='public' AND table_type='BASE TABLE';";

        $results = DataAdapter::DefaultPDOExecuteAndRead($allTables, null, $db);

        //iterate through risky table names, keep ones which match existing table names
        foreach($riskyTablenameArray as $riskyTablename) {

            //iterate through existing tables, check if matches risky name
            $acceptableTableName = "";
            foreach ($results->resultObject as $allowedTablename) {
                $thisTablename = $allowedTablename->table_name;
                if ($thisTablename == $riskyTablename) {

                    //if it matches, accept it and move on
                    $acceptableTableName = $thisTablename;
                    break;
                }
            }

            if($acceptableTableName == "") {
                // nothing acceptable found
            } else {
                $acceptableTablenameArray[] = $acceptableTableName;
            }
        }

        Log::toFile(print_r($riskyTablenameArray, true) . print_r($riskyTablenameArray, true));
        return $acceptableTablenameArray;
    }

    public static function DefaultPDOExecuteAndRead($dbQuery, $values = null, $DBName = "Geoportal")
    {

        if ($values == null) {
            $values = array();
        }
//        $connString = "host=" . variables::$databaseAddr . " port=". variables::$databasePort .
//            " dbname=" . $DBName . " user=" . variables::$databaseUsername . " password=" . variables::$databasePassword;
//        $pdo = new PDO('pgsql:' . $connString);
//        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//        $pdo = ConnectionFactory::getFactory()->getConnection($DBName);
//        $PDOstatement = $pdo->prepare($dbQuery);

        $PDOstatement = ConnectionFactory::getFactory()->getStatement($dbQuery, $DBName);

        $statementComplete = $PDOstatement->execute($values);

        $queryResults = $PDOstatement->fetchAll(PDO::FETCH_OBJ);

        $success = ($statementComplete == 1 ? True : False);

        $errorString = "PDO output : "
            . $PDOstatement->queryString .
            " : errorcode " . $PDOstatement->errorCode() .
            " : success " . $success .
            " : outputSize " . sizeof($queryResults);
        if($PDOstatement->errorCode() != 0) {
            $errorString .= " : errorinfo " . print_r($PDOstatement->errorInfo(), true);
        }
        Log::toFile($errorString);

        $returnObject = new QueryObject();
        $returnObject->resultObject = $queryResults;
        $returnObject->errorCode = $PDOstatement->errorCode();

        $returnObject->errorInfo = $PDOstatement->errorInfo();

        $returnObject->resultSuccess = $success;

        return $returnObject;
    }

}

class ConnectionFactory
{
    private static $factory;
    public static function getFactory()
    {
        if (!self::$factory)
            self::$factory = new ConnectionFactory();
        return self::$factory;
    }

    private $pdo = array();

    public function getConnection($DBName) {

        if(!array_key_exists($DBName, $this->pdo)) {
            $connString = "host=" . variables::$databaseAddr . " port=". variables::$databasePort .
                " dbname=" . $DBName . " user=" . variables::$databaseUsername . " password=" . variables::$databasePassword;
            $newPDO = new PDO('pgsql:' . $connString);
            $newPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo[$DBName] = $newPDO;
        }
        return $this->pdo[$DBName];
    }

    private $statements = array();

    public function getStatement($query, $DBName) {

        if(!array_key_exists($query, $this->statements)) {
            $pdo = $this->getConnection($DBName);
            $statement = $pdo->prepare($query);
            $this->statements[$query] = $statement;
        }
//        Log::toFile("statements size : " . sizeof($this->statements));
        return $this->statements[$query];
    }
}

class QueryObject {
    public $resultObject = null;
    public $resultSuccess = false;
    public $errorCode;
    public $errorInfo = null;

    public function hasRows() {
        if( $this->resultObject != null) {
            return sizeof($this->resultObject) > 0;
        } else {
            return false;
        }
    }

    public function numberOfRows() {
        if ($this->resultObject != null) {
            return sizeof($this->resultObject);
        } else {
            return 0;
        }
    }
}

?>