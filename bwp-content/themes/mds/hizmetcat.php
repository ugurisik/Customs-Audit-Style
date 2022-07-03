<?php
    $db->where("url",$_GET['url']);
    $cat = $db->getOne("servicecat");

$db->where("langID",LANGID);
$db->where("template","hizmetcat");
$hizmetcat = $db->getOne("page");

    $db->where("template","hizmetpost");
    $caturl = $db->getOne("page");

    $db->where("type", "cat");
    $db->where("type_meta", $cat['id']);
    $postm = $db->get("service_meta");
    if($cat['catID'] == "0"){
        $breadcumb = "<li><a href='".$hizmetcat['url']."/".$cat['url']."/'>".$cat['title']."</a></li>";
    }else {
        $db->where("catID",$cat['id']);
        $catKnt = $db->getOne("servicecat");

        $db->where("id",$cat['catID']);
        $mainKat = $db->getOne("servicecat");

        $breadcumb = "<li><a href='".$hizmetcat['url']."/".$mainKat['url']."/'>".$mainKat['title']."</a></li><li><a href='".$hizmetcat['url']."/".$cat['url']."/'>".$cat['title']."</a></li>";
    }
    $bgen = $db->getSetMeta("blogcat", "genislik");
    $byuk = $db->getSetMeta("blogcat", "yukseklik");


?>
<!DOCTYPE html>
<html lang="<?php echo mb_strtolower($_SESSION['dil']); ?>-<?php echo mb_strtoupper($_SESSION['dil']); ?>">
<head>
    <base href="<?php echo $setting['siteurl']; ?>" />
    <meta charset="utf-8">
    <title>MDS | <?php echo $cat['title']; ?></title>
    <meta name="title" content="<?php echo $setting['baslik']; ?>">
    <meta name="description" content="<?php echo $setting['aciklama']; ?>">
    <meta name="keywords" content="<?php echo $setting['keywords']; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>jquery-ui.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>flaticon.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>pogo-slider.min.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>jquery.fancybox.min.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>magnific-popup.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>animate.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>meanmenu.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>style.css">
    <link rel="stylesheet" href="<?php echo THEMECSS ?>responsive.css">
    <link rel="shortcut icon" type="image/png" href="<?php echo THEMEIMG ?>favicon.ico">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <?php include "inc/header.php"; ?>
    <div class="breadcumb-area bg-with-black">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcumb">
                        <h2 class="name"><?php echo $cat['title'];?></h2>
                        <ul class="links">
                            <?php echo $breadcumb;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-blog-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-12">
                    <div class="page-blog-two-column page-blog">
                        <div class="row">
                            <?php
                                foreach ($postm as $item) {
                                $db->where("ID", $item['postID']);
                                $post = $db->getOne("services");
                                $db->where("postID", $post['ID']);
                                $db->where("type", "image");
                                $image = $db->getOne("service_meta");
                                ?>
                                <div class="col-lg-4 offset-lg-0 col-md-6 offset-md-0 col-sm-8 offset-sm-2 col-12">
                                    <div class="single-page-blog">
                                        <div class="bimg">
                                            <a href="<?php echo $caturl['url']; ?>/<?php echo $post['post_slug']; ?>/">
                                                <?php
                                                    if ($image['type_meta'] == "") {
                                                        echo '<img alt="Carspot" src="https://dummyimage.com/800x600/aaa/fff.png&amp;text=' . $db->translate("resimyok") . '"/>';
                                                    } else {
                                                        echo '<img alt="Carspot" src="' . BWPUP . 'posts/' . $bgen . 'x' . $byuk . '/' . $image['type_meta'] . '"/>';
                                                    }
                                                ?>
                                                <span class="icon"><i class="fas fa-link"></i></span>
                                            </a>
                                        </div>
                                        <div class="content">
                                        <h4 class="title"><a href="<?php echo $caturl['url']; ?>/<?php echo $post['post_slug']; ?>/"><?php echo $yazi->kisalt($post['post_title'],70); ?></a></h4>
                                            <p class="text"><?php echo strip_tags($yazi->kisalt($post['post_content'],170),HTML_SPECIALCHARS); ?></p>
                                            <a class="more" href="<?php echo $caturl['url']; ?>/<?php echo $post['post_slug']; ?>/"><?php echo $db->translate("devaminioku") ;?> <span><i class="fas fa-angle-right"></i></span></a>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.php';?>
    <script src="<?php echo THEMEJS ?>jquery-3.2.0.min.js"></script>
    <script src="<?php echo THEMEJS ?>jquery-ui.js"></script>
    <script src="<?php echo THEMEJS ?>owl.carousel.min.js"></script>
    <script src="<?php echo THEMEJS ?>jquery.pogo-slider.min.js"></script>
    <script src="<?php echo THEMEJS ?>jquery.counterup.min.js"></script>
    <script src="<?php echo THEMEJS ?>parallax.js"></script>
    <script src="<?php echo THEMEJS ?>countdown.js"></script>
    <script src="<?php echo THEMEJS ?>jquery.fancybox.min.js"></script>
    <script src="<?php echo THEMEJS ?>imagesLoaded-PACKAGED.js"></script>
    <script src="<?php echo THEMEJS ?>isotope-packaged.js"></script>
    <script src="<?php echo THEMEJS ?>jquery.meanmenu.js"></script>
    <script src="<?php echo THEMEJS ?>jquery.scrollUp.js"></script>
    <script src="<?php echo THEMEJS ?>jquery.magnific-popup.min.js"></script>
    <script src="<?php echo THEMEJS ?>jquery.mixitup.min.js"></script>
    <script src="<?php echo THEMEJS ?>jquery.waypoints.min.js"></script>
    <script src="<?php echo THEMEJS ?>popper.min.js"></script>
    <script src="<?php echo THEMEJS ?>bootstrap.min.js"></script>
    <script src="<?php echo THEMEJS ?>theme.js"></script>
</body>
</html>