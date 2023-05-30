<section id="nav">
    <div class="navLeft">
        <a href="dashboard.php" class="navIconcss">
            <h1>Dashboard</h1>
        </a>
    </div>
    <div class="search">
        <div class="alert alert-white alert-dismissible fade show" role="alert" style="margin:0px !important;color:black !important;">
            <strong><i>WELCOME </strong><?php echo "<i>".strtoupper($_SESSION['fullName'])."</i>"; ?> </i></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <div class="navRight">
        <div class="menu" onclick="toggle()">
            <span class="bar b1"></span>
            <span class="bar b2"></span>
            <span class="bar b3"></span>
        </div>

        <ul id="navList">
            <?php
            if($_SESSION['accountType']=='group'){
                echo '<li><a href="myMessage.php" class="navIconcss" title="MESSAGES"><i class="fa-solid fa-envelope"></i><span>MESSAGES</span></a></li>';
            }
            if($_SESSION['accountType']=='group' && $_SESSION['userType']=='OWNER'){
                echo '<li><a href="allMember.php" class="navIconcss" title="Manage Member"><i class="fa-solid fa-users-gear"></i></i><span class="text-nowrap">Manage Member</span></a></li>';
                echo '<li><a href="queryLog.php" class="navIconcss" title="Log Records"><i class="fas fa-list-alt"></i><span class="text-nowrap">Log Records</span></a></li>';
                // <i class="fas fa-list-alt"></i>
            }
            if ($_SESSION['accountInfo'] != "tempLogin"){
                echo '<li><a href="myProfile.php" class="navIconcss" title="My Account"><i class="fa-solid fa-user"></i><span>My Account</span></a></li>';
            }
            ?>
            <li><a href="#" class="navIconcss" title="Log Out" onclick="logOut('NATURAL')"><i class="fa-solid fa-right-from-bracket"></i><span>LOG OUT</span></a></li>
        </ul>
    </div>
</section>