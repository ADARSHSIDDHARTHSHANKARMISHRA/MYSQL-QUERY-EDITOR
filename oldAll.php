<?php
require_once 'userDbConnection.php';

$conn1->begin_transaction();

try {

    $masterTableName = [];
    $masterColumnName = [];
    $masterTableData = [];
    if (isset($_POST['flagForTableName'])) {
        $databaseName = $_POST['databaseName'];
        $_SESSION["dbName"] = $databaseName;

        $sql = "SELECT table_name,table_schema FROM information_schema.tables WHERE table_schema = '$databaseName';";
        $result = mysqli_query($conn1, $sql);
        if (!empty($result) && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($masterTableName, $row);
            }
        }
        // echo $databaseName;
        // echo $sql;
        echo json_encode($masterTableName);

        //note that jsom encode return array as json string
        //JSON.parse(outpur) conver array into JSON Object

    } elseif (isset($_POST['flagForColumnName'])) {
        $databaseName = $_POST['databaseName'];
        $tableName = $_POST['tableName'];
        $sql = "SELECT column_name FROM information_schema.columns WHERE table_schema='$databaseName' AND table_name='$tableName';";
        // echo $sql;
        $result = mysqli_query($conn1, $sql);
        if (!empty($result) && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($masterColumnName, $row);
            }
        }
        // echo $databaseName;
        // echo $sql;
        echo json_encode($masterColumnName);

        //note that jsom encode return array as json string
        //JSON.parse(outpur) conver array into JSON Object


    } elseif (isset($_POST['flagForAllTableData'])) {
        $databaseName = $_POST['databaseName'];
        $tableName = $_POST['tableName'];
        $selectQuery = $_POST['selectQuery'];
        $tempQuery = $selectQuery;
        $tempArray = [];


        // $sql="SELECT SELECT DATABASE() FROM DUAL;";
        // // echo $sql;
        // $result = mysqli_query($conn1, $sql);
        // print_r($result);
        // echo 'Called';
        // if (!empty($result) && $result->num_rows > 0) {
        //     echo 'Called1';

        //     while ($row = $result->fetch_assoc()) {
        //         echo 'Called2';

        //        print_r($row);
        //     }
        // }

        $sql1 = $selectQuery;
        // echo $sql1;
        $result1 = mysqli_query($conn1, $sql1);

        if (!empty($result1) && $result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                array_push($masterTableData, $row1);
            }
        }

        if ($result1) {
            $sql = "SELECT column_name FROM information_schema.columns WHERE table_schema='$databaseName' AND table_name='$tableName';";
            // echo $sql;
            $result = mysqli_query($conn1, $sql);
            if (!empty($result) && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($masterColumnName, $row);
                }
            }
            array_push($masterTableData, $masterColumnName);

            echo json_encode($masterTableData) . "%:%:%" . "";
        } else {
            echo json_encode($masterTableData) . "%:%:%" . $conn1->error;
        }

    } elseif (isset($_POST['flagForColumnInfo'])) {
        $databaseName = $_POST['databaseName'];
        $tableName = $_POST['tableName'];
        $sql = "SELECT column_name,column_type FROM information_schema.columns WHERE table_schema='$databaseName' AND table_name='$tableName';";
        // echo $sql;
        $result = mysqli_query($conn1, $sql);
        if (!empty($result) && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($masterColumnName, $row);
            }
        }
        // echo $databaseName;
        // echo $sql;
        echo json_encode($masterColumnName);
    } elseif (isset($_POST['flagForRunQuery']) && $_POST['flagForRunQuery'] == true) {
        $sql = $_POST['currentQuery'];
        $lineNo = $_POST['lineNo'];
        $selectData = [];

        $queryInfo = $sql . '%:%:%' . $lineNo . '%:%:%';

        $result = mysqli_query($conn1, $sql);

        if ($result) {
            $queryInfo = $queryInfo . 'true';
            if (str_contains(strtoupper($sql), 'SELECT')) {
                while ($row = $result->fetch_assoc()) {
                    array_push($selectData, $row);
                }
            }
            $queryInfo = $queryInfo . "%:%:%" . json_encode($selectData);
        } else {
            $queryInfo = $queryInfo . $conn1->error . "%:%:%";
        }
        $queryInfo = $queryInfo . ' %:%:%' . mysqli_affected_rows($conn1);

        echo $queryInfo;
    }
} catch (mysqli_sql_exception $exception) {
    $conn1->rollback();

    throw $exception;
}
$conn1 = null;
