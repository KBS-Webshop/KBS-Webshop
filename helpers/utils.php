<?php

function getVoorraadTekst($actueleVoorraad)
{
    if ($actueleVoorraad > 1000) {
        return "Ruime voorraad beschikbaar.";
    } else {
        return "Voorraad: $actueleVoorraad";
    }
}
function berekenVerkoopPrijs($adviesPrijs, $btw)
{
    return $btw * $adviesPrijs / 100 + $adviesPrijs;
}

function PlaceOrder(
    $Cname,
    $phoneNumber,
    $DeliveryAddress,
    $DeliveryPostalCode,
    $DeliveryInstructions,
    $databaseConnection,
    $betaald,
    $amountOfProductsInOrder,
    $quantityOnHand,
    $DeliveryProvince,
    $cityName
) {

    $orderstatus = "Wordt verwerkt";

    if ($betaald == true) {
        $countryID = 153;
        $newStateProvinceID = getNewStateProvinceID($databaseConnection);
        $provinceID = getStateProvince($DeliveryProvince, $databaseConnection);
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
        if ($DeliveryProvince == null) {
            addStateProvince($newStateProvinceID, $stateProvinceCode, $DeliveryProvince, $countryID, $DeliveryProvince, $salesContactPersonID, $currentDate, $validTo,$databaseConnection);
            $DeliveryProvince = getStateProvince($DeliveryProvince, $databaseConnection);
        } else {
            $DeliveryProvince = getStateProvince($DeliveryProvince, $databaseConnection);
        }
        if ($deliveryCityID == null) {
            addCity ($newCityID, $cityName, $DeliveryProvince, $salesContactPersonID, $currentDate, $validTo, $databaseConnection);
            $deliveryCityID = getCity($cityName, $databaseConnection);
        } else {
            $deliveryCityID = getCity($cityName, $databaseConnection);
        }
        if ($customerId == null) {
            addCustomer($newCustomerID, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $deliveryCityID, $databaseConnection);
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