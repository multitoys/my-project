// // JavaScript Document

// function RealResizeCatalog (){
// var window_height        = $(window).height();

// // var maincontent_height = window_height - $('#top').height() - $('.top2').height() - $('#mainmenu').height() - $('#bottom').height() ;
// var maincontent_height = window_height - $('#top').height() - $('.top2').height() - $('#mainmenu').height() - $('.catalog').height()-$('#cat_path').height()-$('.faq').height()-$('#cat_info_left_block').height() ;

// var search_height      = $('#searchstring10').height();
// var predloj_height     = $('.predloj').height()+25;


// // var catalog_height     = maincontent_height - search_height - predloj_height;
// var catalog_height     = $('.cpt_maincolumns').height();
// var delta = 20;

// if (catalog_height < 100)
// {
// delta = 0;
// catalog_height = 100;
// maincontent_height = catalog_height + search_height + predloj_height;
// }

// //    $('#debug').html('m='+maincontent_height+' w='+window_height+' bottom='+$('#bottom').height());

// $('#main_content').height(maincontent_height);
// // $('.scroll-pane' ).height(catalog_height+delta);
// $('.scroll-pane1').height(maincontent_height-delta);
// // $('.novinki'     ).height(maincontent_height-10+delta);
// $('.cpt_product_lists').height(catalog_height);
// }

// function ResizeCatalog()
// {
// /*  if (inlog) */
// /* { */
// // $('.scroll-pane' ).height(1);
// $('.scroll-pane1').height(1);
// $('.cpt_product_lists'     ).height(1);

// setTimeout('RealResizeCatalog();', 50);
// /*  } */
// }

// $(document).ready(function()
// {
// ResizeCatalog();
// $(window).resize(function(){
// setTimeout(ResizeCatalog, 100);
// });

// // $('.product_qty').spin({
// //   min:0,
// //   max:9999
// // });

// // $('.preview').tooltip({
// //   delay: 0,
// //   showURL: false,
// //   bodyHandler: function()
// //   {
// //       return $("<img/>").attr("src", $(this).attr('pid'));
// //   }
// // });

// $('[name=add2cart]').click(function(){
// var id=$(this).attr('pid');
// $('[pid='+id+']').val('0');
// });
// });


// function add_all2cart(){
// $('#my__cart').html('Загрузка товаров...');
// $('[name=product_qty]').each(function(){
// //$('.addall').hide();
// //setTimeout("$('.addall').show();", 2000);
// var id = $(this).attr('pid');
// var qt = $(this).val();

// var query = '?ukey=cart&view=noframe&action=add_product&force=yes&productID='+id+'&product_qty='+qt;
// if (qt > 0)
// {
// $.ajax({
// type: "GET",
// url: query,
// dataType: "html",
// async: true,
// success: function(data) {
// update_cient_info(id, qt);
// }
// });
// $(this).val('');
// }
// });
// //$('.product_qty').val('');
// load_cart();
// }

// function add_2cart(who)
// {
// $('#my__cart').html('Загрузка товаров...');
// $(who).each(function(){
// var id = $(this).attr('pid');
// var qt = $(this).val();
// if(qt=='') qt=1;
// var query = '/popup/is_in_cart.php?id='+id;
// if (qt > 0)
// {
// $.ajax({
// type: "GET",
// url: query,
// dataType: "html",
// async: false,
// success: function(data)
// {
// if ((data == 0) || (confirm('Эта позиция уже есть в заказе. Добавить количество в корзину?')))
// {
// var query = '?ukey=cart&view=noframe&action=add_product&force=yes&productID='+id+'&product_qty='+qt;
// if (qt > 0)
// {
// $.ajax({
// type: "GET",
// url: query,
// dataType: "html",
// async: false,
// success: function(data) {
// update_cient_info(id, qt);
// }
// });
// }
// }
// }
// });
// }
// });
// $(who).val('');
// load_cart();
// }

// /**
// *
// * @access public
// * @return void
// **/
// function changePic(a,b){
// alert(a);
// }

// function update_cient_info(id, qt) {
// var old_val = Number($('#zpid_'+id).html());
// var new_val = old_val + Number(qt);
// $('#zpid_'+id).html(new_val);
// }

// JavaScript Document

function RealResizeCatalog() {
    var window_height = $(window).height();

    // var maincontent_height = window_height - $('#top').height() - $('.top2').height() - $('#mainmenu').height() - $('#bottom').height() ;
    var maincontent_height = window_height - $('#header').height() - $('.header').height();
    var cpt_product_lists_height = window_height - $('#top').height() - $('.top2').height() - $('#mainmenu').height();

    // var search_height      = $('#searchstring10').height();
    // var predloj_height     = $('.predloj').height()+25;


    // var catalog_height     = maincontent_height - search_height - predloj_height;
    // var catalog_height     = $('.cpt_maincolumns').height();
    // var delta = 28;
    var delta = 65;

    // if (catalog_height < 100)
    // {
    // delta = 0;
    // catalog_height = 100;
    // maincontent_height = catalog_height + search_height + predloj_height;
    // }

//    $('#debug').html('m='+maincontent_height+' w='+window_height+' bottom='+$('#bottom').height());
// if (catalog_height > cpt_product_lists_height)
    // {
    // cpt_product_lists_height = catalog_height;
    // }

    cpt_product_lists_height = $(window).height() - 45;
    catalog_height = cpt_product_lists_height;

    $('#main_content').height(maincontent_height);
    // $('.scroll-pane' ).height(catalog_height+delta);
    $('.scroll-pane1').height(maincontent_height - delta);
    // $('.novinki'     ).height(maincontent_height-10+delta);
    $('.cpt_product_lists').height(maincontent_height);
    $('.cpt_maincolumns').height(maincontent_height);
}

function ResizeCatalog() {
    /*  if (inlog) */
    /* { */
    $('#main_content').height(1);
    $('.scroll-pane1').height(1);
    // $('.cpt_product_lists'     ).height(1);
    $('.cpt_maincolumns').height(1);

    setTimeout('RealResizeCatalog();', 50);
    /*  } */
}

$(document).ready(function () {
    ResizeCatalog();
    $(window).resize(function () {
        setTimeout(ResizeCatalog, 100);
    });

    // $('.product_qty').spin({
    //   min:0,
    //   max:9999
    // });

    // $('.preview').tooltip({
    //   delay: 0,
    //   showURL: false,
    //   bodyHandler: function()
    //   {
    //       return $("<img/>").attr("src", $(this).attr('pid'));
    //   }
    // });

    $('[name=add2cart]').click(function () {
        var id = $(this).attr('pid');
        $('[pid=' + id + ']').val('0');
    });
});


function add_all2cart() {
    $('#my__cart').html('Загрузка товаров...');
    $('[name=product_qty]').each(function () {
        //$('.addall').hide();
        //setTimeout("$('.addall').show();", 2000);
        var id = $(this).attr('pid');
        var qt = $(this).val();

        var query = '?ukey=cart&view=noframe&action=add_product&force=yes&productID=' + id + '&product_qty=' + qt;
        if (qt > 0) {
            $.ajax({
                type: "GET",
                url: query,
                dataType: "html",
                async: true,
                success: function (data) {
                    update_cient_info(id, qt);
                }
            });
            $(this).val('');
        }
    });
    //$('.product_qty').val('');
    load_cart();
}

function add_2cart(who) {
    $('#my__cart').html('Загрузка товаров...');
    $(who).each(function () {
        var id = $(this).attr('pid');
        var qt = $(this).val();
        if (qt == '') qt = 1;
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
                                async: false,
                                success: function (data) {
                                    update_cient_info(id, qt);
                                }
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

function update_cient_info(id, qt) {
    var old_val = Number($('#zpid_' + id).html());
    var new_val = old_val + Number(qt);
    $('#zpid_' + id).html(new_val);
}