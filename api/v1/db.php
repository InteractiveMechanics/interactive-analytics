<?php
    function getDB() {
        $dbhost = "70.32.112.236";
        $dbuser = "im_analytics";
        $dbpass = "Uvnz590$";
        $dbname = "im_analytics";
        $dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
?>