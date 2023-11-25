<!-- dit bestand bevat alle code die verbinding maakt met de database -->
<?php

require 'vendor/autoload.php'; // Zorg ervoor dat je de Composer autoloader hebt ingesloten

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

function connectToDatabase()
{
    $Connection = null;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
    try {
        $Connection = mysqli_connect($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        mysqli_set_charset($Connection, 'latin1');
        $DatabaseAvailable = true;
    } catch (mysqli_sql_exception $e) {
        $DatabaseAvailable = false;
    }
    if (!$DatabaseAvailable) {
        ?>
        <h2>Website wordt op dit moment onderhouden.</h2>
        <?php
        die();
    }

    return $Connection;
}

function getHeaderStockGroups($databaseConnection)
{
    $Query = "
                SELECT StockGroupID, StockGroupName, ImagePath
                FROM stockgroups 
                WHERE StockGroupID IN (
                                        SELECT StockGroupID 
                                        FROM stockitemstockgroups
                                        ) AND ImagePath IS NOT NULL
                ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $HeaderStockGroups = mysqli_stmt_get_result($Statement);
    return $HeaderStockGroups;
}

function getStockGroups($databaseConnection)
{
    $Query = "
            SELECT StockGroupID, StockGroupName, ImagePath
            FROM stockgroups 
            WHERE StockGroupID IN (
                                    SELECT StockGroupID 
                                    FROM stockitemstockgroups
                                    ) AND ImagePath IS NOT NULL
            ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $StockGroups = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $StockGroups;
}

function getStockItem($id, $databaseConnection)
{
    $Result = null;

    $Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
            SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}

function getStockItemImage($id, $databaseConnection)
{

    $Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}



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
    mysqli_stmt_bind_param($Statement, "i", $cityName);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    return intval($R['CityID'], 10);
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

function addCustomer ($dbConnection, $newCustomerID, $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $deliveryCityID, $customCategoryID, $salesContactPersonID, $deliveryMethodID, $currentDate, $standardDiscountPercentage, $isStatementSent, $isOnCreditHold, $paymentDays, $websiteURL, $validTo) {
    $Query = "
    INSERT INTO customers (CustomerID, CustomerName, BillToCustomerID, CustomerCategoryID, PrimaryContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, AccountOpenedDate, StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, WebsiteURL, DeliveryAddressLine1, DeliveryPostalCode, PostalAddressLine1, PostalPostalCode, LastEditedBy, ValidFrom, ValidTo)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?)";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "ssiiiiiisiiiisssssssiss", $newCustomerID, $Cname, $newCustomerID, $customCategoryID, $salesContactPersonID, $deliveryMethodID, $deliveryCityID, $deliveryCityID, $currentDate, $standardDiscountPercentage, $isStatementSent, $isOnCreditHold, $paymentDays, $phoneNumber, $phoneNumber, $websiteURL, $DeliveryAddress, $DeliveryPostalCode, $DeliveryAddress, $DeliveryPostalCode, $salesContactPersonID, $CurrentDate, $validTo);
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
    INSERT INTO orders (CustomerID, OrderDate, ExpectedDeliveryDate, DeliveryInstructions, salespersonPersonID, IsUndersupplyBackordered, lastEditedBy)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "isssiiis", $CustomerId, $currentDate, $estimatedDeliveryDate , $DeliveryInstructions, $salesContactPersonID, $isInStock, $salesContactPersonID, $CurrentDate);
    mysqli_stmt_execute($Statement);
}

#mijn versie
//function getDescription ($dbConnection, $stockItemID) {
//    $Query = "
//    SELECT MarketingComments
//    FROM stockitems
//    WHERE StockItemID = ?";
//
//    $Statement = mysqli_prepare($dbConnection, $Query);
//    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
//    mysqli_stmt_execute($Statement);
//    $R = mysqli_stmt_get_result($Statement);
//    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
//    return $R;
//}
//function getPackageTypeID ($dbConnection, $stockItemID) {
//    # UnitPackageID of OuterPackageID
//    $Query = "
//    SELECT UnitPackageID
//    FROM stockitems
//    WHERE StockItemID = ?";
//
//    $Statement = mysqli_prepare($dbConnection, $Query);
//    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
//    mysqli_stmt_execute($Statement);
//    $R = mysqli_stmt_get_result($Statement);
//    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
//    return $R;
//}
//function getUnitPrice ($dbConnection, $stockItemID) {
//    $Query = "
//    SELECT UnitPrice
//    FROM stockitems
//    WHERE StockItemID = ?";
//
//    $Statement = mysqli_prepare($dbConnection, $Query);
//    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
//    mysqli_stmt_execute($Statement);
//    $R = mysqli_stmt_get_result($Statement);
//    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
//    return $R;
//}
//function getTaxRate ($dbConnection, $stockItemID) {
//    $Query = "
//    SELECT TaxRate
//    FROM stockitems
//    WHERE StockItemID = ?";
//
//    $Statement = mysqli_prepare($dbConnection, $Query);
//    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
//    mysqli_stmt_execute($Statement);
//    $R = mysqli_stmt_get_result($Statement);
//    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
//    return $R;
//}
# chatgpt versie
function getDescription($dbConnection, $stockItemID) {
    $Query = "
    SELECT MarketingComments
    FROM stockitems
    WHERE StockItemID = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    return $R['MarketingComments'];
}

function getPackageTypeID($dbConnection, $stockItemID) {
    $Query = "
    SELECT UnitPackageID
    FROM stockitems
    WHERE StockItemID = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    return $R['UnitPackageID'];
}

function getUnitPrice($dbConnection, $stockItemID) {
    $Query = "
    SELECT UnitPrice
    FROM stockitems
    WHERE StockItemID = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    return $R['UnitPrice'];
}

function getTaxRate($dbConnection, $stockItemID) {
    $Query = "
    SELECT TaxRate
    FROM stockitems
    WHERE StockItemID = ?";

    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $stockItemID);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_assoc($R);
    return $R['TaxRate'];
}

//function getProductInfo($stockItemID, $dbConnection) # TODO: potentieel om te zetten naar een functie die alle productinfo in 1 keer ophaalt.
//{
//    $ProductDescription = getDescription($stockItemID, $dbConnection);
//    $PackageTypeID = getPackageTypeID($stockItemID, $dbConnection);
//    $UnitPrice = getUnitPrice($stockItemID, $dbConnection);
//    $TaxRate = getTaxRate($stockItemID, $dbConnection);
//}
# mijn versie
//function addOrderline($OrderID, $stockItemID, $ProductDescription, $PackageTypeID, $amountOfProductsInOrder, $UnitPrice, $TaxRate, $salesContactPersonID, $currentDate, $dbConnection) {
//    $Query = "
//    INSERT INTO orderlines (OrderID, StockItemID, Description, PackageTypeID, Quantity, UnitPrice, TaxRate, PickedQuantity, LastEditedBy)
//    VALUES (?, ?)";
//    $Statement = mysqli_prepare($dbConnection, $Query);
//    mysqli_stmt_bind_param($Statement, "ii", $OrderID, $stockItemID, $ProductDescription, $PackageTypeID, $amountOfProductsInOrder, $UnitPrice, $TaxRate, $amountOfProductsInOrder, $salesContactPersonID, $currentDate);
//    mysqli_stmt_execute($Statement);
//}
# chatgpt versie
function addOrderline($dbConnection, $OrderID, $stockItemID, $ProductDescription, $PackageTypeID, $amountOfProductsInOrder, $UnitPrice, $TaxRate, $salesContactPersonID, $currentDate) {
    $Query = "
    INSERT INTO orderlines (OrderID, StockItemID, Description, PackageTypeID, Quantity, UnitPrice, TaxRate, PickedQuantity, LastEditedBy, LastEditedWhen)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $Statement = mysqli_prepare($dbConnection, $Query);
    mysqli_stmt_bind_param($Statement, "isiiidddis", $OrderID, $stockItemID, $ProductDescription, $PackageTypeID, $amountOfProductsInOrder, $UnitPrice, $TaxRate, $amountOfProductsInOrder, $salesContactPersonID, $currentDate);
    mysqli_stmt_execute($Statement);
}
