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
    $cityName
)
{
    $orderstatus = "Wordt verwerkt";

    if ($betaald == true) {
        $customerId = getCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
        $currentDate = date("Y-m-d");
        $estimatedDeliveryDate = date("Y-m-d", strtotime($currentDate . "+ 1 days"));
        $salesContactPersonID = 3262;

        if ($customerId == null) {
            definiteAddCustomer($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $cityName, $DeliveryProvince, $customerId);
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
            addOrderline($databaseConnection, $OrderID, $stockItemID, $StockItem, $amountOfProductsInOrder, $salesContactPersonID, $currentDate);
        }
        return $OrderID;
    }
}
?>