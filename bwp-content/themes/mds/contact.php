<?php
session_start();

$db->where("url", $_GET['type']);
$page = $db->getOne("page");

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
    <link rel="shortcut icon" type="image/png" href="<?php echo THEMEIMG ?>li.ico">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <?php include 'inc/header.php'; ?>
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
    <div class="contact-send-msg-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-sm-6 col-12">
                    <div class="question-form-area pt-0">
                        <div class="cf-msg"></div>
                        <form method="post" id="contact">
                            <div class="row">

                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("isim"); ?>" name="name" required="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("soyisim"); ?>" name="surname" required="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("unvan"); ?>" id="funvan" name="unvan" required="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("firmaniz"); ?>" name="firmaadi" required="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("telefon"); ?>" name="phone" required="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("email"); ?>" name="email" required="">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="cf-box">
                                        <textarea class="contact-textarea" placeholder="<?php echo $db->translate("mesajınız"); ?>" id="msg" name="message"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="cf-box">
                                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                        <button type="button" class="cont-submit btn-contact" name="set"><?php echo $db->translate("gonder"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12 " style="text-align: center;">

                    <div class="single-contact-details">
                        <div class="row text-center">
                            <div class="col-lg-11 ">
                                <div class="icon">
                                    <span class="flaticon-placeholder"></span>
                                </div>
                                <p class="desc"><?php echo $setting['adres']; ?></p>
                                <a class="desc" href="mailto:<?php echo $setting['eposta']; ?>"><?php echo $setting['eposta']; ?></a>
                                <a class="desc" href="tel:<?php echo $setting['tel']; ?>"><?php echo $setting['tel']; ?></a>
                            </div>
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
    <script>
        $('body').on('click', 'button[name=set]', function() {
            grecaptcha.ready(() => {
                grecaptcha.execute('6LfCSQMeAAAAACFM1T4MB8QDRwj2wG_xkqoQzYiy', {
                    action: 'submit'
                }).then(token => {
                    document.querySelector('#g-recaptcha-response').value = token;
                    var data = $("#contact").serializeArray();
                    data.push({
                        "name": "resToken",
                        "value": token
                    });
                    var $form = $("#contact")[0];
                    if ($form.checkValidity()) {
                        $.ajax({
                        url: '<?php echo THEMEDIR; ?>inc/ajax.php?type=contactForm',
                        type: 'POST',
                        data: data,
                        success: function(e) {
                            var obj = jQuery.parseJSON(e);
                            swal.fire("" + obj.title + "", "" + obj.message + "", "" + obj.type + "");
                            setTimeout(() => {
                                location.reload(true);
                            }, 1325);
                        }
                    });
                    }else{
                        swal.fire("Eksik Alanlar", "Tüm alanları doldurunuz", "warning");
                    }
                    
                });
            });
        });
    </script>
</body>

</html>