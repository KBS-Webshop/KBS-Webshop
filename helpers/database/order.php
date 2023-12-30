<?php

function getCity ($dbConnection, $cityID) {
    $Query = "
    SELECT Cityname
    FROM cities
    WHERE CityID = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $cityID);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    if ($R != null) {
        return $R["Cityname"];
    } else {
        return $R;
    }
}

function getNewCustomerID($dbConnection)
{
    $Query = "
    SELECT max(CustomerID)
    FROM customers";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    $R = intval($R['max(CustomerID)'], 10);
    $R = $R + 1;
    return $R;
}
function getCustomer($dbConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode) {
    $Query = "
    SELECT CustomerID
    FROM customers
    WHERE CustomerName = ? AND PhoneNumber = ? AND DeliveryAddressLine1 = ? AND DeliveryPostalCode = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "ssss", $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    if ($R == null) {
        return $R;
    } else {
        $R = intval($R['CustomerID'], 10);
        return $R;
    }
}

function addCustomer ($dbConnection, $newCustomerID, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $deliveryCityID, $customCategoryID, $salesContactPersonID, $deliveryMethodID, $currentDate, $standardDiscountPercentage, $isStatementSent, $isOnCreditHold, $paymentDays, $websiteURL, $validTo, $PersonID) {
    $Query = "
    INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, PrimaryContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, WebsiteURL, DeliveryAddressLine1, DeliveryPostalCode, PostalAddressLine1, PostalPostalCode, LastEditedBy, ValidFrom, ValidTo, PersonID)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?, ?)";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "ssiiiiiisiiiisssssssissi", $newCustomerID, $Cname, $newCustomerID, $customCategoryID, $salesContactPersonID, $deliveryMethodID, $deliveryCityID, $deliveryCityID, $currentDate, $standardDiscountPercentage, $isStatementSent, $isOnCreditHold, $paymentDays, $phoneNumber, $phoneNumber, $websiteURL, $DeliveryAddress, $DeliveryPostalCode, $DeliveryAddress, $DeliveryPostalCode, $salesContactPersonID, $currentDate, $validTo, $PersonID);
    mysqli_stmt_execute($Statement);
}
function getOrderID ($dbConnection) {
    $Query = "
    SELECT max(OrderID)
    FROM orders";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
    $R = intval($R[0]['max(OrderID)'], 10);
    return $R;
}

function addOrder ($dbConnection, $CustomerId, $DeliveryInstructions, $currentDate, $estimatedDeliveryDate , $salesContactPersonID, $isInStock) {
    $Query = "CALL addOrder(?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "isssiiiis", $CustomerId, $currentDate, $estimatedDeliveryDate , $DeliveryInstructions, $salesContactPersonID, $salesContactPersonID, $isInStock, $salesContactPersonID , $currentDate);
    mysqli_stmt_execute($Statement);
}

function addOrderline($dbConnection, $OrderID, $stockItemID, $StockItem, $amountOfProductsInOrder, $salesContactPersonID, $currentDate, $discount) {
    $price = sprintf("%.2f", $StockItem["UnitPrice"] / 100 * (100 - $discount));
    $Query = "CALL addOrderline(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "iisiidddis", $OrderID, $stockItemID, $StockItem["MarketingComments"], $StockItem["UnitPackageID"], $amountOfProductsInOrder, $price, $StockItem["TaxRate"], $amountOfProductsInOrder, $salesContactPersonID, $currentDate);
    mysqli_stmt_execute($Statement);
}
function changevoorraad($dbConnection, $amount, $stockItemID){
    $Query = "UPDATE nerdygadgets.stockitemholdings t
              SET t.QuantityOnHand = t.QuantityOnHand - ?
              WHERE t.StockItemID = ?";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "ii", $amount, $stockItemID);
    mysqli_stmt_execute($Statement);

}
function definiteAddCustomer ($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $cityName, $PersonID) {
    $countryID = 153;
    $newCustomerID = getNewCustomerID($databaseConnection);
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
    $deliveryCityID = getCityID($databaseConnection, $cityName);
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
        $validTo,
        $PersonID
    );
    return TRUE;
}

function cityExists($cityName, $dbConnection) {
    $Query = "
        SELECT CityName
        FROM cities
        WHERE CityName = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $cityName);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
    if (count($R) == 0) {
        return false;
    }
    return true;
}
