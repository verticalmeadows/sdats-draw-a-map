<?php
$data = json_decode(file_get_contents('php://input'), true);
echo json_encode($data['geo']);
if(isset($data['folder']) && isset($data['geo']) && is_dir('maps/'.$data['folder'])){
    $file = $data['folder'].'.json';
    $fp = fopen('maps/'.$data['folder'] . '/' . $file, 'w');
    fwrite($fp, json_encode($data['geo']));
    fclose($fp);
}else{
    http_response_code(404);
    die();
}