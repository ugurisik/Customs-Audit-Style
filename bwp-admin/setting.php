<?php 
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $process = $_GET['process'];
        $mpage = "setting";
        $maltpage = "setlink";
        if($process == "add"){
            $pageTitle = "Ayar Ekle";
            $processTitle = "Ayar Ekle";
            $yetkitype = "setadd";
            $mlink = "setadd";
        }else if($process == "edit"){
            $db->where ("id", $_GET["id"]);
            $post = $db->getOne ("settings");
            
            $db->where ("id", $post['langID']);
            $langm = $db->getOne ("langs");

            $pageTitle = "Ayar Düzenle";
            $processTitle = "Ayar Düzenle";
            $yetkitype = "postedit";
            $mlink = "setadd";
        }else if(is_null($process)){
            $process = "add";
            $pageTitle = "Ayar Ekle";
            $processTitle = "Ayar Ekle";
            $yetkitype = "setadd";
            $mlink = "setadd";
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
                            <div class="col-sm-12">
                                <form class="kt-form col-md-12" method="post">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="kt-portlet">
                                                <div class="kt-portlet__head">
                                                    <div class="kt-portlet__head-label">
                                                        <h3 class="kt-portlet__head-title">
                                                            Site Bilgileri
                                                        </h3>
                                                    </div>
                                                </div>
                                                <div class="kt-portlet__body">
                                                    <div class="form-group">
                                                        <label>Site Başlığı</label>			
                                                        <input type="text" class="form-control" name="baslik" maxlength="65" value="<?php echo $post['baslik'];?>">
                                                    </div>			
                                                    <div class="form-group">
                                                        <label>Site Açıklama</label>				
                                                        <input type="text" class="form-control" name="aciklama" maxlength="154" value="<?php echo $post['aciklama'];?>">
                                                    </div>			
                                                    <div class="form-group">
                                                        <label>Site URL</label>					
                                                        <input type="text" class="form-control" name="siteurl" value="<?php echo $post['siteurl'];?>">
                                                    </div>      
                                                    <div class="form-group">
                                                        <label>Site Seo Kelime</label>
                                                        <input id="kt_tagify_1" name="kelime" placeholder="kelime..." autofocus value="<?php echo $post['keywords'];?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Ayar Dili</label>
                                                        <select class="form-control kt-selectpicker" name="langID">
                                                            <?php 
                                                                $langs = $db->get('langs');
                                                                foreach ($langs as $lang) {
                                                                    if($lang['id'] == $post['langID']){
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
                                        </div>
                                        <div class="col-md-4">
                                            <div class="kt-portlet">
                                                <div class="kt-portlet__head">
                                                    <div class="kt-portlet__head-label">
                                                        <h3 class="kt-portlet__head-title">İletişim Bilgileri</h3>
                                                    </div>
                                                </div>
                                                <div class="kt-portlet__body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label >Telefon</label>			
                                                                <input type="text" class="form-control" name="tel" value="<?php echo $post['tel'];?>">
                                                            </div>	
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Fax</label>				
                                                                <input type="text" class="form-control" name="fax" value="<?php echo $post['fax'];?>">
                                                            </div>
                                                        </div>	
                                                    </div>		
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>GSM</label>						
                                                                <input type="text" class="form-control" name="gsm" value="<?php echo $post['gsm'];?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>E-Posta</label>					
                                                                <input type="text" class="form-control" name="eposta" value="<?php echo $post['eposta'];?>">
                                                            </div>	
                                                        </div>	
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Boylam</label>							
                                                                <input type="text" class="form-control" name="lng" value="<?php echo $post['lat'];?>">
                                                            </div>	
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Enlem</label>						
                                                                <input type="text" class="form-control" name="lat" value="<?php echo $post['lng'];?>">
                                                            </div>
                                                        </div>	
                                                    </div>	
                                                    <div class="form-group">
                                                        <label>Adres</label>								
                                                        <input type="text" class="form-control" name="adres" value="<?php echo $post['adres'];?>">
                                                    </div>				
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="kt-portlet">
                                                <div class="kt-portlet__head">
                                                    <div class="kt-portlet__head-label">
                                                        <h3 class="kt-portlet__head-title">E-Posta Sunucu Bilgileri</h3>
                                                    </div>
                                                </div>
                                                <div class="kt-portlet__body">
                                                    <div class="form-group">
                                                        <label>Giden Sunucu Adresi</label>			
                                                        <input type="text" class="form-control" name="mailHost" value="<?php echo $post['mailHost'];?>" >
                                                    </div>			
                                                    <div class="form-group">
                                                        <label>Giden Sunucu Portu</label>			
                                                        <input type="text" class="form-control" name="mailPort" value="<?php echo $post['mailPort'];?>" >
                                                    </div>			
                                                    <div class="form-group">
                                                        <label>Gönderici Mail Adresi</label>	
                                                        <input type="email" class="form-control" name="mailUser" value="<?php echo $post['mailUser'];?>" >
                                                    </div>	
                                                    <div class="form-group">
                                                        <label>Mail Şifresi</label>		
                                                        <input type="password" class="form-control" name="mailPass" value="<?php echo $post['mailPass'];?>" >
                                                    </div>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">   
                                            <div class="kt-portlet">   
                                                <div class="kt-portlet__body">                                                
                                                    <?php if($process == "edit"){?>
                                                        <button type="button" class="btn btn-dark" data-id="<?php echo $post['id'];?>" data-type="edit" name="set"><i class="fa fa-edit"></i> Düzenle</button>
                                                    <?php }else{?>
                                                        <button type="button" class="btn btn-success"  data-type="add" name="set" ><i class="fa fa-plus"></i> Ekle</button>
                                                    <?php }?>
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
                ajax: "../bwp-includes/ajax.php?i=sets&process=view",
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
                url: '../bwp-includes/ajax.php?i=sets&process=set',
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
                                window.location.href = "allset.php";
                            }, 1000);
                        }
                    }
                }
            });
        }
        $("button[name=set]").on("click", function(){
            var id = $(this).data("id");
            var type = $(this).data("type");
            pageset(id,type);
        });
        jQuery(document).ready(function() {
            $("select").select2();
            yetkikont("setview");
            var input = document.querySelector('input[name=kelime]');
            new Tagify(input)
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