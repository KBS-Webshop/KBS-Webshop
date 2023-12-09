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
        display: flex;
        width: 25%;
        justify-content: center;
    }
    .informationBox{
        display: flex;
        justify-content: center;
        flex-direction: row;
        width: 40%;
    }
</style>
<form method="POST" name="bevestig" class="loginBox" action="userInfoAanpassen.php">
    <div class="informationBox">
        <h2>Uw gegevens Aanpassen</h2>
    </div>
    <div class="informationBox">
        <label>Naam: </label>
        <input type="text" class="FullName" name="FullName" value="<?php if (isset($_POST["FullName"])){ print($_POST["FullName"]);} else { print($_SESSION["user"]["FullName"]);} ?>">
    </div>
    <div class="informationBox">
        <label>email: </label>
        <input type="text" class="FullName" name="EmailAddress" value="<?php if (isset($_POST["EmailAddress"])){ print($_POST["EmailAddress"]);} else { print($_SESSION["user"]["EmailAddress"]);} ?>">
    </div>
    <div class="informationBox">
        <label>Telefoonnummer: </label>
        <input type="text" class="FullName" name="PhoneNumber" value="<?php if (isset($_POST["PhoneNumber"])){ print($_POST["PhoneNumber"]);} else { print($_SESSION["user"]["PhoneNumber"]);} ?>">
    </div>
        <div class="login-input">
            <input type="submit" class="informationBox" name="aanpassingenOpslaan" value="Opslaan">
        </div>
    <?php
if (isset($_POST["aanpassingenOpslaan"])) {
    getCurrentUserID($databaseConnection, $_SESSION["userEmail"], $_SESSION["password"]);
    $updated = updateAccount($databaseConnection, $_POST["FullName"], $_POST["PhoneNumber"], $_POST["EmailAddress"], $_SESSION["user"]["PersonID"]);
if ($updated) {
        print("Account aangepast.");
        getCurrentUser($databaseConnection, $_SESSION["userEmail"], $_SESSION["password"]);
    } else {
        print("Account aanpassen mislukt.");
    }
}

include __DIR__ . "/components/footer.php"
?>