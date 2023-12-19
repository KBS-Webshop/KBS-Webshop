<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
    $basket_contents = json_decode($_COOKIE["basket"], true);

}
$_SESSION["user"]["NAW"]["FullName"] = $_POST["naam"];
$_SESSION["user"]["NAW"]["CityName"] = $_POST["stad"];
$_SESSION["user"]["NAW"]["DeliveryAddressLine1"] = $_POST["adress"] . " " . $_POST["huisnummer"];
$_SESSION["user"]["NAW"]["DeliveryPostalCode"] = $_POST["postcode"];
$_SESSION["user"]["NAW"]["PhoneNumber"] = $_POST["telefoonnummer"];
$_SESSION["user"]["NAW"]["DeliveryInstructions"] = $_POST["bezorgInstructies"];
$_SESSION["user"]["NAW"]["DeliveryProvince"] = $_POST["provincie"];

foreach ($basket_contents as $item) {
    $StockItem = getStockItem($item["id"], $databaseConnection);
    if (intval(preg_replace('/[^0-9]+/', '', $StockItem["QuantityOnHand"]))<$item["amount"]){
        header("Location: winkelmand.php?message=geen_voorraad");
        $_SESSION["itemNietOpVoorraad"] = $StockItem["StockItemName"];
        $_SESSION["QuantityOnHand"]= (intval(preg_replace('/[^0-9]+/', '', $StockItem["QuantityOnHand"])));
    }
}
$_SESSION["order"]["placeOrder"] = TRUE;
if (isset($_POST["gelukt"])){
    print ("Bestelling word geplaatst.");
}
?>

<html>
<form method="post" name="mislukt" action="winkelmand.php">
    <input type="submit" name="mislukt" value="betaling annuleren">
</form>
<form method="post" name="gelukt" action="orderbevestiging.php">
    <input type="submit" name="gelukt" value="betaling geslaagd ">
</form>
</html>
<?php
include __DIR__ . "/components/footer.php"
?>
