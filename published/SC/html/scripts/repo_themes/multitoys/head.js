// JavaScript Document

function RealResizeCatalog() {
    var window_height = $(window).height();

    var maincontent_height = window_height - $('#top').height() - $('.top2').height() - $('#mainmenu').height() - $('#bottom').height() - 2;

    var search_height = $('#searchstring10').height();
    var predloj_height = $('.predloj').height() + 25;


    var catalog_height = maincontent_height - search_height - predloj_height;
    var delta = 0;

    if (catalog_height < 100) {
        delta = 0;
        catalog_height = 100;
        maincontent_height = catalog_height + search_height + predloj_height;
    }

//    $('#debug').html('m='+maincontent_height+' w='+window_height+' bottom='+$('#bottom').height());

    $('#main_content').height(maincontent_height + delta);
    $('.scroll-pane').height(catalog_height + delta);
    $('.scroll-pane1').height(maincontent_height + delta - 20);
    $('.novinki').height(maincontent_height - 23 + delta);
}

function ResizeCatalog() {
    if (inlog) {
        $('.scroll-pane').height(1);
        $('.scroll-pane1').height(1);
        $('.novinki').height(1);

        setTimeout('RealResizeCatalog();', 50);
    }
}

$(document).ready(function () {
    ResizeCatalog();
    $(window).resize(function () {
        setTimeout(ResizeCatalog, 500);
    });

    $('.product_qty').spin({
        min: 0,
        max: 9999
    });

    $('.preview').tooltip({
        delay: 0,
        showURL: false,
        bodyHandler: function () {
            return $("<img/>").attr("src", $(this).attr('pid'));
        }
    });

    $('[name=add2cart]').click(function () {
        var id = $(this).attr('pid');
        $('[pid=' + id + ']').val('0');
    });
});


function add_all2cart() {
    $('#my__cart').html('Загрузка товаров...');
    $('.product_qty').each(function () {
        $('.addall').hide();
        setTimeout("$('.addall').show();", 2000);
        var id = $(this).attr('pid');
        var qt = $(this).val();
        var query = '?ukey=cart&view=noframe&action=add_product&force=yes&productID=' + id + '&product_qty=' + qt;
        if (qt > 0) {
            $.ajax({
                type: "GET",
                url: query,
                dataType: "html",
                async: false
            });
        }
    });
    $('.product_qty').val('');
    load_cart();
}

function add_2cart(who) {
    $('#my__cart').html('Загрузка товаров...');
    $(who).each(function () {
        var id = $(this).attr('pid');
        var qt = $(this).val();

        var query = '/popup/is_in_cart.php?id=' + id;
        if (qt > 0) {
            $.ajax({
                type: "GET",
                url: query,
                dataType: "html",
                async: false,
                success: function (data) {
                    if ((data == 0) || (confirm('Эта позиция уже есть в заказе. Добавить количество в корзину?'))) {
                        var query = '?ukey=cart&view=noframe&action=add_product&force=yes&productID=' + id + '&product_qty=' + qt;
                        if (qt > 0) {
                            $.ajax({
                                type: "GET",
                                url: query,
                                dataType: "html",
                                async: false
                            });
                        }
                    }
                }
            });
        }
    });
    $(who).val('');
    load_cart();
}

/**
 *
 * @access public
 * @return void
 **/
function changePic(a, b) {
    alert(a);
}