<!-- dit is het bestand dat wordt geladen zodra je naar de website gaat -->
<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
include __DIR__ . "/helpers/database/discount.php";
include __DIR__ . "/helpers/database/productOnIndex.php";

if (hasHighlightedProduct($databaseConnection)) {
    $productID = pickHighlightedProduct($databaseConnection);
} else {
    $currentDiscounts = getCurrentDiscounts($databaseConnection);

    if (!$currentDiscounts) {
        $productID = pickRandomProduct($databaseConnection);
    } else {
        $highestDiscount = 0;
        $highestDiscountID = 0;

        foreach ($currentDiscounts as $discount) {
            if ($discount['DiscountPercentage'] > $highestDiscount) {
                $highestDiscount = $discount['DiscountPercentage'];
                $highestDiscountID = $discount['StockItemID'];
            }
        }

        $productID = $highestDiscountID;
    }
}

$StockItem = getStockItem($productID, $databaseConnection);
$currentDiscount = getDiscountByStockItemID($productID, $databaseConnection);
$StockItemImage = getStockItemImage($productID, $databaseConnection)[0];
$amtSold72Hours = getAmountOrderedLast72Hours($productID, $databaseConnection);

?>
<style>
    /* Sorry voor de CSS hier Renze maar dit is voor PHP integratie */
    .HomePageStockItemPicture {
        background-image: url("<?php echo 'Public/StockItemIMG/' . $StockItemImage['ImagePath'] ?>");
        object-fit: contain;
        background-size: contain;
        width: 477px;
        height: 477px;
        max-height: 477px;
        background-repeat: no-repeat;
        margin-left: 55%;
        margin-top: -30%;
    }
    .Background {
        height: auto !important;
    }
</style>

<div class="IndexStyle" <?php if ($currentDiscount) echo 'style="margin-top: -120px;"'?>>
    <div class="col-11">
        <div class="TextPrice">
            <a href="view.php?id=<?php echo $productID ?>">
                <div class="TextMain">
                    <?php echo $StockItem['StockItemName'] ?>
                </div>
                <ul id="ul-class-price">
                    <?php if ($currentDiscount) { ?>
                        <h1 class="HomePageClock" id="clock"></h1>
                        <h1 class="HomePageDiscount">-<?php echo intval($currentDiscount['DiscountPercentage'], 10) ?>%</h1>
                        <li class="HomePagePrice">
                            <s class="strikedtext-2"><?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']) ?></s>
                            <?php echo calculateDiscountedPriceBTW($StockItem['SellPrice'], $currentDiscount['DiscountPercentage'], $StockItem['TaxRate']) ?>
                        </li>
                    <?php } else { ?>
                        <li class="HomePagePrice"><?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']) ?></li>
                    <?php } ?>
                    <?php if ($amtSold72Hours > 5) { ?>
                        <p class="HomePageSold">OP = OP: Dit product is afgelopen 72 uur <?php echo $amtSold72Hours ?> keer verkocht.</p>
                    <?php } ?>
                    <button class="add-to-cart-button" type="submit" name="addToCart" value="addItemToCart">
                        <i class="fa fa-cart-plus fa-lg" style="color:red;"></i>
                    </button>
                </ul>
        </div>
        <div class="HomePageStockItemPicture"></div>

        <?php

        if ($currentDiscount) {
            ?>
            <script>
                clockCountdown("clock", "<?php echo $currentDiscount['EndDate'] ?>", true);
            </script>
            <?php
        }

        ?>
    </div>
</div>
<?php
include __DIR__ . "/components/footer.php"
?>

