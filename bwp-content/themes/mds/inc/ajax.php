<?php
session_start();
include '../../../../bwp-includes/vendor/autoload.php';
include '../../../../bwp-includes/settings.php';
include '../../../../bwp-includes/classes/sender.php';


function insertLog($security, $text, $db)
{
    $data = array(
        "transaction" => $text,
        "userIP" => "" . $security->getIP() . "",
        "userOS" => "" . $security->getOS() . "",
        "userLang" => "" . $security->getLang() . "",
        "userAgent" => "" . $security->getUserAgent() . ""
    );
    $insert = $db->insert("logs", $data);
}

function spamControl($db, $ip, $useragent, $os, $minute)
{

    $time = new DateTime(date("Y-m-d H:i:s"));
    $time->modify($minute . ' minutes');
    $stamp = $time->format('Y-m-d H:i:s');

     $db->where("userIP", $ip);
    // $db->where("userOS", $os);
     $db->where("userAgent", $useragent);
    $db->where("date",$stamp,">");    
    $logs = $db->get("logs");
    return count($logs);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $spamcontrol = spamControl($db,$security->getIP(),$security->getUserAgent(),$security->getOS(),-2);
    if ($spamcontrol > 10) {
        $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . "Son 15 Dakikada Çok Fazla İşlem Yaptığınız İçin Bazı İşlemleriniz Kısıtlanmıştır!". " ", "type" => "error");
        goto son;
    }
    $type = $_GET['type'];
    if ($type == "contactForm") {
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        }
        $control = $security->reCaptchaControl("6LfCSQMeAAAAAHDyewDaexFty4sOjoOzFQgua9Q6", $captcha);
        if ($control['success'] == false) {
            $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("googlecaptchaerror") . " ", "type" => "error", "cnt" => $control);
        } else {
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

                if ($security->formCrudSecurityforURI($_POST)) {
                    $db->where("id", "1");
                    $mail = $db->getOne("mailSetting");
                    $sender = new sender($mail["host"], $mail["port"], $mail["mail"], $mail["pass"], $mail["encryption"]);
                    $db->where("type", "contact");
                    $mailTemp = $db->getOne("mailTemplate");
                    $variables = array();
                    foreach (json_decode($mailTemp['sablonmesaji']) as $k => $v) {
                        if ($k == mb_strtoupper($_SESSION['dil'])) {
                            $sablonmesaji = $v;
                        }
                    }
                    foreach (json_decode($mailTemp['sablonbasligi']) as $k => $v) {
                        if ($k == mb_strtoupper($_SESSION['dil'])) {
                            $sablonbasligi = $v;
                        }
                    }
                    $variables['pagetitle'] = $setting['baslik'];
                    $variables['url'] = $setting['siteurl'];
                    $variables['compName'] = $setting['compName'];
                    $variables['hi'] = $db->translate("merhaba");
                    $variables['mesajınız'] = $db->translate("mesajınız");
                    $variables['iletisimbilgileriniz'] = $db->translate("iletisimbilgileriniz");
                    $variables['isimsoyisim'] = $db->translate("isimsoyisim");
                    $variables['ourphone'] = $db->translate("telefon");
                    $variables['ouremail'] = $db->translate("email");
                    $variables['ourcomp'] = $db->translate("firmaniz");
                    $variables['konu'] = $db->translate("konu");
                    $variables['name'] = $db->translate("isim");
                    $variables['noreply'] = $db->translate("noreply");
                    $variables['firmaniz'] = $db->translate("firmaniz");
                    $variables['sablonmesaji'] = $sablonmesaji;
                    $variables['name'] =  $_POST['unvan'] . ' ' . $_POST['name'] . ' ' . $_POST['surname'];
                    $variables['postemail'] = $_POST['email'];
                    $variables['postphone'] =  $_POST['phone'];
                    $variables['postmessage'] =  $_POST['message'];
                    $variables['postfirmaadi'] =  $_POST['firmaadi'];

                    $db->where("id", $_POST['basvurukategori']);
                    $basvurubaslik = $db->getOne("pageBasvuru");
                    $basvurubaslik = json_decode($basvurubaslik['title'], true);
                    $basvuru =  $basvurubaslik[mb_strtoupper($_SESSION['dil'])];
                    $variables['postsubject'] =  $basvuru;

                    $variables['ip'] = $security->getIP();
                    $variables['browser'] = $security->getBrowser();
                    $variables['header'] = $security->getUserAgent();
                    $variables['os'] = $security->getOS();
                    $variables['lang'] = $security->getLang();

                    $reciverTemplate = $mailTemp['reciverTemplate'];
                    $adminTemplate = $mailTemp['adminTemplate'];
                    foreach ($variables as $key => $value) {
                        $reciverTemplate = str_replace('{{' . $key . '}}', $value, $reciverTemplate);
                        $adminTemplate = str_replace('{{' . $key . '}}', $value, $adminTemplate);
                    }

                    $sender->mail($sablonbasligi, $variables['postemail'], "" . $variables['name'] . "", $mailTemp['senderMail'], $mailTemp['senderTitle'], $reciverTemplate);

                    $sender->mail($mailTemp['adminTitle'], $mailTemp['adminMail'], $mailTemp['senderTitle'], $mailTemp['senderMail'], $mailTemp['senderTitle'], $adminTemplate);
                    if ($sender) {
                        $idata = array("title" => "" . $db->translate("islembasarili") . "", "message" => "" . $db->translate("formgonderildi") . "", "type" => "success");
                    } else {
                        $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("tekrardene") . "", "type" => "error");
                    }
                } else {
                    $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("urlicerik") . "", "type" => "error");
                }
            } else {
                $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("mailformat") . "", "type" => "error");
            }
        }
     
      
    } else if ($type == "sendComment") {
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        }
        $control = $security->reCaptchaControl("6LfCSQMeAAAAAHDyewDaexFty4sOjoOzFQgua9Q6", $captcha);
        if ($control['success'] == false) {
            $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("googlecaptchaerror") . " ", "type" => "error", "cnt" => $control);
        } else {
            if ($security->formCrudSecurityforURI($_POST)) {
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $data = array(
                        "date" => date("Y-m-d H:i:s"),
                        "type" => "post",
                        "status" => "1",
                        "postID" => $_POST['postID'],
                        "name" => "" . $_POST['name'] . "",
                        "email" => "" . $_POST['email'] . "",
                        "message" => "" . $_POST['message'] . "",
                        "userIP" => "" . $security->getIP() . "",
                        "userOS" => "" . $security->getOS() . "",
                        "userBrowser" => "" . $security->getBrowser() . "",
                        "userAgent" => "" . $security->getUserAgent() . ""
                    );
                    $insert = $db->insert("comments", $data);
                    if ($insert) {
                        $idata = array("title" => "" . $db->translate("islembasarili") . "", "message" => "" . $db->translate("yorumgonderildi") . "", "type" => "success");
                    } else {
                        $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => $db->getLastError(), "type" => "error");
                    }
                } else {
                    $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("mailformat") . "", "type" => "error");
                }
                insertLog($security, "Bir Yorum Gönderildi!", $db);
            }
        }
    } else if ($type == "applicationForm") {
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        }
        $control = $security->reCaptchaControl("6LfCSQMeAAAAAHDyewDaexFty4sOjoOzFQgua9Q6", $captcha);
        if ($control['success'] == false) {
            $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("googlecaptchaerror") . " ", "type" => "error", "cnt" => $control);
        } else {
            if ($security->formCrudSecurityforURI($_POST)) {
                if (filter_var($_POST['emailaddress'], FILTER_VALIDATE_EMAIL)) {




                    $db->where("id", "1");
                    $mail = $db->getOne("mailSetting");
                    $sender = new sender($mail["host"], $mail["port"], $mail["mail"], $mail["pass"], $mail["encryption"]);
                    $db->where("type", "basvuru");
                    $mailTemp = $db->getOne("mailTemplate");
                    $variables = array();
                    foreach (json_decode($mailTemp['sablonmesaji']) as $k => $v) {
                        if ($k == mb_strtoupper($_SESSION['dil'])) {
                            $sablonmesaji = $v;
                        }
                    }
                    foreach (json_decode($mailTemp['sablonbasligi']) as $k => $v) {
                        if ($k == mb_strtoupper($_SESSION['dil'])) {
                            $sablonbasligi = $v;
                        }
                    }
                    $variables['pagetitle'] = $setting['baslik'];
                    $variables['url'] = $setting['siteurl'];
                    $variables['compName'] = $setting['compName'];
                    $variables['hi'] = $db->translate("merhaba");
                    $variables['mesajınız'] = $db->translate("mesajınız");
                    $variables['iletisimbilgileriniz'] = $db->translate("iletisimbilgileriniz");
                    $variables['isimsoyisim'] = $db->translate("isimsoyisim");
                    $variables['ourphone'] = $db->translate("telefon");
                    $variables['ouremail'] = $db->translate("email");
                    $variables['ourcomp'] = $db->translate("firmaniz");
                    $variables['konu'] = $db->translate("konu");
                    $variables['name'] = $db->translate("isim");
                    $variables['noreply'] = $db->translate("noreply");
                    $variables['firmaniz'] = $db->translate("firmaniz");
                    $variables['sablonmesaji'] = $sablonmesaji;
                    $variables['name'] =  $_POST['degree'] . ' ' . $_POST['name'] . ' ' . $_POST['surname'];
                    $variables['postemail'] = $_POST['emailaddress'];
                    $variables['postphone'] =  $_POST['phonenumber'];
                    $variables['postmessage'] =  $_POST['message'];
                    $variables['postfirmaadi'] =  $_POST['companyname'];

                    $db->where("id", $_POST['basvurukategori']);
                    $basvurubaslik = $db->getOne("pageBasvuru");
                    $basvurubaslik = json_decode($basvurubaslik['title'], true);
                    $basvuru =  $basvurubaslik[mb_strtoupper($_SESSION['dil'])];
                    $variables['postsubject'] =  $basvuru;

                    $variables['ip'] = $security->getIP();
                    $variables['browser'] = $security->getBrowser();
                    $variables['header'] = $security->getUserAgent();
                    $variables['os'] = $security->getOS();
                    $variables['lang'] = $security->getLang();

                    $reciverTemplate = $mailTemp['reciverTemplate'];
                    $adminTemplate = $mailTemp['adminTemplate'];
                    foreach ($variables as $key => $value) {
                        $reciverTemplate = str_replace('{{' . $key . '}}', $value, $reciverTemplate);
                        $adminTemplate = str_replace('{{' . $key . '}}', $value, $adminTemplate);
                    }

                    $sender->mail($sablonbasligi, $variables['postemail'], "" . $variables['name'] . "", $mailTemp['senderMail'], $mailTemp['senderTitle'], $reciverTemplate);

                    $sender->mail($mailTemp['adminTitle'], $mailTemp['adminMail'], $mailTemp['senderTitle'], $mailTemp['senderMail'], $mailTemp['senderTitle'], $adminTemplate);
                    if ($sender) {
                        insertLog($security, "Başvuru Form Maili Gönderildi! -> Kullanıcı:" . $variables['postemail'] . " Admin: " . $mailTemp['adminMail'], $db);
                        $idata = array("title" => "" . $db->translate("islembasarili") . "", "message" => "" . $db->translate("formgonderildi") . "", "type" => "success");
                    } else {
                        insertLog($security, "Başvuru Form Maili Gönderilemedi! -> ", $db);
                        $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("tekrardene") . "", "type" => "error");
                    }


                    function InsertData($security, $post, $db)
                    {
                        $data = array(
                            "status" => "1",
                            "date" => date("Y-m-d H:i:s"),
                            "basvuruAlani" => $post['basvurukategori'],
                            "firmaadi" => "" . $post['companyname'] . "",
                            "unvan" => "" . $post['degree'] . "",
                            "eposta" => $post['emailaddress'],
                            "mesaj" => "" . $post['message'] . "",
                            "isim" => "" . $post['name'] . "",
                            "telefon" => "" . $post['phonenumber'] . "",
                            "soyisim" => "" . $post['surname'] . "",
                            "userIP" => "" . $security->getIP() . "",
                            "userOS" => "" . $security->getOS() . "",
                            "userLang" => "" . $security->getLang() . "",
                            "userAgent" => "" . $security->getUserAgent() . ""
                        );
                        $insert = $db->insert("basvurular", $data);
                        if ($insert) {
                            insertLog($security, "Başvuru Formu Başarılı Bir Şekilde Gönderildi!", $db);
                            $idata = array("title" => "" . $db->translate("islembasarili") . "", "message" => "" . $db->translate("basvurualindi") . "", "type" => "success");
                        } else {
                            insertLog($security, "Başvuru Formu Gönderilirken Bir Hata Oluştu! -> " . $db->getLastError(), $db);
                            $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => $db->getLastError(), "type" => "error");
                        }
                        return $idata;
                    }
                    $idata = InsertData($security, $_POST, $db);
                } else {
                    insertLog($security, "Başvuru Formu Gönderilirken Bir Hata Oluştu! -> Mail Formatı Hatalı", $db);
                    $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("mailformat") . "", "type" => "error");
                }
            } else {
                insertLog($security, "Başvuru Formu Gönderilirken Bir Hata Oluştu! -> Link İçeriyor", $db);
                $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => "" . $db->translate("urlicerik") . "", "type" => "error");
            }
        }
    } else if ($type == "setLang") {
        unset($_SESSION['dil']);
        unset($_SESSION['dilID']);

        $db->where('subtitle', $_POST['lang']);
        $setsLang = $db->getOne("langs");

        $db->where('langID', $setsLang['id']);
        $db->where('template', 'index');
        $page = $db->getOne("page");
        
        $_SESSION['dil'] = mb_strtolower($setsLang['url']);
        $_SESSION['dilID'] = $setsLang['id'];
        $idata = array("url" => $page['url'], "lang" => $setsLang['subtitle'], "langID" => $setsLang['id'],"Session"=>$_SESSION);
    } else if ($type == "basvurugetir") {
        if (isset($_POST['basvuruid']) && !empty($_POST['basvuruid'])) {
            $basvuruid =  $_POST['basvuruid'];
            $db->where("id", $basvuruid);
            $pageBasvuru = $db->getOne("pageBasvuru");
            if ($pageBasvuru) {
                $pageBasvuru = json_decode($pageBasvuru['options'], true);
                $optionsComment =  $pageBasvuru[mb_strtoupper($_SESSION['dil'])];
                insertLog($security, "Başvuru Listesi Başarılı Bir Şekilde Getirildi!", $db);
                $idata = array("title" => "İşlem Başarılı", "message" => "" . $optionsComment . "", "type=" => "success");
            } else {
                insertLog($security, "Başvuru Listesi Getirilirken Bir Hata Oluştu! -> " . $db->getLastError(), $db);
                $idata = array("title" => "" . $db->translate("islembasarisiz") . "", "message" => $db->getLastError(), "type" => "error");
            }
        }
    } else {
        insertLog($security, "Başvuru Formu Gönderilirken Bir Hata Oluştu! -> Geçersiz İstek", $db);
        $idata = array("title" => "İşlem Başarısız", "message" => "Bu bir geçersiz istek!", "type=" => "error");
    }
} else {
    insertLog($security, "Başvuru Formu Gönderilirken Bir Hata Oluştu! -> Geçersiz İstek", $db);
    $idata = array("title" => "İşlem Başarısız", "message" => "Bu bir geçersiz istek!", "type=" => "error");
}
son:
$json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
print_r($json);
