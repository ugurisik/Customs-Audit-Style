<script>
function search() {
    let input = document.getElementById("search-input");
    if (input.value.length >= 3) {
        window.location.href = "https://mdsaudit.com/ara/" + input.value + "/";
    } else {

    }
}
</script>
<footer>
    <div class="footer-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="fw-info footer-widget">
                        <div class="flogo">
                            <a href="<?php echo $setting['siteurl']; ?>"><img
                                    src="<?php echo THEMEIMG ?>home1/logo white.png" alt=""></a>
                        </div>
                        <div class="address">
                            <h5><span><i class="fas fa-map-marker-alt"></i></span>
                                <?php echo $db->translate('adres'); ?> </h5>
                            <p><?php echo $setting['adres']; ?></p>
                        </div>
                        <ul class="social">
                            <?php
                            foreach ($db->get("social") as $item) {
                                echo '<li><a href="' . $item['url'] . '" target="_blank"><i class="' . $item['icon'] . '"></i></a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="fw-categories footer-widget">
                        <h4 class="title"></h4>
                        <ul class="list">
                            <?php
                            $db->where("menu_langID", LANGID);
                            $db->where("menu_position", 2);
                            $menu = $db->getOne("menu");
                            $json = json_decode($menu['menu_json'], true);
                            foreach ($json as $j) {
                                if ($j['type'] == "url") {
                                    echo '  <li><a href="' . $j['href'] . '" target="' . $j['target'] . '"><span><img src="https://mdsaudit.com/li.png" width="15px"></span>' . $j['text'] . '</a></li>';
                                } else if ($j['type'] == "sayfa") {
                                    echo '  <li><a href="' . $db->translate("page") . '/' . $j['href'] . '" target="' . $j['target'] . '"><span><img src="https://mdsaudit.com/li.png" width="15px"></span>' . $j['text'] . '</a></li>';
                                } else if ($j['type'] == "kategori") {
                                    echo '  <li><a href="' . $db->translate("kategori") . '/' . $j['href'] . '/" target="' . $j['target'] . '"><span><img src="https://mdsaudit.com/li.png" width="15px"></span>' . $j['text'] . '</a></li>';
                                } else if ($j['type'] == "yazi") {
                                    echo '  <li><a href="' . $db->translate("yazi") . '/' . $j['href'] . '" target="' . $j['target'] . '"><span><img src="https://mdsaudit.com/li.png" width="15px"></span>' . $j['text'] . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-5 col-sm-9 col-12">
                    <div class="fw-rpost footer-widget">
                        <h4 class="title"></h4>
                        <ul class="rpost">
                            <?php
                            $bgen = $db->getSetMeta("kurucu", "genislik");
                            $byuk = $db->getSetMeta("kurucu", "yukseklik");

                            $db->orderBy("RAND()");
                            $postm = $db->get("posts");
                            $c = 0;
                            foreach ($postm as $item) {

                                $db->where("postID", $item['ID']);
                                $db->where("type", "image");
                                $image = $db->getOne("post_meta");

                                $db->orderBy("type_meta", "DESC");
                                $db->where("postID", $item['ID']);
                                $db->where("type", "cat");
                                $cate = $db->getOne("post_meta");
                                if ($cate['type_meta'] == "6") {

                            ?>
                            <li>
                                <a href="<?php echo $db->translate("yazi"); ?>/<?php echo $item['post_slug']; ?>/">
                                    <span class="img">
                                        <?php
                                                if ($image['type_meta'] == "") {
                                                    echo '<img alt="mdsaudit" src="https://dummyimage.com/800x600/aaa/fff.png&amp;text=' . $db->translate("resimyok") . '"/>';
                                                } else {
                                                    echo '<img alt="mdsaudit" src="' . BWPUP . 'posts/' . $bgen . 'x' . $byuk . '/' . $image['type_meta'] . '"/>';
                                                }
                                                ?>
                                    </span>
                                    <span class="content">
                                        <span class="name"><?php echo $yazi->kisalt($item['post_title'], 50); ?></span>
                                        <span class="date"><span><i class="far fa-clock"></i></span>
                                            <?php echo $item['post_date']; ?></span>
                                    </span>
                                </a>
                            </li>
                            <?php
                                    $c++;
                                    if ($c > 5) {
                                        break;
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-sm-12 col-12">
                    <div class="fba-left">
                        <p><a href="https://www.internettescil.com.tr">İNTERNETTESCİL</a> © <?= date("Y") ?>. All Rights
                            Reserved.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12 col-12">
                    <div class="fba-right">
                        <!-- <p>Developed by Can & Ugur</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>