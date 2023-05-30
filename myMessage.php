<?php
session_start();
if (isset($_SESSION["login"])) {
    if ($_SESSION["login"] > 0) {
        // header("Location:loginmessage.php");
        // exit;
    } else {
        header("Location:index.php");
        exit;
    }
} else {
    header("Location:index.php");
    exit;
}
require 'header.php';
require 'navbar.php';
require 'pooldb.php';
// require 'userDbConnection.php';


$userArray = [];
$groupId = $_SESSION["groupId"];
// $sql = "SELECT userId,groupId,firstname,lastname FROM logindetails WHERE groupId='$groupId';";
// echo $sql;
// $result = mysqli_query($conn1, $sql);
// if (!empty($result) && $result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         array_push($userArray, $row);
//     }
// }
// print_r($userArray);
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 allMemberDiv mt-3 mb-3">
                <!-- <div class="MemberListHeader">
                    <input type="text" id="filterInput" class="searchMember searchInput mb-3"
                        placeholder="Search member" title="Type a value">
                </div> -->

                <div class="MemberListHeader">
                    <div>
                        <ul class="list-group verticalScroll resizeVertical list-group-flush" id="memberList">

                            <li class="list-group-item currentUserSelect"
                                id='<?php echo $_SESSION['userId'] . ':::' . $_SESSION['groupId'] . ':::' . 'group.png' . ':::' . 'group' . ':::' . 'Online'; ?>'
                                onclick='selectUser(this.id)'>
                                <div class="row" style="height: 43px;">
                                    <div class="col-4">
                                        <!-- <i class="fa-solid fa-users userProfile"></i> -->
                                        <img src="images/userImage/group.png" class="chatProfileImage">
                                    </div>
                                    <div class="col-8">
                                        <h6 id="memberName" class="p-0 m-0">
                                            <?php echo $_SESSION['groupName']; ?>
                                        </h6>
                                    </div>
                                </div>
                            </li>
                            <!-- loading message -->
                            <div style="display: flex;justify-content: center;" id="loadingUserMessage">
                                <p class="text-white mt-3">Loading users please wait..</p>
                                <div class="spinner-border text-primary" role="status"
                                    style="margin-top: 12px;margin-left: 1%;">
                                </div>
                            </div>

                            <div id="subMemberList"></div>
                            <?php
                            // $userId = $_SESSION['userId'];
                            // $sql = "SELECT userId,groupId,firstname,lastname,imageName,userStatus FROM logindetails WHERE groupId='$groupId' AND userId !='$userId'";
                            // // echo $sql;
                            
                            // $result = mysqli_query($poolConn, $sql);
                            // // print_r($result);
                            
                            // if (!empty($result) && $result->num_rows > 0) {
                            //     // echo "Enter";
                            //     while ($row = $result->fetch_assoc()) {
                            //         $userName = $row['firstname'] . ' ' . $row['lastname'];
                            //         $userId = $row['userId'];
                            //         $groupId = $row['groupId'];
                            //         $tempId = $userId . ':::' . $groupId . ':::' . $row["imageName"] . ":::" . "single" . ":::" . $row['userStatus'];
                            //         $img = "images/userImage/" . $row["imageName"];
                            
                            //         echo "<li class='list-group-item currentUser' id='$tempId' onclick='selectUser(this.id)'>
                            //         <div class='row' style='height: 43px;'>
                            //             <div class='col-4'>
                            //                 <img src='$img' class='chatProfileImage'>
                            //             </div>
                            //             <div class='col-8'>
                            //                 <h6 id='memberName' class='p-0 m-0'>$userName</h6>
                            //             </div>
                            //         </div>
                            //     </li>";
                            //     }
                            // }
                            ?>

                            <!-- <li class="list-group-item currentUser">
                                <div class="row" style="height: 43px;">
                                    <div class="col-4">
                                        <i class="fa-solid fa-user userProfile"></i>
                                    </div>
                                    <div class="col-8">
                                        <h6 id="memberName" class="p-0 m-0">Beauty Mishra</h6>
                                        <p id="lastMessage" class="p-0 m-0">Hii</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item currentUser">
                                <div class="row" style="height: 43px;">
                                    <div class="col-4">
                                        <i class="fa-solid fa-user userProfile"></i>
                                    </div>
                                    <div class="col-8">
                                        <h6 id="memberName" class="p-0 m-0">Santu Mishra</h6>
                                        <p id="lastMessage" class="p-0 m-0">Hey</p>
                                    </div>
                                </div>
                            </li> -->

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-1 mt-3  mb-3"></div>
            <div class="col-md-7 MessageArea mt-3  mb-3">
                <div class="MessageAreaHeader">
                    <div class="row" style="height: 55px;">
                        <div class="col-2">
                            <!-- <i class="fa-solid fa-user userProfile"></i> -->
                            <img src="images/userImage/group.png" class="chatProfileImage" id="currentSelectedUser">
                        </div>
                        <div class="col-8">
                            <h2 id="receiverName" class="p-0 m-0 text-white">
                                <?php echo $_SESSION['groupName']; ?>
                            </h2>
                            <h4 class="pb-2 m-0 text-black" id="userStatus">---</h4>
                        </div>
                    </div>
                </div>
                <div class="MessageAreaBody">
                    <!-- loading message for messages -->
                    <div style="display: flex;justify-content: center;" id="loadingMessageDiv">
                        <h5 class="text-white mt-3" id="loadingTableMessage">Loading messages please wait..</h5>
                        <div class="spinner-border text-primary" role="status"
                            style="margin-top: 15px;margin-left: 1%;">
                        </div>
                    </div>
                    <div class="allMessages">

                        <!-- <div class="receiver row">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>Santanu</p>
                            </div>

                            <p class="receive-message">Hii adarsh</p>
                        </div>
                        <div class="sender divEnd">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>You</p>
                            </div>
                            <p class="sender-message">Hii Santanu</p>
                        </div>
                        <div class="receiver row">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>Santanu</p>
                            </div>
                            <p class="receive-message">How are you ?</p>
                        </div>
                        <div class="sender divEnd">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>You</p>
                            </div>

                            <p class="sender-message">Fine.Thank you ;)</p>
                        </div>
                        <div class="receiver row">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>Santanu</p>
                            </div>
                            <p class="receive-message">What about you ?</p>
                        </div>
                        <div class="sender divEnd">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>You</p>
                            </div>
                            <p class="sender-message">I am also good to.Currently working on my college project.</p>
                        </div>
                        <div class="receiver row">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>Santanu</p>
                            </div>
                            <p class="receive-message">Oh! That's nice.Hope you are doing well.</p>
                        </div>
                        <div class="sender divEnd">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>You</p>
                            </div>
                            <p class="sender-message">Yes,Thanks.</p>
                        </div>
                        <div class="sender divEnd">
                            <div class="imagetextdiv">
                                <img src="images/userImage/group.png" class="messageInProfileImage">
                                <p>You</p>
                            </div>
                            <p class="sender-message">Lorem ipsum dolor sit amet consectetur adipisicing elit. Iste odio
                                ipsam tempora mollitia placeat? Saepe praesentium eaque velit quas odit aliquam
                                corporis, laboriosam at minus ullam maiores sit recusandae optio.</p>
                        </div> -->

                    </div>
                </div>
                <!-- Message input -->
                <div class="row mt-1 mb-5" style="padding: 0px 30px 0px 30px !important;">
                    <div class="userMessageTextarea mt-1">
                        <div class="col-10 mt-1">
                            <textarea id="userMessage" spellcheck="false" class="w-100 text-black" rows="2"></textarea>
                        </div>
                        <div class="col-2 p-2" style="display: flex !important;align-items: center !important;">
                            <p id="userInfo" hidden>
                                <?php echo $_SESSION['groupId'] . ':::' . $_SESSION['groupId'] . ':::' . 'group.png' . ':::' . 'group' . ':::' . 'Online'; ?>
                            </p>

                            <i class="fa-solid fa-paper-plane sendIcon" id="sendMessage" name="adrsh"
                                onclick="sendMessage()"></i>
                            <!-- spinner -->
                            <div class="spinner-border text-white" role="status" id="waitingSpinner" hidden>
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>
<script src="js/myMessage.js?<?php echo rand(); ?>"></script>
<script>
    $(document).ready(function () {
        document.getElementById('loadingMessage').hidden = true;
        loadGroupUser();
    });
</script>

</html>