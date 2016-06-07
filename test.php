<?php
/*
  Project name : market
  Start Date : 22 Feb, 2016 10:18:10 PM
  Author: Adarsh
  Purpose :
 */
set_time_limit(0);
die("Comment line 9 to run. This is a fail safe to prevent unwanted reruns");
include 'classes/Fetcher.php';
include 'classes/Processor.php';
$f = new Fetcher();
$f->setFromDate('10-05-2016');
$f->setToDate('07-06-2016');
$p = new Processor();

$result = $p->db->query('SELECT * FROM companies');
while($row = $result->fetch_assoc()){
    $res = $f->fetch($row['symbol']);
    $p->setSymbol($row['symbol']);
    $p->process($res);
}
echo "Completed all.";
?>

