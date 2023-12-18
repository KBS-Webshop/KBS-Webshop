<?php

require '../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../../../');
$dotenv->load();

function connectToDatabase()
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $connection = mysqli_connect($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"]);
        mysqli_set_charset($connection, 'latin1');
        return $connection;
    } catch (mysqli_sql_exception $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

$conn = connectToDatabase();

for ($i = 2000; $i <= 2400; $i++) {
    $personID = $i;
    $stockItemID = rand(1, 135);
    $review = '';
    $randNum = rand(1, 5);

    switch ($randNum) {
        case 1:
            $review = 'Terrible purchase. Regret buying it.';
            break;
        case 2:
            $review = 'Not satisfied. Poor quality and performance.';
            break;
        case 3:
            $review = 'Average product. Does the job but nothing special.';
            break;
        case 4:
            $review = 'Great purchase! Fantastic features and design.';
            break;
        case 5:
            $review = 'Outstanding product. Exceeded my expectations!';
            break;
    }

    $publicationDate = date('Y-m-d', strtotime('-' . rand(1, 365) . ' days'));
    $rating = rand(1, 5);

    $sql = "INSERT INTO nerdygadgets.Reviews (PersonID, StockItemID, review, publicationDate, rating)
            VALUES ('$personID', '$stockItemID', '$review', '$publicationDate', '$rating')";

    // Execute the query
    mysqli_query($conn, $sql);
}

$conn->close();
?>

