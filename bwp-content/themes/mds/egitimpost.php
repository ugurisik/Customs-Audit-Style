<?php
$db->where("post_slug",$_GET['url']);
$post = $db->getOne("educations");

$db->where("template","egitimpost");
$caturl = $db->getOne("page");

$db->where("postID",$post['ID']);
$db->where("type","image");
$image = $db->getOne("education_meta");

$db->where("type","cat");
$db->orderBy("type_meta","ASC");
$db->groupBy("postID");
$cat = $db->get("education_meta",6);




// $db->where("type","cat");
// $db->where("postID",$post['ID']);
// $db->orderBy("type_meta","ASC");
// $postcat = $db->getOne("post_meta");

// $db->where("type","cat");
// $db->where("type_meta",$postcat['type_meta']);
// $db->orderBy("RAND ()");
// $cat = $db->get("post_meta", 10);



$bgen = $db->getSetMeta("blogdetail","genislik");
$byuk = $db->getSetMeta("blogdetail","yukseklik");

?>
<!DOCTYPE html>
<html lang="<?php echo mb_strtolower($_SESSION['dil']); ?>-<?php echo mb_strtoupper($_SESSION['dil']); ?>">
    <head>
    <base href="<?php echo $setting['siteurl']; ?>" />
    <meta charset="utf-8">
    <title>MDS | <?php echo $post['post_title']; ?></title>
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
                            <h2 class="name"><?php echo $post['post_title'];?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-blog-details-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-sm-12 col-12">
                        <div class="page-blog-details">
                            <div class="single-page-blog" >
                                <div class="bimg">
                                    <?php
                                        if($image['type_meta'] == ""){
                                            echo '<img alt="'.$post['post_title'].'" src="https://dummyimage.com/800x600/aaa/fff.png&amp;text='.$db->translate("resimyok").'" />';
                                        }else {
                                            echo '<img alt="'.$post['post_title'].'" src="'.BWPUP.'posts/'.$bgen.'x'.$byuk.'/'.$image['type_meta'].'" />';
                                        }
                                    ?>
                                </div>
                                <div class="content">
                                    <h4 class="title"><?php echo $post['post_title'];?></h4>
                                    <?php echo $post['post_content'];?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 offset-lg-0 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12">
                        <div class="sd-sidebar">
                            <div class="sdsw-feature sd-sidebar-widget">
                                <h4 class="title"><?php echo $db->translate("digeryazilar"); ?></h4>
                                <ul class="list">
                                    <?php
                                        foreach ($cat as $item) {
                                            $db->where("ID",$item['postID']);
                                            $postmenu = $db->getOne("educations");

                                            $bgen = $db->getSetMeta("kurucu","genislik");
                                            $byuk = $db->getSetMeta("kurucu","yukseklik");

                                            $db->where("postID",$postmenu['ID']);
                                            $db->where("type","image");
                                            $image = $db->getOne("education_meta");

                                            $db->where("id",$item['type_meta']);
                                            $cat = $db->getOne("educationcat");
                                    ?>
                                        <li>
                                            <a href="<?php echo $caturl['url']; ?>/<?php echo $postmenu['post_slug']; ?>/">
                                                <span class="img">
                                                    <?php
                                                        if($image['type_meta'] == ""){
                                                            echo '<img alt="'.$postmenu['post_title'].'" src="https://dummyimage.com/'.$bgen.'x'.$byuk.'/aaa/fff.png&amp;text='.$db->translate("resimyok").'" />';
                                                        }else {
                                                            echo '<img alt="'.$postmenu['post_title'].'" src="'.BWPUP.'posts/'.$bgen.'x'.$byuk.'/'.$image['type_meta'].'" />';
                                                        }
                                                    ?>
                                                </span>
                                                <span class="content">
                                                    <span class="name"><?php echo $postmenu['post_title']; ?></span>
                                                    <span class="type"><?php echo $cat['title'];?></span>
                                                </span>
                                            </a>
                                        </li>
                                    <?php
                                        }
                                    ?>
                                </ul>
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