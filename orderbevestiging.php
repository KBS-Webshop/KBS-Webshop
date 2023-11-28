<?php

include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
    $naam=$_SESSION["naam"];
    $telefoonnummer=$_SESSION["telefoonnummer"];
    $adress=$_SESSION["adress"];
    $postcode=$_SESSION["postcode"];
    $stad=$_SESSION["stad"];
    $orderID = getOrderID($databaseConnection);
?>
<h1> orderbevestiging</h1><br>
<h4><?php print $naam?> bedankt voor uw bestelling bij nerdygatgets! uw bestel nummer is <?php print $orderID?></h4><br>
<h1>Bestel overzicht</h1>
    <div class="winkelmand-wrapper">
    <ul class="winkelmand">
    <div id="ResultsArea" class="Winkelmand">
<?php
$totalprice = 0;
if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
    $basket_contents = json_decode($_COOKIE["basket"], true);
    foreach ($basket_contents as $item) {
        $StockItem = getStockItem($item["id"], $databaseConnection);
        $StockItemImage = getStockItemImage($item['id'], $databaseConnection);

        $totalprice += round($item['amount'] * $StockItem['SellPrice'], 2);
        changevoorraad($item["amount"] ,$StockItem,$databaseConnection);

        ?>
    <div id="ProductFrame1">
<?php
if (isset($StockItemImage[0]["ImagePath"])) { ?>
    <a class="ListItem" href='view.php?id=<?php print $item["id"]; ?>'>
        <div class="ImgFrame"
             style="background-image: url('<?php print "Public/StockItemIMG/" . $StockItemImage[0]["ImagePath"]; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        </div>
    </a>
<?php }
?>
    <div id="StockItemFrameRight" style="display: flex;flex-direction: column">
        <div class="CenterPriceLeft">
            <h1 class="StockItemPriceText"> <?php $price = sprintf("€ %.2f", $StockItem['SellPrice'] * $item["amount"]); $pricecoma= str_replace(".",",",$price);  print $pricecoma;?></h1>
            <h6> Inclusief BTW </h6>
        </div>
    </div>

    <h1 class="StockItemID"> <?php print ("artikelnummer: " . $item["id"]."<br>")?></h1>
    <h1 class="StockItemID1"> <?php print($StockItem["StockItemName"]."<br><br>aantal: ". $item['amount']) ?>
    <div class="buttonAlignmentWinkelmand">
                                </div>
                            </h1>
                        </div>
<?php
    }
}
?>
<br>
<h3 class="StockItemPriceTextbevestiging">Totaalprijs: € <?php print str_replace(".",",",$totalprice)?></h3><br>
    <h3 class="verzendadres">verzendadres: </h3>
<h4 class="verzendgegevens">
    naam: <?php print $naam?><br>
    adres: <?php print $adress." in ". $stad?><br>
    postcode: <?php print $postcode?><br>
    telefoonnummer: <?php print $telefoonnummer?><br>

</h4>
<?php
//if ($_SESSION["gelukt"] == TRUE){
//    $betaald = TRUE;
//} else {
//    $betaald = FALSE;
//}
$betaald = TRUE;
    $Cname = " ";
    $phoneNumber = " ";
    $DeliveryAddress = " ";
    $DeliveryPostalCode = " ";
    $DeliveryInstructions = "";
    $amountOfProductsInOrder = 0;
    $quantityOnHand = 0;
    $Cname = $_SESSION["naam"];
    $phoneNumber = $_SESSION["telefoonnummer"];
    $DeliveryAddress = $_SESSION["adress"];
    $DeliveryPostalCode = $_SESSION["postcode"];
    $DeliveryInstructions = $_SESSION["bezorgInstructies"];
    $cityName = $_SESSION["stad"];
    $DeliveryProvince = $_SESSION["provincie"];
    $dbConnection = connectToDatabase(); #TODO: 2e connectie word opgesteld moet later fixen dat we hier omheen komen.
    function PlaceOrder(
    $dbConnection,
    $Cname,
    $phoneNumber,
    $DeliveryAddress,
    $DeliveryPostalCode,
    $DeliveryInstructions,
    $betaald,
    $amountOfProductsInOrder,
    $quantityOnHand,
    $DeliveryProvince,
    $cityName
    ) {
    $orderstatus = "Wordt verwerkt";

    if ($betaald == true) {
    $countryID = 153;
    $newStateProvinceID = getNewStateProvinceID($dbConnection);
    $StateProvinceID = getStateProvince($dbConnection, $DeliveryProvince);
    $stateProvinceCode = abbreviate($DeliveryProvince);
    $newCityID = getNewCityID($dbConnection);
    $deliveryCityID = getCity($dbConnection, $cityName);
    $newCustomerID = getNewCustomerID($dbConnection);
    $customerId = getCustomer($dbConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
    $customerCategoryID = 8;
    $salesContactPersonID = 3262;
    $deliveryMethodID = 3;
    $standardDiscountPercentage = 0.000;
    $isOnCreditHold = 0;
    $isStatementSent = 0;
    $paymentDays = 7;
    $validTo = "9999-12-31 23:59:59";
    $websiteURL = "https://KBS.renzeboerman.nl";
    $currentDate = date("Y-m-d");
    $estimatedDeliveryDate = date("Y-m-d", strtotime($currentDate . "+ 1 days"));
    if ($StateProvinceID == null) {
    addStateProvince($dbConnection, $newStateProvinceID, $stateProvinceCode, $countryID, $DeliveryProvince, $salesContactPersonID, $currentDate, $validTo);
    $StateProvinceID = getStateProvince($dbConnection, $DeliveryProvince);
    } else {
    $StateProvinceID = getStateProvince($dbConnection, $DeliveryProvince);
    }
    if ($deliveryCityID == null) {
    addCity ($dbConnection, $newCityID, $cityName, $StateProvinceID, $salesContactPersonID, $currentDate, $validTo);
    $deliveryCityID = getCity($dbConnection,$cityName);
    } else {
    $deliveryCityID = getCity($dbConnection, $cityName);
    }
    if ($customerId == null) {
    addCustomer(
    $dbConnection,
    $newCustomerID,
    $Cname,
    $phoneNumber,
    $DeliveryAddress,
    $DeliveryPostalCode,
    $deliveryCityID,
    $customerCategoryID,
    $salesContactPersonID,
    $deliveryMethodID,
    $currentDate,
    $standardDiscountPercentage,
    $isStatementSent,
    $isOnCreditHold,
    $paymentDays,
    $websiteURL,
    $validTo);
    $customerId = getCustomer($dbConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
    } else {
    $customerId = getCustomer($dbConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
    }
    if ($quantityOnHand < $amountOfProductsInOrder) {
    $isInStock = 0;
    } else {
    $isInStock = 1;
    }
    addOrder($dbConnection, $customerId, $DeliveryInstructions, $currentDate, $estimatedDeliveryDate, $salesContactPersonID, $isInStock);

    $OrderID = getOrderID($dbConnection);

    $basket_contents = json_decode($_COOKIE["basket"], true);
    foreach ($basket_contents as $item) {

    if (isset($item["amount"])) {
    $amountOfProductsInOrder = $item["amount"];
    }
    if (isset($row["quantityOnHand"])) {
    $quantityOnHand = $row["quantityOnHand"];
    }
    $stockItemID = $item["id"];
    $ProductDescription = getDescription($dbConnection, $stockItemID);
    $PackageTypeID = getPackageTypeID($dbConnection, $stockItemID);
    $UnitPrice = getUnitPrice($dbConnection, $stockItemID);
    $TaxRate = getTaxRate($dbConnection, $stockItemID);
    addOrderline($dbConnection, $OrderID, $stockItemID, $ProductDescription, $PackageTypeID, $amountOfProductsInOrder, $UnitPrice, $TaxRate, $salesContactPersonID, $currentDate);
    }

    $orderstatus = "Order is geplaatst";

    } else {

    $orderstatus = "Order is niet geplaatst";

    }

    return $orderstatus;
    }
if (isset($_SESSION["naam"]) && isset($_SESSION["telefoonnummer"]) && isset($_SESSION["adress"]) && isset($_SESSION["postcode"]) && isset($_SESSION["provincie"]) && isset($_SESSION["stad"])) {
    PlaceOrder(
        $dbConnection,
        $Cname,
        $phoneNumber,
        $DeliveryAddress,
        $DeliveryPostalCode,
        $DeliveryInstructions,
        $betaald,
        $amountOfProductsInOrder,
        $quantityOnHand,
        $DeliveryProvince,
        $cityName
    );
}
include __DIR__ . "/components/footer.php"
?>