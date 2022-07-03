<?php
session_start();
include '../bwp-includes/settings.php';
if ($_SESSION['loggedin'] == true) {
    $db->where("email", $_SESSION["email"]);
    $userKont = $db->getOne("users");
    if ($userKont['loginID'] == $_SESSION['loginid']) {
        $pageTitle = "Tüm Başvurular";
        $mpage = "content";
        $maltpage = "basvuru";
        $mlink = "basvurular";
        $process = $_GET['process'];
        $processTitle = "Başvuruları Göster";
?>
        <!DOCTYPE html>
        <html lang="tr">
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

        <head>
            <meta charset="utf-8" />
            <title><?php echo $pageTitle; ?> - <?php echo $panelTitle; ?></title>
            <meta name="description" content="No subheader example">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
            <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
            <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
            <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        </head>

        <body class="kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-aside--enabled kt-aside--fixed kt-page--loading">
            <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
                <div class="kt-header-mobile__logo">
                    <a href="index.html">
                        <img alt="Logo" src="assets/media/logos/logo-12.png" />
                    </a>
                </div>
                <div class="kt-header-mobile__toolbar">
                    <button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
                    <button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
                    <button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
                </div>
            </div>
            <div class="kt-grid kt-grid--hor kt-grid--root">
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
                    <?php include 'inc/menu.php'; ?>
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                        <?php include 'inc/header.php'; ?>
                        <div class="kt-content kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                            <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="kt-portlet kt-portlet--mobile">
                                            <div class="kt-portlet__head kt-portlet__head--lg">
                                                <div class="kt-portlet__head-label">
                                                    <span class="kt-portlet__head-icon">
                                                        <i class="kt-font-brand fa fa-newspaper"></i>
                                                    </span>
                                                    <h3 class="kt-portlet__head-title"><?php echo $processTitle; ?></h3>
                                                </div>
                                                <div class="kt-portlet__head-toolbar">
                                                    <div class="kt-portlet__head-wrapper">
                                                        <div class="kt-portlet__head-actions">
                                                            <div class="input-group float-left"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="kt-portlet__body tablediv">
                                                <div class="alert alert-solid-danger alert-bold d-none" role="alert">
                                                    <div class="alert-icon">
                                                        <i class="flaticon-warning"></i>
                                                    </div>
                                                    <div class="alert-text">
                                                        <h4 class="alert-heading"></h4>
                                                        <p></p>
                                                    </div>
                                                </div>
                                                <button onclick="exportData()">
                                                    <i class="kt-font-brand fa fa-download"></i>
                                                    Listeyi İndir
                                                </button>
                                                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Başvuru Alanı</th>
                                                            <th>Başvuru Tarihi</th>
                                                            <th>İsim Soyisim</th>
                                                            <th>Ünvan</th>
                                                            <th>Firma Adı</th>
                                                            <th>E-Posta</th>
                                                            <th>Telefon</th>
                                                            <th>Mesaj</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php include 'inc/footer.php'; ?>
                    </div>
                </div>
            </div>
            <div id="kt_scrolltop" class="kt-scrolltop">
                <i class="fa fa-arrow-up"></i>
            </div>
            <script src="assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
            <script src="assets/js/scripts.bundle.js" type="text/javascript"></script>
            <script src="assets/js/main.js" type="text/javascript"></script>
            <script src="assets/plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
            <script>
                function yetkikont(process) {
                    $.ajax({
                        url: "../bwp-includes/ajax.php?i=yetki&process=" + process + "",
                        success: function(e) {
                            var obj = jQuery.parseJSON(e);
                            if (obj.type == "success") {} else {
                                var notify = $.notify({
                                    title: obj.title,
                                    message: obj.message
                                }, {
                                    type: obj.type
                                });
                            }
                        }
                    });
                }

                function table() {
                    var pdata = new Array();
                    pdata.push({
                        name: "basvuruList",
                        value: "true"
                    });
                    $.ajax({
                        url: '../bwp-includes/ajax.php?i=basvurugoster&process=view',
                        type: 'POST',
                        data: pdata,
                        success: function(e) {
                            console.log(e);
                            var obj = jQuery.parseJSON(e);
                            console.log(obj);
                            if (obj.notify.type == "danger") {
                                $(".tablediv .alert").removeClass("d-none");
                                $(".tablediv .alert .alert-text h4").html(obj.notify.title);
                                $(".tablediv .alert .alert-text p").html(obj.notify.message);
                                $(".tablediv table").addClass("d-none");
                            } else if (obj.notify.type == "success") {
                                $(".tablediv .alert").addClass("d-none");
                                $(".tablediv table").removeClass("d-none");
                                $('.tablediv table').DataTable({
                                    data: obj.data,
                                    searching: false,
                                    info: false,
                                    responsive: true,
                                    displayLength: 50,
                                    language: {
                                        url: "assets/plugins/custom/datatables/Turkish.json"
                                    },
                                    dom: 'Bfrtip',
                                    buttons: [
                                        'copy', 'csv', 'excel', 'pdf', 'print'
                                    ]
                                });
                            }
                        }
                    });
                }
                jQuery(document).ready(function() {
                    $("select").select2();
                    yetkikont("postview");
                    table();
                });
                $(document).on("click", "button[name=set]", function() {
                    var id = $(this).data("id");
                    var type = $(this).data("type");
                    pageset(id, type);
                });


                function exportData() {
                    /* Get the HTML data using Element by Id */
                    var table = document.getElementById("kt_table_1");

                    /* Declaring array variable */
                    var rows = [];

                    //iterate through rows of table
                    for (var i = 0, row; row = table.rows[i]; i++) {
                        //rows would be accessed using the "row" variable assigned in the for loop
                        //Get each cell value/column from the row
                        column1 = row.cells[0].innerText;
                        column2 = row.cells[1].innerText;
                        column3 = row.cells[2].innerText;
                        column4 = row.cells[3].innerText;
                        column5 = row.cells[4].innerText;
                        column6 = row.cells[5].innerText;
                        column7 = row.cells[6].innerText;
                        column8 = row.cells[7].innerText;
                        column9 = row.cells[8].innerText;
                        /* add a new records in the array */
                        rows.push(
                            [
                                column1,
                                column2,
                                column3,
                                column4,
                                column5,
                                column6,
                                column7,
                                column8,
                                column9
                            ]
                        );

                    }
                    csvContent = "data:text/csv;charset=utf-8,";
                    /* add the column delimiter as comma(,) and each row splitted by new line character (\n) */
                    rows.forEach(function(rowArray) {
                        row = rowArray.join(",");
                        csvContent += row + "\r\n";
                    });

                    /* create a hidden <a> DOM node and set its download attribute */
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "Stock_Price_Report.csv");
                    document.body.appendChild(link);
                    /* download the data file named "Stock_Price_Report.csv" */
                    link.click();
                }
            </script>
        </body>

        </html>
<?php
    }
} else {
    echo '<meta http-equiv="refresh" content="0;URL=index.php">';
}
?>