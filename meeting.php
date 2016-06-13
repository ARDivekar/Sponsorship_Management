<!DOCTYPE html>
<html lang="en">

<head>
	<?php
		include_once "UserNavBarImports.php";
		include_once "SponsEnums.php";
		include_once "table_output.php"

	?>
	<title>Meetings</title>

	<!-- DataTables CSS -->
	<link href="./User_GUI_CSS/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

	<!-- DataTables Responsive CSS -->
	<link href="./User_GUI_CSS/bower_components/datatables-responsive/css/responsive.dataTables.scss" rel="stylesheet">

	<!-- DataTables JavaScript -->
	<script src="./User_GUI_CSS/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
	<script src="./User_GUI_CSS/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

	<!-- Page-Level Demo Scripts - Tables - Use for reference -->
	<script>
		$(document).ready(function () {
			$('#dataTables-example').DataTable({
				responsive: true
			});
		});
	</script>

	<?php
		/*

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="./User_GUI_CSS/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="./User_GUI_CSS/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="./User_GUI_CSS/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="./User_GUI_CSS/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="./User_GUI_CSS/dist/css/sb-admin-2.css" rel="stylesheet">

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

    <!-- DataTables JavaScript -->
    <script src="./User_GUI_CSS/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="./User_GUI_CSS/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="./User_GUI_CSS/dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
                responsive: true
        });
    });
    </script>
	*/
	?>
	<?php
		$db = new SponsorshipDB();

		$meetingLatestQuery = new SQLQuery();
		$meetingLatestQuery->setSelectQuery(
			$tableName = SQLTables::Meeting,
			$tableFields = "*",
			$whereClause = SQLQuery::getWhereEquality([["SponsID", $_SESSION[SessionEnums::UserLoginID]]]),
			$groupByClause = NULL,
			$orderByClause = " Date DESC, Time DESC"
		);

		$meetingLatestResult = $db->select($meetingLatestQuery->getQuery());
		$latestMeeting = NULL;
		$latestMeetingRedirect = NULL;
		if(count($meetingLatestResult)>0){
			$latestMeeting = $meetingLatestResult[0];

			$httpBuildArray = [
				QueryFormSessionEnums::TableName => SQLTables::Meeting,
				QueryFormSessionEnums::QueryType => QueryTypes::Modify,
				QueryFieldNames::Submit => "true"
			];
			foreach(QueryFieldNames::mapDBToQueryForm(SQLTables::Meeting) as $meetingDBField => $meetingQueryField){
				if($meetingQueryField == QueryFieldNames::SponsMeetingOutcome)
					continue;
				$httpBuildArray[$meetingQueryField] = $latestMeeting[$meetingDBField];
			}
			$latestMeetingRedirect = "./query.php?".http_build_query($httpBuildArray);
		}





	?>

	<script type="text/javascript">
		function latestMeetingRedirect(){
			var meetingOutcomeTextarea = document.getElementById("meetingOutcomeTextarea");
			url = "<?php echo $latestMeetingRedirect?>"+"&Outcome="+meetingOutcomeTextarea.value;
			console.log(url);
			window.location = url;
			return false;
		}
	</script>

</head>

<body>

<div id="wrapper">

	<!-- Navigation -->
	<?php
		include("./UserNavBar.php");
	?>

	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Meeting</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>

		<div class="row">
			<?php
				echo generate_table_button(SQLTables::Meeting);
			?>
		</div>
		<br />
		<?php
			if($latestMeeting)
				echo '
		<div id="meetingOutcome" class="collapse row">
			<div class="col-md-offset-8">
				<div class="col-xs-12">
					<form>
						<textarea placeholder="Enter meeting outcome..." class="form-control" id="meetingOutcomeTextarea"></textarea>
						<br />
						<button type="submit" class="btn btn-success" onclick="return latestMeetingRedirect()">Submit</button>
					</form>
				</div>
			</div>
		</div>';
		?>
		<br />

		<!-- /.row -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						Meetings of <strong><?php echo $_SESSION[SessionEnums::UserSector]; ?></strong> Sector ( For CSO - All), Here we need to add an option of adding outcome for a particular meeting. It should be a column
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="dataTable_wrapper" style="overflow-x: scroll;">

							<?php
								$t = new TableOutput(
									$_SESSION[SessionEnums::UserAccessLevel],
									SQLTables::Meeting
								);

								$meetingQuery = $t->getOutputQuery();
								$meetingOutputResult = $db->select($meetingQuery);
								if(count($meetingOutputResult)>0)
									echo make_simple_table($meetingOutputResult, ["table", "table-striped", "table-bordered", "table-hover"], "dataTables-example");
								else echo "<h1>No meetings</h1>";
							?>

						</div>
						<!-- /.table-responsive -->
						
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>

	</div>
	<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->


</body>

</html>
