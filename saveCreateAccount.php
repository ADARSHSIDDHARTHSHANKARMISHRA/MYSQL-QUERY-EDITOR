<?php

require_once 'pooldb.php';
session_start();
$poolConn->begin_transaction();
if (isset($_POST['flagforUpdateAccount'])) {
    try {
        $dirUserImage = "images/userImage/";

        $accountType = $_POST['accountType'];
        $groupName = $_POST['groupName'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $loginName = $_POST['loginName'];
        $loginPassword = $_POST['loginPassword'];
        $hostName = $_POST['hostName'];
        $userName = $_POST['userName'];
        $dbPassWord = $_POST['dbPassWord'];
        $userId = $_SESSION['userId'];
        $accountInfo = $accountType . ':::' . $groupName;

        $flagImageUpload = $_POST['flagImageUpload'];
        $oldUserImageName = $_POST['oldUserImageName'];
        $userImageName = "";

        if ($flagImageUpload == true) {
            if ($oldUserImageName!="default.png" && file_exists($dirUserImage.$oldUserImageName)) {
                unlink($dirUserImage.$oldUserImageName);
            }
            $temp1        = explode(".", $_FILES["userImage"]["name"]);
            $userImageName = round(rand()) . '.' . end($temp1);
            move_uploaded_file($_FILES["userImage"]["tmp_name"], $dirUserImage . $userImageName);
        }else{
            $userImageName = $oldUserImageName;
        }
        

        // userId, accountInfo, firstname, lastname, loginName, loginPassword, hostName, userName, databasePassword, lastLoginTime
        $sql = "UPDATE logindetails set accountInfo='$accountInfo',firstname='$firstName',lastname='$lastName',loginName='$loginName',loginPassword='$loginPassword',hostName='$hostName',userName='$userName',databasePassword='$dbPassWord',imageName='$userImageName' where userId='$userId';";
        // echo $sql;
        $result = mysqli_query($poolConn, $sql);

        $poolConn->commit();
        echo 'true';
    } catch (mysqli_sql_exception $exception) {
        $poolConn->rollback();

        throw $exception;
    }
    $poolConn = null;
} else if (isset($_POST['flagForCreateAccount'])) {
    try {
        $dirUserImage = "images/userImage/";

        $accountType = $_POST['accountType'];
        $groupName = $_POST['groupName'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $loginName = $_POST['loginName'];
        $loginPassword = $_POST['loginPassword'];
        $hostName = $_POST['hostName'];
        $userName = $_POST['userName'];
        $fSport = mysqli_real_escape_string($poolConn, $_POST['fSport']);
        $fFood = mysqli_real_escape_string($poolConn, $_POST['fFood']);
        $dbPassWord = $_POST['dbPassWord'];
        $flagImageUpload = $_POST['flagImageUpload'];

        $userId = '';
        $groupId = '';
        $accountInfo = $accountType . ':::' . $groupName;
        $userImageName = "";
        $qAns=$fSport."%:::%".$fFood;


        $sql = "SELECT * FROM seriesnumber WHERE seriesName = 'userId';";
        $result = mysqli_query($poolConn, $sql);
        // echo $sql.' '.$result;
        if (!empty($result) && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // print_r($row);
                $userId = $row['seriesValue'];
            }
        }
        $groupId = $userId;
        if ($flagImageUpload == true) {
            $temp1        = explode(".", $_FILES["userImage"]["name"]);
            $userImageName = round(rand()) . '.' . end($temp1);
            move_uploaded_file($_FILES["userImage"]["tmp_name"], $dirUserImage . $userImageName);
        }
        // userId, accountInfo, firstname, lastname, loginName, loginPassword, hostName, userName, databasePassword, lastLoginTime
        $sql = "INSERT INTO logindetails VALUES('$userId','$groupId','OWNER','$accountInfo','$firstName','$lastName','$userImageName','$loginName','$loginPassword','Offline','$hostName','$userName','$dbPassWord','','$qAns');";
        // echo $sql;
        $result = mysqli_query($poolConn, $sql);

        $sql = "UPDATE seriesnumber SET seriesValue=seriesValue+1 WHERE seriesName = 'userId';";
        $result = mysqli_query($poolConn, $sql);
        $poolConn->commit();
        echo 'true';
    } catch (mysqli_sql_exception $exception) {
        $poolConn->rollback();

        throw $exception;
    }
    $poolConn = null;
} else if (isset($_POST['falgForDuplicate'])) {
    try {
        $userEmail = $_POST["userEmail"];
        $sql = "SELECT * FROM logindetails WHERE loginName='$userEmail';";
        $result = mysqli_query($poolConn, $sql);
        if ($result->num_rows > 0) {
            echo "true";
        } else {
            echo 'false';
        }
        $poolConn->commit();
    } catch (mysqli_sql_exception $exception) {
        $poolConn->rollback();
        throw $exception;
    }
    $poolConn = null;
}
