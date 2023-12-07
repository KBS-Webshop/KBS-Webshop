<?php
# alter table specialdeals drop constraint FK_Sales_SpecialDeals_Application_People;

function getNewID($databaseConnection) {
    $query = "SELECT MAX(SpecialDealID) FROM specialdeals";
    $result = mysqli_query($databaseConnection, $query);
    $result = mysqli_fetch_assoc($result);
    return $result["MAX(SpecialDealID)"] + 1;
}

function getCurrentDiscounts($databaseConnection)
{
    $query = "SELECT * FROM specialdeals";
    $result = mysqli_query($databaseConnection, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getDiscount($id, $databaseConnection)
{
    $query = "SELECT * FROM specialdeals WHERE SpecialDealID = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    return mysqli_fetch_assoc($result);
}

function updateDiscountOrEndDate($id, $databaseConnection, $discount=null, $endDate=null)
{
    if (!$discount) {
        $query = "UPDATE specialdeals SET EndDate = ? WHERE SpecialDealID = ?";
        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_bind_param($statement, "si", $endDate, $id);
        mysqli_stmt_execute($statement);
    } else {
        $query = "UPDATE specialdeals SET DiscountPercentage = ? WHERE SpecialDealID = ?";
        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_bind_param($statement, "ii", $discount, $id);
        mysqli_stmt_execute($statement);
    }
}

function deleteDiscount($id, $databaseConnection)
{
    $query = "DELETE FROM specialdeals WHERE SpecialDealID = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
}

function createDiscount($stockItemID, $discount, $startDate, $endDate, $databaseConnection)
{
    $id = getNewID($databaseConnection);

    $query = "INSERT INTO specialdeals (SpecialDealID, StockItemID, CustomerID, BuyingGroupID, CustomerCategoryID, StockGroupID, DealDescription, StartDate, EndDate, DiscountAmount, DiscountPercentage, UnitPrice, LastEditedBy, LastEditedWhen) 
                VALUES (?, ?, NULL, NULL, NULL, NULL, '', ?, ?, NULL, ?, NULL, 3262, NOW())";
    $statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param($statement, "iissi", $id, $stockItemID, $startDate, $endDate, $discount);
    mysqli_stmt_execute($statement);

    return $id;
}
