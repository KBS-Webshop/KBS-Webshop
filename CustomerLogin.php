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
<form method="post" class="loginBox" action="CustomerLogin.php">
    <h2>Inloggen</h2>
    <div class="login-input">
        <label for="email">email</label>
        <input type="text" class="loginEmail" name="email" required>
    </div>
    <div class="login-input">
        <label for="password">wachtwoord</label>
        <input type="password" class="loginPassword" name="password" required>
    </div>
    <div>
    <a>Nog geen account? Maak </a><a href="createAccount.php">hier</a><a> een account aan</a>
    </div>
    <h2>Uw informatie</h2>
    <div class="informationBox">
        <label>Naam: </label>
    <?php
if (isset($_SESSION["naam"])) {
    print($_SESSION["naam"]);
}
?>
    </div>
    <div class="informationBox">
        <label>email: </label>
        <?php
        if (isset($_SESSION["email"])) {
            print($_SESSION["email"]);
        }
        ?>
    </div>
    <div class="informationBox">
        <label>Telefoonnummer: </label>
    <?php
if (isset($_SESSION["telefoonnummer"])) {
    print($_SESSION["telefoonnummer"]);
}
?>
    </div>
    <div class="informationBox">
        <label>Adres: </label>
    <?php
if (isset($_SESSION["adress"])) {
    print($_SESSION["adress"]);
}
?>
    </div>
    <div class="informationBox">
        <label>Postcode: </label>
    <?php
if (isset($_SESSION["postcode"])) {
    print($_SESSION["postcode"]);
}
?>
    </div>
    <div class="informationBox">
        <label>Stad: </label>
    <?php
if (isset($_SESSION["stad"])) {
    print($_SESSION["stad"]);
}

    ?>
</form>





<?php
include __DIR__ . "/components/footer.php"
?>
