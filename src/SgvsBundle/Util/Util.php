<?php
/**
 * Created by PhpStorm.
 * User: B�rbaro
 * Date: 15/12/2015
 * Time: 14:20
 */

namespace SgvsBundle\Util;

class Util
{
    static public function getSlug($cadena, $separador = '-')
    {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $cadena);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separador));
        $slug = preg_replace("/[\/_|+ -]+/", $separador, $slug);
        return $slug;
    }

    static public function stringToPhone($telefono){
        $picotillo=str_split($telefono, 2);
        $phone=$picotillo[0].' '.$picotillo[1].' '.$picotillo[2];
        return $phone;
    }

    static public function phoneToString($telefono){
        $picotillo=str_split($telefono);
        $phone=$picotillo[0].$picotillo[1].$picotillo[3].$picotillo[4].$picotillo[6].$picotillo[7];
        return $phone;
    }


    static public function stringToDate($string){
        $date=new \DateTime($string);

        return $date;
    }

    static public function generatorCode($date, $ci, $tipocaso){
        $parte1=str_split($tipocaso, 2);
        $parte2=str_split($ci, 6);
        $parte3=mt_rand(10,99);

        $code=strtoupper($parte1[0]).'-'.$parte2[1].$parte3;

        return $code;


    }


    static public function mesString($mes){
        if ($mes==01){
            $mes='Enero';
        } else if ($mes=='02'){
            $mes='Febrero';
        } else if ($mes=='03'){
            $mes='marzo';
        } else if ($mes=='04'){
            $mes='Abril';
        } else if ($mes=='05'){
            $mes='Mayo';
        } else if ($mes=='06'){
            $mes='Junio';
        } else if ($mes=='07'){
            $mes='Julio';
        } else if ($mes=='08'){
            $mes='Agosto';
        } else if ($mes=='09'){
            $mes='Semptiembre';
        } else if ($mes=='10'){
            $mes='Octubre';
        } else if ($mes=='11'){
            $mes='Noviembre';
        } else if ($mes=='12'){
            $mes='Diciembre';
        }
        return $mes;
    }


    public static function img_base64($rutaabsoluta){
        $logofile=$rutaabsoluta;
        $imgbinary = fread(fopen($logofile, "r"), filesize($logofile));
        return base64_encode($imgbinary) ;


    }

}