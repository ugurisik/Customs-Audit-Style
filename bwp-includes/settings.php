<?php
//error_reporting(0);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'classes/database.php';
$db = new MysqliDb(
    array(
        'host' =>       'localhost',
        'username' =>   'ixngwary_mds',
        'password' =>   'test123',
        'db' =>          'ixngwary_mds',
        'port' =>       3306,
        'prefix' =>     'bwp_',
        'charset' =>    'utf8'
    )
);
include 'classes/functions.php';
include 'classes/security.php';

$db->where("aktif",1);
$seosettings = $db->getOne("settings");

$yazi = new yazi();
$security = new security();
$panelTitle = "İnternet Tescil CSM v1";
$authorization = array("Yazılım Sahibi" => 1, "Yönetici" => 2, "Editör" => 3, "Üye" => 4);
$status = array("Durumu Seçiniz" => 0, "Pasif" => 1, "Aktif" => 2, "Onay Bekliyor" => 3, "Silinmiş" => 4);
$postComment = array("Durumu Seçiniz" => 0, "Yorumlar Kapalı" => 1, "Yorumlar Açık" => 2);
$yetkiler = array(
    'Ekle&Düzenle&Sil' => 'set',
    'Sayfa Görüntüleme' => 'pageview',
    'Sayfa Ekleme' => 'pageadd',
    'Sayfa Düzenleme' => 'pageedit',
    'Sayfa Silme' => 'pagedel',
    'Kullanıcı Görüntüleme' => 'userview',
    'Kullanıcı Ekleme' => 'useradd',
    'Kullanıcı Düzenleme' => 'useredit',
    'Kullanıcı Yetki Düzenleme' => 'userautedit',
    'Kullanıcı Silme' => 'userdel',
    'Katagori Görüntüleme' => 'catview',
    'Katagori Ekleme' => 'catadd',
    'Katagori Düzenleme' => 'catedit',
    'Katagori Silme' => 'catdel',
    'Mail Şablon Görüntüleme' => 'mailview',
    'Mail Şablon Ekleme' => 'mailadd',
    'Mail Şablon Düzenleme' => 'mailedit',
    'Mail Şablon Silme' => 'maildel',
    'Yazı Görüntüleme' => 'postview',
    'Yazı Ekleme' => 'postadd',
    'Yazı Düzenleme' => 'postedit',
    'Yazı Silme' => 'postdel',
    'Ayar Görüntüleme' => 'setview',
    'Ayar Ekleme' => 'setadd',
    'Ayar Düzenleme' => 'setedit',
    'Ayar Silme' => 'setdel',
    'Varsayılan ayar değiştirme' => 'defaultset',
    'Tema Görüntüleme' => 'themeview',
    'Varsayılan Tema Değiştirme' => 'themeactive',
    'Resim ayarları Görüntüleme' => 'setimageview',
    'Resim ayarları Ekleme' => 'setimageadd',
    'Resim ayarları Düzenleme' => 'setimageedit',
    'Resim ayarları Silme' => 'setimagedel',
    'Dashboard Görüntüleme' => 'dashboardview',
    'Menu Ekleme' => 'menuadd',
    'Menu Düzenleme' => 'menuedit',
    'Menu Silme' => 'menudel',
    'S.S.S. Yönetimi' => 'faqView',
    'S.S.S. Ekleme' => 'faqAdd',
    'S.S.S. Düzenleme' => 'faqEdit',
    'S.S.S. Silme' => 'faqDel',
    'Sosyal Medya Yönetimi' => 'socialViews',
    'Sosyal Medya Ekleme' => 'socialAdd',
    'Sosyal Medya Düzenleme' => 'socialEdit',
    'Sosyal Medya Silme' => 'socialDel',
    'Anasayfa Yönetimi' => 'homepageview',
    'Anasayfa Ekleme' => 'homepageAdd',
    'Anasayfa Düzenleme' => 'homepageEdit',
    'Anasayfa Silme' => 'homepageDel',
    'Müşteri Yorumları Yönetimi' => 'commentview',
    'Müşteri Yorumları Ekleme' => 'commentAdd',
    'Müşteri Yorumları Düzenleme' => 'commentEdit',
    'Müşteri Yorumları Silme' => 'commentDel',
);
$panelMenu = [
    [
        "text" => "Site Anasayfa",
        "href" => "#",
        "icon" => "fa fa-home",
        "target" => "_blank"
    ],
    [
        "text" => "Ayarlar",
        "href" => "#",
        "active" => "setting",
        "icon" => "fa fa-cogs",
        "target" => "",
        "children" => [
            [
                "text" => "Site Ayarları",
                "href" => "",
                "active" => "setlink",
                "icon" => "",
                "target" => "",
                "children" => [
                    [
                        "text" => "Ayar Ekle",
                        "href" => "setting.php?process=add",
                        "active" => "setadd",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "Tüm Ayarlar",
                        "href" => "allset.php",
                        "active" => "sets",
                        "icon" => "",
                        "target" => "",
                    ]
                ]
            ],
            [
                "text" => "Site Dilleri",
                "href" => "",
                "active" => "lang",
                "icon" => "",
                "target" => "",
                "children" => [
                    [
                        "text" => "Dil Değişkenleri",
                        "href" => "langvariable.php",
                        "active" => "langvariable",
                        "icon" => "",
                        "target" => "",
                    ]
                ]
            ],
            [
                "text" => "Görünüm Ayarları",
                "href" => "",
                "active" => "viewlink",
                "icon" => "",
                "target" => "",
                "children" => [
                    [
                        "text" => "Menü Ayarları",
                        "href" => "menu.php",
                        "active" => "menu",
                        "icon" => "",
                        "target" => "",
                    ]
                ]
            ],
            [
                "text" => "Sosyal Medya Link Yönetimi",
                "href" => "social.php",
                "active" => "social",
                "icon" => "",
                "target" => "",
            ],
            [
                "text" => "Mail ve Mail şablon Yönetimi",
                "href" => "",
                "active" => "mail",
                "icon" => "",
                "target" => "",
                "children" => [
                    [
                        "text" => "Mail Ayarları",
                        "href" => "mailsetting.php",
                        "active" => "mailsetting",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "Mail Şablonları",
                        "href" => "mailtemplate.php",
                        "active" => "mailtemplate",
                        "icon" => "",
                        "target" => "",
                    ]
                ]
            ],
        ]
    ],
    [
        "text" => "Kullanıcı Yönetimi",
        "href" => "users.php",
        "active" => "users",
        "icon" => "fa fa-user",
        "target" => "",
    ],
    [
        "text" => "İçerik Yönetimi",
        "href" => "#",
        "active" => "content",
        "icon" => "fa fa-newspaper",
        "target" => "",
        "children" => [
            [
                "text" => "Anasayfa Yönetimi",
                "href" => "homepage.php",
                "active" => "homepage",
                "icon" => "",
                "target" => "",
            ],
            [
                "text" => "Hakkımızda Yönetimi",
                "href" => "aboutus.php",
                "active" => "aboutus",
                "icon" => "",
                "target" => "",
            ],
            [
                "text" => "Yazı Yönetimi",
                "href" => "",
                "active" => "post",
                "icon" => "",
                "target" => "",
                "children" => [
                    [
                        "text" => "Kategoriler Yönetimi",
                        "href" => "cats.php?process=add",
                        "active" => "cats",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "Yazı Ekle",
                        "href" => "posts.php?process=add",
                        "active" => "postsadd",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "Tüm Yazılar",
                        "href" => "post.php",
                        "active" => "posts",
                        "icon" => "",
                        "target" => "",
                    ]
                ]
            ],
            [
                "text" => "Hizmet Yönetimi",
                "href" => "",
                "active" => "service",
                "icon" => "",
                "target" => "",
                "children" => [
                    [
                        "text" => "Kategoriler Yönetimi",
                        "href" => "servicecats.php",
                        "active" => "cats",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "İçerik Ekle",
                        "href" => "service.php?process=add",
                        "active" => "serviceadd",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "Tüm Yazılar",
                        "href" => "services.php",
                        "active" => "services",
                        "icon" => "",
                        "target" => "",
                    ]
                ]
            ],
            [
                "text" => "Eğitim Yönetimi",
                "href" => "",
                "active" => "education",
                "icon" => "",
                "target" => "",
                "children" => [
                    [
                        "text" => "Kategoriler Yönetimi",
                        "href" => "educationcats.php",
                        "active" => "educationcats",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "İçerik Ekle",
                        "href" => "education.php?process=add",
                        "active" => "educationAdd",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "Tüm Yazılar",
                        "href" => "educations.php",
                        "active" => "educations",
                        "icon" => "",
                        "target" => "",
                    ]
                ]
            ],
            [
                "text" => "Müşteri Yorumları",
                "href" => "customer.php",
                "active" => "customer",
                "icon" => "",
                "target" => "",
            ],
            [
                "text" => "Başvuru Yönetimi",
                "href" => "",
                "active" => "basvuru",
                "icon" => "",
                "target" => "",
                "children" => [
                    [
                        "text" => "Sayfa Ayarları",
                        "href" => "basvuru.php",
                        "active" => "basvuruset",
                        "icon" => "",
                        "target" => "",
                    ],
                    [
                        "text" => "Tüm Başvurular",
                        "href" => "basvurular.php",
                        "active" => "basvurular",
                        "icon" => "",
                        "target" => "",
                    ]
                ]
            ],
        ]
    ]
];
$iconPack = array(0 => 'fa fa-bars fa-lg', 1 => 'fa fa-font-awesome', 2 => 'fa fa-caret-down', 3 => 'fa fa-flag fa-fw', 4 => 'fa fa-handshake-o fa-fw', 5 => 'fa fa-camera-retro fa-fw', 6 => 'fa fa-universal-access fa-fw', 7 => 'fa fa-hand-spock-o fa-fw', 8 => 'fa fa-ship fa-fw', 9 => 'fa fa-venus fa-fw', 10 => 'fa fa-file-image-o fa-fw', 11 => 'fa fa-spinner fa-fw', 12 => 'fa fa-check-square fa-fw', 13 => 'fa fa-credit-card fa-fw', 14 => 'fa fa-pie-chart fa-fw', 15 => 'fa fa-won fa-fw', 16 => 'fa fa-file-text-o fa-fw', 17 => 'fa fa-arrow-right fa-fw', 18 => 'fa fa-play-circle fa-fw', 19 => 'fa fa-facebook-official fa-fw', 20 => 'fa fa-medkit fa-fw', 21 => 'fa fa-caret-down', 22 => 'fa fa-universal-access', 23 => 'fa fa-flag', 24 => 'fa fa-search', 25 => 'fa fa-times-circle hide', 26 => 'fa fa-address-book', 27 => 'fa fa-address-book-o', 28 => 'fa fa-address-card', 29 => 'fa fa-address-card-o', 30 => 'fa fa-bandcamp', 31 => 'fa fa-bath', 32 => 'fa fa-bathtub', 33 => 'fa fa-drivers-license', 34 => 'fa fa-drivers-license-o', 35 => 'fa fa-eercast', 36 => 'fa fa-envelope-open', 37 => 'fa fa-envelope-open-o', 38 => 'fa fa-etsy', 39 => 'fa fa-free-code-camp', 40 => 'fa fa-grav', 41 => 'fa fa-handshake-o', 42 => 'fa fa-id-badge', 43 => 'fa fa-id-card', 44 => 'fa fa-id-card-o', 45 => 'fa fa-imdb', 46 => 'fa fa-linode', 47 => 'fa fa-meetup', 48 => 'fa fa-microchip', 49 => 'fa fa-podcast', 50 => 'fa fa-quora', 51 => 'fa fa-ravelry', 52 => 'fa fa-s15', 53 => 'fa fa-shower', 54 => 'fa fa-snowflake-o', 55 => 'fa fa-superpowers', 56 => 'fa fa-telegram', 57 => 'fa fa-thermometer', 58 => 'fa fa-thermometer-0', 59 => 'fa fa-thermometer-1', 60 => 'fa fa-thermometer-2', 61 => 'fa fa-thermometer-3', 62 => 'fa fa-thermometer-4', 63 => 'fa fa-thermometer-empty', 64 => 'fa fa-thermometer-full', 65 => 'fa fa-thermometer-half', 66 => 'fa fa-thermometer-quarter', 67 => 'fa fa-thermometer-three-quarters', 68 => 'fa fa-times-rectangle', 69 => 'fa fa-times-rectangle-o', 70 => 'fa fa-user-circle', 71 => 'fa fa-user-circle-o', 72 => 'fa fa-user-o', 73 => 'fa fa-vcard', 74 => 'fa fa-vcard-o', 75 => 'fa fa-window-close', 76 => 'fa fa-window-close-o', 77 => 'fa fa-window-maximize', 78 => 'fa fa-window-minimize', 79 => 'fa fa-window-restore', 80 => 'fa fa-wpexplorer', 81 => 'fa fa-address-book', 82 => 'fa fa-address-book-o', 83 => 'fa fa-address-card', 84 => 'fa fa-address-card-o', 85 => 'fa fa-adjust', 86 => 'fa fa-american-sign-language-interpreting', 87 => 'fa fa-anchor', 88 => 'fa fa-archive', 89 => 'fa fa-area-chart', 90 => 'fa fa-arrows', 91 => 'fa fa-arrows-h', 92 => 'fa fa-arrows-v', 93 => 'fa fa-asl-interpreting', 94 => 'fa fa-assistive-listening-systems', 95 => 'fa fa-asterisk', 96 => 'fa fa-at', 97 => 'fa fa-audio-description', 98 => 'fa fa-automobile', 99 => 'fa fa-balance-scale', 100 => 'fa fa-ban', 101 => 'fa fa-bank', 102 => 'fa fa-bar-chart', 103 => 'fa fa-bar-chart-o', 104 => 'fa fa-barcode', 105 => 'fa fa-bars', 106 => 'fa fa-bath', 107 => 'fa fa-bathtub', 108 => 'fa fa-battery', 109 => 'fa fa-battery-0', 110 => 'fa fa-battery-1', 111 => 'fa fa-battery-2', 112 => 'fa fa-battery-3', 113 => 'fa fa-battery-4', 114 => 'fa fa-battery-empty', 115 => 'fa fa-battery-full', 116 => 'fa fa-battery-half', 117 => 'fa fa-battery-quarter', 118 => 'fa fa-battery-three-quarters', 119 => 'fa fa-bed', 120 => 'fa fa-beer', 121 => 'fa fa-bell', 122 => 'fa fa-bell-o', 123 => 'fa fa-bell-slash', 124 => 'fa fa-bell-slash-o', 125 => 'fa fa-bicycle', 126 => 'fa fa-binoculars', 127 => 'fa fa-birthday-cake', 128 => 'fa fa-blind', 129 => 'fa fa-bluetooth', 130 => 'fa fa-bluetooth-b', 131 => 'fa fa-bolt', 132 => 'fa fa-bomb', 133 => 'fa fa-book', 134 => 'fa fa-bookmark', 135 => 'fa fa-bookmark-o', 136 => 'fa fa-braille', 137 => 'fa fa-briefcase', 138 => 'fa fa-bug', 139 => 'fa fa-building', 140 => 'fa fa-building-o', 141 => 'fa fa-bullhorn', 142 => 'fa fa-bullseye', 143 => 'fa fa-bus', 144 => 'fa fa-cab', 145 => 'fa fa-calculator', 146 => 'fa fa-calendar', 147 => 'fa fa-calendar-check-o', 148 => 'fa fa-calendar-minus-o', 149 => 'fa fa-calendar-o', 150 => 'fa fa-calendar-plus-o', 151 => 'fa fa-calendar-times-o', 152 => 'fa fa-camera', 153 => 'fa fa-camera-retro', 154 => 'fa fa-car', 155 => 'fa fa-caret-square-o-down', 156 => 'fa fa-caret-square-o-left', 157 => 'fa fa-caret-square-o-right', 158 => 'fa fa-caret-square-o-up', 159 => 'fa fa-cart-arrow-down', 160 => 'fa fa-cart-plus', 161 => 'fa fa-cc', 162 => 'fa fa-certificate', 163 => 'fa fa-check', 164 => 'fa fa-check-circle', 165 => 'fa fa-check-circle-o', 166 => 'fa fa-check-square', 167 => 'fa fa-check-square-o', 168 => 'fa fa-child', 169 => 'fa fa-circle', 170 => 'fa fa-circle-o', 171 => 'fa fa-circle-o-notch', 172 => 'fa fa-circle-thin', 173 => 'fa fa-clock-o', 174 => 'fa fa-clone', 175 => 'fa fa-close', 176 => 'fa fa-cloud', 177 => 'fa fa-cloud-download', 178 => 'fa fa-cloud-upload', 179 => 'fa fa-code', 180 => 'fa fa-code-fork', 181 => 'fa fa-coffee', 182 => 'fa fa-cog', 183 => 'fa fa-cogs', 184 => 'fa fa-comment', 185 => 'fa fa-comment-o', 186 => 'fa fa-commenting', 187 => 'fa fa-commenting-o', 188 => 'fa fa-comments', 189 => 'fa fa-comments-o', 190 => 'fa fa-compass', 191 => 'fa fa-copyright', 192 => 'fa fa-creative-commons', 193 => 'fa fa-credit-card', 194 => 'fa fa-credit-card-alt', 195 => 'fa fa-crop', 196 => 'fa fa-crosshairs', 197 => 'fa fa-cube', 198 => 'fa fa-cubes', 199 => 'fa fa-cutlery', 200 => 'fa fa-dashboard', 201 => 'fa fa-database', 202 => 'fa fa-deaf', 203 => 'fa fa-deafness', 204 => 'fa fa-desktop', 205 => 'fa fa-diamond', 206 => 'fa fa-dot-circle-o', 207 => 'fa fa-download', 208 => 'fa fa-drivers-license', 209 => 'fa fa-drivers-license-o', 210 => 'fa fa-edit', 211 => 'fa fa-ellipsis-h', 212 => 'fa fa-ellipsis-v', 213 => 'fa fa-envelope', 214 => 'fa fa-envelope-o', 215 => 'fa fa-envelope-open', 216 => 'fa fa-envelope-open-o', 217 => 'fa fa-envelope-square', 218 => 'fa fa-eraser', 219 => 'fa fa-exchange', 220 => 'fa fa-exclamation', 221 => 'fa fa-exclamation-circle', 222 => 'fa fa-exclamation-triangle', 223 => 'fa fa-external-link', 224 => 'fa fa-external-link-square', 225 => 'fa fa-eye', 226 => 'fa fa-eye-slash', 227 => 'fa fa-eyedropper', 228 => 'fa fa-fax', 229 => 'fa fa-feed', 230 => 'fa fa-female', 231 => 'fa fa-fighter-jet', 232 => 'fa fa-file-archive-o', 233 => 'fa fa-file-audio-o', 234 => 'fa fa-file-code-o', 235 => 'fa fa-file-excel-o', 236 => 'fa fa-file-image-o', 237 => 'fa fa-file-movie-o', 238 => 'fa fa-file-pdf-o', 239 => 'fa fa-file-photo-o', 240 => 'fa fa-file-picture-o', 241 => 'fa fa-file-powerpoint-o', 242 => 'fa fa-file-sound-o', 243 => 'fa fa-file-video-o', 244 => 'fa fa-file-word-o', 245 => 'fa fa-file-zip-o', 246 => 'fa fa-film', 247 => 'fa fa-filter', 248 => 'fa fa-fire', 249 => 'fa fa-fire-extinguisher', 250 => 'fa fa-flag', 251 => 'fa fa-flag-checkered', 252 => 'fa fa-flag-o', 253 => 'fa fa-flash', 254 => 'fa fa-flask', 255 => 'fa fa-folder', 256 => 'fa fa-folder-o', 257 => 'fa fa-folder-open', 258 => 'fa fa-folder-open-o', 259 => 'fa fa-frown-o', 260 => 'fa fa-futbol-o', 261 => 'fa fa-gamepad', 262 => 'fa fa-gavel', 263 => 'fa fa-gear', 264 => 'fa fa-gears', 265 => 'fa fa-gift', 266 => 'fa fa-glass', 267 => 'fa fa-globe', 268 => 'fa fa-graduation-cap', 269 => 'fa fa-group', 270 => 'fa fa-hand-grab-o', 271 => 'fa fa-hand-lizard-o', 272 => 'fa fa-hand-paper-o', 273 => 'fa fa-hand-peace-o', 274 => 'fa fa-hand-pointer-o', 275 => 'fa fa-hand-rock-o', 276 => 'fa fa-hand-scissors-o', 277 => 'fa fa-hand-spock-o', 278 => 'fa fa-hand-stop-o', 279 => 'fa fa-handshake-o', 280 => 'fa fa-hard-of-hearing', 281 => 'fa fa-hashtag', 282 => 'fa fa-hdd-o', 283 => 'fa fa-headphones', 284 => 'fa fa-heart', 285 => 'fa fa-heart-o', 286 => 'fa fa-heartbeat', 287 => 'fa fa-history', 288 => 'fa fa-home', 289 => 'fa fa-hotel', 290 => 'fa fa-hourglass', 291 => 'fa fa-hourglass-1', 292 => 'fa fa-hourglass-2', 293 => 'fa fa-hourglass-3', 294 => 'fa fa-hourglass-end', 295 => 'fa fa-hourglass-half', 296 => 'fa fa-hourglass-o', 297 => 'fa fa-hourglass-start', 298 => 'fa fa-i-cursor', 299 => 'fa fa-id-badge', 300 => 'fa fa-id-card', 301 => 'fa fa-id-card-o', 302 => 'fa fa-image', 303 => 'fa fa-inbox', 304 => 'fa fa-industry', 305 => 'fa fa-info', 306 => 'fa fa-info-circle', 307 => 'fa fa-institution', 308 => 'fa fa-key', 309 => 'fa fa-keyboard-o', 310 => 'fa fa-language', 311 => 'fa fa-laptop', 312 => 'fa fa-leaf', 313 => 'fa fa-legal', 314 => 'fa fa-lemon-o', 315 => 'fa fa-level-down', 316 => 'fa fa-level-up', 317 => 'fa fa-life-bouy', 318 => 'fa fa-life-buoy', 319 => 'fa fa-life-ring', 320 => 'fa fa-life-saver', 321 => 'fa fa-lightbulb-o', 322 => 'fa fa-line-chart', 323 => 'fa fa-location-arrow', 324 => 'fa fa-lock', 325 => 'fa fa-low-vision', 326 => 'fa fa-magic', 327 => 'fa fa-magnet', 328 => 'fa fa-mail-forward', 329 => 'fa fa-mail-reply', 330 => 'fa fa-mail-reply-all', 331 => 'fa fa-male', 332 => 'fa fa-map', 333 => 'fa fa-map-marker', 334 => 'fa fa-map-o', 335 => 'fa fa-map-pin', 336 => 'fa fa-map-signs', 337 => 'fa fa-meh-o', 338 => 'fa fa-microchip', 339 => 'fa fa-microphone', 340 => 'fa fa-microphone-slash', 341 => 'fa fa-minus', 342 => 'fa fa-minus-circle', 343 => 'fa fa-minus-square', 344 => 'fa fa-minus-square-o', 345 => 'fa fa-mobile', 346 => 'fa fa-mobile-phone', 347 => 'fa fa-money', 348 => 'fa fa-moon-o', 349 => 'fa fa-mortar-board', 350 => 'fa fa-motorcycle', 351 => 'fa fa-mouse-pointer', 352 => 'fa fa-music', 353 => 'fa fa-navicon', 354 => 'fa fa-newspaper-o', 355 => 'fa fa-object-group', 356 => 'fa fa-object-ungroup', 357 => 'fa fa-paint-brush', 358 => 'fa fa-paper-plane', 359 => 'fa fa-paper-plane-o', 360 => 'fa fa-paw', 361 => 'fa fa-pencil', 362 => 'fa fa-pencil-square', 363 => 'fa fa-pencil-square-o', 364 => 'fa fa-percent', 365 => 'fa fa-phone', 366 => 'fa fa-phone-square', 367 => 'fa fa-photo', 368 => 'fa fa-picture-o', 369 => 'fa fa-pie-chart', 370 => 'fa fa-plane', 371 => 'fa fa-plug', 372 => 'fa fa-plus', 373 => 'fa fa-plus-circle', 374 => 'fa fa-plus-square', 375 => 'fa fa-plus-square-o', 376 => 'fa fa-podcast', 377 => 'fa fa-power-off', 378 => 'fa fa-print', 379 => 'fa fa-puzzle-piece', 380 => 'fa fa-qrcode', 381 => 'fa fa-question', 382 => 'fa fa-question-circle', 383 => 'fa fa-question-circle-o', 384 => 'fa fa-quote-left', 385 => 'fa fa-quote-right', 386 => 'fa fa-random', 387 => 'fa fa-recycle', 388 => 'fa fa-refresh', 389 => 'fa fa-registered', 390 => 'fa fa-remove', 391 => 'fa fa-reorder', 392 => 'fa fa-reply', 393 => 'fa fa-reply-all', 394 => 'fa fa-retweet', 395 => 'fa fa-road', 396 => 'fa fa-rocket', 397 => 'fa fa-rss', 398 => 'fa fa-rss-square', 399 => 'fa fa-s15', 400 => 'fa fa-search', 401 => 'fa fa-search-minus', 402 => 'fa fa-search-plus', 403 => 'fa fa-send', 404 => 'fa fa-send-o', 405 => 'fa fa-server', 406 => 'fa fa-share', 407 => 'fa fa-share-alt', 408 => 'fa fa-share-alt-square', 409 => 'fa fa-share-square', 410 => 'fa fa-share-square-o', 411 => 'fa fa-shield', 412 => 'fa fa-ship', 413 => 'fa fa-shopping-bag', 414 => 'fa fa-shopping-basket', 415 => 'fa fa-shopping-cart', 416 => 'fa fa-shower', 417 => 'fa fa-sign-in', 418 => 'fa fa-sign-language', 419 => 'fa fa-sign-out', 420 => 'fa fa-signal', 421 => 'fa fa-signing', 422 => 'fa fa-sitemap', 423 => 'fa fa-sliders', 424 => 'fa fa-smile-o', 425 => 'fa fa-snowflake-o', 426 => 'fa fa-soccer-ball-o', 427 => 'fa fa-sort', 428 => 'fa fa-sort-alpha-asc', 429 => 'fa fa-sort-alpha-desc', 430 => 'fa fa-sort-amount-asc', 431 => 'fa fa-sort-amount-desc', 432 => 'fa fa-sort-asc', 433 => 'fa fa-sort-desc', 434 => 'fa fa-sort-down', 435 => 'fa fa-sort-numeric-asc', 436 => 'fa fa-sort-numeric-desc', 437 => 'fa fa-sort-up', 438 => 'fa fa-space-shuttle', 439 => 'fa fa-spinner', 440 => 'fa fa-spoon', 441 => 'fa fa-square', 442 => 'fa fa-square-o', 443 => 'fa fa-star', 444 => 'fa fa-star-half', 445 => 'fa fa-star-half-empty', 446 => 'fa fa-star-half-full', 447 => 'fa fa-star-half-o', 448 => 'fa fa-star-o', 449 => 'fa fa-sticky-note', 450 => 'fa fa-sticky-note-o', 451 => 'fa fa-street-view', 452 => 'fa fa-suitcase', 453 => 'fa fa-sun-o', 454 => 'fa fa-support', 455 => 'fa fa-tablet', 456 => 'fa fa-tachometer', 457 => 'fa fa-tag', 458 => 'fa fa-tags', 459 => 'fa fa-tasks', 460 => 'fa fa-taxi', 461 => 'fa fa-television', 462 => 'fa fa-terminal', 463 => 'fa fa-thermometer', 464 => 'fa fa-thermometer-0', 465 => 'fa fa-thermometer-1', 466 => 'fa fa-thermometer-2', 467 => 'fa fa-thermometer-3', 468 => 'fa fa-thermometer-4', 469 => 'fa fa-thermometer-empty', 470 => 'fa fa-thermometer-full', 471 => 'fa fa-thermometer-half', 472 => 'fa fa-thermometer-quarter', 473 => 'fa fa-thermometer-three-quarters', 474 => 'fa fa-thumb-tack', 475 => 'fa fa-thumbs-down', 476 => 'fa fa-thumbs-o-down', 477 => 'fa fa-thumbs-o-up', 478 => 'fa fa-thumbs-up', 479 => 'fa fa-ticket', 480 => 'fa fa-times', 481 => 'fa fa-times-circle', 482 => 'fa fa-times-circle-o', 483 => 'fa fa-times-rectangle', 484 => 'fa fa-times-rectangle-o', 485 => 'fa fa-tint', 486 => 'fa fa-toggle-down', 487 => 'fa fa-toggle-left', 488 => 'fa fa-toggle-off', 489 => 'fa fa-toggle-on', 490 => 'fa fa-toggle-right', 491 => 'fa fa-toggle-up', 492 => 'fa fa-trademark', 493 => 'fa fa-trash', 494 => 'fa fa-trash-o', 495 => 'fa fa-tree', 496 => 'fa fa-trophy', 497 => 'fa fa-truck', 498 => 'fa fa-tty', 499 => 'fa fa-tv', 500 => 'fa fa-umbrella', 501 => 'fa fa-universal-access', 502 => 'fa fa-university', 503 => 'fa fa-unlock', 504 => 'fa fa-unlock-alt', 505 => 'fa fa-unsorted', 506 => 'fa fa-upload', 507 => 'fa fa-user', 508 => 'fa fa-user-circle', 509 => 'fa fa-user-circle-o', 510 => 'fa fa-user-o', 511 => 'fa fa-user-plus', 512 => 'fa fa-user-secret', 513 => 'fa fa-user-times', 514 => 'fa fa-users', 515 => 'fa fa-vcard', 516 => 'fa fa-vcard-o', 517 => 'fa fa-video-camera', 518 => 'fa fa-volume-control-phone', 519 => 'fa fa-volume-down', 520 => 'fa fa-volume-off', 521 => 'fa fa-volume-up', 522 => 'fa fa-warning', 523 => 'fa fa-wheelchair', 524 => 'fa fa-wheelchair-alt', 525 => 'fa fa-wifi', 526 => 'fa fa-window-close', 527 => 'fa fa-window-close-o', 528 => 'fa fa-window-maximize', 529 => 'fa fa-window-minimize', 530 => 'fa fa-window-restore', 531 => 'fa fa-wrench', 532 => 'fa fa-american-sign-language-interpreting', 533 => 'fa fa-asl-interpreting', 534 => 'fa fa-assistive-listening-systems', 535 => 'fa fa-audio-description', 536 => 'fa fa-blind', 537 => 'fa fa-braille', 538 => 'fa fa-cc', 539 => 'fa fa-deaf', 540 => 'fa fa-deafness', 541 => 'fa fa-hard-of-hearing', 542 => 'fa fa-low-vision', 543 => 'fa fa-question-circle-o', 544 => 'fa fa-sign-language', 545 => 'fa fa-signing', 546 => 'fa fa-tty', 547 => 'fa fa-universal-access', 548 => 'fa fa-volume-control-phone', 549 => 'fa fa-wheelchair', 550 => 'fa fa-wheelchair-alt', 551 => 'fa fa-hand-grab-o', 552 => 'fa fa-hand-lizard-o', 553 => 'fa fa-hand-o-down', 554 => 'fa fa-hand-o-left', 555 => 'fa fa-hand-o-right', 556 => 'fa fa-hand-o-up', 557 => 'fa fa-hand-paper-o', 558 => 'fa fa-hand-peace-o', 559 => 'fa fa-hand-pointer-o', 560 => 'fa fa-hand-rock-o', 561 => 'fa fa-hand-scissors-o', 562 => 'fa fa-hand-spock-o', 563 => 'fa fa-hand-stop-o', 564 => 'fa fa-thumbs-down', 565 => 'fa fa-thumbs-o-down', 566 => 'fa fa-thumbs-o-up', 567 => 'fa fa-thumbs-up', 568 => 'fa fa-ambulance', 569 => 'fa fa-automobile', 570 => 'fa fa-bicycle', 571 => 'fa fa-bus', 572 => 'fa fa-cab', 573 => 'fa fa-car', 574 => 'fa fa-fighter-jet', 575 => 'fa fa-motorcycle', 576 => 'fa fa-plane', 577 => 'fa fa-rocket', 578 => 'fa fa-ship', 579 => 'fa fa-space-shuttle', 580 => 'fa fa-subway', 581 => 'fa fa-taxi', 582 => 'fa fa-train', 583 => 'fa fa-truck', 584 => 'fa fa-wheelchair', 585 => 'fa fa-wheelchair-alt', 586 => 'fa fa-genderless', 587 => 'fa fa-intersex', 588 => 'fa fa-mars', 589 => 'fa fa-mars-double', 590 => 'fa fa-mars-stroke', 591 => 'fa fa-mars-stroke-h', 592 => 'fa fa-mars-stroke-v', 593 => 'fa fa-mercury', 594 => 'fa fa-neuter', 595 => 'fa fa-transgender', 596 => 'fa fa-transgender-alt', 597 => 'fa fa-venus', 598 => 'fa fa-venus-double', 599 => 'fa fa-venus-mars', 600 => 'fa fa-file', 601 => 'fa fa-file-archive-o', 602 => 'fa fa-file-audio-o', 603 => 'fa fa-file-code-o', 604 => 'fa fa-file-excel-o', 605 => 'fa fa-file-image-o', 606 => 'fa fa-file-movie-o', 607 => 'fa fa-file-o', 608 => 'fa fa-file-pdf-o', 609 => 'fa fa-file-photo-o', 610 => 'fa fa-file-picture-o', 611 => 'fa fa-file-powerpoint-o', 612 => 'fa fa-file-sound-o', 613 => 'fa fa-file-text', 614 => 'fa fa-file-text-o', 615 => 'fa fa-file-video-o', 616 => 'fa fa-file-word-o', 617 => 'fa fa-file-zip-o', 618 => 'fa fa-info-circle fa-lg fa-li', 619 => 'fa fa-circle-o-notch', 620 => 'fa fa-cog', 621 => 'fa fa-gear', 622 => 'fa fa-refresh', 623 => 'fa fa-spinner', 624 => 'fa fa-check-square', 625 => 'fa fa-check-square-o', 626 => 'fa fa-circle', 627 => 'fa fa-circle-o', 628 => 'fa fa-dot-circle-o', 629 => 'fa fa-minus-square', 630 => 'fa fa-minus-square-o', 631 => 'fa fa-plus-square', 632 => 'fa fa-plus-square-o', 633 => 'fa fa-square', 634 => 'fa fa-square-o', 635 => 'fa fa-cc-amex', 636 => 'fa fa-cc-diners-club', 637 => 'fa fa-cc-discover', 638 => 'fa fa-cc-jcb', 639 => 'fa fa-cc-mastercard', 640 => 'fa fa-cc-paypal', 641 => 'fa fa-cc-stripe', 642 => 'fa fa-cc-visa', 643 => 'fa fa-credit-card', 644 => 'fa fa-credit-card-alt', 645 => 'fa fa-google-wallet', 646 => 'fa fa-paypal', 647 => 'fa fa-area-chart', 648 => 'fa fa-bar-chart', 649 => 'fa fa-bar-chart-o', 650 => 'fa fa-line-chart', 651 => 'fa fa-pie-chart', 652 => 'fa fa-bitcoin', 653 => 'fa fa-btc', 654 => 'fa fa-cny', 655 => 'fa fa-dollar', 656 => 'fa fa-eur', 657 => 'fa fa-euro', 658 => 'fa fa-gbp', 659 => 'fa fa-gg', 660 => 'fa fa-gg-circle', 661 => 'fa fa-ils', 662 => 'fa fa-inr', 663 => 'fa fa-jpy', 664 => 'fa fa-krw', 665 => 'fa fa-money', 666 => 'fa fa-rmb', 667 => 'fa fa-rouble', 668 => 'fa fa-rub', 669 => 'fa fa-ruble', 670 => 'fa fa-rupee', 671 => 'fa fa-shekel', 672 => 'fa fa-sheqel', 673 => 'fa fa-try', 674 => 'fa fa-turkish-lira', 675 => 'fa fa-usd', 676 => 'fa fa-viacoin', 677 => 'fa fa-won', 678 => 'fa fa-yen', 679 => 'fa fa-align-center', 680 => 'fa fa-align-justify', 681 => 'fa fa-align-left', 682 => 'fa fa-align-right', 683 => 'fa fa-bold', 684 => 'fa fa-chain', 685 => 'fa fa-chain-broken', 686 => 'fa fa-clipboard', 687 => 'fa fa-columns', 688 => 'fa fa-copy', 689 => 'fa fa-cut', 690 => 'fa fa-dedent', 691 => 'fa fa-eraser', 692 => 'fa fa-file', 693 => 'fa fa-file-o', 694 => 'fa fa-file-text', 695 => 'fa fa-file-text-o', 696 => 'fa fa-files-o', 697 => 'fa fa-floppy-o', 698 => 'fa fa-font', 699 => 'fa fa-header', 700 => 'fa fa-indent', 701 => 'fa fa-italic', 702 => 'fa fa-link', 703 => 'fa fa-list', 704 => 'fa fa-list-alt', 705 => 'fa fa-list-ol', 706 => 'fa fa-list-ul', 707 => 'fa fa-outdent', 708 => 'fa fa-paperclip', 709 => 'fa fa-paragraph', 710 => 'fa fa-paste', 711 => 'fa fa-repeat', 712 => 'fa fa-rotate-left', 713 => 'fa fa-rotate-right', 714 => 'fa fa-save', 715 => 'fa fa-scissors', 716 => 'fa fa-strikethrough', 717 => 'fa fa-subscript', 718 => 'fa fa-superscript', 719 => 'fa fa-table', 720 => 'fa fa-text-height', 721 => 'fa fa-text-width', 722 => 'fa fa-th', 723 => 'fa fa-th-large', 724 => 'fa fa-th-list', 725 => 'fa fa-underline', 726 => 'fa fa-undo', 727 => 'fa fa-unlink', 728 => 'fa fa-angle-double-down', 729 => 'fa fa-angle-double-left', 730 => 'fa fa-angle-double-right', 731 => 'fa fa-angle-double-up', 732 => 'fa fa-angle-down', 733 => 'fa fa-angle-left', 734 => 'fa fa-angle-right', 735 => 'fa fa-angle-up', 736 => 'fa fa-arrow-circle-down', 737 => 'fa fa-arrow-circle-left', 738 => 'fa fa-arrow-circle-o-down', 739 => 'fa fa-arrow-circle-o-left', 740 => 'fa fa-arrow-circle-o-right', 741 => 'fa fa-arrow-circle-o-up', 742 => 'fa fa-arrow-circle-right', 743 => 'fa fa-arrow-circle-up', 744 => 'fa fa-arrow-down', 745 => 'fa fa-arrow-left', 746 => 'fa fa-arrow-right', 747 => 'fa fa-arrow-up', 748 => 'fa fa-arrows', 749 => 'fa fa-arrows-alt', 750 => 'fa fa-arrows-h', 751 => 'fa fa-arrows-v', 752 => 'fa fa-caret-down', 753 => 'fa fa-caret-left', 754 => 'fa fa-caret-right', 755 => 'fa fa-caret-square-o-down', 756 => 'fa fa-caret-square-o-left', 757 => 'fa fa-caret-square-o-right', 758 => 'fa fa-caret-square-o-up', 759 => 'fa fa-caret-up', 760 => 'fa fa-chevron-circle-down', 761 => 'fa fa-chevron-circle-left', 762 => 'fa fa-chevron-circle-right', 763 => 'fa fa-chevron-circle-up', 764 => 'fa fa-chevron-down', 765 => 'fa fa-chevron-left', 766 => 'fa fa-chevron-right', 767 => 'fa fa-chevron-up', 768 => 'fa fa-exchange', 769 => 'fa fa-hand-o-down', 770 => 'fa fa-hand-o-left', 771 => 'fa fa-hand-o-right', 772 => 'fa fa-hand-o-up', 773 => 'fa fa-long-arrow-down', 774 => 'fa fa-long-arrow-left', 775 => 'fa fa-long-arrow-right', 776 => 'fa fa-long-arrow-up', 777 => 'fa fa-toggle-down', 778 => 'fa fa-toggle-left', 779 => 'fa fa-toggle-right', 780 => 'fa fa-toggle-up', 781 => 'fa fa-arrows-alt', 782 => 'fa fa-backward', 783 => 'fa fa-compress', 784 => 'fa fa-eject', 785 => 'fa fa-expand', 786 => 'fa fa-fast-backward', 787 => 'fa fa-fast-forward', 788 => 'fa fa-forward', 789 => 'fa fa-pause', 790 => 'fa fa-pause-circle', 791 => 'fa fa-pause-circle-o', 792 => 'fa fa-play', 793 => 'fa fa-play-circle', 794 => 'fa fa-play-circle-o', 795 => 'fa fa-random', 796 => 'fa fa-step-backward', 797 => 'fa fa-step-forward', 798 => 'fa fa-stop', 799 => 'fa fa-stop-circle', 800 => 'fa fa-stop-circle-o', 801 => 'fa fa-youtube-play', 802 => 'fa fa-500px', 803 => 'fa fa-adn', 804 => 'fa fa-amazon', 805 => 'fa fa-android', 806 => 'fa fa-angellist', 807 => 'fa fa-apple', 808 => 'fa fa-bandcamp', 809 => 'fa fa-behance', 810 => 'fa fa-behance-square', 811 => 'fa fa-bitbucket', 812 => 'fa fa-bitbucket-square', 813 => 'fa fa-bitcoin', 814 => 'fa fa-black-tie', 815 => 'fa fa-bluetooth', 816 => 'fa fa-bluetooth-b', 817 => 'fa fa-btc', 818 => 'fa fa-buysellads', 819 => 'fa fa-cc-amex', 820 => 'fa fa-cc-diners-club', 821 => 'fa fa-cc-discover', 822 => 'fa fa-cc-jcb', 823 => 'fa fa-cc-mastercard', 824 => 'fa fa-cc-paypal', 825 => 'fa fa-cc-stripe', 826 => 'fa fa-cc-visa', 827 => 'fa fa-chrome', 828 => 'fa fa-codepen', 829 => 'fa fa-codiepie', 830 => 'fa fa-connectdevelop', 831 => 'fa fa-contao', 832 => 'fa fa-css3', 833 => 'fa fa-dashcube', 834 => 'fa fa-delicious', 835 => 'fa fa-deviantart', 836 => 'fa fa-digg', 837 => 'fa fa-dribbble', 838 => 'fa fa-dropbox', 839 => 'fa fa-drupal', 840 => 'fa fa-edge', 841 => 'fa fa-eercast', 842 => 'fa fa-empire', 843 => 'fa fa-envira', 844 => 'fa fa-etsy', 845 => 'fa fa-expeditedssl', 846 => 'fa fa-fa', 847 => 'fa fa-facebook', 848 => 'fa fa-facebook-f', 849 => 'fa fa-facebook-official', 850 => 'fa fa-facebook-square', 851 => 'fa fa-firefox', 852 => 'fa fa-first-order', 853 => 'fa fa-flickr', 854 => 'fa fa-font-awesome', 855 => 'fa fa-fonticons', 856 => 'fa fa-fort-awesome', 857 => 'fa fa-forumbee', 858 => 'fa fa-foursquare', 859 => 'fa fa-free-code-camp', 860 => 'fa fa-ge', 861 => 'fa fa-get-pocket', 862 => 'fa fa-gg', 863 => 'fa fa-gg-circle', 864 => 'fa fa-git', 865 => 'fa fa-git-square', 866 => 'fa fa-github', 867 => 'fa fa-github-alt', 868 => 'fa fa-github-square', 869 => 'fa fa-gitlab', 870 => 'fa fa-gittip', 871 => 'fa fa-glide', 872 => 'fa fa-glide-g', 873 => 'fa fa-google', 874 => 'fa fa-google-plus', 875 => 'fa fa-google-plus-circle', 876 => 'fa fa-google-plus-official', 877 => 'fa fa-google-plus-square', 878 => 'fa fa-google-wallet', 879 => 'fa fa-gratipay', 880 => 'fa fa-grav', 881 => 'fa fa-hacker-news', 882 => 'fa fa-houzz', 883 => 'fa fa-html5', 884 => 'fa fa-imdb', 885 => 'fab fa-instagram', 886 => 'fa fa-internet-explorer', 887 => 'fa fa-ioxhost', 888 => 'fa fa-joomla', 889 => 'fa fa-jsfiddle', 890 => 'fa fa-lastfm', 891 => 'fa fa-lastfm-square', 892 => 'fa fa-leanpub', 893 => 'fab fa-linkedin', 894 => 'fa fa-linkedin-square', 895 => 'fa fa-linode', 896 => 'fa fa-linux', 897 => 'fa fa-maxcdn', 898 => 'fa fa-meanpath', 899 => 'fa fa-medium', 900 => 'fa fa-meetup', 901 => 'fa fa-mixcloud', 902 => 'fa fa-modx', 903 => 'fa fa-odnoklassniki', 904 => 'fa fa-odnoklassniki-square', 905 => 'fa fa-opencart', 906 => 'fa fa-openid', 907 => 'fa fa-opera', 908 => 'fa fa-optin-monster', 909 => 'fa fa-pagelines', 910 => 'fa fa-paypal', 911 => 'fa fa-pied-piper', 912 => 'fa fa-pied-piper-alt', 913 => 'fa fa-pied-piper-pp', 914 => 'fa fa-pinterest', 915 => 'fa fa-pinterest-p', 916 => 'fa fa-pinterest-square', 917 => 'fa fa-product-hunt', 918 => 'fa fa-qq', 919 => 'fa fa-quora', 920 => 'fa fa-ra', 921 => 'fa fa-ravelry', 922 => 'fa fa-rebel', 923 => 'fa fa-reddit', 924 => 'fa fa-reddit-alien', 925 => 'fa fa-reddit-square', 926 => 'fa fa-renren', 927 => 'fa fa-resistance', 928 => 'fa fa-safari', 929 => 'fa fa-scribd', 930 => 'fa fa-sellsy', 931 => 'fa fa-share-alt', 932 => 'fa fa-share-alt-square', 933 => 'fa fa-shirtsinbulk', 934 => 'fa fa-simplybuilt', 935 => 'fa fa-skyatlas', 936 => 'fa fa-skype', 937 => 'fa fa-slack', 938 => 'fa fa-slideshare', 939 => 'fa fa-snapchat', 940 => 'fa fa-snapchat-ghost', 941 => 'fa fa-snapchat-square', 942 => 'fa fa-soundcloud', 943 => 'fa fa-spotify', 944 => 'fa fa-stack-exchange', 945 => 'fa fa-stack-overflow', 946 => 'fa fa-steam', 947 => 'fa fa-steam-square', 948 => 'fa fa-stumbleupon', 949 => 'fa fa-stumbleupon-circle', 950 => 'fa fa-superpowers', 951 => 'fa fa-telegram', 952 => 'fa fa-tencent-weibo', 953 => 'fa fa-themeisle', 954 => 'fa fa-trello', 955 => 'fa fa-tripadvisor', 956 => 'fa fa-tumblr', 957 => 'fa fa-tumblr-square', 958 => 'fa fa-twitch', 959 => 'fa fa-twitter', 960 => 'fa fa-twitter-square', 961 => 'fa fa-usb', 962 => 'fa fa-viacoin', 963 => 'fa fa-viadeo', 964 => 'fa fa-viadeo-square', 965 => 'fa fa-vimeo', 966 => 'fa fa-vimeo-square', 967 => 'fa fa-vine', 968 => 'fa fa-vk', 969 => 'fa fa-wechat', 970 => 'fa fa-weibo', 971 => 'fa fa-weixin', 972 => 'fa fa-whatsapp', 973 => 'fa fa-wikipedia-w', 974 => 'fa fa-windows', 975 => 'fa fa-wordpress', 976 => 'fa fa-wpbeginner', 977 => 'fa fa-wpexplorer', 978 => 'fa fa-wpforms', 979 => 'fa fa-xing', 980 => 'fa fa-xing-square', 981 => 'fa fa-y-combinator', 982 => 'fa fa-y-combinator-square', 983 => 'fa fa-yahoo', 984 => 'fa fa-yc', 985 => 'fa fa-yc-square', 986 => 'fa fa-yelp', 987 => 'fa fa-yoast', 988 => 'fa fa-youtube', 989 => 'fa fa-youtube-play', 990 => 'fa fa-youtube-square', 991 => 'fa fa-warning', 992 => 'fa fa-ambulance', 993 => 'fa fa-h-square', 994 => 'fa fa-heart', 995 => 'fa fa-heart-o', 996 => 'fa fa-heartbeat', 997 => 'fa fa-hospital-o', 998 => 'fa fa-medkit', 999 => 'fa fa-plus-square', 1000 => 'fa fa-stethoscope', 1001 => 'fa fa-user-md', 1002 => 'fa fa-wheelchair', 1003 => 'fa fa-wheelchair-alt', 1004 => 'fa fa-flag', 1005 => 'fa fa-maxcdn', 1006 => 'fa fa-times');
if (is_null($_GET['type'])) {
    if (!is_null($_SESSION['dil'])) {
        $db->where("url", $_SESSION['dil']);
        $lang = $db->getOne("langs");
        if (count($lang) > 0) {
            $_SESSION['dil'] = mb_strtolower($lang['url']);
            $_SESSION['dilID'] = $lang['id'];
        } else {
            $db->where("aktif", 1);
            $setting = $db->getOne("settings");

            $db->where("id", $setting['langID']);
            $lang = $db->getOne("langs");

            $_SESSION['dil'] = mb_strtolower($lang['url']);
            $_SESSION['dilID'] = $lang['id'];
        }
    } else {
        $db->where("url", $security->getLang());
        $lang = $db->getOne("langs");
        if (count($lang) > 0) {
            $_SESSION['dil'] = mb_strtolower($lang['url']);
            $_SESSION['dilID'] = $lang['id'];
        } else {
            $db->where("aktif", 1);
            $setting = $db->getOne("settings");

            $db->where("id", $setting['langID']);
            $lang = $db->getOne("langs");

            $_SESSION['dil'] = mb_strtolower($lang['url']);
            $_SESSION['dilID'] = $lang['id'];
        }
    }
} else {
    $db->where("url", $_GET['type']);
    $lang = $db->getOne("langs");
    if (count($lang) > 0) {
        $_SESSION['dil'] = mb_strtolower($lang['url']);
        $_SESSION['dilID'] = $lang['id'];
    } else {
        $db->where("aktif", 1);
        $setting = $db->getOne("settings");

        $db->where("id", $setting['langID']);
        $lang = $db->getOne("langs");

       // $_SESSION['dil'] = mb_strtolower($lang['url']);
        //$_SESSION['dilID'] = $lang['id'];
    }
}
//echo json_encode($_SESSION)."--";
$lower = mb_strtolower($_SESSION['dil']);
$upper = mb_strtoupper($_SESSION['dil']);
setlocale(LC_ALL, "" . $lower . "_" . $upper . ".UTF8");

$db->where("langID", $_SESSION['dilID']);
$setting = $db->getOne("settings");

$db->where("id", $setting['langID']);
$langSql = $db->getOne("langs");

$db->where("aktif", 1);
$theme = $db->getOne("theme");

define("LANGID", $langSql['id']);
define("THEMEDIR", "bwp-content/themes/" . $theme['themeDir'] . "/");
define("BWPUP", "bwp-content/uploads/");
define("UPLOADIR", THEMEDIR . "uploads/");
define("THEMEIMG", THEMEDIR . "assets/img/");
define("THEMECSS", THEMEDIR . "assets/css/");
define("THEMEJS", THEMEDIR . "assets/js/");
define("THEMESKINS", THEMEDIR . "assets/skins/");
define("THEMEADMIN", THEMEDIR . "themeAdmin/");

?>
