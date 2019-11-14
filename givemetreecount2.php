<?php
header("Content-type: text/plain");
// $newerthan = (int) $_GET['newerthan'];
// if($neverthan < 20){$neverthan = 0;}
// $src = file("/var/www/trees/trees.log");
// foreach($src as $dat)
// {
//   $cdat = explode(" ",trim($dat));
//   if(sizeof($cdat) == 2 && ((int) $cdat[0]) > $newerthan) {
//     echo "{x: '" . date("Y-m-d H:i:s",$cdat[0]) . "', y: {$cdat[1]}}," . PHP_EOL;
//   }
// }
$line = `tail -n 1 /var/www/trees/op.txt`;
$tl = explode(" ",trim($line));
$i = 1;
while(!isset($tl) || sizeof($tl) != 2 || strlen($tl[1]) > 10){
  $tl = `tail -n {$i} /var/www/trees/op.txt`;
  $tl = explode("\n",$tl)[0];
  $tl = explode(" ",trim($tl));
  $i++;
}
echo json_encode(array("x" => $tl[0], "y" => $tl[1]));
?>
