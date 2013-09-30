<!DOCTYPE html>
<html>
<head>
<title>D3.js - A Simple Path Animation</title>

<?php include_once('templates/header.php'); ?>

<style>

.path { 
  fill: none;
  stroke: steelblue; 
  stroke-width: 1px; 
  /*stroke-dasharray: 6; */
  }

.animated-object {
  fill: #ff0000;
  }

</style>

<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '760',
		'height': '500',
    'path': null
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
		_app.path = _app.paper
			.append('path')
			.attr('class', 'path')

      /*
      .attr('d', 'M48.101,68.33C90,35.369,99.318,115.644,144.749,144.866c111.173,71.508,135.754-64.246,218.995-93.854c37.619-13.381,2.299,91.346,122.347,101.117c48.044,3.911-56.983,189.944-108.939,168.157c-0.477-0.2-179.988-62.742-180.446-62.57c-53.631-22.346-154.291,107.759-49.721,99.441C196.146,353.246-18.784,120.945,48.101,68.33z');
      */

			.attr('d', 'M48.101,68.33c2.804-2.692,51.217,47.314,96.648,76.536c111.173,71.508,135.754-64.246,218.995-93.854c37.619-13.381,2.299,91.346,122.347,101.117c48.044,3.911-56.983,189.944-108.939,168.157c-0.477-0.2-179.988-62.742-180.446-62.57c-53.631-22.346-154.291,107.759-49.721,99.441c49.162-3.91-118.436-149.721-112.849-234.637');
      
    

	};

	function getRandom(size){
		return Math.floor(Math.random() * size);
	};

	function animateApp(){

    // add animation
    var pathNode = _app.path.node();
    var pathLength = pathNode.getTotalLength();

    // create animation object and place it to the start of the path
    var animatedObject = 
      _app.paper.append('circle')
        .attr('class', 'animated-object')
        .attr('r', 5)
        .attr({
            transform: function () {
                var p = pathNode.getPointAtLength(0);
                return "translate(" + [p.x, p.y] + ")";
            }
        });

    animatedObject
      .transition()
      .duration( getRandom(50000) )
      .ease("linear")
      .attrTween("transform", function (d, i) {
          return function (t) {
              var p = pathNode.getPointAtLength(pathLength * t);
              return "translate(" + [p.x, p.y] + ")";
          }
      })
      .each('end', function(){

        d3.select( this )
          .transition()
          .duration(600)
          .attr('r', 32)
          .style('opacity', 0.0)
          .remove();   
      });
	};

  function getRandom(size){
    return Math.floor(Math.random() * size);
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

	<h1 id="page-title">D3.js - Simple Path Animation</h1>
	<p id="page-meta"> written 09/08/13 by <a href="http://tommykrueger.com" target="_blank">Tommy Kr&uuml;ger</a></p>

	<div id="description">
		<p>
      This is a basic example on how to move an element along a path.
			Imagine you want to animate a ship along a sea line or a plane along a route.
		</p>
	</div>

	<div id="paper"></div>
	<span class="btn animate-btn">Start Animation</span> 

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

</body>
</html>


