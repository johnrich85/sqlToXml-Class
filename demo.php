<?php


// require the class used to query database & format as xml
require_once( 'getRecordSet.class.php' );

//instantiate class
$xml = new XMLRecordSet();

//get results as xml and echo to page.
echo $xml->getRecordSet("select * from test_table", "test");
?>
