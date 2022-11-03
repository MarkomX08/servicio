<?php
require_once 'config/db2.php';
use DB\db as db;

$tarjeta = $_POST['value'];
// echo($tarjeta);
$query = "SELECT nombre FROM tbl_usuario WHERE num_tarjeta = '$tarjeta'";
// print_r($query);
$result = db::query($query);

if(isset($result[0])){
    $query = "INSERT INTO tbl_login(nombre, num_tarjeta, acceso) VALUES ('{$result[0]->nombre}','{$tarjeta}',1)";
    $result = db::query($query);
    if($result){
    //echo json_encode($query);
        $data=['success'=> 's'];
    }else{
        $data=['success'=> 'QUERY_FALLO'];
    }
}else{
    $query = "INSERT INTO tbl_login(nombre, num_tarjeta, acceso) VALUES ('Desconocido','{$tarjeta}',0)";
    $result = db::query($query);
    //echo json_encode($query);
    $data=['success'=> 'n'];
}
echo json_encode($data);

?>
