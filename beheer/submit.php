<?php
include "../components/beheer-header.php";
// Include the database configuration file

// If the form is submitted
if ((isset($_POST['submit']))) {
    // Get editor content
      $editorContent = $_POST['editor'];
    //$editorContent = str_replace('$(naam)', $naam, $editorContent);
    //$editorContent = str_replace('$(customerID)', $customerID, $editorContent);
    //$editorContent = str_replace('$(telefoonnummer)', $telefoonnummer, $editorContent);
    //$editorContent = str_replace('$(bezorg-adres)', $bezorgAdres, $editorContent);
//    $editorContent = str_replace('$(postcode)', $postcode, $editorContent);

    // Voer de foreach-lus uit en sla de resultaten op in $productenText
    //$productenText = '';
    //foreach ($ordergegevens as $ordergegeven) {
    //    $productenText .= 'Item Name: ' . $ordergegeven["StockItemName"].' aantal: '. $ordergegeven['Quantity'] . '<br>';
    }

    // Vervang de $(producten) placeholder met $productenText in $editorContent

    //$editorContent = str_replace('$(producten)', $productenText, $editorContent);
    //$producttext='';
    //foreach ($AlsoBought as $product){
    //    $producttext .= 'item name: ' .$product['StockItemName'].'<br>';

    //}
      //  $editorContent = str_replace('$(alsobought)', $producttext, $editorContent);
//}



    // Print the updated content (for testing purposes)
    print $editorContent;

    // Check whether the editor content is empty
    if (!empty($editorContent)) {
        insertContent($databaseConnection, $editorContent);
            if(isset($_SESSION['id'])){
            updateTemplate($databaseConnection,$_SESSION['id'],$_POST['editor']);}
            if(!isset($_SESSION['id'])){
            insertTemplate($databaseConnection,$_POST['titel'],$editorContent);}


    } else {
        print 'Please add content in the editor.';

}


