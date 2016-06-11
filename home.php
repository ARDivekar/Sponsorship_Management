<?php
    /*Resume old session:*/
    include_once('library_functions.php');
	include_once "SponsEnums.php";
    session_start();

//    if (empty($_SESSION[SessionEnums::UserLoginID]))
//        header("Location: login.php");

	echo "lol";
    $SponsAccessLevel = $_SESSION[SessionEnums::UserAccessLevel];

    if ($SponsAccessLevel == UserTypes::CSO)
        header('Location: CSO.php');

    else if ($SponsAccessLevel == UserTypes::SectorHead)
        header('Location: Sector Head.php');

    else if ($SponsAccessLevel == UserTypes::SponsRep)
        header('Location: Sponsorship Representative.php');

    else {
        echo "SponsAccessLevel:".$SponsAccessLevel;
//        header('Location: logout.php');
    }
?>