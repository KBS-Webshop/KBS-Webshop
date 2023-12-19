<?php

require '../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../../../');
$dotenv->load();

function connectToDatabase()
{
    $Connection = null;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $Connection = mysqli_connect($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        mysqli_set_charset($Connection, 'latin1');
        $DatabaseAvailable = true;
    } catch (mysqli_sql_exception $e) {
        $DatabaseAvailable = false;
    }
    if (!$DatabaseAvailable) {
        ?>
        <h2>Website wordt op dit moment onderhouden.</h2>
        <?php
        die();
    }

    return $Connection;
}

function addReview($review, $StockItemID, $personID, $rating, $databaseConnection)
{
    $query = '
    INSERT INTO reviews (review, StockItemID, personID, rating)
    VALUES (?, ?, ?, ?)
    ';

    $Statement = mysqli_prepare($databaseConnection, $query);

    if (!$Statement) {
        echo "Error in preparing statement: " . mysqli_error($databaseConnection) . "\n";
        return;
    }

    mysqli_stmt_bind_param(
        $Statement,
        "siii",
        $review,
        $StockItemID,
        $personID,
        $rating
    );

    if (mysqli_stmt_execute($Statement)) {
        echo "Review added successfully for personID $personID\n";
    } else {
        echo "Error in executing statement: " . mysqli_stmt_error($Statement) . "\n";
    }
}

function generateRandomReviews($startPersonID, $endPersonID, $databaseConnection)
{
    for ($personID = $startPersonID; $personID <= $endPersonID; $personID++) {
        $stockItemID = rand(1, 135);
        $review = generateRandomReview();
        $rating = rand(1, 5);

        addReview($review, $stockItemID, $personID, $rating, $databaseConnection);

        echo "Review added for personID $personID\n";
    }
}

function generateRandomReview()
{
    $randNum = rand(1, 5);

    switch ($randNum) {
        case 1:
            return 'Terrible purchase. Regret buying it.';
        case 2:
            return 'Not satisfied. Poor quality and performance.';
        case 3:
            return 'Average product. Does the job but nothing special.';
        case 4:
            return 'Great purchase! Fantastic features and design.';
        case 5:
            return 'Outstanding product. Exceeded my expectations!';
        default:
            return 'No review available.';
    }
}


$databaseConnection = connectToDatabase();
generateRandomReviews(2001, 2400, $databaseConnection);

?>
