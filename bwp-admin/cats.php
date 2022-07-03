<?php 
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $pageTitle = "Kategori Yönetimi";
        $mpage = "content";
        $maltpage = "post";
        $mlink = "cats";
        $process = $_GET['process'];
        $processTitle = "Kategori Yönetimi";
        if($process == "edit"){
            $db->where ("id", $_GET["id"]);
            $editP = $db->getOne ("postcat");
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
                            <div class="col-sm-6">
                                <div class="kt-portlet kt-portlet--mobile">
                                    <div class="kt-portlet__head kt-portlet__head--lg">
                                        <div class="kt-portlet__head-label">
                                            <span class="kt-portlet__head-icon">
                                                <i class="kt-font-brand fa fa-newspaper"></i>
                                            </span>
                                            <h3 class="kt-portlet__head-title"><?php echo $processTitle;?></h3>
                                        </div>
                                        <div class="kt-portlet__head-toolbar">
                                            <div class="kt-portlet__head-wrapper">
                                                <div class="kt-portlet__head-actions">
                                                    <a href="?process=add" class="btn btn-brand btn-elevate btn-icon-sm"><i class="fa fa-plus"></i> Yeni Kayıt</a> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                                        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Kategori Tipi</th>
                                                    <th>Kategori Başlığı</th>
                                                    <th>Kategori Dili</th>
                                                    <th>İşlemler</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <form id="userForm" enctype="multipart/form-data">
                                    <div class="kt-portlet kt-portlet--mobile">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon"><i class="fa fa-plus"></i></span>
                                                <h3 class="kt-portlet__head-title"> 
                                                    <?php if($process == "edit"){echo 'Kategori Düzenle';}else{echo 'Kategori Ekle';}?>
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
                                                        <label>Kategori Başlığı</label> 
                                                        <input type="text" class="form-control" name="title" value="<?php echo $editP['title'];?>">
                                                        <input type="hidden" class="form-control" name="img">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Kategori Tipi</label>
                                                        <select class="form-control kt-selectpicker" name="catID">
                                                            <option value="0">Ana Kategori</option>
                                                            <?php
                                                                $anaKat = $db->get("postcat");
                                                                foreach ($anaKat as $p) {
                                                                    if($p['id'] == $editP['catID']){ 
                                                                        echo '<option value="'.$p['id'].'" selected>'.$p['title'].'</option>';
                                                                    }else{ 
                                                                        echo '<option value="'.$p['id'].'">'.$p['title'].'</option>';
                                                                    }
                                                                }
                                                            ?>		
                                                        </select>
                                                    </div>
                                                </div> 
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Kategori Dili</label>
                                                        <select class="form-control kt-selectpicker" name="langID">
                                                            <?php 
                                                                $langs = $db->get('langs');
                                                                foreach ($langs as $lang) {
                                                                    if($lang['id'] == $editP['langID']){ 
                                                                        echo '<option value="'.$lang['id'].'" selected>'.$lang['title'].'</option>';
                                                                    }else{ 
                                                                        echo '<option value="'.$lang['id'].'">'.$lang['title'].'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Kategori İlişikisi</label>
                                                        <select class="form-control kt-selectpicker" name="termID">
                                                            <?php 
                                                               echo '<option value="0">İlişki Yok</option>';
                                                               $db->orderBy("langID","ASC");
                                                               $termsc = $db->get('postcat');
                                                               foreach ($termsc as $termscp) {
                                                                    if($termscp['id'] == $editP['termID']){ 
                                                                        echo '<option value="'.$termscp['id'].'" selected>'.$termscp['title'].'</option>';
                                                                    }else{ 
                                                                        echo '<option value="'.$termscp['id'].'">'.$termscp['title'].'</option>';
                                                                    }
                                                                    $db->where ("catID",$termscp['id']);
                                                                    $termsc = $db->get('postcat');
                                                                    foreach ($termsc as $termscp) {
                                                                        if($termscp['id'] == $editP['termID']){ 
                                                                            echo '<option value="'.$termscp['id'].'" selected>'.$termscp['title'].'</option>';
                                                                        }else{ 
                                                                            echo '<option value="'.$termscp['id'].'">'.$termscp['title'].'</option>';
                                                                        }
                                                                    }
                                                               }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="hidden" name="resim">
                                                    <div class="dropzone dropzone-default" id="kt_dropzone_1">
                                                        <div class="dropzone-msg dz-message needsclick">
                                                            <h3 class="dropzone-msg-title">Sürükle yada tıkla</h3>
                                                            <?php
                                                                if($process == "edit"){
                                                                    $gen = $db->getSetMeta("gen_cat_pic","genislik");
                                                                    $yuk = $db->getSetMeta("gen_cat_pic","yukseklik");
                                                            ?>
                                                                <img class="w-100" src="../bwp-content/uploads/cats/<?php echo $gen.'x'.$yuk; ?>/<?php echo $editP['img'];?>">
                                                            <?php }?>
                                                        </div>
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
                ajax: "../bwp-includes/ajax.php?i=cats&process=view",
                paging : true,
                responsive: true,
				displayLength: 100,
                language: {url: "assets/plugins/custom/datatables/Turkish.json"}
            });
        }
        function pageset(id,type){
            var data = $("form#userForm").serializeArray();
            data.push({name : "id",value : id},{name : "type",value : type},{name : "media",value :  $("input[name=resim]").val()});
            $.ajax({
                url: '../bwp-includes/ajax.php?i=cats&process=set',
                type: 'POST',
                data: data,
                success: function(e) {
                    console.log(e);
                    var obj = jQuery.parseJSON(e);
                    if(type == "edit") {
                        var notify = $.notify({message: obj.message},{type: obj.type});
                        if(obj.type == "success"){
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }
                    else if(type == "add") {
                        var notify = $.notify({message: obj.message},{type: obj.type});
                        if(obj.type == "success"){
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }
                    else if(type == "delete") {
                        var notify = $.notify({message: obj.message},{type: obj.type});
                        if(obj.type == "success"){
                            setTimeout(function() {
                                window.location.href = "cats.php";
                            }, 1000);
                        }
                    }
                    else if(type == "termscat") {
                        $("select[name=termID]").html(e);
                    }
                }
            });
        }
        $("#kt_dropzone_1").dropzone({
            url: '../bwp-includes/ajax.php?i=cats&process=set',
            acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg,.webp",
            params: {'type':'catMedia'},
            maxFiles :1,
            parallelUploads: 1,
            init:function(){
                var self = this;
                self.options.addRemoveLinks = false;
                self.options.dictRemoveFile = "Sil";
                self.on("sending", function(file, xhr, formData) {
                    $('.meter').show();
                });
                self.on("totaluploadprogress", function (progress) {
                    $('.roller').width(progress + '%');
                });
                self.on("queuecomplete", function (progress) {
                    $('.meter').delay(999).slideUp(999);
                });
            },success: function(file, response){
                var obj = jQuery.parseJSON(response);
                $("input[name=resim]").val(obj.filename);
            }
        });
        jQuery(document).ready(function() {
            $("select").select2();
            yetkikont("catview");
            $("button[name=set]").on("click", function(){
                var id = $(this).data("id");
                var type = $(this).data("type");
                pageset(id,type);
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
}else {
    echo '<meta http-equiv="refresh" content="0;URL=index.php">';
}
?>