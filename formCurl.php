<!DOCTYPE html>
<?php

require_once("areyouahuman.php");

function use_curl(){
    if (function_exists('curl_init') and function_exists('curl_exec'))
		{
			return TRUE;
		}
		return FALSE;
}

function doHttpsPost($hostname, $path, $fields) {
		$result = "";
		// URLencode the post string
		$fields_string = "";
		foreach($fields as $key=>$value) {
			if (is_array($value)) {
				if ( ! empty($value)) {
					foreach ($value as $k => $v) {
						$fields_string .= $key . '['. $k .']=' . $v . '&';
					}
				} else {
					$fields_string .= $key . '=&';
				}
			} else {
				$fields_string .= $key.'='.$value.'&';
			}
		}
		rtrim($fields_string,'&');

		// Use cURL?
		if (use_curl())
		{
			// Build the cURL url.
			$curl_url = "http://" . $hostname . $path;

			// Log it.
			//$this->__log("DEBUG", __FUNCTION__, "Using cURl: url='$curl_url', fields='$fields_string'");

			// Initialize cURL session.
			if ($ch = curl_init($curl_url))
			{
				// Set the cURL options.
				curl_setopt($ch, CURLOPT_POST, count($fields));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				// Execute the cURL request.
				$result = curl_exec($ch);
				

				// Close the curl session.
				curl_close($ch);
			}
			else
			{
				// Log it.
				//$this->__log("DEBUG", __FUNCTION__, "Unable to initialize cURL: url='$curl_url'");
                                return 0;
			}
		}
		else
		{
			// Log it.
			//$this->__log("DEBUG", __FUNCTION__, "Using fsockopen(): fields='$fields_string'");

			// Build a header
			$http_request  = "POST $path HTTP/1.1\r\n";
			$http_request .= "Host: $hostname\r\n";
			$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
			$http_request .= "Content-Length: " . strlen($fields_string) . "\r\n";
			$http_request .= "User-Agent: AreYouAHuman/PHP " . $this->get_version_number() . "\r\n";
			$http_request .= "Connection: Close\r\n";
			$http_request .= "\r\n";
			$http_request .= $fields_string ."\r\n";

			$result = '';
			$errno = $errstr = "";
			$fs = fsockopen("ssl://" . $hostname, 443, $errno, $errstr, 10);
			if( false == $fs ) {
				//$this->__log("ERROR", __FUNCTION__, "Could not open socket");
                                return false;
			} else {
				fwrite($fs, $http_request);
				while (!feof($fs)) {
					$result .= fgets($fs, 4096);
				}

				$result = explode("\r\n\r\n", $result, 2);
				$result = $result[1];
			}
		}

		// Log the result.
		//$this->__log("DEBUG", __FUNCTION__, "result='$result'");

		// Return the result.
		return $result;
	}

if (array_key_exists('my_submit_button_name', $_POST)) {
    
    //Use the AYAH object to see if the user passed or failed the game
    $score = $ayah->scoreResult();
    
    if ($score) {
        //make the POST of the form to another page.
        $host = "localhost";
        $postURL = "/areyouahuman/response-post.php";
        $response = doHttpsPost($host, $postURL, $_POST);
        //header("Location: $postURL");
	echo $response;
        
    }
    else {
        //this happens if the user fails
        echo "Sorry, but you failed.";
    }        
}
?>

<!-- Now we're going to build the form that PlayThru is attached to.
In this example, the form submits to itself. -->
<form method="post" action="">

        <p>Please enter your name: <input type="text" name="name" /></p>

        <?php
            // Use the AYAH object to get the HTML code needed to
            // load and run PlayThru. You should place this code
            // directly before your 'Submit' button.
            echo $ayah->getPublisherHTML();
        ?>
        
        <!-- Make sure the name of your 'Submit' matches the name you used on line 9. -->
        <input type="Submit" name="my_submit_button_name" value=" Submit ">
</form>
