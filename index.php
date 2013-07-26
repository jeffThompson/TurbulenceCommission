<!DOCTYPE html>
<html lang="en">
	<head>
	
		<!-- 
			PHOTOGRAPHS > 123D CATCH > 3D MODEL
			Jeff Thompson | 2013 | www.jeffreythompson.org
			
			Created with generous funding from Turbulence.org
			
			TO DO:
			+ download link to Github repo?
			+ arrow keys to advance models
			+ fixed-width for rotate link (so "download" doesn't move back and forth)?
			+ set auto-rotate to true when new model is loaded? or preserve whatever was for previous...
			+ load files from dir with PHP?
			+ clicking also resets the timer for fadeout
			         
			+ main page with tech details (FF or Chrome, etc)
			+ add instructions for Safari users:
					Mac Safari users, WebGL is disabled by Default (the Windows version of Safari does not yet support WebGL) 
  	    	(1) Open Safari and in the Safari menu select Preferences
    	    (2) Click Advanced tab in the Preferences window
      	  (3) At the bottom of the window check the "Show Develop menu in menu bar" checkbox
        	(4) Open the Develop menu in the menu bar and select Enable WebGL
			
		-->
	
		<title>3d Test - Load STL, Rotate, Interaction</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<style>
			body {
				background-color: #aaaaaa;
				margin: 0;
				padding: 0;
				overflow: hidden;
			}
			a, a:visited {
				color: rgb(200,200,200);
				text-decoration: none;
			}
			a:hover, a:active {
				color: rgb(255,150,0);
				text-decoration: none;
			}
			
			#info {
				top: 0px;
				position: absolute;
				width: 100%;
				margin: 20px;
				font-size: 12px;
				font-family: Helvetica, Arial, sans-serif;
			}
			#modelFilename {
				color: white;
				font-size: 18px;
			}
			#commands {
				margin-top: -8px;
				color: rgb(150,150,150);
			}
			#instructions {
				color: rgb(100,100,100);
			}
			
			#arrow-left, #arrow-right {
				position: absolute;
				opacity: 0.3;
				top: 50%;
			}
			#arrow-left {
				left: 20px;
			}
			#arrow-right {
				right: 20px;
			}
			#arrow-left:hover, #arrow-right:hover {
				opacity: 1.0;
			}
			
			#loadingAnimation {
				margin: 0 auto 0 auto;
				top: 50%;
			}
		</style>
		
		<!-- load all files in the 'models' folder into an array, store as JSON -->
		<?php
			$files = array();
			foreach (glob("models/*.stl") as $filename) {

				# get filesize in proper format
				# via: http://stackoverflow.com/a/5501447/1167783
				$size = filesize($filename);
				if ($size >= 1048576) $size = number_format($size / 1048576, 2) . ' MB';
				else if ($size >= 1024) $size = number_format($size / 1024, 2) . ' kb';
				else $size = $size . ' bytes';
				
				# add to array
				$files[] = array("filename" => basename($filename), "filesize" => $size);
			}
			
			# encode to JSON for our Javascript to read later
			$files = json_encode($files);
		?>
		<script>
			// via: http://www.dyn-web.com/tutorials/php-js/json.php
			var modelFilenames = new Array();												// empty array for filenames
			var filesJSON = JSON.parse('<?php echo $files ?>');			// get from JSON, store as dict
			for (i in filesJSON) {																	// iterate listings, store in array
				modelFilenames.push(filesJSON[i].filename);						// append to list of files
			}
		</script>
		
		<script>			
			// model setup variables (here for convenience)
			var scale = 0.5;													// % to scale model (1.0 = normal size, 0.5 = 50%)
			var objectColor = 0xcccccc;
			var objectShadowColor = 0x000000;
			var objectReflectionColor = 0x444444;
			var backgroundColor = 0x111111;
			var statistics = false;										// show FPS
			var rotateModel = true;										// rotate model automatically? toggle with link
			var fadeInTime = 500;											// time to fade back in (ms)
			var fadeOutTime = 1000;										// ditto fade out										
			var fadeTimer = 5000;											// how long to wait until fading out (ms)
			var whichModel = 0;												// index to access filenames from array
		</script>
		
		<!-- load three.js and jquery -->
		<script src="js/three.min.js"></script>
		<script src="js/STLLoader.js"></script>
		<script src="js/TrackballControls.js"></script>
		<script src="js/Detector.js"></script>
		<script src="js/stats.min.js"></script>
		<script src="js/jquery-1.10.2.min.js"></script>
		
		<!-- hide info when not active -->
		<script>
		// via: http://stackoverflow.com/a/15532514/1167783
		$(function() {
			var timer;
			var fadeInBuffer = false;
			$(document).mousemove(function() {
				if (!fadeInBuffer) {
					if (timer) {
						clearTimeout(timer);
						timer = 0;
					}
					$('#info').fadeIn(fadeInTime);
					$('#arrow-left').fadeIn(fadeInTime);
					$('#arrow-right').fadeIn(fadeInTime);
					$('html').css({
							cursor: ''
					});
				} 
				else {
					fadeInBuffer = false;
				}
				
				timer = setTimeout(function() {
					$('#info').fadeOut(fadeOutTime);
					$('#arrow-left').fadeOut(fadeOutTime);
					$('#arrow-right').fadeOut(fadeOutTime);
					$('html').css({
						cursor: 'none'
					});
					fadeInBuffer = true
				}, fadeTimer)
			});			
		});
		
		// arrow keys to advance
		// cross-browser solution via: http://stackoverflow.com/a/6011119
		$(document).keydown(function(e) {
			switch(e.which) {
				case 37:						// L
					prevModel();
					break;
				case 39:						// R
					nextModel();
					break;
				case 38: break;			// skip U/D because they do weird things in FF
				case 40: break;
				default: return;		// skip all other keys
			}
			e.preventDefault();
		});
		</script>	
	</head>
	
	<body>
	
		<!-- display info/instructions -->
		<div id="info">
			<p id="modelFilename">TEST.STL</p>
			<p id="commands">3,855 photographs&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a id="rotate" href="javascript:void(0)" onclick="toggleModelRotation()">pause</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a id="download" href="#">download</a></p>
			<p id="instructions">click-and-drag / scrollwheel to interact</p>
		</div>
		
		<!-- arrows -->
		<img id="arrow-left" src="images/slideshowArrow-left.png" onclick="prevModel()">
		<img id="arrow-right" src="images/slideshowArrow-right.png" onclick="nextModel()">
				
		<!-- display 3d model -->
		<script src="js/LoadAndDisplaySTLFile.js"></script>
		
		<!-- loading animation (hidden except when loading new model -->
		<!-- <img id="loadingAnimation" src="images/loading.gif"> -->
		
	</body>
</html>
