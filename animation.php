<!DOCTYPE html>
<html>
<head>
<title>D3.js - A Simple Animation</title>

<?php include_once('templates/header.php'); ?>

<style>

.circle { fill: steelblue; }
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
		'data': [3, 6, 9]
	};

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
			.data(_app.data)
			.enter()
				.append('circle')
				.attr('class', function(d, i){ return 'circle circle-' + (i+1); })
				.attr('r', function(){ return getRandom(50); })
				.attr('cx', function(){ return getRandom(400); })
    		.attr('cy', function(){ return getRandom(400); });

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

	<h1 id="page-title">D3.js - Simple Animation</h1>
	<p id="page-meta"> written 09/08/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
			D3.js offers a basic implementation to shapes. 
			Simple animations can be created with CSS3 transitions. 
			D3.js also has the function to chain animations in order
			to create a endles animation like you can see here.
			Just click the start animation button to go on.
		</p>
	</div>

	<div id="paper"></div>
	<span class="btn animate-btn">Start Animation</span> 

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

</body>
</html>


