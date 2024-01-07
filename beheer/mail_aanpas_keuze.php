<?php
include "../components/beheer-header.php";
include "../helpers/utils.php";

$next=getNextNonExistingID($databaseConnection);
$_SESSION['next']=$next;

?>

<div id="CenteredContent">
    <h3>Email Templates</h3>
    <a href="./mail_beheer.php?id=<?php echo $next; ?>" class="button primary btn-small">Maak nieuwe aan</a>
    <table class="loyalty-table">

        <?php
        $templates = getEmailTemplates($databaseConnection);

        if (isset($_POST['verwijder'])) {
            $idToDelete = $_POST['verwijder'];
            deleteTemplateByID($databaseConnection, $idToDelete);
            header("Location: ./mail_aanpas_keuze.php");
            exit();
        }
        ?>

        <table>
            <tr>
                <th>Title</th>
                <th>Action</th>
            </tr>
            <?php foreach ($templates as $item): ?>
                <tr>
                    <td style="cursor: pointer" onClick="window.location.href='./mail_beheer.php?id=<?php echo $item["ID"]; ?>'"> <?php print $item['titel'] ?></td>
                    <td>
                        <form method="post">
                            <button type="submit" name="verwijder" value="<?php echo $item['ID']; ?>">
                                <i class="fa fa-trash lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>


    </table>
</div>

<?php
    if ((isset($_POST['submit']))) {
    $editorContent = $_POST['editor'];
    if (!empty($editorContent)) {
        insertContent($databaseConnection, $editorContent);
        if(isset($_SESSION['id'])){
            updateTemplate($databaseConnection,$_SESSION['id'],$_POST['editor'],$_POST['titel']);}



    } else {
        print 'Please add content in the editor.';

    }
    ?>
<meta http-equiv="refresh" content="0">
<?php
}


include "../components/footer.php"
?>
