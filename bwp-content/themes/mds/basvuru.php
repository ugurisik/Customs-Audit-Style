<?php
$db->where("url", $_GET['type']);
$page = $db->getOne("page");

$db->where("status",2);
$db->orderBy("id","ASC");
$pageBasvuru = $db->getOne("pageBasvuru");
$pageBasvuru = json_decode($pageBasvuru['options'],true);

?>
<!DOCTYPE html>
<html lang="<?php echo mb_strtolower($_SESSION['dil']); ?>-<?php echo mb_strtoupper($_SESSION['dil']); ?>">

<head>
    <base href="<?php echo $setting['siteurl']; ?>" />
    <meta charset="utf-8">
    <title>MDS | <?php echo $page['title']; ?></title>
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
    <div class="contact-send-msg-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 col-sm-12 col-12">
                    <div class="section-title">
                        <h6><?php echo $page['title']; ?></h6>
                        <p class="text commenttext"><?php echo $pageBasvuru[mb_strtoupper($_SESSION['dil'])]; ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="question-form-area">
                        <div class="cf-msg"></div>
                        <form id="cf">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="basvurukategori">
                                        <?php echo $db->translate("applicationtype"); ?>
                                    </label>
                                </div>
                                <div class="col-lg-9">
                                    <div class="cf-box">
                                        <select name="basvurukategori">
                                            <?php
                                            $db->where("status","2");
                                            foreach ($db->get("pageBasvuru") as $item) {
                                                foreach (json_decode($item['title']) as $k => $v) {
                                                    if ($k == mb_strtoupper($_SESSION['dil'])) {
                                                        $ftName = $v;
                                                    }
                                                }
                                                foreach (json_decode($item['options']) as $k => $v) {
                                                    if ($k == mb_strtoupper($_SESSION['dil'])) {
                                                        $ftOption = $v;
                                                    }
                                                }
                                                echo '<option value="' . $item['id'] . '" data-text="' . $ftName . '">' . $ftName . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("isim"); ?>"
                                            name="name" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("soyisim"); ?>"
                                            name="surname" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("unvan"); ?>"
                                            name="degree" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("firmaniz"); ?>"
                                            name="companyname" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("email"); ?>"
                                            name="emailaddress" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="cf-box">
                                        <input type="text" placeholder="<?php echo $db->translate("telefon"); ?>"
                                            name="phonenumber" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="cf-box">
                                        <textarea class="contact-textarea"
                                            placeholder="<?php echo $db->translate("mesajınız"); ?>"
                                            name="message"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-12">
                                    <div class="cf-box">
                                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                        <button type="button" class="cont-submit btn-contact"
                                            name="set"><?php echo $db->translate("gonder"); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
    $(document).ready(() => {
        $("select[name=basvurukategori]").on("change", function() {
            let value = $(this).val();
            let data = [{
                "name": "basvuruid",
                "value": value
            }];
            $.ajax({
                url: '<?php echo THEMEDIR; ?>inc/ajax.php?type=basvurugetir',
                type: 'POST',
                data: data,
                success: (response) => {
                    var obj = jQuery.parseJSON(response);
                    $(".commenttext").text(obj.message);
                }
            });
        });
    });
    </script>
    <script>
    $('button[name=set]').on('click', function() {
        grecaptcha.ready(() => {
            grecaptcha.execute('6LfCSQMeAAAAACFM1T4MB8QDRwj2wG_xkqoQzYiy', {
                action: 'submit'
            }).then(token => {
                document.querySelector('#g-recaptcha-response').value = token;
                var data = $("#cf").serializeArray();
                data.push({
                    "name": "resToken",
                    "value": token
                });
                var $form = $("#cf")[0];
                if ($form.checkValidity()) {
                    $.ajax({
                        url: '<?php echo THEMEDIR; ?>inc/ajax.php?type=applicationForm',
                        type: 'POST',
                        data: data,
                        success: function(e) {
                            var obj = jQuery.parseJSON(e);
                            console.log(e);
                            swal.fire("" + obj.title + "", "" + obj.message + "",
                                "" + obj.type + "");
                            setTimeout(() => {
                                //    location.reload(true);
                            }, 2000);
                        }
                    });
                } else {
                    swal.fire("Eksik Alanlar", "Tüm alanları doldurunuz", "warning");
                }

            });
        });
    });
    </script>
</body>

</html>