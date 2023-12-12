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
    <form method="POST" name="bevestig" class="loginBox" action="createAccount.php">
        <h2>Inloggen</h2>
        <div class="login-input">
            <label for="email">email</label>
            <input type="text" class="loginEmail" name="email" required>
        </div>
        <div class="login-input">
            <label for="password">wachtwoord</label>
            <input type="password" class="loginPassword" name="password" required>
        </div>
            <div class="login-input">
                <label for="name">
                    Naam <span class="required"></span>
                </label>
                <input type="text" name="naam" id="naam" required>
            </div>
                <div class="login-input">
                    <label for="name" class="required">
                        Telefoonnummer
                    </label>
                    <input type="text" name="telefoonnummer" id="telefoonnummer" required>
                </div>
        <div class="login-input">
            <input type="submit" class="loginSubmit" name="inloggen" value="Maak account aan.">
        </div>
        <?php
if (isset($_POST["password"])) {
    $hashedPassword = hashPassword($_POST["password"]);
}
        if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["naam"]) && isset($_POST["telefoonnummer"])) {
            $succes = createAccount($databaseConnection, $_POST["naam"], $hashedPassword, $_POST["telefoonnummer"], $_POST["email"]);

            definiteAddCustomer();
            if ($succes) {
                print("Account aangemaakt.");
            } else {
                print("Account aanmaken mislukt.");
            }
        }
?>
<?php
include __DIR__ . "/components/footer.php"
?>