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
function getEmailTemplates($databaseConnection)
{
    $query = "
        SELECT ID, content, titel, description
        FROM Emailtemplate
    ";

    $statement = mysqli_prepare($databaseConnection, $query);

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
function updateTemplate($databaseConnection, $ID, $content, $title)
{
    $query = "
        UPDATE emailtemplate
        SET content = ?,
            titel = ?
        WHERE ID = ?
    ";

    $statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param($statement, "ssi", $content, $title, $ID);

    mysqli_stmt_execute($statement);

    // Check for successful update
    if (mysqli_stmt_affected_rows($statement) > 0) {
        return true; // Successful update
    } else {
        return false; // Update failed
    }
}

function insertTemplate($databaseConnection, $titel, $content)
{
    $query = "
        INSERT INTO nerdygadgets.emailtemplate (titel, description, content) VALUES (?, null, ?)
    ";

    $statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param($statement, "ss", $titel, $content);

    mysqli_stmt_execute($statement);

    // Check for successful insertion
    if (mysqli_stmt_affected_rows($statement) > 0) {
        return true; // Successful insertion
    } else {
        return false; // Insertion failed
    }
}
function getNextNonExistingID($databaseConnection) {
    $query = "SELECT MIN(t1.ID + 1) AS nextID
              FROM emailtemplate AS t1
              LEFT JOIN emailtemplate AS t2 ON t1.ID + 1 = t2.ID
              WHERE t2.ID IS NULL";

    $result = mysqli_query($databaseConnection, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return ($row['nextID']) ? $row['nextID'] : 1;
    } else {
        // Error handling or default return value
        return 1; // Return 1 if no existing records found
    }
}
function insertIDTemplate($databaseConnection, $ID)
{
    $query = "
        INSERT INTO nerdygadgets.emailtemplate (ID, titel, description, content) VALUES (?, '', null, '')
    ";

    $statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param($statement, "i", $ID);

    mysqli_stmt_execute($statement);

    // Check for successful insertion
    if (mysqli_stmt_affected_rows($statement) > 0) {
        return true; // Successful insertion
    } else {
        return false; // Insertion failed
    }
}
function isIDExists($databaseConnection, $id)
{
    $query = "SELECT COUNT(*) AS count FROM emailtemplate WHERE ID = ?";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_assoc($result);

    return ($row['count'] > 0);
}

function deleteTemplateByID($databaseConnection, $ID)
{
    $query = "DELETE FROM emailtemplate WHERE ID = ?";

    $statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param($statement, "i", $ID);

    mysqli_stmt_execute($statement);

    // Check of het verwijderen succesvol was
    if (mysqli_stmt_affected_rows($statement) > 0) {
        return true; // Succesvol verwijderd
    } else {
        return false; // Verwijderen mislukt
    }
}
