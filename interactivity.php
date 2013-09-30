<!DOCTYPE html>
<html>
<head>
<title>D3.js - Interactivity</title>

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
		'height': '400',

		'data': null,
		'dataBorders': null,

		'hit-count': 0,
		'seconds': 60
	};

	// render the svg
	_app.paper = d3.select('#paper')
		.append('svg')
		.attr("width", _app.width)
		.attr("height", _app.height)
		.attr('class', 'land-glow')
			.append("g")
    	.attr("transform", "translate(64,64)");


	function render(){

		// create random circles every second with different radius and display time

		var appInterval = setInterval(function(){

			// check if seconds are 0
			if(_app.seconds == 0){
				clearInterval(appInterval);

				_app.seconds = 60;

				var hit_count = parseInt($('#hit-count').text());
				var amount = parseInt($('#amount').text());
				var percent = Math.round(hit_count * 100 / amount);

				alert( 
					'Congratulations! \n You got ' 
					+ hit_count
					+ ' hits'
					+ ' out of ' + amount + ' circles, which means '
					+ '\n\n'+ percent + '%'
				);
			}

			var circle = _app.paper
				.append('circle')
				.attr('r', function(){ return getRandom(40) + 3; })
				.attr('cx', function(){ return getRandom( _app.width - 40 ); })
				.attr('cy', function(){ return getRandom( _app.height - 40 ); })
				.attr('fill', 'red')
				.on('click', function(d, i){

					_app['hit-count']++;
					$('#hit-count').text( _app['hit-count'] );
					d3.select(this).remove();
				})
				.transition()
				.duration(0)
				.delay( (getRandom(1400) + 600 ) )
				.remove();

			$('#amount').text( parseInt($('#amount').text()) + 1 );

			$('#seconds').text( _app.seconds-- );

		}, 1000);
	};

	function getRandom(size){
		return Math.floor(Math.random() * size);
	};

	$('.start-btn').click(function(e){
		e.preventDefault();
		
		_app.seconds--
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

	<h1 id="page-title">D3.js - Interactivity</h1>
	<p id="page-meta"> written 09/10/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
			This example shows a basic implementation of D3s interaction capabilities.
			Try to click the as many circles you can get within a secons, 
			but be carefull, they will disappear fast.
		</p>
	</div>

	Seconds: <span id="seconds">60</span>
	<br/>
	Hit Count: <span id="hit-count">0</span>
	<br/>
	Amount: <span id="amount">0</span>

	<div id="paper"></div>
	<span class="btn start-btn">Start</span> 

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

</body>
</html>


