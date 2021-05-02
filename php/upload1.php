<?php

$data = json_decode(file_get_contents("php://input", 'r'));

$pass_data = array();
$pass_data["base64"] = base64_decode($data->upload);
file_put_contents('/app/images/upload/tmp.jpg', $pass_data["base64"]);

$pass = array(
    "upload"=>"../images/upload/tmp.jpg"
);

$output_1=shell_exec('python ../python/test_s.py ' .escapeshellarg(base64_encode(json_encode($pass, JSON_UNESCAPED_SLASHES))));
$output_1 = json_decode($output_1,true);

for($i=0;$i<$output_1["length"];$i++)
    $sce[$i]=$output_1["scene"][$i];

$host = 'localhost:3306';
$user = 'admin';
$passwd = 'gagBpJ6W4cRe';
$database = 'picture';
$connect = new mysqli($host, $user, $passwd, $database);

if($connect->connect_error) {
    die("連線失敗: ".$connect->connect_error);
}

$connect->query("SET NAMES 'utf8'");
$jsn = array("length"=>0,"path"=>array(),"upload"=>'');
$jsn["upload"]="../images/upload/tmp.jpg" ;
for($i=0;$i<$output_1["length"];$i++)
{
    $selectSql = "SELECT * FROM `pictures` WHERE sceid = {$sce[$i]}";
    $memberData = $connect->query($selectSql);
    if ($memberData->num_rows > 0) {
        $jsn["length"] += $memberData->num_rows;
        while ($row = $memberData->fetch_assoc()) {
            array_push($jsn["path"], "../images/". $row["pic"]. '.jpg');
        }
    }
}

$get_json = shell_exec('python ../python/similar_cal.py ' .escapeshellarg(base64_encode(json_encode($jsn))));
$get_json = json_decode($get_json);
$number_of_path = $get_json->length;
$return_json = array();
$return_json["length"] = $number_of_path;
$return_json["image"] = [];
for($i = 0; $i < $number_of_path; $i++){
    $return_json["image"][$i]["path"]= substr($get_json->image[$i], 2, strlen($get_json->image[$i]) - 2);
    $person_filename = substr($get_json->image[$i], strlen("../images/"), strlen($get_json->image[$i]) - strlen("../images/") - 4);
    $return_json["image"][$i]["person"]= "/images/person/" . $person_filename . ".png";
}

echo json_encode($return_json, JSON_UNESCAPED_SLASHES);

?>