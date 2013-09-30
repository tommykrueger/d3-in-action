<!DOCTYPE html>
<html>
<head>
<title>D3.js - Simple Graph Example</title>

<?php include_once('templates/header.php'); ?>

<style>

.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
	}

.amount {
	fill-opacity: 0.4;
	}
.amount-berlin {
	fill: steelblue;
	}
.amount-germany {
	fill: red;
	}

.line {
	fill: none;
	stroke-width: 0.5px;
	stroke-dasharray: 6px;
	}
.line-berlin {
  stroke: steelblue;
	}
.line-germany {
  stroke: red;
	}

.labels-berlin,
.labels-germany {
	fill: steelblue;
	font-size: 0.7em;
	}
.labels-germany {
	fill: red;
	}

</style>

<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '100%',
		'height': '100%',

		//data object to hold after loading
		'data': null,
	};

	// render the svg
	_app.paper = d3.select("#paper")
		.append("svg:svg")
		.attr("width", _app.width)
		.attr("height", _app.height)
			.append("g")
			.attr("transform", "translate(48,72)");

	// render the app
	render();


	// main render function
	function render(){

		d3.json("data/unemploymentrate_berlin.json", function(error, unemploymentrates) {

			_app.data = unemploymentrates;

			var x = d3.scale.linear().domain([1996, 2012]).range([0, 740]);
			var y = d3.scale.linear().domain([0, 50]).range([400, 0]);

			var xAxis = d3.svg.axis()
				.scale(x)
				.orient('bottom')
				.ticks(16)
				.tickFormat(d3.format('.0f'));

			var yAxis = d3.svg.axis()
				.scale(y)
				.orient('left')
				.ticks(16);


			var lineBerlin = d3.svg.line()
				.interpolate("cardinal")
				.x(function(d) { return x(d[0]); })
				.y(function(d) { return y(d[1]); });

			var lineGermany = d3.svg.line()
				.interpolate("cardinal")
				.x(function(d) { return x(d[0]); })
				.y(function(d) { return y(d[2]); });

				x.domain(d3.extent(_app.data, function(d) { return d[0]; }));
				y.domain([0, d3.max(_app.data, function(d) { return d[1]; })]);

			_app.paper.append("g")
				.attr("class", "x axis")
				.attr("transform", "translate(48, 400)")
				.call(xAxis);

			_app.paper.append("g")
				.attr("class", "y axis")
				.attr("transform", "translate(24, 0)")
				.call(yAxis)
					.append("text")
					.attr("transform", "translate(-64, 0) rotate(-90)")
					.attr("y", 6)
					.attr("dy", ".71em")
					.style("text-anchor", "end")
					.text("Unemployment Rates (in % - Average of each year)");

			var unemploymentGraph = _app.paper.selectAll('.unemployment-graph')
				.data(_app.data[0])
				.enter()
				.append('g')
				.attr("class", "unemployment-graph");


			unemploymentGraph.append("path")
				.attr("class", "line line-berlin")
				.attr('transform', 'translate(48,0)')
				.attr("d", lineBerlin(_app.data));


			unemploymentGraph.append("path")
				.attr("class", "line line-germany")
				.attr('transform', 'translate(48,0)')
				.attr("d", lineGermany(_app.data));


			// append circles for berlin
			_app.paper
				.append('g')
				.attr('class', 'circles')
				.attr('transform', 'translate(48,0)')
				.selectAll('circle')
				.data(_app.data)
				.enter()
				.append('circle')
					.attr('class', 'amount amount-berlin')
					.attr('r', 4)
					.attr('cx', function(d){
						return x(d[0]);
					})
					.attr('cy', function(d){
						return y(d[1]);
					})

			// add the labels	for berlin	
			_app.paper
				.append('g')		
				.attr('class', 'labels-berlin')
				.attr('transform', 'translate(48,0)')
				.selectAll("text")
				.data(_app.data)
				.enter()
				.append('text')
					.attr('dx', function(d){ return x(d[0]) - 8; })
					.attr('dy', function(d){ return y(d[1]) - 20; })
					.text(function(d){
						return d[1] + '%';
					});


			// add the circles for germany
			_app.paper
				.append('g')
				.attr('class', 'circles')
				.attr('transform', 'translate(48,0)')
				.selectAll('circle')
				.data(_app.data)
				.enter()
				.append('circle')
					.attr('class', 'amount amount-germany')
					.attr('r', 4)
					.attr('cx', function(d){
						return x(d[0]);
					})
					.attr('cy', function(d){
						return y(d[2]);
					});

			// add the labels	for germany
			_app.paper
				.append('g')		
				.attr('class', 'labels-germany')
				.attr('transform', 'translate(48,0)')
				.selectAll("text")
				.data(_app.data)
				.enter()
				.append('text')
					.attr('dx', function(d){ return x(d[0]) - 8; })
					.attr('dy', function(d){ return y(d[2]) - 20; })
					.text(function(d){
						return d[2] + '%';
					});

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

	<h1 id="page-title">D3.js - A simple graph example</h1>
	<p id="page-meta"> written 09/08/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
		
		Average unemployment rates from Berlin (<span style="color: steelblue;">blue</span>) and 
		Germany (<span style="color: red;">red</span>) from 1995 to 2012. 
		The rates show the percentage value of people who are able to work.  
		In 2005 almost every 5th person (19%) in Berlin who was basically 
		able to work found himself unemployed.

		<br><br/>
		Data source: 
		<br/>
		Berlin:
		<a href="http://daten.berlin.de/datensaetze/arbeitslosenquote-berlin" target="_blank">
		http://daten.berlin.de/datensaetze/arbeitslosenquote-berlin
	</a>

		<br/>
		Germany:
		<a href="http://de.statista.com/statistik/daten/studie/1224/umfrage/arbeitslosenquote-in-deutschland-seit-1995/" target="_blank">
			http://de.statista.com/statistik/daten/studie/1224/umfrage/arbeitslosenquote-in-deutschland-seit-1995/
		</a>
		</p>
	</div>

	<div id="paper"></div>

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

</body>
</html>


