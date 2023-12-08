<?php
function getCurrentUser($databaseConnection, $email, $hashedPassword) {
    $query = "SELECT FullName, PhoneNumber, EmailAddress, loyalty_points FROM people WHERE EmailAddress = ? AND HashedPassword = ?";
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
function logoutUser() {
    $_SESSION["user"]["isLoggedIn"] = 0;
    $_SESSION["user"]["FullName"] = "";
    $_SESSION["user"]["PhoneNumber"] = "";
    $_SESSION["user"]["EmailAddress"] = "";
    $_SESSION["user"]["loyalty_points"] = "";
}
function hashPassword($password) {
    $savePassword = password_hash($password, PASSWORD_BCRYPT);
    return $savePassword;
}

function createAccount ($databaseConnection, $name, $hashedPassword, $phoneNumber, $email){
    $query = "INSERT INTO people (FullName, PreferredName, SearchName, IsPermittedToLogon, IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesPerson, PhoneNumber, EmailAddress, LastEditedBy, ValidFrom, ValidTo)
VALUES (?,?,?,1,0,?,1,0,0,?,?,3262,CURRENT_TIMESTAMP,'9999-12-31 23:59:59.9999999')";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_bind_param($statement, "sssbss", $name, $name, $name, $hashedPassword, $phoneNumber, $email);
    mysqli_stmt_execute($statement);
    return TRUE;
}
