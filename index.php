<!doctype html>
<html lang="en">
<?php
require 'header.php';
?>
<script>
    if (window.location.href.trim() == 'http://www.mysqlqueryeditor.rf.gd/?i=1') {
        window.location.href = 'https://www.mysqlqueryeditor.rf.gd/?i=1';
    }
</script>

<body style="background: linear-gradient(45deg, black, transparent);">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <div class="modal modal-signin position-static d-block py-5" style='height: 100vh;' tabindex="-1" role="dialog" id="modalSignin">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-5 shadow" style='background: #1A2226;'>
                <!--<div class="text-center">
                    <img src="images/lock.png" style="width: 25%;margin-top:35px;" alt="">
                </div>!-->
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h2 class="fw-bold mb-0 text-center" style="color: #0DB8DE !important;">Login</h2>
                </div>

                <div class="modal-body p-5 pt-0">

                    <div class="form-floating input-container">
                        <input class="input-field form-control  inputStyle bordeRradius5" type="text" placeholder="Username" id="loginId">
                        <i class="loginIcon fa-solid fa-envelope"></i>
                        <label for="hostName" class='text-white'>Email/Login Id</label>
                    </div>

                    <div class="form-floating input-container">
                        <input type="password" class="input-field form-control  inputStyle bordeRradius5" id="loginPassword" placeholder="Password">
                        <label for="loginPassword" class='text-white'>Login Password</label>
                        <i class="loginIcon loginPassIcon fa-solid fa-eye" onclick="showPassword(true)"></i>
                        <i class="loginIcon loginPassIcon fa-solid fa-eye-slash" onclick="showPassword(false)" hidden></i>
                    </div>

                    <div class="form-floating input-container">
                        <input class="input-field form-control  inputStyle bordeRradius5" type="text" placeholder="Sport" id="fSport">
                        <i class="loginIcon fas fa-running"></i>
                        <label for="fSport" class='text-white'>Favourite Sport</label>
                    </div>

                    <div class="form-floating input-container">
                        <input class="input-field form-control  inputStyle bordeRradius5" type="text" placeholder="Food" id="fFood">
                        <i class="loginIcon fas fa-utensils"></i>
                        <label for="fFood" class='text-white'>Favourite Food</label>
                    </div>

                    <div class="row">
                        <!-- For show message -->
                        <div class="col-mb-12">
                            <p class="error text-danger" id="error">
                            </p>
                            <p class="text-success success" id="success">
                            </p>
                        </div>
                    </div>
                    <button class="w-100 mb-2 btn btn-lg rounded-4 checkButton" onclick="checkLogin()" type="submit" id="loginButton">Login</button>
                    <div class="text-center my-2">
                        <a href="createDatabaseAccount.php">Not have account.Create Account</a><br>
                        <a href="tempLogin.php">Temporary Login(No Data Will Save).</a><br>
                    </div>
                    <audio id="audio" src="music/loginMusic.mp3" hidden></audio>
                    <hr class="my-3">
                    <h5 class="text-center text-white">All Rights Reserved At <a href='#'>Adarsh Mishra</a> </h5>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {
        document.getElementById('loadingMessage').hidden = true;
    });
</script>

</html>