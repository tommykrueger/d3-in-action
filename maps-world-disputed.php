<!DOCTYPE html>
<html>
<head>
<title>D3.js - World Disputed Regions</title>

<?php include_once('templates/header.php'); ?>

<!-- add the topojson library -->
<script type="text/javascript" src="http://d3js.org/topojson.v0.min.js"></script>

<style>

.country {
	transition: all 150ms ease-in-out;
	fill: #e0e0e0;
	stroke: #555;
	stroke-width: 0.2px;
	stroke-dasharray: 0.5px;
	}
.country:hover {
	opacity: 0.8;
	}

.disputed-area {
	fill: #ff0000;
	}

</style>

<!-- load custom script which handles the application logic -->
<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '100%',
		'height': '1200'
	};

	// render the svg
	_app.paper = d3.select("#paper")
		.append("svg")
		.attr("width", _app.width)
		.attr("height", _app.height);

	_app.plot = _app.paper.append('g').attr('class', 'plot');
	_app.countries = _app.plot.append('g').attr('class', 'countries');
	_app.disputed = _app.plot.append('g').attr('class', 'disputed-areas');

	// render the app
	render();

	// append the circle elements at random positions
	function render(){
		var width = 960,
	    	height = 800;

		var path = d3.geo.path();

		d3.json("data/world_disputed.json", function(error, de) {

	    var projection = d3.geo.mercator()
	        .center([0, 52.35])
	        .scale(200)
	        .translate([width / 2, height / 2]);

	    var path = d3.geo.path()
	        .projection(projection)
	        .pointRadius(4);

	    _app.countries
	    	.selectAll('path')
	    	.data(topojson.object(de, de.objects.world).geometries)
	    	.enter()
	    	.append('path')
	      .attr('d', path)
	      .attr('class', 'country');

	   	_app.disputed
	    	.selectAll('path')
	    	.data(topojson.object(de, de.objects.disputed).geometries)
	    	.enter()
	    	.append('path')
	      .attr('d', path)
	      .attr('class', 'disputed-area')
	      .on('mouseenter', function(area, idx){
	      	console.log(area);
	      });

     	_app.zoom = d3.behavior.zoom().scaleExtent([-100,100]).on('zoom', redraw);
		  _app.paper.call(_app.zoom); 

		  function redraw(){

		    var translation = d3.event.translate,
		        newx = translation[0];
		        newy = translation[1];

		    _app.plot
		      .transition()
		      .attr('transform', "translate("+ newx +", "+ newy +")" + " scale(" + d3.event.scale + ")");
		  };

		     /*
		    _app.paper.selectAll('.county')
		        .data(topojson.object(de, de.objects.germany).geometries)
		      .enter().append('path')
	      	.attr('id', function(d, idx){
	        	return counties[idx];
	        })
	        .attr("class", function(d, idx) { 
	        	var city = '';

	        	var countyName = d3.select(this).attr('id');

	     			//console.log(countyName);

	        	if(counties[idx].toString().indexOf('Stadt') != -1)
	        		city = 'city ';

	        	var color = '';
	        	_.each(_app.unemploymentRates, function(rate, idx){
							countyName = countyName.replace('Stadt', '');

							if(_.isEqual(countyName, rate.region)){

								var percent = rate.percent.replace(',', '.');
								color = ' unemployment-' + Math.round(percent) + ' amount-' + percent;
							}
						});

	        	return 'county ' + city + color; 
	        })
	        .attr("d", path)
	        .on("click", click)
	        .on('mouseenter', provinceHover)
	        .on('mouseleave', provinceLeave);

				*/
		    function click(a){
		        //console.log(a);
		    }

		    function provinceHover(province){
		    	var classes = d3.select(this).attr('class');
		    		classes = classes.split(' ');

		    	var percent = '-';
		    	
		    	_.each(classes, function(c, idx){
		    		if(c.toString().indexOf('amount') != -1) {
		    			percent = c.replace('amount-', '');
		    		}	
		    	})

		    	$('#tooltip').text( d3.select(this).attr('id') + ': Arbeitslosenquote ' + percent + '%');

		    	$('#tooltip').css({
		    		'left': d3.event.pageX + 24,
		    		'top': d3.event.pageY + 24
		    	});
		    	$('#tooltip').stop().fadeIn();
		    }

		    function provinceLeave(province){
		    	$('#tooltip').stop().fadeOut();
		    }

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

	<h1 id="page-title">D3.js -World Disputed Regions</h1>
	<p id="page-meta">written 09/28/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
			In this worldmap are the disputed regions (red) which are claimed by more than one country.
		</p>
	</div>

	<div id="paper"></div>

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

<div id="tooltip"></div>
</body>
</html>

