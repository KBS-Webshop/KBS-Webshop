<?php
function addTemp($temp, $databaseConnection) {
    $Query = "
    INSERT INTO nerdygadgets.coldroomtemperatures (ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo) 
    VALUES ( 5, ?, ?, ?, '9999-12-31 23:59:59')
    ";
    $Statement = mysqli_prepare($databaseConnection, $Query);

    $currentDate = date("Y-m-d H:i:s");
    mysqli_stmt_bind_param(
        $Statement,
        "sds",
        $currentDate,
        $temp,
        $currentDate
    );

    mysqli_stmt_execute($Statement);
}

function moveTemp($databaseConnection) {
    $QueryMove = "
    INSERT INTO nerdygadgets.coldroomtemperatures_archive (ColdRoomTemperatureID, ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo)
    SELECT ColdRoomTemperatureID, ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo
    FROM nerdygadgets.coldroomtemperatures
    WHERE ColdRoomSensorNumber = 5
    ";

    $Statement = mysqli_prepare($databaseConnection, $QueryMove);
    mysqli_stmt_execute($Statement);

    $QueryDelete = "
    DELETE FROM nerdygadgets.coldroomtemperatures
    WHERE ColdRoomSensorNumber = 5
    ";

    $Statement = mysqli_prepare($databaseConnection, $QueryDelete);
    mysqli_stmt_execute($Statement);
}

function actueleTemperatuur($databaseConnection) {
    $Query = "
    SELECT Temperature
    FROM nerdygadgets.coldroomtemperatures
    WHERE ColdRoomSensorNumber = 5
    ";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    $Temperatuur = number_format($R[0]["Temperature"], 1, ',', '.');

    return $Temperatuur;
}







