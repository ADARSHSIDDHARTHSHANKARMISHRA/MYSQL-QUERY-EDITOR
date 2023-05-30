<!doctype html>
<html lang="en">
<?php
require_once 'pooldb.php';
require 'header.php';

try {
    $sql = "select loginName from logindetails";
    $result = mysqli_query($poolConn, $sql);
    $emailArray = [];
    if (!empty($result) && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($emailArray, $row);
        }
    }
} catch (Exception $e) {
    echo $e;
}

?>


<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <div class="modal modal-signin position-static d-block py-5" style='height: 100vh;' tabindex="-1" role="dialog"
        id="modalSignin">
        <div class="modal-dialog" role="document">
            <!-- Ask Account Type -->
            <div class="modal-content rounded-5 shadow" style='background: #1A2226;' id='accountType'>
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h2 class="fw-bold mb-0 text-center" style="color: #0DB8DE !important;">Select Account Type</h2>
                </div>

                <div class="modal-body p-5 pt-0">
                    <div class="form-floating mb-3">
                        <button class="w-100 mb-2 btn btn-lg rounded-4 checkButton"
                            onclick="showUsertypeSection('single')" type="submit">Single Account</button>
                        <button class="w-100 mb-2 btn btn-lg rounded-4 checkButton"
                            onclick="showUsertypeSection('group')" type="submit">Group Account</button>
                        <a href="index.php" class="text-right">Click Here To Login.</a>
                    </div>
                </div>
            </div>
            <!-- Account -->
            <div class="modal-content rounded-5 shadow" style='background: #1A2226;width: 110% !important;'
                id='databseAccount' hidden>
                <div class="modal-header p-5 pb-4 border-bottom-0 col-12">
                    <div class="col-9">
                    <h2 class="fw-bold mb-0 text-center" style="color: #0DB8DE !important;">Create Account</h2>
                    </div>
                    <div class="col-3">
                        <a href="createDatabaseAccount.php"><button class="btn btn-danger">< Back</button></a>
                    </div>
                </div>

                <div class="modal-body p-5 pt-0">
                    <!-- profile image -->
                    <div class="col-md-12 mb-3">
                        <label for="userImage" class="form-label text-white">Account Image</label>
                        <input type="file" class="form-control" id="userImage" accept="image/*"
                            onchange="loadFile(event,this.id)">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="imagePreview" class="text-white">Image Preview</label>
                        <img src="images/userImage/default.png" alt="" id="imagePreview">
                    </div>
                    <div class="form-floating mb-3" id='groupSection' hidden>
                        <input type="text" class="form-control  inputStyle bordeRradius5" id="groupName"
                            placeholder="Host Name Of Database">
                        <label for="groupName" class='text-white'>Group Name</label>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="firstName"
                                    placeholder="Host Name Of Database">
                                <label for="firstName" class='text-white'>First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="lastName"
                                    placeholder="Host Name Of Database">
                                <label for="lastName" class='text-white'>Last Name</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="loginName"
                                    onkeyup="validateAndMatchEmail()" onchange="checkDuplicateEmail(this.value)"
                                    placeholder="Login Name">
                                <label for="loginName" class='text-white'>Email/Login ID</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="confirmEmail"
                                    onkeyup="validateAndMatchEmail()" placeholder="Confirm Login Name">
                                <label for="confirmEmail" class='text-white'>Confirm Email/Login Id</label>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control  inputStyle bordeRradius5" id="loginPassword"
                                    placeholder="Host Name Of Database">
                                <label for="loginPassword" class='text-white'>Login Password</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="hostName"
                                    placeholder="Host Name Of Database">
                                <label for="hostName" class='text-white'>Host Name</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="userName"
                                    placeholder="User Name Of Database">
                                <label for="userName" class='text-white'>User Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="dbPassWord"
                                    placeholder="Password Of Database">
                                <label for="userName" class='text-white'>Database Password</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="fSport"
                                    placeholder="User Name Of Database">
                                <label for="fSport" class='text-white'>Favourite  Sport</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control  inputStyle bordeRradius5" id="fFood"
                                    placeholder="Password Of Database">
                                <label for="fFood" class='text-white'>Favourite Food</label>
                            </div>
                        </div>
                        <p style="margin-left:7px"><span class="text-danger">Note : </span><span class="text-white">These information is used for authentication.</span></p>
                    </div>
                    

                    <div class="row">
                        <div class="col-mb-12">
                            <p class="error text-danger" id="error">

                            </p>
                            <p class="text-success success" id="success">

                            </p>
                        </div>
                    </div>
                    <button class="w-100 mb-2 btn btn-lg rounded-4 checkButton" id="registerButton"
                        onclick="createAccount()" type="submit">Register</button>
                    <audio id="audio" src="music/loginMusic.mp3" hidden></audio>
                    <hr class="my-4">
                    <h5 class="text-center text-white">All Rights Reserved At <a href='#'>Adarsh Mishra</a> </h5>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="js/createDatabaseAccount.js?v=<?php echo rand(); ?>"></script>
<script>
    //Disabled copy paster for confirm email
    $('#confirmEmail').bind("cut copy paste", function (e) {
        e.preventDefault();
    });

    $(document).ready(function () {
        masterEArray = (<?php echo json_encode($emailArray); ?>);
    document.getElementById('loadingMessage').hidden = true;
    });
</script>

</html>