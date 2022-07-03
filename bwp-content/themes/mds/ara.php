<?php
$db->where("url",$_GET['type']);
$page = $db->getOne("page");

$db->where("langID",LANGID);
$faq = $db->getOne("faq");


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
<script>
    $(".about-tab .tab-content div:first-child").addClass("show").addClass("active")
    $(".about-tab .nav-tabs a:first-child").addClass("show").addClass("active");
</script>
</body>

</html>