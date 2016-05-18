<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<?php

if (empty($_POST['submit']))
    header("Location: home.php");

/*Resume old session:*/
session_start();

require('DBconnect.php');
require('library_functions.php');

$SponsID = $_SESSION['loginID']; //get SponsID from previos session

$FieldEmptyMessage = '<div align=center><h3 align=center style="padding: 40px; font-size:28px; line-height:50px;"  class="invalid_message">Error<br>You have not filled all the required fields.</h3> </div>';

$SponsRepBackButton = "<h2><a href='sponsrep.php' class='back_button'>Go back</a></h2><br>";
$SectorHeadBackButton = "<h2><a href='sectorhead.php' class='back_button'>Go back</a></h2><br>";
$CSOBackButton = "<h2><a href='CSO.php' class='back_button'>Go back</a></h2><br>";


$SponsAccessLevel = get_access_level($SponsID);
$SponsName = get_person_name($SponsID);
$SponsSector = get_person_sector($SponsID);

$table_message = $_SESSION['table_message'];
$main_query = $_SESSION['main_query'];


echo '<header align="center">
			<h1>Sponsorship Department</h1>';

if ($SponsAccessLevel == "SectorHead")
    echo $SectorHeadBackButton;

if ($SponsAccessLevel == "SponsRep")
    echo $SponsRepBackButton;

if ($SponsAccessLevel == "CSO")
    echo $CSOBackButton;

echo '</header>';

echo '<h3 class="SponsID">';
echo 'SponsID: ' . $SponsID;
echo '<br>Name: ' . get_person_name($SponsID);

$role = get_access_level($SponsID);
$printing_role = $role;
if ($role == 'SponsRep')
    $printing_role = "Sponsorship Representative";
if ($role == 'SectorHead')
    $printing_role = "Sector Head";
if ($role == 'CSO')
    $printing_role = "Chief Sponsorship Officer";
echo '<br>Role: ' . $printing_role;

if (get_access_level($SponsID) == "SponsRep" || get_access_level($SponsID) == "SectorHead") {
    echo '<br>Sector: ' . get_person_sector($SponsID);
}
echo '</h3>';
echo '<div align="center">';

if (isset($_POST['submit'])) {
    if (isset($_POST['order_by'])) {
        $order_by = $_POST['order_by'];
        $main_query = $main_query . " Order by `$order_by`";

        // echo $main_query;
    } else if (isset($_POST['search_by']) and isset($_POST['search_field']) and !empty($_POST['search_field'])) {
        $search_by = $_POST['search_by'];
        $search_field = $_POST['search_field'];
        $main_query = $main_query . " and $search_by LIKE '%$search_field%'";
    } else echo $FieldEmptyMessage;

    echo $table_message;
    $result = mysql_query($main_query);

    echo '
<script type="text/javascript">

	SponsID=document.getElementsByClassName("SponsID")[0];
	SponsID.hidden=true;

	function printpage() {
		document.getElementById("printButton").style.visibility="hidden";
		sort = document.getElementsByClassName("sort_form")[0];
		sort.hidden=true;
		search = document.getElementsByClassName("search_form")[0];
		search.hidden=true;
		header=document.getElementsByTagName("header")[0];
		header.hidden=true;

		SponsID=document.getElementsByClassName("SponsID")[0];
		SponsID.hidden=false;

		window.print();

		document.getElementById("printButton").style.visibility="visible";  
		sort.hidden=false;
		search.hidden=false;
		header.hidden=false;
		SponsID.hidden=true;
	}
</script>';
    echo '<button name="print" type="button" value="Print" id="printButton" onClick="printpage()">Print</button> ';
    print_sort($result);
    print_search($result);
    echo '<br>';
    print_table($result);

}

?>
</div>
</body>
</html>