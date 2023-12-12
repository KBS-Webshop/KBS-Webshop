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
            <label for="adress" class="inline-label">
                adress <span class="required"></span>
            </label>
            <input type="text" name="adress" id="adress" required>
        </div>
    <div class="login-input">
        <label for="name" class="inline-label">
            Postcode <span class="required"></span>
        </label>
        <input type="text" name="postcode" id="postcode" required>
    </div>
    <div class="login-input">
        <label for="name" class ="inline-label">
            Stad <span class="required"></span>
        </label>
        <input type="text" name="stad" id="stad" required>
    </div>
    <div class="login-input">
        <label for="name" class ="inline-label">
            Provincie <span class="required"></span>
        </label>
        <select name="provincie" id="provincie" required>
            <option value="Overijssel">Overijssel</option>
            <option value="Groningen">Groningen</option>
            <option value="Noord-Holland">Noord-Holland</option>
            <option value="Zuid-Holland">Zuid-Holland</option>
            <option value="Drenthe">Drenthe</option>
            <option value="Gelderland">Gelderland</option>
            <option value="Zeeland">Zeeland</option>
            <option value="Utrecht">Utrecht</option>
            <option value="Friesland">Friesland</option>
            <option value="Limburg">Limburg</option>
            <option value="Limburg">Brabant</option>
            <option value="Flevoland">Flevoland</option>
        </select>
        <!-- <input type="text" name="provincie" id="provincie" required> -->
    </div>
        <div class="login-input">
            <input type="submit" class="loginSubmit" name="inloggen" value="Maak account aan.">
        </div>
        <?php
if (isset($_POST["password"])) {
    $hashedPassword = hashPassword($_POST["password"]);
}
        if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["naam"]) && isset($_POST["telefoonnummer"])) {
            $PersonID = getNewAccountID($databaseConnection);
            $succes = createAccount($databaseConnection, $_POST["naam"], $hashedPassword, $_POST["telefoonnummer"], $_POST["email"]);
            definiteAddCustomer($databaseConnection, $_POST["naam"], $_POST["telefoonnummer"], $_POST["adress"], $_POST["postcode"], $_POST["stad"], $_POST["provincie"], $PersonID);
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