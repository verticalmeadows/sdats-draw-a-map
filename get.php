<?php
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['folder'])){
    $file = $data['folder'].'.json';
    $current = file_get_contents('maps/'.$data['folder'] . '/' . $file);
    header('Content-Type: application/json');
    echo $current;
}else{
    http_response_code(404);
    die();
}