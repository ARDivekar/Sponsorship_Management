<?php 

    session_start();

    require('./DBconnect.php');

    require('./library_functions.php');

    $SponsID=$_SESSION['loginID']; //get SponsID from previos session

    $SponsAccessLevel = get_access_level($SponsID);

    if($SponsAccessLevel!="CSO")    

    header('Location: ./home.php');


    
    //Committee Members by Each Sector
    $Committe_Members_By_Each_Sector = mysql_query("SELECT YEAR(Date) as Year, MONTH(Date) as Month, sum(Amount) as Total_Sponsorship_Amount
                                        FROM AccountLog WHERE YEAR(Date)=2015
                                        GROUP BY Year, Month  
                                        ORDER BY `Year`  ASC"); 
    
    $rows = array(); 
    $table = array(); $table['cols'] = array( 
    // Labels for your chart, these represent the column titles 
    // Note that one column is in "string" format and another one is in 
    //"number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title 
    array('label' => 'Month', 'type' => 'string'),
    array('label' => 'Amount', 'type' => 'number')
 
    );
 

    while($result = mysql_fetch_assoc($Committe_Members_By_Each_Sector)) {
        
        $temp = array();
        $month="";

        if((int)$result['Month']==1)
            $month="Janurary";
        else if((int)$result['Month']==2)
            $month="Februrary";
        else if((int)$result['Month']==3)
            $month="March";
        else if((int)$result['Month']==4)
            $month="April";
        else if((int)$result['Month']==5)
            $month="May";
        else if((int)$result['Month']==6)
            $month="June";
        else if((int)$result['Month']==7)
            $month="July";
        else if((int)$result['Month']==8)
            $month="August";
        else if((int)$result['Month']==9)
            $month="September";
        else if((int)$result['Month']==10)
            $month="October";
        else if((int)$result['Month']==11)
            $month="November";
        else if((int)$result['Month']==12)
            $month="December";




        $temp[] = array('v' => (string)$month);

        // Values of each slice
        $temp[] = array('v' => (int)$result['Total_Sponsorship_Amount']);
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



    <div id="Amount_per_month_piechart" style="width: 900px; height: 500px;"></div>

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
          title: 'Amount per month'
        };

        var chart = new google.visualization.PieChart(document.getElementById('Amount_per_month_piechart'));

        
        function selectHandler() {
         var selectedItem = chart.getSelection()[0];

          if (selectedItem) {
            var month = data.getValue(selectedItem.row, 0);
            if(month=="Janurary")
                month=1;
            else if(month=="Februrary")
                month=2;
            else if(month=="March")
                month=3;
            else if(month=="April")
                month=4;
            else if(month=="May")
                month=5;
            else if(month=="June")
                month=6;
            else if(month=="July")
                month=7;
            else if(month=="August")
                month=8;
            else if(month=="September")
                month=9;
            else if(month=="October")
                month=10;
            else if(month=="November")
                month=11;
            else if(month=="December")
                month=12;
            window.open("./reportico/run.php?project=CSO%20Reports&xmlin=Amount_In_Month_"+month+".xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1")
          }
        }

        // Add our selection handler.
        google.visualization.events.addListener(chart, 'select', selectHandler);

        chart.draw(data, options);

      }



</script>


</html> 