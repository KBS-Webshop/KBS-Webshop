<?php
session_start();

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

include "helpers/cookie.php";
include "helpers/database/database.php";
include "helpers/database/loyalty.php";
include "helpers/database/order.php";
include "helpers/database/stock.php";
include "helpers/database/customer.php";


$databaseConnection = connectToDatabase();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="Public/JS/fontawesome.js"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/resizer.js"></script>
    <script src="Public/JS/validate_input.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/header.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/orderbevestiging.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/winkelmand.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/naw.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/view.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/loyalty.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/typekit.css">
    <link rel="stylesheet" href="Public/CSS/userlogin.css" type="text/css">
</head>

<body>
    <div class="Background">
        <div class="row" id="Header">
            <div class="col-2"><a href="./" id="LogoA">
                    <div id="LogoImage"></div>
                </a></div>
            <div class="col-8" id="CategoriesBar">
                <ul id="ul-class">
                    <?php
                    $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                    foreach ($HeaderStockGroups as $HeaderStockGroup) {
                        ?>
                        <li>
                            <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                                class="HrefDecoration">
                                <?php print $HeaderStockGroup['StockGroupName']; ?>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <li>
                        <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                    </li>
                </ul>
            </div>
            <!-- code voor US3: zoeken -->

            <ul id="ul-class-navigation">
                <li>
                    <a href="CustomerLogin.php" class="fa fa-user">Account</a>
                </li>
                <li>
                    <a href="browse.php" class="HrefDecoration"><i class="fas fa-search search"></i> Zoeken</a>
                </li>
                <li>&nbsp;&nbsp;</li>
                <li>
                    <a href="winkelmand.php" class="HrefDecoration"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Winkelwagen</a>
                </li>
            </ul>

            <!-- einde code voor US3 zoeken -->

        </div>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">
<!--                --><?php
//if (isset($_SESSION["user"]["hashedPassword"]) && isset($_SESSION["user"]["EmailAddress"])) {
//    getUserCustomerInfo($databaseConnection, $_SESSION["user"]["EmailAddress"], $_SESSION["user"]["hashedPassword"]);
//}
//?>