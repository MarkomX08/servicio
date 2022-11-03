<?php
namespace Token;
use Exception;
use Firebase\JWT\JWT;

//intentar llamar igual la clase que el archivo por buenas practicas
class JWToken{
    //la llave es necesaria para verificar identidad al leer el token
    private static $key = 'jhgfdsa'; //Seedxz

    //tipo de algoritmo
    private static $algorithm =  ['HS256'];

    //Creamos un metodo publico
    public static function createToken($data){
        $time = time();
        $secret = base64_encode(password_hash(self::$key, PASSWORD_DEFAULT, ['cost' =>10]));
        $payload = [
            "iat" => $time,
            "jti" => $secret,
            //tiempo antes de que lo puedas usar
            "nbf" => $time + 0,
            //tiempo antes de expirar
            "exp" => $time + (30),
            "aud" => self::audit(),
            "data" => $data
        ];
        return JWT::encode($payload, self::$key);

    }

    //Creamos metodo privado
    private static function audit(){
        if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $aud = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }elseif(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $aud = $_SERVER["HTTP_CLIENT_IP"];
        }else{
            $aud = $_SERVER["REMOTE_ADDR"];
        }
        $aud .= @$_SERVER["HTTP_USER_AGENT"];
        $aud .= gethostname();
        return sha1($aud);

    }

    public static function verifyToken($token){
        try{
            $decodeToken = JWT::decode(
                $token,
                self::$key,
                self::$algorithm
            );
            if($decodeToken->aud !== self::audit()){
                return false;
            }else{
                return true;
            }
        } catch (Exception $e){
            //echo $e
            return false;
        }
    }


}