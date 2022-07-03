<?php 
session_start();
include '../bwp-includes/settings.php';
include "../".THEMEADMIN."information.php";
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $pageTitle = "Sayfa Yönetimi";
        $mpage = "content";
        $maltpage = "page";
        $process = $_GET['process'];
        $processTitle = "Sayfa Yönetimi";
        if($process == "edit"){
            $mlink = "pageadd";
            $db->where ("id", $_GET["id"]);
            $editP = $db->getOne ("page");
        }else if($process == "add"){
            $mlink = "pageadd";
        }else {
            $mlink = "pages";
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
                                                    <th>Sayfa Dili</th>
                                                    <th>Başlık</th>
                                                    <th>Sayfa Tasarımı</th>
                                                    <th>İşlemler</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <form id="userForm">
                                    <div class="kt-portlet kt-portlet--mobile">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon"><i class="fa fa-plus"></i></span>
                                                <h3 class="kt-portlet__head-title"> 
                                                    <?php if($process == "edit"){echo 'Sayfa Düzenle';}else{echo 'Sayfa Ekle';}?>
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
                                                        <label>Sayfa Başlığı</label>			
                                                        <input type="text" class="form-control" name="title" value="<?php echo $editP['title'];?>">
                                                    </div>
                                                </div>		
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Sayfa Adresi</label> 
                                                        <input type="text" class="form-control" name="url" value="<?php echo $editP['url'];?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Sayfa Dili</label>
                                                    <div class="row">
                                                        <div class="col-md-12">
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
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Sayfa Şablonu</label>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <select class="form-control kt-selectpicker" name="template">
                                                                <?php
                                                                    print_r($pageTemplate);
                                                                    foreach ($pageTemplate as $key => $value) {
                                                                        if($editP['template'] == $value){
                                                                            echo '<option value="'.$value.'" selected>'.$key.'</option>';
                                                                        }else{
                                                                            echo '<option value="'.$value.'">'.$key.'</option>';
                                                                        }
                                                                    }
                                                                ?> 
                                                            </select>
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
                    console.log(obj);
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
                ajax: "../bwp-includes/ajax.php?i=page&process=view",
                paging : true,
                responsive: true,
				displayLength: 100,
                language: {url: "assets/plugins/custom/datatables/Turkish.json"}
            });
        }
        function pageset(id,type){
            var data = $("form").serializeArray();
            data.push({name : "id",value : id},{name : "type",value : type});
            $.ajax({
                url: '../bwp-includes/ajax.php?i=page&process=set',
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
                                window.location.href = "page.php";
                            }, 1000);
                        }
                    }
                }
            });
        }
        jQuery(document).ready(function() {
            $("select").select2();
            yetkikont("pageview");
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
}
else {
    echo '<meta http-equiv="refresh" content="0;URL=index.php">';
}
?>