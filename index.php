<!-- dit is het bestand dat wordt geladen zodra je naar de website gaat -->
<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";

$productID = 69;

$StockItem = getStockItem($productID, $databaseConnection);
$currentDiscount = getDiscountByStockItemID($productID, $databaseConnection);
$StockItemImage = getStockItemImage($productID, $databaseConnection)[0];

?>
<style>
    /* Sorry voor de CSS hier Renze maar dit is voor PHP integratie */
    .HomePageStockItemPicture {
        background-image: url("<?php echo 'Public/StockItemIMG/' . $StockItemImage['ImagePath'] ?>");
        background-size: 100% 100%;
        width: 477px;
        height: 477px;
        background-repeat: no-repeat;
        margin-left: 55%;
        margin-top: -30%;
    }
</style>

<div class="IndexStyle">
    <div class="col-11">
        <div class="TextPrice">
            <a href="view.php?id=<?php echo $productID ?>">
                <div class="TextMain">
                    <?php echo $StockItem['StockItemName'] ?>
                </div>
                <ul id="ul-class-price">
                    <?php if ($currentDiscount) { ?>
                        <li class="HomePagePrice">
                            <s class="strikedtext-2"><?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']) ?></s>
                            <?php echo calculateDiscountedPriceBTW($StockItem['SellPrice'], $currentDiscount['DiscountPercentage'], $StockItem['TaxRate']) ?>
                        </li>
                    <?php } else { ?>
                        <li class="HomePagePrice"><?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']) ?></li>
                    <?php } ?>
                </ul>
        </div>
        </a>
        <div class="HomePageStockItemPicture"></div>
    </div>
</div>
<?php
include __DIR__ . "/components/footer.php"
?>

