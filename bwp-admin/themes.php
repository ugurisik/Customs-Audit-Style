<?php 
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $process = $_GET['process'];
        $mpage = "setting";
        $maltpage = "viewlink";
        $mlink = "theme";
        $pageTitle = "Site Temaları";
        $processTitle = "Site Temaları";
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
                        <div class="row" id="themelist">
                            
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
            $.ajax({
                url: '../bwp-includes/ajax.php?i=theme&process=view',
                success: function(e) {
                    $("#themelist").html(e);
                }
            });
        }
        function pageset(id,type,form){
            var themeDir = $('#'+form+' input[name=themeDir]').val();
            var name = $('#'+form+' input[name=name]').val();
            var author = $('#'+form+' input[name=author]').val();
            var company = $('#'+form+' input[name=company]').val();
            var version = $('#'+form+' input[name=version]').val();
            var data = {type:type,themeDir:themeDir,name:name,author:author,company:company,version:version,createDate: "<?php echo date("Y-m-d");?>"};
            console.log(data);
            $.ajax({
                url: '../bwp-includes/ajax.php?i=theme&process=set',
                type: 'POST',
                data: data,
                success: function(e) {
                    console.log(e);
                    var obj = jQuery.parseJSON(e);
                    if(type == "active") {
                        if(obj.type == "success"){
                            var theme = $('input[name=themeDir]').val();
                            $.ajax({
                                url:'../bwp-content/themes/'+theme+'/themeAdmin/setup.php?themeID='+obj.themeID, 
                                success:function(cevap){
                                    $.notify({title: '<strong>İşlem Başarılı</strong> ',message: 'Tema Aktifleştirildi!'},{type: 'success'});
                                }
                            });
                        }else {
                            var notify = $.notify({message: obj.message},{type: obj.type});
                        }
                    }
                }
            });
        }
        $("#themelist").on("click",'button[name=set]', function(){
            var type = $(this).data("type");
            var themeid = $(this).data("themeid");
            pageset(null,type,themeid);
        });
        jQuery(document).ready(function() {
            $("select").select2();
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
?>