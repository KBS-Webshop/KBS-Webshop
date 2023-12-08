<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
<style>
    .loginBox {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .loginBox .login-input {
        width: 25%;
    }
    .informationBox{
        display: flex;
        justify-content: center;
        flex-direction: row;
    }
</style>

<div class="informationBox">
    <?php
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
    }
    if (isset($_POST["userEmail"]) && isset($_POST["password"]) && $_SESSION["user"]["isLoggedIn"] == 0) {
        getCurrentUser($databaseConnection, $_POST["userEmail"], $_POST["password"]);
    }
    if ($_SESSION["user"]["isLoggedIn"] == 1) {
        print("U bent ingelogd.");
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
            <a>Nog geen account? Maak </a><a href="createAccount.php">hier</a><a> een account aan</a>
        </div>
    <?php } elseif ($_SESSION["user"]["isLoggedIn"] == 1) { ?>
    <div class="login-input">
        <input type="submit" class="loginSubmit" name="uitloggen" value="uitloggen">
    <?php
    }
    ?>
</form>
<!--<div class="informationBox">-->
<!--    <h2>Uw informatie</h2>-->
<!--</div>-->
<!--    <div class="informationBox">-->
<!--        <label>Naam: </label>-->
<!--    --><?php
//if (isset($_SESSION["user"]["FullName"])) {
//    print($_SESSION["user"]["FullName"]);
//}
//?>
<!--    </div>-->
<!--    <div class="informationBox">-->
<!--        <label>email: </label>-->
<!--        --><?php
//        if (isset($_SESSION["user"]["EmailAddress"])) {
//            print($_SESSION["user"]["EmailAddress"]);
//        }
//        ?>
<!--    </div>-->
<!--    <div class="informationBox">-->
<!--        <label>Telefoonnummer: </label>-->
<!--    --><?php
print_r($_SESSION["user"]);
//if (isset($_SESSION["user"]["PhoneNumber"])) {
//    print($_SESSION["user"]["PhoneNumber"]);
//}
//?>
<?php
include __DIR__ . "/components/footer.php"
?>
