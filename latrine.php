<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

###### Adjust the following storage location #########
# Storage location for files with a trailing slash to indicate a directory
# Currently it is set to latrine's dir

	$store = './'; 
//	$store = '/tmp/latrine/';

###### Adjust housekeeping time (if not using /tmp)
# Set to 24h. Change to fit your needs (time is in sec)

@$housekeeping = 24*60*60;

###### Change the following only if you know what you're doing #########

@$view = $_GET['view'];
@$geo = $_GET['geo'];
@$key = $_GET['key'];
@$delete = $_GET['delete'];

####### Locus Pro Parameters
# Latitude - current GPS latitude (in degree unit, rounded on 5 decimal places)
@$lat = $_GET['lat'];

# Longitude - current GPS longitude (in degree unit, rounded on 5 decimal places)
@$lon = $_GET['lon'];

# Altitude - current altitude value in meter (improved altitude value by filter and barometer)
@$alt = $_GET['alt'];

# Speed - current GPS speed (in m/s)
@$speed = $_GET['speed'];

# Accuracy - current GPS accuracy (in metres)
@$acc = $_GET['acc'];

# Battery - current akku (in percent)
@$battery = $_GET['battery'];

# GSM signal - current signal strength (in percent)
@$gsm_signal = $_GET['gsm_signal'];

# Bearing - current GPS bearing (in degree)
@$bearing = $_GET['bearing'];

# Time - current GPS time (in format defined by user. You may choose from predefined styles or define you own by this specification)
#my $time = param('time') || '';
# Text field - text field with own define key/value pair.
#y $var = param('var') || '';

# Check storage location
if (!is_dir($store)) {
    if (!mkdir($store)) {
        die("Store nicht verfügbar");
    }
}

# Current Unix timestamp
$time = time();
$now = $time;

# JSON output
#echo header('application/json');
header('Content-Type: application/json;charset=utf-8');
echo '{';


# Check private key
if ($key!="") {
        $geojson = $store.$key.".geojson";
        $store .= "$key.latlon"; # Create storage location

	# Housekeeping
	if (filemtime($store)< (time()-$housekeeping)) {
		unlink($geojson);
		unlink($store);
	}

	##### Write to file

        # Write latitude, longitude and other data into .latlon file when received from Locus
        if ( $lat!="" && $lon!="" ) {

        if ($handle = fopen($store, "w")) {
            fwrite($handle,'"lat":"'.$lat.'","lon":"'.$lon.'","alt":"'.$alt.'","speed":"'.$speed.'","bearing":"'.$bearing.'","acc":"'.$acc.'","time":"'.$time.'","battery":"'.$battery.'","gsm_signal":"'.$gsm_signal.'"');
            fclose($handle);
        } else {
            echo "Dateizugriffsfehler .latlon";
        }

	# Write latitude & longitude into .geojson file, collecting positions for track
        if ($handle = fopen($geojson, "a+")) {
                fwrite($handle,"[ ".$lon.", ".$lat." ], ");
                fclose($handle);
        } else {
                echo "Dateizugriffsfehler .geojson";
        }

	##### Read from file

        # Output latitude, longitude and other data as JSON
        } else if ($view == '1' ) {
                if ($handle = fopen($store, "r")) {
                    $contents=fread($handle,filesize($store));
                    $contents=$contents.',"now":"'.$now.'"';
                    echo $contents;
                    fclose($handle);
                } else {
                        echo 'Dateilesefahler view=1';
                }

        # Output latitude, longitude as JSON for drawing track
        } else if ($geo == '1') {
                if ($handle = fopen($geojson, "r")) {
                    $contents=fread($handle,filesize($geojson));
                    //Get rid of last ","
                    $contents=substr($contents,0,strrpos($contents,","));
                    $contents=' "type": "FeatureCollection",
                           "features": [
                             { "type": "Feature",
                               "geometry": {
                         "type": "MultiLineString",
                         "coordinates": [[ 
                        '.$contents.'
                        ]]
                        }
                        }
                        ]
                        ';
                    echo $contents;
                    fclose($handle);
                } else {
                        echo 'Dateilesefehler geo=1';
                }

	# Delete .latlon & .geojson by purpose
	} else if ($delete == '1') {
		unlink($geojson);
		unlink($store);

        # Parameters are missing
        } else {
                echo '"error":"bad parameters"';
        }

# Private key is missing
} else {
    echo 'Schlüssel falsch oder fehlt';
}

# Done!
echo '}';
?>
