<!DOCTYPE html>
<html>
<head>

<title>D3.js examples - Deutschlandkarte</title>

<?php include_once('templates/header.php'); ?>

<!-- add the topojson library -->
<script type="text/javascript" src="http://d3js.org/topojson.v0.min.js"></script>

<style>
.subunit{fill:#fff;}
.subunit.Nordrhein-Westfalen{ fill: #aba; }
.subunit.Baden-Württemberg{ fill: #bab; }
.subunit.Hessen{ fill: #bcb; }
.subunit.Niedersachsen{ fill: #cbc; }
.subunit.Thüringen{ fill: #cdc; }
.subunit.Hamburg{ fill: #dcd; }
.subunit.Schleswig-Holstein{ fill: #ded; }
.subunit.Rheinland-Pfalz{ fill: #ede; }
.subunit.Saarland{ fill: #efe; }
.subunit.Sachsen-Anhalt{ fill: #fef; }
.subunit.Brandenburg{ fill: #aaa; }
.subunit.Mecklenburg-Vorpommern{ fill: #bbb; }
.subunit.Bayern { fill: #ccc; }
.subunit.Sachsen { fill: #ddd; }
.subunit.Bremen { fill: #eee; }
.subunit.Berlin { fill: #fff; }

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
</style>

<!-- load custom script which handles the application logic -->
<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	var _app = {
		'paper': null,
		'width': '100%',
		'height': '1400',
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
		var width = 1050,
	    	height = 1400;

		var path = d3.geo.path();

		d3.json("data/de.json", function(error, de) {

		    var subunits = topojson.object(de, de.objects.subunits);

		    var projection = d3.geo.mercator()
		        .center([10.5, 51.35])
		        .scale(5000)
		        .translate([width / 2, height / 2]);

		    var path = d3.geo.path()
		        .projection(projection)
		        .pointRadius(4);

		    _app.paper.append("path")
		        .datum(subunits)
		        .attr("d", path)

		    _app.paper.selectAll(".subunit")
		        .data(topojson.object(de, de.objects.subunits).geometries)
		      .enter().append("path")
		        .attr("class", function(d) { return "subunit " + d.properties.name; })
		        .attr("d", path)
		        .on("click", click);

		    function click(a){
		        console.log(a.properties.name);
		    	}

		    _app.paper.append("path")
		        .datum(topojson.mesh(de, de.objects.subunits, function(a,b) { if (a!==b || a.properties.name === "Berlin"|| a.properties.name === "Bremen"){var ret = a;}return ret;}))
		        .attr("d", path)
		        .attr("class", "subunit-boundary");

		    _app.paper.append("path")
		        .datum(topojson.object(de, de.objects.places))
		        .attr("d", path)
		        .attr("class", "place");

		    _app.paper.selectAll(".place-label")
		        .data(topojson.object(de, de.objects.places).geometries)
		      .enter().append("text")
		        .attr("class", "place-label")
		        .attr("transform", function(d) { return "translate(" + projection(d.coordinates) + ")"; })
		        .attr("dy", ".35em")
		        .text(function(d) { if (d.properties.name!=="Berlin"&&d.properties.name!=="Bremen"){return d.properties.name;} })
		        .attr("x", function(d) { return d.coordinates[0] > -1 ? 6 : -6; })
		        .style("text-anchor", function(d) { return d.coordinates[0] > -1 ? "start" : "end"; });

		    _app.paper.selectAll(".subunit-label")
		        .data(topojson.object(de, de.objects.subunits).geometries)
		      .enter().append("text")
		        .attr("class", function(d) { return "subunit-label " + d.properties.name; })
		        .attr("transform", function(d) { return "translate(" + path.centroid(d) + ")"; })
		        .attr("dy", function(d){ 
		        if(d.properties.name==="Sachsen"||d.properties.name==="Thüringen"||d.properties.name==="Sachsen-Anhalt"||d.properties.name==="Rheinland-Pfalz")
		            {return ".9em"}
		        else if(d.properties.name==="Brandenburg"||d.properties.name==="Hamburg")
		            {return "1.5em"}
		        else if(d.properties.name==="Berlin"||d.properties.name==="Bremen")
		            {return "-1em"}else{return ".35em"}})
		        .text(function(d) { return d.properties.name; });

		});
	}	

	//remove all circle elements from the paper
	function clearApp(){
		_app.paper.selectAll('circle').remove();
	}
	

	$('.render-btn').click(function(e){
		e.preventDefault();

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

<script type="text/javascript" src="http://yandex.st/highlightjs/7.0/highlight.min.js"></script>
<link rel="stylesheet" href="css/github.css" />

</head>

<body>
<div id="page">

	<?php include_once('templates/mainmenu.php'); ?>

	<h1 id="page-title">D3.js - Ein einfaches Beispiel</h1>
	<p id="page-meta"> geschrieben am 07.09.13 von <a href="http://tommykrueger.com" target="_blank">Tommy Krüger</a></p>

	<div id="description">
		<p>
		In diesem einfachen Beispiel wird gezeigt, wie man mit D3.js Kreise darstellen kann. Die Kreise haben hierbei zufällige Radien und Positionen.
		</p>
	</div>

	<div id="paper"></div>

	<?php include_once('templates/source.php'); ?>
</div>

<?php include_once('templates/footer.php'); ?>

</body>
</html>

