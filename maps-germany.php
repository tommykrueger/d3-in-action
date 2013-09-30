<!DOCTYPE html>
<html>
<head>
<title>D3.js - Germany Unemployment Rates</title>

<?php include_once('templates/header.php'); ?>

<!-- add the topojson library -->
<script type="text/javascript" src="http://d3js.org/topojson.v0.min.js"></script>

<script src="data/germany_countynames.js"></script>

<style>
.county{fill:#fff;}

.subunit-boundary {
  fill: none;
  stroke-width:1px;
  stroke: #777;
  stroke-dasharray: 2,2;
  stroke-linejoin: round;
}
.place,
.place-label {
  fill: #444;
  font-size:12px;
}
text {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 16px;
  pointer-events: none;
}
.subunit-label {
  fill: #777;
  fill-opacity: .5;
  font-size: 30px;
  font-weight: 200;
  text-anchor: middle;
}

.county {
	transition: all 150ms ease-in-out;
	fill: #fff;
	}
.county:hover {
	fill-opacity: 0.8;
	}
.city {
	/*fill: #ff6600;*/
	}

/* set unemployment colors */

.unemployment-1 { fill: rgb(255, 245, 245); }
.unemployment-2 { fill: rgb(255, 235, 235); }
.unemployment-3 { fill: rgb(255, 225, 225); }
.unemployment-4 { fill: rgb(255, 215, 215); }
.unemployment-5 { fill: rgb(255, 205, 205); }
.unemployment-6 { fill: rgb(255, 195, 195); }
.unemployment-7 { fill: rgb(255, 185, 185); }
.unemployment-8 { fill: rgb(255, 175, 175); }
.unemployment-9 { fill: rgb(255, 165, 165); }
.unemployment-10 { fill: rgb(255, 155, 155); }
.unemployment-11 { fill: rgb(255, 145, 145); }
.unemployment-12 { fill: rgb(255, 135, 135); }
.unemployment-13 { fill: rgb(255, 125, 125); }
.unemployment-14 { fill: rgb(255, 115, 115); }
.unemployment-15 { fill: rgb(255, 105, 105); }
.unemployment-16 { fill: rgb(255, 95, 95); }
.unemployment-17 { fill: rgb(255, 85, 85); }
.unemployment-18 { fill: rgb(255, 75, 75); }
.unemployment-19 { fill: rgb(255, 65, 65); }
.unemployment-20 { fill: rgb(255, 55, 55); }

</style>

<!-- load custom script which handles the application logic -->
<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '100%',
		'height': '1200',
		'randomRange': 300,

		//define radius to use
		'data': [10, 20, 50],

		'unemploymentRates': null,

		//colors: red / green / blue
		'colors': ['#ff0000', '#00ff00', '#0000ff']
	};

	// render the svg
	_app.paper = d3.select("#paper")
		.append("svg:svg")
		.attr("width", _app.width)
		.attr("height", _app.height);

	// render the app
	render();

	// append the circle elements at random positions
	function render(){
		var width = 1050,
	    	height = 900;

		var path = d3.geo.path();

		d3.json("data/unemployment_germany.json", function(error, unemploymentRates){
			_app.unemploymentRates = unemploymentRates;
		});


		d3.json("data/_germany_paths.json", function(error, de) {

		    var subunits = topojson.object(de, de.objects.germany);

		    var projection = d3.geo.mercator()
		        .center([11.5, 52.35])
		        .scale(5000)
		        .translate([width / 2, height / 2]);

		    var path = d3.geo.path()
		        .projection(projection)
		        .pointRadius(4);

		    _app.paper.append('path')
		        .datum(subunits)
		        .attr('d', path);

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

	//remove all circle elements from the paper
	function clearApp(){
		_app.paper.selectAll('circle').remove();
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

	<h1 id="page-title">D3.js - Unemployment Rate in Germany 2012</h1>
	<p id="page-meta">written 09/07/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
			This example shows the unemployment rate of German counties an cities. 
			The data is taken from the "Bundesagentur f&uuml;r Arbeit" and shows the averages of 2012.
			<br/>
			You can see a slighty difference between north and south German counties. In the north statistically more
			people are unemployed than in the south. The same is for west and east.
			Also urban areas have a higher unemployment rate than non urban areas.
			Unfortunately there aren't all counties available in the databse of the Bundesagentur f&uuml;r Arbeit, so some
			areas are white.
		</p>
	</div>

	<div id="paper"></div>

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

<div id="tooltip"></div>
</body>
</html>

