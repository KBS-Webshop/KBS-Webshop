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
        <label for="email">email</label>
        <input type="text" class="loginEmail" name="userEmail" required>
    </div>
    <div class="login-input">
        <label for="password">wachtwoord</label>
        <input type="password" class="loginPassword" name="password" required>
    </div>
    <div class="login-input">
        <input type="submit" class="loginSubmit" name="inloggen" value="inloggen">
    </div>
        <div class="informationBox">
            <a>Nog geen account? Maak </a><a href="createAccount.php">&nbsp;hier&nbsp;</a><a> een account aan</a>
        </div>
    <?php } elseif ($_SESSION["user"]["isLoggedIn"] == 1) {
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
        <label>Naam: </label>
        <?php
        if (isset($_SESSION["user"]["FullName"])) {
            print($_SESSION["user"]["FullName"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>email: </label>
        <?php
        if (isset($_SESSION["user"]["EmailAddress"])) {
            print($_SESSION["user"]["EmailAddress"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>Telefoonnummer: </label>
        <?php
        if (isset($_SESSION["user"]["PhoneNumber"])) {
            print($_SESSION["user"]["PhoneNumber"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>Loyaliteitspunten: </label>
        <?php
        if (isset($_SESSION["user"]["loyalty_points"])) {
            print($_SESSION["user"]["loyalty_points"]);
        }
        ?>
    </div>

        <a href="userInfoAanpassen.php">
            <h2>aanpassen</h2>
            </a>
        <a href="previouslyOrdered.php">
            <h2>eerder gekochte producten</h2>
        </a>

    <?php
        if ($_SESSION["user"]["IsSalesPerson"] == 1) {
            ?>
            <div class="informationBox">
                <a href="beheer/beheer.php">
                    <h2>beheerpagina</h2>
                </a>
            </div>
            <?php
        }
    }
    if ($_POST["inloggen"] == "inloggen" && $_SESSION["user"]["isLoggedIn"] == 0) {
        print("Inloggen mislukt, email en wachtwoord komen niet overeen.");
    }
include __DIR__ . "/components/footer.php"
?>
