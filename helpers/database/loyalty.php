<?php
function getAllLoyaltyDeals($databaseConnection) {
    
    $Query = "
        SELECT * FROM loyalty_deals
        ORDER BY points
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}

function getLoyaltyDeal($id, $databaseConnection) {
    if ($id != null) {
        $Query = "
            SELECT * FROM loyalty_deals
            WHERE id = ?
        ";

        $Statement = mysqli_prepare($databaseConnection, $Query);

        mysqli_stmt_bind_param($Statement, "i", $id);

        mysqli_stmt_execute($Statement);
        $R = mysqli_stmt_get_result($Statement);
        $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

        return $R[0];
    }
}

function updateLoyaltyConfiguration($points, $price, $databaseConnection) {
    $Query = "
        UPDATE loyalty_configuration
        SET price_per_points = ?, points_per_price = ?
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param(
        $Statement, 
        "ii",
        $price,
        $points,
    );
    mysqli_stmt_execute($Statement);
}

function getLoyaltyConfiguration($databaseConnection) {
    
    $Query = "
        SELECT * FROM loyalty_configuration
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R[0];
}

function updateLoyaltyDeal($id, $values, $databaseConnection) {
    if (!isSet($values["free_shipping"])) {
        $values["free_shipping"] = 0;
    }
    
    $Query = "
        UPDATE loyalty_deals
        SET title = ?, description = ?, points = ?, discount = ?, free_shipping = ?
        WHERE id = ?
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param(
        $Statement, 
        "ssiiii",
        $values["title"],
        $values["description"],
        $values["points"],
        $values["discount"],
        $values["free_shipping"],
        $id
    );
    mysqli_stmt_execute($Statement);

}

function createLoyaltyDeal($values, $databaseConnection) {
    if (!isSet($values["free_shipping"])) {
        $values["free_shipping"] = 0;
    }
    
    $Query = "
        INSERT INTO loyalty_deals (title, description, points, discount, free_shipping)
        VALUES (?, ?, ?, ?, ?)
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param(
        $Statement, 
        "ssiii",
        $values["title"],
        $values["description"],
        $values["points"],
        $values["discount"],
        $values["free_shipping"],
    );
    mysqli_stmt_execute($Statement);
}

function setPoints($personId, $points, $databaseConnection) {
    $Query = "
        UPDATE people
        SET loyalty_points = ?
        WHERE PersonID = ?
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);

    mysqli_stmt_bind_param(
        $Statement, 
        "ii",
        $points,
        $personId
    );
    mysqli_stmt_execute($Statement);
}

function getPoints($personId, $databaseConnection) {
    $Query = "
        SELECT loyalty_points
        FROM people
        WHERE PersonID = ?
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param(
        $Statement, 
        "i",
        $personId,
    );
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);
    return $R[0]["loyalty_points"];
}

?>

