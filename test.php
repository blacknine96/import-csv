<?php

require_once __DIR__ . '\core\Iacsv.php';

use core\blacknine\Iacsv;


$pathFile = 'product_2020-02-25_083538.csv';


$test = new Iacsv($pathFile);


$test2 = Iacsv::start($pathFile)->execute();

var_dump($test2);





