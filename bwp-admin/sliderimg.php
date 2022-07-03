<?php 
session_start();
include '../bwp-includes/settings.php';
include "../".THEMEADMIN."information.php";
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $pageTitle = "Slider Yönetimi";
        $mpage = "content";
        $maltpage = "slide";
        $process = $_GET['process'];
        $processTitle = "Slider Yönetimi";

        $db->where ("id", $_GET['id']);
        $editP = $db->getOne ("slider");

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
                            <div class="col-sm-3">
                                <div class="kt-portlet ">
                                    <div class="kt-portlet__body">
                                        <div class="accordion" id="accordionExample1">
                                            <div class="card">
                                                <div class="card-header" id="headingOne">
                                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                            </g>
                                                        </svg> Özel Resim
                                                    </div>
                                                </div>
                                                <div id="collapseOne1" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample1">
                                                    <div class="card-body">
                                                        <div class="kt-portlet__body">
                                                            <form id="image" class="form-horizontal">
                                                                <div class="form-group">
                                                                    <label for="text">1. Satır</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control item-menu" name="text" id="text" placeholder="Başlık">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="text">2. Satır</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control item-menu" name="detail" id="text" placeholder="Detay">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="text">Görüntüleme Sırası</label>
                                                                    <div class="input-group">
                                                                        <input type="number" min="0" class="form-control item-menu" name="sira">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="href">Bağlandı Adresi</label>
                                                                    <input type="text" class="form-control item-menu" id="href" name="href" placeholder="Hedef URL">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="target">Bağlantı Tipi</label>
                                                                    <select name="target" id="target" class="form-control item-menu kt-selectpicker">
                                                                        <option value="_blank">Blank (Yeni sekmede aç)</option>
                                                                        <option value="_self">Self ( Aynı sekme içerisinde aç)</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="hidden" name="resim">
                                                                    <div class="dropzone dropzone-default" id="kt_dropzone_1">
                                                                        <div class="dropzone-msg dz-message needsclick">
                                                                            <h3 class="dropzone-msg-title">Değiştirmek için sürükle yada tıkla</h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group float-right mt-4 mb-0">
                                                                    <input type="hidden" name="type" value="url">
                                                                    <button type="button" name="set"  data-type="image" data-id="<?php echo $editP['id'];?>" data-form="image" class="btn btn-outline-success">Ekle</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="tours">
                                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#toursd" aria-expanded="false" aria-controls="collapseThree1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                            </g>
                                                        </svg> Turlar
                                                    </div>
                                                </div>
                                                <div id="toursd" class="collapse" aria-labelledby="tours" data-parent="#accordionExample1">
                                                    <div class="card-body">
                                                        <form id="formTour">
                                                            <div class="kt-portlet__body kt-scroll" data-scroll="true" style="height: 370px">
                                                                <?php
                                                                    $db->where("langID",$editP['langID']);
                                                                    $db->orderBy("id","desc");
                                                                    $catAltList = $db->get("tour",100);
                                                                    foreach ($catAltList as $cap) {
                                                                ?>
                                                                    <div class="form-group ml-3 mb-0">
                                                                        <label class="kt-radio kt-radio--success kt-checkbox--warning">
                                                                            <input type="radio" name="tour" value="<?php echo $cap['id'];?>" > <?php echo $cap['title'];?>
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="form-group float-right mt-4 mb-0">
                                                                <button type="button" name="set" data-type="tours" class="btn btn-outline-success" data-toggle="modal" data-target="#kt_modal_4">Ekle</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <form id="userForm">
                                    <div class="kt-portlet kt-portlet--mobile">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <span class="kt-portlet__head-icon"><i class="fa fa-plus"></i></span>
                                                <h3 class="kt-portlet__head-title"> 
                                                    Slide Görselleri
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                                                <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>G. Sırası</th>
                                                    <th>Başlık</th>
                                                    <th>Detay</th>
                                                    <th>Resim</th>
                                                    <th>İşlemler</th>
                                                </tr>
                                                </thead>
                                            </table>
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
    <!--begin::Modal-->
    <div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="text">1. Satır</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-menu" name="text" id="text" placeholder="Başlık">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="text">2. Satır</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-menu" name="detail" id="text" placeholder="Detay">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="text">Görüntüleme Sırası</label>
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control item-menu" name="sira">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="href">Bağlandı Adresi</label>
                                    <input type="text" class="form-control item-menu" id="href" name="href" placeholder="Hedef URL">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="target">Bağlantı Tipi</label>
                                    <select name="target" id="target" class="form-control item-menu kt-selectpicker">
                                        <option value="_blank">Blank (Yeni sekmede aç)</option>
                                        <option value="_self">Self ( Aynı sekme içerisinde aç)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row kt-portlet__body kt-scroll modalimg" data-scroll="true" style="height: 370px"> </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Modalı Kapat</button>
                    <button type="button" name="set" data-type="tour" data-id="<?php echo $editP['id'];?>" data-form="modal" class="btn btn-outline-success">Ekle</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
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
            dropzonejs("../bwp-includes/ajax.php?i=slide&process=set&type=image");
            slideList("<?php echo $editP['id'];?>");
        }
        function pageset(id,type,form){
            var data = $("#"+form+"").serializeArray();
            data.push({name : "id",value : id},{name : "type",value : type});
            $.ajax({
                url: '../bwp-includes/ajax.php?i=slide&process=set',
                type: 'POST',
                data: data,
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    var notify = $.notify({message: obj.message},{type: obj.type});
                    if(type == "edit") {
                        if(obj.type == "success"){
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }
                    else if(type == "add") {
                        if(obj.type == "success"){
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }
                    else if(type == "delete") {
                        if(obj.type == "success"){
                            slideList(<?php echo $editP['id'];?>);
                        }
                    }
                    else if(type == "imgdelete") {
                        if(obj.type == "success"){
                            slideList(<?php echo $editP['id'];?>);
                        }
                    }
                    else if(type == "image"){
                        if(obj.type == "success"){
                            slideList(<?php echo $editP['id'];?>);
                        }
                    }
                    else if(type == "tours"){
                        $("#kt_modal_4 input[name=text]").val(obj.title);
                        $("#kt_modal_4 input[name=href]").val(obj.url);
                        $("#kt_modal_4 .modalimg").html("");
                        $.each( obj.media, function( key, value ) {
                            $("#kt_modal_4 .modalimg").append("<div class='col-lg-4 mb-2 mt-2'><label class='kt-radio kt-radio--success' name='radio5'> <input type='radio' name='tourslideimg' value='"+value.fileName+"'>"+value.fileurl+" <span></span> </label></div>");
                        });
                        if(obj.type == "success"){
                            slideList(<?php echo $editP['id'];?>);
                        }
                    }
                    else if(type == "tour"){
                        if(obj.type == "success"){
                            slideList(<?php echo $editP['id'];?>);
                        }
                    }
                }
            });
        }
        function dropzonejs(urltxt,type){
            $("#kt_dropzone_1").dropzone({
                url: urltxt,
                maxFilesize: 5,
                addRemoveLinks: true,
                dictResponseError: 'Server not Configured',
                acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg,.webp",
                init:function(){
                    var self = this;
                    self.options.addRemoveLinks = true;
                    self.options.dictRemoveFile = "Delete";
                    //New file added
                    self.on("addedfile", function (file) {
                        console.log('new file added ', file);
                    });
                    self.on("sending", function (file) {
                        $('.meter').show();}
                    );
                    self.on("totaluploadprogress", function (progress) {
                        console.log("progress ", progress);
                        $('.roller').width(progress + '%');
                    });
                    self.on("queuecomplete", function (progress) {
                        $('.meter').delay(999).slideUp(999);
                    });
                    self.on("removedfile", function (file) {
                        console.log(file);
                    });
                },success: function(file, response){
                    var obj = jQuery.parseJSON(response);
                    $('input[name=resim]').val(obj.filename);
                }
            });
        }
        function slideList(id){
            $('#kt_table_1').DataTable().clear().destroy();
            var data = [];
            data.push({name : "id",value : id});
            $.ajax({
                url: '../bwp-includes/ajax.php?i=slide&process=detail-view',
                type: 'POST',
                data: data,
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    tourTab = $('#kt_table_1').DataTable({
                        data:obj.data,
                        searching: true,
                        info: false,
                        responsive: true,
                        displayLength: 100,
                        language: {url: "assets/plugins/custom/datatables/Turkish.json"}
                    });
                }
            });
        }
        jQuery(document).ready(function() {
            $("select").select2();
            yetkikont("slideview");
            $('body').on('click', 'button[name=set]', function () {
                var id = $(this).data("id");
                var type = $(this).data("type");
                var form = $(this).data("form");
                if(type == "tours"){
                    pageset($("input[name='tour']:checked").val(),type,form);
                }else {
                    pageset(id,type,form);
                }
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