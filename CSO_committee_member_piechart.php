<?php 

    session_start();

    require('./DBconnect.php');

    require('./library_functions.php');

    $SponsID=$_SESSION['loginID']; //get SponsID from previos session

    $SponsAccessLevel = get_access_level($SponsID);

    if($SponsAccessLevel!="CSO")    

    header('Location: ./home.php');


    
    //Committee Members by Each Sector
    $Committe_Members_By_Each_Sector = mysql_query("SELECT SponsOfficer.Sector, count(SponsOfficer.SponsID) as Role_Count
                FROM
                (SELECT SO.SponsID, Name, Sector, Role, DateAssigned, Branch, Year, Organization, EventName, Mobile, Email 
                    FROM (SELECT SponsID, Sector, DateAssigned FROM `SectorHead` UNION SELECT SponsID, Sector, DateAssigned FROM `SponsRep`) as SO 
                    INNER JOIN CommitteeMember ON CommitteeMember.ID = SO.SponsID
                ) AS SponsOfficer WHERE SponsOfficer.Role=\"Sector Head\"
                GROUP BY SponsOfficer.Sector, SponsOfficer.Role
                ORDER BY SponsOfficer.Sector ASC, SponsOfficer.Role ASC"); 
    
    $rows = array(); 
    $table = array(); $table['cols'] = array( 
    // Labels for your chart, these represent the column titles 
    // Note that one column is in "string" format and another one is in 
    //"number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title 
    array('label' => 'Sector', 'type' => 'string'),
    array('label' => 'SponsReps', 'type' => 'number')
 
    );
 

    while($result = mysql_fetch_assoc($Committe_Members_By_Each_Sector)) {
        
        $temp = array();
        $temp[] = array('v' => (string)$result['Sector']);

        // Values of each slice
        $temp[] = array('v' => (int)$result['Role_Count']);
        $rows[] = array('c' => $temp);
       
    }
 
    $table['rows'] = $rows;
    $jsonTable = json_encode($table);

?>
 
<html>

<head>

    <title>CSO Reports Page</title>

    <link id="reportico_css" rel="stylesheet" type="text/css" href="./reportico/.//css/reportico_bootstrap.css">

    <link id="bootstrap_css" rel="stylesheet" type="text/css" href="./reportico/.//css/bootstrap3/bootstrap.min.css">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="tabcontent.js" type="text/javascript"></script>
    <link href="tabcontent.css" rel="stylesheet" type="text/css" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



    <style type="text/css"></style>

    <style id="style-1-cropbar-clipper">/* Copyright 2014 Evernote Corporation. All rights reserved. */

    .en-markup-crop-options {

        top: 18px !important;
        left: 50% !important;
        margin-left: -100px !important;
        width: 200px !important;
        border: 2px rgba(255,255,255,.38) solid !important;
        border-radius: 4px !important;

    }

    .en-markup-crop-options div div:first-of-type {

        margin-left: 0px !important;
    }

    </style>

</head>

<body class="swMenuBody" style="margin: 0 auto; width:70%;">



<link id="reportico_css" rel="stylesheet" type="text/css" href="./reportico/./js/ui/jquery-ui.css">
<link id="PRP_StyleSheet" rel="stylesheet" type="text/css" href="./reportico/.//css/jquery.dataTables.css">
<link id="bootstrap_css" rel="stylesheet" type="text/css" href="./reportico/./js/nvd3/nv.d3.css">



<div id="reportico_container">

<form class="swMenuForm" name="topmenu" method="POST" action="./reportico/./run.php">




    <div class="navbar navbar-default" role="navigation">


        <div class="container" style="width: 100%">
            
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#reportico-bootstrap-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="nav-collapse collapse in" id="reportico-bootstrap-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="CSOreports.php">Go Back</a></li>
                </ul>
                <ul class="nav navbar-nav pull-right navbar-right">
                </ul>
            </div>

        </div>
    </div>
 

<h1 class="swTitle">Reports</h1>



    <div id="Committe_Members_By_Each_Sector_Piechart" style="width: 900px; height: 500px;"></div>

</form>

<div class="smallbanner">Sponsorship Management System<a href="#" target="_blank"></a></div>

</div>



 





</body>

<script>

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = new google.visualization.DataTable(<?=$jsonTable?>);

        var options = {
          title: 'Sector Head by Sector'
        };

        var chart = new google.visualization.PieChart(document.getElementById('Committe_Members_By_Each_Sector_Piechart'));

        
        function selectHandler() {
         var selectedItem = chart.getSelection()[0];

          if (selectedItem) {
            var sector = data.getValue(selectedItem.row, 0);
            if(sector=="Clothes Retail")
                sector = "Clothes_Retail";
            if(sector=="Music Stores")
                sector="Music_Stores";
            window.open("./reportico/run.php?project=CSO%20Reports&xmlin=Sector_Head_"+sector+".xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1")
          }
        }

        // Add our selection handler.
        google.visualization.events.addListener(chart, 'select', selectHandler);

        chart.draw(data, options);

      }



</script>


</html> 