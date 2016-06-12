<?php 
    /*Resume old session:*/
    	header("Location: homepage.php");
        session_start();

        require('DBconnect.php');
        require('library_functions.php');
        $SponsID=$_SESSION['loginID']; //get SponsID from previos session
        $SponsAccessLevel = get_access_level($SponsID);
        if($SponsAccessLevel=="CSO")    
            header('Location: CSOreports.php');

        if($SponsAccessLevel=="SectorHead")
            header('Location: SectorHead_reports.php');

        if($SponsAccessLevel=="SponsRep")
            header('Location: SponsRep_reports.php');
?>