<?php
$sinput = array("upload"=>"../images/upload/tmp.jpg");
$obj = shell_exec('python ../python/test_s.py ' .escapeshellarg(base64_encode(json_encode($sinput))));
echo $obj;
$obj = json_decode($obj, true);
//print_r($obj);

$host = 'localhost:3306';
$user = 'admin';
$passwd = 'gagBpJ6W4cRe';
$database = 'picture';
$connect = new mysqli($host, $user, $passwd, $database);

if($connect->connect_error) {
    die("連線失敗: ".$connect->connect_error);
}

$connect->query("SET NAMES 'utf8'");
$jsn = array("length"=>0,"path"=>array(),"upload"=>'../images/upload/tmp.jpg');


for($i=0; $i<$obj['length']; $i++)
{
    $selectSql = "SELECT * FROM `pictures` WHERE sceid = {$obj['scene'][$i]}";
    $memberData = $connect->query($selectSql);
    if ($memberData->num_rows > 0) {
        $jsn["length"] = $jsn["length"] + $memberData->num_rows;
        while ($row = $memberData->fetch_assoc()) {
            array_push($jsn["path"], "../images/". $row["pic"]. '.jpg');
        }
    }
}

$jsn = json_encode($jsn, JSON_UNESCAPED_SLASHES);
$analy_return = shell_exec('python ../python/test_a.py ' .base64_encode(json_encode($jsn)));
echo $analy_return."123";
$get_json = json_decode($analy_return);
//echo $get_json;

$return_json = array("length"=>0, "images"=>array("path"=>'', "person"=>''));
$return_json["length"] = $get_json["length"];

for($i=0; $i<$return_json['length']; $i++){
    $return_json["image"][$i]["path"]= substr($get_json->image[$i]->path, 2, strlen($get_json->image[$i]->path) - 2);
    $person_filename = substr($get_json->image[$i]->path, strlen("../images/"), strlen($get_json->image[$i]->path) - strlen("../images/") - 4);
    $return_json["image"][$i]["person"]= "/images/person/" . $person_filename . ".png";
}

// echo(json_encode($return_json, JSON_UNESCAPED_SLASHES));

?>
