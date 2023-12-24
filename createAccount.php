<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>
<!--<script>-->
<!--    function checkCreateInput() {-->
<!--        const email = document.getElementById("email").value;-->
<!--        const telefoonnummer = document.getElementById("telefoonnummer").value;-->
<!--        const postcode = document.getElementById("postcode").value;-->
<!---->
<!--        const emailRegex = /^[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]+@[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]+\.[a-zA-Z0-9!#$%&'*+-/=?^_`{|}~.]+$/;-->
<!--        const postcodeRegex = /^[0-9]{4}\s*[a-zA-Z]{2}$/;-->
<!--        const telefoonnummerRegex = /^(?:(?:\+|00(\s|\s?\-\s?)?)31(?:\s|\s?\-\s?)?(?:\(0\)[\-\s]?)?|0)[1-9](?:(?:\s|\s?\-\s?)?[0-9])(?:(?:\s|\s?-\s?)?[0-9])(?:(?:\s|\s?-\s?)?[0-9])\s?[0-9]\s?[0-9]\s?[0-9]\s?[0-9]\s?[0-9]$/;-->
<!--        if (!emailRegex.test(email)) {-->
<!--            alert("Email is niet geldig.") -->
<!--            return false;-->
<!--        }-->
<!--        if (!telefoonnummerRegex.test(telefoonnummer)) {-->
<!--            alert("Telefoonnummer is niet geldig.")-->
<!--            return false;-->
<!--        }-->
<!--        if (!postcodeRegex.test(postcode)) {-->
<!--            alert("Postcode is niet geldig.")-->
<!--            return false;-->
<!--        }-->
<!--        return true;-->
<!--    }-->
<!---->
<!--    if (location.pathname.includes('/createAccount.php')) {-->
<!--        document.addEventListener('submit', (e) => {-->
<!--            if (!checkCreateInput()) {-->
<!--                e.preventDefault();-->
<!--            }-->
<!--        });-->
<!--    }-->
<!--</script>-->
    <form method="POST" name="bevestig" class="loginBox" action="createAccount.php">
        <h2>Account aanmaken</h2>
        <div class="login-input-create">
            <label for="email">
                email <span class="required"></span>
            </label>
            <input type="text" class="loginEmail" name="email" id="email" required>
        </div>
        <div class="login-input-create">
            <label for="password">
                wachtwoord <span class="required"></span>
            </label>
            <input type="password" class="loginPassword" name="password" required>
        </div>
        <div class="login-input-create">
            <label for="password">
                wachtwoord herhalen <span class="required"></span>
            </label>
            <input type="password" class="loginPasswordConfirm" name="passwordConfirm" required>
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
        <label for="name">
            akkoord met de terms of service <span class="required"></span>
            <input type="radio" name="options" value="option1" required>
        </label>
    </div>
        <div class="login-input-create">
            <input type="submit" class="loginSubmit" name="inloggen" value="Maak account aan." id="submit">
        </div>
        <?php
//        if (isset($_POST["inloggen"])) {
//            ?><!--<script>-->
<!--            checkCreateInput();-->
<!--            </script>--><?php
//        }
if (isset($_POST["straatnaam"]) && isset($_POST["huisnummer"])) {
    $_POST["address"] = $_POST["straatnaam"] . " " . $_POST["huisnummer"];
}
if (isset($_POST["password"]) && isset($_POST["passwordConfirm"])) {
    if ($_POST["password"] != $_POST["passwordConfirm"]) {
        print("Wachtwoorden komen niet overeen.");
    } elseif (isset($_POST["password"])) {
    $hashedPassword = hashPassword($_POST["password"]);
    $_SESSION["hashedPassword"] = $hashedPassword;
}
}
    if (isset($_POST["email"]) && isset($_SESSION["hashedPassword"]) && isset($_POST["naam"]) && isset($_POST["telefoonnummer"]) && isset($_POST["address"]) && isset($_POST["postcode"]) && isset($_POST["stad"])) {
        if (checkIfAccountExists($databaseConnection, $_POST["email"])) {
            print("Account met deze email bestaat al.");
        } else if (!cityExists($_POST["stad"], $databaseConnection)) {
            print("Vul een bstaande stad in");
        } else {
            $PersonID = getNewAccountID($databaseConnection);
            $accountCreated = createAccount($databaseConnection, $_POST["naam"], $_SESSION["hashedPassword"], $_POST["telefoonnummer"], $_POST["email"]);
            $customerAdded = definiteAddCustomer($databaseConnection, $_POST["naam"], $_POST["telefoonnummer"], $_POST["address"], $_POST["postcode"], $_POST["stad"], $PersonID);
            if ($accountCreated && $customerAdded) {
                print ("Account aangemaakt.");

            } else {
                print("Account aanmaken mislukt.");
            }
        }
}
?>
<!--        <script>-->
<!--                document.addEventListener('submit', (e) => {-->
<!--                if (!checkPasswordStrength(--><?php //print ($_POST["password"]); ?><!--)) e.preventDefault();-->
<!--                });
//        </script>
    </form>
<?php
include __DIR__ . "/components/footer.php"
?>
