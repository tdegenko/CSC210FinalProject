<?php

//
// From http://non-diligent.com/articles/yelp-apiv2-php-example/
//
include('ip2locationlite.class.php');
 
//Load the class
$ipLite = new ip2location_lite;
$ipLite->setKey('19bf356b10d1cb49a3118b65335774666a71f1d6971c5f29a96f03b00c334c03');
 
//Get errors and locations
$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
$errors = $ipLite->getError();

echo "<p>\n";
echo "<strong>First result</strong><br />\n";
if (!empty($locations) && is_array($locations)) {
  foreach ($locations as $field => $val) {
    echo $field . ' : ' . $val . "<br />\n";
  }
}
echo "</p>\n";
 
//Getting the result
$latitude = $locations['latitude'];
$longitude = $locations['longitude'];
$city = $locations['cityName'];

echo "Current Position: $city ($latitude : $longitude)";
echo "<br>";

 
//Show errors


// Enter the path that the oauth library is in relation to the php file
require_once ('lib/OAuth.php');

// For example, request business with id 'the-waterboy-sacramento'
//$unsigned_url = "http://api.yelp.com/v2/business/the-waterboy-sacramento";

// For examaple, search for 'tacos' in 'sf'
$unsigned_url = "http://api.yelp.com/v2/search?term=tacos&ll=$latitude,$longitude";


// Set your keys here
$consumer_key = "cpZ9nrOD1sgQbReRO2Ye_A";
$consumer_secret = "HRKYduueF-sDSsaFQJUrzIYxOYA";
$token = "3L13PdJ_LnccS8WcCAYCLPxEpL29cDxQ";
$token_secret = "Hnk6gB1A7EbfVW1x1et2Dj7ssnc";

// Token object built using the OAuth library
$token = new OAuthToken($token, $token_secret);

// Consumer object built using the OAuth library
$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

// Yelp uses HMAC SHA1 encoding
$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

// Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);

// Sign the request
$oauthrequest->sign_request($signature_method, $consumer, $token);

// Get the signed URL
$signed_url = $oauthrequest->to_url();

// Send Yelp API Call
$ch = curl_init($signed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch); // Yelp response
curl_close($ch);


// Print it for debugging
$obj = json_decode($data, TRUE);
for($i=0; $i<count($obj['businesses']); $i++) {
	?>
	
	
    <p><a href = "<?php echo $obj['businesses'][$i]["mobile_url"]; ?>"> <?php echo $obj['businesses'][$i]["name"]; ?></a>  <img src = "<?php echo $obj['businesses'][$i]["rating_img_url"]; ?>" alt="Rating img"> </p>
 	<br>   
	
<?php	
	
}
?>