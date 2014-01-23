<?php
require_once("areyouahuman.php");

if (array_key_exists('my_submit_button_name', $_POST)) {
    
    //Use the AYAH object to see if the user passed or failed the game
    $score = $ayah->scoreResult();
    
    if ($score) {
        if (isset($_POST['name'])) {
            $name = strip_tags(trim($_POST['name']));
        }
        echo "Congratulations $name. You proved you are human";    
    }
    else {
        //this happens if the user fails
        echo "Sorry, butw you failed.";
    }        
}
?>