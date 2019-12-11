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
    .custom-style {
            fill: hsl(12.3, 73.9%, 48%);
            fill-opacity:0;
            stroke: hsl(1,80%,60%);
    }
    a{color:hsl(232,69%,69%);text-decoration: none}
    a:hover{text-decoration: underline}
    h3{color:white;text-decoration: underline;}
    h3:hover{text-decoration: none;}
    #map {
    position: fixed;
    top: 0;
    right: 0;
    width: 200px;
    height: 100%;
    z-index: 100;
    background: rgba(0,0,0,0.9);
  }
  ::-webkit-scrollbar-track
  {
    border-radius: 30px;
  	background-color: white;
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.4);
  }
  ::-webkit-scrollbar
  {
  	width: 15px;
    height: 15px;
  }
  ::-webkit-scrollbar-thumb
  {
  	background-color: #111505;
    border-radius: 30px;
  }
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
  </style>

  <script src="https://visjs.github.io/vis-timeline/dist/vis-timeline-graph2d.min.js"></script>
  <script src="https://larsjung.de/pagemap/latest/demo/pagemap.min.js"></script>
  <link href="https://visjs.github.io/vis-timeline/dist/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<a name="tt" href="#tt"><h3 style="font-size: 1.2em;">Trees donated to #TeamTrees.</h3></a>
<div id="visualization" style="max-height: 80vh;"></div>

<p>
  <b>Chart shows the number of trees donated</b> to the <a href="https://teamtrees.org" target="_blank">#TeamTrees</a> initiative (y-axis) plotted over time (x-axis). And because one tree conveniently is $1, the y-axis also represents the amount of money donated so far.<br />
  Data is captured every 10s and updated live in this view. Use scrollwheel or pinch to zoom, click+drag to move.<br /><br />
  Due to this site becoming close to 25MB and therefore too heavy for some clients to handle/load, only 1 out of 10 datapoints are displayed. If you have a beefy computer and whish to check out the "full resolution" version featuring all data points <a href="https://vps.natur-kultur.eu/trees_highres.php">click here</a>
</p><br /><hr />
<a name="tpd" href="#tpd"><h3 style="font-size: 1.2em;">Trees donated per UTC day</h3></a>
<div id="visualization2" style="max-height: 80vh;"></div>
<p>
  <b>Chart shows the amount of donations per day</b>
</p><br /><hr />
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
  $src = file("../trees/op.txt");
  $k = 0;
  foreach($src as $dat)
  {
    if($k == 10){
       $k = 0;
       $cdat = explode(" ",trim($dat));
       if(sizeof($cdat) == 2 && strlen($cdat[1]) < 10) {
         echo "{x: vis.moment(\"" . $cdat[0] . "\"), y: " . $cdat[1] . "}," . PHP_EOL;
       }
    }
    $k++;
  }
  ?>
  ];
  var dataset = new vis.DataSet(items);

  var options = {
    start: vis.moment(items[0].x),
    interpolation: false,
    drawPoints: false,
    end: vis.moment(),
    height: '70vh',
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
  var container2 = document.getElementById('visualization2');
  var items2 = [
    <?php
    $src = file("../trees/op.txt");
    $newlist = Array();
    $tree = array();
    foreach($src as $dat) {
         $cdat = explode(" ",trim($dat));
         if(sizeof($cdat) == 2 && strlen($cdat[1]) < 10) {
           $timeAsInt = strtotime($cdat[0]);
           $newlist[$timeAsInt] = $cdat[1];
           $year = date("Y",$timeAsInt);
           $month = date("m",$timeAsInt);
           $day = date("d",$timeAsInt);
           if(!isset($tree[$year])){
             $tree[$year] = array();
           }
           if(!isset($tree[$year][$month])){
             $tree[$year][$month] = array();
           }
           if(!isset($tree[$year][$month][$day])){
             $tree[$year][$month][$day] = array();
           }
           $tree[$year][$month][$day][$timeAsInt] = $cdat[1];
         }
    }
    foreach($tree as $year => $yearArray){
      foreach($yearArray as $month => $monthArray){
        foreach($monthArray as $day => $dayContents){
          $beginning = min($dayContents);
          $end = max($dayContents);
          $beginningKey = array_search($beginning,$dayContents);
          $endKey = array_search($end,$dayContents);
          $beginningDate = date('Y-m-d\TH:i:s.Z\Z', $beginningKey); // https://stackoverflow.com/a/23108686/10659982
          $endDate = date('Y-m-d\TH:i:s.Z\Z', $endKey);
          echo "{x: '{$beginningDate}', end: '{$endDate}', y:" . ($end-$beginning) . ", group: 0},\n";
        }
      }
    }
    ?>
  ];

  var groups = new vis.DataSet();
  groups.add({
          id: 0,
          content: "Trees per day",
          className: 'custom-style',
          });
  var dataset2 = new vis.DataSet(items2);
  var options2 = {
      style:'bar',
      drawPoints: false,
      height: '70vh',
      dataAxis: {
          left: {
              format: function(value){
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); // https://stackoverflow.com/a/2901298/10659982
              }
          }
      },
      orientation:'top',
      start: '2019-10-25',
      end: vis.moment()
  };
  var graph2d2 = new vis.Graph2d(container2, dataset2, groups, options2);
  function is_touch_device() {
    try {
      document.createEvent("TouchEvent");
      return true;
    } catch (e) {
      return false;
    }
  }
  if(is_touch_device() === false)
  {
    var cnv = document.createElement("canvas");
    cnv.id = "map";
    document.body.appendChild(cnv);
    pagemap(cnv, {
      viewport: null,
    styles: {
        'h1,a': 'rgba(200,200,0,0.7)',
        'h2,h3,h4': 'rgba(0,0,255,0.7)',
        '#visualization': 'rgba(79, 129, 189, 0.5)',
        '#visualization2': 'hsl(1,30%,30%)',
        'p': 'rgba(255,255,255,0.8)',
        'ul': 'rgba(200,200,200,0.8)',
        'br,hr': 'rgba(255,255,255,0.2)',
    },
    back: 'rgba(255,255,255,0.02)',
    view: 'rgba(255,255,255,0.05)',
    drag: 'rgba(255,255,255,0.10)',
    interval: null
    });
  }
</script>
</body>
</html>
