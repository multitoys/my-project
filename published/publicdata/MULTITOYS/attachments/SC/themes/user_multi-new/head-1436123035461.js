function RealResizeCatalog() {
    var window_height = $(window).height();
    var document_height = $(document).height();
    var header_id_height = $("#header").height();
    var header_class_height = $(".header").height();
    var maincontent_height = window_height - header_id_height.height() - header_class_height.height() - 5;
    var columns_height = document_height - header_id_height.height() - header_class_height.height() - 5;
    var delta = $(".product_brief_head").height() + $(".navigator").height();
    $("#columns").height(columns_height);
    $(".scroll-pane1").height(maincontent_height - delta);
    $(".cpt_product_lists").height(columns_height);
}
function RealMarginLi() {
    var idCenter = $("#center");
    var product_topview_area_width = idCenter.width();
    var centerLi = idCenter.find("ul li");
    var li_width = centerLi.width() + 6;
    var liNum = Math.floor(product_topview_area_width / li_width);
    var margin = Math.round((product_topview_area_width - li_width * liNum) / (liNum + 1));
    centerLi.css({"margin-left": margin, "margin-bottom": margin});
}
function ResizeCatalog() {
    $("#columns").height(1);
    $(".scroll-pane1").height(1);
    $(".cpt_product_lists").height(1);
    setTimeout(RealResizeCatalog(), 500);
}
function zakcia(seconds) {
    var _date = new Date();
    _date.setSeconds(seconds);
    $("#z_counter").countdown({image: "/img/_digits.png", startTime: _date});
}
function load_cart() {
    $("#my__cart").load("/popup/show_cart.php");
}
function update_cient_info(id, qt) {
    var zpid = $("#zpid_" + id);
    var old_val = Number(zpid.text());
    var new_val = old_val + Number(qt);
    zpid.html('<div class="animated fadeInDownBig">' + new_val + "</div>");
}
function add_all2cart() {
    $("#my__cart").html('<div style="float:right"><p style="font-size:14px;line-height:37px;color:white">Загрузка товаров...</p></div>');
    $("[name=product_qty]").each(function () {
        var id = $(this).attr("data-id");
        var qt = $(this).val();
        var query = "?ukey=cart&view=noframe&action=add_product&force=yes&productID=" + id + "&product_qty=" + qt;
        if (qt > 0) {
            $.ajax({
                type: "GET", url: query, dataType: "html", async: true, success: function () {
                    update_cient_info(id, qt);
                }
            });
            $(this).val("");
        }
    });
    setTimeout("load_cart();", 300);
}
function add_2cart(who) {
    $("#my__cart").html('<div style="float:right"><p style="font-size:14px;line-height:37px;color:white">Загрузка товаров...</p></div>');
    $(who).each(function () {
        var id = $(this).attr("data-id");
        var qt = $(this).val();
        if (qt == "") {
            qt = 1;
        }
        var query = "?ukey=cart&view=noframe&action=add_product&force=yes&productID=" + id + "&product_qty=" + qt;
        if (qt > 0) {
            $.ajax({
                type: "GET", url: query, dataType: "html", async: false, success: function () {
                    update_cient_info(id, qt);
                }
            });
        }
    });
    $(who).val("");
    setTimeout("load_cart();", 250);
}
$(function () {
    $.scrollUp({
        scrollName: "scrollUp",
        topDistance: "200",
        topSpeed: 1000,
        animation: "fade",
        animationInSpeed: 1000,
        animationOutSpeed: 1000,
        scrollText: "Наверх",
        activeOverlay: false
    });
});
$(document).ready(function () {
    ResizeCatalog();
    RealMarginLi();
    load_cart();
    var preview = $(".preview");
    var quantity = $(".cart_product_quantity");
    $(window).resize(function () {
        setTimeout(ResizeCatalog, 300);
        RealMarginLi();
    });
    $("[name=add2cart]").click(function () {
        var id = $(this).attr("data-id");
        $("[data-id=" + id + "]").val("0");
    });
    if (preview.tooltip) {
        preview.tooltip({
            delay: 0, showURL: false, bodyHandler: function () {
                return $("<img/>").attr("src", $(this).attr("data-pid"));
            }
        });
    }
    if (quantity.spin) {
        quantity.spin({min: 0, max: 999});
    }
    $(".cpt_product_lists").niceScroll({
        cursorcolor: "coral",
        cursorborderradius: 2,
        horizrailenabled: false,
        cursoropacitymin: 0.5
    });
    $(".scroll-pane1").niceScroll({
        cursorcolor: "coral",
        cursorborderradius: 3,
        cursoropacitymin: 0.5,
        horizrailenabled: true,
        scrollspeed: 40,
        mousescrollstep: 20,
        smoothscroll: false,
        cursorwidth: 9
    });
    $("#show_more").click(function () {
        var btn_more = $(this);
        var count_show = parseInt($(this).attr("data-show"));
        var count_add = parseInt($(this).attr("data-add"));
        var sort = $(this).attr("data-sort");
        var direction = $(this).attr("data-direction");
        var url = $(this).attr("data-url");
        btn_more.val("Подождите...");
        $.ajax({
            url: url,
            type: "post",
            dataType: "json",
            data: {count_show: count_show, count_add: count_add, sort: sort, direction: direction},
            success: function (data) {
                if (data.result == "success") {
                    $("#content").append(data.html);
                    $(".scroll-pane1").getNiceScroll().resize();
                    btn_more.val("Показать еще " + count_add + " товаров");
                    btn_more.attr("data-show", (count_show + count_add));
                    if (url == "/auxpage_new_items/") {
                        quantity.siblings().remove();
                        quantity.spin({min: 0, max: 999});
                        preview.tooltip({
                            delay: 0, showURL: false, bodyHandler: function () {
                                return $("<img/>").attr("src", $(this).attr("data-pid"));
                            }
                        });
                    }
                } else {
                    btn_more.val("Больше нечего показывать");
                }
            }
        });
    });
});
function _changeCurrency() {
    document.ChangeCurrencyForm.submit();
}
$(function () {
    $("#searchstring").keyup(function () {
        var live_search = $("#live_search");
        var search;
        search = $("#searchstring").val();
        if (search.length > 2) {
            setTimeout(function () {
                $.ajax({
                    type: "POST",
                    url: "/popup/search.php",
                    data: {search: search},
                    cache: false,
                    success: function (response) {
                        live_search.html(response);
                        live_search.niceScroll({
                            cursorcolor: "#03A9F4",
                            cursorborderradius: 2,
                            cursorbordercolor: "#03A9F4",
                            horizrailenabled: false,
                            cursoropacitymin: 1,
                            cursorwidth: 5
                        });
                    }
                });
            }, 1000);
            return false;
        } else {
            setTimeout(function () {
                $("#live_search").html("");
            }, 500);
        }
    });
});