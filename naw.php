<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>

<form action="POST" class="naw-form">
    <div class="naw-input">
            <label for="name">
              Naam
            </label>
        <input type="text" name="naam" id="naam">
    </div>

    <div class="naw-input form-width-2">
        <div class="naw-input-inner">
                <label for="name">
                    Straatnaam
                </label>
            <input type="text" name="straatnaam" id="straatnaam">
        </div>
        <div class="naw-input-inner2">
                <label for="name">
                    Huisnummer
                </label>
            <input type="text" name="huisnummer" id="huisnummer">
        </div>
    </div>

    <div class="naw-input form-width-4">
            <div class="naw-input-inner">
                <label for="name">
                    Postcode
                </label>
            <input type="text" name="postcode" id="postcode">
        </div>
        <div class="naw-input-inner">
            <label for="name">
                Land
            </label>
            <input type="text" name="land" id="land">
        </div>
        <div class="naw-input-inner">
            <label for="name">
                Provincie
            </label>
            <input type="text" name="provincie" id="provincie">
        </div>
        <div class="naw-input-inner">
            <label for="name">
                Plaats
            </label>
            <input type="text" name="plaats" id="plaats">
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Telefoonnummer
            </label>
            <input type="text" name="telefoonnummer" id="telefoonnummer">
        </div>
    </div>

    <div class="radio-container">
        <div class="radio-label-naw">
                <label>
                    <input type="radio" name="Verzending" id="standaardVerzending">
                    Standaard verzending
                </label>
                <label>
                    <input type="radio" name="Verzending" id="expressVerzending">
                    Express verzending
                </label>
        </div>
        <div class="radio-label">
                <label>
                    <input type="radio" name="betaalmethode" id="iDeal">
                    iDeal
                </label>
                <label>
                    <input type="radio" name="betaalmethode" id="Nerdygadgets Giftcard">
                    Nerdygadgets Gifcard
                </label>
        </div>
    </div>

    <div class="comments">
        <div>
            <label for="opmerkingen">Opmerkingen</label>
        </div>
        <div>
            <textarea id="opmerkingen" name="opmerkingen" rows="6" cols="50"></textarea>
        </div>
    </div>

    <div class="naw-submit-wrapper">
        <input type="submit" value="Afrekenen" class="button primary">
    </div>
</form>


<?php
include __DIR__ . "/components/footer.php"
?>
