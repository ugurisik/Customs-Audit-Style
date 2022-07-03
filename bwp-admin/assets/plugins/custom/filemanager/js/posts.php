<?php 
session_start();
include '../bwp-includes/settings.php';
if($_SESSION['loggedin']==true){
    $db->where ("email", $_SESSION["email"]);
    $userKont = $db->getOne ("users");
    if($userKont['loginID'] == $_SESSION['loginid']){
        $process = $_GET['process'];
        $mpage = "content";
        $maltpage = "post";
        if($process == "add"){
            $pageTitle = "Yazı Ekle";
            $processTitle = "Yazı Ekle";
            $yetkitype = "postadd";
            $mlink = "postsadd";
        }else if($process == "edit"){
            $db->where ("ID", $_GET["id"]);
            $post = $db->getOne ("posts");
            $pageTitle = $post['post_title']." adlı içerik";
            $processTitle = $post['post_title']." Düzenle";
            $yetkitype = "postedit";
            $mlink = "postsadd";
        }else if(is_null($process)){
            $process = "add";
            $pageTitle = "Yazı Ekle";
            $processTitle = "Yazı Ekle";
            $yetkitype = "postadd";
            $mlink = "postsadd";
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
                        <div class="col-md-9">
                            <div class="kt-portlet kt-portlet--mobile">
                                <div class="kt-portlet__body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="baslik" placeholder="İçerik başlığı" value="<?php echo $post['post_title'];?>">
                                                <input type="hidden" class="form-control" name="id" value="<?php echo $post['ID'];?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <textarea name="tinymce"><?php echo $post['post_content'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" id="kt_sortable_portlets">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="kt-portlet kt-portlet--mobile kt-portlet--sortable">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">Öne çıkan görsel</h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body kt-scroll" data-scroll="true" style="height: 200px">
                                            <img class="w-100" id="img">
                                            <button type="button" class="btn btn-bold btn-label-brand btn-sm" data-toggle="modal" data-target="#kt_modal_4">Launch Modal</button>
                                            <div class="col-sm-12">
                                                <input type="hidden" name="resim" value="<?php echo $image['type_meta'];?>">
                                                <div class="dropzone dropzone-default" id="kt_dropzone_1">
                                                    <div class="dropzone-msg dz-message needsclick">
                                                        <h3 class="dropzone-msg-title">Değiştirmek için sürükle yada tıkla</h3><br>
                                                        <img class="w-100" src="../bwp-content/uploads/<?php echo $postDate[0];?>/<?php echo $postDate[1];?>/<?php echo $boyut; ?>/<?php echo $image['type_meta'];?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>	
                                    <div class="kt-portlet kt-portlet--mobile kt-portlet--sortable">
                                            <div class="kt-portlet__head">
                                                <div class="kt-portlet__head-label">
                                                    <h3 class="kt-portlet__head-title">Kategoriler</h3>
                                                </div>
                                            </div>
                                            <div class="kt-portlet__body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <div class="col-sm-4">
                                                            <label>İçerik Dili</label>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <select class="form-control kt-selectpicker" name="langID">
                                                                    <?php 
                                                                        $langs = $db->get('langs');
                                                                        foreach ($langs as $lang) {
                                                                            if($lang['id'] == $post['post_langID']){
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
                                            </div>
                                            <div class="kt-portlet__body kt-scroll" data-scroll="true" style="height: 370px">
                                                <?php
                                                    if($process == "edit"){
                                                        $db->where("langID",$post['post_langID']);
                                                    }
                                                    $db->where("catID",0);
                                                    $catList = $db->get("postcat");
                                                    foreach ($catList as $cp) {
                                                        $db->where ("postID", $post["ID"]);
                                                        $db->where ("type", "cat");
                                                        $db->where ("type_meta", $cp["id"]);
                                                        $postcat = $db->getOne ("post_meta");
                                                        if($postcat['type_meta'] == $cp['id']){
                                                            echo '<label class="kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success">
                                                                    <input type="checkbox" name="cat" value="'.$cp['id'].'" checked> '.$cp['title'].'
                                                                    <span></span>
                                                                </label>';
                                                        }else {
                                                            echo '<label class="kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success">
                                                                <input type="checkbox" name="cat" value="'.$cp['id'].'"> '.$cp['title'].'
                                                                <span></span>
                                                            </label>';
                                                        }
                                                        $db->where("catID",$cp['id']);
                                                        $catAltList = $db->get("postcat");
                                                        foreach ($catAltList as $cap) {
                                                            $db->where ("postID", $post["ID"]);
                                                            $db->where ("type", "cat");
                                                            $db->where ("type_meta", $cap["id"]);
                                                            $postcat = $db->getOne ("post_meta");
                                                            if($postcat['type_meta'] == $cap['id']){
                                                                echo '<label class="kt-checkbox ml-3 kt-checkbox--bold kt-checkbox--warning">
                                                                        <input type="checkbox" name="cat" value="'.$cap['id'].'" checked> '.$cap['title'].'
                                                                        <span></span>
                                                                    </label>';
                                                            }else {
                                                                echo '<label class="kt-checkbox ml-3 kt-checkbox--bold kt-checkbox--warning">
                                                                    <input type="checkbox" name="cat" value="'.$cap['id'].'"> '.$cap['title'].'
                                                                    <span></span>
                                                                </label>';
                                                            }
                                                        }
                                                    } 
                                                ?>
                                            </div>
                                        </div>	
                                    <div class="kt-portlet kt-portlet--mobile kt-portlet--sortable">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">Etiketler</h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <?php 
                                                $db->where ("termID", $post["ID"]);
                                                $db->where ("type", "tag");
                                                $tags = $db->get("terms");
                                            ?>
                                            <input id="kt_tagify_1" name="kelime" placeholder="kelime..." autofocus value="<?php foreach($tags as $tagp){ echo $tagp['type_meta'].',';}?>">
                                        </div>
                                    </div>	
                                    <div class="kt-portlet kt-portlet--mobile kt-portlet--sortable">
                                        <div class="kt-portlet__head">
                                            <div class="kt-portlet__head-label">
                                                <h3 class="kt-portlet__head-title">Yayımla</h3>
                                            </div>
                                        </div>
                                        <div class="kt-portlet__body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Durum</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select class="form-control kt-selectpicker" name="durum">
                                                                <?php 
                                                                    foreach ($status as $key => $val) {
                                                                        if($post['post_status'] == $val){
                                                                            echo '<option value="'.$val.'" selected>'.$key.'</option>';
                                                                        }else{
                                                                            echo '<option value="'.$val.'">'.$key.'</option>';
                                                                        }
                                                                    }
                                                                ?>		
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                        <label>İçerik Dili</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select class="form-control kt-selectpicker" name="langID">
                                                                <?php 
                                                                    $langs = $db->get('langs');
                                                                    foreach ($langs as $lang) {
                                                                        if($lang['id'] == $post['post_langID']){
                                                                            echo '<option value="'.$lang['id'].'" selected>'.$lang['title'].'</option>';
                                                                        }else{
                                                                            echo '<option value="'.$lang['id'].'">'.$lang['title'].'</option>';
                                                                        }
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Anasayfada Göster</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                        <?php
                                                            $db->where ("postID", $post["ID"]);
                                                            $db->where ("type", "homePageShow");
                                                            $hps = $db->getOne ("post_meta");
                                                        ?>
                                                            <label class="kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success float-right">
                                                                <?php if($hps['type_meta'] == 1){?>
                                                                    <input type="checkbox" name="homePageShow" checked><span></span>
                                                                <?php }else{?>
                                                                    <input type="checkbox" name="homePageShow"><span></span>
                                                                <?php }?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Slider'de Göster</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                        <?php
                                                            $db->where ("postID", $post["ID"]);
                                                            $db->where ("type", "slideShow");
                                                            $ss = $db->getOne ("post_meta");
                                                        ?>
                                                            <label class="kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success  float-right">
                                                                <?php if($ss['type_meta'] == 1){?>
                                                                    <input type="checkbox" name="slideShow" checked><span></span>
                                                                <?php }else{?>
                                                                    <input type="checkbox" name="slideShow"><span></span>
                                                                <?php }?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group float-right">
                                                        <button type="button" class="btn btn-dark" name="edit"><i class="flaticon-edit"></i> Düzenle</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion accordion-solid accordion-panel accordion-toggle-svg" id="accordionExample8">
                                        <div class="card">
                                            <div class="card-header" id="headingOne8">
                                                <div class="card-title" data-toggle="collapse" data-target="#collapseOne8" aria-expanded="true" aria-controls="collapseOne8">
                                                    Product Inventory 
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"/>
                                                            <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "/>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div id="collapseOne8" class="collapse show" aria-labelledby="headingOne8" data-parent="#accordionExample8">
                                                <div class="card-body">
                                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingTwo8">
                                                <div class="card-title" data-toggle="collapse" data-target="#collapseTwo8" aria-expanded="false" aria-controls="collapseTwo8">
                                                    Order Statistics 
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"/>
                                                            <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "/>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div id="collapseTwo8" class="collapse show" aria-labelledby="headingTwo8" data-parent="#accordionExample8">
                                                <div class="card-body">
                                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header" id="headingThree8">
                                                <div class="card-title" data-toggle="collapse" data-target="#collapseThree8" aria-expanded="false" aria-controls="collapseThree8">
                                                    eCommerce Reports 
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                                            <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"/>
                                                            <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "/>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div id="collapseThree8" class="collapse show" aria-labelledby="headingThree8" data-parent="#accordionExample8">
                                                <div class="card-body">
                                                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Accordion-->
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
    <div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <iframe 
                        style="border:0;width:100%;height:400px" 
                        src="assets/plugins/custom/filemanager/dialog.php?type=1&lang=tr_TR&popup=1&callback=1&field_id=img&crossdomain=1">
                    </iframe>
                </div>
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
    <script src="assets/plugins/custom/filemanager/plugin.min.js"></script>
    <script>
        function yetkikont(process){
            $.ajax({
                url: "../bwp-includes/ajax.php?i=yetki&process="+process+"",
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    console.log(obj);
                    if(obj.type == "success"){
                    }else {
                        var notify = $.notify({title: obj.title,message: obj.message},{type: obj.type});
                    }
                }
            });
        }
        function tinymceset(div){
            tinymce.init({
                selector: div,
                language: 'tr',
                plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons responsivefilemanager',
                
                toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl responsivefilemanager | tools',
                toolbar_sticky: true,
                autosave_ask_before_unload: true,
                autosave_interval: "30s",
                autosave_prefix: "{path}{query}-{id}-",
                autosave_restore_when_empty: false,
                autosave_retention: "2m",
                image_advtab: true,
                importcss_append: true,
                template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
                template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
                height: 600,
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_noneditable_class: "mceNonEditable",
                toolbar_mode: 'wrap',
                contextmenu: "link image imagetools table",
                external_filemanager_path:"assets/plugins/custom/filemanager/",
                filemanager_title:"Filemanager" ,
                external_plugins: { "filemanager" : "assets/plugins/custom/filemanager/plugin.min.js"},
                fontsize_formats: "13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 36px 40px 50px 60px 70px 72px"
            });
        }
        function catList(langID){
            $.ajax({
                url: "../bwp-includes/ajax.php?i=yetki&process="+process+"",
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    console.log(obj);
                    if(obj.type == "success"){
                    }else {
                        var notify = $.notify({title: obj.title,message: obj.message},{type: obj.type});
                    }
                }
            });
        }
        function responsive_filemanager_callback(field_id){
                console.log(field_id);
                var url=jQuery('#'+field_id).val();
            }
        jQuery(document).ready(function() {
            $("select").select2();
            yetkikont("<?php echo $yetkitype;?>");
            tinymceset("textarea[name=tinymce]");
            var input = document.querySelector('input[name=kelime]'),
            tagify = new Tagify(input, {
                enforceWhitelist : false,
                whitelist: [<?php  $db->where("type","tag"); $tags = $db->get('terms');foreach ($tags as $tagsp) { echo '"'.$tagsp['type_meta'].'",';}?>],
                callbacks: {add: console.log,remove : console.log}
            });
        });
    </script>
</body>
</html>    
<?php 
    }
}
?>