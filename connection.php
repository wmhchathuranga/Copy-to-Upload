<?php

class Database{

    public static $connection;
    
    public static function setUpConnection(){
        if(!isset(Database::$connection)){
            Database::$connection = new mysqli("localhost","root","root","picture_bin");
        }
    }
    
    public static function select($query){
        Database::setUpConnection();
        $res = Database::$connection -> query($query);
        return $res;
    }

    public static function iud($query){
        Database::setUpConnection();
        Database::$connection -> query($query);
    }

    public static function insert_id($table){
        Database::setUpConnection();
        $query = "SELECT COUNT(*) as count FROM `$table`";
        $res = Database::select($query);
        $id = $res->fetch_assoc();
        $id = $id['count'] + 1;
        return $id;
    }
}