<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/database/reviewsDB.php";
include __DIR__ . "/Applications/XAMPP/xamppfiles/htdocs/nerdygit/helpers/database/database.php";

$conn = connectToDatabase();

for ($i = 0; $i < 50; $i++) {
    $personID = rand(2, 43);
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

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully.<br>";
    } else {
        echo "Error inserting record: " . $conn->error . "<br>";
    }
}

$conn->close();
