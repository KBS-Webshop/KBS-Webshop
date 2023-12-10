<?php

function addReview($review, $StockItemID, $personID, $publicationDate, $databaseConnection)
{
    $query = '
    INSERT INTO reviews (review, StockItemID, personID, PublicationDate)
    VALUES (?, ?, ?, ?)
    ';

    $Statement = mysqli_prepare($databaseConnection, $query);

    mysqli_stmt_bind_param(
        $Statement,
        "siii",
        $review,
        $StockItemID,
        $personID,
        $publicationDate
    );


    mysqli_stmt_execute($Statement);

    if (mysqli_stmt_execute($Statement)) {
        echo "Review toegevoegd!";
    } else {
        echo "Fout bij het toevoegen van review: " . mysqli_error($databaseConnection);
    }
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
