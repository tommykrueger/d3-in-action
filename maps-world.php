<!DOCTYPE html>
<html>
<head>
<title>D3.js - Basic Worldmap Example</title>

<?php include_once('templates/header.php'); ?>

<!-- add the topojson library -->
<script type="text/javascript" src="http://d3js.org/topojson.v0.min.js"></script>

<script src="data/germany_countynames.js"></script>

<style>

/* set country colors */

/*
.country-0 { fill: rgb(0, 0, 255); }
.country-1 { fill: rgb(128, 128, 255); }
.country-2 { fill: rgb(0, 0, 128); }
.country-3 { fill: rgb(0, 128, 128); }
.country-4 { fill: rgb(128, 128, 128); }
.country-5 { fill: rgb(255, 205, 205); }
.country-6 { fill: rgb(255, 195, 195); }
.country-7 { fill: rgb(255, 185, 185); }
.country-8 { fill: rgb(255, 175, 175); }
.country-9 { fill: rgb(255, 165, 165); }
.country-10 { fill: rgb(255, 155, 155); }
.country-11 { fill: rgb(255, 145, 145); }
.country-12 { fill: rgb(255, 135, 135); }
.country-13 { fill: rgb(255, 125, 125); }
.country-14 { fill: rgb(255, 115, 115); }
.country-15 { fill: rgb(255, 105, 105); }
.country-16 { fill: rgb(255, 95, 95); }
.country-17 { fill: rgb(255, 85, 85); }
.country-18 { fill: rgb(255, 75, 75); }
.country-19 { fill: rgb(255, 65, 65); }
.country-20 { fill: rgb(255, 55, 55); }
*/

.country {
	fill: #b0b0b0;
	transition: all 250ms ease-in-out;
	}
.country:hover {
	opacity: 0.85;
	}

</style>

<!-- load custom script which handles the application logic -->
<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '100%',
		'height': '1200',
		'zoom': null
	};

	// render the svg
	_app.paper = d3.select("#paper")
		.append("svg")
		.attr("width", _app.width)
		.attr("height", _app.height)
			.append('g')
			.attr('class', 'plot');

	// render the app
	render();

	// append the circle elements at random positions
	function render(){

		d3.json("data/worldmap.json", function(error, worldmap) {

	    _app.paper
	    	.selectAll('path')
        .data(worldmap)
        .enter()
        .append('path')
        .attr('class', function(){
        	var className = 'country country-' + getRandom(20);
        	return className;
        })
        .attr('d', function(path){
        	return path.d;
        });

		    
		    function getRandom(size){
					return Math.floor(Math.random() * size);
				};

		    _app.zoom = d3.behavior.zoom().scaleExtent([-100,100]).on('zoom', redraw);
			  _app.paper.call(_app.zoom); 

			  function redraw(){

			    var translation = d3.event.translate,
			        newx = translation[0];
			        newy = translation[1];

			    _app.paper
			      .transition()
			      .attr('transform', "translate("+ newx +", "+ newy +")" + " scale(" + d3.event.scale + ")")
			      .duration(350);
			  };

		});
	}	

	// get own page source
	$.ajax(window.location.href, {
  		success: function (data) {

    		// display current page source on website
    		$('#source').text( data );
    		$('pre code').each(function(i, e) {hljs.highlightBlock(e)});
  		}
	});

});

</script>

</head>

<body>
<div id="page">

	<?php include_once('templates/mainmenu.php'); ?>

	<h1 id="page-title">D3.js - Basic Worldmap Example</h1>
	<p id="page-meta">written 09/07/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
			Basic worldmap projection shows world countries and islands as SVG path objects based on JSON Data.
			The file size has around 3.2MB and was shrinked from 5.6MB original. The wikimedia map is already a simplyfied version of the worldmap.
			<span style="color: red;">
				<br/>
				<strong>Attention:</strong>
				<br/>
				The given file is about 3 MB large. The file is taken from wikimedia directory:  
				<a href="http://upload.wikimedia.org/wikipedia/commons/5/55/Blank_map_world_gmt_%28more_simplified%29.svg" target="_blank">
					http://upload.wikimedia.org/wikipedia/commons/5/55/Blank_map_world_gmt_%28more_simplified%29.svg
				</a>
			</span>
		</p>
	</div>

	<div id="paper"></div>

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

<div id="tooltip"></div>
</body>
</html>

