<?php
$db->where("id", 1);
$basvuru = $db->getOne("page");
?>
<script>
$(document).ready(() => {
    const url = new URL(window.location.href);
    if (url.origin.search("www") != -1) {
        window.location.href = "https://mdsaudit.com" + url.pathname
    }
});
</script>
<div id="preloader"></div>
<header>
    <div class="header-upper-area pb-4">
        <div class="header-upper-area p-0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 text-left">
                        <?php

                        $db->where("langID", LANGID);
                        $db->where("template", "basvuruyap");
                        $bpage = $db->getOne("page");
                        if ($bpage['status'] == 2) {
                            echo '<a href="' . $bpage['url'] . '" class="btn btn-style-2 text-white" id="basvurubtn">' . $bpage['title'] . '</a>';
                        }

                        ?>
                    </div>
                    <div class="input-group col-lg-4">
                        <input class="form-control py-2 " type="search" placeholder="Arama yap..." id="search-input"
                            onkeydown="if (event.keyCode == 13) search();">

                    </div>
                    <div class="col-lg-1" onclick="search();" id="searchicon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="col-lg-2 col-sm-12 col-md-7 col-12 text-right">
                        <div class="address-phone">
                            <div class="ap-phone">
                                <p class="phone"><span><i class="fas fa-phone"></i></span>
                                    <?php echo $setting['gsm']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-1 col-sm-12 col-md-12 col-12" style="padding: 0px;">
                        <div class="header-social">
                            <ul style="text-align: center;margin:6px">
                                <?php
                                foreach ($db->get("social") as $item) {
                                    echo '<li><a href="' . $item['url'] . '" target="_blank"><i class="' . $item['icon'] . '" style = "font-size:18px" ></i></a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2  d-md-block col-md-2 text-center">
                        <div class="lang-time">
                            <div class="lt-language  d-xl-block">
                                <p class="current" style="padding-right: 10px;">
                                    <?php echo $db->translate("sayfadili"); ?></p>
                                <ul class="list" style="right: 25%">
                                    <?php
                                    $langSql = $db->get("langs");
                                    foreach ($langSql as $lsP) {
                                        echo '<li><a class="lang" href="https://mdsaudit.com/index.php?type=' . $lsP['url'] . '" data-flag="' . $lsP['img'] . '" data-lang="' . $lsP['subtitle'] . '">' . $lsP['title'] . '</a></li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-sm-12 col-12">
                    <div class="logo">
                        <a href="<?php echo $setting['siteurl']; ?>"><img src="<?php echo THEMEIMG ?>home1/logo.png"
                                alt="" style="min-width: 220px;"></a>
                    </div>
                </div>
                <div class="col-lg-10 offset-lg-0 col-md-8 offset-md-2 col-sm-12 offset-sm-0 col-12" style="    align-items: center;
    align-content: end;
    justify-content: end;
    display: flex;">
                    <div class="menu float-right">
                        <nav id="mobile_menu_active">
                            <ul>
                                <?php
                                $db->where("menu_langID", LANGID);
                                $db->where("menu_position", 1);
                                $menu = $db->getOne("menu");
                                $json = json_decode($menu['menu_json']);
                                foreach ($json as $k => $val) {
                                    if (count($val->children) > 0) {

                                        echo '<li>';
                                        if ($val->type == "url") {
                                            echo '<a href="' . $val->href . '" target="' . $val->target . '">' . $val->text . ' <i class="fa fa-angle-down fa-indicator"></i></a>';
                                        } else {
                                            echo '<a href="' . $db->translate("kategori") . '/' . $val->href . '/" target="' . $val->target . '">' . $val->text . ' <i class="fa fa-angle-down fa-indicator"></i></a>';
                                        }

                                        echo '<ul class="drop">';
                                        for ($i = 0; $i < count($val->children); $i++) {
                                            if (count($val->children[$i]->children) > 0) {
                                                echo '<li><a href="' . $val->children[$i]->href . '">' . $val->children[$i]->text . '</a></li>';
                                            } else {
                                                if ($val->children[$i]->type == "sayfa") {
                                                    echo '<li><a href="' . $db->translate("page") . '/' . $val->children[$i]->href . '/">' . $val->children[$i]->text . '</a></li>';
                                                } else if ($val->children[$i]->type == "kategori") {
                                                    echo '<li><a href="' . $db->translate("kategori") . '/' . $val->children[$i]->href . '/">' . $val->children[$i]->text . '</a></li>';
                                                } else if ($val->children[$i]->type == "yazi") {
                                                    echo '<li><a href="' . $db->translate("yazi") . '/' . $val->children[$i]->href . '/">' . $val->children[$i]->text . '</a></li>';
                                                } else if ($val->children[$i]->type == "url") {
                                                    echo '<li><a href="' . $val->children[$i]->href . '">' . $val->children[$i]->text . '</a></li>';
                                                }
                                            }
                                        }
                                        echo '</ul>';
                                        echo '</li>';
                                    } else {
                                        echo '<li>';
                                        if ($val->type == "sayfa") {
                                            echo '<a href="' . $val->href . '" target="' . $val->target . '">' . $val->text . ' </a>';
                                        } else if ($val->type == "yazi") {
                                            echo '<a href="' . $db->translate("yazi") . '/' . $val->href . '/" target="' . $val->target . '">' . $val->text . ' </a>';
                                        } else if ($val->type == "kategori") {
                                            echo '<a href="' . $db->translate("kategori") . '/' . $val->href . '/" target="' . $val->target . '">' . $val->text . '</a>';
                                        } else if ($val->type == "url") {
                                            echo '<a href="' . $val->href . '" target="' . $val->target . '">' . $val->text . '</a>';
                                        }
                                        echo '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>