<?php

require_once __DIR__ . '\core\Iacsv.php';

use core\blacknine\Iacsv;


$pathFile = 'product_2020-02-25_083538.csv';


$test = new Iacsv($pathFile);
$test->setRowStart(2);
$test->setIsUTF8(true);
$test->setDelimiter(';');

$test->openSingleRow();

while ($test->checkIsNotEof()) {
	$singleRow = $test->getSingleRow();
}
$test->closeSingleRow();

var_dump($test);



/*
$test2 = Iacsv::start($pathFile)
	->setRowStart(2)
	->setIsUTF8(true)
	->setDelimiter(';')
	->all();
*/








