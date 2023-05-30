<?php
if (session_id() == '') {
  session_start();
}
require_once 'pooldb.php';
if (isset($_POST['hostName'])) {
  $hostName = $_POST['hostName'];
  $userName = $_POST['userName'];
  $dbPassWord = $_POST['dbPassWord'];

  $conn = new mysqli($hostName, $userName, $dbPassWord);
  if ($conn->connect_error) {
    echo "false";
  } else {
    echo "validCredential";
  }
} else if (isset($_POST['flagForTempLogin'])) {

  date_default_timezone_set('Asia/Kolkata');
  $currentTime = date('d-m-Y::h:i:sa');

  $_SESSION['hostName'] = $hostName = $_POST['tempHostName'];
  $_SESSION['userName'] = $userName = $_POST['tempUserName'];
  $_SESSION['dbPassWord'] = $dbPassWord = $_POST['tempDbPassWord'];

  $conn = new mysqli($hostName, $userName, $dbPassWord);
  if ($conn->connect_error) {
    echo "false";
  } else {
    $_SESSION['accountInfo'] = "tempLogin";
    $_SESSION['userId'] = "";
    $_SESSION['accountType'] = "OWNER";
    $_SESSION['groupName'] = "G";
    $_SESSION['userType'] = 'TEMP LOGIN';
    $_SESSION['login'] = 1;
    $_SESSION['fullName'] = "USER";
    $_SESSION['lastLoginTime'] = $currentTime;
    echo "validTempCredential";
  }
} elseif (isset($_POST['loginId'])) {
  $loginId =mysqli_real_escape_string($poolConn, $_POST['loginId']); 
  $loginPassword = mysqli_real_escape_string($poolConn, $_POST['loginPassword']);
  $fSport = mysqli_real_escape_string($poolConn, $_POST['fSport']);
  $fFood =mysqli_real_escape_string($poolConn, $_POST['fFood']); 
  $qAns=$fSport."%:::%".$fFood;



  $sql = "SELECT * FROM logindetails WHERE loginName='" . $loginId . "' AND loginPassword='" . $loginPassword . "';";
  // echo $sql;
  $result = mysqli_query($poolConn, $sql);

  if (!empty($result) && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      if(strcmp($qAns,$row['qAns'])!=0){
        echo "no match error";
        return;
      }
      date_default_timezone_set('Asia/Kolkata');
      $currentTime = date('d-m-Y::h:i:sa');
      $accountInfoArray = explode(":::", $row['accountInfo']);
      $_SESSION['accountInfo'] = $row['accountInfo'];
      $_SESSION['userId'] = $row['userId'];
      $_SESSION['groupId'] = $row['groupId'];
      $_SESSION['userType'] = $row['userType'];
      $_SESSION['profileImage'] = $row['imageName'];
      $_SESSION['accountType'] = $accountInfoArray[0];
      $_SESSION['groupName'] = $accountInfoArray[1];
      $_SESSION['login'] = 1;
      $_SESSION['fullName'] = $row['firstname'] . '  ' . $row['lastname'];
      $_SESSION['lastLoginTime'] = $row['lastLoginTime'];
      $_SESSION['LAST_ACTIVITY'] = time();

      $_SESSION['hostName'] = $row['hostName'];
      $_SESSION['userName'] = $row['userName'];
      $_SESSION['dbPassWord'] = $row['databasePassword'];

      // echo $_SESSION['accountType'].' '.$_SESSION['groupName'];
    }
    $sql = "UPDATE logindetails SET lastLoginTime='$currentTime',userStatus='Online' WHERE loginName='" . $loginId . "' AND loginPassword='" . $loginPassword . "';";
    // echo $sql;
    $result = mysqli_query($poolConn, $sql);

    echo 'true';
  }
} elseif (isset($_POST['logOut'])) {
  if(!isset($_SESSION['LAST_ACTIVITY'])) return;
  $logOutType=$_POST['logOutType'];
  // echo "enter"." ".$logOutType." ".strcmp($logOutType, "NATURAL");

  if (strcmp($logOutType, "NATURAL")==0) {
    $sql = "UPDATE logindetails SET userStatus='Offline' WHERE userId='" . $_SESSION['userId'] . "';";
    // echo $sql;
    $result = mysqli_query($poolConn, $sql);
    
    session_unset(); // unset $_SESSION variable for the run-time 
    session_destroy();
    
    echo 'true';
  } elseif (strcmp($logOutType, "AUTOMATIC")==0) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > 1800) {
      $sql = "UPDATE logindetails SET userStatus='Offline' WHERE userId='" . $_SESSION['userId'] . "';";
      // echo $sql;
      $result = mysqli_query($poolConn, $sql);

      // last request was more than 30 minutes ago since diff is 1800
      session_unset(); // unset $_SESSION variable for the run-time 
      session_destroy(); // destroy session data in storage
      // header("Location:index.php");
      // echo "log out";
      echo 'true';
    }
  }

}

// $hostName='31.220.110.201';
// $userName='u964538868_testForStudy';
// $dbPassWord='JainamJain@12345678';
// $db='u964538868_testForStudy';
