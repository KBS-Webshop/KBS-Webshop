<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";
$template = '';
$titel = '';
if(isset($_GET["id"])) {

    $templateData = getEmailTemplateID($databaseConnection, $_GET['id']);

    // Controleer of het sjabloon bestaat
    if ($templateData) {
        $template = $templateData[0]['content'];
        $titel = $templateData[0]['titel'];
        $_SESSION["id"] = $_GET['id'];
    }
 else {

    $newID = $_SESSION['next'];

    if (!isIDExists($databaseConnection, $newID)) {

        insertIDTemplate($databaseConnection, $newID);

        $templateData = getEmailTemplate($databaseConnection, $newID);

        $_SESSION["id"] = $newID;

    } else {
        print 'error';
    }}
}

?>

<script>
    function submitForm() {
        document.getElementById("radioForm").submit();
    }
</script>
    <script src="https://cdn.tiny.cloud/1/33vhqyke3rifq88t349u5xvus5yq5sco72ip3h1xwkiay8sr/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.tiny.cloud/1/33vhqyke3rifq88t349u5xvus5yq5sco72ip3h1xwkiay8sr/tinymce/5/plugins/powerpaste/plugin.min.js" referrerpolicy="origin"></script>
    <script>
            tinymce.init({
            selector: '#editor',
            plugins: 'advlist autolink image lists link image charmap print preview anchor autoresize template powerpaste',
            toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | template powerpaste | bullist numlist outdent indent | link image | removeformat | fullscreen code',
        });
    </script>
<form method="post" action="mail_aanpas_keuze.php"  >

    <input type="text" name="titel" value="<?php print $titel?>" required>
    <textarea name="editor" id="editor" rows="10" cols="10">
        <?php print $template ?>
    </textarea>

    <input type="submit" name="submit" value="pas format aan" class="button1 primary ">

</form>
mogelijke veriabele om de gebruiken in de tekst (moet exact kloppen):<br>
$(naam), $(customerID), $(telefoonnummer), $(bezorg-adres), $(postcode), $(producten), $(alsobought), $(linkUserInfo), $(logo)
<?php
include "../components/footer.php"
?>
