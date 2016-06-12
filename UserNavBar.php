<?php
	/*Resume old session:*/


	include_once "library_functions.php";
	include_once "SponsEnums.php";

	if(!isset($_SESSION[SessionEnums::UserLoginID]))
		session_start();
	if(!$_SESSION[SessionEnums::UserLoginID])
		header("Location: login.php");



?>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        
        <a class="navbar-brand" href="home.php">Sponsorship Department</a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <label><?php echo $_SESSION[SessionEnums::UserName]; ?></label><i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="home.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                </li>
                <li><a data-toggle="collapse" data-target="#changePassword"><i class="fa fa-gear fa-fw"></i> Change Password</a>
                </li>
                <li class="divider"></li>
                <li><a href="./logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="homepage.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="companies.php"><i class="fa fa-building-o fa-fw"></i> Companies</a>
                </li>
                <li>
                    <a href="companyexec.php"><i class="fa fa-building-o fa-fw"></i> Company Executives</a>
                </li>
		        <li>
                    <a href="meeting.php"><i class="fa fa-calendar fa-fw"></i> Meeting Log</a>
                </li>
                <li>
                    <a href="accounts.php"><i class="fa fa-inr fa-fw"></i> <?php echo $_SESSION[SessionEnums::UserAccessLevel] != UserTypes::SponsRep ? "Account Log" : "My Sponsorships"?></a>
                </li>

				<?php
					if($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO)
				echo '
				<li id="reports">
                    <a href="CSOreports.php"><i class="fa fa-line-chart fa-fw"></i> Reports</a>
                </li>';

				if($_SESSION[SessionEnums::UserAccessLevel] != UserTypes::SponsRep)
                echo '
				<li>
                    <a href="sectorhead.php"><i class="fa fa-user fa-fw"></i> '.($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO? "Sector Heads" : "My details").'</a>
                </li>';
				?>
                <li>
                    <a href="sponsrep.php"><i class="fa fa-users fa-fw"></i>
						<?php
							switch($_SESSION[SessionEnums::UserAccessLevel]){
								case UserTypes::CSO:
									echo "Sponsorship Representatives";
									break;
								case UserTypes::SectorHead:
									echo "Sponsorship Representatives";
									break;
								case UserTypes::SponsRep:
									echo "My details";
									break;
							}
						?>

					</a>
                </li>


            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>