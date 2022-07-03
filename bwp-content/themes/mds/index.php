<?php
session_start();

$db->where("langID", LANGID);
$db->where("category", "Anasayfa");
$homePage = $db->getOne("homePage");

$gen = $db->getSetMeta("home-slide", "genislik");
$yuk = $db->getSetMeta("home-slide", "yukseklik");

$bgen = $db->getSetMeta("blog", "genislik");
$byuk = $db->getSetMeta("blog", "yukseklik");

$bsgen = $db->getSetMeta("homepage2", "genislik");
$bsyuk = $db->getSetMeta("homepage2", "yukseklik");

$db->where("id", $homePage['blogcat1']);
$cat1 = $db->getOne("servicecat");

$db->where("id", $homePage['blogcat2']);
$cat2 = $db->getOne("postcat");

?>
<!DOCTYPE html>
<html lang="<?php echo mb_strtolower($_SESSION['dil']); ?>-<?php echo mb_strtoupper($_SESSION['dil']); ?>">

<head>
    <base href="<?php echo $setting['siteurl']; ?>" />
    <meta charset="utf-8">
    <title><?php echo $setting['baslik']; ?></title>
    <meta name="title" content="<?php echo $setting['baslik']; ?>" />
    <!-- <meta name="description" content="<?php echo $setting['aciklama']; ?>" /> -->
    <meta name="keywords" content="<?php echo $setting['keywords']; ?>" />
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
    <link rel="stylesheet" href="<?php echo THEMEJS ?>/slick/slick.css">
    <link rel="shortcut icon" type="image/png" href="<?php echo THEMEIMG ?>li.ico">
    <script src="<?php echo THEMEJS ?>jquery-3.2.0.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        /* .custom-cursor {
            width: 20px;
            height: 20px;
            border: 2px solid #3c95d1;
            position: absolute;
            z-index: 555;
            border-radius: 50%;
            box-shadow: 0 0 0 2px #00324c;
            pointer-events: none;
            display: none;
        }

        .custom-cursor.hover {
            width: 40px;
            height: 40px;
            cursor: pointer;
            background: rgba(#FFCC00, .5);
        } */
    </style>

    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
</head>

<body>
    <?php include 'inc/header.php'; ?>
    <!-- <div class="custom-cursor"></div> -->
    <!--  <div class="slider-area">

      <div class="pogoSlider " id="js-main-slider">

            <?php
            $db->where("langID", LANGID);
            $db->where("category", "slider");
            $slider = $db->get("homePage");
            foreach ($slider as $slide) {
            ?>
                <div class="pogoSlider-slide " data-transition="expandReveal" data-duration="1500" style="background-image:url('bwp-content/uploads/slide/<?php echo $gen; ?>x<?php echo $yuk . '/' . $slide['resim']; ?>');">
                    <div class="pogoSlider-progressBar">
                        <div class="pogoSlider-progressBar-duration"></div>
                    </div>
                    <div class="container">
                        <div class="pss-box center">
                            <?php echo $slide['slidetext']; ?>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>

        <div class="to-down">
            <a class="smoothscroll" href="#hizmetlerimiz"><img src="<?php echo THEMEIMG ?>home1/to-down.png" alt=""></a>
        </div> 

        
    </div>-->
    <div class="swiper-container slider" style="position:relative; display:flex; z-index:1">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
            <!-- Slides -->

            <?php
            $db->where("langID", LANGID);
            $db->where("category", "slider");
            $slider = $db->get("homePage");
            foreach ($slider as $slide) {
            ?>
                <div class="swiper-slide">
                    <div class="parallax-bg" style="background-image:url(bwp-content/uploads/slide/<?php echo $gen; ?>x<?php echo $yuk . '/' . $slide['resim']; ?>)" data-swiper-parallax="-20%" data-swiper-parallax-duration="1500" background-attachment: fixed;></div>
                    <div class="container">
                        <div class="pss-box center">
                            <?php echo $slide['slidetext']; ?>
                        </div>
                    </div>
                </div>

            <?php 
            } ?>
        </div>

        <!-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> -->

        <div class="swiper-pagination"></div>
    </div>


    <div class="top-agency-area" id="hizmetlerimiz">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 col-12">
                    <div class="section-title">
                        <h2><?php echo $db->translate("ourservices") ?></h2>
                        <p style=" font-family: 'Poppins', sans-serif !important;">
                            <?php echo $homePage['blogcat1subtitle']; ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="top-agencey-content">
                        <div class="row">

                            <?php
                            $count = 1;
                            $db->where("catID", $cat1['id']);
                            $postm = $db->get("servicecat", 6);
                            foreach ($postm as $item) {
                                if (count($postm) % 2 != 0) {
                                    if (count($postm) == $count) {
                                        $col = "col-lg-12 col-md-12";
                                    } else {
                                        $col = "col-lg-6 col-md-6";
                                    }
                                } else {
                                    $col = "col-lg-6 col-md-6";
                                }
                                echo '<div class="' . $col . ' col-sm-6 col-12 mt-4"><a href="hizmetler-kategori/' . $item['url'] . '/"> <div class="single-top-agency brief-case-box"><h6 class="name">' . $item['title'] . '</h6></div></a></div>';
                                $count++;
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $db->where("langID", LANGID);
    $postm = $db->getOne("homePage_blogcat_sort");


    if (count($postm) > 0) { ?>
        <div class="blog-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 col-12">
                        <div class="section-title">
                            <h2><?php echo $db->translate("onemliyazilar") ?></h2>
                            <p><?php echo $homePage['blogcat2subtitle']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="blog-carousel owl-carousel">
                    <?php
                    foreach (json_decode($postm['Sorted']) as $item => $val) {
                        $db->where("ID", $val);
                        $post = $db->getOne("posts");

                        $db->where("postID", $val);
                        $db->where("type", "image");
                        $image = $db->getOne("post_meta");
                    ?>
                        <div class="single-blog" style="max-height:400px;">
                            <div class="bimg">
                                <a href="<?php echo $db->translate("yazi"); ?>/<?php echo $post['post_slug']; ?>/">
                                    <?php
                                    if ($image['type_meta'] == "") {
                                        echo '<img alt="Carspot" src="https://dummyimage.com/800x600/aaa/fff.png&amp;text=' . $db->translate("resimyok") . '"/>';
                                    } else {
                                        echo '<img alt="Carspot" src="' . BWPUP . 'posts/' . $bsgen . 'x' . $bsyuk . '/' . $image['type_meta'] . '"/>';
                                    }
                                    ?>
                                    <span class="icon"><i class="fas fa-link"></i></span>
                                </a>

                            </div>
                            <div class="content">
                                <h4 class="title"><a href="<?php echo $db->translate("yazi"); ?>/<?php echo $post['post_slug']; ?>/"><?php echo $yazi->kisalt($post['post_title'], 50) ?></a>
                                </h4>
                                <p class="text">
                                    <?php echo strip_tags($yazi->kisalt($post['post_content'], 100), HTML_SPECIALCHARS); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    <?php }
    include 'inc/footer.php'; ?>

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
    <script src="<?php echo THEMEJS ?>/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {


            var swiper = new Swiper('.slider', {
                loop: true,
                parallax: false,
                speed: 1000,
                // direction: "vertical",
                //allowTouchMove:false,
                effect: 'fade',
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".slider .swiper-button-next",
                    prevEl: ".slider .swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            });



            // var mySlider = $('#js-main-slider').pogoSlider({
            //     autoplay: false,
            //     autoplayTimeout: 1000,
            //     targetWidth: 1920,
            //     targetHeight: 400,
            //     preserveTargetSize: true,
            //     responsive: true
            // }).data('plugin_pogoSlider');
            // $("#js-main-slider").on("click", () => {
            //     mySlider.nextSlide();
            // });


            // setInterval(() => {
            //     mySlider.nextSlide();
            // }, 5000);
            // let pogoslider_nav = document.getElementsByClassName("pogoSlider-nav")[0];
            // pogoslider_nav.style.marginBottom = "8%"
            // let to_down = document.getElementsByClassName("to-down")[0];
            // to_down.style.marginBottom = "6%"
        });
    </script>
</body>

</html>