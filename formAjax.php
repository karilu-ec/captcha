<!DOCTYPE html>
	<head>
	    <script src="http://www.usna.edu/CMS/_standard2.1.1/_files/scripts/head.js" type="text/javascript"></script>
<script type="text/javascript">
head.js(
   {jquery: "http://www.usna.edu/CMS/_standard2.1.1/_files/scripts/jquery/js/jquery-1.10.1.min.js"},
   {jqueryui: "http://www.usna.edu/CMS/_standard2.1.1/_files/scripts/jquery/js/jquery-ui-1.10.2.custom.min.js"}
   );
</script>
<script type="text/javascript">
         function reloadRecaptcha() {
           Recaptcha.reload();
         }
  
      head.ready('jqueryui',function() {
                
      $(document).ready(function(){
        
       $("#mysubmit").click(function(){
           
           
           $.ajax({
            type: "POST",
            url: "responseCheckAnswerGame.php",
            dataType: "text",
            data: {my_submit_button_name : $("input[name='my_submit_button_name']").val()                                      
            },
            success:function(data) {
             var response = data;
             if (response == "1") {
               $("#mainform").submit();
             } else {
              $(".notice").css("color", "red").html("Sorry, try again.");
             }
             
             },
            error: function() {
              alert ("something went wrong");
              
            }
           });        
       });
      }); 
        
      });
    </script>
</head>
<?php
//******************************************************************************
/*
	Name:		sample.php

	Purpose:	Provide an example of how to integrate an AYAH PlayThru on PHP web form.

	Requirements:
			- your web server uses PHP5 (or higher).
			- all the AYAH PHP library files are in the same directory as this file.
			- the ayah_config.php contains a valid publisher key and scoring key.
			- you have read the installation instructions page at:
				http://portal.areyouahuman.com/installation/php

	Notes:		- if the Game Style for your PlayThru is set to "Lightbox", the
			  PlayThru will not display until after you click the submit button.
			  To change this setting, use the dashboard at:
				http://portal.areyouahuman.com/dashboard.php
*/
//******************************************************************************

// Instantiate the AYAH object. You need to instantiate the AYAH object
// on each page that is using PlayThru.
require_once("areyouahuman.php");

// Check to see if the user has submitted the form. You will need to replace
// 'my_submit_button_name' with the name of your 'Submit' button.
//if (array_key_exists('my_submit_button_name', $_POST))
//{
//        // Use the AYAH object to see if the user passed or failed the game.
//        $score = $ayah->scoreResult();
//
//        if ($score)
//        {
//                // This happens if the user passes the game. In this case,
//                // we're just displaying a congratulatory message.
//                echo "Congratulations: you are a human!";
//        }
//        else
//        {
//        		// This happens if the user does not pass the game.
//                echo "Sorry, but we were not able to verify you as human. Please try again.";
//        }
//}
?>

<!-- Now we're going to build the form that PlayThru is attached to.
In this example, the form submits to itself. -->
<div class="notice"></div>
<form id="mainform" method="post" action="response-post.php">

        <p>Please enter your name: <input type="text" name="name" /></p>

        <?php
            // Use the AYAH object to get the HTML code needed to
            // load and run PlayThru. You should place this code
            // directly before your 'Submit' button.
            echo $ayah->getPublisherHTML();
        ?>
        
        <!-- Make sure the name of your 'Submit' matches the name you used on line 9. -->
        <input type="button" id="mysubmit" name="my_submit_button_name" value=" Submit ">
</form>
