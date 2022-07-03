<?php 
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $process = $_GET['process'];
        $mpage = "setting";
        $maltpage = "mail";
        $mlink = "mailsetting";
        $pageTitle = "Mail Ayarları";
        $processTitle = "Mail Ayarları";
        $db->where ("id", "1");
        $mailSetting = $db->getOne ("mailSetting");
?>
<!DOCTYPE html>
<html lang="tr">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8" />
    <title><?php echo $pageTitle; ?> - <?php echo $panelTitle;?></title>
    <meta name="description" content="No subheader example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script src="assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
    <script src="assets/js/scripts.bundle.js" type="text/javascript"></script>
    <script src="assets/js/main.js" type="text/javascript"></script>
</head>
<body class="kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
        <div class="kt-header-mobile__logo">
            <a href="index.html">
                <img alt="Logo" src="assets/media/logos/logo-12.png" />
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
        </div>
    </div>
    <div class="kt-grid kt-grid--hor kt-grid--root">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
            <?php include 'inc/menu.php';?>
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                <?php include 'inc/header.php';?>
                <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                        <div class="row">
                            <div class="col-md-4">
                                <form method="post" action="">
                                    <div class="kt-portlet">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">E-Posta Sunucu Bilgileri</h3>
                                            </div>
                                            <div class="kt-portlet__head-toolbar">
                                                <div class="kt-portlet__head-wrapper">
                                                    <div class="kt-portlet__head-actions">
                                                        <button type="submit" class="btn btn-success" name="set"><i class="fa fa-edit"></i> Düzenle</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="form-group">
                                                <label>Giden Sunucu Adresi</label>
                                                <input type="text" class="form-control" name="mailHost" value="<?php echo $mailSetting['host'];?>" >
                                            </div>
                                            <div class="form-group">
                                                <label>Giden Sunucu Portu</label>
                                                <input type="text" class="form-control" name="mailPort" value="<?php echo $mailSetting['port'];?>" >
                                            </div>
                                            <div class="form-group">
                                                <label>SSL</label>
                                                <input type="text" class="form-control" name="mailSll" value="<?php echo $mailSetting['encryption'];?>" >
                                            </div>
                                            <div class="form-group">
                                                <label>Gönderici Mail Adresi</label>
                                                <input type="email" class="form-control" name="mailUser" value="<?php echo $mailSetting['mail'];?>" >
                                            </div>
                                            <div class="form-group">
                                                <label>Mail Şifresi</label>
                                                <input type="password" class="form-control" name="mailPass" value="<?php echo $mailSetting['pass'];?>" >
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <?php

                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        $data = Array (
                                            'host' => $_POST['mailHost'],
                                            'port' => $_POST['mailPort'],
                                            'encryption' =>$_POST['mailSll'],
                                            'mail' => $_POST['mailUser'],
                                            'pass' => $_POST['mailPass']
                                        );
                                        $db->where ('id',1);
                                        $ssqldrm = $db->update ('mailSetting', $data);
                                        if($ssqldrm){
                                            echo '<script>swal.fire("İşlem Başarılı","Ayar Düzenlendi", "success"); </script>';
                                        }else{
                                            echo '<script>swal.fire("İşlem Başarısız","Ayar Düzenlenmedi", "danger");console.log('.$db->getLastError().');</script>';
                                        }

                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include 'inc/footer.php';?>
            </div>
        </div>
    </div>
    <div id="kt_scrolltop" class="kt-scrolltop">
        <i class="fa fa-arrow-up"></i>
    </div>
    <script>
        function yetkikont(process){
            $.ajax({
                url: "../bwp-includes/ajax.php?i=yetki&process="+process+"",
                success: function(e) {
                    console.log(e);
                    var obj = jQuery.parseJSON(e);
                    if(obj.type == "success"){
                        pagelist();
                    }else {
                        var notify = $.notify({title: obj.title,message: obj.message},{type: obj.type});
                    }
                }
            });
        }
        jQuery(document).ready(function() {
            yetkikont("themeview");
        });
    </script>
</body>
</html>    
<?php 
    }
}
else {
    echo '<meta http-equiv="refresh" content="0;URL=index.php">';
}
?>