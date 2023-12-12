<?php

function abbreviate($string){ # maakt een afkorting van de ingevoerde waarde
    $abbreviation = "";
    $string = ucwords($string);
    $words = explode(" ", "$string");
    foreach($words as $word){
        $abbreviation .= $word[0];
    }
    return $abbreviation;
}
function getNewStateProvinceID($dbConnection)
{
    $Query = "
    SELECT max(StateProvinceID)
    FROM stateprovinces";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    $R = intval($R['max(StateProvinceID)'], 10);
    $R = $R + 1;
    return $R;
}
function getStateProvince ($dbConnection, $provinceName) {
    $Query = "
    SELECT StateProvinceID
    FROM stateprovinces
    WHERE StateProvinceName = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $provinceName);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    if ($R == null) {
        return $R;
    } else {
        $R = intval($R['StateProvinceID'], 10);
        return $R;
    }
}
function addStateProvince ($dbConnection, $newStateProvinceID, $stateProvinceCode, $countryID, $DeliveryProvince, $salesContactPersonID, $currentDate, $validTo) {
    $Query = "
    INSERT INTO stateprovinces (StateProvinceID, StateProvinceCode, StateProvinceName, CountryID, SalesTerritory, LastEditedBy, ValidFrom, ValidTo)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "issisiss", $newStateProvinceID, $stateProvinceCode, $DeliveryProvince, $countryID, $DeliveryProvince, $salesContactPersonID, $currentDate, $validTo);
    mysqli_stmt_execute($Statement);
}

function getNewCityID ($dbConnection) {
    $Query = "
    SELECT max(CityID)
    FROM cities";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
    $R = intval($R[0]['max(CityID)'], 10);
    $R = $R + 1;
    return $R;
}
function getCity ($dbConnection, $cityName) {
    $Query = "
    SELECT CityID
    FROM cities
    WHERE CityName = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "s", $cityName);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    if ($R == null) {
        return $R;
    } else {
        $R = intval($R['CityID'], 10);
        return $R;
    }
}

function addCity ($dbConnection, $newCityID, $cityName, $StateProvinceID, $salesContactPersonID, $currentDate, $validTo){
    $Query = "
    INSERT INTO cities (CityID, CityName, StateProvinceID, LastEditedBy, ValidFrom, ValidTo)
    VALUES (?, ?, ?, ?, ?, ?)";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "isiiss", $newCityID, $cityName, $StateProvinceID, $salesContactPersonID, $currentDate, $validTo);
    mysqli_stmt_execute($Statement);
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
    $Query = "
    INSERT INTO orders (CustomerID, OrderDate, ExpectedDeliveryDate, DeliveryInstructions, salespersonPersonID, ContactPersonID, IsUndersupplyBackordered, lastEditedBy, lastEditedWhen)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "isssiiiis", $CustomerId, $currentDate, $estimatedDeliveryDate , $DeliveryInstructions, $salesContactPersonID, $salesContactPersonID, $isInStock, $salesContactPersonID , $currentDate);
    mysqli_stmt_execute($Statement);
}

function addOrderline($dbConnection, $OrderID, $stockItemID, $StockItem, $amountOfProductsInOrder, $salesContactPersonID, $currentDate) {
    $Query = "
    INSERT INTO orderlines (OrderID, StockItemID, Description, PackageTypeID, Quantity, UnitPrice, TaxRate, PickedQuantity, LastEditedBy, LastEditedWhen)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "iisiidddis", $OrderID, $stockItemID, $StockItem["MarketingComments"], $StockItem["UnitPackageID"], $amountOfProductsInOrder, $StockItem["UnitPrice"], $StockItem["TaxRate"], $amountOfProductsInOrder, $salesContactPersonID, $currentDate);
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
function definiteAddCustomer ($databaseConnection, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $cityName, $DeliveryProvince, $PersonID) {
    $countryID = 153;
    $newStateProvinceID = getNewStateProvinceID($databaseConnection);
    $StateProvinceID = getStateProvince($databaseConnection, $DeliveryProvince);
    $stateProvinceCode = abbreviate($DeliveryProvince);
    $newCityID = getNewCityID($databaseConnection);
    $deliveryCityID = getCity($databaseConnection, $cityName);
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
}
