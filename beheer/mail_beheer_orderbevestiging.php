<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";


$template1=getEmailTemplate($databaseConnection,1)
?>

<script>
    function submitForm() {
        document.getElementById("radioForm").submit();
    }
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TinyMCE Example</title>
    <!-- Include TinyMCE script -->
    <script src="https://cdn.tiny.cloud/1/33vhqyke3rifq88t349u5xvus5yq5sco72ip3h1xwkiay8sr/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/33vhqyke3rifq88t349u5xvus5yq5sco72ip3h1xwkiay8sr/tinymce/5/plugins/powerpaste/plugin.min.js" referrerpolicy="origin"></script>
    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '#editor',
            plugins: 'advlist autolink image lists link image charmap print preview anchor autoresize template powerpaste spellchecker',
            toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | template powerpaste spellchecker | bullist numlist outdent indent | link image | removeformat | fullscreen code',
            templates: [
                {
                    title: 'orderbevestiging',
                    description: 'Orderbevestiging',
                    content: 'Beste,<br><br>' +
                        'Bedankt voor je bestelling bij NerdyGatgets.<br>' +
                        'Alle details van je bestelling vind je hieronder terug. Je ontvangt een e-mail zodra je bestelling onderweg is.<br><br>' +
                        'Customer ID: <br>' +
                        'Name: <br>' +
                        'Phone Number: <br>' +
                        'Delivery Address: <br>'
                },
                {
                    title: 'reclame',
                    description: 'Reclame',
                    content: '<p>Beste,</p>' +
                        '<p>Op basis van je eerdere bestellingen hebben wij een aantal aanbiedingen speciaal voor jou:</p>'
                },
                {
                    title: 'ordernietbevestigd',
                    description: 'Ordernietbevestigd',
                    content: '<p>Beste (naam),</p>' +
                        '<p>Je hebt nog producten in je winkelmandje staan. Hierbij een herinnering om deze producten mogelijk alsnog te bestellen.</p>'
                }
            ],
        });
    </script>

    </script>
</head>
<body>
<form method="post" action="submit.php">
        <input type="text" name="orderID" placeholder="orderID">
        <textarea name="editor" id="editor" rows="25" cols="80" placeholder="kijk tussen de templates wat je op dit moment nodig hebt">

        </textarea>
    <input type="submit" name="submit" value="SUBMIT">
</form>
mogelijke veriabele om de gebruiken in de tekst (moet exact kloppen):<br>
$(naam), $(customerID), $(telefoonnummer), $(bezorg-adres), $(postcode), $(producten), $(alsobought)
</body>
</html>
<?php
include "../components/footer.php"
?>
