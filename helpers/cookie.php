<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            addRowToCookie($_POST['StockItemID']);
            break;
        case 'remove':
            removeRowFromCookie($_POST['StockItemID']);
            break;
        case 'increment':
            incrementAmount($_POST['StockItemID']);
            break;
        case 'decrement':
            decrementAmount($_POST['StockItemID']);
            break;
        case 'change_amt':
            changeAmount($_POST['StockItemID'], $_POST['amount']);
            break;
        case 'clear_cookie':
            clearCookie();
    }

    $requestPath = $_SERVER['REQUEST_URI'];
    header("location: $requestPath");
    exit();
}

function cookieEmpty() {
    if (isset($_COOKIE["basket"])) {
        $basket = json_decode($_COOKIE["basket"], true);
        if (empty($basket)) {
            return true;
        }
    }
    return false;
}

function addRowToCookie($id)
{
    if (isset($_COOKIE["basket"])) {
        $basket_rows = json_decode($_COOKIE["basket"], true);
        if (isset($basket_rows[$id])) {
            $basket_rows[$id]["amount"] = $basket_rows[$id]["amount"] + 1;
        } else {
            $basket_rows[$id] = array("amount" => 1, "id" => $id);
        }
        setcookie("basket", json_encode($basket_rows), 2147483647);
    } else {
        $basket_row = array($id => array("amount" => 1, "id" => $id));
        setcookie("basket", json_encode($basket_row), 2147483647);
    }
    header("location: winkelmand.php");
    exit();
}

function removeRowFromCookie($id)
{
    if (isset($_COOKIE["basket"])) {
        $basket = json_decode($_COOKIE["basket"], true);

        if (isset($basket[$id])) {
            unset($basket[$id]);
            setcookie("basket", json_encode($basket), 2147483647);
        }
    }
}

function changeAmount($id, $amount)
{
    if (isset($_COOKIE["basket"])) {
        $basket = json_decode($_COOKIE["basket"], true);

        if (isset($basket[$id])) {
            $basket[$id]['amount'] = $amount;
            setcookie("basket", json_encode($basket), 2147483647);
        }
    }
}

function incrementAmount($id)
{
    if (isset($_COOKIE["basket"])) {
        $basket = json_decode($_COOKIE["basket"], true); # voor elk product, als het id in de cookie overeenkomt met de gegeven id, verhoog het aantal met 1
            if (isset($basket[$id])) {
                $basket[$id]['amount'] += 1;
                setcookie("basket", json_encode($basket), 2147483647);
            }
    }
}

function decrementAmount($id)
{
    if (isset($_COOKIE["basket"])) {
        $basket = json_decode($_COOKIE["basket"], true);

        # voor elk product, als het id in de cookie overeenkomt met de gegeven id, verhoog het aantal met 1
        if (isset($basket[$id])) {
            $basket[$id]['amount'] -= 1;

            if ($basket[$id]['amount'] <= 0) {
                unset($basket[$id]);
            }
            setcookie("basket", json_encode($basket), 2147483647);
        }
    }
}

function clearCookie() {
    setcookie("basket", "", time()-3600);
}

?>