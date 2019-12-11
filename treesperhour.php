<?php
set_time_limit(120);
?><!DOCTYPE HTML>
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
    }
    .custom-style {
            fill: #f23303;
            fill-opacity:0;
            stroke-width:2px;
            stroke: #ff0004;
    }
    a{color:blue;text-decoration: none}
    a:hover{text-decoration: underline}
  </style>

  <script src="https://visjs.github.io/vis-timeline/dist/vis-timeline-graph2d.min.js"></script>
  <link href="https://visjs.github.io/vis-timeline/dist/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h3 style="font-size: 1.2em;">Tree count graph of #TeamTrees.</h3>
<div id="visualization" style="max-height: 90vh;"></div>
<p>
  Chart shows the amount of donations per hour
</p>

<script type="text/javascript">
var container = document.getElementById('visualization');
var items = [
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
         $hour = date("H",$timeAsInt);
         if(!isset($tree[$year])){
           $tree[$year] = array();
         }
         if(!isset($tree[$year][$month])){
           $tree[$year][$month] = array();
         }
         if(!isset($tree[$year][$month][$day])){
           $tree[$year][$month][$day] = array();
         }
         if(!isset($tree[$year][$month][$day][$hour])){
           $tree[$year][$month][$day][$hour] = array();
         }
         $tree[$year][$month][$day][$hour][$timeAsInt] = $cdat[1];
       }
  }
  foreach($tree as $year => $yearArray){
    foreach($yearArray as $month => $monthArray){
      foreach($monthArray as $day => $dayContents){
        foreach($dayContents as $hour => $hourContents){
          $beginning = min($hourContents);
          $end = max($hourContents);
          $beginningKey = array_search($beginning,$hourContents);
          $endKey = array_search($end,$hourContents);
          $beginningDate = date('Y-m-d\TH:i:s.Z\Z', $beginningKey); // https://stackoverflow.com/a/23108686/10659982
          $endDate = date('Y-m-d\TH:i:s.Z\Z', $endKey);
          $diff = ($end-$beginning);
          if($diff == 0){$diff = 1;}
          echo "{x: '{$beginningDate}', end: '{$endDate}', y:" . $diff . ", group: 0},\n";
        }
      }
    }
  }
  ?>
];

var groups = new vis.DataSet();
groups.add({
        id: 0,
        content: "Trees donated per hour",
        className: 'custom-style',
        });
var dataset = new vis.DataSet(items);
var options = {
    drawPoints: false,
    height: '75vh',
    interpolation: false,
    dataAxis: {
        left: {
            format: function(value){
              return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); // https://stackoverflow.com/a/2901298/10659982
            }
        }
    },
    orientation:'top',
    start: '2019-10-25',
    end: vis.moment(),
    shaded: {
      orientation: 'bottom' // top, bottom
    }
};
var graph2d = new vis.Graph2d(container, dataset, groups, options);
</script>
</body>
</html>
