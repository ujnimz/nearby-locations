<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<meta name="robots" content="nofollow" />
<meta name="googlebot" content="noindex">
<title>Find Nearby Location with HTML5 Geo Location and Google Maps API</title>
	<link type="text/css" rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap/3.0.2/css/bootstrap.css" media="all" />
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"  integrity="sha256-C6CB9UYIS9UJeqinPHWTHVqh/E1uhG5Twh+Y5qFQmYg=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
	<script src="location.js?p4r9n5"></script>
	<style type="text/css">
		.red-text{
			color: #FF0004;
		}
		.blue-text{
			color: #0078ff;
		}
	</style>
</head>
<body>
<div class="container text-center">    
  <div class="row content">
		
	<?php if(!isset($_GET['ulat']) || !isset($_GET['ulng'])){ ?>
     <div class="find_near_btn_wrapper clearfix">
		<button class="btn btn-primary form-submit" id="find_near_btn">Find Your Nearest Branch</button>
		
		<div class='info'>
			<i class="fa fa-info-circle fa-lg" aria-hidden="true"></i> Please allow your website to use your current location to find your nearest branch.
		</div>
	</div>
	<?php } ?>
	
	<div id="result">
        <!--Position information will be inserted here-->
    </div>
	  
	<div id="map">
        <!--Position information will be inserted here-->
    </div>
	
	<?php if(isset($_GET['ulat']) || isset($_GET['ulng'])){
		$center_lat = $_GET['ulat'];
		$center_lng = $_GET['ulng'];
				
		$url_nearby_api="https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$center_lat.",".$center_lng."&radius=1000&type=YOUR+LOCATION+TYPE&keyword=YOUR+LOCATION+NAME&key=GOOGLE+API+KEY";
		//echo $url_nearby_api;
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url_nearby_api);
		$str_nearby_api = curl_exec($ch);
	
		//$str_nearby_api = file_get_contents($url_nearby_api);
		$json_nearby_api = json_decode($str_nearby_api, true);
		//var_dump(json_decode($str_nearby_api, true));
		$all_distance = array();
		$all_place_id = array();
		$all_details = array();
		$i = 0;
		//print_r($json_nearby_api['results']);
		if (!empty($json_nearby_api['results'])) {
		foreach ($json_nearby_api['results'] as $nearby_option) {
			$option_id = $nearby_option['place_id'];
			$option_lat = $nearby_option['geometry']['location']['lat'];
			$option_lng = $nearby_option['geometry']['location']['lng'];
			//echo $option_id;

			$url_directions_api="https://maps.googleapis.com/maps/api/directions/json?origin=".$center_lat.",".$center_lng."&destination=place_id:".$option_id."&key=GOOGLE+API+KEY";
			
			$ch_2 = curl_init();
			curl_setopt($ch_2, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch_2, CURLOPT_URL, $url_directions_api);
			$str_directions_api = curl_exec($ch_2);
			
			//$str_directions_api = file_get_contents($url_directions_api);
			$json_directions_api = json_decode($str_directions_api, true);
			//var_dump(json_decode($str_directions_api, true));
			foreach ($json_directions_api['routes'] as $directions_option) {
				$distance_in_km = $directions_option['legs'][0]['distance']['text'];
				$distance_in_m = $directions_option['legs'][0]['distance']['value'];
				$distance_time = $directions_option['legs'][0]['duration']['text'];
				$address = $directions_option['legs'][0]['end_address'];
				//echo $distance_in_km.' km';
				//echo $distance_in_m.' m';
				//echo '<br>';
			}
			
			$all_distance[$i] = $distance_in_m;
			$all_details[$i]['place_id'] = $option_id;
			$all_details[$i]['distance_in_km'] = $distance_in_km;
			$all_details[$i]['distance_time'] = $distance_time;
			$all_details[$i]['branch_address'] = $address;

			$i++;

		}
		} else {
			$place_id = NULL;
		}
		
	
		$min_distance_index = array_keys($all_distance, min($all_distance)); // find the index of the smallest distance value
		$min_distance_array = $all_details[$min_distance_index[0]];
		//print_r($min_distance_array);
		$min_distance_id = $min_distance_array['place_id'];
		$min_distance_km = $min_distance_array['distance_in_km'];
		$min_distance_time = $min_distance_array['distance_time'];
		$min_distance_branch = $min_distance_array['branch_address'];
		$place_id = $min_distance_id;
	?>
	<div class="row">
	<div class="map-wrapper">
		<?php if($place_id != NULL){ ?>
			<div style="padding: 20px;">
			<h2>Your nearest location is:</h2>
			<h3>Address: <br><span class="blue-text"><?php echo $min_distance_branch; ?></span></h3>
			<h3>Distance from your location:<br> <span class="blue-text"><?php echo $min_distance_km; ?></span></h3>
				<h3>Drive duration by car:<br> <span class="blue-text"><?php echo $min_distance_time; ?></span></h3>

			<a class="btn btn-success" href="https://www.google.com/maps/dir/?api=1&origin=<?php echo $center_lat; ?>,<?php echo $center_lng; ?>&destination=YOUR+LOCATION+NAME&destination_place_id=<?php echo $place_id; ?>&travelmode=driving" target="_blank">Open Directions with Google Maps</a>
			</div>
			<iframe
			style="display:block;"
			width="100%"
			height="650"
			frameborder="0" style="border:0px"
			src="https://www.google.com/maps/embed/v1/place?key=GOOGLE+API+KEY&q=place_id:<?php echo $place_id; ?>" allowfullscreen>
			</iframe>
		<?php } else { ?>
			<div style="padding: 20px;">
			<h2 class="red-text">There is no branch nearby. Your location is:</h2>
			<iframe
			style="display:block;"
			width="100%"
			height="650"
			frameborder="0" style="border:0px"
			src="https://www.google.com/maps/embed/v1/place?key=GOOGLE+API+KEY&q=<?php echo $center_lat.','.$center_lng; ?>" allowfullscreen>
			</iframe>
			</div>
		<?php } ?>
	</div>
	</div>
	<?php } ?>
		
  </div>
</div>
</body>
</html>                            
