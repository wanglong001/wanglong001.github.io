<?php

header("Content-type: application/json; charset=utf-8");

require_once "../db/product.func.php";

$keyword=$_GET['keyword'];

$sugg_arr=getSuggestion($keyword);

echo json_encode($sugg_arr);