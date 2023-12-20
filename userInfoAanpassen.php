<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
$_SESSION["user"]["customer"]["cityName"] = getCity($databaseConnection, $_SESSION["user"]["customer"]["DeliveryCityID"]);
$adress = explode(" ", $_SESSION["user"]["customer"]["DeliveryAddressLine1"]);
$adressSlice = array_slice($adress, 0, -1);
$_SESSION["user"]["customer"]["streetName"] = implode(" ", $adressSlice);
$_SESSION["user"]["customer"]["houseNumber"] = end($adress);
?>
<form method="POST" name="bevestig" class="loginBox" action="userInfoAanpassen.php">
    <div class="informationBox">
        <h2>Uw gegevens Aanpassen</h2>
    </div>
    <div class="informationBox">
        <label>Naam: </label>
        <input type="text" class="FullName" name="FullName" value="<?php if (isset($_POST["FullName"])){ print($_POST["FullName"]);} else { print($_SESSION["user"]["FullName"]);} ?>" required>
    </div>
    <div class="informationBox">
        <label>email: </label>
        <input type="text" class="FullName" name="EmailAddress" value="<?php if (isset($_POST["EmailAddress"])){ print($_POST["EmailAddress"]);} else { print($_SESSION["user"]["EmailAddress"]);} ?>" required>
    </div>
    <div class="informationBox">
        <label>Telefoonnummer: </label>
        <input type="text" class="FullName" name="PhoneNumber" value="<?php if (isset($_POST["PhoneNumber"])){ print($_POST["PhoneNumber"]);} else { print($_SESSION["user"]["PhoneNumber"]);} ?>" required>
    </div>
    <div class="informationBox">
        <label>straatnaam: </label>
        <input type="text" class="FullName" name="streetName" value="<?php if (isset($_POST["streetName"])){ print($_POST["streetName"]);} else { print($_SESSION["user"]["customer"]["streetName"]); } ?>" required>
    </div>
    <div class="informationBox">
        <label>huisnummer: </label>
        <input type="text" class="FullName" name="houseNumber" value="<?php if (isset($_POST["houseNumber"])){ print($_POST["houseNumber"]);} else { print($_SESSION["user"]["customer"]["houseNumber"]); } ?>" required>
    </div>
    <div class="informationBox">
        <label>Postcode: </label>
        <input type="text" class="FullName" name="DeliveryPostalCode" value="<?php if (isset($_POST["DeliveryPostalCode"])){ print($_POST["DeliveryPostalCode"]);} else { print($_SESSION["user"]["customer"]["DeliveryPostalCode"]);} ?>" required>
    </div>
    <div class="informationBox">
        <label>Stad: </label>
        <input type="text" class="FullName" name="cityName" value="<?php if (isset($_POST["cityName"])){ print($_POST["cityName"]);} else { print($_SESSION["user"]["customer"]["cityName"]);} ?>" required>
    </div>
        <div class="login-input">
            <input type="submit" class="informationBox" name="aanpassingenOpslaan" value="Opslaan">
        </div>
</form>
<div class="loginBox">
    <?php
    if (isset($_POST["streetName"]) && isset($_POST["houseNumber"])) {
        $_POST["DeliveryAddressLine1"] = $_POST["streetName"] . " " . $_POST["houseNumber"];
        $_SESSION["user"]["customer"]["DeliveryAddressLine1"] = $_POST["DeliveryAddressLine1"];
    }
if (isset($_POST["aanpassingenOpslaan"]) && isset($_POST["FullName"]) && isset($_POST["PhoneNumber"]) && isset($_POST["EmailAddress"]) && isset($_POST["cityName"]) && isset($_POST["DeliveryAddressLine1"]) && isset($_POST["DeliveryPostalCode"])) {
    getCurrentUserID($databaseConnection, $_SESSION["userEmail"], $_SESSION["hashedPassword"]);
    $updated = updateAccount($databaseConnection, $_POST["FullName"], $_POST["PhoneNumber"], $_POST["EmailAddress"], $_SESSION["user"]["PersonID"]);
    $updatedCustomer = updateCustomer($databaseConnection, $_SESSION["user"]["customer"]["CustomerID"], $_POST["FullName"], $_POST["cityName"], $_POST["PhoneNumber"], $_POST["DeliveryAddressLine1"], $_POST["DeliveryPostalCode"], $_POST["DeliveryAddressLine1"], $_POST["DeliveryPostalCode"]);
if ($updated && $updatedCustomer) {
        print("Account aangepast.");
        getCurrentUser($databaseConnection, $_POST["EmailAddress"], $_SESSION["hashedPassword"]);
        $_SESSION["userEmail"] = $_POST["EmailAddress"];
        getUserCustomerInfo($databaseConnection, $_SESSION["userEmail"], $_SESSION["hashedPassword"]);
    } else {
        print("Account aanpassen mislukt.");
    }
}
?>
</div>
<?php
include __DIR__ . "/components/footer.php"
?>