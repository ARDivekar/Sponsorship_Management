<!DOCTYPE html>
<html lang="en">

<head>
	<?php
		include_once "SponsEnums.php";
		include_once "table_output.php";

		if(!isset($_SESSION[SessionEnums::UserLoginID]))
		session_start();
		if(!$_SESSION[SessionEnums::UserLoginID])
			header("Location: login.php");

		include('UserNavBarImports.php');
	?>
	<title>CSO profile</title>
	<!-- Timeline CSS -->
	<link href="./User_GUI_CSS/dist/css/timeline.css" rel="stylesheet">

	<!-- Morris Charts CSS -->
	<link href="./User_GUI_CSS/bower_components/morrisjs/morris.css" rel="stylesheet">

	<!-- Morris Charts JavaScript -->
	<script src="./User_GUI_CSS/bower_components/raphael/raphael-min.js"></script>
	<script src="./User_GUI_CSS/bower_components/morrisjs/morris.min.js"></script>
	<script src="./User_GUI_CSS/js/morris-data.js"></script>

	<?php
		/*
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Janit Mehta, Abhishek Divekar, Advik Shetty">

		<title>Sponsorship Department</title>

		<!-- Bootstrap Core CSS -->
		<link href="./User_GUI_CSS/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

		<!-- MetisMenu CSS -->
		<link href="./User_GUI_CSS/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

		<!-- Timeline CSS -->
		<link href="./User_GUI_CSS/dist/css/timeline.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="./User_GUI_CSS/dist/css/sb-admin-2.css" rel="stylesheet">

		<!-- Morris Charts CSS -->
		<link href="./User_GUI_CSS/bower_components/morrisjs/morris.css" rel="stylesheet">

		<!-- Custom Fonts -->
		<link href="./User_GUI_CSS/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="./User_GUI_CSS/js/oss.maxcdn.com-libs-html5shiv-3.7.0-html5shiv.js"></script>
			<script src="./User_GUI_CSS/js/oss.maxcdn.com-libs-respond.js-1.4.2-respond.min.js"></script>
		<![endif]-->


		<!-- jQuery -->
		<script src="./User_GUI_CSS/bower_components/jquery/dist/jquery.min.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="./User_GUI_CSS/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

		<!-- Metis Menu Plugin JavaScript -->
		<script src="./User_GUI_CSS/bower_components/metisMenu/dist/metisMenu.min.js"></script>

		<!-- Morris Charts JavaScript -->
		<script src="./User_GUI_CSS/bower_components/raphael/raphael-min.js"></script>
		<script src="./User_GUI_CSS/bower_components/morrisjs/morris.min.js"></script>
		<script src="./User_GUI_CSS/js/morris-data.js"></script>

		<!-- Custom Theme JavaScript -->
		<script src="./User_GUI_CSS/dist/js/sb-admin-2.js"></script>
		*/
	?>
</head>

<body>
	<?php 
		
		/*Resume old session:*/
		// session_start();

		// include_once('DBconnect.php');
		// include_once('library_functions.php');
		// $SponsID=$_SESSION[SessionEnums::UserLoginID]; //get SponsID from previous session


	?>


<div id="wrapper">

	<!-- Navigation -->
	<?php
		include("./UserNavBar.php");
	?>


	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Dashboard</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-building-o fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php
										$TotalCompaniesSponsored = $db->select("SELECT COUNT(Title) as 'Count' FROM AccountLog WHERE TransType='Deposit'");
										echo $TotalCompaniesSponsored[0]['Count'];
									?>
								</div>
								<div>Companies Signed</div>
							</div>
						</div>
					</div>
					<a href="companies.php">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-green">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-inr fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php 
										$TotalIncome= $db->select("SELECT SUM(Amount) as 'Sum' FROM AccountLog WHERE TransType='Deposit'");
										echo $TotalIncome[0]['Sum'];
									?>
								</div>
								<div>Amount Earned</div>
							</div>
						</div>
					</div>
					<a href="accounts.php">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-yellow">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-calendar fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php 
										$MeetingsCount= $db->select("SELECT Count(MeetingType) as 'Count' FROM `meeting` WHERE MeetingType='Meet'");
										echo $MeetingsCount[0]['Count'];
									?>
								</div>
								<div>Meetings Scheduled</div>
							</div>
						</div>
					</div>
					<a href="meeting.php">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-phone fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge">
									<?php 
										$CompaniesCalledCount= $db->select("SELECT COUNT(CMPStatus) as 'Count' FROM `company` WHERE CMPStatus != 'Not called'");
										echo $CompaniesCalledCount[0]['Count'];
									?>
								</div>
								<div>Companies Called</div>
							</div>
						</div>
					</div>
					<a href="companies.php">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-bar-chart-o fa-fw"></i>&nbsp;&nbsp;Money Progress
					</div>
					<div class="panel-body" style="overflow-x:scroll;">
						<div id="money-progress"></div>
						<script>
							
							new Morris.Line({
							  element: 'money-progress', 
							  data: <?php 
							  $MoneyProgress= $db->select("SELECT SUM(Amount) as 'amount', WEEK(Date) as 'week' FROM `accountlog` GROUP BY WEEK(Date)");
							  echo json_encode($MoneyProgress);
							  ?>,
							  xkey: ['week'],
							  ykeys: ['amount'],
							  labels: ['Amount']
							});

						</script> 
					</div>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-calendar"></i>&nbsp;&nbsp;5 Most Recent Meetings Scheduled
					</div>
					<div class="panel-body" style="overflow-x:scroll;">
						<?php
							if($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::CSO){
								$query = "SELECT m.Date as 'Date', m.Time as 'Time', m.MeetingType as 'Type', CMPName as 'Company', c.Name as 'Name' FROM `meeting` m 
								INNER JOIN `sponsrep` s ON (m.SponsID=s.SponsID) INNER JOIN `committeemember` c ON (c.ID=s.SponsID) 
								UNION 
								SELECT m.Date as 'Date', m.Time as 'Time', m.MeetingType as 'Type', 
								CMPName as 'Company', c.Name as 'Name' FROM `meeting` m INNER JOIN `sectorhead` s ON (m.SponsID=s.SponsID) INNER JOIN 
								`committeemember` c ON (c.ID=s.SponsID)  ORDER BY Date DESC, TIME DESC LIMIT 5";
								$LatestMeetings = $db->select($query);
							}
							else if($_SESSION[SessionEnums::UserAccessLevel] == UserTypes::SectorHead){
								$query = "SELECT m.Date as 'Date', m.Time as 'Time', m.MeetingType as 'Type', CMPName as 'Company', c.Name as 'Name' FROM `meeting` m 
								INNER JOIN `sponsrep` s ON (m.SponsID=s.SponsID) INNER JOIN `committeemember` c ON (c.ID=s.SponsID) 
								WHERE s.Sector='" + $_SESSION[SessionEnums::UserSector] + "' UNION 
								SELECT m.Date as 'Date', m.Time as 'Time', m.MeetingType as 'Type', 
								CMPName as 'Company', c.Name as 'Name' FROM `meeting` m INNER JOIN `sectorhead` s ON (m.SponsID=s.SponsID) INNER JOIN 
								`committeemember` c ON (c.ID=s.SponsID) WHERE s.Sector='" +  $_SESSION[SessionEnums::UserSector] + "'  ORDER BY Date DESC, TIME DESC LIMIT 5";
								$LatestMeetings = $db->select($query);
							}
							else{
								$query = "SELECT m.Date as 'Date', m.Time as 'Time', m.MeetingType as 'Type', CMPName as 'Company' FROM `meeting` m 
								INNER JOIN `sponsrep` s ON (m.SponsID=s.SponsID) WHERE s.Sector='" + $_SESSION[SessionEnums::UserSector] + "' ORDER BY Date DESC, TIME DESC LIMIT 5";
								$LatestMeetings = $db->select($query);
							} 

							echo make_simple_table($LatestMeetings, ["table", "table-striped", "table-bordered", "table-hover"], "dataTables-example");
						
						?>
					</div>
				</div>
			</div>
			</div>

		</div>

	</div>
</div>

</body>

</html>
