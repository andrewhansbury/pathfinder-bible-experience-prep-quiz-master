<?php
    require_once(dirname(__FILE__)."/../init.php");

    $canViewAdminPanel = isset($_SESSION["UserType"]) && $_SESSION["UserType"] !== "Pathfinder";
    if (!$canViewAdminPanel) {
        header("Location: $basePath/index.php");
    }

    $isClubAdmin = $_SESSION["UserType"] === "ClubAdmin";
    $isWebAdmin = $_SESSION["UserType"] === "WebAdmin";
?>