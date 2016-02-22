<?php
/*
  Project name : market
  Start Date : 22 Feb, 2016 10:18:10 PM
  Author: Adarsh
  Purpose :
 */
include 'classes/Fetcher.php';
$f = new Fetcher();
echo $f->fetch("ITC", "20-01-2010", "20-02-2015");
?>

