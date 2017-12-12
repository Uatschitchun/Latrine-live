Latrine-Live
============

[Locus Map](http://www.locusmap.eu/) Android GPS live tracking server script

![ScreenShot](https://i.imgur.com/jTl2dIG.png?maxwidth=200)

Latrine-Live is an enhancement to [Latrine](https://github.com/Cyclenerd/Latrine), a minimalist GPS live tracking server script.
It is very easy to set up and requires no database.
All you need is a web server which can execute PHP scripts.

More information on the live tracking feature of Locus Map Pro can be found on its [documentation web page](http://docs.locusmap.eu/doku.php?id=manual:user_guide:functions:live_tracking#web_services).


Features
--------

* Tracking: Server receives data from Android device with Locus Pro installed and live tracking enabled.
* Live following: Data sent by Locus Pro is shown live on map together with moving data. (all of locus possible data is supported) 
* Track History: The track gets recorded and displayed.
* Multiple map layers: Currently you can choose between: OpenStreetMap, OpenCylceMap, OpenTopoMap, Hike & Bike (with Hillshading) and ESRI
* Multiple map overlays: Currently you can choose between: Hillshading, Contourlines, Waymarked Hike & Cycling
* Multi-user: Authentication with key
* Dialog for key: If URL is given without a direct key, a prompt shows up for to enter the key
* Disable Auto-Pan: Panning the map is active, as long as the popup is shown. When closing the popup, the user is able to en-/disable panning by checkbox
* Delete files: It's possible to delete files on server by URL
* Housekeeping: If not stored on /tmp device, the created files 'key'.latlaon & 'key'.geojson are kept as long as housekeeping time is set in latrine.php
* Messages: It's possible to provide a variable 'message' within locus and send free text with your position and moving data


Functions
---------

Authentication is done with a key. Anyone who knows the key can see and update the GPS location.
Your key must be at least 5 characters up to 15 characters `a-zA-Z0-9_`.

### Update

	http://<SERVER>/Latrine-live/latrine.php?key=<KEY>&lat=<Latitude>&lon=<Longitude>&alt=<Altitude>&speed=<Speed>&bearing=<Bearing>&acc=<Accuracy>

Example:

	http://localhost/cgi-bin/latrine.php?key=OnMyBike&lat=45.09&lon=6.07&acc=5&speed=4.16666667

### Get JSON (Position)

	http://<SERVER>/Latrine-live/latrine.php?key=<KEY>&view=1

Example:

	http://localhost/cgi-bin/latrine.php?key=OnMyBike&view=1

Reply:

	{
	  "lat":"51.25450",
	  "lon":"7.21661",
	  "alt":"322.1",
	  "speed":"0.0",
	  "bearing":"0.0",
	  "acc":"8.0",
	  "time":"1513007824",
	  "battery":"87",
	  "gsm_signal":"0",
	  "message":"Halbmarathon",
	  "now":"1513008148"
	}

### Get JSON (Track)

	http://<SERVER>/Latrine-live/latrine.php?key=<KEY>&geo=1

Example:

	http://localhost/cgi-bin/latrine.php?key=OnMyBike&geo=1

Reply:

	{
	  [ 6.07, 45.09 ],[ 6.07, 45.09 ],[ 6.07, 45.09 ],
	}

### Delete JSON Files created on server (be careful)

        http://<SERVER>/Latrine-live/latrine.php?key=<KEY>&delete=1

Example:

        http://localhost/cgi-bin/latrine.php?key=OnMyBike&delete=1

Reply:

        { }

### Show map

	http://<SERVER>/Latrine-live/index.html?#<KEY>
	or
	http://<SERVER>/Latrine-live/#<KEY>
	or
	http://<SERVER>/Latrine-live/ (You'll get asked for KEY then)

Example:

	http://localhost/Latrine-live/index.html?#OnMyBike
	or
	http://localhost/Latrine-live/#OnMyBike
	or
	http://localhost/Latrine-live/ (You'll be prompted for KEY then)



Installation
------------

1.) Adjust the storage location `my $store = '/tmp/latrine/';` in `latrine.php`.

2.) Adjust housekeeping time, if desired, in `latrine.php`.

3.) Adjust the location (URL) to your PHP script `var phpScript = 'latrine.php';` in `index.html`.

4.) Upload `latrine.php` and `index.html` onto your web server. The PHP script `latrine.php` must be executable by the web server.

5.) Configure Locus Map. Do not forget the key. The URL is: http://<SERVER>/Latrine-live/latrine.php

![LocusMap](http://i.imgur.com/NIRQrw8_d.jpg?maxwidth=320)

6.) Go out :+1: 


Sample Configuration for Mac OS X
---------------------------------

* Web Sharing must be [enabled](http://support.apple.com/kb/HT3323)
* Copy `index.html` to `/Library/WebServer/Documents/`
* Copy `latrine.php` to `/Library/WebServer/CGI-Executables/` and run `chmod +x latrine.php`
* Default storage location in `latrine.php` is just fine
* Adjust the location (URL) in `index.html` to `var phpScript = '/cgi-bin/latrine.php';`


Issues
------

* GSM_SIGNAL currently isn't sent correctly by locus (20171211)
