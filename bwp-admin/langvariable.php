<?php
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $pageTitle = "Dil Yönetimi";
        $mpage = "setting";
        $maltpage = "lang";
        $mlink = "langvariable";
        $process = $_GET['process'];
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
                            <div class="col-sm-12">
                                <div class="kt-portlet kt-portlet--mobile">
                                    <div class="kt-portlet__head kt-portlet__head--lg">
                                        <div class="kt-portlet__head-label">
                                            <span class="kt-portlet__head-icon">
                                                <i class="kt-font-brand fa fa-user"></i>
                                            </span>
                                            <h3 class="kt-portlet__head-title">Dil Değişkenleri</h3>
                                        </div>
                                        <div class="kt-portlet__head-toolbar">
                                            <div class="kt-portlet__head-wrapper">
                                                <div class="kt-portlet__head-actions">
                                                    <button type="button" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#kt_modal_4"><i class="fa fa-user-plus"></i> Yeni Kayıt</button>
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
                                        <div class="table-responsive">
                                            <table class="table table-striped w-100 table-bordered table-hover table-checkable" id="kt_table_1">
                                                <thead>
                                                <tr>
                                                    <th>Değişken</th>
                                                    <?php
                                                    $db->orderBy("id","ASC");
                                                    foreach ($db->get("langs") as $item) {
                                                        echo '<th>'.$item['title'].'</th>';
                                                    }
                                                    ?>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $db->where("langID","1");
                                                foreach ($db->get("langs_meta") as $item) {
                                                    echo '<tr>';
                                                    echo '<td>'.$item['type_meta'].'<br><small>'.$item['type'].'</small></td>';
                                                    $db->orderBy("id","ASC");
                                                    foreach ($db->get("langs") as $l) {
                                                        $db->where("langID",$l['id']);
                                                        $db->where("type",$item['type']);
                                                        $llmeta = $db->getOne("langs_meta");
                                                        echo '<td><input type="text" class="form-control" name="langup" data-id="'.$llmeta['id'].'" value="'.$llmeta['type_meta'].'"></td>';
                                                    }
                                                    echo '</tr>';
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
    <div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dil Değişkeni Ekle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modalForm">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="recipient-name" class="form-control-label">Değişken kısa adı:</label>
                                    <input type="text" class="form-control" name="title">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            foreach ($db->get("langs") as $k=>$v) {
                                echo '<div class="col-md-6">
                                        <div class="form-group">
                                            <label>Değişken Açıklaması('.$v['title'].')</label>
                                            <input type="text" class="form-control" name="type_meta['.$v['id'].']" value="">
                                        </div>
                                    </div>';
                            }
                            ?>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Modalı Kapat</button>
                    <button type="button" name="set" data-type="langvaradd" class="btn btn-primary">Ekle</button>
                </div>
            </div>
        </div>
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
                    }else {
                        var notify = $.notify({title: obj.title,message: obj.message},{type: obj.type});
                    }
                }
            });
        }
        jQuery(document).ready(function() {
            yetkikont("langview");
        });
        $("input[name=langup]").on("change keyup", function(){
            var ti = $(this);
            var id = $(this).data("id");
            var val = $(this).val();
            var data = [];
            data.push({name : "id",value : id},{name : "val",value : val},{name : "type",value : "langvarup"});
            $.ajax({
                url: '../bwp-includes/ajax.php?i=langs&process=set',
                type: 'POST',
                data: data,
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    if(obj.type=="success"){
                        $(ti).addClass("is-valid");
                        $(ti).removeClass("is-invalid");
                    }else {
                        $(ti).addClass("is-invalid");
                        $(ti).removeClass("is-valid");
                    }
                    console.log(obj);
                }
            });
        });
        function pageset(id,type){
            var data = $("form#modalForm").serializeArray();
            data.push({name : "type",value : type});
            $.ajax({
                url: '../bwp-includes/ajax.php?i=langs&process=set',
                type: 'POST',
                data: data,
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    swal.fire(""+obj.title+"", ""+obj.message+"", ""+obj.type+"");

                }
            });
        }
        $(document).on("click","button[name=set]", function(){
            var id = $(this).data("id");
            var type = $(this).data("type");
            pageset(id,type);
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