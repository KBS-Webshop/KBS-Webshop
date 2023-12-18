<?php

function getVoorraadTekst($actueleVoorraad)
{
    if ($actueleVoorraad > 1000) {
        return "Ruime voorraad beschikbaar.";
    } else {
        return "Voorraad: $actueleVoorraad";
    }
}

function berekenVerkoopPrijs($adviesPrijs, $btw) {
    return $btw * $adviesPrijs / 100 + $adviesPrijs;
}

function formatPrice($price) {
    return str_replace('.', ',', sprintf("€%.2f", $price));
}

function PlaceOrder(
    $databaseConnection,
    $Cname,
    $phoneNumber,
    $DeliveryAddress,
    $DeliveryPostalCode,
    $DeliveryInstructions,
    $betaald,
    $amountOfProductsInOrder,
    $quantityOnHand,
    $DeliveryProvince,
    $cityName,
    $price
) {
    $orderstatus = "Wordt verwerkt";

    if ($betaald == true) {
        $countryID = 153;
        $newStateProvinceID = getNewStateProvinceID($databaseConnection);
        $StateProvinceID = getStateProvince($databaseConnection, $DeliveryProvince);
        $stateProvinceCode = abbreviate($DeliveryProvince);
        $newCityID = getNewCityID($databaseConnection);
        $deliveryCityID = getCity($databaseConnection, $cityName);
        $newCustomerID = getNewCustomerID($databaseConnection);
        $customerId = getCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
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
            addStateProvince($databaseConnection, $newStateProvinceID, $stateProvinceCode, $countryID, $DeliveryProvince, $salesContactPersonID, $currentDate, $validTo);
            $StateProvinceID = getStateProvince($databaseConnection, $DeliveryProvince);
        } else {
            $StateProvinceID = getStateProvince($databaseConnection, $DeliveryProvince);
        }

        if ($deliveryCityID == null) {
            addCity($databaseConnection, $newCityID, $cityName, $StateProvinceID, $salesContactPersonID, $currentDate, $validTo);
            $deliveryCityID = getCity($databaseConnection, $cityName);
        } else {
            $deliveryCityID = getCity($databaseConnection, $cityName);
        }

        if ($customerId == null) {
            addCustomer(
                $databaseConnection,
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
                $validTo
            );

            $customerId = getCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
        } else {
            $customerId = getCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
        }
        if ($quantityOnHand < $amountOfProductsInOrder) {
            $isInStock = 0;
        } else {
            $isInStock = 1;
        }
        addOrder($databaseConnection, $customerId, $DeliveryInstructions, $currentDate, $estimatedDeliveryDate, $salesContactPersonID, $isInStock);
        $OrderID = getOrderID($databaseConnection);

        calculateAndRemovePoints($price, 1, $databaseConnection);
        calculateAndAddPoints((float) $price, 1, $databaseConnection);
        removeDealFromCart();

        $basket_contents = json_decode($_COOKIE["basket"], true);

        foreach ($basket_contents as $item) {
            if (isset($item["amount"])) {
                $amountOfProductsInOrder = $item["amount"];
                $item["amount"] = intval($item["amount"], 10);
            }

            if (isset($row["quantityOnHand"])) {
                $quantityOnHand = $row["quantityOnHand"];
                $row["quantityOnHand"] = intval($row["quantityOnHand"], 10);
            }

            $stockItemID = $item["id"];
            $StockItem = getStockItem($stockItemID, $databaseConnection);
            changevoorraad($databaseConnection, $amountOfProductsInOrder, $stockItemID);
            $deal = getLoyaltyDeal(getDealInCart(), $databaseConnection);
            if ($deal != null) {
                $discount = $deal["discount"];
            } else {
                $discount = 0;
            }
            addOrderline($databaseConnection, $OrderID, $stockItemID, $StockItem, $amountOfProductsInOrder, $salesContactPersonID, $currentDate, $discount);
        }
        return $OrderID;
    }
}
?>