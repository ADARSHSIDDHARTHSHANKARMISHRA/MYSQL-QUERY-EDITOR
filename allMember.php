<?php
session_start();
if (isset($_SESSION["login"])) {
    if ($_SESSION["login"] > 0 && $_SESSION['accountType']=='group' && $_SESSION['userType']=='OWNER') {
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
$sql = "select loginName from logindetails";
$result = mysqli_query($poolConn, $sql);
$emailArray = [];
if(!empty($result) && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($emailArray, $row);
    }
}
//json encode directly convert array into javascript array if declare in same file
?>

<body style="background: #0f5a88 !important;">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- add member -->
                    <div class="card my-4">
                        <div class="card-header">
                            <h4 class="text-center">Add Member</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="firstName" class="col-6">Enter First Name</label>
                                    <input type="text" id="firstName" class="col-11">
                                </div>
                                <div class="col-md-4">
                                    <label for="lastName" class="col-6">Enter Last Name</label>
                                    <input type="text" id="lastName" class="col-11">
                                </div>
                                <div class="col-md-4">
                                    <label for="userType" class="col-6">Select User Type</label>
                                    <select name="userType" id="userType" class="col-11" style="height: 28px;">
                                        <option value="">USER TYPE</option>
                                        <option value="EDITOR">EDITOR</option>
                                        <option value="VIEWER">VIEWER</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="loginId" class="col-6">Login Id/Email Id</label>
                                    <input type="text" class="col-11" id="loginId" onchange="checkDuplicateEmail(this.value)">
                                </div>
                                <div class="col-md-4">
                                    <label for="loginPassword" class="col-6">Login Password</label>
                                    <input type="text" id="loginPassword" class="col-11">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 text-center">
                                    <p id="errorMessage" class="error text-danger"></p>
                                </div>
                                <div class="col-12 text-center">
                                    <p id="successMessage" class="success text-success"></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button class="btn btn-success" id="addButton" onclick="addMember()">Add Member</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- add member end -->
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">Manage Member</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="example1">
                                    <thead>
                                        <tr>
                                            <th>
                                                Sr No
                                            </th>
                                            <th>
                                                Member Name
                                            </th>
                                            <th>
                                                Login Name
                                            </th>
                                            <th>
                                                Login Password
                                            </th>
                                            <th class='text-center'>
                                                Member Type
                                            </th>
                                            <th class='text-center'>
                                                ACTIONS
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $groupId = $_SESSION['userId'];
                                        $sql2    = "SELECT * FROM logindetails WHERE groupId=$groupId  ORDER BY userId DESC";
                                        // echo $sql;
                                        $result = $poolConn->query($sql2);
                                        if ($result->num_rows > 0) {
                                            $rowId = 1;
                                            // output data of each row
                                            while ($row = $result->fetch_assoc()) {
                                                $id = $row['userId'];
                                                $userTypeCss = "badge bg-info text-black";
                                                if ($row['userType'] == "EDITOR")
                                                    $userTypeCss = "badge bg-warning text-black";
                                                else if ($row['userType'] == "VIEWER")
                                                    $userTypeCss = "badge bg-view text-black";

                                                echo "
                                                <tr  class='text-dark'>
                                                    <td>" . $rowId . "</td> 
                                                    <td>" . $row["firstname"] . ' ' . $row["lastname"] . "</td> 
                                                    <td>" . $row["loginName"] . "</td> 
                                                    <td>" . $row["loginPassword"] . "</td> 
                                                    <td class='text-center'><span class='" . $userTypeCss . "'>" . $row["userType"] . "</span></td>
                                                    <td class='project-actions text-center'>
                                                        ";

                                                // echo "<a class='btn btn-secondary btn-sm' href='#' onclick='facultyEdit(this.id)' id = " . $id . " title='click hear to Edit'>
                                                //             <i class='fas fa-user-edit'>
                                                //             </i>
                                                //         </a>
                                                //         ";

                                                if ($row["userType"] != 'OWNER') {
                                                    echo " <a class='btn btn-danger btn-sm' href='#'  id = " . $id . ':%:' . $row['firstname'] . ' ' . $row['lastname'] . " title='click hear to Delete Student' onclick='deleteMember(this.id)' >
                                                    <i class='fas fa-trash-alt'></i>
                                                </a><br>
                                            </td>
                                        </tr>
                                        ";
                                                }

                                                $rowId += 1;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>

</body>
<script src="js/allMember.js?v=<?php echo rand(); ?>"></script>
<script>
    $(document).ready(function() {
        masterEArray=(<?php echo json_encode($emailArray);?>);
        document.getElementById('loadingMessage').hidden = true;
    });
</script>

</html>