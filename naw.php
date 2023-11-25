<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
$Cname = " ";
$phoneNumber = " ";
$DeliveryAddress = " ";
$DeliveryPostalCode = " ";
$DeliveryInstructions = "";
$amountOfProductsInOrder = 0;
$quantityOnHand = 0;
$betaald = true;
if (isset($_POST["naam"])) {
    $Cname = $_POST["naam"];
}
if (isset($_POST["telefoonnummer"])) {
    $phoneNumber = $_POST["telefoonnummer"];
}
if (isset($_POST["adress"])) {
    $DeliveryAddress = $_POST["adress"];
}
if (isset($_POST["postcode"])) {
    $DeliveryPostalCode = $_POST["postcode"];
}
if (isset($_POST["bezorgInstructies"])) {
    $DeliveryInstructions = $_POST["bezorgInstructies"];
}
if (isset($_POST["stad"])) {
    $cityName = $_POST["stad"];
}
if (isset($_POST["provincie"])) {
    $DeliveryProvince = $_POST["provincie"];
}
//if (isset($_POST["land"])) {
//    $DeliveryCountry = $_POST["land"];
//}

function PlaceOrder(
    $Cname,
    $phoneNumber,
    $DeliveryAddress,
    $DeliveryPostalCode,
    $DeliveryInstructions,
    $betaald,
    $amountOfProductsInOrder,
    $quantityOnHand,
    $databaseConnection,
    $DeliveryProvince,
    $cityName
) {

    $orderstatus = "Wordt verwerkt";

    if ($betaald == true) {
        $countryID = 153;
        $newStateProvinceID = getNewStateProvinceID($databaseConnection);
        $StateProvinceID = getStateProvince($DeliveryProvince, $databaseConnection);
        $stateProvinceCode = abbreviate($DeliveryProvince);
        $newCityID = getNewCityID($databaseConnection);
        $deliveryCityID = getCity($cityName, $databaseConnection);
        $newCustomerID = getNewCustomerID($databaseConnection);
        $customerId = getCustomer($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $databaseConnection);
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
            addStateProvince($newStateProvinceID, $stateProvinceCode, $countryID, $DeliveryProvince, $salesContactPersonID, $currentDate, $validTo,$databaseConnection);
            $StateProvinceID = getStateProvince($DeliveryProvince, $databaseConnection);
        } else {
            $StateProvinceID = getStateProvince($DeliveryProvince, $databaseConnection);
        }
        if ($deliveryCityID == null) {
            addCity ($newCityID, $cityName, $StateProvinceID, $salesContactPersonID, $currentDate, $validTo, $databaseConnection);
            $deliveryCityID = getCity($cityName, $databaseConnection);
        } else {
            $deliveryCityID = getCity($cityName, $databaseConnection);
        }
        if ($customerId == null) {
            addCustomer($newCustomerID, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $deliveryCityID, $deliveryCityID, $countryID, $customerCategoryID, $salesContactPersonID, $DeliveryInstructions, $deliveryMethodID, $standardDiscountPercentage, $isOnCreditHold, $isStatementSent, $paymentDays, $currentDate, $validTo, $websiteURL, $databaseConnection);
            $customerId = getCustomer($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $databaseConnection);
        } else {
            $customerId = getCustomer($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $databaseConnection);
        }
        if ($quantityOnHand < $amountOfProductsInOrder) {
            $isInStock = 0;
        } else {
            $isInStock = 1;
        }
        addOrder($customerId, $DeliveryInstructions, $currentDate, $estimatedDeliveryDate, $salesContactPersonID, $databaseConnection, $isInStock);

        $OrderID = getOrderID($customerId, $databaseConnection);

        $basket_contents = json_decode($_COOKIE["basket"], true);
        foreach ($basket_contents as $item) {

            if (isset($item["amount"])) {
                $amountOfProductsInOrder = $item["amount"];
            }
            if (isset($row["quantityOnHand"])) {
                $quantityOnHand = $row["quantityOnHand"];
            }
            $stockItemID = $item["id"];
            $ProductDescription = getDescription($stockItemID, $databaseConnection);
            $PackageTypeID = getPackageTypeID($stockItemID, $databaseConnection);
            $UnitPrice = getUnitPrice($stockItemID, $databaseConnection);
            $TaxRate = getTaxRate($stockItemID, $databaseConnection);
            addOrderline($OrderID, $stockItemID, $ProductDescription, $PackageTypeID, $amountOfProductsInOrder, $UnitPrice, $TaxRate, $salesContactPersonID, $currentDate, $databaseConnection);
        }

        $orderstatus = "Order is geplaatst";

    } else {

        $orderstatus = "Order is niet geplaatst";

    }

    return $orderstatus;
}
?>
<h2>bestelgegevens</h2><br>
<?php
if (isset($_COOKIE["basket"]) AND !cookieEmpty()) {
?>
<div class="bonnetje-wrapper">
    <?php

    $basket_contents = json_decode($_COOKIE["basket"], true);
    ?>
    <table>
        <th>Product</th>
        <th>Aantal</th>
        <th>Prijs</th>

        <?php
        foreach ($basket_contents as $item) {
            $totalprice=0;
            $StockItem = getStockItem($item["id"], $databaseConnection);
            $totalprice += round($item['amount'] * $StockItem['SellPrice'], 2);
            echo ("<tr> <td>" . $StockItem['StockItemName'] . "</td>");
            echo ("<td>" . $item['amount'] . "</td>");
            echo "<td>".sprintf("€%.2f", $StockItem['SellPrice'] * $item["amount"]);
        }
        echo ("<tr class='receivedTotalPrice'> <td></td> <th>totaalprijs</th>");
        echo("<td>$totalprice</td></tr>");
        echo '</table>';

        ?>

        <?php } ?>
</div>
<html>

<!--<form method="POST" name="bevestig" class="naw-form" action="afrekenen.php">-->
    <form method="POST" class="naw-form">
    <div class="naw-input">
        <label for="name">
          Naam <span class="required"></span>
        </label>
        <input type="text" name="naam" id="naam" required>
    </div>

    <div class="naw-input form-width-2">
        <div class="naw-input-inner">
            <label for="straatnaam" class="inline-label">
                Adress <span class="required"></span>
            </label>
            <input type="text" name="adress" id="adress" required>
        </div>
    </div>

    <div class="naw-input form-width-4">
            <div class="naw-input-inner">
                <label for="name" class="inline-label">
                    Postcode <span class="required"></span>
                </label>
            <input type="text" name="postcode" id="postcode" required>
        </div>
        <div class="naw-input-inner">
            <label for="name" class ="inline-label">
                Stad <span class="required"></span>
            </label>
            <input type="text" name="stad" id="stad" required>
        </div>
        <div class="naw-input-inner">
            <label for="name" class ="inline-label">
                Provincie <span class="required"></span>
            </label>
            <input type="text" name="provincie" id="provincie" required>
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name" class="required">
                Telefoonnummer
            </label>
            <input type="text" name="telefoonnummer" id="telefoonnummer" >
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Email-adres <span class="required"></span>
            </label>
            <input type="text" name="email" id="email" required>
        </div>
    </div>
<?php
//print ($Cname); ?><!-- <BR> --><?php
//print ($phoneNumber); ?><!-- <BR> --><?php
//    print ($DeliveryAddress); ?><!-- <BR> --><?php
//    print ($DeliveryPostalCode); ?><!-- <BR> --><?php
//    print ($DeliveryInstructions); ?><!-- <BR> --><?php
//    ?>
    <div class="radio-container">

        <fieldset>
            <legend>Verzendopties</legend>
        <div class="radio-label-naw">
                <label>
                    <input type="radio" name="Verzending" id="standaardVerzending" required>
                    Standaard verzending
                </label>
                <label>
                    <input type="radio" name="Verzending" id="expressVerzending" required>
                    Express verzending
                </label>
        </div>
        </fieldset>

        <fieldset>

        <div class="radio-label">
            <legend>Betaalmogelijkheden</legend>
                <label>
                    <input type="radio" name="betaalmethode" id="iDeal" required>
                    iDeal
                </label>
                <label>
                    <input class="nerdy" type="radio" name="betaalmethode" id="Nerdygadgets Giftcard" required>
                    Nerdygadgets Gifcard
                </label>
        </div>
        </fieldset>
    </div>

    <div class="comments">
        <div>
            <label for="opmerkingen">Instructies voor de bezorger. (Optioneel)</label>
        </div>
        <div>
            <textarea id="bezorgInstructies" name="bezorgInstructies" rows="6" cols="50"></textarea>
        </div>
    </div>

    <div class="naw-submit-wrapper">
        <input type="submit" name="bevestig" value="Afrekenen" class="button primary">
    </div>
</form>
</html>



<?php
if (isset($_POST["naam"]) && isset($_POST["telefoonnummer"]) && isset($_POST["adress"]) && isset($_POST["postcode"])) {
    $orderstatus = PlaceOrder($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $DeliveryInstructions, $databaseConnection, $betaald, $amountOfProductsInOrder, $quantityOnHand, $DeliveryProvince, $cityName);
    print ($orderstatus);

}

include __DIR__ . "/components/footer.php"
?>
