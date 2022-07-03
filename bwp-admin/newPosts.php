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
        }
        else if($process == "edit"){
            $db->where ("ID", $_GET["id"]);
            $post = $db->getOne ("posts");
            $b1 = $yazi->yazibol(" ",$post['post_date']);
            $postDate = $yazi->yazibol("-",$b1[0]);
            $db->where ("postID", $post["ID"]);
            $db->where ("type", "image");
            $image = $db->getOne ("post_meta");

            $gen = $db->getSetMeta("blogdetail","genislik");
            $yuk = $db->getSetMeta("blogdetail","yukseklik");
            $boyut = $gen.'x'.$yuk;

            $pageTitle = $post['post_title']." adlı içerik";
            $processTitle = $post['post_title']." Düzenle";
            $yetkitype = "postedit";
            $mlink = "postsadd";
        }
        else if(is_null($process)){
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
                                        <div class="accordion accordion-solid accordion-panel accordion-toggle-svg" id="accordionExample8">
                                            <div class="card">
                                                <div class="card-header" id="headingOne8">
                                                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne8" aria-expanded="true" aria-controls="collapseOne8">
                                                    Öne çıkan görsel
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
                                                        <div class="col-sm-12">
                                                            <input type="hidden" name="resim" value="<?php echo $image['type_meta'];?>">
                                                            <div class="dropzone dropzone-default" id="kt_dropzone_1">
                                                                <div class="dropzone-msg dz-message needsclick">
                                                                    <h3 class="dropzone-msg-title">Değiştirmek için sürükle yada tıkla</h3><br>
                                                                    <?php if($process == "edit"){?>
                                                                        <img class="w-100" src="../bwp-content/uploads/posts/<?php echo $boyut; ?>/<?php echo $image['type_meta'];?>">
                                                                    <?php }?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingTwo8">
                                                    <div class="card-title" data-toggle="collapse" data-target="#collapseTwo8" aria-expanded="false" aria-controls="collapseTwo8">
                                                        Kategoriler
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
                                                    <div class="card-body  kt-scroll" data-scroll="true" style="height: 370px">
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
                                                        <div class="col-md-12">
                                                            <div class="row" id="catList">
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
                                                                            echo '<label class="col-md-12 kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success">
                                                                                    <input type="checkbox" name="cat" value="'.$cp['id'].'" checked> '.$cp['title'].'
                                                                                    <span></span>
                                                                                </label>';
                                                                        }else {
                                                                            echo '<label class="col-md-12 kt-checkbox ml-0 kt-checkbox--bold kt-checkbox--success">
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
                                                                                echo '<label class="col-md-12 kt-checkbox ml-3 kt-checkbox--bold kt-checkbox--warning">
                                                                                        <input type="checkbox" name="cat" value="'.$cap['id'].'" checked> '.$cap['title'].'
                                                                                        <span></span>
                                                                                    </label>';
                                                                            }else {
                                                                                echo '<label class="col-md-12 kt-checkbox ml-3 kt-checkbox--bold kt-checkbox--warning">
                                                                                    <input type="checkbox" name="cat" value="'.$cap['id'].'"> '.$cap['title'].'
                                                                                    <span></span>
                                                                                </label>';
                                                                            }
                                                                            $db->where("catID",$cap['id']);
                                                                            $catAltList = $db->get("postcat");
                                                                            foreach ($catAltList as $cap) {
                                                                                $db->where ("postID", $post["ID"]);
                                                                                $db->where ("type", "cat");
                                                                                $db->where ("type_meta", $cap["id"]);
                                                                                $postcat = $db->getOne ("post_meta");
                                                                                if($postcat['type_meta'] == $cap['id']){
                                                                                    echo '<label class="col-md-12 kt-checkbox ml-5 kt-checkbox--bold kt-checkbox--danger">
                                                                                        <input type="checkbox" name="cat" value="'.$cap['id'].'" checked> '.$cap['title'].'
                                                                                        <span></span>
                                                                                    </label>';
                                                                                }else {
                                                                                    echo '<label class="col-md-12 kt-checkbox ml-5 kt-checkbox--bold kt-checkbox--danger">
                                                                                    <input type="checkbox" name="cat" value="'.$cap['id'].'"> '.$cap['title'].'
                                                                                    <span></span>
                                                                                </label>';
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingTwo8">
                                                    <div class="card-title" data-toggle="collapse" data-target="#collapseTwo8" aria-expanded="false" aria-controls="collapseTwo8">
                                                        PDF Yükle
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
                                                    <div class="card-body  kt-scroll" data-scroll="true" style="height: 370px">
                                                        <div class="mt-4 col-lg-12 d-none file-prog">
                                                            <div class="progress progress-bar-success mb-2">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-4 col-md-12">
                                                            <input type="hidden" name="post_file" value="<?php echo $editP['post_file'];?>">
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="pdf" id="customFile" accept="application/pdf">
                                                                <label class="custom-file-label" for="customFile">PDF dosyası seçiniz</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingThree8">
                                                    <div class="card-title" data-toggle="collapse" data-target="#collapseThree8" aria-expanded="false" aria-controls="collapseThree8">
                                                        Diğer Ayarlar
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
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group row">
                                                                <div class="col-sm-4">
                                                                    <label>Yorumlanabilir sayfa mı?</label>
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <select class="form-control kt-selectpicker" name="post_comment">
                                                                        <?php
                                                                        foreach ($postComment as $key => $val) {
                                                                            if($post['post_comment'] == $val){
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
                                                        <div class="col-sm-12">
                                                            <div class="form-group float-right">
                                                                <?php if($process == "edit"){?>
                                                                    <button type="button" class="btn btn-dark" data-id="<?php echo $editP['id'];?>" data-type="edit" name="set"><i class="fa fa-edit"></i> Düzenle</button>
                                                                <?php }else{?>
                                                                    <button type="button" class="btn btn-success"  data-type="newAdd" name="set" ><i class="fa fa-plus"></i> Ekle</button>
                                                                <?php }?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    <div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <iframe
                        style="border:0;width:100%;height:400px"
                        src="assets/plugins/custom/filemanager/dialog.php?type=4&descending=false&lang=tr&akey=key&crossdomain=1">
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
                toolbar_mode: 'wrap',
                language: 'tr',
                fontsize_formats: "13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 36px 40px 50px 60px 70px 72px",
                plugins: 'print preview paste importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons responsivefilemanager',
                toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl responsivefilemanager | tools',
                toolbar_sticky: false,
                image_advtab: true,
                importcss_append: true,
                height: 600,
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_noneditable_class: "mceNonEditable",
                contextmenu: "link image imagetools table",
                external_filemanager_path:"assets/plugins/custom/filemanager/",
                filemanager_title:"Filemanager" ,
                external_plugins: { "filemanager" : "assets/plugins/custom/filemanager/plugin.min.js"},
                images_upload_handler : function(blobInfo, success, failure) {
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '../bwp-includes/ajax.php?i=imgup');
                    xhr.onload = function() {
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        var obj = jQuery.parseJSON(xhr.responseText);

                        success(obj.file_path);

                    };
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                }
            });
        }
        function catList(langID){
            var data = {langID : langID};
            $.ajax({
                url: '../bwp-includes/ajax.php?i=catList',
                type: 'POST',
                data: data,
                success: function(e) {
                   $("#catList").html(e);
                }
            });
        }
        function pagesetTwo(id,type){
            tinymce.triggerSave();
            var formData = new FormData($('.userForm')[0]);
            if(type == "edit"){
                formData.append("id",id);
            }
            formData.append("type",type);
            $.ajax({
                url: "../bwp-includes/ajax.php?i=posts&process=set",
                type: 'POST',
                data: formData,
                xhr: function() {
                    $(".file-prog").removeClass("d-none");
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            $(".file-prog .progress-bar").width(+ (Math.round(percentComplete * 100)) +'%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(e) {
                    console.log(e);
                    var obj = jQuery.parseJSON(e);
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
                            setTimeout(function() {
                                window.location.href = "posts.php";
                            }, 1000);
                        }
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
        $('select[name=langID]').on('keyup change clear',function () {
            catList(this.value);
        });
        dropzonejs("../bwp-includes/ajax.php?i=imgadd");
        function dropzonejs(urltxt){
            $("#kt_dropzone_1").dropzone({
                url: urltxt,
                params: {
                    type: "posts"
                },
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
                    console.log("response",response);
                    $('input[name=resim]').val(response);
                }
            });
        }
        tinymceset("textarea[name=tinymce]");
        jQuery(document).ready(function() {
            $("select").select2();
            yetkikont("<?php echo $yetkitype;?>");
            /*$(document).on("click","button[name=set]", function(){
                var tip = $(this).data("type");
                var id = $('input[name=id]').val();
                var baslik = $('input[name=baslik]').val();
                var icerik = tinymce.activeEditor.getContent();
                var durum = $('select[name=durum]').val();
                var langID = $('select[name=langID]').val();
                var comment = $('select[name=post_comment]').val();
                var resim = $('input[name=resim]').val();
                var cat = [];
                $('input[name=cat]').each(function(){
                    if($(this).is(":checked")){
                        cat.push($(this).val());
                    }
                });
                //var dataString = {tip:tip,id:id,title:baslik,image:resim,content:""+icerik+"",cat: cat.toString(),durum:durum,comment:comment,langID:langID,author: <?php echo $userKont['id'];?>,date: "<?php echo $tarih;?>"};
                $.ajax({
                    url: '../bwp-includes/ajax.php?i=posts&process=set',
                    type: 'POST',
                    data: dataString,
                    success: function(e) {
                        var obj = jQuery.parseJSON(e);
                        var notify = $.notify({message: obj.message},{type: obj.type});
                        if(tip == "edit") {
                            if(obj.type == "success"){
                                setTimeout(function() {
                                    location.reload(true);
                                }, 1000);
                            }
                        }else if(tip == "add") {
                            if(obj.type == "success"){
                                setTimeout(function() {
                                    location.reload(true);
                                }, 1000);
                            }
                        }else if(tip == "delete") {
                            if(obj.type == "success"){
                                setTimeout(function() {
                                    window.location.href = "posts.php";
                                }, 1000);
                            }
                        }
                    }
                });
            });*/
            <?php if($process == "edit"){?>
            dropzonejs("../bwp-includes/ajax.php?i=imgedit","posts");
            <?php }else{?>
            dropzonejs("../bwp-includes/ajax.php?i=imgadd","posts");
            <?php }?>
        });
        $(document).on("click","button[name=set]", function(){
            var id = $(this).data("id");
            var type = $(this).data("type");
            pagesetTwo(id,type);
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