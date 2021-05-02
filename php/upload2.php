<?php
    
    $data = json_decode(file_get_contents("php://input", 'r'));
    //$data = array(
    //    "person"=>"../images/5.jpg",
    //    "mask"=>"../images/mask/5.jpg",
    //    "position_x"=>2.5,
    //    "position_y"=>3.6,
    //    "scale"=>1.05,        # 放大縮小倍率
    //    "upload"=>"../images/upload/tmp.jpg"
    //);
    //$data = json_decode(json_encode($data), true);

    $pass_data = array();
    $pass_data["person"] = ".." . $data["person"];
    $pass_data["position_x"] = $data["position_x"];
    $pass_data["position_y"] = $data["position_y"];
    $pass_data["scale"] = $data["scale"];
    $mask_filename = substr($pass_data["person"], strlen("../images/person/"), strlen($pass_data["person"]) - strlen("../images/person/") - 4);
    $pass_data["mask"] = "../images/mask/" . $mask_filename . ".jpg";
    //$pass_data["mask"] = "../images/mask/5.jpg";
    $pass_data["upload"] = "../images/upload/tmp.jpg";
    //echo json_encode($pass_data, JSON_UNESCAPED_SLASHES);
    //echo(json_encode($pass_data, JSON_UNESCAPED_SLASHES));

    shell_exec('python ../python/poisson.py ' .escapeshellarg(base64_encode(json_encode($data))));
    //echo $output;
    $jsn = array();
    $jsn["path"] = "../images/upload/new.jpg"; 
    //echo "<img src='../images/upload/new.jpg'>";
    echo json_encode($jsn, JSON_UNESCAPED_SLASHES); 
?>