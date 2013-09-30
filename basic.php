<!DOCTYPE html>
<html>
<head>
<title>D3.js - A Basic example</title>

<?php include_once('templates/header.php'); ?>

<!-- load custom script which handles the application logic -->
<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '100%',
		'height': '400',
		'randomRange': 300,

		//define radius to use
		'data': [10, 20, 50],

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
		
		_app.paper.selectAll('circle')
		.data(_app.data)
		.enter()
		.append('circle')
			.attr('class', 'circle')
			.attr('r', function(radius){
				return radius;
			})	
			.attr('cx', function(radius){
				return Math.random() * _app.randomRange;
			})
			.attr('cy', function(radius){
				return Math.random() * _app.randomRange;
			})
			.attr('fill', function(radius, idx){
				return _app.colors[idx];
			});
	}	

	//remove all circle elements from the paper
	function clearApp(){
		_app.paper.selectAll('circle').remove();
	}
	

	$('.render-btn').click(function(e){
		e.preventDefault();
		console.log('render app');

		clearApp();
		render();
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

	<h1 id="page-title">D3.js - A basic example</h1>
	<p id="page-meta"> written 09/07/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
			This example shows a very simple example how to use D3.js. There are three circles which have a random radius and positions.  <br/>
			If you click the render button the values will be set randomly again.
		</p>
	</div>

	<div id="paper"></div>
	<span class="btn render-btn">Render</span> 

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

</body>
</html>


