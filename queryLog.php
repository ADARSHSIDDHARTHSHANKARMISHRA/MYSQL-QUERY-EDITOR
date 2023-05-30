<?php
session_start();
if (isset($_SESSION["login"])) {
    if ($_SESSION["login"] > 0 && $_SESSION['accountType'] == 'group' && $_SESSION['userType'] == 'OWNER') {
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

//json encode directly convert array into javascript array if declare in same file
?>

<body style="background: #0f5a88 !important;">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card my-4">
                        <div class="card-header">
                            <h3 class="text-center">Log Records</h3>
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
                                                Query
                                            </th>
                                            <th>
                                                Running Date
                                            </th>
                                            <th>
                                                Running Time
                                            </th>
                                            <th class='text-center'>
                                                User Type
                                            </th>
                                            <th class='text-center'>
                                                ACTIONS
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $groupId = $_SESSION['userId'];
                                        $sql2 = "SELECT logindetails.firstname,logindetails.userType,logindetails.lastname,logrecord.* from logindetails inner join logrecord on logindetails.userId=logrecord.userId and logindetails.groupId=logrecord.groupId and logrecord.groupId='$groupId';";
                                        // echo $sql;
                                        $result = $poolConn->query($sql2);
                                        if ($result->num_rows > 0) {
                                            $rowId = 1;
                                            // output data of each row
                                            while ($row = $result->fetch_assoc()) {

                                                $userTypeCss = "badge bg-info text-black";
                                                if ($row['userType'] == "EDITOR")
                                                    $userTypeCss = "badge bg-warning text-black";
                                                else if ($row['userType'] == "VIEWER")
                                                    $userTypeCss = "badge bg-view text-black";

                                                $logTimeArray = explode(":%:", $row['logTime']);
                                                $logDate = $logTimeArray[0];
                                                $logTime = $logTimeArray[1];
                                                // print_r($logTimeArray);
                                        

                                                echo "
                                                <tr  class='text-dark'>
                                                    <td>" . $rowId . "</td> 
                                                    <td>" . $row["firstname"] . ' ' . $row["lastname"] . "</td> 
                                                    <td>" . $row["logQuery"] . "</td> 
                                                    <td>" . $logDate . "</td> 
                                                    <td>" . $logTime . "</td> 
                                                    <td class='text-center'><span class='" . $userTypeCss . "'>" . $row["userType"] . "</span></td>
                                                    <td class='project-actions text-center'>
                                                    <a class='btn btn-danger btn-sm' href='#'  id = " . $row['logId'] . " title='click hear to Delete Query Log' onclick='deleteLogRecord(this.id)' >
                                                    <i class='fas fa-trash-alt'></i>
                                                    </a><br>
                                                    </td>
                                                    </tr>
                                                        ";


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
<script>
    $(document).ready(function () {
        document.getElementById('loadingMessage').hidden = true;
    });
    function deleteLogRecord(logId) {
        console.log(logId);
        if (confirm('Are you sure want to delete record ?')) {
            $.post('allQuery.php', {
                'flagForDeleteLogRecord': 'flagForDeleteLogRecord',
                'logId': logId
            }, function (data) {
                data = data.trim();
                console.log(data);
                if (data == 'true') {
                    location.reload();
                }
            });
        }
    }
</script>

</html>