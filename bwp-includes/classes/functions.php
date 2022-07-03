<?php
date_default_timezone_set('Europe/Istanbul');
$tarih = date("Y.m.d H:i:s");

    class yazi {
        public $string;
        public $int;
        public $ayrac;
        function kisalt($kelime, $str = 10)
        {
            if (strlen($kelime) > $str)
            {
                if (function_exists("mb_substr"))
                {
                    $kelime = mb_substr($kelime, 0, $str, "UTF-8").'..';
                }
                else
                {
                    $kelime = substr($kelime, 0, $str).'..';
                }
            }
            return $kelime;
        }
        function urlKisalt($kelime, $str = 10)
        {
            if (strlen($kelime) > $str)
            {
                if (function_exists("mb_substr"))
                {
                    $kelime = mb_substr($kelime, 0, $str, "UTF-8");
                }
                else
                {
                    $kelime = substr($kelime, 0, $str);
                }
            }
            return $kelime;
        }
        function shorter($text, $chars_limit)
        {
            if (strlen($text) > $chars_limit)
            {
                $new_text = substr($text, 0, $chars_limit);
                $new_text = trim($new_text);
                return $new_text . "...";
            }
            else
            {
                return $text;
            }
        }
        function seoUrl($string){
            $find = array('Ç', 'Ş','Š', 'Ğ', 'Ü', 'İ', 'I', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#', '.');
            $replace = array('c', 's','s', 'g', 'u', 'i','i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp','');
            $string = strtolower(str_replace($find, $replace, $string));
            $string = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $string);
            $string = trim(preg_replace('/\s+/', ' ', $string));
            $string = str_replace(' ', '-', $string);
            return $string;
        }
        function tirnak($string){
            return str_replace(array("'", "\""), array("&#39;", "&quot;"),$string);
        }
        function bosluk($string){
            $string = preg_replace("/\s+/", " ", $string);
            $string = trim($string);
            return $string;
        }
        function yazibol ($ayrac,$string){
            $string = explode($ayrac,$string);
            return $string;
        }
        function datetxt ($string){
            $newdate =  strftime(' %d %B %Y', strtotime($string));
            return $newdate;
        }
        function baslik($string){
            $string = stripslashes($string);
            $string = addslashes($string);
            $string = str_replace(array("'", "\""), array("&#39;", "&quot;"),$string);
            return $string;
        }
        function harfAl($string,$int){
            $say = strlen($string);
            if($say > $int){
                $yeni = substr($string,0,$int);
            }elseif(($say == $int) or ($say < $int)){
                $yeni = $string;
            }
            return $yeni;
        }
        function temizle($string){
            $string = strip_tags($string);
            return $string;
        }

    }
?>