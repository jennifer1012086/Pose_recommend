<?php

$input = json_decode(file_get_contents("php://input"), true);
$sex = $input['sex'];
$pose = $input['pose'];
$scene = $input['scene'];

$host = 'localhost:3306';
$user = 'admin';
$passwd = 'gagBpJ6W4cRe';
$database = 'picture';
$connect = new mysqli($host, $user, $passwd, $database);

if($connect->connect_error) {
    die("連線失敗: ".$connect->connect_error);
}

$connect->query("SET NAMES 'utf8'");

$selectSql = "SELECT * FROM `pictures` WHERE sexid = " . $sex . " AND posid = " . $pose . " AND sceid = " . $scene;
$memberData = $connect->query($selectSql);

$jsn = array("length"=>0, "image"=>array());

if($memberData->num_rows <= 5) {
    $cnt = 1;
    $jsn["length"] = $memberData->num_rows;
    while($row = $memberData->fetch_assoc()) {
        array_push($jsn["image"], "/images/{$row['pic']}.jpg");
        $cnt++;
    }
}
else {
    $tmpRand = range(0, $memberData->num_rows-1);
    shuffle($tmpRand);
    $rand = array_slice($tmpRand, 0, 5);
    $cnt = 0;   $i = 0;
    $jsn["length"] = 5;
    while($row = $memberData->fetch_assoc()) {
        if( in_array($i, $rand) ) {
            array_push($jsn["image"], "/images/{$row['pic']}.jpg");
            $cnt++;
        }
        if($cnt === 5) {
            break;
        }
        $i++;
    }
}

$jsn = json_encode($jsn, JSON_UNESCAPED_SLASHES);
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json");
echo $jsn;

?>