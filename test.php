<?php

require_once __DIR__ . '\core\Iacsv.php';

use blacknine96\importcsv\core;


$pathFile = 'product_2020-02-25_083538.csv';


$test = new Iacsv($pathFile);
$test->setRowStart(11);
$test->setIsUTF8(true);
$test->setDelimiter(';');

$test->openSingleRow();

while ($test->checkIsNotEof()) {
	$singleRow = $test->getSingleRow();
	print_r($singleRow);
}


$test2 = Iacsv::start($pathFile)
	->setRowStart(2)
	->setIsUTF8(true)
	->setDelimiter(';')
	->all();

var_dump($test2);











