<?php
include __DIR__ . "/components/header.php";
include __DIR__ . "/helpers/utils.php";
?>

<form method="POST" class="naw-form" action="afrekenen.php">
    <div class="naw-input">
        <label for="name">
          Naam <span class="required"></span>
        </label>
        <input type="text" name="naam" id="naam" required>
    </div>

    <div class="naw-input form-width-2">
        <div class="naw-input-inner">
            <label for="straatnaam" class="inline-label">
                Straatnaam <span class="required"></span>
            </label>
            <input type="text" name="straatnaam" id="straatnaam" required>
        </div>
        <div class="naw-input-inner2">
            <label for="name" class="inline-label">
                Huisnummer <span class="required"></span>
            </label>
            <input type="text" name="huisnummer" id="huisnummer" required>
        </div>
    </div>

    <div class="naw-input form-width-4">
            <div class="naw-input-inner">
                <label for="name" class="inline-label">
                    Postcode <span class="required"></span>
                </label>
            <input type="text" name="postcode" id="postcode" required>
        </div>
        <div class="naw-input-inner">
            <label for="name" class="inline-label">
                Land <span class="required"></span>
            </label>
            <input type="text" name="land" id="land">
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Telefoonnummer
            </label>
            <input type="text" name="telefoonnummer" id="telefoonnummer" >
        </div>
    </div>

    <div class="naw-input form-width-5">
        <div class="naw-input-inner">
            <label for="name">
                Email-adres <span class="required"></span>
            </label>
            <input type="text" name="email" id="email" required>
        </div>
    </div>

    <div class="radio-container">

        <fieldset>
            <legend>Verzendopties</legend>
        <div class="radio-label-naw">
                <label>
                    <input type="radio" name="Verzending" id="standaardVerzending" required>
                    Standaard verzending
                </label>
                <label>
                    <input type="radio" name="Verzending" id="expressVerzending" required>
                    Express verzending
                </label>
        </div>
        </fieldset>

        <div class="radio-label">
                <label>
                    <input type="radio" name="betaalmethode" id="iDeal" required>
                    iDeal
                </label>
                <label>
                    <input type="radio" name="betaalmethode" id="Nerdygadgets Giftcard" required>
                    Nerdygadgets Gifcard
                </label>
        </div>
    </div>

    <div class="comments">
        <div>
            <label for="opmerkingen">Instructies voor de bezorger. (Optioneel)</label>
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
