<?php 
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $pageTitle = "Dil Yönetimi";
        $mpage = "setting";
        $maltpage = "mail";
        $mlink = "mailtemplate";
        $process = $_GET['process'];
        if($process == "edit"){
            $db->where ("id", $_GET["id"]);
            $editP = $db->getOne ("mailTemplate");
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
                            <div class="<?php if($process == "edit"){ echo 'col-sm-6';}else {echo 'col-sm-12';}?>">
                                <div class="kt-portlet kt-portlet--mobile">
                                    <div class="kt-portlet__head kt-portlet__head--lg">
                                        <div class="kt-portlet__head-label">
                                            <span class="kt-portlet__head-icon">
                                                <i class="kt-font-brand fa fa-user"></i>
                                            </span>
                                            <h3 class="kt-portlet__head-title">Mail Şablonları</h3>
                                        </div>
                                        <div class="kt-portlet__head-toolbar">
                                            <div class="kt-portlet__head-wrapper">
                                                <div class="kt-portlet__head-actions">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                                        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                                            <thead>
                                                <tr>
                                                    <th>Şablon</th>
                                                    <th>Yanıt Adresi</th>
                                                    <th>Gönderen Başlığı</th>
                                                    <th>Şablon Mesajı</th>
                                                    <th>Şablon Başlığı</th>
                                                    <th>İşlemler</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 <?php if($process == "edit"){ echo 'col-sm-6 d-block';}else {echo 'd-none';}?>">
                                <form id="userForm">
                                    <div class="kt-portlet kt-portlet--mobile">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon"><i class="fa fa-user-edit"></i></span>
                                                <h3 class="kt-portlet__head-title"> 
                                                    Şablonu düzenle
                                                </h3>
                                            </div>
                                            <div class="kt-portlet__head-toolbar">
                                                <div class="kt-portlet__head-wrapper">
                                                    <div class="kt-portlet__head-actions">
                                                        <button type="button" class="btn btn-success" data-id="<?php echo $editP['id'];?>" data-type="edit" name="set"><i class="fa fa-edit"></i> Düzenle</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Şablon Yanıt Adresi</label>
                                                        <input type="text" class="form-control" name="senderMail" value="<?php echo $editP['senderMail'];?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Şablon Başlığı</label>
                                                        <input type="text" class="form-control" name="senderTitle" value="<?php echo $editP['senderTitle'];?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Talebin iletileceği admin epostası</label>
                                                        <input type="text" class="form-control" name="adminMail" value="<?php echo $editP['adminMail'];?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Admin başlığı</label>
                                                        <input type="text" class="form-control" name="adminTitle" value="<?php echo $editP['adminTitle'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <?php
                                                    $detailJson = json_decode($editP['sablonbasligi']);
                                                    foreach ($db->get("langs") as $k=>$v) {
                                                        $subtitle = $v['subtitle'];
                                                        echo '<div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Şablon Başlığı ('.$v['title'].')</label>
                                                                    <input type="text" class="form-control" name="sablonbasligi['.$v['subtitle'].']" value="'.$detailJson->$subtitle.'">
                                                                </div>
                                                            </div>';
                                                    }
                                                ?>
                                            </div>
                                            <div class="row">
                                                <?php
                                                    $detailJson = json_decode($editP['sablonmesaji']);
                                                    foreach ($db->get("langs") as $k=>$v) {
                                                        $subtitle = $v['subtitle'];
                                                        echo '<div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Şablon Mesajı ('.$v['title'].')</label>
                                                                    <textarea rows="8" class="form-control" name="sablonmesaji['.$v['subtitle'].']">'.$detailJson->$subtitle.'</textarea>
                                                                </div>
                                                            </div>';
                                                    }
                                                ?>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Alıcı Şablonu</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <textarea data-type="html" name="reciverTemplate"><?php echo $editP['reciverTemplate'];?></textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Yönetici Şablonu</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <textarea data-type="html" name="adminTemplate"><?php echo $editP['adminTemplate'];?></textarea>
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
                ajax: "../bwp-includes/ajax.php?i=mail&process=view",
                paging : true,
                responsive: true,
				displayLength: 100,
                language: {url: "assets/plugins/custom/datatables/Turkish.json"}
            });
        }
        jQuery(document).ready(function() {
            $("select").select2();
            yetkikont("langview");
            $("button[name=set]").on("click", function(){
                tinymce.triggerSave();
                var id = $(this).data("id");
                var type = $(this).data("type");
                var data = $("#userForm").serializeArray();
                data.push({name : "id",value : id},{name : "type",value : "edit"});
                $.ajax({
                    url: '../bwp-includes/ajax.php?i=mail&process=set',
                    type: 'POST',
                    data: data,
                    success: function(e) {
                        var obj = jQuery.parseJSON(e);
                        swal.fire(""+obj.title+"", ""+obj.message+"<br>"+obj.error+"", ""+obj.type+"");
                        setTimeout(function() {
                            location.reload(true);
                        }, 1000);
                    }
                });
            });
            tinymce.init({
                mode : "textareas",
                selector: "textarea[data-type=html]",
                toolbar_mode: 'wrap',
                language: 'tr',
                fontsize_formats: "13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 36px 40px 50px 60px 70px 72px",
                plugins: 'fullpage print preview paste importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons responsivefilemanager',
                toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl responsivefilemanager | tools',
                toolbar_sticky: false,
                image_advtab: true,
                importcss_append: true,
                height: 600,
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_noneditable_class: "mceNonEditable",
                contextmenu: "link image imagetools table",
                element_format : 'html',
                convert_urls: false,
                fullpage_default_doctype: '<!DOCTYPE html>'
            });
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