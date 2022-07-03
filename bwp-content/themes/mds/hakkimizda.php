<?php header('Access-Control-Allow-Origin: *'); ?><?php
$db->where("url", $_GET['type']);
$page = $db->getOne("page");

$db->where("langID", LANGID);
$about = $db->getOne("aboutus");

$bgen = $db->getSetMeta("kurucu", "genislik");
$byuk = $db->getSetMeta("kurucu", "yukseklik");
?>
<!DOCTYPE html>
<html lang="<?php echo mb_strtolower($_SESSION['dil']); ?>-<?php echo mb_strtoupper($_SESSION['dil']); ?>">

<head>
    <base href="<?php echo $setting['siteurl']; ?>" />
    <meta charset="utf-8">
    <title>MDS | <?php echo $page['title']; ?></title>
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
                        <h2 class="name"><?php echo $page['title']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="shortcode-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 col-12">
                    <div class="section-title">
                        <h6></h6>
                        <h2><?php echo $about['title']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sc-dividers">
        <div class="container">
            <div class="row">
                <div class="col-md-12"><?php echo $about['detail']; ?></div>
            </div>
        </div>
    </div>
    <div class="team-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 offset-lg-0 col-md-8 offset-md-2 col-sm-12 col-12">
                    <div class="section-title">
                        <h2><?php echo $about['title3']; ?></h2>
                        <?php echo $about['detail3']; ?>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-0 col-md-8 offset-md-2 col-sm-12 col-12">
                    <div class="all-team">
                        <div class="single-team">
                            <div class="img">
                                <?php
                                if ($about['resim'] == "") {
                                    echo '<img alt="' . $about['title'] . '" src="https://dummyimage.com/800x600/aaa/fff.png&amp;text=' . $db->translate("resimyok") . '" />';
                                } else {
                                    echo '<img alt="' . $about['title'] . '" src="' . BWPUP . 'kurucu/' . $bgen . 'x' . $byuk . '/' . $about['resim'] . '" />';
                                }
                                ?>
                                <div class="content">
                                    <span class="default"><i class="flaticon-network"></i></span>
                                    <ul class="social">
                                        <li><a href="<?php echo $about['facebook']; ?>" target="_blank"><i
                                                    class="fab fa-linkedin"></i></a></li>
                                        <li><a href="<?php echo $about['twitter']; ?>" target="_blank"><i
                                                    class="fab fa-instagram"></i></a></li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $db->where("langID", LANGID);
    $aboutmembers = $db->get("aboutus_members");
    if (count($aboutmembers) > 0) {
    ?>
    <div class="team-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 offset-lg-0 col-md-8 offset-md-2 col-sm-12 col-12">
                    <div class="section-title">
                        <h2><?php echo $db->translate("ekipbaslik"); ?></h2>
                        <?php // echo substr($about['detail3'],0,1200); 
                            ?>
                    </div>
                </div>
            </div>
            <div class="row">

                <?php
                    $db->where("langID", LANGID);
                    $aboutmembers = $db->get("aboutus_members");
                    foreach ($aboutmembers as $aboutmember) :
                    ?>
                <div class="col-lg-3">
                    <div class="single-team mt-5">
                        <div class="img">
                            <?php
                                    if ($aboutmember['member_image'] == "" or !file_exists(BWPUP . 'ekip/' . $bgen . 'x' . $byuk . '/' . $aboutmember['member_image'])) {
                                        echo '<img src="https://dummyimage.com/800x600/aaa/fff.png&amp;text=' . $db->translate("resimyok") . '" />';
                                    } else {
                                        echo '<img  src="' . BWPUP . 'ekip/' . $bgen . 'x' . $byuk . '/' . $aboutmember['member_image'] . '" />';
                                    }
                                    ?>
                            <div class="content">
                                <span class="default"><i class="flaticon-network"></i></span>
                                <ul class="social">
                                    <li><a href="<?php echo $aboutmember['facebook']; ?>" target="_blank"><i
                                                class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="<?php echo $aboutmember['twitter']; ?>" target="_blank"><i
                                                class="fab fa-twitter"></i></a></li>
                                    <li><a href="<?php echo $aboutmember['youtube']; ?>" target="_blank"><i
                                                class="fab fa-youtube"></i></a></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="text-center">
                        <b><?php echo $aboutmember['member_name']; ?></b> <br><br>
                    </div>
                    <?php echo $aboutmember['member_detail']; ?>
                </div>
                <?php endforeach; ?>
                <hr>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="about-tab-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="about-tab">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <?php
                                $db->where("aboutID", $about['id']);
                                $tab = $db->get("aboutus_tab");
                                foreach ($tab as $item) {
                                    echo '<a class="nav-item nav-link" id="' . $item['url'] . '-tab" data-toggle="tab" href="#' . $item['url'] . '" role="tab" aria-controls="' . $item['url'] . '" aria-selected="false">' . $item['title'] . '</a>';
                                }
                                ?>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <?php
                            $db->where("aboutID", $about['id']);
                            $tab = $db->get("aboutus_tab");
                            foreach ($tab as $item) {
                                echo '<div class="tab-pane fade" id="' . $item['url'] . '" role="tabpanel" aria-labelledby="' . $item['url'] . '-tab"><div class="about-tab-box">' . $item['detail'] . '</div></div>';
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="shortcode-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 col-12">
                    <div class="section-title">
                        <h6></h6>
                        <h2><?php echo $about['title2']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sc-dividers">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php echo $about['detail2']; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.php'; ?>
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
    <script>
    $(".about-tab .tab-content div:first-child").addClass("show").addClass("active")
    $(".about-tab .nav-tabs a:first-child").addClass("show").addClass("active");
    </script>
</body>

</html>