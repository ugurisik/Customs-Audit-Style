<?php 
session_start();
$_SESSION['dil'] = "TR";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'bwp-includes/vendor/autoload.php';
include 'bwp-includes/settings.php';
include 'bwp-includes/classes/sender.php';



function spamControl($db, $ip, $useragent, $browser, $os, $minute)
{

    $time = new DateTime(date("Y-m-d H:i:s"));
    $time->modify($minute . ' minutes');
    $stamp = $time->format('Y-m-d H:i:s');

    $db->where("userIP", $ip);
    $db->where("userOS", $os);
    $db->where("userAgent", $useragent);
    $db->where("date",$stamp,"<");    
    $logs = $db->get("logs");
    $a =  array("title" => "" . "AA" . "", "message" => " Sayı : " . count($logs) . "", "type" => "error");
    return $a;
}


$idata = spamControl($db,$security->getIP(),$security->getUserAgent(),$security->getBrowser(),$security->getOS(),5);
$data = array(
    'langID' => 1,
    'member_name' => "asd",
    'member_detail' => "asd",
    'member_image' => $_"aSD",
);
$insert = $db->insert('aboutus_members', $data);
echo $insert;
?>