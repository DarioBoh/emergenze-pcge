<?php 


?>

<style>
.svg-container {
    display: inline-block;
    position: relative;
    width: 100%;
    padding-bottom: 100%;
    vertical-align: top;
    overflow: hidden;
}
.svg-content {
    display: inline-block;
    position: absolute;
    top: 0;
    left: 0;
}
</style>


<script>


var svg = d3.select("svg"),
    margin = {top: 30, right: 35, bottom: 10, left: 30},
    width = +svg.attr("width") - margin.left - margin.right,
    height = +svg.attr("height") - margin.top - margin.bottom,
    g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

var x0 = d3.scaleBand()
    .rangeRound([0, width])
    .paddingInner(0.1);

function readJSON(file) {
    var request = new XMLHttpRequest();
    request.open('GET', file, false);
    request.send(null);
    if (request.status == 200)
        return request.responseText;
};

var data = JSON.parse(readJSON('./tables/griglia_segnalazioni_conteggi.php?id=<?php echo str_replace("'","",$id);?>'));

var keys = ["pervenute", "risolte"]

x0.domain(data.map(function(d) { return d.criticita; }));
		
var gXAxis = g.append("g")
	.attr("class", "axis")
	.call(d3.axisBottom(x0).ticks(10));
gXAxis.selectAll("text")
	.style("text-anchor", "end")
	.attr("dx", "-.8em")
	.attr("dy", ".15em")
	.attr("transform", "rotate(-55)");
	
// Find the maxLabel height, adjust the height accordingly and transform the x axis.
var maxWidth = 0;
gXAxis.selectAll("text").each(function () {
	var boxWidth = this.getBBox().width;
	if (boxWidth > maxWidth) maxWidth = boxWidth;
});
height = height - maxWidth;

gXAxis.attr("transform", "translate(0," + height + ")");

var x1 = d3.scaleBand()
    .padding(0.05);
	
var y = d3.scaleLinear()
    .rangeRound([height, 0]);

var z = d3.scaleOrdinal()
    .range(["#ffcc00", "#007c37"]);

x1.domain(keys).rangeRound([0, x0.bandwidth()]);
  y.domain([0, d3.max(data, function(d) { return d3.max(keys, function(key) { return d[key]; }); })]).nice();


g.append("g")
  .attr("class", "axis")
  .call(d3.axisLeft(y).ticks(1, "s"))
.append("text")
  .attr("x", 2)
  .attr("y", y(y.ticks().pop()) + 0.5)
  .attr("dy", "0.32em")
  .attr("fill", "#000")
  .attr("font-weight", "bold")
  .attr("text-anchor", "start")
  //.text("N");



g.append("g")
    .selectAll("g")
    .data(data)
    .enter().append("g")
      .attr("transform", function(d) { return "translate(" + x0(d.criticita) + ",0)"; })
    .selectAll("rect")
    .data(function(d) { return keys.map(function(key) { return {key: key, value: d[key]}; }); })
    .enter().append("rect")
      .attr("x", function(d) { return x1(d.key); })
      .attr("y", function(d) { return y(d.value); })
      .attr("width", x1.bandwidth())
      .attr("height", function(d) { return height - y(d.value); })
      .attr("fill", function(d) { return z(d.key); });

  var legend = g.append("g")
      .attr("font-family", "sans-serif")
      .attr("font-size", 10)
      .attr("text-anchor", "end")
    .selectAll("g")
    .data(keys.slice())
    .enter().append("g")
      .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });

  legend.append("rect")
      .attr("x", width)
	  .attr("y", -30)
      .attr("width", 19)
      .attr("height", 19)
      .attr("fill", z);

  legend.append("text")
      .attr("x", width - 5 )
      .attr("y", -20.5)
      .attr("dy", "0.32em")
      .text(function(d) { return d; });

</script>


<?php 




?>