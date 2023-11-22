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

//function addCity ($cityName, $state, $country){
//    $Query = "
//    INSERT INTO cities (CityName, State, Country);
//    VALUES (?, ?, ?)";
//    $Statement = mysqli_prepare($databaseConnection, $Query);
//    mysqli_stmt_bind_param($Statement, "ii", $cityName, $state, $country);
//    mysqli_stmt_execute($Statement);
//    $R = mysqli_stmt_get_result($Statement);
//    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
//    return $R;
//}


function getCustomer($databaseConnection) {
    $Query = "
    SELECT CustomerID
    FROM customers
    WHERE CustomerName = ? AND PhoneNumber = ? AND DeliveryAddressLine2 = ? AND DeliveryPostalCode = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "ssss", $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
    return $R;
}

function addCustomer ($Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode, $databaseConnection) {
    $Query = "
    INSERT INTO customers (CustomerName, PhoneNumber, DeliveryAddressLine2, DeliveryPostalCode)
    VALUES (?, ?, ?, ?)";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "ssss", $Cname, $phoneNumber, $DeliveryAddress, $DeliveryPostalCode);
    mysqli_stmt_execute($Statement);
}
function getOrderID ($databaseConnection) {
    $Query = "
    SELECT max(OrderID)
    FROM orders";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
    return $R;
}
function addOrder ($CustomerId, $DeliveryInstructions, $databaseConnection) {
    $Query = "
    INSERT INTO orders (CustomerID, OrderDate) 
    VALUES (?, ?)";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "is", $CustomerId, $DeliveryInstructions);
    mysqli_stmt_execute($Statement);
}

function addOrderline($OrderID, $StockItemID, $databaseConnection) {
    $Query = "
    INSERT INTO orderlines (OrderID, StockItemID);
    VALUES (?, ?)";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "ii", $OrderID, $StockItemID);
    mysqli_stmt_execute($Statement);
}
