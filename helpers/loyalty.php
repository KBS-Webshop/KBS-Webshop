<?php
function calculateLoyaltyPoints($price, $databaseConnection) {
    $loyaltyConfiguration = getLoyaltyConfiguration($databaseConnection);
    return floor((floatval($price) / $loyaltyConfiguration["price_per_points"])) * $loyaltyConfiguration["points_per_price"];
}

function calculateAndAddPoints($price, $personId, $databaseConnection) {
    $points = calculateLoyaltyPoints($price, $databaseConnection);
    $currentPoints = getPoints($personId, $databaseConnection);
    setPoints(1, ($points + $currentPoints), $databaseConnection);
}

function addDealToCart($id) {
    setcookie("deals", $id, 2147483647);
}

function getDealInCart() {
    if(isset($_COOKIE["deals"])) {
        return $_COOKIE["deals"];
    } else {
        return null;
    }
}

function removeDealFromCart() {
    setcookie("deals", "", 2147483647);   
}

function calculatePriceWithDeals($price, $databaseConnection) {
    $deal = getLoyaltyDeal(getDealInCart(), $databaseConnection);
    return calculateDiscount($price, 100 - $deal["discount"]);
}

function calculateDiscount($price, $discount) {
    return ($price / 100) * $discount;
}