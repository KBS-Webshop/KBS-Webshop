<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
<div class="informationBox">
    <?php
    if (!isset($_POST["inloggen"])) {
        $_POST["inloggen"] = "";
    }
    if (!isset($_SESSION["user"]["isLoggedIn"])) {
        $_SESSION["user"]["isLoggedIn"] = 0;
    }
    if (isset($_POST["uitloggen"])) {
        logoutUser();
    }
    if (isset($_POST["userEmail"])) {
        $_SESSION["userEmail"] = $_POST["userEmail"];
    }
    if (isset($_POST["password"])) {
        $_SESSION["password"] = $_POST["password"];
        $hashedPassword = hashPassword($_POST["password"]);
        $_SESSION["hashedPassword"] = $hashedPassword;
        $_SESSION["password"] = "";
        $_POST["password"] = "";
    }
    if (isset($_POST["userEmail"]) && isset($_POST["password"]) && $_SESSION["user"]["isLoggedIn"] == 0) {
        getCurrentUser($databaseConnection, $_POST["userEmail"], $_SESSION["hashedPassword"]); #$_POST["password"] moet nog veranderd worden naar werkende $hashedPassword
        getUserCustomerInfo($databaseConnection, $_POST["userEmail"], $_SESSION["hashedPassword"]);
    }
    ?>
</div>

<form method="POST" class="loginBox">
    <?php if ($_SESSION["user"]["isLoggedIn"] == 0) { ?>
    <h2>Inloggen</h2>
    <div class="login-input">
        <label for="email">E-mail</label>
        <input type="email" class="loginEmail" name="userEmail" id="email" required>
    </div>
    <div class="login-input">
        <label for="password">Wachtwoord</label>
        <input type="password" class="loginPassword" name="password" id="password" required>
    </div>
    <div class="login-input">
        <input type="submit" class="loginSubmit" name="inloggen" value="inloggen">
    </div>
        <div class="informationBox">
            <a>Nog geen account? Maak </a><a href="createAccount.php">&nbsp;hier&nbsp;</a><a> een account aan</a>
        </div>
    <?php } elseif ($_SESSION["user"]["isLoggedIn"] == 1) {
        getUserCustomerInfo($databaseConnection, $_SESSION["userEmail"], $_SESSION["hashedPassword"]);
        print("U bent ingelogd.");
        ?>
    <div class="login-input">
        <input type="submit" class="loginSubmit" name="uitloggen" value="uitloggen">
    </div>
        <div>
    <?php
    print "Welkom " . $_SESSION["user"]["FullName"] . "!";
    ?>
        </div>
    <div class="informationBox">
        <h2>Uw informatie</h2>
    </div>
    <div class="informationBox">
        <label>Naam:&nbsp;</label>
        <?php
        if (isset($_SESSION["user"]["FullName"])) {
            print($_SESSION["user"]["FullName"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>E-mail:&nbsp;</label>
        <?php
        if (isset($_SESSION["user"]["EmailAddress"])) {
            print($_SESSION["user"]["EmailAddress"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>Telefoonnummer:&nbsp;</label>
        <?php
        if (isset($_SESSION["user"]["PhoneNumber"])) {
            print($_SESSION["user"]["PhoneNumber"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>Loyaliteitspunten:&nbsp;</label>
        <?php
        if (isset($_SESSION["user"]["loyalty_points"])) {
            print($_SESSION["user"]["loyalty_points"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>Adres:&nbsp;</label>
        <?php
        if (isset($_SESSION["user"]["customer"]["DeliveryAddressLine1"])) {
            print($_SESSION["user"]["customer"]["DeliveryAddressLine1"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>Postcode:&nbsp;</label>
        <?php
        if (isset($_SESSION["user"]["customer"]["DeliveryPostalCode"])) {
            print($_SESSION["user"]["customer"]["DeliveryPostalCode"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>Stad:&nbsp;</label>
        <?php
        if (isset($_SESSION["user"]["customer"]["cityName"])) {
            print($_SESSION["user"]["customer"]["cityName"]);
        }
        ?>
    </div>

        <a class="button primary userButton" href="userInfoAanpassen.php">
            <h2>Aanpassen</h2>
            </a>
        <a class="button primary userButton" href="previouslyOrdered.php">
            <h2>Eerder gekochte producten</h2>
        </a>

    <?php
        if ($_SESSION["user"]["IsSalesPerson"] == 1) {
            ?>
            <a class="button primary userButton" href="../beheer">
                <h2>Beheerpagina</h2>
            </a>
            <?php
        }
    }
    if ($_POST["inloggen"] == "inloggen" && $_SESSION["user"]["isLoggedIn"] == 0) {
        print("Inloggen mislukt, email of wachtwoord is onjuist.");
    }
include __DIR__ . "/components/footer.php"
?>
