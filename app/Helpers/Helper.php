<?php

namespace App\Helpers;

// convert to print array and Stop 
class Helper {

    static function pr($param) {
        echo "<pre>";
        print_r($param);
        echo "</pre>";
    }

// convert to print array and Stop 
    static function pre($param) {
        echo "<pre>";
        print_r($param);
        echo "</pre>";
        exit();
    }

}

?>
