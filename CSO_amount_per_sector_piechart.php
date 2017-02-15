<?php 

    session_start();

    require('./DBconnect.php');

    require('./library_functions.php');

    $SponsID=$_SESSION['loginID']; //get SponsID from previos session

    $SponsAccessLevel = get_access_level($SponsID);

    if($SponsAccessLevel!="CSO")    

    header('Location: ./home.php');


    //Amount Earned By Each Sector Pie Chart
    $Amount_Earned_by_IT_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"IT\" GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;");
    $Amount_Earned_by_IT_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_IT_Sector);
    $IT = (int)$Amount_Earned_by_IT_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_Builders_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                              (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                WHERE Sector=\"Builders\"
                              GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;");
    $Amount_Earned_by_Builders_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Builders_Sector);
    $Builders = (int)$Amount_Earned_by_Builders_Sector_Result["Amount_Earned_by_Sector_in_Rupees"]; 

    $Amount_Earned_by_Clothes_Retail_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Clothes Retail\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Clothes_Retail_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Clothes_Retail_Sector);
    $Clothes_Retail = (int)$Amount_Earned_by_Clothes_Retail_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];


    $Amount_Earned_by_Ecommerce_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Ecommerce\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Ecommerce_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Ecommerce_Sector);
    $Ecommerce = (int)$Amount_Earned_by_Ecommerce_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_Electricals_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Electricals\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Electricals_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Electricals_Sector);
    $Electricals = (int)$Amount_Earned_by_Electricals_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_FMCG_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"FMCG\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_FMCG_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_FMCG_Sector);
    $FMCG = (int)$Amount_Earned_by_FMCG_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_Hotel_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Hotel\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Hotel_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Hotel_Sector);
    $Hotel = (int)$Amount_Earned_by_Hotel_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_Music_Stores_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Music Stores\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Music_Stores_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Music_Stores_Sector);
    $Music_Stores = (int)$Amount_Earned_by_Music_Stores_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_Radio_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Radio\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Radio_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Radio_Sector);
    $Radio = (int)$Amount_Earned_by_Radio_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_Steel_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Steel\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Steel_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Steel_Sector);
    $Steel = (int)$Amount_Earned_by_Steel_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_Travel_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Travel\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Travel_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Travel_Sector);
    $Travel = (int)$Amount_Earned_by_Travel_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

    $Amount_Earned_by_Tyres_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 
                                  (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 
                                    UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 
                                    WHERE Sector=\"Tyres\"
                                  GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 
    $Amount_Earned_by_Tyres_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Tyres_Sector);
    $Tyres = (int)$Amount_Earned_by_Tyres_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];

?>
 
<html>

<head>

    <title>Reports Page</title>

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

    <div id="Amount_By_Each_Sector_Piechart" style="width: 900px; height: 500px;"></div>


</form>

<div class="smallbanner">Sponsorship Management System<a href="#" target="_blank"></a></div>

</div>



 





</body>

<script>

      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Sector','Amount'],
            ['Builders', <?php echo $Builders?>],
            ['Clothes Retail', <?php echo $Clothes_Retail;?>],
            ['Ecommerce', <?php echo $Ecommerce;?>],
            ['Electricals', <?php echo $Electricals;?>],
            ['FMCG', <?php echo $FMCG;?>],
            ['Hotel', <?php echo $Hotel;?>],
            ['IT', <?php echo $IT;?>],
            ['Music Stores', <?php echo $Music_Stores;?>],
            ['Radio', <?php echo $Radio;?>],
            ['Steel', <?php echo $Steel;?>],
            ['Travel', <?php echo $Travel;?>],
            ['Tyres', <?php echo $Tyres;?>]
        ]);

        var options = {
          title: 'Amount_Earned_by_Sector'
        };

        var chart = new google.visualization.PieChart(document.getElementById('Amount_By_Each_Sector_Piechart'));



        function selectHandler() {
         var selectedItem = chart.getSelection()[0];

          if (selectedItem) {
            var sector = data.getValue(selectedItem.row, 0);
            if(sector=="Clothes Retail")
                sector = "Clothes_Retail";
            if(sector=="Music Stores")
                sector="Music_Stores";
            window.open("./reportico/run.php?project=CSO%20Reports&xmlin=Amount_Earned_By_"+sector+"_Sector.xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1")
          }
        }

        // Add our selection handler.
        google.visualization.events.addListener(chart, 'select', selectHandler);

        chart.draw(data, options);

      }



</script>


</html> 