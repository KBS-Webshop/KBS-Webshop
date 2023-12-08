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
function addUser($email, $password, $databaseConnection) {
    $password = "blablabla";
    $hashPass = hash_hmac("sha256", $password, $_ENV["PASSWORD_SECRET"]);
    $savePassword = password_hash($hashPass, PASSWORD_BCRYPT);
    print $savePassword;
}
