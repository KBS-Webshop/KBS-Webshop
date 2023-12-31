<?php

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

//    SELECT SI.StockItemID,
//            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice,

    $Query = " 
           SELECT StockItemID, IsChillerStock, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            RecommendedRetailPrice SellPrice, 
            TaxRate,
            StockItemName,
            QuantityOnHand,
            SearchDetails, 
            SendCosts, 
            MarketingComments, 
            CustomFields, Video, 
            UnitPackageID, 
            UnitPrice, 
            TaxRate,
            BackupImagePath   
            FROM stock_view
            WHERE stockitemid = ?
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

function getAlsoBought($id, $databaseConnection) {
    # SELECT o.StockItemID, s.StockItemName, si.ImagePath StockItemImage, (RecommendedRetailPrice*(1+(s.TaxRate/100))) AS SellPrice, COUNT(*) kerenSamengekocht
    $Query = "
        SELECT o.StockItemID, s.StockItemName, si.ImagePath StockItemImage, RecommendedRetailPrice SellPrice, s.TaxRate, COUNT(*) kerenSamengekocht
        FROM orderlines o
        JOIN stockitems s ON o.StockItemID = s.StockItemID
        JOIN stockitemimages si ON si.StockItemID = o.StockItemID
        JOIN stockitemholdings sh ON o.StockItemID = sh.StockItemID
        WHERE OrderID IN (
            SELECT OrderID
            FROM orderlines
            WHERE StockItemID = ?
        ) AND o.StockItemID != ?
        GROUP BY o.StockItemID, s.StockItemName, si.ImagePath, SellPrice
        ORDER BY kerenSamengekocht DESC
        LIMIT 6;
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param($Statement, "ii", $id, $id);

    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}

function removeExpiredDiscounts($databaseConnection)
{
    $query = "DELETE FROM specialdeals WHERE EndDate < NOW()";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
}


function getDiscountByStockItemID($id, $databaseConnection)
{
    removeExpiredDiscounts($databaseConnection);
    $query = "SELECT * FROM specialdeals WHERE StockItemID = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    return mysqli_fetch_assoc($result);
}

function getAmountOrderedLast72Hours($id, $databaseConnection)
{
    $query = "SELECT SUM(Quantity) FROM orderlines WHERE StockItemID = ? AND (DATEDIFF(NOW(), LastEditedWhen) < 3)";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $result = mysqli_fetch_assoc($result);
    return $result["SUM(Quantity)"];
}