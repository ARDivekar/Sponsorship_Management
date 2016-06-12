<!DOCTYPE html>
<html lang="en">

<head>
	<?php
		include_once "SponsEnums.php";

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
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-bar-chart-o fa-fw"></i> Graph Showing Progress of Money
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
									Actions
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu pull-right" role="menu">
									<li><a href="#">Action</a>
									</li>
									<li><a href="#">Another action</a>
									</li>
									<li><a href="#">Something else here</a>
									</li>
									<li class="divider"></li>
									<li><a href="#">Separated link</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div id="money-progress"></div>
						<!--<?php 
						/*	$MoneyProgress= $db->select("SELECT SUM(Amount) as 'Amount', MONTH(Date) as 'Month' FROM `accountlog` GROUP BY MONTH(Date)");
							echo $MoneyProgress[0]['Count'];

							for ($x = 0; $x <= $MoneyProgress.length; $x++) {
								$array = (
									$MoneyProgress[i][]

								)    
							} 
							echo "<script>

									new Morris.Line({
									  // ID of the element in which to draw the chart.
									  element: 'money-progress',
									  // Chart data records -- each entry in this array corresponds to a point on
									  // the chart. " + "
									  data: [
									    { year: '2008', value: 20 },
									    { year: '2009', value: 10 },
									    { year: '2010', value: 5 },
									    { year: '2011', value: 5 },
									    { year: '2012', value: 20 }
									  ],
									  // The name of the data record attribute that contains x-values.
									  xkey: 'month',
									  // A list of names of data record attributes that contain y-values.
									  ykeys: ['amount'],
									  // Labels for the ykeys -- will be displayed when you hover over the
									  // chart.
									  labels: ['Amount']
									});

								</script>" */
						?> -->
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
				<!-- <div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-bar-chart-o fa-fw"></i> Bar Chart Example
						<div class="pull-right">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
									Actions
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu pull-right" role="menu">
									<li><a href="#">Action</a>
									</li>
									<li><a href="#">Another action</a>
									</li>
									<li><a href="#">Something else here</a>
									</li>
									<li class="divider"></li>
									<li><a href="#">Separated link</a>
									</li>
								</ul>
							</div>
						</div>
					</div> -->
					<!-- /.panel-heading -->
					<!-- <div class="panel-body">
						<div class="row">
							<div class="col-lg-4">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped">
										<thead>
										<tr>
											<th>#</th>
											<th>Date</th>
											<th>Time</th>
											<th>Amount</th>
										</tr>
										</thead>
										<tbody>
										<tr>
											<td>3326</td>
											<td>10/21/2013</td>
											<td>3:29 PM</td>
											<td>$321.33</td>
										</tr>
										<tr>
											<td>3325</td>
											<td>10/21/2013</td>
											<td>3:20 PM</td>
											<td>$234.34</td>
										</tr>
										<tr>
											<td>3324</td>
											<td>10/21/2013</td>
											<td>3:03 PM</td>
											<td>$724.17</td>
										</tr>
										<tr>
											<td>3323</td>
											<td>10/21/2013</td>
											<td>3:00 PM</td>
											<td>$23.71</td>
										</tr>
										<tr>
											<td>3322</td>
											<td>10/21/2013</td>
											<td>2:49 PM</td>
											<td>$8345.23</td>
										</tr>
										<tr>
											<td>3321</td>
											<td>10/21/2013</td>
											<td>2:23 PM</td>
											<td>$245.12</td>
										</tr>
										<tr>
											<td>3320</td>
											<td>10/21/2013</td>
											<td>2:15 PM</td>
											<td>$5663.54</td>
										</tr>
										<tr>
											<td>3319</td>
											<td>10/21/2013</td>
											<td>2:13 PM</td>
											<td>$943.45</td>
										</tr>
										</tbody>
									</table>
								</div>
					 -->			<!-- /.table-responsive -->
							</div>
							<!-- /.col-lg-4 (nested) -->
					<!-- 		<div class="col-lg-8">
								<div id="morris-bar-chart"></div>
							</div>
					 -->		<!-- /.col-lg-8 (nested) -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->

			</div>
			<!-- /.col-lg-8 -->

		</div>
		<!-- /.row -->
	</div>
	<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->


</body>

</html>
