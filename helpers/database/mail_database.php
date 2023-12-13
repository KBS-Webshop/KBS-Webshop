<?php
function getUserInfo($databaseConnection, $customerID) {

    $Query = "
        SELECT CustomerID, CustomerName, PhoneNumber, DeliveryAddressLine1, DeliveryPostalCode
        FROM customers
        WHERE CustomerID = ?
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param($Statement, "i", $customerID);

    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}


function getorder($databaseConnection, $OrderID)
{

    $Query = "
            select  ol.OrderID,s.StockItemName,ol.Quantity, ol.StockItemID
from orderlines ol
join stockitems s on ol.StockItemID=s.StockItemID
where OrderID= ?
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param($Statement, "i", $OrderID);

    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}
function getEmail($databaseConnection, $personID)
{

    $Query = "
            select  EmailAddress
from people
where personID= ?
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param($Statement, "i", $personID);

    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}
function getCustomerID($databaseConnection, $orderID)
{
    $Query = "
        SELECT CustomerID
        FROM orders
        WHERE OrderID = ?
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param($Statement, "i", $orderID);

    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $customerData = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $customerData;
}
function getEmailTemplate($databaseConnection, $ID)
{
    $query = "
        SELECT ID, content, titel, description
        FROM Emailtemplate
        WHERE ID= ?
    ";

    $statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param($statement, "i", $ID);

    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $templateData = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $templateData;
}

function insertContent($databaseConnection, $content)
{
    $query = "
        INSERT INTO editor (content, created) VALUES (?, NOW())
    ";

    $statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param($statement, "s", $content);

    mysqli_stmt_execute($statement);

    // Check for successful insertion (if needed)
    if(mysqli_stmt_affected_rows($statement) > 0) {
        return true; // Successful insertion
    } else {
        return false; // Insertion failed
    }
}
