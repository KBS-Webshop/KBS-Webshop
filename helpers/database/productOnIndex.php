<?php

function hasHighlightedProduct($databaseConnection) {
    $query = "SELECT StockItemID FROM stockitems WHERE IsHighlighted = 1";
    $result = mysqli_query($databaseConnection, $query);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return count($result) > 0;
}

function pickHighlightedProduct($databaseConnection) {
    $query = "SELECT StockItemID FROM stockitems WHERE IsHighlighted = 1";
    $result = mysqli_query($databaseConnection, $query);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $result = $result[array_rand($result)];
    return $result['StockItemID'];
}

function removeSpotlight($id, $databaseConnection) {
    $query = "UPDATE stockitems SET IsHighlighted = 0 WHERE StockItemID = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
}

function getSpotlight($databaseConnection) {
    $query = "SELECT StockItemID, StockItemName FROM stockitems WHERE IsHighlighted = 1";
    $result = mysqli_query($databaseConnection, $query);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result;
}

function setSpotlight($id, $databaseConnection) {
    $query = "UPDATE stockitems SET IsHighlighted = 1 WHERE StockItemID = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
}
