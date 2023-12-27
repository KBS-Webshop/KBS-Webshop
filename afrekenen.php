<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '');
$dotenv->load();
include "helpers/database/database.php";
include "helpers/database/order.php";
include "helpers/database/stock.php";

$databaseConnection = connectToDatabase();

include __DIR__ . "/helpers/utils.php";
include __DIR__ . "/helpers/cookie.php";

if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
    $basket_contents = json_decode($_COOKIE["basket"], true);
}
$_SESSION["NAW"]["FullName"] = $_POST["naam"];
$_SESSION["NAW"]["CityName"] = $_POST["stad"];
$_SESSION["NAW"]["DeliveryAddressLine1"] = $_POST["adress"] . " " . $_POST["huisnummer"];
$_SESSION["NAW"]["DeliveryPostalCode"] = $_POST["postcode"];
$_SESSION["NAW"]["PhoneNumber"] = $_POST["telefoonnummer"];
$_SESSION["NAW"]["DeliveryInstructions"] = $_POST["bezorgInstructies"];

if (!cityExists($_SESSION["NAW"]["CityName"], $databaseConnection)) {
    header("Location: naw.php?message=wrong_city");
}

foreach ($basket_contents as $item) {
    $StockItem = getStockItem($item["id"], $databaseConnection);
    if (intval(preg_replace('/[^0-9]+/', '', $StockItem["QuantityOnHand"])) < $item["amount"]){
        header("Location: winkelmand.php?message=geen_voorraad");
        $_SESSION["itemNietOpVoorraad"] = $StockItem["StockItemName"];
        $_SESSION["QuantityOnHand"]= (intval(preg_replace('/[^0-9]+/', '', $StockItem["QuantityOnHand"])));
    }
}
?>

<form method="post" name="mislukt" action="winkelmand.php">
    <input type="submit" name="mislukt" value="betaling annuleren">
</form>
<form method="POST" action="orderbevestiging.php">
    <input type="hidden" name="action" value="remove_deal_from_cart">
    <input type="submit" value="betaling geslaagd ">
</form>

<?php
$_SESSION["order"]["placeOrder"] = TRUE;
if (isset($_POST["gelukt"])){
    print ("Bestelling word geplaatst.");
}
include __DIR__ . "/components/footer.php"
?>
