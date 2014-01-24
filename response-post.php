<?php

     if (isset($_POST['name'])) {
            $name = strip_tags(trim($_POST['name']));
            echo "Congratulations $name. You proved you are human";
        }
    else {
        //this happens if the user fails
        echo "Sorry, but you failed.";
    }        

?>