<?php
function getCurrentUser($databaseConnection, $email, $hashedPassword) {
    $query = "SELECT FullName, PhoneNumber, EmailAddress, loyalty_points, IsSalesPerson FROM people WHERE EmailAddress = ? AND HashedPassword = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "ss", $email, $hashedPassword);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $user = mysqli_fetch_assoc($result);
    if ($user != null) {
    foreach ($user as $key => $value) {
        $_SESSION["user"][$key] = $value;
    }
    $_SESSION["user"]["isLoggedIn"] = 1;
    return TRUE;
    } else {
        $_SESSION["user"]["isLoggedIn"] = 0;
        return FALSE;
    }
}
 function getCurrentUserID($databaseConnection, $email, $hashedPassword)
 {
     $query = "SELECT PersonID FROM people WHERE EmailAddress = ? AND HashedPassword = ?";
     $statement = mysqli_prepare($databaseConnection, $query);
     mysqli_stmt_bind_param($statement, "ss", $email, $hashedPassword);
     mysqli_stmt_execute($statement);
     $result = mysqli_stmt_get_result($statement);
     $user = mysqli_fetch_assoc($result);
     if ($user != null) {
         $_SESSION["user"]["PersonID"] = $user["PersonID"];
         return $user["PersonID"];
     }
 }
function logoutUser() {
    $_SESSION["user"]["isLoggedIn"] = 0;
    $_SESSION["user"]["FullName"] = "";
    $_SESSION["user"]["PhoneNumber"] = "";
    $_SESSION["user"]["EmailAddress"] = "";
    $_SESSION["user"]["loyalty_points"] = "";
    $_SESSION["user"]["PersonID"] = "";
    $_SESSION["user"]["customer"]["CustomerID"] = "";
    $_SESSION["user"]["customer"]["CustomerName"] = "";
    $_SESSION["user"]["customer"]["DeliveryCityID"] = "";
    $_SESSION["user"]["customer"]["PostalCityID"] = "";
    $_SESSION["user"]["customer"]["PhoneNumber"] = "";
    $_SESSION["user"]["customer"]["DeliveryAddressLine1"] = "";
    $_SESSION["user"]["customer"]["DeliveryPostalCode"] = "";
    $_SESSION["user"]["customer"]["PostalAddressLine1"] = "";
    $_SESSION["user"]["customer"]["PostalPostalCode"] = "";
    $_SESSION["user"]["customer"]["PersonID"] = "";
}

function hashPassword($password) {
    $fixedSalt = 'kbs-webshop-salt';
    $hashedPassword1 = hash('sha256', $password . $fixedSalt);
    return $hashedPassword1;
}

function createAccount ($databaseConnection, $name, $hashedPassword, $phoneNumber, $email){
    $query = "INSERT INTO people (FullName, PreferredName, SearchName, IsPermittedToLogon, IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson, PhoneNumber, EmailAddress, LastEditedBy, ValidFrom, ValidTo, loyalty_points)
VALUES (?,?,?,1,0,?,1,0,0,?,?,3262,CURRENT_TIMESTAMP,'9999-12-31 23:59:59.9999999', 0)";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "ssssss", $name, $name, $name, $hashedPassword, $phoneNumber, $email);
    mysqli_stmt_execute($statement);
    return TRUE;
}
function updateAccount ($databaseConnection, $name, $phoneNumber, $email, $personID)
{
    $query = "UPDATE people 
              SET FullName = ?, PreferredName = ?, SearchName = ?, PhoneNumber = ?, EmailAddress = ?
              WHERE PersonID = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "sssssi", $name, $name, $name, $phoneNumber, $email, $personID);
    mysqli_stmt_execute($statement);
    return TRUE;
}

function getcityID ($databaseConnection, $cityName)
{
    $query = "SELECT CityID FROM cities WHERE CityName = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "s", $cityName);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $city = mysqli_fetch_assoc($result);
    if ($city != null) {
        return $city["CityID"];
    } else {
        return FALSE;
    }
}
function getUserCustomerInfo($databaseConnection, $email, $hashedPassword)
{
    $personID = getCurrentUserID($databaseConnection, $email, $hashedPassword);
    $query = "SELECT CustomerID, CustomerName, DeliveryCityID, PostalCityID, PhoneNumber, DeliveryAddressLine1, DeliveryPostalCode, PostalAddressLine1, PostalPostalCode
              FROM customers
              WHERE PersonID = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "i", $personID);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $user = mysqli_fetch_assoc($result);
    if ($user != null) {
        foreach ($user as $key => $value) {
            $_SESSION["user"]["customer"][$key] = $value;
        }
        $_SESSION["user"]["customer"]["cityName"] = getCity($databaseConnection, $_SESSION["user"]["customer"]["PostalCityID"]);
        return TRUE;
    } else {
        return FALSE;
    }
}
function getNewAccountID($databaseConnection)
{
    $query = "SELECT max(PersonID) FROM people";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $user = mysqli_fetch_assoc($result);
    if ($user != null) {
        $user = intval($user['max(PersonID)'], 10);
        $user = $user + 1;
        return $user;
    } else {
        return FALSE;
    }
}

function updateCustomer ($databaseConnection, $customerID, $customerName, $cityName, $phoneNumber, $deliveryAddressLine1, $deliveryPostalCode, $postalAddressLine1, $postalPostalCode)
{
    if (getCityID($databaseConnection, $cityName) == FALSE) {
        $newCityID = getNewCityID($databaseConnection);
        $stateProvinceID = 1;
        $salesContactPersonID = 3262;
        $currentDate = date("Y-m-d");
        $validTo = "9999-12-31 23:59:59.9999999";
        addCity($databaseConnection, $newCityID, $cityName, $stateProvinceID, $salesContactPersonID, $currentDate, $validTo);
    }
    $deliveryCityID = getCityID($databaseConnection, $cityName);
    $postalCityID = getCityID($databaseConnection, $cityName);
    $query = "UPDATE customers 
              SET CustomerName = ?, DeliveryCityID = ?, PostalCityID = ?, PhoneNumber = ?, DeliveryAddressLine1 = ?, DeliveryPostalCode = ?, PostalAddressLine1 = ?, PostalPostalCode = ?
              WHERE CustomerID = ?";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "siisssssi", $customerName, $deliveryCityID, $postalCityID, $phoneNumber, $deliveryAddressLine1, $deliveryPostalCode, $postalAddressLine1, $postalPostalCode, $customerID);
    mysqli_stmt_execute($statement);
    return TRUE;
}