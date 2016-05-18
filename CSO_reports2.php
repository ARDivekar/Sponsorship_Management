<?php 

    session_start();

    require('./DBconnect.php');

    require('./library_functions.php');

    $SponsID=$_SESSION['loginID']; //get SponsID from previos session

    $SponsAccessLevel = get_access_level($SponsID);

    if($SponsAccessLevel!="CSO")    

        header('Location: ./home.php');

?>





<html><head>

<title>CSO Reports Page</title>

<link id="reportico_css" rel="stylesheet" type="text/css" href="./reportico/.//css/reportico_bootstrap.css">

<link id="bootstrap_css" rel="stylesheet" type="text/css" href="./reportico/.//css/bootstrap3/bootstrap.min.css">

  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script src="tabcontent.js" type="text/javascript"></script>

<link href="tabcontent.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css"></style><style id="style-1-cropbar-clipper">/* Copyright 2014 Evernote Corporation. All rights reserved. */

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

</style></head>

<body class="swMenuBody" style="margin: 0 auto; width:70%;">



<link id="reportico_css" rel="stylesheet" type="text/css" href="./reportico/./js/ui/jquery-ui.css">

<link id="PRP_StyleSheet" rel="stylesheet" type="text/css" href="./reportico/.//css/jquery.dataTables.css">

<link id="bootstrap_css" rel="stylesheet" type="text/css" href="./reportico/./js/nvd3/nv.d3.css">



<!--

<script type="text/javascript" src="./reportico/./js/jquery.js"></script>





<script type="text/javascript" src="./reportico/./js/ui/jquery-ui.js"></script>





<script type="text/javascript" src="./reportico/./js/reportico.js"></script>



<script type="text/javascript" src="./reportico/./js/bootstrap3/bootstrap.min.js"></script>



 <script type="text/javascript" src="./reportico/./js/ui/i18n/jquery.ui.datepicker-en-GB.js"></script> 







<script type="text/javascript">var reportico_datepicker_language = "yy-mm-dd";</script>

<script type="text/javascript">var reportico_this_script = "./reportico/./run.php";</script>

<script type="text/javascript">var reportico_ajax_script = "./reportico/./run.php";</script>



<script type="text/javascript">var reportico_bootstrap_modal = true;</script>



<script type="text/javascript">var reportico_ajax_mode = "1";</script>



<script type="text/javascript">var reportico_dynamic_grids = false;</script>

 -->

<!-- <script type="text/javascript" src="./reportico/./js/jquery.dataTables.min.js"></script> -->





<!-- <script type="text/javascript" src="./reportico/./js/nvd3/d3.min.js"></script> -->

<!-- <script type="text/javascript" src="./reportico/./js/nvd3/nv.d3.js"></script> -->



<div id="reportico_container">

<form class="swMenuForm" name="topmenu" method="POST" action="./reportico/./run.php">





<!-- BOOTSTRAP VERSION -->

    <div class="navbar navbar-default" role="navigation">

    <!--div class="navbar navbar-default navbar-static-top" role="navigation"-->

        <div class="container" style="width: 100%">

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#reportico-bootstrap-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="nav-collapse collapse in" id="reportico-bootstrap-collapse">

                <ul class="nav navbar-nav">
                    <li><a href="home.php">Go Back</a></li>
                </ul>

                <ul class="nav navbar-nav pull-right navbar-right">
                </ul>

            </div>



        </div>

    </div>



<!-- BOOTSTRAP VERSION -->

 

<h1 class="swTitle">CSO Reports</h1>


        <!-- <ul class="tabs" data-persist="true">
            <li><a >Basic</a></li>
            <li><a href="#2">Intermediate</a></li>
            <li><a href="#3">Advanced</a></li>
        </ul> -->

        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#1">Basic</a></li>
            <li><a data-toggle="tab" href="#2">Intermediate</a></li>
            <li><a data-toggle="tab" href="#3">Advanced</a></li>
        </ul>

    <div class="tab-content">
        <div id="1" class="tab-pane fade in active">
            <table class="swMenu">
                <thead>
                    <tr>
                        <th>Page 1</th>
                    </tr>
                </thead>
                <tbody>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Amount%20By%20Sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Total earned by each sector</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Top Earners Across All Sectors.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Top Earners Across All Sectors</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=SponsReps vs Companies per sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">SponsReps vs Companies per sector</a>
                        </td>
                    </tr>           
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="2" class="tab-pane fade">
        <table class="swMenu">
                <thead>
                    <tr>
                        <th>Page 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Amount%20By%20Sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Total earned by each sector</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Top Earners Across All Sectors.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Top Earners Across All Sectors</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=SponsReps vs Companies per sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">SponsReps vs Companies per sector</a>
                        </td>
                    </tr>           
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="3" class="tab-pane fade">
        <table class="swMenu">
                <thead>
                    <tr>
                        <th>Page 3</th>
                    </tr>
                </thead>
                <tbody>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Amount%20By%20Sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Total earned by each sector</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Top Earners Across All Sectors.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Top Earners Across All Sectors</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=SponsReps vs Companies per sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">SponsReps vs Companies per sector</a>
                        </td>
                    </tr>           
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                </tbody>
            </table>
        </div>

       <!--  <div id="4">
            <table class="swMenu">
                <thead>
                    <tr>
                        <th>Page 4</th>
                </thead>
                <tbody>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Amount%20By%20Sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Total earned by each sector</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Top Earners Across All Sectors.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Top Earners Across All Sectors</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=SponsReps vs Companies per sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">SponsReps vs Companies per sector</a>
                        </td>
                    </tr>           
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="5">
            <table class="swMenu">
                <thead>
                    <tr>
                        <th>Page 5</th>
                    </tr>
                </thead>
                <tbody>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Amount%20By%20Sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Total earned by each sector</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=Top Earners Across All Sectors.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">Top Earners Across All Sectors</a>
                        </td>
                    </tr>
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                    <tr>
                        <td class="swMenuItem">
                            <a class="swMenuItemLink" href="./reportico/run.php?project=CSO%20Reports&xmlin=SponsReps vs Companies per sector.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1">SponsReps vs Companies per sector</a>
                        </td>
                    </tr>           
                    <tr> 
                        <td>&nbsp;</td> 
                    </tr>
                </tbody>
            </table>
        </div> -->
    </div>



</form>



<div class="smallbanner">Sponsorship Management System<a href="#" target="_blank"></a></div>


</div>



 





</body></html>  