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
        if (!isset($_SESSION["user"]["PersonID"])){
        $_SESSION["user"]["PersonID"] = null;
        }
    $customerId = getCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $_SESSION["user"]["PersonID"]);
        $currentDate = date("Y-m-d");
        $estimatedDeliveryDate = date("Y-m-d", strtotime($currentDate . "+ 1 days"));
        $salesContactPersonID = 3262;

        if ($customerId == null) {
            definiteAddCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $cityName, $DeliveryProvince, $customerId);
            $customerId = getCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $_SESSION["user"]["PersonID"]);
        } else {
            $customerId = getCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $_SESSION["user"]["PersonID"]);
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
        # removeDealFromCart();
        $_POST['action'] = 'remove_deal_from_cart';

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

function calculateDiscountedPriceBTW($price, $discountPercentage, $btw, $amount=1, $numeric=false)
{
    $discountedPrice = ($price * (1 - $discountPercentage / 100)) * $amount;
    $discountedPriceBTW = $discountedPrice * (1 + $btw / 100);
    if ($numeric) return $discountedPriceBTW;
    return formatPrice($discountedPriceBTW);
}

function calculatePriceBTW($price, $btw, $amount=1, $numeric=false)
{
    $priceBTW = ($price * (1 + $btw / 100)) * $amount;
    if ($numeric) return $priceBTW;
    return formatPrice($priceBTW);
}
?>