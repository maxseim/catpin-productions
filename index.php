<!DOCTYPE html>
<html> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
  <title>Green Line Light Rail Real-Time Locations</title> 
  <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
  <script type="text/JavaScript">
function TimedRefresh( t ) {
setTimeout("location.reload(true);", t);
}
</script>

</head>
<style>
body,html{
margin:0px auto;
width:100%;
background:#fff;
text-align:center;
}
#wrapper{
margin:0px auto;
width: 1200px;
text-align: center:
}
#map{
width: 1200px; 
height: 700px;
text-align: center:
}
#banner{
background:#000;
width:1200px;
height:40px;
color:#fff;
font-family:arial;
font-size:20px;
text-align:left;
padding-top:15px;
}
#reported{
color:#fff;
font-family:arial;
font-size:20px;
}
#notreported{
color:#fff;
font-family:arial;
font-size:20px;
}
</style>

<body onload="JavaScript:TimedRefresh(30000);">
<div id="wrapper">
<div id="banner">
 &nbsp;&nbsp; <span style="background:green; width:50px; height:30px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> &nbsp; GREEN LINE REAL-TIME LOCATIONS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="now"><?=date("Y-m-d h:i:s")?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ACTIVE TRAINS: <span id="reported"></span> &nbsp;&nbsp; <span id="notreported"></span>
</div>
  <div id="map"></div>
 
  <script type="text/javascript">
	
$.ajax({
    url: "getmarkers.php",
    dataType: "json",
    success: function(locations){

 var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      //center: new google.maps.LatLng(44.955695,-93.167002),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
    var markers = new Array();

    for (i = 0; i < locations.length; i++) {
color = locations[i][4];
icon = color;	
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
		icon: new google.maps.MarkerImage(icon)
      });

      markers.push(marker);

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }

	document.getElementById('reported').innerHTML = locations.length;
	
    function AutoCenter() {
      //  Create a new viewpoint bound
      var bounds = new google.maps.LatLngBounds();
      //  Go through each...
      $.each(markers, function (index, marker) {
      bounds.extend(marker.position);
      });
      //  Fit these bounds to the map
      map.fitBounds(bounds);
    }
    AutoCenter();

    }
})

  </script>

  </div>
</body>
</html>
