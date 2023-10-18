<?php
function addRowToCookie($id, $name) {
    if(isset($_COOKIE["basket"])) {
//        Hier de code om er nog een toe te voegen
        print("Cookie Basket is set to: " . $_COOKIE["basket"]);
    } else {
        $basket_row = array(array("amount" => 1, "product" => array("StockItemID" => $id, "StockItemName" => $name)));
        setcookie("basket", json_encode($basket_row), 2147483647);
    }
}