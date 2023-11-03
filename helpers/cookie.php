<?php
function addRowToCookie($id, $name, $price, $image) {
    if(isset($_COOKIE["basket"])) {
        $basket_rows=json_decode($_COOKIE["basket"], true);
        if (isset($basket_rows[$id])) {
            $basket_rows[$id]["amount"] = $basket_rows[$id]["amount"] + 1;
        } else {
            $basket_rows[$id] = array("amount" => 1, "product" => array("StockItemID" => $id, "StockItemName" => $name, "StockItemImage" => $image, "StockItemPrice" => $price));
        }
        setcookie("basket", json_encode($basket_rows), 2147483647);
    } else {
        $basket_row = array($id => array("amount" => 1, "product" => array("StockItemID" => $id, "StockItemName" => $name, "StockItemImage" => $image, "StockItemPrice" => $price)));
        setcookie("basket", json_encode($basket_row), 2147483647);

    }
}