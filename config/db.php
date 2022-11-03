<?php

header('Access-Control-Allow-Origin: *'); 
header('Content-Type: application/json');


class DB{
    static $host ="localhost";
    static $dbname ="seguridad";
    static $username ="root";
    static $password ="";

    public static function connect(){  
        return new mysqli(
            self::$host,
            self::$username,
            self::$password, 
            self::$dbname
        );
    }
    public static function query($query){
        $results = []; 
        $db = self::connect();
        $result = $db->query($query);
        
            while($row = $result->fetch_object()){ 
                $results[] = $row;
            }
        return $results;
        
        $db->close(); 
    }

    public static function insert($array,$table){
        $db = self::connect();

        $sql  = "INSERT INTO {$table}";

        $sql .= "(`".implode("`, `", array_keys($array))."`)";
        $sql .= " VALUES ('".implode("', '", $array)."') ";

        if($db->query($sql)){
            return true;
        }else{
            return false;
        }
    }
}