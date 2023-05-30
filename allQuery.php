<?php
require_once 'userDbConnection.php';

$conn1->begin_transaction();

//use to see server error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getSqlStatementType($sqlQuery) {
    $sqlType = '';
    $sqlQuery = trim($sqlQuery); // Remove any leading/trailing spaces
    $sqlStart = substr($sqlQuery, 0, 6); // Extract the first 6 characters of the query
    $sqlStart = strtoupper($sqlStart); // Convert to uppercase for case-insensitive comparison
    switch ($sqlStart) {
        case 'SELECT':
            $sqlType = 'SELECT';
            break;
        case 'INSERT':
            $sqlType = 'INSERT';
            break;
        case 'UPDATE':
            $sqlType = 'UPDATE';
            break;
        case 'DELETE':
            $sqlType = 'DELETE';
            break;
        case 'CREATE':
            $sqlType = 'CREATE';
            break;
        case 'ALTER':
            $sqlType = 'ALTER';
            break;
        case 'DROP T':
            $sqlType = 'DROP'; // DROP TABLE or DROP TRIGGER
            break;
        case 'TRUNCA':
            $sqlType = 'TRUNCATE';
            break;
        case 'GRANT ':
            $sqlType = 'GRANT';
            break;
        case 'REVOKE':
            $sqlType = 'REVOKE';
            break;
    }
    return $sqlType;
}


//The manual page for str_contains says the function was introduced in PHP 8. 
//I suspect your host is on PHP 7 (or possibly lower).
// You can define a polyfill for earlier versions (adapted from https://github.com/symfony/polyfill-php80)

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}

try {

    $masterTableName = [];
    $masterColumnName = [];
    $masterTableData = [];
    if (isset($_POST['flagForTableName'])) {
        $databaseName = $_POST['databaseName'];
        $_SESSION["dbName"] = $databaseName;

        $sql = "SELECT table_name,table_schema FROM information_schema.tables WHERE table_schema = '$databaseName' ORDER BY table_name ASC;";
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

        echo json_encode($masterColumnName);
    } elseif (isset($_POST['flagForAllTableData'])) {
        $databaseName = $_POST['databaseName'];
        $tableName = $_POST['tableName'];
        $selectQuery = $_POST['selectQuery'];
        $tempQuery = $selectQuery;
        $tempArray = [];

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

        echo json_encode($masterColumnName);
    } elseif (isset($_POST['flagForRunQuery'])) {
        $sql =$_POST['currentQuery'];  
        $lineNo = $_POST['lineNo'];
        $selectData = [];
        $columnName = [];
        $queryType=getSqlStatementType($sql);

        $queryInfo = $sql . '%:%:%' . $lineNo . '%:%:%';

        $result = mysqli_query($conn1, $sql);
        $isError=false;

        if ($result) {
            $queryInfo = $queryInfo . 'true';
            if (str_contains(strtoupper($sql), 'SELECT') || str_contains(strtoupper($sql), 'SHOW') || str_contains(strtoupper($sql), 'DESCRIBE')) {
                while ($row = $result->fetch_assoc()) {
                    array_push($selectData, $row);
                }
            }
            $queryInfo = $queryInfo . "%:%:%" . json_encode($selectData);
        } else {
            $isError=true;
            $queryInfo = $queryInfo . $conn1->error . "%:%:%";
        }
        $queryStatus=mysqli_info($conn1);//mysqli_info rerurn runned query status 
        $affectedRow=$conn1->affected_rows;
        $queryInfo = $queryInfo . ' %:%:%' .$affectedRow. ' %:%:%' .$queryStatus;
        

        if ($_SESSION['userType'] != 'VIEWER') {
            if(strcmp($queryType,"SELECT")!=0 && strcmp($_SESSION['groupName'],"G")!=0 && $isError==false){
                // echo "echo call";
                date_default_timezone_set('Asia/Kolkata');
                $logTime = date('d-m-Y:%:h:i:sa');
                $userId=$_SESSION['userId'];
                $groupId=$_SESSION['groupId'];
                $queryMessage=$sql;
                
                $sqlLog="INSERT INTO logrecord(userId,groupId,logQuery,logTime) VALUES('$userId','$groupId','$queryMessage','$logTime');";
                $result = mysqli_query($conn1, $sqlLog);
            }
            $conn1->commit();
            echo $queryInfo;
        } elseif ($_SESSION['userType'] == 'VIEWER' && str_contains(strtoupper($sql), 'SELECT') || str_contains(strtoupper($sql), 'SHOW') || str_contains(strtoupper($sql), 'DESCRIBE')) {
            $conn1->commit();
            echo $queryInfo;
        } else {
            echo 'VIEWER ERROR';
        }
    }else if(isset($_POST['flagForDeleteLogRecord'])){
        $logId = $_POST['logId'];
        $sql="DELETE FROM logrecord WHERE logId='$logId';";
        $result=mysqli_query($conn1, $sql);
        if($result){
            $conn1->commit();
            echo "true";
        } 
    }
} catch (mysqli_sql_exception $exception) {
    $conn1->rollback();

    throw $exception;
}
$conn1 = null;
