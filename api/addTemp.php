<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '../../');
$dotenv->load();

include "../helpers/database/database.php";
include "../helpers/database/temprature.php";

$temp = 15.3;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $databaseConnection = connectToDatabase();
    moveTemp($databaseConnection);
    addTemp($temp, $databaseConnection);
    actueleTemperatuur($databaseConnection);
} else {
    http_response_code(405);
    echo "Alleen POST-verzoeken zijn toegestaan.";
}
