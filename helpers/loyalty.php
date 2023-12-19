<?php
function calculateLoyaltyPoints($price, $databaseConnection) {
    $loyaltyConfiguration = getLoyaltyConfiguration($databaseConnection);
    return floor((floatval($price) / $loyaltyConfiguration["price_per_points"])) * $loyaltyConfiguration["points_per_price"];
}

function calculateAndAddPoints($price, $personId, $databaseConnection) {
    $points = calculateLoyaltyPoints($price, $databaseConnection);
    $currentPoints = getPoints($personId, $databaseConnection);
    setPoints($personId, ($points + $currentPoints), $databaseConnection);
}

function calculateAndRemovePoints($price, $personId, $databaseConnection) {
    $deal = getLoyaltyDeal(getDealInCart(), $databaseConnection);
    if ($deal == null) {
        $points = 0;
    } else {
        $points = $deal["points"];
    }
    $currentPoints = getPoints($personId, $databaseConnection);
    setPoints($personId, ($currentPoints - $points), $databaseConnection);
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
    if ($deal != null) {
        return calculateDiscount($price, 100 - $deal["discount"]);
    } else {
        return $price;
    }
}

function calculateDiscount($price, $discount) {
    return ($price / 100) * $discount;
}