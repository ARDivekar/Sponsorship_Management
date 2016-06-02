<?php 



    session_start();



    require('./DBconnect.php');



    require('./library_functions.php');



    $SponsID=$_SESSION['loginID']; //get SponsID from previos session



    $SponsAccessLevel = get_access_level($SponsID);



    if($SponsAccessLevel!="CSO")    



    header('Location: ./home.php');





    // //Amount Earned By Each Sector Pie Chart

    // $Amount_Earned_by_IT_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"IT\" GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;");

    // $Amount_Earned_by_IT_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_IT_Sector);

    // $IT = (int)$Amount_Earned_by_IT_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_Builders_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                           (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                             UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                             WHERE Sector=\"Builders\"

    //                           GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;");

    // $Amount_Earned_by_Builders_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Builders_Sector);

    // $Builders = (int)$Amount_Earned_by_Builders_Sector_Result["Amount_Earned_by_Sector_in_Rupees"]; 



    // $Amount_Earned_by_Clothes_Retail_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Clothes Retail\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Clothes_Retail_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Clothes_Retail_Sector);

    // $Clothes_Retail = (int)$Amount_Earned_by_Clothes_Retail_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];





    // $Amount_Earned_by_Ecommerce_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Ecommerce\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Ecommerce_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Ecommerce_Sector);

    // $Ecommerce = (int)$Amount_Earned_by_Ecommerce_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_Electricals_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Electricals\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Electricals_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Electricals_Sector);

    // $Electricals = (int)$Amount_Earned_by_Electricals_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_FMCG_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"FMCG\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_FMCG_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_FMCG_Sector);

    // $FMCG = (int)$Amount_Earned_by_FMCG_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_Hotel_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Hotel\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Hotel_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Hotel_Sector);

    // $Hotel = (int)$Amount_Earned_by_Hotel_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_Music_Stores_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Music Stores\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Music_Stores_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Music_Stores_Sector);

    // $Music_Stores = (int)$Amount_Earned_by_Music_Stores_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_Radio_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Radio\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Radio_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Radio_Sector);

    // $Radio = (int)$Amount_Earned_by_Radio_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_Steel_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Steel\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Steel_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Steel_Sector);

    // $Steel = (int)$Amount_Earned_by_Steel_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_Travel_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Travel\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Travel_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Travel_Sector);

    // $Travel = (int)$Amount_Earned_by_Travel_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];



    // $Amount_Earned_by_Tyres_Sector =  mysql_query("SELECT sum(Amount) Amount_Earned_by_Sector_in_Rupees FROM 

    //                               (`AccountLog` inner join ((SELECT SponsID, Sector FROM `SectorHead`) 

    //                                 UNION (SELECT SponsID, Sector FROM SponsRep)) as `SponsOfficer` on SponsOfficer.SponsID = AccountLog.SponsID) 

    //                                 WHERE Sector=\"Tyres\"

    //                               GROUP BY `Sector` ORDER BY Amount_Earned_by_Sector_in_Rupees DESC;"); 

    // $Amount_Earned_by_Tyres_Sector_Result = mysql_fetch_assoc($Amount_Earned_by_Tyres_Sector);

    // $Tyres = (int)$Amount_Earned_by_Tyres_Sector_Result["Amount_Earned_by_Sector_in_Rupees"];







    // $rows = array(); 

    // $table = array(); 

    // $table['cols'] = [array( 

    //     // Labels for your chart, these represent the column titles 

    //     // Note that one column is in "string" format and another one is in 

    //     //"number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title 

    //     array('label' => 'Sector', 'type' => 'string'),

    //     array('label' => 'Amount', 'type' => 'number')]

 

    // );

 



    // while($result = mysql_fetch_assoc($Amount_Earned_by_Sector)) {

        

    //     $temp = array();



    //     $temp[] = array('v' => (string)$result['Sector']);

    //     $temp[] = array('v' => $result['Amount_Earned_by_Sector_in_Rupees']);

        

    //     $rows[] = array('c' => $temp);



    //     $Organization = $result['Organization'];

    //     $Event = $result['Event'];

    //     $Sector = (string)$result['Sector'];

    //     $Amount_Earned_by_Sector_in_Rupees = $result['Amount_Earned_by_Sector_in_Rupees'];



    // }

 

    // $table['rows'] = $rows;

    // $jsonTable = json_encode($table);





    //Committee Members by Each Sector

    // $Committe_Members_By_Each_Sector = mysql_query("SELECT SponsOfficer.Sector, count(SponsOfficer.SponsID) as Role_Count

    //             FROM

    //             (SELECT SO.SponsID, Name, Sector, Role, DateAssigned, Branch, Year, Organization, EventName, Mobile, Email 

    //                 FROM (SELECT SponsID, Sector, DateAssigned FROM `SectorHead` UNION SELECT SponsID, Sector, DateAssigned FROM `SponsRep`) as SO 

    //                 INNER JOIN CommitteeMember ON CommitteeMember.ID = SO.SponsID

    //             ) AS SponsOfficer WHERE SponsOfficer.Role=\"Sponsorship Representative\"

    //             GROUP BY SponsOfficer.Sector, SponsOfficer.Role

    //             ORDER BY SponsOfficer.Sector ASC, SponsOfficer.Role ASC"); 

    

    // $rows = array(); 

    // $table = array(); $table['cols'] = array( 

    // // Labels for your chart, these represent the column titles 

    // // Note that one column is in "string" format and another one is in 

    // //"number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title 

    // array('label' => 'Sector', 'type' => 'string'),

    // array('label' => 'SponsReps', 'type' => 'number')

 

    // );

 



    // while($result = mysql_fetch_assoc($Committe_Members_By_Each_Sector)) {

        

    //     $temp = array();

    //     $temp[] = array('v' => (string)$result['Sector']);



    //     // Values of each slice

    //     $temp[] = array('v' => (int)$result['Role_Count']);

    //     $rows[] = array('c' => $temp);

       

    // }

 

    // $table['rows'] = $rows;

    // $jsonTable = json_encode($table);



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

                    <li><a href="homepage.php">Go Back</a></li>

                </ul>

                <ul class="nav navbar-nav pull-right navbar-right">

                </ul>

            </div>



        </div>

    </div>

 



<h1 class="swTitle">Reports</h1>





        <ul class="nav nav-tabs">

            <li class="active"><a data-toggle="tab" href="#1">Display Tables</a></li>

            <li><a data-toggle="tab" href="#2">Time Dependent Reports</a></li>

            <li><a data-toggle="tab" href="#3">Missing Information</a></li>

            <li><a data-toggle="tab" href="#4">Ranking and Aggregation</a></li>

            <li><a data-toggle="tab" href="#5">Comparitive Analysis Reports</a></li>

            <li><a data-toggle="tab" href="#6">Pie Charts</a></li>

        </ul>



    <div class="tab-content">

        <div id="1" class="tab-pane fade in active">

            <table class="table table-striped table-hover">

                <tbody>

                    <tr>

                        <td>&nbsp;</td>

                    </tr>

                    <?php 

                        $Display_tables = array(

                                'CommitteeMembers By Organization And Event',

                                'CommitteeMembers By Role By Organization And Event',

                                'CommitteeMembers By Department By Organization And Event',

                                'CommitteeMembers By Branch By Organization And Event',

                                'CommitteeMembers By Member Year By Organization And Event',

                                'Display all the Sponsorship Representatives ordered by Sector',

                                'Display all the meetings ordered by the type of meeting',

                                'Displaying all the Company Executives ordered by their company',

                                'Display all the Sponsorship Representatives ordered by Date they have been assigned',

                                'Display all the meetings in an ordered time line',

                                'Display all the companies which are very interested in Sponsoring the event',

                                'Display all the meeting that require a Follow Up Meeting',

                                'Displaying all the events by start date',

                                'Display all the sector head ordered by their sectors',

                                'Order Account Log By Date',

                                'Order Account Log By Amount Deposited',

                                'Order Committee Member By Year of the CommitteeMember',

                                'Ordering And Displaying Companies by sector',

                                'Order Committee Member By Branch of the CommitteeMember',

                                'Show Companies that have not been called yet',

                                'Access Level Of Committee Members'

                            );

                        $i = 0; 

                        while ($i < count($Display_tables)){

                            echo '<tr>

                        <td class="swMenuItem">';

                            echo $Display_tables[$i];

                        echo '    

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary" 

                            onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Display_tables[$i].'.xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success"onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Display_tables[$i].'.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            Download</button>

                        </td>

                    </tr>';

                        $i++;



                        }



                    ?>

            <!-- 

                    <tr>

                        <td class="swMenuItem">

                            CommitteeMembers By Role By Organization And Event

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            CommitteeMembers By Organization And Event

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>

                    

                    <tr>

                        <td class="swMenuItem">

                            CommitteeMembers By Department By Organization And Event

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            CommitteeMembers By Member Year By Organization And Event

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            SponsReps and SectorHeads by Sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>   



                    <tr>

                        <td class="swMenuItem">

                            Dispaying all the Company Executives ordered by their company

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>   



                    <tr>

                        <td class="swMenuItem">

                           Displaying all the events by start date 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>   



                    <tr>

                        <td class="swMenuItem">

                            Display all the meetings in an ordered time line

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Display all the companies which are very interested in Sponsoring the event

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Display all the meetings ordered by the type of meeting

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Display all the sector head ordered by their sectors

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Dispaly all the committee members and their AccessLevel

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Display all the Sponsorship Representatives ordered by Date he/she has been assigned

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                           Display all the Sponsorship Representatives ordered by Sector he/she has been assigned

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                           Order Account Log By Date

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>    



                    <tr>

                        <td class="swMenuItem">

                           Order By amount deposited

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>    



                    <tr>

                        <td class="swMenuItem">

                           Order By Year of the CommitteeMember

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>    



                    <tr>

                        <td class="swMenuItem">

                          Order By Branch of the CommitteeMember

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                          Show Companies that have not been called yet

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                          Ordering And Displaying Companies by sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>    

            -->

                </tbody>

            </table>

        </div>



        <div id="2" class="tab-pane fade">

            <table class="table table-striped table-hover">

                    <tbody>

                    <tr>

                        <td>&nbsp;</td>

                    </tr>

                    <?php 

                        $Time_Dependent_tables = array(

                                'Frequency Of Meetings Per month',

                                'Total Amount By Organization and Event Over All Years',

                                'Total Amount By Organization and Event Over Per Year',

                                'Deposits vs Withdrawls',

                                'Number Of Sponsoring Companies This year vs Previous years',

                                'Total Sponsorship Amount Over Previous years',

                                'CompanySponsorship Details For Past Five Years By Sector',

                                'Expenditure Comparisons for Past 5 years',

                                'Budget Comparisons for Past 5 years'

                            );

                        $i = 0; 

                        while ($i < count($Time_Dependent_tables)){

                            echo '<tr>

                        <td class="swMenuItem">';

                            echo $Time_Dependent_tables[$i];

                        echo '    

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary" 

                            onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Time_Dependent_tables[$i].'.xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success"onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Time_Dependent_tables[$i].'.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            Download</button>

                        </td>

                    </tr>';

                        $i++;



                        }



                    ?>

                <!-- 

                    <tr>

                        <td class="swMenuItem">

                            Frequency Of Meetings Per month

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Total Amount By Organization and Event Over All Years

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>

                    

                    <tr>

                        <td class="swMenuItem">

                            Total Amount By Organization and Event Over Per Year

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Deposits vs Withdrawls

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Sponsorship amount per month

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>   



                    <tr>

                        <td class="swMenuItem">

                            Number Of Sponsoring Companies This year vs Previous years

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>   



                    <tr>

                        <td class="swMenuItem">

                           Total Sponsorship Amount Over Previous years 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>   



                    <tr>

                        <td class="swMenuItem">

                            CompanySponsorship Details For Past Five Years By Sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Expenditure Comparisons for Past 5 years 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Budget Comparisons for Past 5 years 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>    

                -->



                </tbody>

            </table>

        </div>



        <div id="3" class="tab-pane fade">

        <table class="table table-striped table-hover">

                    <tbody>

                    <tr>

                        <td>&nbsp;</td>

                    </tr>

                    <?php 

                        $Missing_Information_tables = array(

                                'Committee Members Without Contact Info',

                                'Company Executives Without Info',

                                'Companies Without Info',

                                'Meetings Without Info',

                                'List Of Unassigned Committee Members'

                            );

                        $i = 0; 

                        while ($i < count($Missing_Information_tables)){

                            echo '<tr>

                        <td class="swMenuItem">';

                            echo $Missing_Information_tables[$i];

                        echo '    

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary" 

                            onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Missing_Information_tables[$i].'.xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success"onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Missing_Information_tables[$i].'.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            Download</button>

                        </td>

                    </tr>';

                        $i++;



                        }



                    ?>



                <!--    

                    <tr>

                        <td class="swMenuItem">

                            Committee Members Without Contact Info

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Company Executives Without Info 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>

                    

                    <tr>

                        <td class="swMenuItem">

                            Companies Without Info

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Meetings Without Info

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            List Of Unassigned Committee Members

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>   

                -->



                </tbody>

            </table>

        </div>



        <div id="4" class="tab-pane fade">

            <table class="table table-striped table-hover">

                <tbody>

                    <tr>

                        <td>&nbsp;</td>

                    </tr>



                    <?php 

                        $Ranking_and_Aggregation = array(

                                'Number of Meetings Per Sector',

                                'Total earned by each sector',

                                'Top Earners Across All Sectors',

                                'SponsReps Ripe For Promotion To SectorHead',

                                'Easy Target Companies',

                                'Number Of Safe And Unsafe Passwords'

                            );

                        $i = 0; 

                        while ($i < count($Ranking_and_Aggregation)){

                            echo '<tr>

                        <td class="swMenuItem">';

                            echo $Ranking_and_Aggregation[$i];

                        echo '    

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary" 

                            onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Ranking_and_Aggregation[$i].'.xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success"onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Ranking_and_Aggregation[$i].'.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            Download</button>

                        </td>

                    </tr>';

                        $i++;



                        }



                    ?>



                <!--    

                    <tr>

                        <td class="swMenuItem">

                            Total earned by each sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Top Earners Across All Sectors 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>

                    

                    <tr>

                        <td class="swMenuItem">

                            Number of Junior Sponsreps per sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            SponsReps Ripe For Promotion To SectorHead

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Easy Target Companies

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Number Of Safe And Unsafe Passwords

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr> 



                    <tr>

                        <td class="swMenuItem">

                            Company Status 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>   

                --> 



                </tbody>

            </table>

        </div>



        <div id="5" class="tab-pane fade">

            <table class="table table-striped table-hover">

                <tbody>

                    <tr>

                        <td>&nbsp;</td>

                    </tr>

                    <?php 

                        $Comparitive_Analysis_Reports = array(

                                'SponsReps vs Companies per sector',

                                'Amount Earned vs Number of Meetings Per Sector',

                                'Average Sponsorship Amount Per Sector',

                                'Effectiveness Of Meeting Types',

                                'Effectiveness of SponsReps in sector',

                                'Management Overhead Per Sector',

                                'Management Ratio to Amount Earned Per Sector',

                                'List Of Companies Who Sponsored Last year But Not This year',

                                'List Of Companies Who Sponsored This year But Not Last year',

                                'List of Companies Who Have Sponsored Other Organizations',

                                'List of Companies Who Have Not Sponsored Other Organizations',

                                'Budget Per Day Of Events'

                            );

                        $i = 0; 

                        while ($i < count($Comparitive_Analysis_Reports)){

                            echo '<tr>

                        <td class="swMenuItem">';

                            echo $Comparitive_Analysis_Reports[$i];

                        echo '    

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary" 

                            onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Comparitive_Analysis_Reports[$i].'.xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success"onClick="window.location=\'./reportico/run.php?project=CSO Reports&xmlin='.$Comparitive_Analysis_Reports[$i].'.xml&target_format=PDF&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1\'">

                            Download</button>

                        </td>

                    </tr>';

                        $i++;



                        }



                    ?>

                <!--    

                    <tr>

                        <td class="swMenuItem">

                            SponsReps vs Companies per sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Amount Earned vs Number of Meetings Per Sector 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>

                    

                    <tr>

                        <td class="swMenuItem">

                            Average Sponsorship Amount Per Sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Effectiveness Of Meeting Types

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Effectiveness of SponsReps in sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Management Overhead Per Sector

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr> 

                    

                    <tr>

                        <td class="swMenuItem">

                            Management Ratio to Amount Earned Per Sector 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            List Of Companies Who Sponsored Last year But Not This year 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            List Of Companies Who Sponsored This year But Not Last year  

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                           Budget Per Day Of Events 

                        </td>

                        <td>

                            <button type="button" class="btn btn-primary">View</button>

                        </td>

                        <td>

                            <button type="button" class="btn btn-success">Download</button>

                        </td>

                    </tr>

                -->



                </tbody>

            </table>

        </div>



         <div id="6" class="tab-pane fade">

            <table class="table table-striped table-hover">

                <tbody>

                    <tr>

                        <td>&nbsp;</td>

                    </tr>

                    <tr>

                        <td class="swMenuItem">

                            Total Earned By Each Sector

                        </td>

                        <td>

                            <a href="CSO_amount_per_sector_piechart.php" class="btn btn-primary" role="button">View</a>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Sector Heads per Sector 

                        </td>

                        <td>

                            <a href="CSO_committee_member_piechart.php" class="btn btn-primary" role="button">View</a>

                        </td>

                    </tr>

                    

                    <tr>

                        <td class="swMenuItem">

                            Sponsorship Amount per Month

                        </td>

                        <td>

                            <a href="CSO_amount_per_month_piechart.php" class="btn btn-primary" role="button">View</a>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                             Sponsorship Representatives Per Sector

                        </td>

                        <td>

                            <a href="CSO_juniors_per_sector_piechart.php" class="btn btn-primary" role="button">View</a>

                        </td>

                    </tr>



                    <tr>

                        <td class="swMenuItem">

                            Company Statuses

                        </td>

                        <td>

                            <a href="CSO_company_status_piechart.php" class="btn btn-primary" role="button">View</a>

                        </td>

                    </tr> 

                    

                </tbody>

            </table>

        </div>

    </div>



   <!--  <div id="Amount_By_Each_Sector_Piechart" style="width: 900px; height: 500px;"></div> -->





</form>



<div class="smallbanner">Sponsorship Management System<a href="#" target="_blank"></a></div>



</div>







 











</body>



 <script>



//       google.charts.load('current', {'packages':['corechart']});

//       google.charts.setOnLoadCallback(drawChart);

//       function drawChart() {



//         var data = google.visualization.arrayToDataTable([

//             ['Sector','Amount'],

//             ['Builders', <?php echo $Builders?>],

//             ['Clothes Retail', <?php echo $Clothes_Retail;?>],

//             ['Ecommerce', <?php echo $Ecommerce;?>],

//             ['Electricals', <?php echo $Electricals;?>],

//             ['FMCG', <?php echo $FMCG;?>],

//             ['Hotel', <?php echo $Hotel;?>],

//             ['IT', <?php echo $IT;?>],

//             ['Music Stores', <?php echo $Music_Stores;?>],

//             ['Radio', <?php echo $Radio;?>],

//             ['Steel', <?php echo $Steel;?>],

//             ['Travel', <?php echo $Travel;?>],

//             ['Tyres', <?php echo $Tyres;?>]

//         ]);



//         var options = {

//           title: 'Amount_Earned_by_Sector'

//         };



//         var chart = new google.visualization.PieChart(document.getElementById('Amount_By_Each_Sector_Piechart'));







//         function selectHandler() {

//          var selectedItem = chart.getSelection()[0];



//           if (selectedItem) {

//             var sector = data.getValue(selectedItem.row, 0);

//             if(sector=="Clothes Retail")

//                 sector = "Clothes_Retail";

//             if(sector=="Music Stores")

//                 sector="Music_Stores";

//             window.open("./reportico/run.php?project=CSO%20Reports&xmlin=Amount_Earned_by_"+sector+"_Sector.xml&target_style=TABLE&user_criteria_entered=1&target_show_group_headers=1&target_show_detail=1&target_show_group_trailers=1&target_show_column_headers=1&target_show_graph=1&execute_mode=EXECUTE&submitPrepare=1")

//           }

//         }



//         // Add our selection handler.

//         google.visualization.events.addListener(chart, 'select', selectHandler);



//         chart.draw(data, options);



//       }







// </script>





</html> 