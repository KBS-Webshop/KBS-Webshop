<?php
session_start();

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

include "../helpers/cookie.php";
include "../helpers/database/database.php";
include "../helpers/database/loyalty.php";
include "../helpers/database/order.php";
include "../helpers/database/stock.php";

$databaseConnection = connectToDatabase();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="../Public/JS/fontawesome.js"></script>
    <script src="../Public/JS/jquery.min.js"></script>
    <script src="../Public/JS/bootstrap.min.js"></script>
    <script src="../Public/JS/popper.min.js"></script>
    <script src="../Public/JS/resizer.js"></script>
    <script src="../Public/JS/validate_input.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="../Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/header.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/orderbevestiging.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/winkelmand.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/naw.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/view.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/loyalty.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/typekit.css">
</head>

<body>
    <div class="Background">
        <div class="row" id="Header">
            <div class="col-2"><a href="./" id="LogoA">
                    <div id="LogoImage"></div>
                </a></div>
            <div class="col-8" id="CategoriesBar">
                <ul id="ul-class">
                    <li>
                        <a href="/" class="HrefDecoration">Webshop</a>
                    </li>
                    <li>
                        <a href="/beheer/loyalty.php" class="HrefDecoration">Loyalty</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">