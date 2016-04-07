<?php 
    /*Resume old session:*/
        session_start();

        require('DBconnect.php');
        require('library_functions.php');
        $SponsID=$_SESSION['loginID']; //get SponsID from previos session
        $SponsAccessLevel = get_access_level($SponsID);
        if($SponsAccessLevel=="CSO")    
            header('Location: CSO.php');

        else if($SponsAccessLevel=="SectorHead")
            header('Location: sectorhead.php');

        else if($SponsAccessLevel=="SponsRep")
            header('Location: sponsrep.php');

        else header('Location: logout.php');
?>