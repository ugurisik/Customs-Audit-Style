<?php
$db->where("post_slug", $_GET['url']);
$post = $db->getOne("posts");

$db->where("postID", $post['ID']);
$db->where("type", "image");
$image = $db->getOne("post_meta");

// $db->where("type", "cat");
// $db->groupBy("postID");
// $db->orderBy("RAND ()");
// $cat = $db->get("post_meta", 6);

$db->where("type","cat");
$db->where("postID",$post['ID']);
$postcat = $db->get("post_meta");

$postid = 0;
$arr = array();
foreach ($postcat as $key) {
    $arr[] = intval($key['type_meta']);
}

$asd = array_reverse($arr);


$db->where("type","cat");
$db->where("type_meta",$asd[0]);
$db->orderBy("RAND ()");
$cat = $db->get("post_meta", 10);

$bgen = $db->getSetMeta("blogdetail", "genislik");
$byuk = $db->getSetMeta("blogdetail", "yukseklik");




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
                        <h2 class="name"><?php echo $post['post_title']; ?></h2>
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
                        <div class="single-page-blog">
                            <div class="bimg">
                                <?php
                                if ($image['type_meta'] == "") {
                                    echo '<img alt="' . $post['post_title'] . '" src="https://dummyimage.com/800x600/aaa/fff.png&amp;text=' . $db->translate("resimyok") . '" />';
                                } else {
                                    echo '<img alt="' . $post['post_title'] . '" src="' . BWPUP . 'posts/' . $bgen . 'x' . $byuk . '/' . $image['type_meta'] . '" />';
                                }
                                ?>
                            </div>
                            <div class="content">
                                <h4 class="title"><?php echo $post['post_title']; ?></h4>
                                <?php echo $post['post_content']; ?>
                                <?php
                                $db->where("postID", $post['ID']);
                                $db->where("type", "pdf");
                                $pdfcontrol = $db->getOne("post_meta");
                                if (count($pdfcontrol) > 0) {
                                    echo "<br><hr>";
                                    echo "<a href='" . BWPUP . 'posts/pdf/' . $pdfcontrol['type_meta'] . "'><h5>" . $db->translate("pdfvar") . "</h5></a>";
                                    echo '<embed src= "' . BWPUP . 'posts/pdf/' . $pdfcontrol['type_meta'] . '/#toolbar=0&navpanes=0&scrollbar=0" width="100%" height="500px">';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="bd-comments">
                            <?php
                            if ($post['post_comment'] == "2") {
                                $db->where("postID", $post['ID']);
                                $db->where("status", "2");
                                $db->where("message", NULL, "IS NOT");
                                $db->where("message != ''");
                                $getcomment = $db->get("comments");

                            ?>
                                <h4 class="title"><?php if (count($getcomment) > 0) {
                                                        echo count($getcomment); ?> <?php echo $db->translate("yorumvar");
                                                                                } else {
                                                                                    echo $db->translate("yorumyok");
                                                                                } ?></h4>
                                <ul>
                                    <?php
                                    foreach ($getcomment as $k) { ?>
                                        <li>
                                            <article>
                                                <div class="bd-comment-box">

                                                    <div class="content">
                                                        <h2 class="name"><?php echo $k['name']; ?></h2>
                                                        <span class="ago"><?php echo $k['date']; ?></span>
                                                        <p class="text"><?php echo $k['message']; ?></p>
                                                    </div>
                                                </div>
                                            </article>
                                        </li>

                                <?php }
                                } ?>
                                </ul>
                        </div>
                        <div class="leave-message">

                            <?php
                            if ($post['post_comment'] == "2") { ?>
                                <h2 class="title"><?php echo $db->translate("yorumgonder"); ?></h2>
                                <form method="post" id="comment">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="leave-msg-input-box">
                                                <input type="text" name="name" required placeholder="<?php echo $db->translate("isim"); ?> <?php echo $db->translate("soyisim"); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="leave-msg-input-box">
                                                <input type="email" name="email" required placeholder="<?php echo $db->translate("email"); ?>">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="leave-msg-input-box">
                                                <textarea name="message" required placeholder="<?php echo $db->translate("mesajınız"); ?>"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="leave-msg-input-box">
                                                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                                <input type="button" name="submit" value="<?php echo $db->translate("gonder"); ?> ">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <?php } else { ?>
                                <h2 class="title"><?php //echo $db->translate("yorumkapali"); 
                                                    ?></h2>
                            <?php } ?>
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
                                    $db->where("ID", $item['postID']);
                                    $postmenu = $db->getOne("posts");

                                    $bgen = $db->getSetMeta("kurucu", "genislik");
                                    $byuk = $db->getSetMeta("kurucu", "yukseklik");

                                    $db->where("postID", $postmenu['ID']);
                                    $db->where("type", "image");
                                    $image = $db->getOne("post_meta");

                                    $db->where("id", $item['type_meta']);
                                    $cat = $db->getOne("postcat");
                                ?>
                                    <li>
                                        <a href="<?php echo $db->translate("yazi"); ?>/<?php echo $postmenu['post_slug']; ?>/">
                                            <span class="img">
                                                <?php
                                                if ($image['type_meta'] == "") {
                                                    echo '<img alt="' . $postmenu['post_title'] . '" src="https://dummyimage.com/' . $bgen . 'x' . $byuk . '/aaa/fff.png&amp;text=' . $db->translate("resimyok") . '" />';
                                                } else {
                                                    echo '<img alt="' . $postmenu['post_title'] . '" src="' . BWPUP . 'posts/' . $bgen . 'x' . $byuk . '/' . $image['type_meta'] . '" />';
                                                }
                                                ?>
                                            </span>
                                            <span class="content">
                                                <span class="name"><?php echo $postmenu['post_title']; ?></span>
                                                <span class="type"><?php echo $cat['title']; ?></span>
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
    <script src="https://www.google.com/recaptcha/api.js?render=6LfCSQMeAAAAACFM1T4MB8QDRwj2wG_xkqoQzYiy"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
<script>
    $('body').on('click', 'input[name=submit]', function() {
        grecaptcha.ready(() => {
            grecaptcha.execute('6LfCSQMeAAAAACFM1T4MB8QDRwj2wG_xkqoQzYiy', {
                action: 'submit'
            }).then(token => {
                document.querySelector('#g-recaptcha-response').value = token;
                var data = $("#comment").serializeArray();
                data.push({
                    "name": "resToken",
                    "value": token
                }, {
                    "name": "postID",
                    "value": <?php echo $post['ID'] ?>
                });
                $.ajax({
                    url: '<?php echo THEMEDIR; ?>inc/ajax.php?type=sendComment',
                    type: 'POST',
                    data: data,
                    success: function(e) {
                        var obj = jQuery.parseJSON(e);
                        swal.fire("" + obj.title + "", "" + obj.message + "", "" + obj.type + "");
                        setTimeout(function() {
                            //location.reload(true);
                        }, 1500);
                    }
                });
            });
        });
    });
</script>

</html>