<!DOCTYPE html>
<html>
<head>
<title>D3.js - Performance Test</title>

<?php include_once('templates/header.php'); ?>

<!-- load performance measure library -->
<script type="text/javascript" src="js/stats.min.js"></script>

<style>

.circle { 
	fill: steelblue; 
	opacity: 0.4;
	}

.circle-1 { fill: steelblue; }
.circle-2 { fill: red; }
.circle-3 { fill: darkgreen; }

</style>

<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '760',
		'height': '500',

		// the amount of circle objects the app should have on startup
		'amount': 50,

		'getAmount': function(){
			_app.circles = [];

			for(var i=0; i<_app.amount;  i++){
				_app.circles.push(1);
			}
			return _app.circles;
		},

		'circles': [],

		'radius': 12
	};

	// add 500 circles on startup
	

	console.log(_app.circles);

	var stats = new Stats();
			stats.setMode(0);

	$("#frames-per-second").append(stats.domElement);


	// render the svg
	_app.paper = d3.select('#paper')
		.append('svg')
		.attr("width", _app.width)
		.attr("height", _app.height)
			.append("g")
    	.attr("transform", "translate(64,64)");

	// render the app
	render();

	function render(){

		// render the circles
		_app.paper
			.selectAll('.circle')
			.data( _app.getAmount )
			.enter()
				.append('circle')
				.attr('class', 'circle')
				.attr('r', _app.radius)
				.attr('cx', function(){ return getRandom(400); })
    		.attr('cy', function(){ return getRandom(400); });

	};

	function getRandom(size){
		return Math.floor(Math.random() * size);
	};

	function animateApp(){

    d3.selectAll('.circle')
      .transition()            
        .delay(0)            
        .duration(3000)
        .attr("r", _app.radius)
        .attr('cx', function(){ return getRandom(400) })
  			.attr('cy', function(){ return getRandom(400) })
        .each("end", animateApp);
	};

	$('.animate-btn').click(function(e){
		e.preventDefault();

		animateApp();
	});	

	$('.add-btn').click(function(e){
		e.preventDefault();

		_app.amount = parseInt($(this).attr('rel'));
		$('#paper').empty();

		_app.paper = d3.select('#paper')
		.append('svg')
		.attr("width", _app.width)
		.attr("height", _app.height)
			.append("g")
    	.attr("transform", "translate(64,64)");

		_app.getAmount;
		render();
	});	

	$('.clear-btn').click(function(e){
		e.preventDefault();
		$('#paper').empty();
	});	


	setInterval(function(){

	    stats.begin();

	    //$('#paper').empty();
	    render();

	    stats.end();

	}, 1000 / 60 );


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

	<h1 id="page-title">D3.js - Performance Test</h1>
	<p id="page-meta"> written 09/08/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
			D3.js rendering is based on SVG objects. 
			As SVG is not hardware accelerated there could be performance issues
			if an application uses too many SVG objects.
			<br/>
			This little test should give you the ability to test how many circle
			objects your machine supports until the animations aren't 
			smooth anymore or even your browser stops ?!
			Use the button below to add circles.

			<br><br/>
			This example uses Stats.js (<a href="https://github.com/mrdoob/stats.js" target="_blank">
			https://github.com/mrdoob/stats.js</a>) to measure the performance.
		</p>
	</div>

	<div id="paper"></div>
	<span class="btn animate-btn">Start Animation</span> 
	<span class="btn add-btn" rel="100">100 Circles</span> 
	<span class="btn add-btn" rel="200">200 Circles</span> 
	<span class="btn add-btn" rel="400">400 Circles</span> 
	<span class="btn add-btn" rel="800">800 Circles</span> 
	<span class="btn add-btn" rel="1600">1600 Circles</span> 
	<span class="btn add-btn" rel="3200">3200 Circles</span> 
	<span class="btn add-btn" rel="6400">6400 Circles</span> 
	<span class="btn add-btn" rel="12800">12800 Circles</span>
	<span class="btn clear-btn">Clear</span>

	<span id="frames-per-second"></span>

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

</body>
</html>


