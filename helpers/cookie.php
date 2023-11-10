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
    }

    $requestPath = $_SERVER['REQUEST_URI'];
    header("location: $requestPath");
    exit();
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
        $basket = json_decode($_COOKIE["basket"], true);

        # voor elk product, als het id in de cookie overeenkomt met de gegeven id, verhoog het aantal met 1
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
            setcookie("basket", json_encode($basket), 2147483647);
        }
    }
}

?>


<!-- ALLES ONDER DEZE REGEL IS VOOR HET TESTEN VAN DE BOVENSTAANDE FUNCTIES -->

<!--<div>-->
<!--    <form method="post">-->
<!--        <p>Dummy 1</p>-->
<!--        <input type="hidden" name="StockItemID" value="1">-->
<!--        <input type="hidden" name="StockItemName" value="Dummy 1">-->
<!--        <input type="hidden" name="action" value="add">-->
<!--        <label>Amount:-->
<!--            <input type="number" value="1" name="amount" min="1" max="100">-->
<!--        </label>-->
<!--        <input type="submit" value="Add to basket">-->
<!--    </form>-->
<!---->
<!--    <form method="post">-->
<!--        <p>Dummy 2</p>-->
<!--        <input type="hidden" name="StockItemID" value="2">-->
<!--        <input type="hidden" name="StockItemName" value="Dummy 2">-->
<!--        <input type="hidden" name="action" value="add">-->
<!--        <label>Amount:-->
<!--            <input type="number" value="1" name="amount" min="1" max="100">-->
<!--        </label>-->
<!--        <input type="submit" value="Add to basket">-->
<!--    </form>-->
<!--</div>-->
<!---->
<!--<p>-->
<?php //print("Cookie Basket is set to: " . $_COOKIE["basket"]); ?>
<!--</p>-->
<!---->
<!--<div>-->
<!--    -->
<?php
//
//    $basket = json_decode($_COOKIE["basket"], true);
//
//    foreach ($basket as $key => $value) {
//        print("<p>" . $value['product']['StockItemName'] . "</p>");
//        print("<form method='post'>");
//        print("<input type='hidden' name='StockItemID' value='" . $value['product']['StockItemID'] . "'>");
//        print("<input type='hidden' name='action' value='remove'>");
//        print("<input type='submit' value='Remove from basket'>");
//        print("</form>");
//    }
//    ?>
<!--</div>-->