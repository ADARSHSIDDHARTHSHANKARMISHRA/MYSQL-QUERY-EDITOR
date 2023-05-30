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

$userData = [];
$userId = $_SESSION['userId'];
$sql = "SELECT * FROM logindetails WHERE userId='$userId';";
// echo $sql;
$result = mysqli_query($poolConn, $sql);

if (!empty($result) && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($userData, $row);
    }
}

?>

<body style="background: #0f5a88 !important;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <div style='height: 100vh;' tabindex="-1" role="dialog" id="modalSignin">
        <div class="modal-dialog" role="document">
            <!-- Account -->
            <div class="modal-content rounded-5 shadow" style='background: #1A2226;' id='databseAccount'>
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h2 class="fw-bold mb-0 text-center" style="color: #0DB8DE !important;">My Account</h2>
                    <button class="btn btn-danger" onclick="enableEdit()" id="editButton">Edit</button>
                </div>

                <div class="modal-body p-5 pt-0">
                    <!-- profile image -->
                    <div class="col-md-12 mb-3 editView" hidden>
                        <label for="userImage" class="form-label text-white">Account Image</label>
                        <input type="file" class="form-control" id="userImage" accept="image/*" 
                            onchange="loadFile(event,this.id)">
                            <!-- <input type="file" accept="image/*"> -->
                    </div>
                    <div class="col-md-12 mb-3 editView" hidden>
                        <label for="imagePreview" class="text-white">image Preview</label><br>
                        <img src="images/userImage/default.png" alt="" id="imagePreview">
                    </div>
                    <!-- for only view -->
                    <div class="col-md-12 mb-3 viewOnly">
                        <div class="text-center">
                            <img src="images/userImage/default.png" alt="" id="profileImage">
                        </div>
                    </div>
                    <!-- profile image end-->
                    <!-- user type -->
                    <div class="col-md-12 mt-1 mb-3 text-center">
                        <h1 id="userType"></h1>
                    </div>
                    <div class="form-floating mb-3" id='groupSection' hidden>
                        <input type="text" class="form-control  inputStyle bordeRradius5" id="groupName"
                            placeholder="Host Name Of Database" disabled>
                        <label for="groupName" class='text-white'>Group Name</label>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="firstName"
                                    placeholder="Host Name Of Database" disabled>
                                <label for="firstName" class='text-white'>First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="lastName"
                                    placeholder="Host Name Of Database" disabled>
                                <label for="lastName" class='text-white'>Last Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="loginName"
                                    onkeyup="validateAndMatchEmail()" placeholder="Login Name" disabled>
                                <label for="loginName" class='text-white'>Email/Login ID</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control  inputStyle bordeRradius5" id="loginPassword"
                                    placeholder="Host Name Of Database" disabled>
                                <label for="loginPassword" class='text-white'>Login Password</label>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (isset($_SESSION['userType']) && $_SESSION['userType'] == "OWNER") {
                        echo '
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="form-floating mb-3">
                                         <input type="text" class="form-control  inputStyle bordeRradius5" id="hostName" placeholder="Host Name Of Database" disabled>
                                         <label for="hostName" class="text-white">Host Name</label>
                                     </div>
                                 </div>
                                 <div class="col-md-12">
                                     <div class="form-floating mb-3">
                                         <input type="text" class="form-control  inputStyle bordeRradius5" id="userName" placeholder="User Name Of Database" disabled>
                                         <label for="userName" class="text-white">User Name</label>
                                     </div>
                                 </div>
                                 <div class="col-md-12">
                                     <div class="form-floating mb-3">
                                         <input type="password" class="form-control  inputStyle bordeRradius5" id="dbPassWord" placeholder="Password Of Database" disabled>
                                         <label for="userName" class="text-white">Database Password</label>
                                     </div>
                                 </div>
                             </div>';
                    }
                    ?>

                    <div class="row">
                        <div class="col-mb-12">
                            <p class="error text-danger" id="error">

                            </p>
                            <p class="text-success success" id="success">

                            </p>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="mb-2 btn btn-lg rounded-4 checkButton" id="updateButton"
                            onclick="updateAcount('<?php echo $_SESSION['accountType'] ?>')" type="submit"
                            hidden>Update</button>

                    </div>
                    <hr class="my-4">
                    <h5 class="text-center text-white">All Rights Reserved At <a href='#'>Adarsh Mishra</a> </h5>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="js/myProfile.js?v=<?php echo rand(); ?>"></script>
<script>
    $(document).ready(function () {
        document.getElementById('loadingMessage').hidden = true;
        userMasterData = <?php echo json_encode($userData); ?>;
        loadDataInAccount();
    });
</script>

</html>