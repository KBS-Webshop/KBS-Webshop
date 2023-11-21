<?php
# ALS ER GEEN ERRORS ZIJN IS DE ENIGE OUTPUT VAN DIT BESTAND 'Tests passed'.
# ANDERS WORDT AANGEGEVEN WAT DE ERROR(S) IS/ZIJN.

# class AssertExceptionCustom extends Exception {}

error_reporting(E_ALL);
ini_set("display_errors", 1);

# normale assert stopt de code, deze functie niet
function assert_custom($assertion, $message = "") {
    if (!$assertion) {
        echo ($message);
    }
}


# TESTS VOOR cookie.php
# cookie.php moet handmatig getest worden
function cookie_tests() {
    include __DIR__ . "/../helpers/cookie.php";

    # functies in cookie.php
    # - cookieEmpty()
    # - addRowToCookie()
    # - removeRowFromCookie()
    # - changeAmount()
    # - incrementAmount()
    # - decrementAmount()

    # verwijder basket voor de test
    if (isset($_COOKIE["basket"])) {
        unset($_COOKIE["basket"]);
    }

    # Test voor cookieEmpty()
    try {
        assert_custom(cookieEmpty(), "cookieEmpty() in file cookie.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }
    # cookieEmpty() geeft false terug als de cookie niet bestaat


    # Test voor addRowToCookie()
    try {
        addRowToCookie(1);
        $basket = json_decode($_COOKIE["basket"], true);
        assert_custom(isset($basket[1]), "addRowToCookie() in file cookie.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }
    # addRowToCookie() voegt een rij toe aan de cookie met id 1


    # Test voor changeAmount()
    try {
        changeAmount(1, 2);
        $basket = json_decode($_COOKIE["basket"], true);
        assert_custom($basket[1]["amount"] == 2, "changeAmount() in file cookie.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }
    # changeAmount() verandert het aantal van de rij met id 1 naar 2


    # Test voor incrementAmount()
    try {
        incrementAmount(1);
        $basket = json_decode($_COOKIE["basket"], true);
        assert_custom($basket[1]["amount"] == 3, "incrementAmount() in file cookie.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }
    # incrementAmount() verhoogt het aantal van de rij met id 1 met 1 naar 3


    # Test voor decrementAmount()
    try {
        decrementAmount(1);
        $basket = json_decode($_COOKIE["basket"], true);
        assert_custom($basket[1]["amount"] == 2, "decrementAmount() in file cookie.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }
    # decrementAmount() verlaagt het aantal van de rij met id 1 met 1 naar 2


    # Test voor removeRowFromCookie()
    try {
        removeRowFromCookie(1);
        $basket = json_decode($_COOKIE["basket"], true);
        assert_custom(!isset($basket[1]), "removeRowFromCookie() in file cookie.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }
    # removeRowFromCookie() verwijdert de rij met id 1 uit de cookie
}

function database_test() {
    include __DIR__ . "/../helpers/database.php";

    # functies in database.php
    # - connectToDatabase()
    # - getHeaderStockGroups()
    # - getStockGroups()
    # - getStockItem()
    # - getStockItemImage()

    # Test voor connectToDatabase()
    try {
        $Connection = connectToDatabase();
        assert_custom($Connection != null, "connectToDatabase() in file database.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }

    # Test voor getHeaderStockGroups()
    try {
        $HeaderStockGroups = getHeaderStockGroups($Connection);
        assert_custom(!empty($HeaderStockGroups), "getHeaderStockGroups() in file database.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }

    # Test voor getStockGroups()
    try {
        $StockGroups = getStockGroups($Connection);
        assert_custom(!empty($StockGroups), "getStockGroups() in file database.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }

    # Test voor getStockItem()
    try {
        $StockItem = getStockItem(1, $Connection);
        assert_custom($StockItem != null, "getStockItem() in file database.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }

    # Test voor getStockItemImage()
    try {
        $StockItemImage = getStockItemImage(1, $Connection);
        assert_custom(!empty($StockItemImage), "getStockItemImage() in file database.php failed");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }
}

function utils_test() {
    include __DIR__ . "/../helpers/utils.php";

    # functies in utils.php
    # - getVoorraadTekst()
    # - berekenVerkoopPrijs()

    # Test voor getVoorraadTekst()
    try {
        $voorraadTekst = getVoorraadTekst(1001);
        assert_custom($voorraadTekst == "Ruime voorraad beschikbaar.", "getVoorraadTekst() in file utils.php failed; 1001 items");
        assert_custom(getVoorraadTekst(1000) == "Voorraad: 1000", "getVoorraadTekst() in file utils.php failed; 1000 items");
        assert_custom(getVoorraadTekst(999) == "Voorraad: 999", "getVoorraadTekst() in file utils.php failed; 999 items");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }

    # Test voor berekenVerkoopPrijs()
    try {
        $verkoopPrijs = berekenVerkoopPrijs(100, 21);
        assert_custom($verkoopPrijs == 121, "berekenVerkoopPrijs() in file utils.php failed; 21% BTW");

        $verkoopPrijs = berekenVerkoopPrijs(100, 9);
        assert_custom($verkoopPrijs == 109, "berekenVerkoopPrijs() in file utils.php failed; 9% BTW");
    } catch (Throwable $e) {
        echo($e->getMessage());
    }

}

function run_tests() {
    ob_start();
    chdir(__DIR__ . "/../");

    try {
        database_test();
        utils_test();
    } catch(Exception $ignored) {}
    finally {
        if (!ob_get_contents()) {
            echo "Tests passed";
        }
        ob_end_flush();
    }
}

run_tests();
