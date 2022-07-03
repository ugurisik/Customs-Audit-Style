<?php
session_start();
include 'settings.php';
include 'vendor/autoload.php';
include "../" . THEMEADMIN . "information.php";
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
insertLog($security, "Bir işlem yapıldı!", $db);
$islem = $_GET["i"];
$process = $_GET["process"];
$id = $_GET['id'];
$db->where("email", $_SESSION["email"]);
$userKont = $db->getOne("users");
$tarih = date("d.m.Y");

$db->where("setType", "setting");
$db->where("type", "pagination");
$topPost = $db->getOne('settings_meta');

if ($islem == "yetki") {
    $yetki = $db->yetkikont($process, "" . $userKont['id'] . "");
    if ($yetki == "1") {
        $idata = array("type" => "success");
    } else {
        $idata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " " . $process . " işlemi için yetkin yok!", "type" => "danger", "error" => "");
    }
    $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    print_r($json);
} else if ($islem == "login") {
    $tarih = date("d.m.Y");
    $email = $_POST['email'];
    $passwd = $_POST['password'];
    if (empty($email) || empty($passwd)) {
        $data = array(
            "message" => "Alanları boş bırakamazsınız",
            "type" => "danger"
        );
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        print_r($json);
    } else {
        $db->where("email", $email);
        $account = $db->getOne("users");
        $db->where("userID", $account['id']);
        $db->where("type", "yetki");
        $yetki = $db->getOne("users_meta");
        if (empty($account['email'])) {
            $data = array(
                "message" => "E-posta adresi kayıtlı değil!",
                "type" => "danger"
            );
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            if (password_verify($passwd, $account['password'])) {
                if ($yetki['type_meta'] == 1 || $yetki['type_meta'] == 2 || $yetki['type_meta'] == 6 || $yetki['type_meta'] == 7) {
                    $token = md5(uniqid(mt_rand(), true));
                    $data = array(
                        'login' => 1,
                        'loginID' => $token
                    );
                    $db->where('id', $account['id']);
                    $db->update('users', $data);
                    $data = array("userID" => $account['id'], "date" => $tarih, "browser" => $security->getBrowser(), "os" => $security->getOS(), "ip" => $security->getIP(), "userAgent" => $security->getUserAgent());
                    $id = $db->insert('login_info', $data);

                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $account['id'];
                    $_SESSION["email"] = $account['email'];
                    $_SESSION["loginid"] = $token;
                    $data = array(
                        "message" => "Giriş yapıldı. Yönlendiriliyorsunuz lütfen bekleyin!",
                        "type" => "success"
                    );
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                } else {
                    $data = array(
                        "message" => "Yönetici paneline giriş için yetkin bulunmuyor!",
                        "type" => "danger"
                    );
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else {
                if ($account['login'] < 3) {
                    $up = $account['login'] + 1;
                    $data = array('login' => $up);
                    $db->where('id', $account['id']);
                    $db->update('users', $data);
                } else if ($account['login'] == 3) {
                    $data = array("startDate" => $tarih, "endDate" => date("Y.m.d H:i:s", strtotime('+15 minutes')), "ip" => $security->getIP());
                    $id = $db->insert('login_ban', $data);
                    $data = array(
                        "message" => "Çok fazla hatalı girişi denemesi sebebi ile IP Adresiniz 15 dakika engellenmiştir!",
                        "type" => "danger"
                    );
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
                $data = array("userID" => $account['id'], "date" => $tarih, "userAgent" => $security->getUserAgent(), "browser" => $security->getBrowser(), "os" => $security->getOS(), "ip" => $security->getIP(), "username" => $email, "password" => $passwd);
                $id = $db->insert('login_error', $data);
                $data = array(
                    "message" => "Şifreniz hatalı! Lütfen tekrar deneyiniz.",
                    "type" => "danger"
                );
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            }
        }
    }
} else if ($islem == "user") {
    if ($process == "view") {
        $yetki = $db->yetkikont("userview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $pagesql = $db->get("users");
            foreach ($pagesql as $key => $value) {
                $db->where("userID", $value['id']);
                $db->where("type", "register_time");
                $rt = $db->getOne("users_meta");
                $db->where("userID", $value['id']);
                $db->orderBy("id", "desc");
                $logins = $db->getOne("login_info");

                $darray["data"][] = array(
                    $value['id'],
                    $value['name'] . ' ' . $value['surname'],
                    $value['phone'],
                    $value['email'],
                    $value['company'],
                    $value['department'],
                    $rt['type_meta'],
                    '<span class="dropdown">
                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="la la-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                <i class="la la-edit"></i> Düzenle
                            </a>
                            <a href="?process=delete&id=' . $value['id'] . '"class="dropdown-item">
                                <i class="la la-remove"></i> Sil
                            </a>
                        </div>
                    </span>',
                    $logins['date'],
                    $logins['ip'],
                    $logins['os'],
                    $logins['browser'],
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " sayfa görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "view");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $name = $_POST['isim'];
            $surname = $_POST['soyisim'];
            $email = $_POST['eposta'];
            $phone = $_POST['telefon'];
            $address = $_POST['address'];
            $company = $_POST['comp'];
            $department = $_POST['dept'];
            $aut = $_POST['yetki'];
            $status = $_POST['durum'];
            $pass = $_POST['pass'];
            $passd = $_POST['passd'];
            if ($_POST['type'] == "edit") {
                if (!empty($pass) && !empty($passd) && $pass == $passd) {
                    $hashpwd = password_hash($pass, PASSWORD_DEFAULT);
                } else {
                    $hashpwd = $_POST['passwd'];
                }
            } else if ($_POST['type'] == "add") {
                $hashpwd = password_hash($pass, PASSWORD_DEFAULT);
            }
            $data = array(
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'company' => $company,
                'department' => $department,
                'password' => $hashpwd
            );
            if ($_POST['type'] == "edit") {
                $yetki = $db->yetkikont("useredit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('users', $data);
                    if ($ssqldrm) {
                        $adata = array('type_meta' => $aut);
                        $db->where('userID', $setID);
                        $db->where('type', "yetki");
                        $db->update('users_meta', $adata);

                        $sdata = array('type_meta' => $status);
                        $db->where('userID', $setID);
                        $db->where('type', "durum");
                        $db->update('users_meta', $sdata);

                        if (isset($_POST['yetkiler'])) {
                            $authorYetki = $db->yetkikont("yetki", "" . $userKont['id'] . "");
                            $yonetici = $db->yetkikont("yetki", "" . $userKont['id'] . "");
                            $yetki = $db->yetkikont("userautedit", "" . $userKont['id'] . "");
                            if ($yetki == "1" || $yonetici['type_meta'] = 2 || $authorYetki['type_meta'] = 1) {
                                $db->where("type != 'durum'");
                                $db->where("type !='yetki'");
                                $db->where("type !='register_time'");
                                $db->where("userID", $setID);
                                $db->delete('users_meta');
                                $cats = $_POST['yetkiler'];
                                $catss = $yazi->yazibol(",", $cats);
                                $catSize = count($catss);
                                for ($i = 0; $i < $catSize; $i++) {
                                    $data = array(
                                        'userID' => $setID,
                                        'type' => $catss[$i],
                                        'type_meta' => 1
                                    );
                                    $db->insert('users_meta', $data);
                                }
                            }
                        }
                        $data = array("message" => "Kullanıcı Düzenlendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Kullanıcı Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kullanıcı düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "useredit");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "add") {
                $yetki = $db->yetkikont("useradd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $ssqldrm = $db->insert('users', $data);
                    if ($ssqldrm) {
                        $db->where("email", $email);
                        $userKont = $db->getOne("users");
                        $data = array(
                            array("userID" => $userKont['id'], "type" => "durum", "type_meta" => $status),
                            array("userID" => $userKont['id'], "type" => "yetki", "type_meta" => $aut),
                            array("userID" => $userKont['id'], "type" => "register_time", "type_meta" => $tarih)
                        );
                        $db->insertMulti('users_meta', $data);
                        if (isset($_POST['yetkiler'])) {
                            $authorYetki = $db->yetkikont("yetki", "" . $userKont['id'] . "");
                            $yonetici = $db->yetkikont("yetki", "" . $userKont['id'] . "");
                            $yetki = $db->yetkikont("userautedit", "" . $userKont['id'] . "");
                            if ($yetki == "1" || $yonetici['type_meta'] = 2 || $authorYetki['type_meta'] = 1) {
                                $db->where("type != 'durum'");
                                $db->where("type !='yetki'");
                                $db->where("type !='register_time'");
                                $db->where("userID", $setID);
                                $db->delete('users_meta');
                                $cats = $_POST['yetkiler'];
                                $catss = $yazi->yazibol(",", $cats);
                                $catSize = count($catss);
                                for ($i = 0; $i < $catSize; $i++) {
                                    $data = array(
                                        'userID' => $setID,
                                        'type' => $catss[$i],
                                        'type_meta' => 1
                                    );
                                    $db->insert('users_meta', $data);
                                }
                            }
                        }
                        $data = array("message" => "Kullanıcı Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Kullanıcı Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kullanıcı ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "useradd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "delete") {
                $yetki = $db->yetkikont("userdel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->delete('users');
                    if ($ssqldrm) {
                        $db->where('userID', $setID);
                        $ssqldrm = $db->delete('users_meta');
                        $data = array("message" => "Kullanıcı Silindi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Kullanıcı Silinemedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kullanıcı silme işlemi için yetkin yok!", "type" => "danger", "error" => "userdel");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "page") {
    if ($process == "view") {
        $yetki = $db->yetkikont("pageview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $pagesql = $db->get("page");
            foreach ($pagesql as $key => $value) {
                $db->where("id", $value["langID"]);
                $langs = $db->getOne("langs");

                foreach ($pageTemplate as $key => $v) {
                    if ($value['template'] == $v) {
                        $template = $key;
                    }
                }
                $edit = $db->yetkikont("pageedit", "" . $userKont['id'] . "");
                if ($edit == 1) {
                    $edtbtn = '<a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item"><i class="la la-edit"></i> Düzenle</a>';
                }
                $del = $db->yetkikont("pagedel", "" . $userKont['id'] . "");
                if ($del == 1) {
                    $delbtn = '<a href="?process=delete&id=' . $value['id'] . '"class="dropdown-item"><i class="la la-remove"></i> Sil</a>';
                }
                if ($edit == 1 && $del == 1) {
                    $pmenu = '<span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                    <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    ' . $edtbtn . ' ' . $delbtn . '
                                </div>
                            </span>';
                } else {
                    $pmenu = "<div class='alert alert-danger'>Sayfa <b>düzenleme</b> ve <b>silme</b> yetkiniz bulunmuyor</div>";
                }
                $darray["data"][] = array(
                    $value['id'],
                    $langs['title'],
                    $value['title'],
                    $template,
                    $pmenu
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " sayfa görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "pageview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $title = $_POST['title'];
            $langID = $_POST['langID'];
            $template = $_POST['template'];
            $url = $yazi->seoUrl($title);
            $data = array(
                'title' => $title,
                'langID' => $langID,
                'template' => $template,
                'url' => $url
            );
            if ($_POST['type'] == "edit") {
                $yetki = $db->yetkikont("pageedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('page', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Sayfa Düzenlendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Sayfa Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " sayfa düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "pagedit");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "add") {
                $yetki = $db->yetkikont("pageadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $ssqldrm = $db->insert('page', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Sayfa Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Sayfa Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " sayfa ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "pageadd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "delete") {
                $yetki = $db->yetkikont("pagedel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->delete('page');
                    if ($ssqldrm) {
                        $data = array("message" => "Sayfa Silindi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Sayfa Silinemedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " sayfa silme işlemi için yetkin yok!", "type" => "danger", "error" => "pagedelete");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "posts") {
    if ($process == "view") {
        $yetki = $db->yetkikont("postview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            if (isset($_POST)) {
                if (strlen($_POST['key']) >= 3) {
                    $db->where("post_title", "%" . $_POST['key'] . "%", "like");
                    $db->orderBy("ID", "desc");
                    $pagesql = $db->get("posts");
                } else {
                    $db->orderBy("ID", "desc");
                    $pagesql = $db->get("posts", 100);
                }
            } else {
                $db->orderBy("ID", "desc");
                $pagesql = $db->get("posts", 100);
            }
            foreach ($pagesql as $key => $value) {
                $db->where("id", $value['post_langID']);
                $langs = $db->getOne('langs');
                foreach ($status as $key => $val) {
                    if ($value['post_status'] == $val) {
                        $status = $key;
                    }
                }

                $db->where("postID", $value['ID']);
                $db->where("type", "cat");
                $pcats = $db->get('post_meta');

                foreach ($pcats as $pcatsp) {
                    $db->where("id", $pcatsp['type_meta']);
                    $cat = $db->getOne('postcat');
                    $link = '<a href="?islem=cat&id=' . $cat['id'] . '">' . $cat['title'] . '</a>,';
                }

                $edit = $db->yetkikont("postedit", "" . $userKont['id'] . "");
                if ($edit == 1) {
                    $edtbtn = '<a href="posts.php?process=edit&id=' . $value['ID'] . '" class="btn btn-dark"><i class="la la-edit "></i> Düzenle</a>';
                }
                $del = $db->yetkikont("postdel", "" . $userKont['id'] . "");
                if ($del == 1) {
                    $delbtn = '<button type="button" class="btn btn-danger" name="set" data-id="' . $value['ID'] . '" data-type="delete"><i class="la la-remove"></i> Sil</button>';
                }
                $darray["data"][] = array(
                    $value['ID'],
                    $status,
                    $langs['title'],
                    $value['post_title'],
                    $link,
                    $value['post_date'],
                    $edtbtn . '' . $delbtn
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " yazı görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "catview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            if ($_POST['type'] == "add") {
                $yetki = $db->yetkikont("postadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $post_slug = $yazi->urlKisalt($yazi->seourl($_POST['title']), 30) ;
                    $data = array(
                        'post_title' => stripslashes($_POST['title']),
                        'post_content' => $_POST['tinymce'],
                        'post_status' => $_POST['durum'],
                        'post_langID' => $_POST['langID'],
                        'post_comment' => $_POST['post_comment'],
                        'post_author' => $userKont['id'],
                        'post_date' => date('Y-m-d H:i:s'),
                        'post_slug' => $post_slug
                    );
                    $ssqldrm = $db->insert('posts', $data);
                    if ($ssqldrm) {
                        if ($_FILES['image']['error'] == 0) {
                            $p = $db->rawQuery("SELECT t1.setType,t2.type_meta as genislik,t3.type_meta as yukseklik FROM bwp_settings_meta t1,bwp_settings_meta t2,bwp_settings_meta t3 WHERE t1.type_cat = 'image' AND t2.type_cat = 'image' AND t3.type_cat = 'image' AND t1.setType = t2.setType AND t1.setType = t3.setType AND t2.type = 'genislik' AND t3.type = 'yukseklik' GROUP BY t1.setType,t2.type_meta,t3.type_meta");

                            $handle = new \Verot\Upload\Upload($_FILES['image']);
                            foreach ($p as $v) {
                                if ($handle->uploaded) {
                                    $handle->file_new_name_body   = $post_slug;
                                    $handle->image_resize         = true;
                                    $handle->image_convert = 'webp';
                                    $handle->image_x = $v['genislik'];
                                    $handle->image_y = $v['yukseklik'];
                                    $handle->image_ratio = true;
                                    $handle->image_ratio_crop = true;
                                    $handle->process("../bwp-content/uploads/posts/" . $v['genislik'] . "x" . $v['yukseklik'] . "/");
                                    if ($handle->processed) {
                                        $ResimName = $handle->file_dst_name;
                                    } else {
                                        echo $handle->error;
                                    }
                                }
                            }
                            $data = array(
                                'postID' => $ssqldrm,
                                'type' => 'image',
                                'type_meta' => $ResimName
                            );
                            $db->insert('post_meta', $data);
                        }
                        if ($_FILES['pdf']['error'] == 0) {
                            $fileType = $yazi->yazibol("/", $_FILES['pdf']['type']);
                            if ($fileType[1] == "pdf") {
                                $foo = new \Verot\Upload\Upload($_FILES['pdf']);
                                if ($foo->uploaded) {
                                    $foo->file_new_name_body = $post_slug;
                                    $foo->image_resize = false;
                                    $foo->image_ratio = false;
                                    $foo->image_ratio_crop = false;
                                    $foo->process("../bwp-content/uploads/posts/pdf/");
                                    if ($foo->processed) {
                                        $data = array(
                                            'postID' => $ssqldrm,
                                            'type' => 'pdf',
                                            'type_meta' => $foo->file_dst_name
                                        );
                                        $db->insert('post_meta', $data);
                                    } else {
                                        $idata = array("title" => "İşlem Başarısız!", "message" => "Medya Eklemedi", "type" => "error", "error" => $foo);
                                    }
                                }
                            }
                        }
                        foreach ($_POST['cat'] as $k => $v) {
                            $data = array(
                                'postID' => $ssqldrm,
                                'type' => 'cat',
                                'type_meta' => $v
                            );
                            $db->insert('post_meta', $data);
                        }
                        $idata = array("title" => "İşlem Başarılı", "message" => "Yazı Eklendi", "type" => "success", "error" => "");
                    } else {
                        $idata = array("title" => "İşlem Başarısız", "message" => "Yazı Eklenmedi", "type" => "error", "error" => $db->getLastError());
                    }
                } else {
                    $idata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " yazı ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "postadd");
                }
            } else if ($_POST['type'] == "edit") {
                $yetki = $db->yetkikont("postedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $post_slug = $yazi->urlKisalt($yazi->seourl($_POST['title']), 30) ;
                    $data = array(
                        'post_title' => stripslashes($_POST['title']),
                        'post_content' => $_POST['tinymce'],
                        'post_status' => $_POST['durum'],
                        'post_langID' => $_POST['langID'],
                        'post_comment' => $_POST['post_comment'],
                        'post_author' => $userKont['id'],
                        'post_date' => date('Y-m-d H:i:s'),
                        'post_slug' => $post_slug
                    );
                    $db->where("ID", $_POST['id']);
                    $ssqldrm = $db->update('posts', $data);
                    if ($ssqldrm) {
                        if ($_FILES['image']['error'] == 0) {
                            $db->where("postID", $_POST['id']);
                            $db->where("type", "image");
                            $db->delete('post_meta');

                            $p = $db->rawQuery("SELECT t1.setType,t2.type_meta as genislik,t3.type_meta as yukseklik FROM bwp_settings_meta t1,bwp_settings_meta t2,bwp_settings_meta t3 WHERE t1.type_cat = 'image' AND t2.type_cat = 'image' AND t3.type_cat = 'image' AND t1.setType = t2.setType AND t1.setType = t3.setType AND t2.type = 'genislik' AND t3.type = 'yukseklik' GROUP BY t1.setType,t2.type_meta,t3.type_meta");
                            $handle = new \Verot\Upload\Upload($_FILES['image']);
                            foreach ($p as $v) {
                                if ($handle->uploaded) {
                                    $handle->file_new_name_body   = $post_slug;
                                    $handle->image_resize         = true;
                                    $handle->image_convert = 'webp';
                                    $handle->image_x = $v['genislik'];
                                    $handle->image_y = $v['yukseklik'];
                                    $handle->image_ratio = true;
                                    $handle->image_ratio_crop = true;
                                    $handle->process("../bwp-content/uploads/posts/" . $v['genislik'] . "x" . $v['yukseklik'] . "/");
                                    if ($handle->processed) {
                                        $ResimName = $handle->file_dst_name;
                                    } else {
                                        echo $handle->error;
                                    }
                                }
                            }
                            $isdata = array(
                                'postID' => $_POST['id'],
                                'type' => 'image',
                                'type_meta' => $ResimName
                            );
                            $db->insert('post_meta', $isdata);
                        }
                        if ($_FILES['pdf']['error'] == 0) {
                            $fileType = $yazi->yazibol("/", $_FILES['pdf']['type']);
                            if ($fileType[1] == "pdf") {
                                $db->where("postID", $_POST['id']);
                                $db->where("type", "pdf");
                                $db->delete('post_meta');
                                $foo = new \Verot\Upload\Upload($_FILES['pdf']);
                                if ($foo->uploaded) {
                                    $foo->file_new_name_body = $post_slug;
                                    $foo->image_resize = false;
                                    $foo->image_ratio = false;
                                    $foo->image_ratio_crop = false;
                                    $foo->process("../bwp-content/uploads/posts/pdf/");
                                    if ($foo->processed) {
                                        $pdata = array(
                                            'postID' => $_POST['id'],
                                            'type' => 'pdf',
                                            'type_meta' => $foo->file_dst_name
                                        );
                                        $db->insert('post_meta', $pdata);
                                    } else {
                                        $idata = array("title" => "İşlem Başarısız!", "message" => "Medya Eklemedi", "type" => "error", "error" => $foo);
                                    }
                                }
                            }
                        }

                        $db->where("type", "cat");
                        $db->where("postID", $_POST['id']);
                        $db->delete('post_meta');
                        foreach ($_POST['cat'] as $k => $v) {
                            $cadata = array(
                                'postID' => $_POST['id'],
                                'type' => 'cat',
                                'type_meta' => $v
                            );
                            $db->insert('post_meta', $cadata);
                        }
                        $idata = array("title" => "İşlem Başarılı", "message" => "Yazı Düzenlendi", "type" => "success", "error" => "");
                    } else {
                        $idata = array("title" => "İşlem Başarısız", "message" => "Yazı Düzenlenmedi", "type" => "error", "error" => $db->getLastError());
                    }
                } else {
                    $idata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " yazı düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "postedit");
                }
            } else if ($_POST['type'] == "delete") {
                $yetki = $db->yetkikont("postdel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('ID', $_POST['id']);
                    $pknt = $db->getOne('posts');
                    if (count($pknt)) {
                        $db->where("postID", $pknt["ID"]);
                        $db->where("type", "image");
                        $image = $db->getOne("post_meta");

                        $p = $db->rawQuery("SELECT t1.setType,t2.type_meta as genislik,t3.type_meta as yukseklik FROM bwp_settings_meta t1,bwp_settings_meta t2,bwp_settings_meta t3 WHERE t1.type_cat = 'image' AND t2.type_cat = 'image' AND t3.type_cat = 'image' AND t1.setType = t2.setType AND t1.setType = t3.setType AND t2.type = 'genislik' AND t3.type = 'yukseklik' GROUP BY t1.setType,t2.type_meta,t3.type_meta");

                        foreach ($p as $v) {
                            unlink("../bwp-content/uploads/posts/" . $v['genislik'] . "x" . $v['yukseklik'] . "/" . $image['type_meta'] . "");
                        }

                        $db->where("postID", $pknt["ID"]);
                        $db->where("type", "pdf");
                        $pdf = $db->getOne("post_meta");
                        unlink("../bwp-content/uploads/posts/pdf/" . $pdf['type_meta'] . "");

                        $db->where('ID', $pknt["ID"]);
                        $ssqldrm = $db->delete('posts');
                        if ($ssqldrm) {
                            $db->where('postID', $pknt["ID"]);
                            $db->delete('post_meta');
                            $idata = array("title" => "İşlem Başarılı", "message" => "Yazı Silindi", "type" => "success", "error" => $db->getLastError());
                        } else {
                            $idata = array("title" => "İşlem Başarısız", "message" => "Yazı Silinmedi", "type" => "error", "error" => $db->getLastError());
                        }
                    } else {
                        $idata = array("title" => "İşlem Başarısız", "message" => "Silmek istediğiniz içerik bulunamadı!", "type" => "error");
                    }
                } else {
                    $idata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " yazı düzenleme işlemi için yetkin yok!", "type" => "error", "error" => "postedit");
                }
            }
        } else {
            $idata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "postset");
        }
        $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        print_r($json);
    }
} else if ($islem == "cats") {
    if ($process == "view") {
        $yetki = $db->yetkikont("catview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $pagesql = $db->get("postcat");
            foreach ($pagesql as $key => $value) {
                if ($value['catID'] == 0) {
                    $termTitle = "Ana Kategori";
                } else {
                    $db->where("id", $value['catID']);
                    $catTerm = $db->getOne('postcat');
                    $termTitle = $catTerm['title'];
                }
                $db->where("id", $value['langID']);
                $taglang = $db->getOne('langs');

                $darray["data"][] = array(
                    $value['id'],
                    $termTitle,
                    $value['title'],
                    $taglang['title'],
                    '<span class="dropdown">
                        <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="la la-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                <i class="la la-edit"></i> Düzenle
                            </a>
                            <a href="?process=delete&id=' . $value['id'] . '"class="dropdown-item">
                                <i class="la la-remove"></i> Sil
                            </a>
                        </div>
                    </span>'
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kategori görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "catview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $langID = $_POST['langID'];
            $catID = $_POST['catID'];
            $title = $_POST['title'];
            $slug = $yazi->seoUrl($title);
            if ($_POST['type'] == "edit") {
                $yetki = $db->yetkikont("catedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $data = array(
                        'catID' => $catID,
                        'langID' => $langID,
                        'title' => $title,
                        'url' => $slug,
                        'img' => $_POST['resim']
                    );
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('postcat', $data);

                    if ($ssqldrm) {
                        $data = array("message" => "Kategori Düzenlendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Kategori Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kategori düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "catedit");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "add") {
                $yetki = $db->yetkikont("catadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $data = array(
                        'catID' => $catID,
                        'langID' => $langID,
                        'title' => $title,
                        'url' => $slug,
                        'img' => $_POST['resim']
                    );
                    $ssqldrm = $db->insert('postcat', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Kategori Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Kategori Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kategori ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "catadd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "delete") {
                $yetki = $db->yetkikont("catdel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where("id", $setID);
                    $image = $db->getOne("postcat");
                    $p = $db->rawQuery("SELECT t1.setType,t2.type_meta as genislik,t3.type_meta as yukseklik FROM bwp_settings_meta t1,bwp_settings_meta t2,bwp_settings_meta t3 WHERE t1.type_cat = 'image' AND t2.type_cat = 'image' AND t3.type_cat = 'image' AND t1.setType = t2.setType AND t1.setType = t3.setType AND t2.type = 'genislik' AND t3.type = 'yukseklik' GROUP BY t1.setType,t2.type_meta,t3.type_meta");
                    foreach ($p as $v) {
                        unlink("../bwp-content/uploads/cats/" . $v['genislik'] . "x" . $v['yukseklik'] . "/" . $image['fileName'] . "");
                    }
                    $db->where('id', $setID);
                    $ssqldrm = $db->delete('postcat');
                    if ($ssqldrm) {
                        $data = array("message" => "Kategori Silindi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Kategori Silinemedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kategori silme işlemi için yetkin yok!", "type" => "danger", "error" => "catdelete");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "catMedia") {
                $fileType = $yazi->yazibol("/", $_FILES['file']['type']);
                $p = $db->rawQuery("SELECT t1.setType,t2.type_meta as genislik,t3.type_meta as yukseklik FROM bwp_settings_meta t1,bwp_settings_meta t2,bwp_settings_meta t3 WHERE t1.type_cat = 'image' AND t2.type_cat = 'image' AND t3.type_cat = 'image' AND t1.setType = t2.setType AND t1.setType = t3.setType AND t2.type = 'genislik' AND t3.type = 'yukseklik' GROUP BY t1.setType,t2.type_meta,t3.type_meta");
                $imgText = $yazi->yazibol(".", $_FILES['file']['name']);
                $url = $yazi->seourl($imgText[0]);
                foreach ($p as $key => $v) {
                    $foo = new \Verot\Upload\Upload($_FILES['file']);
                    if ($foo->uploaded) {
                        $foo->file_new_name_body = '' . $url . '';
                        $foo->image_resize = true;
                        $foo->image_convert = 'webp';
                        $foo->image_x = $v['genislik'];
                        $foo->image_y = $v['yukseklik'];
                        $foo->image_ratio = true;
                        $foo->image_ratio_crop = true;
                        $foo->process("../bwp-content/uploads/cats/" . $v['genislik'] . "x" . $v['yukseklik'] . "/");
                    }
                }
                if ($foo->processed) {
                    $idata = array("title" => "İşlem Başarılı!", "message" => "Medya Eklendi", "type" => "success", "filename" => $foo->file_dst_name, "fileurl" => $foo->file_dst_name, "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız!", "message" => "Medya Eklemedi  <br/>" . $foo->error, "type" => "success", "error" => $foo->error);
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "sets") {
    if ($process == "view") {
        $yetki = $db->yetkikont("setview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $db->orderBy("id", "desc");
            $pagesql = $db->get("settings");
            foreach ($pagesql as $key => $value) {
                $db->where("id", $value['langID']);
                $langs = $db->getOne('langs');

                $edit = $db->yetkikont("setedit", "" . $userKont['id'] . "");
                if ($edit == 1) {
                    $edtbtn = '<a href="setting.php?process=edit&id=' . $value['id'] . '" class="btn btn-dark"><i class="la la-edit "></i> Düzenle</a>';
                }
                $del = $db->yetkikont("setdel", "" . $userKont['id'] . "");
                if ($del == 1) {
                    //$delbtn = '<a href="?process=delete&id='.$value['id'].'"class="dropdown-item"><i class="la la-remove"></i> Sil</a>';
                    $delbtn = '<button type="button" class="btn btn-danger" name="set" data-id="' . $value['id'] . '" data-type="delete"><i class="la la-remove"></i> Sil</button>';
                }
                if ($edit == 1 || $del == 1) {
                    $pmenu = '<span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                    <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    ' . $edtbtn . ' 
                                </div>
                            </span>';
                } else {
                    $pmenu = "<div class='alert alert-danger'>Ayar düzenleme ve silme yetkiniz bulunmuyor</div>";
                }
                if ($value['aktif'] == 1) {
                    $btn = '<button class="btn btn-success" name="set" disabled>Varsayılan Ayar</div>';
                } else {
                    $btn = '<button type="button" class="btn btn-dark" name="set" data-id="' . $value['id'] . '" data-type="aktif">Varsayılan Yap</button>';
                }
                $darray["data"][] = array(
                    $value['id'],
                    $btn,
                    $langs['title'],
                    $value['siteurl'],
                    $value['baslik'],
                    $value['aciklama'],
                    $value['keywords'],
                    $value['adres'],
                    $edtbtn . '' . $delbtn
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " ayarları görüntülemek için yetkin yok!", "type" => "danger", "error" => "setview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $tags = str_replace("\&quot;", '"', $_POST['kelime']);
            $kelime = json_decode($tags, true);
            $arraySize = count($kelime);
            $keywords = "";
            for ($i = 0; $i < $arraySize; $i++) {
                $keywords .= $kelime[$i]['value'] . ',';
            }
            $data = array(
                'baslik' => $_POST['baslik'],
                'aciklama' => $_POST['aciklama'],
                'keywords' => $keywords,
                'siteurl' => $_POST['siteurl'],
                'langID' => $_POST['langID'],
                'eposta' => $_POST['eposta'],
                'tel' => $_POST['tel'],
                'fax' => $_POST['fax'],
                'gsm' => $_POST['gsm'],
                'adres' => $_POST['adres'],
                'lat' => $_POST['lat'],
                'lng' => $_POST['lng'],
                'mailHost' => $_POST['mailHost'],
                'mailPort' => $_POST['mailPort'],
                'mailUser' => $_POST['mailUser'],
                'mailPass' => $_POST['mailPass']
            );
            if ($_POST['type'] == "edit") {
                $yetki = $db->yetkikont("setedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('settings', $data);
                    if ($ssqldrm) {
                        $jdata = array("message" => "Ayar Düzenlendi", "type" => "success", "error" => "");
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $jdata = array("message" => "Ayar Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $jdata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " ayar düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "setedit");
                    $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "add") {
                $yetki = $db->yetkikont("setadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $ssqldrm = $db->insert('settings', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Ayar Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Ayar Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " ayar ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "setadd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "delete") {
                $yetki = $db->yetkikont("setdel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->delete('settings');
                    if ($ssqldrm) {
                        $data = array("message" => "Ayar Silindi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Ayar Silinemedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " Ayar silme işlemi için yetkin yok!", "type" => "danger", "error" => "setdel");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "aktif") {
                $yetki = $db->yetkikont("defaultset", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $a = array('aktif' => 0);
                    $db->update('settings', $a);

                    $b = array('aktif' => 1);
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('settings', $b);

                    if ($ssqldrm) {
                        $data = array("message" => "Ayar varsayılan olarak seçildi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Ayar varsayılan olarak seçilemedi!", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " varsayılan ayar atama yetkin yok!", "type" => "danger", "error" => "defaultset");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "postset");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "mail") {
    if ($process == "view") {
        $yetki = $db->yetkikont("mailview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $pagesql = $db->get("mailTemplate");
            foreach ($pagesql as $key => $value) {
                $ftName = "";
                foreach (json_decode($value['sablonmesaji']) as $k => $v) {
                    $db->where("subtitle", $k);
                    $fl = $db->getOne("langs");
                    $ftName .= '<small>' . $fl['title'] . '</small>' . '-><b>' . $v . '</b><br>';
                }
                $ft2Name = "";
                foreach (json_decode($value['sablonbasligi']) as $k => $v) {
                    $db->where("subtitle", $k);
                    $fl = $db->getOne("langs");
                    $ft2Name .= '<small>' . $fl['title'] . '</small>' . '-><b>' . $v . '</b><br>';
                }
                $darray["data"][] = array(
                    $value['type'],
                    $value['senderMail'],
                    $value['senderTitle'],
                    $ftName,
                    $ft2Name,
                    '<a href="?process=edit&id=' . $value['id'] . '" class="btn btn-dark"><i class="la la-edit"></i> Düzenle</a>'
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " ayarları görüntülemek için yetkin yok!", "type" => "danger", "error" => "setview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $data = array(
                'senderMail' => $_POST['senderMail'],
                'senderTitle' => $_POST['senderTitle'],
                'sablonmesaji' => json_encode($_POST['sablonmesaji'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG),
                'sablonbasligi' => json_encode($_POST['sablonbasligi'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG),
                'reciverTemplate' => $_POST['reciverTemplate'],
                'adminTemplate' => $_POST['adminTemplate'],
                'senderTitle' => $_POST['senderTitle'],
                'adminMail' => $_POST['adminMail'],
                'adminTitle' => $_POST['adminTitle']
            );
            if ($_POST['type'] == "edit") {
                $yetki = $db->yetkikont("mailEdit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('mailTemplate', $data);
                    if ($ssqldrm) {
                        $idata = array("title" => "İşlem Başarılı", "message" => "Şablon Düzenlendi", "type" => "success", "error" => "");
                    } else {
                        $idata = array("title" => "İşlem Başarısız", "message" => "Şablon Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                    }
                } else {
                    $idata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " şablon düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "mailEdit");
                }
            }
        } else {
            $idata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
        }

        $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        print_r($json);
    }
} else if ($islem == "langs") {
    if ($process == "view") {
        $yetki = $db->yetkikont("langview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $pagesql = $db->get('langs');
            foreach ($pagesql as $key => $value) {
                $edit = $db->yetkikont("langedit", "" . $userKont['id'] . "");
                if ($edit == 1) {
                    $edtbtn = '<a href="?process=edit&id=' . $value['id'] . '" class="btn btn-dark"><i class="la la-edit "></i> Düzenle</a>';
                }
                $del = $db->yetkikont("langdel", "" . $userKont['id'] . "");
                if ($del == 1) {
                    $delbtn = '<button type="button" class="btn btn-danger" name="set" data-id="' . $value['id'] . '" data-type="delete"><i class="la la-remove"></i> Sil</button>';
                }
                $darray["data"][] = array(
                    $value['id'],
                    $value['title'],
                    $value['subtitle'],
                    '<img src="../bwp-content/uploads/langs/' . $value['img'] . '" alt="' . $value['title'] . '">',
                    $edtbtn . '' . $delbtn
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kategori görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "catview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "langvari") {
        $yetki = $db->yetkikont("langview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $pagesql = $db->get('langs');
            foreach ($pagesql as $key => $value) {

                $darray["data"][] = array(
                    $value['type'],
                    $value['type_meta']
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kategori görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "catview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $title = $_POST['title'];
            $subtitle = $_POST['subtitle'];
            $img = $_POST['flag'];
            $url = $yazi->seoUrl($subtitle);
            $data = array(
                'title' => $title,
                'subtitle' => $subtitle,
                'img' => $img,
                'url' => $url
            );
            if ($_POST['type'] == "edit") {
                $yetki = $db->yetkikont("langedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('langs', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Dil Düzenlendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Dil Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " dil düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "tagedit");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "add") {
                $yetki = $db->yetkikont("langadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $ssqldrm = $db->insert('langs', $data);
                    $lastId = $db->getInsertId();
                    if ($ssqldrm) {
                        $db->where("langID", "1");
                        foreach ($db->get("langs_meta") as $item) {
                            $data = array(
                                'langID' => $lastId,
                                'type' => $item['type'],
                                'type_meta' => ''
                            );
                            $db->insert('langs_meta', $data);
                        }
                        $data = array("message" => "Dil Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Dil Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " dil ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "tagadd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "delete") {
                $yetki = $db->yetkikont("langdel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->delete('langs');
                    if ($ssqldrm) {
                        $db->where('langID', $setID);
                        $db->delete('langs_meta');
                        $data = array("message" => "Dil Silindi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Dil Silinemedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " dil silme işlemi için yetkin yok!", "type" => "danger", "error" => "tagdel");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "langsdir") {
                $dir = "../bwp-content/uploads/langs/";
                $images = glob("" . $dir . "*.{jpg,png}", GLOB_BRACE);
                foreach ($images as $key => $value) {
                    $name =  $yazi->yazibol($dir, $value);
                    if (!is_null($setID)) {
                        $db->where('id', $setID);
                        $knt = $db->getOne('langs');
                        if ($knt['img'] == $name[1]) {
                            echo '<option value="' . $name[1] . '" selected>' . $name[1] . '</option>';
                        } else {
                            echo '<option value="' . $name[1] . '">' . $name[1] . '</option>';
                        }
                    } else {
                        echo '<option value="' . $name[1] . '">' . $name[1] . '</option>';
                    }
                }
            } else if ($_POST['type'] == "langvarup") {
                $yetki = $db->yetkikont("langedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $data = array(
                        'type_meta' => $_POST['val'],
                    );
                    $db->where('id', $_POST['id']);
                    $ssqldrm = $db->update('langs_meta', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Düzenlendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Dil Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " dil düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "tagedit");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "langvaradd") {
                $yetki = $db->yetkikont("langadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    foreach ($_POST['type_meta'] as $k => $v) {
                        $datap[$k]['langID'] = $k;
                        $datap[$k]['type'] = $_POST['title'];
                        $datap[$k]['type_meta'] = $v;
                    }
                    $ssqldrm = $db->insertMulti('langs_meta', $datap);
                    if ($ssqldrm) {
                        $idata = array("title" => "İşlem Başarılı", "message" => "Değişken Eklendi", "type" => "success");
                    } else {
                        $idata = array("title" => "İşlem Başarısız", "message" => "Değişken eklenmedi", "type" => "error", "error" => $db->getLastError());
                    }
                } else {
                    $idata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " dil değişkeni ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "tagedit");
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "catList") {
    $db->where("langID", $_POST['langID']);
    $db->where("catID", 0);
    $catList = $db->get("postcat");
    foreach ($catList as $cp) {
        $db->where("type", "cat");
        $db->where("type_meta", $cp["id"]);
        $postcat = $db->getOne("post_meta");
        echo '<label class="col-md-12 kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success">
                <input type="checkbox" name="cat" value="' . $cp['id'] . '"> ' . $cp['title'] . '
                <span></span>
            </label>';
        $db->where("catID", $cp['id']);
        $catAltList = $db->get("postcat");
        foreach ($catAltList as $cap) {
            $db->where("type", "cat");
            $db->where("type_meta", $cap["id"]);
            $postcat = $db->getOne("post_meta");
            echo '<label class="col-md-12 kt-checkbox ml-3 kt-checkbox--bold kt-checkbox--warning">
                <input type="checkbox" name="cat" value="' . $cap['id'] . '"> ' . $cap['title'] . '
                <span></span>
            </label>';
            $db->where("catID", $cap['id']);
            $catAltList = $db->get("postcat");
            foreach ($catAltList as $cap) {
                $db->where("type", "cat");
                $db->where("type_meta", $cap["id"]);
                $postcat = $db->getOne("post_meta");
                echo '<label class="col-md-12 kt-checkbox ml-5 kt-checkbox--bold kt-checkbox--danger">
                <input type="checkbox" name="cat" value="' . $cap['id'] . '"> ' . $cap['title'] . '
                <span></span>
            </label>';
            }
        }
    }
} else if ($islem == "theme") {
    if ($process == "view") {
        $yetki = $db->yetkikont("themeview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $dir = "../bwp-content/themes/";
            $dh  = opendir($dir);
            while (false !== ($filename = readdir($dh))) {
                if (is_dir($dir . $filename)) {
                    if ($filename != "." && $filename != "..") {
                        $files[] = $filename;
                    }
                }
            }
            closedir($dh);
            foreach ($files as $key => $value) {
                include '../bwp-content/themes/' . $value . '/themeAdmin/information.php';
                $themeImg = "../bwp-content/themes/" . $value . "/assets/img/theme.jpg";
                echo '<div class="col-sm-3">
                    <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                        <div class="kt-portlet__body kt-portlet__body--fit kt-portlet__body--unfill">
                            <div class="kt-widget19__pic kt-portlet-fit--top kt-portlet-fit--sides" style="min-height: 300px; background-image: url(' . $themeImg . ')">
                                <h3 class="kt-widget19__title kt-font-light">' . $themeArray['name'] . '</h3>
                                <div class="kt-widget19__shadow"></div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-widget19__wrapper mb-0">
                                <div class="kt-widget19__content mb-0">
                                    <div class="row col-sm-12">
                                        <div class="col-sm-8">
                                            <div class="col-sm-12"><span class="kt-widget19__username">' . $themeArray['author'] . '</span></div>
                                            <div class="col-sm-12"><span class="kt-widget19__time">' . $themeArray['developer'] . '</span></div>
                                            <div class="col-sm-12"><span class="kt-widget19__time">' . $themeArray['version'] . '</span></div>
                                        </div>
                                        <div class="col-sm-4 my-auto">
                                            <form id="form' . $key . '">
                                                <input type="hidden" name="themeDir" value="' . $value . '">
                                                <input type="hidden" name="name" value="' . $themeArray['name'] . '">
                                                <input type="hidden" name="author" value="' . $themeArray['author'] . '">
                                                <input type="hidden" name="company" value="' . $themeArray['developer'] . '">
                                                <input type="hidden" name="version" value="' . $themeArray['version'] . '">
                                                <button type="button" name="set" data-type="active" data-themeid="form' . $key . '" class="btn btn-sm btn-label-brand btn-bold">Aktif Et</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kategori görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "catview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            if ($_POST['type'] == "active") {
                $yetki = $db->yetkikont("themeactive", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where("themeDir", $_POST['themeDir']);
                    $themeKont = $db->getOne("theme");
                    if ($themeKont['themeDir'] == $_POST['themeDir']) {
                        $data = array('aktif' => 0);
                        $db->where('aktif', 1);
                        $dbstatus = $db->update('theme', $data);
                        if ($dbstatus) {
                            $jdata = array("message" => "Tema Değiştirildi " . $db->getLastQuery() . "", "type" => "success", "error" => "", "themeID" => $dbstatus);
                            $data2 = array('aktif' => 1);
                            $db->where('id', $themeKont['id']);
                            $db->update('theme', $data2);
                            $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                            print_r($json);
                        } else {
                            $jdata = array("message" => "Tema Değiştirilemedi", "type" => "danger", "error" => $db->getLastError());
                            $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                            print_r($json);
                        }
                    } else {
                        $data = array(
                            'aktif' => 1,
                            'themeDir' => $_POST['themeDir'],
                            'themeName' => $_POST['name'],
                            'author' => $_POST['author'],
                            'company' => $_POST['company'],
                            'createDate' => $_POST['createDate'],
                            'version' => $_POST['version']
                        );
                        $dbstatus = $db->insert('theme', $data);
                        if ($dbstatus) {
                            $data = array("message" => "Tema Değiştirildi", "type" => "success", "error" => "", "themeID" => $dbstatus);
                            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                            print_r($json);
                        } else {
                            $data = array("message" => "Tema Değiştirilemedi", "type" => "danger", "error" => $db->getLastError());
                            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                            print_r($json);
                        }
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " tema değiştirme işlemi için yetkin yok!", "type" => "danger", "error" => "themeactive");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "image") {
    if ($process == "view") {
        $yetki = $db->yetkikont("setimageview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $db->where("type_cat", "image");
            $pagesql = $db->get("settings_meta");
            foreach ($pagesql as $key => $value) {
                $edit = $db->yetkikont("setimageedit", "" . $userKont['id'] . "");
                if ($edit == 1) {
                    $edtbtn = '<a href="?process=edit&id=' . $value['id'] . '" class="btn btn-dark"><i class="la la-edit "></i> Düzenle</a>';
                }
                $del = $db->yetkikont("setimagedel", "" . $userKont['id'] . "");
                if ($del == 1) {
                    $delbtn = '<button type="button" class="btn btn-danger" name="set" data-id="' . $value['id'] . '" data-type="delete"><i class="la la-remove"></i> Sil</button>';
                }
                $darray["data"][] = array(
                    $value['id'],
                    $value['setType'],
                    $value['type'],
                    $value['type_meta'],
                    $edtbtn . '' . $delbtn
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " kategori görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "catview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $setType = $_POST['setType'];
            $type = $_POST['type'];
            $type_meta = $_POST['type_meta'];
            $data = array(
                'setType' => $setType,
                'type' => $type,
                'type_meta' => $type_meta
            );
            if ($_POST['tip'] == "edit") {
                $yetki = $db->yetkikont("setimageedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('settings_meta', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Ayar Düzenlendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Ayar Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " resim ayarlarını düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "setimageedit");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['tip'] == "add") {
                $yetki = $db->yetkikont("setimageadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $ssqldrm = $db->insert('settings_meta', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Ayar Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Ayar Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " resim boyut ayarı ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "setimageadd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['tip'] == "delete") {
                $yetki = $db->yetkikont("setimagedel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->delete('settings_meta');
                    if ($ssqldrm) {
                        $data = array("message" => "Ayar Silindi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Ayar Silinemedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " etiket silme işlemi için yetkin yok!", "type" => "danger", "error" => "setimagedel");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "menu") {
    if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $menudata = str_replace("\&quot;", '"', $_POST['json']);
            $data = array(
                'menu_title' => stripslashes($_POST['title']),
                'menu_position' => $_POST['position'],
                'menu_langID' => $_POST['langID'],
                'menu_author' => $_POST['author'],
                'menu_json' => $menudata
            );
            if ($_POST['tip'] == "add") {
                $yetki = $db->yetkikont("menuadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $ssqldrm = $db->insert('menu', $data);
                    if ($ssqldrm) {
                        $jdata = array("message" => "Menü Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $jdata = array("message" => "Menü Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $jdata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " menü ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "menuadd");
                    $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['tip'] == "edit") {
                $yetki = $db->yetkikont("menuedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where("id", $setID);
                    $menuKont = $db->getOne("menu");
                    if ($menuKont['id'] == $setID) {
                        $db->where("id", $setID);
                        $ssqldrm = $db->update('menu', $data);
                        if ($ssqldrm) {
                            $jdata = array("message" => "Menü Düzenlendi", "type" => "success", "error" => "");
                            $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                            print_r($json);
                        } else {
                            $jdata = array("message" => "Menü Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                            $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                            print_r($json);
                        }
                    } else {
                        $jdata = array("message" => "Düzenlemeye çalıştığınız menü veritabanında bulunamadı", "type" => "danger", "error" => "hack girişimi olabilir!");
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $jdata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " menü düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "menuedit");
                    $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['tip'] == "page") {
                if (isset($_POST)) {
                    if (strlen($_POST['key']) >= 3) {
                        $db->where("title", "%" . $_POST['key'] . "%", "like");
                        $db->orderBy("langID", "asc");
                        $pagesql = $db->get("page");
                    } else {
                        $db->orderBy("langID", "asc");
                        $pagesql = $db->get("page", 20);
                    }
                } else {
                    $db->orderBy("langID", "asc");
                    $pagesql = $db->get("page", 20);
                }
                foreach ($pagesql as $p) {
                    $db->where("id", $p['langID']);
                    $lang = $db->getOne("langs");
                    $darray["data"][] = array(
                        $p['id'],
                        '<b>' . $lang['title'] . '</b> -> ' . $p['title']
                    );
                }
                $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            } else if ($_POST['tip'] == "post") {
                if (isset($_POST)) {
                    if (strlen($_POST['key']) >= 3) {
                        $db->where("post_title", "%" . $_POST['key'] . "%", "like");
                        $db->orderBy("ID", "desc");
                        $pagesql = $db->get("posts");
                        $db->where("post_title", "%" . $_POST['key'] . "%", "like");
                        $db->orderBy("ID", "desc");
                        $pagesql1 = $db->get("educations");
                        $db->where("post_title", "%" . $_POST['key'] . "%", "like");
                        $db->orderBy("ID", "desc");
                        $pagesql2 = $db->get("services");

                        $pagesql = array_merge($pagesql,$pagesql1,$pagesql2);
                    } else {
                        $db->orderBy("ID", "desc");
                        $pagesql = $db->get("posts", 20);
                        $db->orderBy("ID", "desc");
                        $pagesql2 = $db->get("educations", 20);
                        $db->orderBy("ID", "desc");
                        $pagesql3 = $db->get("services", 20);
                        $pagesql = array_merge($pagesql,$pagesql2,$pagesql3);
                    }
                } else {
                    $db->orderBy("ID", "desc");
                    $pagesql = $db->get("posts", 20);
                    $db->orderBy("ID", "desc");
                    $pagesql2 = $db->get("educations", 20);
                    $db->orderBy("ID", "desc");
                    $pagesql3 = $db->get("services", 20);
                    $pagesql = array_merge($pagesql,$pagesql2,$pagesql3);
                }
                foreach ($pagesql as $p) {
                    $darray["data"][] = array(
                        $p['ID'],
                        '<b>' . $p['ID'] . '</b> -> ' . $p['post_title']
                    );
                }
                $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            } else if ($_POST['tip'] == "postkat") {
                $cats = $_POST['cat'];
                $catss = $yazi->yazibol(",", $cats);
                $catSize = count($catss);
                $catArray = array();
                for ($i = 0; $i < $catSize; $i++) {
                    $db->where("id", $catss[$i]);
                    $terms = $db->getOne("postcat");
                    $catArray[] = array(
                        "href" => $terms['url'],
                        "icon" => "",
                        "text" => $terms['title'],
                        "target" => "_self",
                        "title" => $terms['title'],
                        "type" => "kategori"
                    );
                    $json = json_encode($catArray);
                }
                echo $json;
            } else if ($_POST['tip'] == "mPost") {
                $pages = $_POST['page'];
                $pagess = $yazi->yazibol(",", $pages);
                $pageSize = count($pagess);
                $pageArray = array();
                for ($i = 0; $i < $pageSize; $i++) {
                    $db->where("ID", $pagess[$i]);
                    $terms = $db->getOne("posts");
                    $catArray[] = array(
                        "href" => $terms['post_slug'],
                        "icon" => "",
                        "text" => $terms['post_title'],
                        "target" => "_self",
                        "title" => $terms['post_title'],
                        "type" => "yazi"
                    );
                    $json = json_encode($catArray);
                }
                echo $json;
            } else if ($_POST['tip'] == "mPage") {
                $pages = $_POST['page'];
                $pagess = $yazi->yazibol(",", $pages);
                $pageSize = count($pagess);
                $pageArray = array();
                for ($i = 0; $i < $pageSize; $i++) {
                    $db->where("ID", $pagess[$i]);
                    $terms = $db->getOne("page");
                    $catArray[] = array(
                        "href" => $terms['url'],
                        "icon" => "",
                        "text" => $terms['title'],
                        "target" => "_self",
                        "title" => $terms['title'],
                        "type" => "sayfa"
                    );
                    $json = json_encode($catArray);
                }
                echo $json;
            } else if ($_POST['tip'] == "mGaleri") {
                $pages = $_POST['galeri'];
                $pagess = $yazi->yazibol(",", $pages);
                $pageSize = count($pagess);
                $pageArray = array();
                for ($i = 0; $i < $pageSize; $i++) {
                    $db->where("id", $pagess[$i]);
                    $terms = $db->getOne("img");
                    $catArray[] = array(
                        "href" => $terms['url'],
                        "icon" => "",
                        "text" => $terms['title'],
                        "target" => "_self",
                        "title" => $terms['title'],
                        "type" => "galeri"
                    );
                    $json = json_encode($catArray);
                }
                echo $json;
            } else if ($_POST['tip'] == "url") {
                $text = $_POST['text'];
                $href = $_POST['href'];
                $target = $_POST['target'];
                $urlArray[] = array(
                    "href" => $href,
                    "icon" => "",
                    "text" => $text,
                    "target" => $target,
                    "title" => $text,
                    "type" => "url"
                );
                $json = json_encode($urlArray);
                echo $json;
            } else if ($_POST['tip'] == "turkat") {
                $cats = $_POST['cat'];
                $catss = $yazi->yazibol(",", $cats);
                $catSize = count($catss);
                $catArray = array();
                for ($i = 0; $i < $catSize; $i++) {
                    $db->where("id", $catss[$i]);
                    $selectCat = $db->getOne("tourcat");
                    if ($selectCat['catID'] == 0) {
                        $catArray[] = array(
                            "href" => $selectCat['url'],
                            "icon" => "",
                            "target" => "_self",
                            "text" => $selectCat['title'],
                            "title" => $selectCat['title'],
                            "detail" => $selectCat['detail'],
                            "image" => $selectCat['img'],
                            "type" => "tur",
                        );
                    } else {
                        $db->where("id", $catss[$i]);
                        $selectCat = $db->getOne("tourcat");

                        $db->where("catID", $selectCat['id']);
                        $countCat = $db->getOne("tourcat");

                        if (count($countCat) == 0) {
                            $db->where("id", $catss[$i]);
                            $selectCat = $db->getOne("tourcat");

                            $db->where("langID", $selectCat['langID']);
                            $db->where("catID", 0);
                            $mainCat = $db->getOne("tourcat");

                            $catArray[] = array(
                                "href" => $mainCat['url'] . '/' . $selectCat['url'] . '',
                                "icon" => "",
                                "target" => "_self",
                                "text" => $selectCat['title'],
                                "title" => $selectCat['title'],
                                "detail" => $selectCat['detail'],
                                "image" => $selectCat['img'],
                                "type" => "tur",
                            );
                        } else {
                            $db->where("id", $catss[$i]);
                            $selectCat = $db->getOne("tourcat");

                            $db->where("langID", $selectCat['langID']);
                            $db->where("catID", 0);
                            $mainCat = $db->getOne("tourcat");
                            $catArray[] = array(
                                "href" => $mainCat['url'] . '/' . $selectCat['url'],
                                "icon" => "",
                                "target" => "_self",
                                "text" => $selectCat['title'],
                                "title" => $selectCat['title'],
                                "detail" => $selectCat['detail'],
                                "image" => $selectCat['img'],
                                "type" => "tur",
                            );
                        }
                    }
                }
                $json = json_encode($catArray);
                echo $json;
            } else if ($_POST['tip'] == "tours") {
                $tours = $_POST['tour'];
                $tourss = $yazi->yazibol(",", $tours);
                $tourSize = count($tourss);
                $tourArray = array();
                for ($i = 0; $i < $tourSize; $i++) {
                    $db->where("id", $tourss[$i]);
                    $terms = $db->getOne("tour");
                    $tourArray[] = array(
                        "href" => $terms['url'],
                        "icon" => $tourss[$i],
                        "text" => $terms['title'],
                        "target" => "_self",
                        "title" => $terms['title'],
                        "type" => "turdetay",
                    );
                }
                $json = json_encode($tourArray);
                echo $json;
            } else if ($_POST['tip'] == "hotelcat") {
                $cats = $_POST['cat'];
                $catss = $yazi->yazibol(",", $cats);
                $catSize = count($catss);
                $catArray = array();
                for ($i = 0; $i < $catSize; $i++) {
                    $db->where("id", $catss[$i]);
                    $selectCat = $db->getOne("hotelcat");
                    if ($selectCat['catID'] == 0) {
                        $catArray[] = array(
                            "href" => $selectCat['url'],
                            "icon" => "",
                            "target" => "_self",
                            "text" => $selectCat['title'],
                            "title" => $selectCat['title'],
                            "detail" => $selectCat['detail'],
                            "image" => $selectCat['img'],
                            "type" => "hotel",
                        );
                    } else {
                        $db->where("id", $catss[$i]);
                        $selectCat = $db->getOne("hotelcat");

                        $db->where("catID", $selectCat['id']);
                        $countCat = $db->getOne("hotelcat");

                        if (count($countCat) == 0) {
                            $db->where("id", $catss[$i]);
                            $selectCat = $db->getOne("hotelcat");

                            $db->where("langID", $selectCat['langID']);
                            $db->where("catID", 0);
                            $mainCat = $db->getOne("hotelcat");

                            $catArray[] = array(
                                "href" => $mainCat['url'] . '/' . $selectCat['url'] . '',
                                "icon" => "",
                                "target" => "_self",
                                "text" => $selectCat['title'],
                                "title" => $selectCat['title'],
                                "detail" => $selectCat['detail'],
                                "image" => $selectCat['img'],
                                "type" => "hotel",
                            );
                        } else {
                            $db->where("id", $catss[$i]);
                            $selectCat = $db->getOne("hotelcat");

                            $db->where("langID", $selectCat['langID']);
                            $db->where("catID", 0);
                            $mainCat = $db->getOne("hotelcat");
                            $catArray[] = array(
                                "href" => $mainCat['url'] . '/' . $selectCat['url'],
                                "icon" => "",
                                "target" => "_self",
                                "text" => $selectCat['title'],
                                "title" => $selectCat['title'],
                                "detail" => $selectCat['detail'],
                                "image" => $selectCat['img'],
                                "type" => "hotel",
                            );
                        }
                    }
                }
                $json = json_encode($catArray);
                echo $json;
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "slide") {
    if ($process == "view") {
        $yetki = $db->yetkikont("slideview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $pagesql = $db->get("slider");
            foreach ($pagesql as $key => $value) {
                $db->where("id", $value["langID"]);
                $langs = $db->getOne("langs");

                $db->where("slideID", $value["id"]);
                $imgs = $db->get("slider_img");

                $edit = $db->yetkikont("slideedit", "" . $userKont['id'] . "");
                if ($edit == 1) {
                    $edtbtn = '<a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item"><i class="la la-edit"></i> Düzenle</a>';
                }
                $imgadd = $db->yetkikont("slideimgadd", "" . $userKont['id'] . "");
                if ($imgadd == 1) {
                    $imgaddbtn = '<a href="sliderimg.php?process=view&id=' . $value['id'] . '" class="dropdown-item"><i class="la la-image"></i> Resim Ekle</a>';
                }
                $imgview = $db->yetkikont("slideimgview", "" . $userKont['id'] . "");
                if ($imgview == 1) {
                    $imgviewbtn = '<a href="sliderimg.php?process=add&id=' . $value['id'] . '" class="dropdown-item"><i class="la la-eye"></i> Resimler</a>';
                }
                $del = $db->yetkikont("slidedel", "" . $userKont['id'] . "");
                if ($del == 1) {
                    $delbtn = '<a href="?process=delete&id=' . $value['id'] . '"class="dropdown-item"><i class="la la-remove"></i> Sil</a>';
                }
                if ($value['dp'] == 0) {
                    $setActive = '<button type="button" class="btn btn-success" data-type="dp" name="set" data-id="' . $value['id'] . '"><i class="fa fa-check"></i> Aktif ET</button>';
                } else if ($value['dp'] == 1) {
                    $setActive = '<button type="button" class="btn btn-danger" data-type="dp" name="set" data-id="' . $value['id'] . '"><i class="fa fa-check"></i> Deaktif ET</button>';
                }
                if ($edit == 1 && $del == 1) {
                    $pmenu = '<span class="dropdown">
                                <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                    <i class="la la-ellipsis-h"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    ' . $edtbtn . ' ' . $delbtn . ' ' . $imgaddbtn . ' ' . $imgviewbtn . '
                                </div>
                            </span>';
                } else {
                    $pmenu = "<div class='alert alert-danger'>Sayfa <b>düzenleme</b> ve <b>silme</b> yetkiniz bulunmuyor</div>";
                }
                $darray["data"][] = array(
                    $value['id'],
                    $setActive,
                    $langs['title'],
                    $value['title'],
                    count($imgs),
                    $pmenu
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " galeri görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "galview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "detail-view") {
        $yetki = $db->yetkikont("slideimgview", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $db->where("slideID", $_POST['id']);
            $pagesql = $db->get("slider_img");
            foreach ($pagesql as $key => $value) {
                $del = $db->yetkikont("slidedel", "" . $userKont['id'] . "");
                if ($del == 1) {
                    $pmenu = '<button type="button" name="set" data-type="imgdelete" data-id="' . $value['id'] . '" class="btn btn-danger"><i class="la la-remove"></i> Sil</button>';
                } else {
                    $pmenu = "<div class='alert alert-danger'>Slide <b>silme</b> yetkiniz bulunmuyor</div>";
                }

                $gen = $db->getSetMeta("slidemin", "genislik");
                $yuk = $db->getSetMeta("slidemin", "yukseklik");
                if ($value['type'] == "image") {
                    $img = '<img class="img-responsive float-left w-100" src="../bwp-content/uploads/slide/' . $gen . 'x' . $yuk . '/' . $value['fileName'] . '">';
                } else if ($value['type'] == "tour") {
                    $img = '<img class="img-responsive float-left w-100" src="../bwp-content/uploads/tour/' . $gen . 'x' . $yuk . '/' . $value['fileName'] . '">';
                }
                $darray["data"][] = array(
                    $value['id'],
                    $value['order'],
                    $value['title'],
                    $value['detail'],
                    $img,
                    $pmenu
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " galeri görüntüleme işlemi için yetkin yok!", "type" => "danger", "error" => "galview");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $setID = $_POST['id'];
            $title = $_POST['title'];
            $langID = $_POST['langID'];
            $url = $yazi->seoUrl($title);
            $data = array(
                'title' => $title,
                'langID' => $langID
            );
            if ($_POST['type'] == "edit") {
                $yetki = $db->yetkikont("slideedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('id', $setID);
                    $ssqldrm = $db->update('slider', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Slider Düzenlendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Slider Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " slider düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "slideedit");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "add") {
                $yetki = $db->yetkikont("slideadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $ssqldrm = $db->insert('slider', $data);
                    if ($ssqldrm) {
                        $data = array("message" => "Slider Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    } else {
                        $data = array("message" => "Slider Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        print_r($json);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " slide ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "slideadd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            } else if ($_POST['type'] == "delete") {
                $yetki = $db->yetkikont("slidedel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where("slideID", $_POST['id']);
                    $slide = $db->get("slider_img");
                    foreach ($slide as $item) {
                        if ($item['type'] == "image") {
                            $gen = $db->getSetMeta("slidemin", "genislik");
                            $yuk = $db->getSetMeta("slidemin", "yukseklik");
                            unlink("../bwp-content/uploads/slide/" . $gen . "x" . $yuk . "/" . $item['fileName'] . "");
                            $db->where("id", $item['id']);
                            $db->delete("slider_img");
                        } else if ($item['type'] == "tour") {
                            $db->where("id", $item['id']);
                            $db->delete("slider_img");
                        }
                    }
                    $db->where("id", $_POST['id']);
                    $ssqldrm = $db->delete("slider");
                    if ($ssqldrm) {
                        $data = array("message" => "Resim Silindi", "type" => "success", "error" => "");
                    } else {
                        $data = array("message" => "Resim Silinemedi", "type" => "danger", "error" => $db->getLastError());
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " slide resmi silme işlemi için yetkin yok!", "type" => "danger", "error" => "slideimgdel");
                }
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            } else if ($_POST['type'] == "imgdelete") {
                $yetki = $db->yetkikont("slideimgdel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where("id", $_POST['id']);
                    $slide = $db->getOne("slider_img");
                    if ($slide['type'] == "image") {
                        $gen = $db->getSetMeta("slidemin", "genislik");
                        $yuk = $db->getSetMeta("slidemin", "yukseklik");
                        unlink("../bwp-content/uploads/slide/" . $gen . "x" . $yuk . "/" . $slide['fileName'] . "");
                        $db->where("id", $_POST['id']);
                        $ssqldrm = $db->delete("slider_img");
                    } else if ($slide['type'] == "tour") {
                        $db->where("id", $_POST['id']);
                        $ssqldrm = $db->delete("slider_img");
                    }
                    if ($ssqldrm) {
                        $data = array("message" => "Resim Silindi", "type" => "success", "error" => "");
                    } else {
                        $data = array("message" => "Resim Silinemedi", "type" => "danger", "error" => $db->getLastError());
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " slide resmi silme işlemi için yetkin yok!", "type" => "danger", "error" => "slideimgdel");
                }
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            } else if ($_GET['type'] == "image") {
                $fileType = $yazi->yazibol("/", $_FILES['file']['type']);
                if ($fileType[0] == "image") {
                    $gen = $db->getSetMeta("slidemin", "genislik");
                    $yuk = $db->getSetMeta("slidemin", "yukseklik");
                    $imgText = $yazi->yazibol(".", $_FILES['file']['name']);
                    $url = $yazi->seourl($imgText[0]);
                    $foo = new \Verot\Upload\Upload($_FILES['file']);
                    if ($foo->uploaded) {
                        $foo->file_new_name_body = '' . $url . '';
                        $foo->image_resize = true;
                        $foo->image_convert = 'webp';
                        $foo->image_x = $gen;
                        $foo->image_y = $yuk;
                        $foo->image_ratio = true;
                        $foo->image_ratio_crop = true;
                        $foo->process("../bwp-content/uploads/slide/" . $gen . "x" . $yuk . "/");
                    }

                    if ($foo->processed) {
                        $idata = array("title" => "İşlem Başarılı!", "message" => "Medya Eklendi", "type" => "success", "filename" => $foo->file_dst_name, "error" => "");
                    } else {
                        $idata = array("title" => "İşlem Başarısız!", "message" => "Resim Eklemedi  <br/>" . $foo->error, "type" => "success", "error" => $foo->error);
                    }
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            } else if ($_POST['type'] == "image") {
                $pdata = array(
                    'slideID' => $_POST['id'],
                    'order' => $_POST['sira'],
                    'target' => $_POST['target'],
                    'type' => $_POST['type'],
                    'title' => $_POST['text'],
                    'detail' => $_POST['detail'],
                    'fileName' => $_POST['resim'],
                    'url' => $_POST['href']
                );
                $ssqldrm = $db->insert('slider_img', $pdata);
                if ($ssqldrm) {
                    $data = array("message" => "Görsel Eklendi", "type" => "success", "error" => "");
                } else {
                    $data = array("message" => "Görsel Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            } else if ($_POST['type'] == "tours") {
                $db->where("id", $_POST['id']);
                $tour = $db->getOne("tour");

                $db->where("id", $tour['langID']);
                $tourLang = $db->getOne("langs");

                $db->where("tourID", $tour['id']);
                $db->orderBy("catID", "desc");
                $tourmainCat = $db->getOne("tour_cats");

                $db->where("id", $tourmainCat['catID']);
                $tourmainCatDetail = $db->getOne("tourcat");

                $db->where("tourID", $tour['id']);
                $db->where("fileMimeType", "image");
                $tourMedia = $db->get("tour_media");

                $gen = $db->getSetMeta("panel-tour-img", "genislik");
                $yuk = $db->getSetMeta("panel-tour-img", "yukseklik");
                if (count($tour) > 0) {
                    $data = array(
                        "tourID" => $tour['tourID'],
                        "title" => $tour['title'],
                        "url" => mb_strtolower($tourLang['url']) . '/' . $tourmainCatDetail['url'] . '/' . $yazi->seoUrl($tour['tourID']) . '/'
                    );
                    foreach ($tourMedia as $media) {
                        $data['media'][] = array(
                            "mediaTitle" => $media['title'],
                            "fileName" => $media['fileName'],
                            "fileurl" => '<img class="img-responsive float-left w-100" src="../bwp-content/uploads/tour/' . $gen . 'x' . $yuk . '/' . $media['fileName'] . '">'
                        );
                    }
                } else {
                    $data = array("message" => "Tur bulunamadı", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            } else if ($_POST['type'] == "tour") {
                $pdata = array(
                    'slideID' => $_POST['id'],
                    'order' => $_POST['sira'],
                    'target' => $_POST['target'],
                    'type' => $_POST['type'],
                    'title' => $_POST['text'],
                    'detail' => $_POST['detail'],
                    'fileName' => $_POST['tourslideimg'],
                    'url' => $_POST['href']
                );
                $ssqldrm = $db->insert('slider_img', $pdata);
                if ($ssqldrm) {
                    $data = array("message" => "Görsel Eklendi", "type" => "success", "error" => "");
                } else {
                    $data = array("message" => "Görsel Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                print_r($json);
            } else if ($_POST['type'] == "dp") {
                $db->where("id", $_POST['id']);
                $slider = $db->getOne("slider");

                $data = array(
                    'dp' => '0',
                );
                $db->where("langID", $slider['langID']);
                $ssqldrm = $db->update('slider', $data);
                if ($ssqldrm) {
                    $data = array(
                        'dp' => '1',
                    );
                    $db->where("id", $slider['id']);
                    $ssqldprm = $db->update('slider', $data);
                    if ($ssqldprm) {
                        $idata = array("message" => "Slider Güncellnedi", "type" => "success", "error" => "");
                    } else {
                        $idata = array("message" => "Slider Güncellenmedi", "type" => "danger", "error" => $db->getLastError());
                    }
                    $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    print_r($json);
                }
            }
        } else {
            $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "homepage") {
    if ($process == "view") {
        if ($_POST['type'] == "list") {
            $pagesql = $db->get("homePage");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Lütfen anasayfa ekleyin",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );
                foreach ($pagesql as $key => $value) {
                    
                    $db->where("id", $value['langID']);
                    $langs = $db->getOne('langs');

                    $db->where("id", $value['blogcat1']);
                    $blogcat1 = $db->getOne('servicecat');

                    $db->where("id", $value['blogcat2']);
                    $blogcat2 = $db->getOne('postcat');
                    $darray["data"][] = array(
                        $value['id'],
                        $langs['title'],
                        $value['slidetext'],
                        $blogcat1['title'],
                        $blogcat2['title'],
                        $value['category'],
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <a href="?process=delete&id=' . $value['id'] . '"class="dropdown-item">
                                    <i class="la la-remove"></i> Sil
                                </a>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        }
        print_r($json);
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $gen = $db->getSetMeta("home-slide", "genislik");
            $yuk = $db->getSetMeta("home-slide", "yukseklik");
            if ($_FILES['slide']['error'] == 0) {
                $fileType = $yazi->yazibol("/", $_FILES['slide']['type']);
                if ($fileType[0] == "image") {
                    $imgText = $yazi->yazibol(".", $_FILES['slide']['name']);
                    $url = $yazi->seourl($imgText[0]);
                    $foo = new \Verot\Upload\Upload($_FILES['slide']);
                    if ($foo->uploaded) {
                        $foo->file_new_name_body = '' . $url . '';
                        $foo->image_resize = true;
                        $foo->image_convert = 'webp';
                        $foo->image_x = $gen;
                        $foo->image_y = $yuk;
                        $foo->image_ratio = true;
                        $foo->image_ratio_crop = true;
                        $foo->process("../bwp-content/uploads/slide/" . $gen . "x" . $yuk . "/");
                    }
                }
                if ($foo->processed) {
                    $_POST['resim'] = $foo->file_dst_name;
                } else {
                    $idata = array("title" => "İşlem Başarısız!", "message" => "Medya Eklemedi <br>" . $foo->error, "type" => "success", "error" => $foo->error);
                }
            }
            if ($_POST['type'] == "add") {
                $data = array(
                    'langID' => $_POST['langID'],
                    'slidetext' => $_POST['slidetext'],
                    'blogcat1' => $_POST['blogcat1'],
                    'blogcat1subtitle' => $_POST['blogcat1subtitle'],
                    'blogcat2subtitle' => $_POST['blogcat2subtitle'],
                    'blogcat2' => 0,
                    'category' => "Anasayfa",
                    'yorumlar' => $_POST['yorumlar'],
                    'resim' => $_POST['resim']
                );
                $ssqldrm = $db->insert('homePage', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Anasayfa Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Anasayfa Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            }else if ($_POST['type'] == "slider") {
                $data = array(
                    'langID' => $_POST['langID'],
                    'slidetext' => $_POST['slidetext'],
                    'blogcat1' => 1,
                    'blogcat2' => 1,
                    'yorumlar' => 1,
                    'category' => "slider",
                    'resim' => $_POST['resim']
                );
                $ssqldrm = $db->insert('homePage', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Anasayfa Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Anasayfa Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "edit") {
                $data = array(
                    'langID' => $_POST['langID'],
                    'slidetext' => $_POST['slidetext'],
                    'blogcat1' => $_POST['blogcat1'],
                    'blogcat1subtitle' => $_POST['blogcat1subtitle'],
                    'blogcat2subtitle' => $_POST['blogcat2subtitle'],
                    'blogcat2' => 0,
                    'yorumlar' => $_POST['yorumlar'],
                    'resim' => $_POST['resim']
                );
                $db->where("id", $_POST['id']);
                $ssqldrm = $db->update('homePage', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Anasayfa Düzenlendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Anasayfa Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "delete") {
                $db->where('id', $_POST['id']);
                $home = $db->getOne('homePage');

                $db->where('id', $_POST['id']);
                $ssqldrm = $db->delete('homePage');
                if ($ssqldrm) {
                    unlink("../bwp-content/uploads/slide/" . $gen . "x" . $yuk . "/" . $home['resim'] . "");
                    $idata = array("message" => "Anasayfa Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("message" => "Anasayfa Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
            }else if ($_POST['type'] == "addSort") {
                $data = array(
                    'langID' => $_POST['langID'],
                    'Sorted' => json_encode($_POST['siralama'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)
                );
                $db->where("langID",$_POST['langID']);
                $delete = $db->delete('homePage_blogcat_sort');

                $ssqldrm = $db->insert('homePage_blogcat_sort', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Sıralama Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Sıralama Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            }
            $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        } else {
            $json = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
        }
        
        print_r($json);
    }
} else if ($islem == "aboutus") {
    if ($process == "view") {
        if ($_POST['type'] == "list") {
            $pagesql = $db->get("aboutus");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Lütfen anasayfa ekleyin",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );
                foreach ($pagesql as $key => $value) {
                    $db->where("id", $value['langID']);
                    $langs = $db->getOne('langs');
                    $db->where("langID", $value['langID']);
                    $tabs = $db->get('aboutus_tab');
                    $darray["data"][] = array(
                        $value['id'],
                        $langs['title'],
                        $value['title'],
                        $value['title2'],
                        count($tabs),
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <a href="abouttab.php?process=add&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-plus"></i> Sekme Ekle
                                </a>
                                <button name="set" data-type="delete" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        } else if ($_POST['type'] == "tabList") {
            $db->where("aboutID", $_POST['aboutID']);
            $pagesql = $db->get("aboutus_tab");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Lütfen sekme ekleyin",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );
                foreach ($pagesql as $key => $value) {
                    $db->where("id", $value['langID']);
                    $langs = $db->getOne('langs');

                    $darray["data"][] = array(
                        $value['id'],
                        $langs['title'],
                        $value['title'],
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['aboutID'] . '&tabID=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <button name="set" data-type="tabDel" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        } else if ($_POST['type'] == "members") {
            $pagesql = $db->get("aboutus_members");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Lütfen anasayfa ekleyin",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );
                foreach ($pagesql as $key => $value) {
                    $db->where("id", $value['langID']);
                    $langs = $db->getOne('langs');

                    $darray["data"][] = array(
                        $value['id'],
                        $langs['title'],
                        $value['member_name'],
                        $value['member_detail'],
                        $value['member_image'],
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                             
                                <button name="set" data-type="deletemember" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
                $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            }
        }
        print_r($json);
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $gen = $db->getSetMeta("kurucu", "genislik");
            $yuk = $db->getSetMeta("kurucu", "yukseklik");
            if ($_FILES['slide']['error'] == 0) {
                $fileType = $yazi->yazibol("/", $_FILES['slide']['type']);
                if ($fileType[0] == "image") {
                    $imgText = $yazi->yazibol(".", $_FILES['slide']['name']);
                    $url = $yazi->seourl($imgText[0]);
                    $foo = new \Verot\Upload\Upload($_FILES['slide']);
                    if ($foo->uploaded) {
                        $foo->file_new_name_body = '' . $url . '';
                        $foo->image_resize = true;
                        $foo->image_convert = 'webp';
                        $foo->image_x = $gen;
                        $foo->image_y = $yuk;
                        $foo->image_ratio = true;
                        $foo->image_ratio_crop = true;
                        if ($_POST['type'] == "addmember") {
                            $foo->process("../bwp-content/uploads/ekip/" . $gen . "x" . $yuk . "/");
                        } else {
                            $foo->process("../bwp-content/uploads/kurucu/" . $gen . "x" . $yuk . "/");
                        }
                    }
                }
                if ($foo->processed) {
                    $_POST['resim'] = $foo->file_dst_name;
                } else {
                    $idata = array("title" => "İşlem Başarısız!", "message" => "Medya Eklemedi <br>" . $foo->error, "type" => "success", "error" => $foo->error);
                }
            }
            if ($_POST['type'] == "add") {
                $data = array(
                    'langID' => $_POST['langID'],
                    'title' => $_POST['title'],
                    'title2' => $_POST['title2'],
                    'title3' => $_POST['title3'],
                    'detail' => $_POST['detail'],
                    'detail2' => $_POST['detail2'],
                    'detail3' => $_POST['detail3'],
                    'facebook' => $_POST['facebook'],
                    'twitter' => $_POST['twitter'],
                    'youtube' => $_POST['youtube'],
                    'resim' => $_POST['resim']
                );
                $ssqldrm = $db->insert('aboutus', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Hakkımızda sayfası Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Hakkımızda sayfası Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "addmember") {
                $data = array(
                    'langID' => $_POST['langID'],
                    'member_name' => $_POST['membername'],
                    'member_detail' => $_POST['memberdetail'],
                    'member_image' => $_POST['resim'],
                    'facebook' => $_POST['facebook'],
                    'twitter' => $_POST['twitter'],
                    'youtube' => $_POST['youtube']
                );
                $insert = $db->insert('aboutus_members', $data);
                if ($insert) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Ekip Üyesi Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Ekip Üyesi Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "deletemember") {

                $db->where('id', $_POST['id']);
                $member = $db->get('aboutus_members');


                $db->where('id', $_POST['id']);
                $delete = $db->delete('aboutus_members');
                if ($delete) {
                    unlink("../bwp-content/uploads/ekip/" . $gen . "x" . $yuk . "/" . $member['member_image'] . "");
                    $idata = array("title" => "İşlem Başarılıs", "message" => "Ekip Üyesi Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Ekip Üyesi Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "edit") {
                $data = array(
                    'langID' => $_POST['langID'],
                    'title' => $_POST['title'],
                    'title2' => $_POST['title2'],
                    'title3' => $_POST['title3'],
                    'detail' => $_POST['detail'],
                    'detail2' => $_POST['detail2'],
                    'detail3' => $_POST['detail3'],
                    'facebook' => $_POST['facebook'],
                    'twitter' => $_POST['twitter'],
                    'youtube' => $_POST['youtube'],
                    'resim' => $_POST['resim']
                );
                $db->where("id", $_POST['id']);
                $ssqldrm = $db->update('aboutus', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Hakkımızda sayfası Düzenlendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Hakkımızda sayfası  Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "delete") {
                $db->where('id', $_POST['id']);
                $home = $db->getOne('aboutus');

                $db->where('aboutID', $home['id']);
                $db->delete('aboutus_tab');

                $db->where('id', $_POST['id']);
                $ssqldrm = $db->delete('aboutus');
                if ($ssqldrm) {

                    unlink("../bwp-content/uploads/kurucu/" . $gen . "x" . $yuk . "/" . $home['resim'] . "");
                    $idata = array("message" => "Hakkımızda sayfası Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("message" => "Hakkımızda sayfası Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "tabAdd") {
                $data = array(
                    'aboutID' => $_POST['aboutID'],
                    'langID' => $_POST['langID'],
                    'title' => $_POST['title'],
                    'detail' => $_POST['detail'],
                    'url' => $yazi->seoUrl($_POST['title'])
                );
                $ssqldrm = $db->insert('aboutus_tab', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Hakkımızda sayfası Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Hakkımızda sayfası Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "tabEdit") {
                $data = array(
                    'aboutID' => $_POST['aboutID'],
                    'langID' => $_POST['langID'],
                    'title' => $_POST['title'],
                    'detail' => $_POST['detail'],
                );
                $db->where("id", $_POST['tabID']);
                $ssqldrm = $db->update('aboutus_tab', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Hakkımızda sayfası Düzenlendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Hakkımızda sayfası  Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                }
            } else if ($_POST['type'] == "tabDel") {

                $db->where('id', $_POST['tabID']);
                $ssqldrm = $db->delete('aboutus_tab');
                if ($ssqldrm) {
                    $idata = array("message" => "Hakkımızda sayfası sekmesi Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("message" => "Hakkımızda sayfası sekmesi Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
            }
        } else {
            $json = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
        }
        $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        print_r($json);
    }
} else if ($islem == "service") {
    if ($process == "view") {
        if ($_POST['type'] == "cats") {
            $pagesql = $db->get("servicecat");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Servis bilgisi girilmemiş",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );
                foreach ($pagesql as $key => $value) {
                    $db->where("id", $value['langID']);
                    $taglang = $db->getOne('langs');

                    if ($value['catID'] == 0) {
                        $termTitle = "Ana Kategori";
                    } else {
                        $db->where("id", $value['catID']);
                        $catTerm = $db->getOne('servicecat');
                        $termTitle = $catTerm['title'];
                    }
                    $darray["data"][] = array(
                        $value['id'],
                        $taglang['title'],
                        $termTitle,
                        $value['title'],
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <button name="set" data-type="catDel" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        } else if ($_POST['type'] == "catList") {
            $db->where("langID", $_POST['langID']);
            $db->where("catID", 0);
            $catList = $db->get("servicecat");
            foreach ($catList as $cp) {
                $db->where("type", "cat");
                $db->where("type_meta", $cp["id"]);
                $postcat = $db->getOne("service_meta");
                echo '<label class="col-md-12 kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success">
                <input type="checkbox" name="cat" value="' . $cp['id'] . '"> ' . $cp['title'] . '
                <span></span>
            </label>';
                $db->where("catID", $cp['id']);
                $catAltList = $db->get("servicecat");
                foreach ($catAltList as $cap) {
                    $db->where("type", "cat");
                    $db->where("type_meta", $cap["id"]);
                    $postcat = $db->getOne("service_meta");
                    echo '<label class="col-md-12 kt-checkbox ml-2 kt-checkbox--bold kt-checkbox--warning">
                <input type="checkbox" name="cat" value="' . $cap['id'] . '"> ' . $cap['title'] . '
                <span></span>
            </label>';
                    $db->where("catID", $cap['id']);
                    $catAltList = $db->get("servicecat");
                    foreach ($catAltList as $cap) {
                        $db->where("type", "cat");
                        $db->where("type_meta", $cap["id"]);
                        $postcat = $db->getOne("service_meta");
                        echo '<label class="col-md-12 kt-checkbox ml-4 kt-checkbox--bold kt-checkbox--danger">
                            <input type="checkbox" name="cat" value="' . $cap['id'] . '"> ' . $cap['title'] . '
                            <span></span>
                        </label>';
                        $db->where("catID", $cap['id']);
                        $catAltList = $db->get("servicecat");
                        foreach ($catAltList as $cap) {
                            $db->where("type", "cat");
                            $db->where("type_meta", $cap["id"]);
                            $postcat = $db->getOne("service_meta");
                            echo '<label class="col-md-12 kt-checkbox ml-5 kt-checkbox--bold kt-checkbox--dark">
                            <input type="checkbox" name="cat" value="' . $cap['id'] . '"> ' . $cap['title'] . '
                            <span></span>
                        </label>';
                        }
                    }
                }
            }
        } else if ($_POST['type'] == "serviceList") {
            $db->orderBy("ID", "desc");
            $pagesql = $db->get("services");
            foreach ($pagesql as $key => $value) {
                $db->where("id", $value['post_langID']);
                $langs = $db->getOne('langs');
                foreach ($status as $key => $val) {
                    if ($value['post_status'] == $val) {
                        $status = $key;
                    }
                }
                $db->where("postID", $value['ID']);
                $db->where("type", "cat");
                $pcats = $db->get('service_meta');

                foreach ($pcats as $pcatsp) {
                    $db->where("id", $pcatsp['type_meta']);
                    $cat = $db->getOne('servicecat');
                    $link = '<a href="?islem=cat&id=' . $cat['id'] . '">' . $cat['title'] . '</a>,';
                }

                $edit = $db->yetkikont("postedit", "" . $userKont['id'] . "");
                if ($edit == 1) {
                    $edtbtn = '<a href="service.php?process=edit&id=' . $value['ID'] . '" class="btn btn-dark"><i class="la la-edit "></i> Düzenle</a>';
                }
                $del = $db->yetkikont("postdel", "" . $userKont['id'] . "");
                if ($del == 1) {
                    $delbtn = '<button type="button" class="btn btn-danger" name="set" data-id="' . $value['ID'] . '" data-type="serviceDel"><i class="la la-remove"></i> Sil</button>';
                }
                $darray["data"][] = array(
                    $value['ID'],
                    $status,
                    $langs['title'],
                    $value['post_title'],
                    $link,
                    $value['post_date'],
                    $edtbtn . '' . $delbtn
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        }
        print_r($json);
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $gen = $db->getSetMeta("homepage", "genislik");
            $yuk = $db->getSetMeta("homepage", "yukseklik");
            if ($_FILES['slide']['error'] == 0) {
                $fileType = $yazi->yazibol("/", $_FILES['slide']['type']);
                if ($fileType[0] == "image") {
                    $imgText = $yazi->yazibol(".", $_FILES['slide']['name']);
                    $url = $yazi->seourl($imgText[0]);
                    $foo = new \Verot\Upload\Upload($_FILES['slide']);
                    if ($foo->uploaded) {
                        $foo->file_new_name_body = '' . $url . '';
                        $foo->image_resize = true;
                        $foo->image_convert = 'webp';
                        $foo->image_x = $gen;
                        $foo->image_y = $yuk;
                        $foo->image_ratio = true;
                        $foo->image_ratio_crop = true;
                       
                            $foo->process("../bwp-content/uploads/hizmet/" . $gen . "x" . $yuk . "/");
                        
                    }
                }
                if ($foo->processed) {
                    $_POST['resim'] = $foo->file_dst_name;
                } else {
                    $idata = array("title" => "İşlem Başarısız!", "message" => "Medya Eklemedi <br>" . $foo->error, "type" => "success", "error" => $foo->error);
                }
            }
            

            $db->where("type", "image");
            $img = $db->get("settings_meta");
            if ($_POST['type'] == "catAdd") {
                $jdata = array(
                    'langID' => $_POST['langID'],
                    'catID' => $_POST['catID'],
                    'title' => $_POST['title'],
                    'url' => $yazi->seoUrl($_POST['title']) ,
                    'resim' => $_POST['resim']
                );
                
                $ssqldrm = $db->insert('servicecat', $jdata);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Kategori Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Kategori Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "catEdit") {
                $jdata = array(
                    'langID' => $_POST['langID'],
                    'catID' => $_POST['catID'],
                    'title' => $_POST['title'],
                    'url' => $yazi->seoUrl($_POST['title']) ,
                    'resim' => $_POST['resim']
                );
                $db->where("id", $_POST['id']);
                $ssqldrm = $db->update('servicecat', $jdata);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Kategori Güncellendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Kategori Güncellenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "catDel") {
                $db->where('id', $_POST['id']);
                $ssqldrm = $db->delete('servicecat');
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Kategori Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Kategori Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "serviceAdd") {
                $yetki = $db->yetkikont("postadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    
                    $data = array(
                        'post_title' => stripslashes($_POST['title']),
                        'post_content' => $_POST['content'],
                        'post_status' => $_POST['durum'],
                        'post_langID' => $_POST['langID'],
                        'post_author' => $_POST['author'],
                        'post_date' => $_POST['date'],
                        'post_slug' => $yazi->seourl($_POST['title']) 
                    );
                    $ssqldrm = $db->insert('services', $data);
                    if ($ssqldrm) {
                        $data = array(
                            'postID' => $ssqldrm,
                            'type' => 'image',
                            'type_meta' => $_POST['image']
                        );
                        $db->insert('service_meta', $data);

                        $cats = $_POST['cat'];
                        $catss = $yazi->yazibol(",", $cats);
                        $catSize = count($catss);
                        for ($i = 0; $i < $catSize; $i++) {
                            $data = array(
                                'postID' => $ssqldrm,
                                'type' => 'cat',
                                'type_meta' => $catss[$i]
                            );
                            $db->insert('service_meta', $data);
                        }
                        $jdata = array("title" => "İşlem Başarılı", "message" => "Yazı Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    } else {
                        $jdata = array("title" => "İşlem Başarısız", "message" => "Yazı Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " yazı ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "postadd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                }
            } else if ($_POST['type'] == "serviceEdit") {
                $yetki = $db->yetkikont("postedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where("ID", $_POST['id']);
                    $postKont = $db->getOne("services");
                    if ($postKont['ID'] == $_POST['id']) {
                        $data = array(
                            'post_title' => stripslashes($_POST['title']),
                            'post_content' => $_POST['content'],
                            'post_status' => $_POST['durum'],
                            'post_langID' => $_POST['langID'],
                            'post_author' => $_POST['author'],
                            'post_date' => $_POST['date'],
                            'post_slug' => $yazi->seourl($_POST['title']) 
                        );
                        $db->where('ID', $postKont['ID']);
                        $ssqldrm = $db->update('services', $data);

                        $db->where('postID', $postKont['ID']);
                        $db->delete('service_meta');
                        if ($ssqldrm) {
                            $data = array(
                                'postID' => $postKont['ID'],
                                'type' => 'image',
                                'type_meta' => $_POST['image']
                            );
                            $db->insert('service_meta', $data);

                            $cats = $_POST['cat'];
                            $catss = $yazi->yazibol(",", $cats);
                            $catSize = count($catss);
                            for ($i = 0; $i < $catSize; $i++) {
                                $data = array(
                                    'postID' => $postKont['ID'],
                                    'type' => 'cat',
                                    'type_meta' => $catss[$i]
                                );
                                $db->insert('service_meta', $data);
                            }
                            $jdata = array("title" => "İşlem Başarılı", "message" => "Yazı Düzenlendi", "type" => "success", "error" => "");
                            $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        } else {
                            $jdata = array("title" => "İşlem Başarısız", "message" => "Yazı Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                            $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        }
                    }
                } else {
                    $jdata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " yazı düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "postedit");
                    $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                }
            } else if ($_POST['type'] == "serviceDel") {
                $yetki = $db->yetkikont("postdel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('ID', $_POST['id']);
                    $pknt = $db->getOne('services');

                    $db->where("postID", $pknt["ID"]);
                    $db->where("type", "image");
                    $image = $db->getOne("service_meta");

                    $db->where('ID', $_POST['id']);
                    $ssqldrm = $db->delete('services');
                    if ($ssqldrm) {
                        $db->where('postID', $_POST['id']);
                        $db->delete('service_meta');
                        $data = array("title" => "İşlem Başarılı", "message" => "Yazı Silindi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    } else {
                        $data = array("title" => "İşlem Başarısız", "message" => "Yazı Silinemedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " etiket silme işlemi için yetkin yok!", "type" => "danger", "error" => "postdel");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                }
            }
        } else {
            $json = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
        }
        print_r($json);
    }
} else if ($islem == "education") {
    if ($process == "view") {
        if ($_POST['type'] == "cats") {
            $pagesql = $db->get("educationcat");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Eğitim bilgisi girilmemiş",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );
                foreach ($pagesql as $key => $value) {
                    $db->where("id", $value['langID']);
                    $taglang = $db->getOne('langs');

                    if ($value['catID'] == 0) {
                        $termTitle = "Ana Kategori";
                    } else {
                        $db->where("id", $value['catID']);
                        $catTerm = $db->getOne('educationcat');
                        $termTitle = $catTerm['title'];
                    }
                    $darray["data"][] = array(
                        $value['id'],
                        $taglang['title'],
                        $termTitle,
                        $value['title'],
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <button name="set" data-type="catDel" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        } else if ($_POST['type'] == "catList") {
            $db->where("langID", $_POST['langID']);
            $db->where("catID", 0);
            $catList = $db->get("educationcat");
            foreach ($catList as $cp) {
                $db->where("type", "cat");
                $db->where("type_meta", $cp["id"]);
                $postcat = $db->getOne("education_meta");
                echo '<label class="col-md-12 kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success">
                <input type="checkbox" name="cat" value="' . $cp['id'] . '"> ' . $cp['title'] . '
                <span></span>
            </label>';
                $db->where("catID", $cp['id']);
                $catAltList = $db->get("educationcat");
                foreach ($catAltList as $cap) {
                    $db->where("type", "cat");
                    $db->where("type_meta", $cap["id"]);
                    $postcat = $db->getOne("education_meta");
                    echo '<label class="col-md-12 kt-checkbox ml-2 kt-checkbox--bold kt-checkbox--warning">
                <input type="checkbox" name="cat" value="' . $cap['id'] . '"> ' . $cap['title'] . '
                <span></span>
            </label>';
                    $db->where("catID", $cap['id']);
                    $catAltList = $db->get("educationcat");
                    foreach ($catAltList as $cap) {
                        $db->where("type", "cat");
                        $db->where("type_meta", $cap["id"]);
                        $postcat = $db->getOne("education_meta");
                        echo '<label class="col-md-12 kt-checkbox ml-4 kt-checkbox--bold kt-checkbox--danger">
                            <input type="checkbox" name="cat" value="' . $cap['id'] . '"> ' . $cap['title'] . '
                            <span></span>
                        </label>';
                        $db->where("catID", $cap['id']);
                        $catAltList = $db->get("educationcat");
                        foreach ($catAltList as $cap) {
                            $db->where("type", "cat");
                            $db->where("type_meta", $cap["id"]);
                            $postcat = $db->getOne("education_meta");
                            echo '<label class="col-md-12 kt-checkbox ml-5 kt-checkbox--bold kt-checkbox--dark">
                            <input type="checkbox" name="cat" value="' . $cap['id'] . '"> ' . $cap['title'] . '
                            <span></span>
                        </label>';
                        }
                    }
                }
            }
        } else if ($_POST['type'] == "educationList") {
            $pagesql = $db->get("educations");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Henüz içerik eklenmemiş",
                    "type" => "danger",
                    "count" => count($pagesql)
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success",
                    "count" => count($pagesql)
                );
                foreach ($pagesql as $key => $value) {
                    $db->where("id", $value['post_langID']);
                    $langs = $db->getOne('langs');

                    foreach ($status as $key => $val) {
                        if ($value['post_status'] == $val) {
                            $status = $key;
                        }
                    }

                    $db->where("postID", $value['ID']);
                    $db->where("type", "cat");
                    $pcats = $db->get('education_meta');

                    foreach ($pcats as $pcatsp) {
                        $db->where("id", $pcatsp['type_meta']);
                        $cat = $db->getOne('educationcat');
                        $link = '<a href="?islem=cat&id=' . $cat['id'] . '">' . $cat['title'] . '</a>,';
                    }

                    $edit = $db->yetkikont("postedit", "" . $userKont['id'] . "");
                    if ($edit == 1) {
                        $edtbtn = '<a href="education.php?process=edit&id=' . $value['ID'] . '" class="btn btn-dark"><i class="la la-edit "></i> Düzenle</a>';
                    }
                    $del = $db->yetkikont("postdel", "" . $userKont['id'] . "");
                    if ($del == 1) {
                        $delbtn = '<button type="button" class="btn btn-danger" name="set" data-id="' . $value['ID'] . '" data-type="educationDel"><i class="la la-remove"></i> Sil</button>';
                    }
                    $darray["data"][] = array(
                        $value['ID'],
                        $status,
                        $langs['title'],
                        $value['post_title'],
                        $link,
                        $value['post_date'],
                        $edtbtn . '' . $delbtn
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        }
        print_r($json);
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $gen = $db->getSetMeta("homepage", "genislik");
            $yuk = $db->getSetMeta("homepage", "yukseklik");
            if ($_FILES['slide']['error'] == 0) {
                $fileType = $yazi->yazibol("/", $_FILES['slide']['type']);
                if ($fileType[0] == "image") {
                    $imgText = $yazi->yazibol(".", $_FILES['slide']['name']);
                    $url = $yazi->seourl($imgText[0]);
                    $foo = new \Verot\Upload\Upload($_FILES['slide']);
                    if ($foo->uploaded) {
                        $foo->file_new_name_body = '' . $url . '';
                        $foo->image_resize = true;
                        $foo->image_convert = 'webp';
                        $foo->image_x = $gen;
                        $foo->image_y = $yuk;
                        $foo->image_ratio = true;
                        $foo->image_ratio_crop = true;
                       
                            $foo->process("../bwp-content/uploads/egitim/" . $gen . "x" . $yuk . "/");
                        
                    }
                }
                if ($foo->processed) {
                    $_POST['resim'] = $foo->file_dst_name;
                } else {
                    $idata = array("title" => "İşlem Başarısız!", "message" => "Medya Eklemedi <br>" . $foo->error, "type" => "success", "error" => $foo->error);
                }
            }

            $db->where("type", "image");
            $img = $db->get("settings_meta");
            if ($_POST['type'] == "catAdd") {
                $jdata = array(
                    'langID' => $_POST['langID'],
                    'catID' => $_POST['catID'],
                    'title' => $_POST['title'],
                    'url' => $yazi->seoUrl($_POST['title']) ,
                    'resim' => $_POST['resim']
                );
                $ssqldrm = $db->insert('educationcat', $jdata);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Kategori Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Kategori Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "catEdit") {
                $jdata = array(
                    'langID' => $_POST['langID'],
                    'catID' => $_POST['catID'],
                    'title' => $_POST['title'],
                    'url' => $yazi->seoUrl($_POST['title']) ,
                    'resim' => $_POST['resim']
                );
                $db->where("id", $_POST['id']);
                $ssqldrm = $db->update('educationcat', $jdata);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Kategori Güncellendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Kategori Güncellenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "catDel") {
                $db->where('id', $_POST['id']);
                $ssqldrm = $db->delete('educationcat');
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Kategori Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Kategori Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "educationAdd") {
                $yetki = $db->yetkikont("postadd", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $data = array(
                        'post_title' => stripslashes($_POST['title']),
                        'post_content' => $_POST['content'],
                        'post_status' => $_POST['durum'],
                        'post_langID' => $_POST['langID'],
                        'post_author' => $_POST['author'],
                        'post_date' => $_POST['date'],
                        'post_slug' => $yazi->seourl($_POST['title']) 
                    );
                    $ssqldrm = $db->insert('educations', $data);
                    if ($ssqldrm) {
                        $data = array(
                            'postID' => $ssqldrm,
                            'type' => 'image',
                            'type_meta' => $_POST['image']
                        );
                        $db->insert('education_meta', $data);

                        $cats = $_POST['cat'];
                        $catss = $yazi->yazibol(",", $cats);
                        $catSize = count($catss);
                        for ($i = 0; $i < $catSize; $i++) {
                            $data = array(
                                'postID' => $ssqldrm,
                                'type' => 'cat',
                                'type_meta' => $catss[$i]
                            );
                            $db->insert('education_meta', $data);
                        }
                        $jdata = array("title" => "İşlem Başarılı", "message" => "Yazı Eklendi", "type" => "success", "error" => "");
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    } else {
                        $jdata = array("title" => "İşlem Başarısız", "message" => "Yazı Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " yazı ekleme işlemi için yetkin yok!", "type" => "danger", "error" => "postadd");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                }
            } else if ($_POST['type'] == "educationEdit") {
                $yetki = $db->yetkikont("postedit", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where("ID", $_POST['id']);
                    $postKont = $db->getOne("educations");
                    if ($postKont['ID'] == $_POST['id']) {
                        $data = array(
                            'post_title' => stripslashes($_POST['title']),
                            'post_content' => $_POST['content'],
                            'post_status' => $_POST['durum'],
                            'post_langID' => $_POST['langID'],
                            'post_author' => $_POST['author'],
                            'post_date' => $_POST['date'],
                            'post_slug' => $yazi->seourl($_POST['title']) 
                        );
                        $db->where('ID', $postKont['ID']);
                        $ssqldrm = $db->update('educations', $data);

                        $db->where('postID', $postKont['ID']);
                        $db->delete('education_meta');
                        if ($ssqldrm) {
                            $data = array(
                                'postID' => $postKont['ID'],
                                'type' => 'image',
                                'type_meta' => $_POST['image']
                            );
                            $db->insert('education_meta', $data);

                            $cats = $_POST['cat'];
                            $catss = $yazi->yazibol(",", $cats);
                            $catSize = count($catss);
                            for ($i = 0; $i < $catSize; $i++) {
                                $data = array(
                                    'postID' => $postKont['ID'],
                                    'type' => 'cat',
                                    'type_meta' => $catss[$i]
                                );
                                $db->insert('education_meta', $data);
                            }
                            $jdata = array("title" => "İşlem Başarılı", "message" => "Yazı Düzenlendi", "type" => "success", "error" => "");
                            $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        } else {
                            $jdata = array("title" => "İşlem Başarısız", "message" => "Yazı Düzenlenmedi", "type" => "danger", "error" => $db->getLastError());
                            $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                        }
                    }
                } else {
                    $jdata = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " yazı düzenleme işlemi için yetkin yok!", "type" => "danger", "error" => "postedit");
                    $json = json_encode($jdata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                }
            } else if ($_POST['type'] == "educationDel") {
                $yetki = $db->yetkikont("postdel", "" . $userKont['id'] . "");
                if ($yetki == "1") {
                    $db->where('ID', $_POST['id']);
                    $ssqldrm = $db->delete('educations');
                    if ($ssqldrm) {
                        $db->where('postID', $_POST['id']);
                        $db->delete('education_meta');

                        $data = array("title" => "İşlem Başarılı", "message" => "Yazı Silindi", "type" => "success", "error" => "");
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    } else {
                        $data = array("title" => "İşlem Başarısız", "message" => "Yazı Silinemedi", "type" => "danger", "error" => $db->getLastError());
                        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                    }
                } else {
                    $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " etiket silme işlemi için yetkin yok!", "type" => "danger", "error" => "postdel");
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
                }
            }
        } else {
            $json = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        }
        print_r($json);
    }
} else if ($islem == "social") {
    if ($process == "view") {
        $yetki = $db->yetkikont("socialViews", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $pagesql = $db->get("social");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Henüz Yorum eklenmemiş",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );
                foreach ($pagesql as $key => $value) {
                    $darray["data"][] = array(
                        $value['id'],
                        $value['baslik'],
                        $value['icon'],
                        $value['url'],
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <button name="set" type="button" data-type="socialDel" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                               
                            </div>
                        </span>'
                    );
                }
            }
        } else {
            $darray = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "socialViews");
        }

        $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        print_r($json);
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            if ($_POST['type'] == "socialAdd") {
                $data = array(
                    'baslik' => $_POST['baslik'],
                    'icon' => $_POST['icon'],
                    'bg-color' => $_POST['bg-color'],
                    'url' => $_POST['url'],
                );
                $ssqldrm = $db->insert('social', $data);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Soysal Medya linki eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Soysal Medya linki  Eklenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "socialEdit") {
                $data = array(
                    'baslik' => $_POST['baslik'],
                    'icon' => $_POST['icon'],
                    'bg-color' => $_POST['bg-color'],
                    'url' => $_POST['url'],
                );
                $db->where("id", $_POST['id']);
                $ssqldrm = $db->update('social', $data);

                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Soysal Medya Güncellendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Soysal Medya Güncellenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "socialDel") {
                $db->where('id', $_POST['id']);
                $ssqldrm = $db->delete('social');
                if ($ssqldrm) {
                    $idata = array("message" => "Soysal Medya Link Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("message" => "Soysal Medya Link Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else {
                $json = array("title" => "Bilinmeyen İşlem", "message" => "Gönderilen işlem bilinmiyor. IP Adresiniz kayıt altına alınmıştır.", "type" => "danger", "error" => "set");
            }
        } else {
            $json = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
        }
        $json = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        print_r($json);
    }
} else if ($islem == "basvuru") {
    if ($process == "view") {
        if ($_POST['type'] == "list") {
            $pagesql = $db->get("pageBasvuru");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Servis bilgisi girilmemiş",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );

                foreach ($pagesql as $key => $value) {
                    $ftName = "";
                    foreach (json_decode($value['title']) as $k => $v) {
                        $db->where("subtitle", $k);
                        $fl = $db->getOne("langs");
                        $ftName .= '<small>' . $fl['title'] . '</small>' . '-><b>' . $v . '</b><br>';
                    }

                    $fName = "";
                    foreach (json_decode($value['options']) as $k => $v) {
                        $db->where("subtitle", $k);
                        $fl = $db->getOne("langs");
                        $fName .= '<small>' . $fl['title'] . '</small>' . '-><b>' . $yazi->kisalt($v, 30) . '</b><br>';
                    }
                    foreach ($status as $key_ => $val) {
                        if ($value['status'] == $val) {
                            $fStatus = '<b>' . $key_ . '</b>';
                        }
                    }
                    $darray["data"][] = array(
                        $value['id'],
                        $fStatus,
                        $ftName,
                        $fName,
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <button name="set" data-type="del" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        } else if ($_POST['type'] == "basvuruList") {
            $pagesql = $db->get("pageBasvuru");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Servis bilgisi girilmemiş",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );

                foreach ($pagesql as $key => $value) {
                    $ftName = "";
                    foreach (json_decode($value['title']) as $k => $v) {
                        $db->where("subtitle", $k);
                        $fl = $db->getOne("langs");
                        $ftName .= '<small>' . $fl['title'] . '</small>' . '-><b>' . $v . '</b><br>';
                    }

                    $fName = "";
                    foreach (json_decode($value['options']) as $k => $v) {
                        $db->where("subtitle", $k);
                        $fl = $db->getOne("langs");
                        $fName .= '<small>' . $fl['title'] . '</small>' . '-><b>' . $yazi->kisalt($v, 30) . '</b><br>';
                    }
                    $darray["data"][] = array(
                        $value['id'],
                        $ftName,
                        $fName,
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <button name="set" data-type="del" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        }
        print_r($json);
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $db->where("type", "image");
            $img = $db->get("settings_meta");
            if ($_POST['type'] == "add") {
                $jdata = array(
                    'title' => json_encode($_POST['title'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG),
                    'options' => json_encode($_POST['options'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG),
                    'status' => $_POST['durum'],
                );
                $basvuruekle = $db->insert("pageBasvuru", $jdata);
                if ($basvuruekle) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Başvuru Alanı Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Başvuru Alanı Eklenemedi", "type" => "error", "error" => "");
                }

                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "edit") {
                $jdata = array(
                    'title' => json_encode($_POST['title'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG),
                    'options' => json_encode($_POST['options'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG),
                    'status' => $_POST['durum'],
                );
                $db->where("id", $_POST['id']);
                $ssqldrm = $db->update('pageBasvuru', $jdata);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Başvuru Güncellendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Başvuru Güncellenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "del") {
                $db->where('id', $_POST['id']);
                $ssqldrm = $db->delete('pageBasvuru');
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Kategori Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Kategori Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            }
        } else {
            $json = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
        }
        print_r($json);
    } else if ($process == "status") {
        $db->where("template", "basvuruyap");
        $status = $db->getOne("page");
        if ($status['status'] == "1") {
            $data = array(
                "status" => 2
            );
            $idata = array("title" => "İşlem Başarılı", "message" => "Başvuru Sayfası Aktif Hale Getirildi", "type" => "success", "status" => "Pasif Hale Getir",  "error" => "");
        } else {
            $data = array(
                "status" => 1
            );
            $idata = array("title" => "İşlem Başarılı", "message" => "Başvuru Sayfası Pasif Hale Getirildi", "type" => "success", "status" => "Aktif Hale Getir",  "error" => "");
        }
        $db->where("template", "basvuruyap");
        $update = $db->update("page", $data);
        if ($update) {
            $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        } else {
            $idata = array("title" => "İşlem Başarısız", "message" => "Başvuru Ayarları Güncellenemedi", "type" => "danger", "error" => $db->getLastError());
            $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        }
        print_r($json);
    }
} else if ($islem == "basvurugoster") {
    if ($process == "view") {
        if ($_POST['basvuruList']) {
            $db->orderBy("id", "DESC");
            $basvurular = $db->get("basvurular");
            if (count($basvurular) > 0) {
                $darray["notify"] = array(
                    "type" => "success"
                );
                foreach ($basvurular as $key) {



                    $db->where("id", $key['basvuruAlani']);
                    $basvurubaslik = $db->getOne("pageBasvuru");
                    $basvurubaslik = json_decode($basvurubaslik['title'], true);
                    $basvuru =  $basvurubaslik[mb_strtoupper($key['userLang'])];

                    $bID = $key['id'];
                    $bBasvuruID = $key['basvuruAlani'];
                    $db->where("id", $bBasvuruID);
                    $pageBasvuru = $db->getOne("pageBasvuru");
                    $title = $pageBasvuru['title'];
                    $title = json_decode($title, true);
                    $bTarih = $key['date'];
                    $bAdSoy = $key['isim'] . " " . $key['soyisim'];
                    $bUnvan = $key['unvan'];
                    $bCompName = $key['firmaadi'];
                    $bEmail = $key['eposta'];
                    $bTel = $key['telefon'];
                    $bMessageArray = $yazi->yazibol(" ", $key['mesaj']);
                    $bCount = 0;
                    $bMessage = "";
                    foreach ($bMessageArray as $key) {
                        if ($bCount % 9 == 8) {
                            $bMessage .= $key . " <br>";
                        } else {
                            $bMessage .= $key . " ";
                        }
                        $bCount++;
                    }
                    $darray["data"][] = array(
                        $bID,
                        $basvuru,
                        $bTarih,
                        $bAdSoy,
                        $bUnvan,
                        $bCompName,
                        '<a href="mailto:' . $bEmail . '" > ' . $bEmail . ' </a>',
                        '<a href="tel:' . $bTel . '" > ' . $bTel . ' </a>',
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-eye"></i>
                            </a>
                          
                            <div class="dropdown-menu dropdown-menu-right" style="padding:15px">
                                <p>' . $bMessage . '</p>
                            </div>
                        </span>'
                    );
                }
            } else {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Servis bilgisi girilmemiş",
                    "type" => "danger"
                );
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "customer") {
    if ($process == "view") {
        if ($_POST['type'] == "list") {
            $db->orderBy("id", "DESC");
            $pagesql = $db->get("comments");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Servis bilgisi girilmemiş",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );

                foreach ($pagesql as $key) {
                    $ftName = $key['email'];
                    $fName = $key['message'];
                    if ($key['status'] == "1") {
                        $color = "color:cyan";
                        $onaytext = "Onayla";
                    } else {
                        $color = "color:orange";
                        $onaytext = "Onay Kaldır";
                    }
                    $darray["data"][] = array(
                        $key['id'],
                        $key['date'],
                        $key['name'],
                        $key['email'],
                        $key['message'],
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                          
                            <div class="dropdown-menu dropdown-menu-right">
                                <button name="set" id="edit" style="' . $color . '" data-type="edit" data-id="' . $key['id'] . '" class="dropdown-item"><i style="' . $color . '" class="la la-remove"></i> ' . $onaytext . '</button>
                                <button name="set" style="color:red" data-type="del" data-id="' . $key['id'] . '" class="dropdown-item"><i style="color:red" class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        } else if ($_POST['type'] == "basvuruList") {
            $pagesql = $db->get("pageBasvuru");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Servis bilgisi girilmemiş",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );

                foreach ($pagesql as $key => $value) {
                    $ftName = "";
                    foreach (json_decode($value['title']) as $k => $v) {
                        $db->where("subtitle", $k);
                        $fl = $db->getOne("langs");
                        $ftName .= '<small>' . $fl['title'] . '</small>' . '-><b>' . $v . '</b><br>';
                    }

                    $fName = "";
                    foreach (json_decode($value['options']) as $k => $v) {
                        $db->where("subtitle", $k);
                        $fl = $db->getOne("langs");
                        $fName .= '<small>' . $fl['title'] . '</small>' . '-><b>' . $yazi->kisalt($v, 30) . '</b><br>';
                    }
                    $darray["data"][] = array(
                        $value['id'],
                        $ftName,
                        $fName,
                        '<span class="dropdown">
                            <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="?process=edit&id=' . $value['id'] . '" class="dropdown-item">
                                    <i class="la la-edit"></i> Düzenle
                                </a>
                                <button name="set" data-type="del" data-id="' . $value['id'] . '" class="dropdown-item"><i class="la la-remove"></i> Sil</button>
                            </div>
                        </span>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        }
        print_r($json);
    } else if ($process == "set") {
        $yetki = $db->yetkikont("set", "" . $userKont['id'] . "");
        if ($yetki == "1") {
            $db->where("type", "image");
            $img = $db->get("settings_meta");
            if ($_POST['type'] == "add") {
                $jdata = array(
                    'title' => json_encode($_POST['title'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG),
                    'options' => json_encode($_POST['options'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG),
                );
                $basvuruekle = $db->insert("pageBasvuru", $jdata);
                if ($basvuruekle) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Başvuru Alanı Eklendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Başvuru Alanı Eklenemedi", "type" => "error", "error" => "");
                }

                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "edit") {
                $db->where("id", $_POST['id']);
                $statu = $db->getOne("comments");
                $status = 1;
                if ($statu['status'] == "1") {
                    $status = 2;
                } else {
                    $status = 1;
                }
                $jdata = array(
                    'status' => $status,
                );
                $db->where("id", $_POST['id']);
                $ssqldrm = $db->update('comments', $jdata);
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Yorum Güncellendi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Yorum Güncellenmedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            } else if ($_POST['type'] == "del") {
                $db->where('id', $_POST['id']);
                $ssqldrm = $db->delete('comments');
                if ($ssqldrm) {
                    $idata = array("title" => "İşlem Başarılı", "message" => "Yorum Silindi", "type" => "success", "error" => "");
                } else {
                    $idata = array("title" => "İşlem Başarısız", "message" => "Yorum Silinemedi", "type" => "danger", "error" => $db->getLastError());
                }
                $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            }
        } else {
            $json = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " işlem yapma yetkin yok!", "type" => "danger", "error" => "set");
        }
        print_r($json);
    }
} else if ($islem == "faq") {
    if ($process == "view") {
        if ($_POST['type'] == "list") {
            $db->orderBy("id", "DESC");
            $pagesql = $db->get("faq");
            if (count($pagesql) == 0) {
                $darray["notify"] = array(
                    "title" => "Gösterilecek tablo verisi yok!",
                    "message" => "Servis bilgisi girilmemiş",
                    "type" => "danger"
                );
            } else {
                $darray["notify"] = array(
                    "type" => "success"
                );

                foreach ($pagesql as $key) {
                    $db->where("id", $key['langID']);
                    $lang = $db->getOne("langs");
                    $darray["data"][] = array(
                        $key['id'],
                        $lang['title'],
                        $key['title'],
                        $yazi->kisalt($key['content'], 200),
                        '  <a href="?process=edit&id=' . $key['id'] . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" >
                        <i class="la la-edit"></i> Düzenle
                            </a>'
                    );
                }
            }
            $json = json_encode($darray, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    } else if ($process == "set") {
        if ($_POST['type'] == "edit") {
            $jdata = array(
                'title' => $_POST['title'],
                'content' => $_POST['content'],
            );
            $db->where("id", $_POST['id']);
            $ssqldrm = $db->update('faq', $jdata);
            if ($ssqldrm) {
                $idata = array("title" => "İşlem Başarılı", "message" => "Sorular Güncellendi", "type" => "success", "error" => "");
            } else {
                $idata = array("title" => "İşlem Başarısız", "message" => "Sorular Güncellenmedi", "type" => "danger", "error" => $db->getLastError());
            }
            $json = json_encode($idata, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
            print_r($json);
        }
    }
} else if ($islem == "bos") {
    $yetki = $db->yetkikont("", "" . $userKont['id'] . "");
    if ($yetki == "1") {
    } else {
        $data = array("title" => "Yetkişiz İşlem", "message" => "" . $userKont['name'] . " bu işlem için yetkin yok!", "type" => "danger", "error" => "");
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        print_r($json);
    }
} else {
    $data = array("title" => "Yetkişiz İşlem", "message" => "" . print_r($_POST) . " " . $islem . " " . $process . "", "type" => "error", "error" => "could not be read a type");
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    print_r($json);
}