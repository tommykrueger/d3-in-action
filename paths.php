<!DOCTYPE html>
<html>
<head>
<title>D3.js - SVG Path Objects</title>

<?php include_once('templates/header.php'); ?>

<style>

.country { 
	fill: steelblue;
	stroke: #676767; 
	fill-opacity: 0.2;
	}
.border { 
	fill: none; 
	stroke: #900000;
	stroke-width: 1px;
	}


</style>

<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '760',
		'height': '700',

		'data': null,
		'dataBorders': null
	};

	// render the svg
	_app.paper = d3.select('#paper')
		.append('svg')
		.attr("width", _app.width)
		.attr("height", _app.height)
		.attr('class', 'land-glow')
			.append("g")
    	.attr("transform", "translate(64,64)");

	// render the app
	render();

	function render(){

		d3.json('data/provinces.json', function(error, data) {

			_app.data = data;

			_.each(_app.data, function(object, idx){

				var country = _app.paper
						.append('path')
            .attr('id', object.id)
            .attr('class', 'country')
            .attr('d', object.path)
            .attr('title', object.id);
			});
			
		});

		// load the borders
		d3.json('data/borders.json', function(error, data) {

			_app.dataBorders = data;

			_.each(_app.dataBorders, function(object, idx){

				var country = _app.paper
						.append('path')
            .attr('id', object.id)
            .attr('class', 'border')
            .attr('d', object.path)
            .attr('title', object.id);
			});
			
		});

	};

	function getRandom(size){
		return Math.floor(Math.random() * size);
	};

	function animateApp(){

    d3.selectAll('.circle')
      .transition()            
        .delay(1000)            
        .duration(3500)
        .attr("r", function(){ return getRandom(50); })
        .attr('cx', function(){ return getRandom(400) })
  			.attr('cy', function(){ return getRandom(400) })
        .each("end", animateApp);
	};

	$('.animate-btn').click(function(e){
		e.preventDefault();

		animateApp();
	});	

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

	<h1 id="page-title">D3.js - SVG Path Objects</h1>
	<p id="page-meta"> written 09/10/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
			Let's say we have a set of complex SVG paths like, for example the borders of a country.
			In this example we have the UK and Ireland as two paths. 
			Ireland and UK have a border on the the Irish island

		</p>
	</div>

	<div id="paper"></div>

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>
</body>
</html>


