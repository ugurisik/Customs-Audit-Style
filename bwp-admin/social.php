<?php
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $pageTitle = "Sosyal medya Yönetimi";
        $mpage = "setting";
        $maltpage = "social";
        $process = $_GET['process'];
        $processTitle = "Sosyal medya Yönetimi";
        if($process == "edit"){
            $db->where ("id", $_GET["id"]);
            $editP = $db->getOne ("social");
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
                                    <div class="kt-portlet__body tablediv">
                                        <div class="alert alert-solid-danger alert-bold d-none" role="alert">
                                            <div class="alert-icon">
                                                <i class="flaticon-warning"></i>
                                            </div>
                                            <div class="alert-text">
                                                <h4 class="alert-heading"></h4>
                                                <p></p>
                                            </div>
                                        </div>
                                        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Başlık</th>
                                                    <th>İcon</th>
                                                    <th>URL</th>
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
                                                    <?php if($process == "edit"){echo 'Sosyal Medya Düzenle';}else{echo 'Sosyal Medya Ekle';}?>
                                                </h3>
                                            </div>
                                            <div class="kt-portlet__head-toolbar">
                                                <div class="kt-portlet__head-wrapper">
                                                    <div class="kt-portlet__head-actions">
                                                        <?php if($process == "edit"){?>
                                                            <button type="button" class="btn btn-success" data-id="<?php echo $editP['id'];?>" data-type="socialEdit" name="set"><i class="fa fa-edit"></i> Düzenle</button>
                                                        <?php }else{?>
                                                            <button type="button" class="btn btn-success" data-type="socialAdd" name="set" ><i class="fa fa-plus"></i> Ekle</button>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Başlık</label>
                                                    <input type="text" class="form-control" name="baslik" value="<?php echo $editP['baslik'];?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Arka Plan Rengi</label>
                                                    <input class="form-control" type="color" pattern="#[a-f0-9]{6}" name="bg-color" value="<?php echo $editP['bg-color'];?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Sosyal Medya İkonu</label>
                                                    <select class="select2-icon form-control" name="icon">
                                                        <?php
                                                            foreach ($iconPack as $k=>$v) {
                                                                if($editP['icon'] == $v){
                                                                    echo '<option value="'.$v.'" data-icon="'.$v.'" selected><i class="'.$v.'">'.$v.'</i></option>';
                                                                }else {
                                                                    echo '<option value="'.$v.'" data-icon="'.$v.'"><i class="'.$v.'">'.$v.'</i></option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Hedef URL</label>
                                                    <input class="form-control" type="text" name="url" value="<?php echo $editP['url'];?>">
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
    <script src="assets/plugins/custom/tinymce/tinymce.min.js"></script>
    <script src="assets/js/jquery.maskMoney.min.js"></script>
    <script>
        function yetkikont(process){
            $.ajax({
                url: "../bwp-includes/ajax.php?i=yetki&process="+process+"",
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    if(obj.type == "success"){
                        table();
                    }else {
                        var notify = $.notify({title: obj.title,message: obj.message},{type: obj.type});
                    }
                }
            });
        }
        function table(){
            $.ajax({
                url: '../bwp-includes/ajax.php?i=social&process=view',
                type: 'POST',
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    if(obj.notify.type == "danger")
                    {
                        $(".tablediv .alert").removeClass("d-none");
                        $(".tablediv .alert .alert-text h4").html(obj.notify.title);
                        $(".tablediv .alert .alert-text p").html(obj.notify.message);
                        $(".tablediv table").addClass("d-none");
                    }
                    else if(obj.notify.type == "success")
                    {
                        $(".tablediv .alert").addClass("d-none");
                        $(".tablediv table").removeClass("d-none");
                        $('.tablediv table').DataTable({
                            data:obj.data,
                            searching: false,
                            info: false,
                            responsive: true,
                            displayLength: 50,
                            language: {url: "assets/plugins/custom/datatables/Turkish.json"}
                        });
                    }
                }
            });
        }
        function pageset(id,type){
            var data = $("form#userForm").serializeArray();
            data.push({name : "type",value : type});
            if(type=="socialEdit" || type=="socialDel"){
                data.push({name : "id",value : id});
            }
            $.ajax({
                url: '../bwp-includes/ajax.php?i=social&process=set',
                type: 'POST',
                data: data,
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    swal.fire(""+obj.title+"", ""+obj.message+"<br>"+obj.error+"", ""+obj.type+"");

                    $('.tablediv table').DataTable().clear().destroy();
                    table();
                }
            });
        }
        function iformat(icon) {
            var originalOption = icon.element;
            return $('<span><i class="' + $(originalOption).data('icon') + '"></i> ' + icon.text + '</span>');
        }
        jQuery(document).ready(function() {
            $("select").select2();
            $('.select2-icon').select2({
                templateSelection: iformat,
                templateResult: iformat,
                allowHtml: true
            });
            yetkikont("socialViews");
            $(document).on("click","button[name=set]", function(){
                var id = $(this).data("id");
                var type = $(this).data("type");
                pageset(id,type);
            });
            <?php if($process == "delete"){?>
                pageset(<?php echo $_GET['id'];?>,"socialDel");
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