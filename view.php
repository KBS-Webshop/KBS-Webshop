<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/components/header.php";

$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);
$AlsoBought = getAlsoBought($_GET['id'], $databaseConnection);


?>
<div id="CenteredContent">
    <?php
    if ($StockItem != null) {
        ?>
        <?php
        if (isset($StockItem['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $StockItem['Video']; ?>
            </div>
        <?php }
        ?>


        <div id="ArticleHeader">
            <?php
            if (isset($StockItemImage)) {
                // één plaatje laten zien
                if (count($StockItemImage) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockItemIMG/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                    <?php
                } else if (count($StockItemImage) >= 2) { ?>
                    <!-- meerdere plaatjes laten zien -->
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <li data-target="#ImageCarousel"
                                        data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                    <?php
                                } ?>
                            </ul>

                            <!-- slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="Public/StockItemIMG/<?php print $StockItemImage[$i]['ImagePath'] ?>">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- knoppen 'vorige' en 'volgende' -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="ImageFrame"
                     style="background-image: url('Public/StockGroupIMG/<?php print $StockItem['BackupImagePath']; ?>'); background-size: cover;"></div>
                <?php
            }
            ?>


            <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $StockItem['StockItemName']; ?>
            </h2>
                <div class="temperature-box">
                    <?php if ($StockItem["IsChillerStock"]) {
                        print "Temperatuur in het magazijn: " . actueleTemperatuur($databaseConnection) . " °C";
                    } ?>
                </div>
            <div class="QuantityText"><?php print $StockItem['QuantityOnHand']; ?></div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $StockItem['SellPrice']); ?></b></p>
                        <h6> Inclusief BTW </h6>
                        <form method="post">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="StockItemID" value="<?php echo $StockItem["StockItemID"]; ?>">
                            <button class="add-to-cart-button" type="submit" name="addToCart" value="addItemToCart">
                                <i class="fa fa-cart-plus fa-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="ProductInformationWrapper">
            <div class="ProductInformation">
                <div id="StockItemDescription">
                    <h3>Artikel beschrijving</h3>
                    <p><?php print $StockItem['SearchDetails']; ?></p>
                </div>
                <div id="StockItemSpecifications">
                    <h3>Artikel specificaties</h3>
                    <?php
                    $CustomFields = json_decode($StockItem['CustomFields'], true);
                    if (is_array($CustomFields)) { ?>
                        <table>
                        <thead>
                        <th>Naam</th>
                        <th>Data</th>
                        </thead>
                        <?php
                        foreach ($CustomFields as $SpecName => $SpecText) { ?>
                            <tr>
                                <td>
                                    <?php print $SpecName; ?>
                                </td>
                                <td>
                                    <?php
                                    if (is_array($SpecText)) {
                                        foreach ($SpecText as $SubText) {
                                            print $SubText . " ";
                                        }
                                    } else {
                                        print $SpecText;
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </table><?php
                    } else { ?>
                        <p>
                            <?php print $StockItem['CustomFields']; ?>.
                        </p>
                        <?php
                    }
                    ?>

                </div>
            </div>

            <?php 
                if(count($AlsoBought) != 0) {
            ?>
            <div class="ProductAlsoBoughtWrapper">
                <h3>Vaak samen gekocht</h3>
                <div class="ProductsAlsoBoughtGrid">
                    <?php
                        foreach ($AlsoBought as $product) {
                            ?>
                            <a class="ListItem" href='view.php?id=<?php echo $product['StockItemID']; ?>'>
                                <?php
                                    if (isset($product["StockItemImage"])) { ?>
                                        <div class="ImgFrame"
                                            style="background-image: url('<?php print "Public/StockItemIMG/" . $product["StockItemImage"]; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                                <?php } ?>
                                <h5><?php echo $product["StockItemName"] ?></h5>
                                <?php echo sprintf("€ %.2f", $product['SellPrice']) ?></p>
                                <form method="post">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="StockItemID" value="<?php echo $product['StockItemID']; ?>">
                                    <button class="add-to-cart-button" type="submit" name="addToCart" value="addItemToCart">
                                        <i class="fa fa-cart-plus fa-lg"></i>
                                    </button>
                                </form>
                            </a>
                            <?php
                        }
                    ?>
                </div>
            </div>
            <?php 
                }
            ?>
        </div>
        <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>
</div>
