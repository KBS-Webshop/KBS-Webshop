<?php

function addReview($review, $StockItemID, $personID, $rating, $databaseConnection)
{
    $query = '
    INSERT INTO reviews (review, StockItemID, personID, rating)
    VALUES (?, ?, ?, ?)
    ';

    $Statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param(
        $Statement,
        "siii",
        $review,
        $StockItemID,
        $personID,
        $rating
    );

    mysqli_stmt_execute($Statement);
}

function getAllReviews($StockItemID, $databaseConnection)
{
    $query = '
    SELECT *
    FROM reviews
    WHERE StockItemID = ?
    ';

    $Statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($Statement, "i", $StockItemID);
    mysqli_stmt_execute($Statement);

    $result = mysqli_stmt_get_result($Statement);
    $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $reviews;
}

function getReviewDates($StockItemID, $databaseConnection)
{
    $query = '
    SELECT publicationDate
    FROM reviews
    WHERE StockItemID = ?
    ';

    $Statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($Statement, "i", $StockItemID);
    mysqli_stmt_execute($Statement);

    $result = mysqli_stmt_get_result($Statement);
    $publicationdates = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $publicationdates;
}

function getReviewPerson($StockItemID, $databaseConnection)
{
    $query = '
    SELECT p.FullName
    FROM people p
    JOIN Reviews r ON p.PersonID = r.PersonID
    WHERE r.StockItemID = ?;
    ';

    $Statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($Statement, "i", $StockItemID);
    mysqli_stmt_execute($Statement);

    $result = mysqli_stmt_get_result($Statement);
    $names = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $names;
}

function getRatings($StockItemID, $databaseConnection)
{
    $query = '
    SELECT rating
    FROM reviews
    WHERE StockItemID = ?
    ';

    $Statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($Statement, "i", $StockItemID);
    mysqli_stmt_execute($Statement);

    $result = mysqli_stmt_get_result($Statement);
    $ratings = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $ratings;
}

function getPersonIDs($StockItemID, $databaseConnection)
{
    $query = '
    SELECT PersonID
    FROM reviews
    WHERE StockItemID = ?
    ';

    $Statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($Statement, "i", $StockItemID);
    mysqli_stmt_execute($Statement);

    $result = mysqli_stmt_get_result($Statement);
    $personIDs = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $personIDs;
}

