<?php
require_once 'pooldb.php';
if (session_id() == '') {
    session_start();
}

$poolConn->begin_transaction();


try {
    if (isset($_POST['flagForUserDetail'])) {
        $userId = $_SESSION['userId'];
        $groupId = $_SESSION["groupId"];

        $sql = "SELECT userId,groupId,firstname,lastname,imageName,userStatus FROM logindetails WHERE groupId='$groupId' AND userId !='$userId'";
        // echo $sql;

        $result = mysqli_query($poolConn, $sql);
        $ouTput = "";
        // print_r($result);

        if (!empty($result) && $result->num_rows > 0) {
            // echo "Enter";
            while ($row = $result->fetch_assoc()) {
                $userName = $row['firstname'] . ' ' . $row['lastname'];
                $userId = $row['userId'];
                $groupId = $row['groupId'];
                $tempId = $userId . ':::' . $groupId . ':::' . $row["imageName"] . ":::" . "single" . ":::" . $row['userStatus'];
                $img = "images/userImage/" . $row["imageName"];
                if (!file_exists($img)) {
                    $img = "images/userImage/default.png";
                }
                $userStatus = "";
                if (strlen($row['userStatus']) == 0) $userStatus = "Offline";
                else $userStatus=$row['userStatus'];

                $ouTput=$ouTput. "<li class='list-group-item currentUser' id='$tempId' onclick='selectUser(this.id)'>
                                    <div class='row' style='height: 43px;'>
                                        <div class='col-4'>
                                            <img src='$img' class='chatProfileImage'>
                                        </div>
                                        <div class='col-8'>
                                            <h6 id='memberName' class='p-0 m-0'>$userName</h6>
                                            <h6 id='memberName' class='p-0 mt-1'>".$userStatus."</h6>
                                        </div>
                                    </div>
                                </li>";
            }
        }
        $poolConn->commit();
        echo $ouTput;
    } else if (isset($_POST['flagForSendMessage'])) {
        $senderId = $_SESSION["userId"];
        $recerverId = $_POST["recerverId"];
        $senderName = $_SESSION["fullName"];
        $groupId = $_POST["groupId"];
        $senderImageName = $_SESSION['profileImage'];
        $userMessage = mysqli_real_escape_string($poolConn, $_POST["userMessage"]);
        $messageType = $_POST["messageType"];

        echo strcmp($messageType, "group") . " " . $messageType . " " . $recerverId . " ";
        if (strcmp($messageType, "group") == 0) {
            $recerverId = $groupId;
        }
        date_default_timezone_set('Asia/Kolkata');
        $sendTime = date('d-m-Y::h:i:sa');

        // echo $userId . ":" . $groupId . ":" . $messageType;
        $sql = "INSERT INTO userMessage (messageType,senderId,senderName,recerverId,sendTime,msg,senderImageName) VALUES ('$messageType','$senderId','$senderName','$recerverId','$sendTime','$userMessage','$senderImageName');";
        $result = mysqli_query($poolConn, $sql);

        echo "true" . $sql;

        $poolConn->commit();

    } else if (isset($_POST['flagForFetchMessage'])) {
        $senderId = $_SESSION["userId"];
        $recerverId = $_POST["recerverId"];
        $groupId = $_POST["groupId"];
        $senderImageName = $_SESSION['profileImage'];
        $messageType = $_POST["messageType"];

        $sql = "SELECT * FROM userMessage WHERE (senderId='$senderId' AND recerverId='$recerverId' AND messageType='$messageType') OR (senderId='$recerverId' AND recerverId='$senderId' AND messageType='$messageType') ORDER BY messageId ASC;";


        if (strcmp($messageType, "group") == 0) {
            $recerverId = $groupId;
            $sql = "SELECT * FROM userMessage WHERE recerverId='$recerverId' AND messageType='$messageType' ORDER BY messageId ASC;";
        }

        // echo $userId . ":" . $groupId . ":" . $messageType;
        // echo $sql;
        $result = mysqli_query($poolConn, $sql);


        $outPut = "";

        if (!empty($result) && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $userImagePath = "images/userImage/" . $row['senderImageName'];
                $senderName = explode(" ", $row['senderName'])[0];

                if (!file_exists($userImagePath)) {
                    $userImagePath = "images/userImage/default.png";
                }
                    
                if (strcmp($row['messageType'], "group") == 0) {
                    $receiverName = "All";
                }

                //sender message
                if (strcmp($row['senderId'], $senderId) == 0) {

                    $userImagePath = "images/userImage/" . $_SESSION['profileImage'];

                    if (!file_exists($userImagePath)) {
                        $userImagePath = "images/userImage/default.png";
                    }

                    $outPut = $outPut . '<div class="sender divEnd">
                    <div class="imagetextdiv">
                        <img src=' . $userImagePath . ' class="messageInProfileImage">
                        <p>You</p>
                    </div>
                    <p class="sender-message">' . $row["msg"] . '</p>
                </div>';
                } else {
                    //receiver message
                    $outPut = $outPut . ' <div class="receiver row">
                    <div class="imagetextdiv">
                        <img src=' . $userImagePath . ' class="messageInProfileImage">
                        <p>' . $senderName . '</p>
                    </div>

                    <p class="receive-message">' . $row["msg"] . '</p>
                </div>';

                }

            }
        }

        echo $outPut.':%:%:'.$recerverId;

        $poolConn->commit();

    }
} catch (mysqli_sql_exception $exception) {
    $conn1->rollback();

    throw $exception;
}
$poolConn = null;
?>