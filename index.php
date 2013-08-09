<!DOCTYPE html>
<html lang="en">
	<head>
	
		<!-- 
			PHOTOGRAPHS > 123D CATCH > 3D MODEL
			Jeff Thompson | 2013 | www.jeffreythompson.org
			
			Created with generous funding from Turbulence.org
			
			TO DO:
			
		-->
	
		<title>Photographs >> 3d Models | Jeff Thompson</title>
		<meta charset="utf-8">
		<!-- <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"> -->
		<link rel="stylesheet" type="text/css" href="stylesheet.css">	

		<!-- import jquery -->
		<script src="js/jquery-1.10.2.min.js"></script>
	</head>
	
	<body>
	
		<div id="intro">
			<h1>EVERY PHOTOGRAPH I EVERY TOOK, CONVERTED TO 3D MODELS</h1>
			<h2>Jeff Thompson&nbsp;&nbsp;|&nbsp;&nbsp;2013</h2>

			<p>Created with generous funding from <a href="http://www.turbulence.org">Turbulence.org</a></p>
			
			<p>My entire photo library (from 2002-2013) is interpolated, placed in a virtual 3d space, and uploaded to AutoDesk's 123D Catch software. Returned is a 3d model of what the software thinks the pictures are.</p>

			<p>This site requires JavaScript, and runs best in Chrome or Firefox. <a id="toggleDiv" href="javascript:void(0)" onclick="toggleVisibility()">Safari users will need to follow these instructions to enable WebGL.</p>
		</div> <!-- end intro -->
		
		<!-- popup for Safari users -->
		<div id="safariInstructions">
			<h1>INSTRUCTIONS FOR SAFARI USERS</h1>
			<p>Safari, by default, disables WebGL, the 3d engine used in this project.</p>
			<p>Mac users:</p>
			<ol>
				<li>In the "Safari" menu, select "Preferences"...</li>
				<li>Click the "Advanced" tab</li>
				<li>At the bottom of the window, check the "Show Develop menu in menu bar" checkbox</li>
				<li>Close the preferences</li>
				<li>In the Menu > Develop select "Enable WebGL"</li>
			</ol>
			<p>PC users: sadly, WebGL is not supported in Safari for Windows; please try Chrome or Firefox</p>
		</div> <!-- end safariInstructions -->
		<script>
			$(function toggleVisibility() {
				$('#toggleDiv').click(function() {
					$('#safariInstructions').toggle();
				});
			});
		</script>

	</body>
</html>
