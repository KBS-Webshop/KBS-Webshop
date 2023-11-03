<?php
include __DIR__ . "/header.php";
?>
<<<<<<< Updated upstream
<?php
include __DIR__ . "/footer.php";
?>
=======

<!DOCTYPE html>
<html>
<head>
    <title>Winkelmandje</title>
</head>
<body>


<h2>Winkelmandje</h2>
<ul>
    <div id="ResultsArea" class="Browse">

    <?php
    if (isset($_COOKIE["basket"])) {
        $basket_contents = json_decode($_COOKIE["basket"], true);
        print_r($basket_contents);
        foreach ($basket_contents as $item) {
            print "ID " . $item["product"]["StockItemID"] . "<br>";
            print "Name " . $item["product"]["StockItemName"] . "<br>";
            print "Image " . $item["product"]["StockItemImage"] . "<br>";
            print "Price " . $item["product"]["StockItemPrice"] . "<br>";
            print "Amount " . $item["amount"] . "<br>";

            ?>
            <a class="ListItem" href='view.php?id=<?php print $item['product']['StockItemID']; ?>'>
                <?php
                print($item["product"]["StockItemName"])
                ?>
            </a>
        <?php
        }
    } else {
        echo "Winkelmandje is leeg.";
    }
    ?>
    </div>
</ul>
</body>
</html>

>>>>>>> Stashed changes
