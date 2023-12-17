<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
    $basket_contents = json_decode($_COOKIE["basket"], true);

}

$_SESSION["naam"]=$_POST["naam"];
$_SESSION["telefoonnummer"]=$_POST["telefoonnummer"];
$_SESSION["adress"]=$_POST["adress"] . " " . $_POST["huisnummer"];
$_SESSION["postcode"]=$_POST["postcode"];
$_SESSION["stad"]=$_POST["stad"];
$_SESSION["bezorgInstructies"] = $_POST["bezorgInstructies"];
$_SESSION["provincie"] = $_POST["provincie"];
$_SESSION["price"] = $_POST["price"];


foreach ($basket_contents as $item) {
    $StockItem = getStockItem($item["id"], $databaseConnection);
    if (intval(preg_replace('/[^0-9]+/', '', $StockItem["QuantityOnHand"]))<$item["amount"]){
        header("Location: winkelmand.php?message=geen_voorraad");
        $_SESSION["itemNietOpVoorraad"] = $StockItem["StockItemName"];
        $_SESSION["QuantityOnHand"]= (intval(preg_replace('/[^0-9]+/', '', $StockItem["QuantityOnHand"])));
    }
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
