<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../');
$dotenv->load();

include "../helpers/database/database.php";
include "../helpers/database/temprature.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temp = $_POST['waarde'];
    $databaseConnection = connectToDatabase();
    moveTemp($databaseConnection);
    addTemp($temp, $databaseConnection);
    actueleTemperatuur($databaseConnection);
} else {
    http_response_code(405);
    echo "Alleen POST-verzoeken zijn toegestaan.";
}
