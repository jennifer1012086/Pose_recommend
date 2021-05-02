<?php
    
    $data = json_decode(file_get_contents("php://input"), true);

    $pass_data = array();
    $pass_data["person"] = ".." . $data["person"];
    $pass_data["position_x"] = $data["position_x"];
    $pass_data["position_y"] = $data["position_y"];
    $pass_data["scale"] = $data["scale"];
    $mask_filename = substr($pass_data["person"], strlen("../images/person/"), strlen($pass_data["person"]) - strlen("../images/person/") - 4);
    $pass_data["mask"] = "../images/mask/" . $mask_filename . ".jpg";
    $pass_data["upload"] = "../images/upload/tmp.jpg";
    // echo(json_encode($pass_data, JSON_UNESCAPED_SLASHES));

    // shell_exec('python ../python/poisson.py ' .escapeshellarg(base64_encode(json_encode($pass_data))));
    
    $jsn = array();
    $jsn["path"] = "/images/upload/new.jpg"; 
    // echo "<img src='../images/upload/new.jpg'>";
    header("Access-Control-Allow-Origin:*");
    header("Content-Type: application/json");
    echo json_encode($jsn, JSON_UNESCAPED_SLASHES); 
?>