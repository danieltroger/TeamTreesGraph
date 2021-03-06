<!DOCTYPE HTML>
<html>

<head>
  <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
  <meta content="utf-8" http-equiv="encoding">
  <meta name="viewport" content="width=device-width">
  <link href="https://fonts.googleapis.com/css?family=Ubuntu|Ubuntu+Mono&display=swap" rel="stylesheet">
  <title>TeamTrees donated graph</title>

  <style type="text/css">
    @media screen and (min-width: 1920px) {
      div, p, ul, li {
         font-size: 12pt;
      }
    }
    body {
      font-size: 1.7vh;
      font-family: 'Ubuntu', sans-serif;
      background: rgba(0,0,0,0.9);
      color: white;
    }
    a{color:blue;text-decoration: none}
    a:hover{text-decoration: underline}
    .vis-data-axis .vis-y-axis.vis-major {
      color: white !important;
    }
    .vis-data-axis .vis-y-axis.vis-minor {
      color: rgba(255,255,255,0.6) !important;
    }
    .vis-time-axis .vis-text {
      color: white !important;
    }
    .vis-panel.vis-background.vis-horizontal .vis-grid.vis-minor {
      border-color: rgba(255,255,255,0.6) !important;
    }
    .vis-panel.vis-background.vis-horizontal .vis-grid.vis-major {
      border-color: white !important;
    }
    a{color:hsl(232,69%,69%);text-decoration: none}
    a:hover{text-decoration: underline}
    h3{color:white;text-decoration: underline;}
    h3:hover{text-decoration: none;}
    .vis-graph-group0 {
      fill: #0074ff !important;
    }
  </style>

  <script src="https://visjs.github.io/vis-timeline/dist/vis-timeline-graph2d.min.js"></script>
  <link href="https://visjs.github.io/vis-timeline/dist/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="darkmode">
<h3 style="font-size: 1.2em;">Tree count graph of #TeamTrees.</h3>
<div id="visualization" style="max-height: 90vh;"></div>

<p>
  Chart shows the number of trees donated to the <a href="https://teamtrees.org" target="_blank">#TeamTrees</a> initiative (y-axis) plotted over time (x-axis). And because one tree conveniently is $1, the y-axis also represents the amount of money donated so far.<br />
  Data is captured every 10s and updated live in this view. Use scrollwheel or pinch to zoom, click+drag to move.
</p>
<p style="font-family: Ubuntu Mono;">
  Thanks to:
  <ul style="font-family: Ubuntu Mono;">
    <li><a href="https://www.reddit.com/user/peterlip" target="_blank">/u/peterlip</a> for providing additional data points before I started capturing (sourced from Waybackmachine, Mark Rober, Mr.Beast, Arborday, Whatsinside, MinuteEarth, TeamTreesOfficial).</li>
    <li>Everybody who donated, as well as the initiators.</li>
    <li>TeamTrees for not complaining that I've been requesting their website every 10s.</li>
  </ul>
</p>

<script type="text/javascript">
  // create a graph2d with an (currently empty) dataset
  var container = document.getElementById('visualization');
  var items = [
  <?php
  $src = file("/var/www/trees/op.txt");
  foreach($src as $dat)
  {
       $cdat = explode(" ",trim($dat));
       if(sizeof($cdat) == 2 && strlen($cdat[1]) < 10) {
         echo "{x: vis.moment(\"" . $cdat[0] . "\"), y: " . $cdat[1] . "}," . PHP_EOL;
       }
  }
  ?>
  ];
  var dataset = new vis.DataSet(items);

  var options = {
    start: vis.moment(items[0].x),
    interpolation: false,
    drawPoints: false,
    end: vis.moment(),
    height: '75vh',
    dataAxis: {
        left: {
            format: function(value){
              return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); // https://stackoverflow.com/a/2901298/10659982
            }
        }
    },
    shaded: {
      orientation: 'bottom' // top, bottom
    }
  };
  var graph2d = new vis.Graph2d(container, dataset, options);

  /**
   * Add a new datapoint to the graph
   */
  function addDataPoint() {
    // add a new data point to the dataset
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           // Typical action to be performed when the document is ready:
           var pointt = JSON.parse(xhttp.responseText);
           if(pointt.y != null){dataset.add(pointt);}
           setTimeout(addDataPoint, 5000);
        }
    };
    xhttp.open("GET", "/givemetreecount2.php?"+Math.floor(new Date().getTime() / 1000), true);
    xhttp.send();
  }
  setTimeout(addDataPoint,2000);
</script>
</body>
</html>
