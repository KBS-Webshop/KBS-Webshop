<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
include __DIR__ . "/helpers/database/reviewsDB.php";


$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);
$AlsoBought = getAlsoBought($_GET['id'], $databaseConnection);
$currentDiscount = getDiscountByStockItemID($_GET['id'], $databaseConnection);
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
            <?php
            $amtSoldLast72Hrs = getAmountOrderedLast72Hours($StockItem['StockItemID'], $databaseConnection);
            if ($amtSoldLast72Hrs >= 5) { ?>
                <p><b>ERG GEWILD: dit product is afgelopen 72 uur <?php echo $amtSoldLast72Hrs ?> keer verkocht.</b></p>
            <?php } ?>
            <div class="QuantityText"><?php print $StockItem['QuantityOnHand']; ?></div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <?php if ($currentDiscount) { ?><h1><b><?php echo intval(-$currentDiscount['DiscountPercentage'], 10) ?>%</b></h1>
                            <h4 id="clock" style="font-weight: bold;"></h4>
                            <h2 class="StockItemPriceText">
                                    <s class="strikedtext">
                                        <?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']) ?>
                                    </s>
                                    <?php echo calculateDiscountedPriceBTW($StockItem['SellPrice'], $currentDiscount['DiscountPercentage'], $StockItem['TaxRate']); ?>
                            </h2>
                        <?php } else { ?>
                            <h2 class="StockItemPriceText"><?php echo calculatePriceBTW($StockItem['SellPrice'], $StockItem['TaxRate']); ?></h2>
                        <?php } ?>
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
                <?php
                if (isset($_SESSION['user']))
                {
                    $personID =  $_SESSION['user']['PersonID']; // hier moet een check komen op wie ingelogd is✅//
                    $existingPersonIDs = array_column(getPersonIDs($StockItem['StockItemID'], $databaseConnection), 'PersonID');
                    $gekochteItemsPersoon = didUserBuy($personID, $databaseConnection);
                    if (!in_array($personID, $existingPersonIDs)
                        && $_SESSION['user']['isLoggedIn'] == 1
                        && in_array($StockItem['StockItemID'], $gekochteItemsPersoon))
                    { //&& de persoon het product heeft gekocht✅
                        ?>
                        <div id="StockItemSpecifications">
                            <form method="post">
                                <input type="text" name="review" placeholder="Typ hier uw review! (max 800 leestekens)" maxlength="800" required>
                                <div class="review-knoppen">
                                    <?php
                                    for($i = 1; $i <= 5; $i++){ ?>
                                        <label><?php echo $i ?></label>
                                        <input type="radio" name="rating" value="<?php echo $i ?>" <?php echo ($i === 1) ? 'required' : ''; ?>>
                                    <?php } ?>
                                </div>
                                <input type="submit" value="Review toevoegen" name="ReviewToevoegen">
                            </form>
                        </div>
                        <?php
                    }
                    else
                    {
                        $existingReview = getReviewByPerson($personID, $StockItem['StockItemID'], $databaseConnection);
                        if ($existingReview)
                        {
                            ?>
                            <div id="StockItemSpecifications">
                                <form method="post">
                                    <input type="text" name="aangepasteReview"
                                           value="<?php echo $existingReview['review']; ?>"
                                           placeholder="Type hier uw aangepaste review! (max 800 leestekens)"
                                           maxlength="800" required>
                                    <div class="review-knoppen">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) { ?>
                                            <label><?php echo $i ?></label>
                                            <input type="radio" name="aangepasteRating"
                                                   value="<?php echo $i ?>" <?php echo ($i == $existingReview['rating']) ? 'checked' : ''; ?>>
                                        <?php } ?>
                                    </div>
                                    <input type="submit" value="Review aanpassen" name="ReviewAanpassen">
                                </form>
                            </div>
                        <?php }
                    }
                }

                $sortOrder = $sortOrder = 'rating DESC';
                if (getAllReviews($StockItem['StockItemID'], $sortOrder, $databaseConnection)){
                    ?>
                    <div class="reviews">
                        <div class="order-by">
                            <form method="post">
                                <label for="sortOrder">Sort by:</label>
                                <select name="sortOrder" id="sortOrder">
                                    <option value="rating ASC">Rating (Ascending)</option>
                                    <option value="rating DESC">Rating (Descending)</option>
                                    <option value="publicationDate ASC">Review Date (Ascending)</option>
                                    <option value="publicationDate DESC">Review Date (Descending)</option>
                                </select>
                                <input type="submit" value="Sort Reviews">
                            </form>
                        </div>

                        <?php
                        }
                        if (isset($_POST['sortOrder']))
                        {
                            $sortOrder = $_POST['sortOrder'];
                        }
                        $reviews = getAllReviews($StockItem['StockItemID'], $sortOrder, $databaseConnection);

                        foreach ($reviews as $review) {
                                ?>
                            <div id="StockItemSpecifications">
                                <div class="review-person">
                                    <?php
                                    echo ($review['FullName'] . '<br>');
                                    for ($i = 0; $i < $review['rating']; $i++) {
                                        echo '⭐️ ';
                                    }
                                    ?>
                                </div>
                                <div>
                                    <?php
                                    echo ($review['review'] . "<br>");
                                    ?>
                                </div>
                                <div class="review-date">
                                    <?php
                                    if ($review['lastedited'])
                                    {
                                        echo ('<i> edited </i> &nbsp  ' . $review['lastedited']); //edited tag als er een wijziging is geweest
                                    }
                                    else
                                    {
                                        echo $review['publicationDate'];
                                    }
                                        ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
            </div>

            <?php
            if (isset($_POST['ReviewToevoegen'])) {
                $review = $_POST['review'];
                addReview($review, $StockItem["StockItemID"], $personID, $_POST['rating'],$databaseConnection);
                ?>
            <meta http-equiv="refresh" content="0">
            <?php
            }
            ?>

            <?php
            if (isset($_POST['ReviewAanpassen'])) {
                $editedRating = $_POST['aangepasteRating'];
                $editedReview = $_POST['aangepasteReview'];
                updateReview($editedReview, $editedRating, $personID, $StockItem['StockItemID'],$databaseConnection);
                ?>
                <meta http-equiv="refresh" content="0">
                <?php
            }
            ?>


            <?php
                if(count($AlsoBought) != 0) {
            ?>
                    <div class="ProductAlsoBoughtWrapper">
                <h3>Vaak samen gekocht</h3>
                <div class="ProductsAlsoBoughtGrid">
                    <?php
                        foreach ($AlsoBought as $product) {
                            $alsoBoughtDiscount = getDiscountByStockItemID($product['StockItemID'], $databaseConnection);
                            $amtSoldLast72Hrs = getAmountOrderedLast72Hours($product['StockItemID'], $databaseConnection);
                            ?>
                            <a class="ListItem" href='view.php?id=<?php echo $product['StockItemID']; ?>'>
                                <?php
                                    if (isset($product["StockItemImage"])) { ?>
                                        <div class="ImgFrame"
                                            style="background-image: url('<?php print "Public/StockItemIMG/" . $product["StockItemImage"]; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;">
                                            <?php if ($alsoBoughtDiscount) { ?>
                                            <div class="small-timer-container">
                                                <div class="discount-also-bought-text">-<?php echo intval($alsoBoughtDiscount['DiscountPercentage'], 10) ?>%&nbsp;</div>
                                                <?php if ($amtSoldLast72Hrs >= 5) { ?><div class="flame-small"></div><?php } ?>
                                                <div class="small-timer" id="clock<?php echo $product['StockItemID'] ?>"></div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                <?php } ?>
                                <h5><?php echo $product["StockItemName"] ?></h5>

                                <?php if ($alsoBoughtDiscount) { ?>
                                    <p>
                                        <s><?php echo calculatePriceBTW($product['SellPrice'], $product['TaxRate']); ?></s>
                                        <?php echo calculateDiscountedPriceBTW($product['SellPrice'], $alsoBoughtDiscount['DiscountPercentage'], $product['TaxRate']); ?>
                                    </p>
                                <?php } else { ?>
                                    <p><?php echo calculatePriceBTW($product['SellPrice'], $product['TaxRate']); ?></p>
                                <?php } ?>

                                <form method="post">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="StockItemID" value="<?php echo $product['StockItemID']; ?>">
                                    <button class="add-to-cart-button" type="submit" name="addToCart" value="addItemToCart">
                                        <i class="fa fa-cart-plus fa-lg"></i>
                                    </button>
                                </form>
                            </a>
                            <script>
                                <?php if ($alsoBoughtDiscount) { ?>

                                clockCountdown('clock<?php echo $product['StockItemID'] ?>', '<?php echo $alsoBoughtDiscount['EndDate'] ?>', false);

                                <?php } ?>
                            </script>
                            <?php
                        }
                    ?>
                </div>
            </div>
            <?php 
                }
            ?>
        </div>
        <?php if ($currentDiscount AND strtotime($currentDiscount['StartDate']) < time()) { ?>
        <script>
            clockCountdown('clock', '<?php echo $currentDiscount['EndDate'] ?>');
        </script>
        <?php }
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    }
    ?>
</div>




