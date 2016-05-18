<?php
    /*Resume old session:*/
    session_start();
    if (empty($_SESSION['loginID']))
        header("Location: login.php");

    require('DBconnect.php');
    require('library_functions.php');
    $SponsID = $_SESSION['loginID']; //get SponsID from previos session
    $SponsAccessLevel = get_access_level($SponsID);
    if ($SponsAccessLevel == "CSO")
        header('Location: CSO.php');

    else if ($SponsAccessLevel == "SectorHead")
        header('Location: Sector Head.php');

    else if ($SponsAccessLevel == "SponsRep")
        header('Location: Sponsorship Representative.php');

    else header('Location: logout.php');
?>