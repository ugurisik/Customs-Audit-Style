

<?php
session_start();
?>
<script>
// document.addEventListener("DOMContentLoaded", function(event) { 
//     let password = prompt("Lütfen şifre giriniz:","***");
//     if (password!="nictr") {
//         document.body.remove("html");
//         alert("Hatalı Şifreden Dolayı Site Yüklenemedi!");
//     }
// });
</script>
<?php
include 'bwp-includes/settings.php';
echo "<meta name='description' content='".$setting['aciklama']."' />";
if (!isset($_GET['type'])) {
    $stiphome = 'index';
} else {
    $db->where("url", $_GET['type']);
    $pageView = $db->getOne("page");
    if ($db->count > 0) {
        $stiphome = $pageView['template'];
    } else {
        $stiphome = 'index';
    }
}
switch ($stiphome) {
    case 'contact':
        include THEMEDIR . '/contact.php';
        break;
    case 'post':
        include THEMEDIR . '/post.php';
        break;
    case 'kategori':
        include THEMEDIR . '/cat.php';
        break;
    case 'index':
        include THEMEDIR . '/index.php';
        break;
    case 'hakkimizda':
        include THEMEDIR . '/hakkimizda.php';
        break;
    case 'hizmetler':
        include THEMEDIR . '/hizmetler.php';
        break;
    case 'hizmetcat':
        include THEMEDIR . '/hizmetcat.php';
        break;
    case 'hizmetpost':
        include THEMEDIR . '/hizmetpost.php';
        break;
    case 'egitimler':
        include THEMEDIR . '/egitimler.php';
        break;
    case 'egitimcat':
        include THEMEDIR . '/egitimcat.php';
        break;
    case 'egitimpost':
        include THEMEDIR . '/egitimpost.php';
        break;
    case 'basvuruyap':
        include THEMEDIR . '/basvuru.php';
        break;
    case 'ara':
        include THEMEDIR . '/sorular.php';
        break;
    default:
        include THEMEDIR . '/index.php';
}
