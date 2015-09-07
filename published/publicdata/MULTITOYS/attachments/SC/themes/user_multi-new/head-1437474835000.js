var preview = $(".preview");
var quantity = $(".cart_product_quantity");
var scrollPane = $(".scroll-pane1");
var productLists = $(".cpt_product_lists");
var myCart = $("#my__cart");
var content = $("#content");
function RealMarginLi() {
    var idCenter = $("#center");
    var product_topview_area_width = idCenter.width();
    var centerLi = idCenter.find("ul li");
    var li_width = centerLi.width() + 6;
    var liNum = Math.floor(product_topview_area_width / li_width);
    var margin = Math.round((product_topview_area_width - li_width * liNum) / (liNum + 1));
    centerLi.css({"margin-left": margin, "margin-bottom": margin});
}
function RealResizeCatalog() {
    var header_height = $("#header").height() + $(".header").height() + 5;
    var maincontent_height = $(window).height() - header_height;
    var columns_height = $(document).height() - header_height;
    var delta = $(".product_brief_head").height() + $(".navigator").height();
    scrollPane.height(maincontent_height - delta);
    $("#columns").height(columns_height);
    productLists.height(columns_height);
}
function ResizeCatalog() {
    scrollPane.height(1);
    $("#columns").height(1);
    productLists.height(1);
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
    myCart.html('<div style="float:right"><p style="font-size:14px;line-height:37px;color:white">Загрузка товаров...</p></div>');
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
    myCart.html('<div style="float:right"><p style="font-size:14px;line-height:37px;color:white">Загрузка товаров...</p></div>');
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
    RealMarginLi();
    ResizeCatalog();
    $(window).resize(function () {
        RealMarginLi();
        setTimeout(ResizeCatalog, 300);
    });
    $("[name=add2cart]").click(function () {
        var id = $(this).attr("data-id");
        $("[data-id=" + id + "]").val("0");
    });
    if (preview.tooltip) {
        preview.tooltip({
            delay: 0, bodyHandler: function () {
                return $("<img/>").attr("src", $(this).attr("data-pid"));
            }
        });
    }
    if (quantity.spin) {
        quantity.spin({min: 0, max: 999});
    }
    scrollPane.niceScroll({cursorcolor: "coral", cursoropacitymin: 1, cursorwidth: 8});
    productLists.niceScroll({cursorcolor: "coral", horizrailenabled: false, cursoropacitymin: 1, cursorwidth: 3});
    var notProgress = true;
    scrollPane.scroll(function () {
        if (scrollPane.scrollTop() + scrollPane.height() >= content.height() && notProgress) {
            var btn_more = $("#light-pagination");
            var itemsOnPage = parseInt(btn_more.attr("data-itemsOnPage"));
            var items = parseInt(btn_more.attr("data-items"));
            var show = parseInt(btn_more.attr("data-show"));
            var page = parseInt(btn_more.attr("data-page"));
            if (page == -1) {
                page = 0;
            }
            var add = parseInt(btn_more.attr("data-add"));
            var sort = btn_more.attr("data-sort");
            var direction = btn_more.attr("data-direction");
            var count_show = page + show + add;
            if ((show + add) < itemsOnPage && count_show <= items) {
                notProgress = false;
                scrollPane.addClass("loader");
                $.ajax({
                    url: "/auxpage_new_items/",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    data: {count_show: count_show, sort: sort, direction: direction},
                    success: function (data) {
                        if (data.result == "success") {
                            content.append(data.html);
                            btn_more.attr("data-show", count_show - page);
                            $(".cart_product_quantity").siblings().remove();
                            $(".cart_product_quantity").spin({min: 0, max: 999});
                            $(".preview").tooltip({
                                delay: 0, showURL: false, bodyHandler: function () {
                                    return $("<img/>").attr("src", $(this).attr("data-pid"));
                                }
                            });
                        } else {
                            if (data.result == "finish") {
                            }
                        }
                        scrollPane.removeClass("loader");
                        scrollPane.getNiceScroll(0).doScrollLeft(0, 100);
                        notProgress = true;
                    }
                });
            }
        }
    });
    var pageNavigator = $("#light-pagination");
    pageNavigator.pagination({
        items: parseInt(pageNavigator.attr("data-items")),
        itemsOnPage: parseInt(pageNavigator.attr("data-itemsOnPage")),
        cssStyle: "compact-theme",
        prevText: "&larr;",
        nextText: "&rarr;",
        hrefTextPrefix: location.href + "#page-",
        onPageClick: function (pageNumber, event) {
            var itemsOnPage = pageNavigator.attr("data-itemsOnPage");
            var p = (pageNumber - 1) * itemsOnPage;
            var page = "#page-" + (pageNumber + 1);
            if (pageNumber == 1) {
                p = -1;
            }
            location.href = location.href.replace(/#page-(\d+)/, "") + page;
            var sort = pageNavigator.attr("data-sort");
            var direction = pageNavigator.attr("data-direction");
            var url = "/auxpage_new_items/";
            content.addClass("smoke");
            scrollPane.addClass("loader");
            if (notProgress) {
                notProgress = false;
                $.ajax({
                    url: url,
                    type: "post",
                    dataType: "json",
                    cache: false,
                    data: {p: p, sort: sort, direction: direction},
                    success: function (data) {
                        if (data.result == "success") {
                            content.html(data.html);
                            pageNavigator.attr("data-show", 0);
                            pageNavigator.attr("data-page", p);
                            scrollPane.removeClass("loader");
                            content.removeClass("smoke");
                            $(".cart_product_quantity").spin({min: 0, max: 999});
                            $(".preview").tooltip({
                                delay: 0, showURL: false, bodyHandler: function () {
                                    return $("<img/>").attr("src", $(this).attr("data-pid"));
                                }
                            });
                            notProgress = true;
                        }
                    }
                });
            }
            scrollPane.getNiceScroll(0).doScrollTop(0, 1000);
            productLists.getNiceScroll(0).doScrollTop(0, 1000);
        }
    });
    load_cart();
});
function _changeCurrency() {
    document.ChangeCurrencyForm.submit();
}
function throttle(func, ms) {
    var isThrottled = false, savedArgs, savedThis;

    function wrapper() {
        if (isThrottled) {
            savedArgs = arguments;
            savedThis = this;
            return;
        }
        func.apply(this, arguments);
        isThrottled = true;
        setTimeout(function () {
            isThrottled = false;
            if (savedArgs) {
                wrapper.apply(savedThis, savedArgs);
                savedArgs = savedThis = null;
            }
        }, ms);
    }

    return wrapper;
}
$(function () {
    var searchOk = $("#search_ok");
    $("#searchstring").keyup(throttle(function () {
        searchOk.addClass("search_loader");
        var live_search = $("#live_search");
        var search;
        search = $("#searchstring").val();
        if (search.length > 2) {
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
            return searchOk.removeClass("search_loader");
        } else {
            live_search.html("");
            if (!search.length) {
                searchOk.removeClass("search_loader");
            }
        }
    }, 1500));
});
function changePic(id, direction) {
    var element = document.getElementById("pic" + id);
    var picNums = Number(element.getAttribute("data-pics"));
    var currPic = Number(element.getAttribute("data-current"));
    var newPic;
    var endOfSrc;
    if (direction > 0) {
        if (currPic < picNums) {
            newPic = currPic + direction;
            endOfSrc = "-" + newPic + "_thm.jpg";
        } else {
            newPic = 0;
            endOfSrc = "_thm.jpg";
        }
    } else {
        if (currPic > 1) {
            newPic = currPic + direction;
            endOfSrc = "-" + newPic + "_thm.jpg";
        } else {
            if (currPic == 1) {
                newPic = 0;
                endOfSrc = "_thm.jpg";
            } else {
                newPic = picNums;
                endOfSrc = "-" + newPic + "_thm.jpg";
            }
        }
    }
    var startOfSrc = "/published/publicdata/MULTITOYS/attachments/SC/products_pictures/";
    element.setAttribute("src", startOfSrc + id + endOfSrc);
    element.setAttribute("data-current", newPic);
}/*

 scrollUp v1.1.0
 Author: Mark Goodyear - http://www.markgoodyear.com
 Git: https://github.com/markgoodyear/scrollup

 Copyright 2013 Mark Goodyear
 Licensed under the MIT license
 http://www.opensource.org/licenses/mit-license.php

 Twitter: @markgdyr

 */

(function($) {

    $.scrollUp = function (options) {

        // Defaults
        var defaults = {
            scrollName: 'scrollUp', // Element ID
            topDistance: 300, // Distance from top before showing element (px)
            topSpeed: 300, // Speed back to top (ms)
            animation: 'fade', // Fade, slide, none
            animationInSpeed: 200, // Animation in speed (ms)
            animationOutSpeed: 200, // Animation out speed (ms)
            scrollText: 'Scroll to top', // Text for element
            scrollImg: false, // Set true to use image
            activeOverlay: false // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        };

        var o = $.extend({}, defaults, options),
            scrollId = '#' + o.scrollName;

        // Create element
        $('<a/>', {
            id: o.scrollName,
            href: '#top',
            title: o.scrollText
        }).appendTo('body');

        // If not using an image display text
        if (!o.scrollImg) {
            $(scrollId).text(o.scrollText);
        }

        // Minium CSS to make the magic happen
        $(scrollId).css({'display':'none','position': 'fixed','z-index': '2147483647'});

        // Active point overlay
        if (o.activeOverlay) {
            $("body").append("<div id='"+ o.scrollName +"-active'></div>");
            $(scrollId+"-active").css({ 'position': 'absolute', 'top': o.topDistance+'px', 'width': '100%', 'border-top': '1px dotted '+o.activeOverlay, 'z-index': '2147483647' });
        }

        // Scroll function
        $(window).scroll(function(){
            switch (o.animation) {
                case "fade":
                    $( ($(window).scrollTop() > o.topDistance) ? $(scrollId).fadeIn(o.animationInSpeed) : $(scrollId).fadeOut(o.animationOutSpeed) );
                    break;
                case "slide":
                    $( ($(window).scrollTop() > o.topDistance) ? $(scrollId).slideDown(o.animationInSpeed) : $(scrollId).slideUp(o.animationOutSpeed) );
                    break;
                default:
                    $( ($(window).scrollTop() > o.topDistance) ? $(scrollId).show(0) : $(scrollId).hide(0) );
            }
        });

        // To the top
        $(scrollId).click( function(event) {
            $('html, body').animate({scrollTop:0}, o.topSpeed);
            event.preventDefault();
        });

    };
})(jQuery);