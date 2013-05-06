<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link href="map.css" rel="stylesheet" type="text/css">
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

	<?php

	//
	// From http://non-diligent.com/articles/yelp-apiv2-php-example/
	//
	include('ip2locationlite.class.php');

	//Load the class
	$ipLite = new ip2location_lite;
	$ipLite->setKey('2961c85aa177053beee919bc6ed8cc4571991f77215ff8562fc0c46e857fb314');

	//Get errors and locations
	$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
	$errors = $ipLite->getError();
	
	echo "$errors";

	//Getting the result
	$latitude = "43.1333";
	$longitude = "-77.6018";
	$city = "Rochester";

	echo "Current Position: $city ($latitude : $longitude)";
	echo "<br>";



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
	
	$response = json_decode($data, TRUE);
	
	?>

    <script>
var map;
function initialize() {
	
	var lancenter =
      "<?php
      echo $latitude
      ?>";
    var loncenter = 
      "<?php
      echo $longitude
      ?>";

  var mapOptions = {
    zoom: 12,
    center: new google.maps.LatLng(lancenter, loncenter),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

	var companyLogo = new google.maps.MarkerImage('marker.png',
	new google.maps.Size(35,60),
	new google.maps.Point(0,0),
	new google.maps.Point(18,60)
	);
	

	<?php    
	//Er word een loop gemaakt die de array met resultaten doorloopt en vervolgens markers, infowindows en beschrijving op de kaart plaatst voor alle resultaten.
	 
	 for($i=0; $i<count($response['businesses']); $i++) {
	        $latitude_result = $response["businesses"][$i]["location"]["coordinate"]["latitude"];
	        $longitude_result = $response["businesses"][$i]["location"]["coordinate"]["longitude"];  
	        $result_description = $response["businesses"][$i]["name"];
	        $result_rating = $response["businesses"][$i]["rating_img_url_small"];
	        $result_ratingnr = $response["businesses"][$i]["review_count"];
	        $result_beschrijving = $response["businesses"][$i]["snippet_text"];
	        $result_beschrijving=str_replace("\n"," ",$result_beschrijving);
	        $result_beschrijving=str_replace("\r"," ",$result_beschrijving);
	        $result_adres0 = $response["businesses"][$i]["location"]["display_address"][$i];
	        $result_adres1 = $response["businesses"][$i]["location"]["display_address"]["1"];
	        $result_adres2 = $response["businesses"][$i]["location"]["display_address"]["2"];
	        $result_adres3 = $response["businesses"][$i]["location"]["display_address"]["3"];
	        $result_image = $response["businesses"][$i]["image_url"];
	        $result_url = $response["businesses"][$i]["url"];
	        $result_image = $result_image ? $result_image : 'noimg.gif';
	        $result_beschrijving = $result_beschrijving ? $result_beschrijving : 'Not Validated.';
	?>
	
	var lanresult =
	      "<?php
	      echo $latitude_result
	      ?>";
	 var lonresult = 
	      "<?php
	      echo $longitude_result
	      ?>";
	var resultloc = new google.maps.LatLng(lanresult, lonresult);
	 var beschrijving = 
	      '<h3>'+"<?php
	      echo "<div id='container'>",Name,":","&nbsp;","<h2>", $result_description,"</h2>","</br>",Comment,":","&nbsp;","<h2>", $result_beschrijving,"<a href='", $result_url,"' target='_new'>","Details","</a>","</h2>","</br>", Addr,":","&nbsp;","<h2>", $result_adres0,"</br>", $result_adres1,"</h2>","</br>", Rating,":","&nbsp;","<img src='", $result_rating,"'/>","<h2>",$result_ratingnr,"&nbsp;",Recommands,"</h2>","</br>","<img src='", $result_image,"'/>","</br>","</br>","<h2>","<a href='", $result_url,"' target='_new'>","Lees meer informatie via YELP","</a>","</h2>","</div>"
	      ?>";'</h3>'

	    var marker<?php echo $i; ?> = new google.maps.Marker({
	    map: map,
	    icon: companyLogo,
	    position: resultloc,
	    });
	    var infowindow<?php echo $i; ?>= new google.maps.InfoWindow({
	    content: beschrijving,
	    maxWidth: 260
	    });
	    google.maps.event.addListener(marker<?php echo $i; ?>, 'click', function() {
	      infowindow<?php echo $i; ?>.open(map,marker<?php echo $i; ?>);
	    });

	 <?php
	     } // end if php
	  ?>

}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>
