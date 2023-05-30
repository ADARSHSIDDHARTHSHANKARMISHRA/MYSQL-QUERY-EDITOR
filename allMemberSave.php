<?php
session_start();
require_once 'pooldb.php';
$poolConn->begin_transaction();
if (isset($_POST['flagForSave'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $userType = $_POST['userType'];
    $loginName = $_POST['loginId'];
    $loginPassword = $_POST['loginPassword'];
    $groupId = $_SESSION['userId'];
    $accountInfo = $_SESSION['accountInfo'];
    $hostName = $_SESSION['hostName'];
    $userName = $_SESSION['userName'];
    $dbPassWord = $_SESSION['dbPassWord'];


    $sql = "SELECT * FROM seriesnumber WHERE seriesName = 'userId';";
    $result = mysqli_query($poolConn, $sql);
    // echo $sql.' '.$result;
    if (!empty($result) && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // print_r($row);
            $userId = $row['seriesValue'];
        }
    }
    // userId, accountInfo, firstname, lastname, loginName, loginPassword, hostName, userName, databasePassword, lastLoginTime
    $sql = "INSERT INTO logindetails VALUES('$userId','$groupId','$userType','$accountInfo','$firstName','$lastName','$loginName','$loginPassword','$hostName','$userName','$dbPassWord','');";
    // echo $sql;
    $result = mysqli_query($poolConn, $sql);

    $sql = "UPDATE seriesnumber SET seriesValue=seriesValue+1 WHERE seriesName = 'userId';";
    $result = mysqli_query($poolConn, $sql);
    $poolConn->commit();
    echo 'true';
} elseif (isset($_POST['flagForDelete'])) {
    $userId =$_POST['userId'];
    $sql = "DELETE FROM logindetails WHERE userId = '$userId';";
    $result = mysqli_query($poolConn, $sql);
    $poolConn->commit();
    echo 'true';
}
$poolConn = null;