<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
    <form method="POST" name="bevestig" class="loginBox" action="createAccount.php">
        <h2>Account aanmaken</h2>
        <div class="login-input-create">
            <label for="email">email</label>
            <input type="text" class="loginEmail" name="email" required>
        </div>
        <div class="login-input-create">
            <label for="password">wachtwoord</label>
            <input type="password" class="loginPassword" name="password" required>
        </div>
            <div class="login-input-create">
                <label for="name">
                    Naam <span class="required"></span>
                </label>
                <input type="text" name="naam" id="naam" required>
            </div>
                <div class="login-input-create">
                    <label for="name" class="required">
                        Telefoonnummer
                    </label>
                    <input type="text" name="telefoonnummer" id="telefoonnummer" required>
                </div>
        <div class="login-input-create">
            <label for="adress" class="inline-label">
                straatnaam <span class="required"></span>
            </label>
            <input type="text" name="straatnaam" id="straatnaam" required>
        </div>
    <div class="login-input-create">
        <label for="adress" class="inline-label">
            huisnummer <span class="required"></span>
        </label>
        <input type="text" name="huisnummer" id="huisnummer" required>
    </div>

    <div class="login-input-create">
        <label for="name" class="inline-label">
            Postcode <span class="required"></span>
        </label>
        <input type="text" name="postcode" id="postcode" required>
    </div>
    <div class="login-input-create">
        <label for="name" class ="inline-label">
            Stad <span class="required"></span>
        </label>
        <input type="text" name="stad" id="stad" required>
    </div>
    <div class="login-input-create">
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
    </div>
    <div class="login-input-create">
        <label for="name">
            akkoord met de terms of service <span class="required"></span>
            <input type="radio" name="options" value="option1" required>
        </label>
    </div>
        <div class="login-input-create">
            <input type="submit" class="loginSubmit" name="inloggen" value="Maak account aan.">
        </div>
        <?php
if (isset($_POST["password"])) {
        $hashedPassword = hashPassword($_POST["password"]);
        $_SESSION["hashedPassword"] = $hashedPassword;
}
if (isset($_POST["straatnaam"]) && isset($_POST["huisnummer"])) {
    $_POST["address"] = $_POST["straatnaam"] . " " . $_POST["huisnummer"];
}
        if (isset($_POST["email"]) && isset($_SESSION["hashedPassword"]) && isset($_POST["naam"]) && isset($_POST["telefoonnummer"]) && isset($_POST["address"]) && isset($_POST["postcode"]) && isset($_POST["stad"]) && isset($_POST["provincie"])) {
            if (checkIfAccountExists($databaseConnection, $_POST["email"])) {
                print("Account met deze email bestaat al.");
            } else {
                $PersonID = getNewAccountID($databaseConnection);
                $succes = createAccount($databaseConnection, $_POST["naam"], $_SESSION["hashedPassword"], $_POST["telefoonnummer"], $_POST["email"]);
                definiteAddCustomer($databaseConnection, $_POST["naam"], $_POST["telefoonnummer"], $_POST["address"], $_POST["postcode"], $_POST["stad"], $_POST["provincie"], $PersonID);
                if ($succes) {
                    print("Account aangemaakt.");
                } else {
                    print("Account aanmaken mislukt.");
                }
            }
        }
?>
        <script>
                document.addEventListener('submit', (e) => {
                if (!checkPasswordStrength(<?php print ($_POST["password"]); ?>)) e.preventDefault();
                });
        </script>
<?php
include __DIR__ . "/components/footer.php"
?>
