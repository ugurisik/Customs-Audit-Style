<?php 
session_start();
include '../bwp-includes/settings.php';
?>
<!DOCTYPE html>
<html lang="tr">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8" />
    <title><?php echo $panelTitle;?></title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <link href="assets/css/login.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
</head>
<body class="kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div class="kt-grid kt-grid--ver kt-grid--root kt-page">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(assets/media/bg/bg-1.jpg);">
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__logo">
                            <a href="#">
                                <img src="assets/media/logos/logo-4.png">
                            </a>
                        </div>
                        <?php 
                            $db->where ("ip", $security->getIP());
                            $db->orderBy("startDate","desc");
                            $ipKont = $db->getOne("login_ban");
                            if($ipKont){
                                $db->where ("ip", $security->getIP());
                                $db->orderBy("startDate","desc");
                                $users = $db->getOne("login_ban");
                                if($tarih > $users['endDate']){
                        ?>
                            <div class="kt-login__signin">
                                <form class="kt-form" id="login" action="#">
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="E-posta" name="email" autocomplete="off">
                                    </div>
                                    <div class="input-group">
                                        <input class="form-control" type="password" placeholder="Şifre" name="password">
                                    </div>
                                    <div class="kt-login__actions">
                                        <button type="button" name="giris" class="btn btn-brand btn-pill kt-login__btn-primary">Giriş Yap</button>
                                    </div>
                                </form>
                            </div>
                        <?php 
                                }else {
                                    echo '<div class="alert alert-danger fade show" role="alert">
                                        <div class="alert-icon"><i class="flaticon-danger"></i></div>
                                        <div class="alert-text">IP Adresiniz engellenmiş giriş yapamazsınız!</div>
                                        <div class="alert-close">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                            </button>
                                        </div>
                                    </div>';
                                }
                            }else {
                        ?>
                        <div class="kt-login__signin">
                            <form class="kt-form" id="login" action="#">
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="E-posta" name="email" autocomplete="off">
                                </div>
                                <div class="input-group">
                                    <input class="form-control" type="password" placeholder="Şifre" name="password">
                                </div>
                                <div class="kt-login__actions">
                                    <button type="button" name="giris" class="btn btn-brand btn-pill kt-login__btn-primary">Giriş Yap</button>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
    <script src="assets/js/scripts.bundle.js" type="text/javascript"></script>
    <script src="assets/js/main.js" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function() {
            console.log("<?php echo $security->getIP() ?>");
            <?php 
                if($_SESSION['loggedin']==true){
                    $db->where ("email", $_SESSION["email"]);
                    $userKont = $db->getOne ("users");
                    if($userKont['loginID'] == $_SESSION['loginid']){
            ?>
            var interval = 2;
            var notify = $.notify({title:"Giriş Yapılmış. Yönlendiriliyorsunuz !",message: "Yönlendirilmeye son " + interval + " saniye"},{type: "success",showProgressbar: true},{delay:2000});
            
            setInterval(function() {
                interval--;
                notify.update('message', "Yönlendirilmeye son " + interval + " saniye");
            }, 1000);
            setTimeout(function() {
                notify.update('message', "Yönlendiriliyorsunuz !");
            }, 2000);
            setTimeout(function() {
                window.location.href = "dashboard.php";
            }, 2000);
            <?php } }?>
            $("button[name=giris]").on("click", function() {
                var data = $("#login").serialize();
                $.ajax({
                    url: '../bwp-includes/ajax.php?i=login',
                    type: 'POST',
                    data: data,
                    success: function(e) {
                        console.log(e);
                        var obj = jQuery.parseJSON(e);
                        var notify = $.notify({message: obj.message},{type: obj.type,showProgressbar: true},{delay:2000});
                        if(obj.type == "success"){
                            var interval = 5;
                            setInterval(function() {
                                interval--;
                                notify.update('message', "Yönlendirilmeye son " + interval + " saniye");
                            }, 1000);
                            setTimeout(function() {
                                notify.update('message', "Yönlendiriliyorsunuz !");
                            }, 5000);
                            setTimeout(function() {
                                window.location.href = "dashboard.php";
                            }, 1000);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>