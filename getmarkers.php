<?php
date_default_timezone_set('America/Chicago');

$headers = array(
    'Content-Type: application/json',
);

$url = 'http://svc.metrotransit.org/NexTrip/VehicleLocations/902?format=json';

// 901=Blue
// 902=Green
// 904=Red

//    "Bearing": 0,
//    "BlockNumber": 212,
//    "Direction": 3,   1=south, 2=east, 3=west, 4=north
//    "LocationTime": "/Date(1436314016000-0500)/",
//    "Odometer": 0,
//    "Route": "902",
//    "Speed": 0,
//    "Terminal": "",
//    "VehicleLatitude": 44.947935,
//    "VehicleLongitude": -93.148798

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);
$result_arr = json_decode($result, true);


header('Content-type: application/json');
$ind=1;
$index=0;
$build=array();
$reported=0;
$notreported=0;
foreach($result_arr as $key => $row) {
$dir=$row['Direction'];
$block=$row['BlockNumber'];
list($loc,$junk)=explode("-",$row['LocationTime']);
list($junk,$loc)=explode("(",$loc);
$loc=$result = substr($loc, 0, 10);
$loc=date("Y-m-d h:i:s",$loc);
$lat=$row['VehicleLatitude'];
$lon=$row['VehicleLongitude'];

$direct="X";
if($dir==2){
$direct="E";
}
if($dir==3){
$direct="W";
}
if($dir==2 && $lat>44.943000){
$build[]=array($direct.'-'.$loc, $lat, $lon, $ind, 'greeneast.png');
$ind++;
$reported++;
}
if($dir==3 && $lat>44.943000){
$build[]=array($direct.'-'.$loc, $lat, $lon, $ind, 'greenwest.png');
$ind++;
$reported++;
}
if($lat<=44.943000){
$gps="44.955695,-93.167002";
$build[]=array('Bad GPS Location', $lat, $lon, $ind, 'greenunknown.png');
$ind++;
$reported++;
$notreported++;
}
$index++;
}

echo json_encode($build);
?>
