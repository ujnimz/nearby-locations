// Set up global variable
	$(document).ready(function() {
		$("#find_near_btn").click(function () {
			
		console.log("We run");
		var current_url = window.location.href; 

		
		// Set up global variable
		var result;

		// Store the element where the page displays the result
		result = document.getElementById("result");

		//If geolocation is available, try to get the visitor's position
		if ("geolocation" in navigator){ //check geolocation available 
        //try to get user current location using getCurrentPosition() method
        	navigator.geolocation.getCurrentPosition(successCallback, errorCallback); 
			result.innerHTML = "Getting the position information...";
		} else {
			alert("Sorry, your browser does not support HTML5 geolocation.");
		}
			
			
		// Define callback function for successful attempt
    function successCallback(position){
		result.innerHTML = "Your current position is (" + "Latitude: " + position.coords.latitude + ", " + "Longitude: " + position.coords.longitude + ")";
        window.location = current_url + '?ulat='+ position.coords.latitude +'&ulng='+ position.coords.longitude;
		//$("#map").html("<iframe width='100%' height='450' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/place?q="+ position.coords.latitude +","+ position.coords.longitude +"&amp;key=AIzaSyBdcHcmKMHBKHL2VXCEc5hVWewif3x60Tk'></iframe>");
		//$("#map").html("<iframe width='100%' height='600' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/directions?key=AIzaSyBdcHcmKMHBKHL2VXCEc5hVWewif3x60Tk&origin="+ position.coords.latitude +","+ position.coords.longitude +"&destination=place_id:ChIJDx4UHGCvST4RWZcngx6uqL4&avoid=tolls|highways'></iframe>");
		
    }
    
    // Define callback function for failed attempt
    function errorCallback(error){
        if(error.code === 1){
            result.innerHTML = "You've decided not to share your position, if you change your mind please reload this page.";
        } else if(error.code === 2){
            result.innerHTML = "The network is down or the positioning service can't be reached.";
        } else if(error.code === 3){
            result.innerHTML = "The attempt timed out before it could get the location data.";
        } else{
            result.innerHTML = "Geolocation failed due to unknown error.";
        }
    }
			
 

			
	});
		
	});