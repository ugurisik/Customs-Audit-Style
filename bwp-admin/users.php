<?php 
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $pageTitle = "Kullanıcı Yönetimi";
        $mpage = "users";
        $process = $_GET['process'];
        if($process == "edit"){
            $db->where ("id", $_GET["id"]);
            $editP = $db->getOne ("users");
            $db->where ("userID", $editP['id']);
            $db->where ("type", "yetki");
            $yetki = $db->getOne ("users_meta");
            $db->where ("userID", $editP['id']);
            $db->where ("type", "durum");
            $durum = $db->getOne ("users_meta");
        }
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
                            <div class="col-sm-8">
                                <div class="col-sm-12">
                                    <div class="kt-portlet kt-portlet--mobile">
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title">Kullanıcılar</h3>
                                            </div>
                                            <div class="kt-portlet__head-toolbar">
                                                <div class="kt-portlet__head-wrapper">
                                                    <div class="kt-portlet__head-actions">
                                                        <a href="?process=add" class="btn btn-brand btn-elevate btn-icon-sm"><i class="fa fa-user-plus"></i> Yeni Kayıt</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>İsim Soyisim</th>
                                                        <th>Telefon</th>
                                                        <th>E-Posta</th>
                                                        <th>Firma</th>
                                                        <th>Bölümü</th>
                                                        <th>Kayıt Tarihi</th>
                                                        <th>İşlemler</th>
                                                        <th>Son Giriş Tarih</th>
                                                        <th>Son Giriş IP</th>
                                                        <th>Son Giriş OS</th>
                                                        <th>Son Giriş Browser</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="kt-portlet kt-portlet--mobile">
                                        <div class="kt-portlet__head kt-portlet__head--lg">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon">
                                                    <i class="fa fa-store"></i>
                                                </span>
                                                <h3 class="kt-portlet__head-title"><span class="name"><?php echo $editP['name'];?></span> <span class="surname"><?php echo $editP['surname'];?></span> Kullanıcısının yetkileri</h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="row">
                                                <div class="col-md-12" id="mah">
                                                    <div class="row kt-checkbox-inline">
                                                        <?php 
                                                            foreach ($yetkiler as $k => $v) {
                                                                echo '<div class="col-md-3">';
                                                                    $db->where('userID',$editP['id']);
                                                                    $db->where('type',$v);
                                                                    $yetkiknt = $db->getOne('users_meta');
                                                                    if($yetkiknt['type_meta'] == 1){
                                                                        echo '<label class="kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success">';
                                                                            echo '<input type="checkbox" name="yetki" value="'.$v.'" checked>'.$k.'<span></span>';
                                                                    }else {
                                                                        echo '<label class="kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--warning">';
                                                                            echo '<input type="checkbox" name="yetki" value="'.$v.'">'.$k.'<span></span>';
                                                                    }
                                                                    echo '</label>';
                                                                echo '</div>';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <?php if($process == "edit"){?>
                                                        <button type="button" class="btn btn-success" data-id="<?php echo $editP['id'];?>" data-type="edit" name="set"><i class="fa fa-edit"></i> Düzenle</button>
                                                    <?php }else{?>
                                                        <button type="button" class="btn btn-success"  data-type="add" name="set" ><i class="fa fa-plus"></i> Ekle</button>
                                                    <?php }?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <form id="userForm">
                                    <div class="kt-portlet kt-portlet--mobile">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon"><i class="fa fa-user-edit"></i></span>
                                                <h3 class="kt-portlet__head-title"> 
                                                    <?php if($process == "edit"){echo 'Kullanıcı Düzenle';}else{echo 'Kullanıcı Ekle';}?>
                                                </h3>
                                            </div>
                                            <div class="kt-portlet__head-toolbar">
                                                <div class="kt-portlet__head-wrapper">
                                                    <div class="kt-portlet__head-actions">
                                                        <?php if($process == "edit"){?>
                                                            <button type="button" class="btn btn-success" data-id="<?php echo $editP['id'];?>" data-type="edit" name="set"><i class="fa fa-edit"></i> Düzenle</button>
                                                        <?php }else{?>
                                                            <button type="button" class="btn btn-success"  data-type="add" name="set" ><i class="fa fa-plus"></i> Ekle</button>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>İsim</label>
                                                        <input type="text" class="form-control" name="isim" value="<?php if($process == "edit"){ echo $editP['name'];}?>">
                                                    </div>
                                                </div>		
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Soyisim</label>
                                                        <input type="text" class="form-control" name="soyisim" value="<?php if($process == "edit"){ echo $editP['surname'];}?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>E-Posta</label>
                                                        <input type="email" class="form-control" name="eposta" value="<?php if($process == "edit"){ echo $editP['email'];}?>">
                                                    </div>
                                                </div>		
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Telefon</label>
                                                        <input type="text" class="form-control" name="telefon" value="<?php if($process == "edit"){ echo $editP['phone'];}?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Firma</label>
                                                        <input type="text" class="form-control" name="comp" value="<?php if($process == "edit"){ echo $editP['company'];}?>">
                                                    </div>
                                                </div>		
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Departman</label>
                                                        <input type="text" class="form-control" name="dept" value="<?php if($process == "edit"){ echo $editP['department'];}?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Adres</label>
                                                        <input type="text" class="form-control" name="address" value="<?php if($process == "edit"){ echo $editP['address'];}?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Şifre</label>
                                                        <input type="hidden" name="passwd" value="<?php if($process == "edit"){ echo $editP['password'];}?>">
                                                        <input type="text" class="form-control" name="pass">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Şifre Doğrula</label>
                                                        <input type="text" class="form-control" name="passd">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Yetki</label>	
                                                        <select class="form-control kt-selectpicker" name="yetki">
                                                            <?php 
                                                                foreach ($authorization as $key => $val) {
                                                                    if($yetki['type_meta'] == $val){
                                                                        echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                                                    }else{
                                                                        echo '<option value="'.$val.'">'.$key.'</option>';
                                                                    }
                                                                }
                                                            ?>		
                                                        </select>
                                                    </div>
                                                </div>   
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Üye Durumu</label>	
                                                        <select class="form-control kt-selectpicker" name="durum">
                                                            <?php 
                                                                foreach ($status as $key => $val) {
                                                                    if($durum['type_meta'] == $val){
                                                                        echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                                                    }else{
                                                                        echo '<option value="'.$val.'">'.$key.'</option>';
                                                                    }
                                                                }
                                                            ?>		
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    <script src="assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
    <script src="assets/js/scripts.bundle.js" type="text/javascript"></script>
    <script src="assets/js/main.js" type="text/javascript"></script>
    <script src="assets/plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
    <script>
        function yetkikont(process){
            $.ajax({
                url: "../bwp-includes/ajax.php?i=yetki&process="+process+"",
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    if(obj.type == "success"){
                        pagelist();
                    }else {
                        var notify = $.notify({title: obj.title,message: obj.message},{type: obj.type});
                    }
                }
            });
        }
        function pagelist(){
            var table = $('#kt_table_1').DataTable({
                ajax: "../bwp-includes/ajax.php?i=user&process=view",
                paging : true,
                responsive: true,
				displayLength: 100,
                language: {url: "assets/plugins/custom/datatables/Turkish.json"}
            });
        }
        function pageset(id,type){
            var thisauth = [];
            $('input[name=yetki]').each(function(){  
                if($(this).is(":checked")){  
                    thisauth.push($(this).val());
                }  
            });  
            var data = $("form").serializeArray();
            data.push({name : "id",value : id},{name : "type",value : type},{name : "yetkiler",value : thisauth});
            $.ajax({
                url: '../bwp-includes/ajax.php?i=user&process=set',
                type: 'POST',
                data: data,
                success: function(e) {
                    console.log(e);
                    var obj = jQuery.parseJSON(e);
                    var notify = $.notify({message: obj.message},{type: obj.type});
                    if(type == "edit") {
                        if(obj.type == "success"){
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }else if(type == "add") {
                        if(obj.type == "success"){
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }else if(type == "delete") {
                        if(obj.type == "success"){
                            setTimeout(function() {
                                window.location.href = "users.php";
                            }, 1000);
                        }
                    }
                }
            });
        }
        jQuery(document).ready(function() {
            $("select").select2();
            yetkikont("userview");
            $("button[name=set]").on("click", function(){
                var id = $(this).data("id");
                var type = $(this).data("type");
                pageset(id,type);
            });
            $("input[name=isim]").on("keyup change click", function(){
                $(".name").html(this.value);
            });
            $("input[name=soyisim]").on("keyup change click", function(){
                $(".surname").html(this.value);
            });
            <?php if($process == "delete"){?>
                pageset(<?php echo $_GET['id'];?>,"delete");
            <?php }?>
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