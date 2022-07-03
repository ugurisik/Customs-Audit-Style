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
        $mlink = "menu";
        $pageTitle = "Menü Ayarları";
        $processTitle = "Menü Ayarları";
        if($process == "edit"){
            $db->where ("id", $_GET['id']);
            $menu = $db->getOne ("menu");
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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
</head>

<body
    class="kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-aside--enabled kt-aside--fixed kt-page--loading">
    <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
        <div class="kt-header-mobile__logo">
            <a href="index.html">
                <img alt="Logo" src="assets/media/logos/logo-12.png" />
            </a>
        </div>
        <div class="kt-header-mobile__toolbar">
            <button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left"
                id="kt_aside_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
            <button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                    class="flaticon-more"></i></button>
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
                                <div class="kt-portlet ">
                                    <div class="kt-portlet__body row">
                                        <div class="row">
                                            <div class="col-sm-3 my-auto">
                                                <span>Düzenlemek için bir menü seçin : </span>
                                            </div>
                                            <div class="col-sm-4 my-auto">
                                                <select class="form-control kt-selectpicker" name="menuID">
                                                    <option value="0">Menü seçin</option>
                                                    <?php
                                                    $m = $db->get('menu');
                                                    foreach ($m as $mp) {
                                                        if($mp['menu_position'] == 1){$position = 'Üst Menü (header)';}else if($mp['menu_position'] == 2){$position = 'Alt Menü (footer) ';}
                                                        if($_GET['id'] == $mp['id']){
                                                            echo '<option value="'.$mp['id'].'" selected>'.$mp['menu_title'].' '.$position.'</option>';
                                                        }else {
                                                            echo '<option value="'.$mp['id'].'">'.$mp['menu_title'].' '.$position.'</option>';
                                                        }
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4 my-auto">
                                                <span>veya <a href="menu.php">yeni menü oluştur</a>. Değişiklikleri
                                                    kaydetmeyi unutmayın!</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="kt-portlet ">
                                    <div class="kt-portlet__body">
                                        <div class="accordion" id="accordionExample1">
                                            <div class="card">
                                                <div class="card-header" id="sayfa">
                                                    <div class="card-title collapsed" data-toggle="collapse"
                                                        data-target="#sayfa1" aria-expanded="false"
                                                        aria-controls="sayfa1">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1"
                                                            class="kt-svg-icon">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path
                                                                    d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                                    fill="#000000" fill-rule="nonzero" />
                                                                <path
                                                                    d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"
                                                                    transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                            </g>
                                                        </svg> Sayfalar
                                                    </div>
                                                </div>
                                                <div id="sayfa1" class="collapse" aria-labelledby="sayfa"
                                                    data-parent="#accordionExample1">
                                                    <div class="card-body">
                                                        <form id="menuPagef">
                                                            <div class="form-group">
                                                                <input class="form-control col-sm-12" type="text"
                                                                    name="pagekey" placeholder="İçerik Ara">
                                                            </div>
                                                            <div class="kt-portlet__body kt-scroll" data-scroll="true"
                                                                style="height: 370px" id="menupage"></div>
                                                            <div
                                                                class="form-group float-left mt-4 mb-0 w-100 p2 pagination">
                                                                <ul class="nav-pages"></ul>
                                                            </div>
                                                            <div class="form-group float-right mt-4 mb-0">
                                                                <button type="button" name="menuPage"
                                                                    class="btn btn-outline-success">Menüye Ekle</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingTwo">
                                                    <div class="card-title collapsed" data-toggle="collapse"
                                                        data-target="#collapseTwo1" aria-expanded="false"
                                                        aria-controls="collapseTwo1">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1"
                                                            class="kt-svg-icon">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path
                                                                    d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                                    fill="#000000" fill-rule="nonzero" />
                                                                <path
                                                                    d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"
                                                                    transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                            </g>
                                                        </svg> Yazılar
                                                    </div>
                                                </div>
                                                <div id="collapseTwo1" class="collapse" aria-labelledby="headingTwo1"
                                                    data-parent="#accordionExample1">
                                                    <div class="card-body">
                                                        <form id="menuPostf">
                                                            <div class="form-group">
                                                                <input class="form-control col-sm-12" type="hidden"
                                                                    name="searchType" value="post">
                                                                <input class="form-control col-sm-12" type="text"
                                                                    name="postkey" placeholder="İçerik Ara">
                                                            </div>
                                                            <div class="kt-portlet__body kt-scroll" data-scroll="true"
                                                                style="height: 370px" id="menupost"></div>
                                                            <div
                                                                class="form-group float-left mt-4 mb-0 w-100 p2 pagination">
                                                                <ul class="nav-pages"></ul>
                                                            </div>
                                                            <div class="form-group float-right mt-4 mb-0">
                                                                <button type="button" name="menuPost"
                                                                    class="btn btn-outline-success">Menüye Ekle</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingOne">
                                                    <div class="card-title collapsed" data-toggle="collapse"
                                                        data-target="#collapseOne1" aria-expanded="false"
                                                        aria-controls="collapseOne1">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1"
                                                            class="kt-svg-icon">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path
                                                                    d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                                    fill="#000000" fill-rule="nonzero" />
                                                                <path
                                                                    d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"
                                                                    transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                            </g>
                                                        </svg> Özel Bağlantılar
                                                    </div>
                                                </div>
                                                <div id="collapseOne1" class="collapse" aria-labelledby="headingOne"
                                                    data-parent="#accordionExample1">
                                                    <div class="card-body">
                                                        <div class="kt-portlet__body kt-scroll" data-scroll="true"
                                                            style="height: 370px">
                                                            <form id="formUrl" class="form-horizontal">
                                                                <div class="form-group">
                                                                    <label for="text">Bağlantı Başlığı</label>
                                                                    <div class="input-group">
                                                                        <input type="text"
                                                                            class="form-control item-menu" name="text"
                                                                            id="text" placeholder="Text">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="href">Bağlantı Adresi</label>
                                                                    <input type="text" class="form-control item-menu"
                                                                        id="href" name="href" placeholder="URL">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="target">Bağlantı Tipi</label>
                                                                    <select name="target" id="target"
                                                                        class="form-control item-menu kt-selectpicker">
                                                                        <option value="_self">Self ( Aynı sekme
                                                                            içerisinde aç)</option>
                                                                        <option value="_blank">Blank (Yeni sekmede aç)
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group float-right mt-4 mb-0">
                                                                    <input type="hidden" name="type" value="url">
                                                                    <button type="button" name="menuLink"
                                                                        class="btn btn-outline-success">Menüye
                                                                        Ekle</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingThree1">
                                                    <div class="card-title collapsed" data-toggle="collapse"
                                                        data-target="#collapseThree1" aria-expanded="false"
                                                        aria-controls="collapseThree1">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1"
                                                            class="kt-svg-icon">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <path
                                                                    d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                                                    fill="#000000" fill-rule="nonzero" />
                                                                <path
                                                                    d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"
                                                                    transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                                            </g>
                                                        </svg> Kategoriler
                                                    </div>
                                                </div>
                                                <div id="collapseThree1" class="collapse"
                                                    aria-labelledby="headingThree1" data-parent="#accordionExample1">
                                                    <div class="card-body">
                                                        <form id="postCat">
                                                            <div class="kt-portlet__body kt-scroll" data-scroll="true"
                                                                style="height: 370px">
                                                                <?php
                                                                        $db->where("catID",0);
                                                                        $catList = $db->get("postcat");
                                                                        foreach ($catList as $cp) {
                                                                    ?>
                                                                <div class="form-group ml-0 mb-0">
                                                                    <label
                                                                        class="kt-checkbox  kt-checkbox--bold kt-checkbox--success">
                                                                        <input type="checkbox" name="cat"
                                                                            value="<?php echo $cp['id'];?>">
                                                                        <?php echo $cp['title'];?>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <?php
                                                                            $db->where("catID",$cp['id']);
                                                                            $catAltList = $db->get("postcat");
                                                                            foreach ($catAltList as $cap) {
                                                                        ?>
                                                                <div class="form-group ml-3 mb-0">
                                                                    <label
                                                                        class="kt-checkbox kt-checkbox--bold kt-checkbox--warning">
                                                                        <input type="checkbox" name="cat"
                                                                            value="<?php echo $cap['id'];?>">
                                                                        <?php echo $cap['title'];?>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <?php
                                                                                $db->where("catID",$cap['id']);
                                                                                $catAltList = $db->get("postcat");
                                                                                foreach ($catAltList as $cap) {
                                                                                    ?>
                                                                <div class="form-group ml-5 mb-0">
                                                                    <label
                                                                        class="kt-checkbox kt-checkbox--bold kt-checkbox--danger">
                                                                        <input type="checkbox" name="cat"
                                                                            value="<?php echo $cap['id'];?>">
                                                                        <?php echo $cap['title'];?>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <?php } ?>
                                                                <?php } ?>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="form-group float-right mt-4 mb-0">
                                                                <button type="button" name="menuCat" data-type="postkat"
                                                                    class="btn btn-outline-success">Menüye Ekle</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="kt-portlet kt-portlet--mobile">
                                    <div class="kt-portlet__head kt-portlet__head--lg">
                                        <div class="kt-portlet__head-label">
                                            <div class="form-group col-sm-5 mb-0">
                                                <div class="row">
                                                    <label class="col-sm-4 mb-0 my-auto">Menü İsmi</label>
                                                    <input class="form-control col-sm-8" type="text" name="title"
                                                        value="<?php if($process == "edit"){echo $menu['menu_title'];}?>">
                                                    <input type="hidden" class="form-control" name="id"
                                                        value="<?php echo $menu['id'];?>">
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-5 mb-0">
                                                <div class="row">
                                                    <label class="col-sm-4 mb-0 my-auto">İçerik Dili</label>
                                                    <select class="col-sm-8 form-control kt-selectpicker" name="langID">
                                                        <?php
                                                            $langs = $db->get('langs');
                                                            foreach ($langs as $lang) {
                                                                if($menu['menu_langID'] == $lang['id']){
                                                                    echo '<option value="'.$lang['id'].'" selected>'.$lang['title'].'</option>';
                                                                }else {
                                                                    echo '<option value="'.$lang['id'].'">'.$lang['title'].'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-sm-3 mb-0">
                                                <?php if($process == "edit"){?>
                                                <button type="button" class="btn btn-dark"
                                                    data-id="<?php echo $menu['id'];?>" data-type="edit" name="set"><i
                                                        class="fa fa-edit"></i> Düzenle</button>
                                                <?php }else{?>
                                                <button type="button" class="btn btn-success" data-type="add"
                                                    name="set"><i class="fa fa-plus"></i> Ekle</button>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                                        <ul id="myEditor" class="sortableLists list-group"></ul>
                                        <div class="kt-separator kt-separator--dashed"></div>
                                        <div class="form-group row mt-0 mb-0">
                                            <label class="col-sm-3 mb-0 my-auto">Tema Pozisyonları</label>
                                            <div class="col-sm-9 mb-0 my-auto">
                                                <?php
                                                    $db->where ("aktif", 1);
                                                    $menuPos = $db->getOne ("theme");
                                                    include '../bwp-content/themes/'.$menuPos['themeDir'].'/themeAdmin/information.php';
                                                    foreach ($menuPosition as $key => $value) {
                                                        if($menu['menu_position'] == $value){
                                                            echo '<label class="col-sm-12 kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                                    <input type="checkbox" name="position" value="'.$value.'" checked> '.$key.' <span></span>
                                                                </label>';
                                                        }else {
                                                            echo '<label class="col-sm-12 kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                                                    <input type="checkbox" name="position" value="'.$value.'"> '.$key.' <span></span>
                                                                </label>';
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="kt-portlet kt-portlet--mobile">
                                    <div class="kt-portlet__head kt-portlet__head--lg">
                                        <div class="kt-portlet__head-label">
                                            <span class="kt-portlet__head-icon">
                                                <i class="fa fa-edit"></i>
                                            </span>
                                            <h3 class="kt-portlet__head-title">Düzenle</h3>
                                        </div>
                                    </div>
                                    <div class="kt-portlet__body">
                                        <form id="frmEdit" class="form-horizontal">
                                            <div class="form-group">
                                                <label for="text">Bağlantı Başlığı</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control item-menu" name="text"
                                                        id="text" placeholder="Text">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="href">Bağlandı Adresi</label>
                                                <input type="text" class="form-control item-menu" id="href" name="href"
                                                    placeholder="URL">
                                            </div>
                                            <div class="form-group">
                                                <label for="target">Bağlantı Tipi</label>
                                                <select name="target" id="target"
                                                    class="form-control item-menu kt-selectpicker">
                                                    <option value="_self">Self ( Aynı sekme içerisinde aç)</option>
                                                    <option value="_blank">Blank (Yeni sekmede aç)</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i
                                                        class="fas fa-sync-alt"></i> Güncelle</button>
                                                <button type="button" id="btnAdd" class="btn btn-success"><i
                                                        class="fas fa-plus"></i> Ekle</button>
                                            </div>
                                        </form>
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
    <div id="kt_scrolltop" class="kt-scrolltop"><i class="fa fa-arrow-up"></i></div>
    <script src="assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
    <script src="assets/js/scripts.bundle.js" type="text/javascript"></script>
    <script src="assets/js/main.js" type="text/javascript"></script>
    <script src="assets/plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
    <script src="assets/plugins/custom/menu-editor/jquery-menu-editor.min.js" type="text/javascript"></script>
    <script src="assets/plugins/custom/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js" type="text/javascript">
    </script>
    <script>
    function yetkikont(process) {
        $.ajax({
            url: "../bwp-includes/ajax.php?i=yetki&process=" + process + "",
            success: function(e) {
                var obj = jQuery.parseJSON(e);
                if (obj.type == "success") {
                    menuSet("<?php echo $pageNum;?>", "page", null);
                    menuSet("<?php echo $pageNum;?>", "post", null);
                } else {
                    var notify = $.notify({
                        title: obj.title,
                        message: obj.message
                    }, {
                        type: obj.type
                    });
                }
            }
        });
    }

    function menuSet(sayfa, tip, key) {
        var dataString = {
            sayfa: sayfa,
            tip: tip,
            key: key
        };
        $.ajax({
            url: '../bwp-includes/ajax.php?i=menu&process=set',
            type: 'POST',
            data: dataString,
            success: function(e) {
                var obj = jQuery.parseJSON(e);
                if (tip == "page") {
                    $.each(obj.data, function(key, value) {
                        $("#menupage").append(
                            '<div class="form-group ml-0 mb-0"><label class="kt-checkbox kt-checkbox--bold kt-checkbox--success"><input type="checkbox" name="mPage" value="' +
                            value[0] + '">' + value[1] + '<span></span></label></div>');
                    });
                } else if (tip == "post") {
                    $.each(obj.data, function(key, value) {
                        $("#menupost").append(
                            '<div class="form-group ml-0 mb-0"><label class="kt-checkbox kt-checkbox--bold kt-checkbox--success"><input type="checkbox" name="mPost" value="' +
                            value[0] + '">' + value[1] + '<span></span></label></div>');
                    });
                } else if (tip == "img") {
                    $.each(obj.data, function(key, value) {
                        $("#menugaleri").append(
                            '<div class="form-group ml-0 mb-0"><label class="kt-checkbox kt-checkbox--bold kt-checkbox--success"><input type="checkbox" name="mPost" value="' +
                            value[0] + '">' + value[1] + '<span></span></label></div>');
                    });
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    }
    $('#menuPagef input[name=pagekey]').on('keyup change', function() {
        $("#menupage").html("");
        menuSet("1", "page", $(this).val());
    });
    $('#menuPostf input[name=postkey]').on('keyup change', function() {
        $("#menupost").html("");
        menuSet("1", "post", $(this).val());
    });
    jQuery(document).ready(function() {
        $("select").select2();
        yetkikont("menuadd");
        $('input[name=position]').on('change', function() {
            $('input[name=position]').not(this).prop('checked', false);
        });
        $('select[name=menuID]').on('change', function() {
            if (this.value != "0") {
                window.location.href = '?process=edit&id=' + this.value;
            } else {
                window.location.href = 'menu.php';
            }
        });
        $("button[name=menuPost]").click(function() {
            var pages = [];
            $('input[name=mPost]').each(function() {
                if ($(this).is(":checked")) {
                    pages.push($(this).val());
                }
            });
            var dataString = {
                page: pages.toString(),
                tip: "mPost"
            };
            $.ajax({
                url: '../bwp-includes/ajax.php?i=menu&process=set',
                type: 'POST',
                data: dataString,
                success: function(e) {
                    editor.ekle(e);
                    $("#menuPostf")[0].reset();
                }
            });
        });
        $("button[name=menuPage]").click(function() {
            var pages = [];
            $('input[name=mPage]').each(function() {
                if ($(this).is(":checked")) {
                    pages.push($(this).val());
                }
            });
            var dataString = {
                page: pages.toString(),
                tip: "mPage"
            };
            $.ajax({
                url: '../bwp-includes/ajax.php?i=menu&process=set',
                type: 'POST',
                data: dataString,
                success: function(e) {
                    editor.ekle(e);
                    $("#menuPagef")[0].reset();
                }
            });
        });
        $("button[name=menuLink]").click(function() {
            var text = $('input[name=text]').val();
            var href = $('input[name=href]').val();
            var target = $('select[name=target]').val();
            var dataString = {
                text: text,
                href: href,
                target: target,
                tip: "url"
            };
            $.ajax({
                url: '../bwp-includes/ajax.php?i=menu&process=set',
                type: 'POST',
                data: dataString,
                success: function(e) {
                    editor.ekle(e);
                    $("#formUrl")[0].reset();
                }
            });
        });
        $("button[name=menuCat]").click(function() {
            var cat = [];
            $('input[name=cat]').each(function() {
                if ($(this).is(":checked")) {
                    cat.push($(this).val());
                }
            });
            var dataString = {
                cat: cat.toString(),
                tip: $(this).data("type")
            };
            $.ajax({
                url: '../bwp-includes/ajax.php?i=menu&process=set',
                type: 'POST',
                data: dataString,
                success: function(e) {
                    editor.ekle(e);
                    $("#postCat")[0].reset();
                }
            });
        });

        $("button[name=menuGal]").click(function() {
            var pages = [];
            $('input[name=mGal]').each(function() {
                if ($(this).is(":checked")) {
                    pages.push($(this).val());
                }
            });
            var dataString = {
                galeri: pages.toString(),
                tip: "mGaleri"
            };

            $.ajax({
                url: '../bwp-includes/ajax.php?i=menu&process=set',
                type: 'POST',
                data: dataString,
                success: function(e) {
                    console.log(e);
                    editor.ekle(e);
                    $("#menuGalerif")[0].reset();
                }
            });
        });
        $("button[name=set]").on("click", function() {
            var tip = $(this).data("type");
            var id = $('input[name=id]').val();
            var title = $('input[name=title]').val();
            var position = [];
            $('input[name=position]').each(function() {
                if ($(this).is(":checked")) {
                    position.push($(this).val());
                }
            });
            var langID = $('select[name=langID]').val();
            var json = editor.getString();
            var userID = "<?php echo $userKont['id'];?>";
            var dataString = {
                title: title,
                position: position.toString(),
                langID: langID,
                json: json,
                author: userID,
                id: id,
                tip: tip
            };
            $.ajax({
                url: '../bwp-includes/ajax.php?i=menu&process=set',
                type: 'POST',
                data: dataString,
                success: function(e) {
                    var obj = jQuery.parseJSON(e);
                    var notify = $.notify({
                        message: obj.message
                    }, {
                        type: obj.type
                    });
                    if (tip == "edit") {
                        if (obj.type == "success") {
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    } else if (tip == "add") {
                        if (obj.type == "success") {
                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        });

        var iconPickerOptions = {
            searchText: "",
            labelHeader: "{0}/{1}"
        };
        var sortableListOptions = {
            placeholderCss: {
                'background-color': "#cccccc"
            }
        };
        var editor = new MenuEditor('myEditor', {
            listOptions: sortableListOptions,
            iconPicker: iconPickerOptions
        });
        editor.setForm($('#frmEdit'));
        editor.setUpdateButton($('#btnUpdate'));

        $('button[name=json]').on('click', function() {
            var str = editor.getString();
            $("#out").text(str);
        });
        $("#btnUpdate").click(function() {
            editor.update();
        });
        $('#btnAdd').click(function() {
            editor.add();
        });
        <?php if($process == "edit"){ ?>
        var arrayjson = <?php echo $menu['menu_json'];?>;
        editor.setData(arrayjson);
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