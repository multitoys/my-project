/**
 * Created by multi on 16.03.2016.
 */
var mainScroll,
    listScroll,
    searchCache = [];

window.onload = function() {
   
    baron({
        root: 'body'
    });
    
    baron({
        root: '#live_search',
        scroller: '.search_res',
        bar: '.baron__bar',
        scrollingCls: '_scrolling'
    }).controls({
        track: '.baron__track'
    });
};

$(document).ready(function () {
        
    var /*slideMenu = $('#slidemenu'),*/
        notProgress = true,
        content = $("#content"),
        scrollPane = $(".scroll-pane1"),
        // productLists = $(".cpt_product_lists"),
        preview = $(".preview"),
        controls = $(".controls"),
        quantity = $(".cart_product_quantity"),
        // menuWraperHeight,
        cuttedPath;

    $('#slidemenu1').multiSlideMenu({
        autoHeightMenu: true,
        //backOnTop: true,
        scrollToTopSpeed: 200,
        slideSpeed: 300,
        backLinkContent: 'Назад',
        loadContainer: "#center",
        loadOnlyLatest: true,
        afterLoadDone: function() {
            var preview = $(".preview"),
                controls = $(".controls"),
                quantity = $(".cart_product_quantity");
            try {
                mainScroll = baron({
                    root: '.scroll-pane1',
                    scroller: '#content',
                    bar: '.baron__bar',
                    scrollingCls: '_scrolling'
                }).autoUpdate().controls({
                    track: '.baron__track'
                });
            } catch (e) {
                console.log( e.stack );
            }
            resizeCatalog();

            if (preview.tooltip) {
                preview.tooltip({
                    delay: 0, bodyHandler: function () {
                        return $("<img/>").attr("src", $(this).attr("data-pid"));
                    }
                });
            }
            if (controls.tooltip) {
                controls.tooltip({
                    delay: 1, bodyHandler: function () {
                        return $("<img/>").attr("src", $(this).attr("data-pid"));
                    }
                });
            }
            if (quantity.spin) {
                quantity.spin({min: 0, max: 999});
            }
            $('a.page-link').click(navigation);
        }
    });
    
    $('#slidemenu0').multiSlideMenu({
        autoHeightMenu: true,
        //backOnTop: true,
        scrollToTopSpeed: 200,
        slideSpeed: 300,
        backLinkContent: 'Назад',
        loadContainer: "html",
        afterLoadDone: function() {
            try {
                mainScroll = baron({
                    root: '.scroll-pane1',
                    scroller: '#content',
                    bar: '.baron__bar',
                    scrollingCls: '_scrolling'
                }).autoUpdate().controls({
                    track: '.baron__track'
                });
            } catch (e) {
                console.log( e.stack );
            }
            resizeCatalog();
            $('a.page-link').click(navigation);
        }
    });
    // slideMenu.height(1);

    // menuWraperHeight = $(".multiSlideMenu-wrapper").height();
    // slideMenu.height(menuWraperHeight);
    
    loadCart();
    resizeCatalog();
    
    if (window.location.pathname == "/") {
        realMarginLi();
    } else {
        if (preview.tooltip) {
            preview.tooltip({
                delay: 0, bodyHandler: function () {
                    return $("<img/>").attr("src", $(this).attr("data-pid"));
                }
            });
        }
        if (controls.tooltip) {
            controls.tooltip({
                delay: 1, bodyHandler: function () {
                    return $("<img/>").attr("src", $(this).attr("data-pid"));
                }
            });
        }
        if (quantity.spin) {
            quantity.spin({min: 0, max: 999});
        }
        $('a.page-link').click(navigation);
    }

    
    
    cuttedPath = window.location.pathname.split('/');
    
    switch (cuttedPath[1]) {

        case 'cart':
            break;
        case 'category':
        case 'search':
        case 'auxpage_new_items':
            try {
                mainScroll = baron({
                    root: '.scroll-pane1',
                    scroller: '#content',
                    bar: '.baron__bar',
                    scrollingCls: '_scrolling'
                }).autoUpdate().controls({
                    track: '.baron__track'
                });
            } catch (e) {
                console.log(e.stack);
            }
        case 'product':
        default:
            try {
                listScroll = baron({
                    root: '.cpt_maincolumns',
                    scroller: '.cpt_product_lists',
                    bar: '.baron__bar',
                    scrollingCls: '_scrolling'
                }).autoUpdate().controls({
                    track: '.baron__track'
                });
            } catch (e) {
                console.log(e.stack);
            }

            break;
    }
    
    $(window).resize(function () {
        if (window.location.pathname == "/") {
            // realMarginLi();
            setTimeout(realMarginLi, 300);
        }
         setTimeout(resizeCatalog, 300);
    });
    
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
    
    
////////////////////////////////////////////////////////
    $('.js_login_block .js_btn_close').on('click',function(){
        $(this).parents('.js_login_block').hide(300);
    });
    $('#link_login').on('click', function(){
        var login_block = $('#log2div');
        if (login_block.css('display') === 'none') {
            // if ($('#login_form').css('display') === 'none') {
            //     $('#remind1_form').css('display', 'none');
            //     $('#remind2_form').css('display', 'none');
            //     $('#remind3_form').css('display', 'none');
            //     $('#login_form').css('display', 'block');
            // }
            login_block.show(300);
        }
        else {
            login_block.hide(300);
        }
        return false;
    });

    // if( $('#login_form').length ) {
    //     $("#login_form").submit(function(e) {
    //         var postData = $(this).serialize();
    //         var formURL = $(this).attr("action");
    //         $.ajax({
    //             url: formURL,
    //             type: "POST",
    //             data: postData,
    //             dataType: "JSON",
    //             beforeSend: function(){
    //                 $(".js_submit_reg").attr('disabled', 'disabled');
    //             },
    //             success: function( rsp ){
    //                 if (rsp.status === 'ok') {
    //                     $('.js_login_block').hide(300, function() {
    //                         location.href = $("#after_login_url").text();
    //                     });
    //                 }
    //                 else {
    //                     $('.js_phone_field').parent().addClass('error');
    //                     $('.js_phone_field').parent().find('.txt_error').text(rsp.msg);
    //                 }
    //             },
    //             complete: function( ){
    //                 $(".js_submit_reg").removeAttr('disabled');
    //             }
    //         });
    //         return false; // avoid to execute the actual submit of the form.
    //     });
    // }
    //
    // if( $('#remind1_form').length ) {
    //     $("#remind1_form").submit(function(e) {
    //         var postData = $(this).serialize();
    //         var formURL = $(this).attr("action");
    //         $.ajax({
    //             url: formURL,
    //             type: "POST",
    //             data: postData,
    //             dataType: "JSON",
    //             beforeSend: function(){
    //                 $(".js_submit_reg").attr('disabled', 'disabled');
    //             },
    //             success: function( rsp ){
    //                 if (rsp.status === 'ok') {
    //                     $('#remind1_form').hide(300, function() {
    //                         $('#remind2_form').show(300);
    //                     });
    //                 }
    //                 else {
    //                     $('.js_remind_field').parent().addClass('error');
    //                     $('.js_remind_field').parent().find('.txt_error').text(rsp.msg);
    //                 }
    //             },
    //             complete: function( ){
    //                 $(".js_submit_reg").removeAttr('disabled');
    //             }
    //         });
    //         return false; // avoid to execute the actual submit of the form.
    //     });
    // }
////////////////////////////////////////////////////////
    
    
    // scrollPane.scroll(function () {
    //     if (scrollPane.scrollTop() + scrollPane.height() >= content.height() - 200 && notProgress) {
    //     var btnMore = $("#light-pagination"),
    //         itemsOnPage = parseInt(btnMore.attr("data-itemsOnPage")),
    //         items = parseInt(btnMore.attr("data-items")),
    //         show = parseInt(btnMore.attr("data-show")),
    //         page = parseInt(btnMore.attr("data-page")),
    //         date = parseInt(btnMore.attr("data-date")),
    //         made = btnMore.attr("data-made");
    //         if (page == -1) {
    //             page = 0;
    //         }
    //     var add = parseInt(btnMore.attr("data-add"));
    //     var sort = btnMore.attr("data-sort");
    //     var direction = btnMore.attr("data-direction");
    //         var countShow = page + show + add;
    //         if ((show + add) < itemsOnPage && countShow <= items) {
    //             notProgress = false;
    //             content.addClass("smoke");
    //             scrollPane.addClass("loader");
    //             $.ajax({
    //                 url: "/auxpage_new_items/" + date + "/" + made + "/",
    //                 type: "post",
    //                 dataType: "json",
    //                 cache: false,
    //                 data: {count_show: countShow, sort: sort, direction: direction, date: date, made: made},
    //                 success: function (data) {
    //                     if (data.result == "success") {
    //                         content.append(data.html);
    //                     btnMore.attr("data-show", countShow - page);
    //                     var cartQuantity = $(".cart_product_quantity");
    //                     cartQuantity.siblings().remove();
    //                     cartQuantity.spin({min: 0, max: 999});
    //                         $(".preview").tooltip({
    //                             delay: 0, showURL: false, bodyHandler: function () {
    //                                 return $("<img/>").attr("src", $(this).attr("data-pid"));
    //                             }
    //                         });
    //                         $(".controls").tooltip({
    //                             delay: 0, showURL: false, bodyHandler: function () {
    //                                 return $("<img/>").attr("src", $(this).attr("data-pid"));
    //                             }
    //                         });
    //                     } else {
    //                         if (data.result == "finish") {
    //                         }
    //                     }
    //                     content.removeClass("smoke");
    //                     scrollPane.removeClass("loader");
    //                     notProgress = true;
    //                 }
    //             });
    //         }
    //     }
    // });

    // slideMenu.multiSlideMenu({
    //     // autoHeightMenu: true,
    //     scrollToTopSpeed: 200,
    //     loadContainer: "#center",
    //     afterLoadDone: function() {
    //         var preview = $(".preview"),
    //             controls = $(".controls"),
    //             quantity = $(".cart_product_quantity");
    //         try {
    //             mainScroll = baron({
    //                 root: '.scroll-pane1',
    //                 scroller: '#content',
    //                 bar: '.baron__bar',
    //                 scrollingCls: '_scrolling'
    //             }).autoUpdate().controls({
    //                 track: '.baron__track'
    //             });
    //         } catch (e) {
    //             console.log( e.stack );
    //         }
    //         resizeCatalog();
    //
    //         if (preview.tooltip) {
    //             preview.tooltip({
    //                 delay: 0, bodyHandler: function () {
    //                     return $("<img/>").attr("src", $(this).attr("data-pid"));
    //                 }
    //             });
    //         }
    //         if (controls.tooltip) {
    //             controls.tooltip({
    //                 delay: 1, bodyHandler: function () {
    //                     return $("<img/>").attr("src", $(this).attr("data-pid"));
    //                 }
    //             });
    //         }
    //         if (quantity.spin) {
    //             quantity.spin({min: 0, max: 999});
    //         }
    //         $('a.page-link').click(navigation);
    //     }
    // });
    
    $(window).bind('popstate', function () {
        $.ajax({
            url: location.pathname + '?ajax=1',
            success: function (data) {
                $('#center').html(data);
            }
        });
    });
    
    /**
     * DocumentReadyFunctions
     */
    function realMarginLi() {
        var idCenter = $("#center").find(".product_topview_area"),
            productTopviewAreaWidth = idCenter.width(),
            centerLi = idCenter.find("ul li"),
            liWidth = centerLi.width() + 6,
            liNum = Math.floor(productTopviewAreaWidth / liWidth),
            margin = Math.round((productTopviewAreaWidth - liWidth * liNum) / (liNum + 1));
        centerLi.css({"margin-left": margin, "margin-bottom": margin});
    }
    function resizeCatalog() {
        var scrollPane = $(".scroll-pane1"),
            productLists = $(".cpt_product_lists");
        cuttedPath = window.location.pathname.split('/');
        if (cuttedPath[1] !== "" && cuttedPath[1] !== "cart") {
            scrollPane.height(1);
            $("#content").height(1);
        }

        
        $("#columns").height(1);
        productLists.height(1);

        setTimeout(realResizeCatalog, 100);
    }
    function realResizeCatalog() {
        var headerHeight = $("#header").height() + $(".header").height() + 8,
            maincontentHeight = $(window).height() - headerHeight,
            delta = $(".product_brief_head").height() + $(".navigator").height(),
            baronFree = $("#content").next(),
            scrollPane = $(".scroll-pane1"),
            mainColumnsHeight = $(".cpt_maincolumns").height(),
            docHeight =  $(document).height() - headerHeight,
            centerHeight = $("#center").height(),
            columns = $("#columns"),
            columnsHeight;
        cuttedPath = window.location.pathname.split('/');

            
        if (cuttedPath[1] == "category" || cuttedPath[1] == "auxpage_new_items" || cuttedPath[1] == "search") {
            scrollPane.height(maincontentHeight - delta);
            $("#content").height(maincontentHeight - delta);
            baronFree.css({"top": delta, "height": maincontentHeight - delta});
            try {
                mainScroll.update();
            } catch (e) {
                console.log(e.stack);
            }
        }

        if (scrollPane.length) {
            columns.height((mainColumnsHeight > docHeight)?mainColumnsHeight:docHeight);
        } else {
            centerHeight = ((maincontentHeight - delta) > centerHeight)?(maincontentHeight - delta):centerHeight;
            columns.height((mainColumnsHeight > centerHeight)?mainColumnsHeight:centerHeight);
        }
        columnsHeight = columns.height();
        
        if (cuttedPath[1] != "cart") {
            $(".cpt_product_lists").height(columnsHeight);
            listScroll.update();
        }
    }
    
    function navigation() {
        var url = $(this).attr('href'),
            scrollPaine = $(".scroll-pane1"),
            glue = '?';
        cuttedPath = window.location.pathname.split('/');
        if (cuttedPath[1] != "category") {
            glue = '&';
        }
        $('#center').addClass("smoke");
        scrollPaine.addClass("loader");
        $(".scroll-pane1").baron().dispose();
        $.ajax({
            url: url + glue +'ajax=1',
            success: function (data) {
                $('#center').html(data).removeClass("smoke");
                var preview = $(".preview"),
                    controls = $(".controls"),
                    quantity = $(".cart_product_quantity");
                scrollPaine.removeClass("loader");
                try {
                    mainScroll = baron({
                        root: '.scroll-pane1',
                        scroller: '#content',
                        bar: '.baron__bar',
                        scrollingCls: '_scrolling'
                    }).autoUpdate().controls({
                        track: '.baron__track'
                    });
                } catch (e) {
                    console.log( e.stack );
                }
                $('a.page-link').click(navigation);

                preview.tooltip({
                    delay: 0, bodyHandler: function () {
                        return $("<img/>").attr("src", $(this).attr("data-pid"));
                    }
                });

                controls.tooltip({
                    delay: 1, bodyHandler: function () {
                        return $("<img/>").attr("src", $(this).attr("data-pid"));
                    }
                });

                quantity.spin({min: 0, max: 999});

                resizeCatalog();
            }
        });
        if (url != window.location) {
            window.history.pushState(null, null, url);
        }
        return false;
    }
});

/**
 * Functions
 */
function strpos( haystack, needle, offset) {
    var i = haystack.indexOf(needle, offset); // returns -1
    return i >= 0 ? i : false;
}

function explode(delimiter, string) {	
    var emptyArray = { 0: '' };

    if ( arguments.length != 2
        || typeof arguments[0] == 'undefined'
        || typeof arguments[1] == 'undefined' )
    {
        return null;
    }

    if ( delimiter === ''
        || delimiter === false
        || delimiter === null )
    {
        return false;
    }

    if ( typeof delimiter == 'function'
        || typeof delimiter == 'object'
        || typeof string == 'function'
        || typeof string == 'object' )
    {
        return emptyArray;
    }

    if ( delimiter === true ) {
        delimiter = '1';
    }

    return string.toString().split ( delimiter.toString() );
}

function zakcia(seconds) {
    var startDate = new Date();
    startDate.setSeconds(seconds);
    $("#z_counter").countdown({image: "/img/_digits.png", startTime: startDate});
}

function loadCart() {
    $("#my__cart").load("/popup/show_cart.php");
}

function updateClientInfo(id, qt) {
    var zpid = $("#zpid_" + id);
    var oldVal = Number(zpid.text());
    var newVal = oldVal + Number(qt);
    zpid.html('<div class="animated fadeInDownBig">' + newVal + "</div>");
}

function addAll2Cart() {
    var myCart = $("#my__cart");
    myCart.html('<div style="float:right"><p style="font-size:14px;line-height:37px;color:white">Загрузка товаров...</p></div>');
    $("[name=product_qty]").each(function () {
        var id = $(this).attr("data-id");
        var qt = $(this).val();
        var query = "?ukey=cart&view=noframe&action=add_product&force=yes&productID=" + id + "&product_qty=" + qt;
        if (qt > 0) {
            $.ajax({
                type: "GET", url: query, dataType: "html", async: true, success: function () {
                    updateClientInfo(id, qt);
                }
            });
            $(this).val("");
        }
    });
    setTimeout(loadCart, 300);
}

function add2Cart(who) {
    var myCart = $("#my__cart");
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
                type: "GET", url: query, dataType: "html", async: true, success: function () {
                    updateClientInfo(id, qt);
                }
            });
        }
    });
    $(who).val("");
    setTimeout(loadCart, 250);
}

function _changeCurrency() {
    document.ChangeCurrencyForm.submit();
}

function changePic(id, direction) {
    var pic = "pic";
    if (id < 10000) {
        id = "0" + id;
    }
    var element = document.getElementById("pic" + id);
    var picNums = Number(element.getAttribute("data-pics"));
    var currPic = Number(element.getAttribute("data-current"));
    var startOfSrc = "/pictures/";
    var newPic;
    var endOfSrc;
    var ext = "_thm.jpg";
    var extDiv = ".jpg";
    if (direction > 0) {
        if (currPic < picNums) {
            newPic = currPic + direction;
            endOfSrc = "-" + newPic;
        } else {
            newPic = 0;
            endOfSrc = "";
        }
    } else {
        if (currPic > 1) {
            newPic = currPic + direction;
            endOfSrc = "-" + newPic;
        } else {
            if (currPic == 1) {
                newPic = 0;
                endOfSrc = "";
            } else {
                newPic = picNums;
                endOfSrc = "-" + newPic;
            }
        }
    }
    element.setAttribute("src", startOfSrc + id + endOfSrc + ext);
    element.setAttribute("data-current", newPic);
    var cotrolDiv = element.previousElementSibling;
    cotrolDiv.setAttribute("data-pid", startOfSrc + id + endOfSrc + extDiv);
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
    var
        searchOk = $("#search_ok"),
        liveSearch = $(".container"),
        searchString = $("#searchstring");

    searchString.keyup(throttle(function () {
        var search = searchString.val();
        searchOk.addClass("search_loader");
        if (search.length > 2) {
            if (searchCache[search]) {
                liveSearch.html(searchCache[search]);
            } else {
                $.ajax({
                    type: "POST",
                    url: "/popup/search.php",
                    data: {search: search},
                    cache: true,
                    success: function (response) {
                        liveSearch.html(response);
                        searchCache[search] = response;
                    }
                });
            }
            return searchOk.removeClass("search_loader");
        } else {
            liveSearch.html("");
            if (!search.length) {
                searchOk.removeClass("search_loader");
            }
        }
    }, 1500));
});

/**
 * Plugins
 */
(function ($) {
    $.scrollUp = function (options) {
        var defaults = {
            scrollName: "scrollUp",
            topDistance: 300,
            topSpeed: 300,
            animation: "fade",
            animationInSpeed: 200,
            animationOutSpeed: 200,
            scrollText: "Scroll to top",
            scrollImg: false,
            activeOverlay: false
        };
        var o = $.extend({}, defaults, options), scrollId = "#" + o.scrollName;
        $("<a/>", {id: o.scrollName, href: "#top", title: o.scrollText}).appendTo("body");
        if (!o.scrollImg) {
            $(scrollId).text(o.scrollText);
        }
        $(scrollId).css({display: "none", position: "fixed", "z-index": "2147483647"});
        if (o.activeOverlay) {
            $("body").append("<div id='" + o.scrollName + "-active'></div>");
            $(scrollId + "-active").css({
                position: "absolute",
                top: o.topDistance + "px",
                width: "100%",
                "border-top": "1px dotted " + o.activeOverlay,
                "z-index": "2147483647"
            });
        }
        $(window).scroll(function () {
            switch (o.animation) {
                case"fade":
                    $(($(window).scrollTop() > o.topDistance) ? $(scrollId).fadeIn(o.animationInSpeed) : $(scrollId).fadeOut(o.animationOutSpeed));
                    break;
                case"slide":
                    $(($(window).scrollTop() > o.topDistance) ? $(scrollId).slideDown(o.animationInSpeed) : $(scrollId).slideUp(o.animationOutSpeed));
                    break;
                default:
                    $(($(window).scrollTop() > o.topDistance) ? $(scrollId).show(0) : $(scrollId).hide(0));
            }
        });
        $(scrollId).click(function (event) {
            $("html, body").animate({scrollTop: 0}, o.topSpeed);
            event.preventDefault();
        });
    };
})(jQuery);

(function ($) {
    var helper = {}, current, title, tID, track = false;
    $.tooltip = {
        blocked: false,
        defaults: {delay: 200, fade: false, top: 150, left: 100, id: "tooltip"},
        block: function () {
            $.tooltip.blocked = !$.tooltip.blocked;
        }
    };
    $.fn.extend({
        tooltip: function (settings) {
            settings = $.extend({}, $.tooltip.defaults, settings);
            createHelper(settings);
            return this.each(function () {
                $.data(this, "tooltip", settings);
                this.tOpacity = helper.parent.css("opacity");
                this.tooltipText = this.title;
                $(this).removeAttr("title");
                this.alt = "";
            }).mouseover(save).mouseout(hide).click(hide);
        }
    });
    function createHelper(settings) {
        if (helper.parent) {
            return;
        }
        helper.parent = $('<div id="' + settings.id + '"><div class="body"></div>').appendTo(document.body).hide();
        helper.title = $("h3", helper.parent);
        helper.body = $("div.body", helper.parent);
    }

    function settings(element) {
        return $.data(element, "tooltip");
    }

    function handle(event) {
        if (settings(this).delay) {
            tID = setTimeout(show, settings(this).delay);
        } else {
            show();
        }
        track = !!settings(this).track;
        $(document.body).bind("mousemove", update);
        update(event);
    }

    function save() {
        if ($.tooltip.blocked || this == current || (!this.tooltipText && !settings(this).bodyHandler)) {
            return;
        }
        current = this;
        title = this.tooltipText;
        if (settings(this).bodyHandler) {
            helper.title.hide();
            var bodyContent = settings(this).bodyHandler.call(this);
            if (bodyContent.nodeType || bodyContent.jquery) {
                helper.body.empty().append(bodyContent);
            } else {
                helper.body.html(bodyContent);
            }
            helper.body.show();
        } else {
            if (settings(this).showBody) {
                var parts = title.split(settings(this).showBody);
                helper.title.html(parts.shift()).show();
                helper.body.empty();
                for (var i = 0, part; (part = parts[i]); i++) {
                    if (i > 0) {
                        helper.body.append("<br/>");
                    }
                    helper.body.append(part);
                }
                helper.body.hideWhenEmpty();
            } else {
                helper.title.html(title).show();
                helper.body.hide();
            }
        }
        handle.apply(this, arguments);
    }

    function show() {
        tID = null;
        if (settings(current).fade) {
            if (helper.parent.is(":animated")) {
                helper.parent.stop().show().fadeTo(settings(current).fade, current.tOpacity);
            } else {
                helper.parent.is(":visible") ? helper.parent.fadeTo(settings(current).fade, current.tOpacity) : helper.parent.fadeIn(settings(current).fade);
            }
        } else {
            helper.parent.show();
        }
        update();
    }

    function update(event) {
        if ($.tooltip.blocked) {
            return;
        }
        if (event && event.target.tagName == "OPTION") {
            return;
        }
        if (!track && helper.parent.is(":visible")) {
            $(document.body).unbind("mousemove", update);
        }
        if (current == null) {
            $(document.body).unbind("mousemove", update);
            return;
        }
        helper.parent.removeClass("viewport-right").removeClass("viewport-bottom");
        var left = helper.parent[0].offsetLeft;
        var top = helper.parent[0].offsetTop;
        if (event) {
            left = event.pageX + settings(current).left;
            top = event.pageY - settings(current).top;
            var right = "auto";
            if (settings(current).positionLeft) {
                right = $(window).width() - left;
                left = "auto";
            }
            helper.parent.css({left: left, right: right, top: top});
        }
        var v = viewport(), h = helper.parent[0];
        if (v.x + v.cx < h.offsetLeft + h.offsetWidth) {
            left -= h.offsetWidth + 20 + settings(current).left;
            helper.parent.css({left: left + "px"}).addClass("viewport-right");
        }
        if (v.y + v.cy < h.offsetTop + h.offsetHeight) {
            top -= h.offsetHeight + 120 - settings(current).top;
            helper.parent.css({top: top + "px"}).addClass("viewport-bottom");
        }
    }

    function viewport() {
        return {x: $(window).scrollLeft(), y: $(window).scrollTop(), cx: $(window).width(), cy: $(window).height()};
    }

    function hide(event) {
        if ($.tooltip.blocked) {
            return;
        }
        if (tID) {
            clearTimeout(tID);
        }
        current = null;
        var tsettings = settings(this);

        function complete() {
            helper.parent.hide().css("opacity", "");
        }

        if (tsettings.fade) {
            if (helper.parent.is(":animated")) {
                helper.parent.stop().fadeTo(tsettings.fade, 0, complete);
            } else {
                helper.parent.stop().fadeOut(tsettings.fade, complete);
            }
        } else {
            complete();
        }
    }
})(jQuery);

(function ($) {
    var calcFloat = {
        get: function (num) {
            var num = num.toString();
            if (num.indexOf(".") == -1) {
                return [0, eval(num)];
            }
            var nn = num.split(".");
            var po = nn[1].length;
            var st = nn.join("");
            var sign = "";
            if (st.charAt(0) == "-") {
                st = st.substr(1);
                sign = "-";
            }
            for (var i = 0; i < st.length; ++i) {
                if (st.charAt(0) == "0") {
                    st = st.substr(1, st.length);
                }
            }
            st = sign + st;
            return [po, eval(st)];
        }, getInt: function (num, figure) {
            var d = Math.pow(10, figure);
            var n = this.get(num);
            var v1 = eval("num * d");
            var v2 = eval("n[1] * d");
            if (this.get(v1)[1] == v2) {
                return v1;
            }
            return (n[0] == 0 ? v1 : eval(v2 + "/Math.pow(10, n[0])"));
        }, sum: function (v1, v2) {
            var n1 = this.get(v1);
            var n2 = this.get(v2);
            var figure = (n1[0] > n2[0] ? n1[0] : n2[0]);
            v1 = this.getInt(v1, figure);
            v2 = this.getInt(v2, figure);
            return eval("v1 + v2") / Math.pow(10, figure);
        }
    };
    $.extend({
        spin: {
            imageBasePath: "/img/spin/",
            spinBtnImage: "spin-button.png",
            spinUpImage: "spin-up.png",
            spinDownImage: "spin-down.png",
            interval: 1,
            max: null,
            min: null,
            timeInterval: 500,
            timeBlink: 200,
            btnClass: null,
            btnCss: {cursor: "pointer"},
            txtCss: {marginRight: 0, paddingRight: 0},
            lock: false,
            decimal: null,
            beforeChange: null,
            changed: null,
            buttonUp: null,
            buttonDown: null
        }
    });
    $.fn.extend({
        spin: function (o) {
            return this.each(function () {
                o = o || {};
                var opt = {};
                $.each($.spin, function (k, v) {
                    opt[k] = (typeof o[k] != "undefined" ? o[k] : v);
                });
                var txt = $(this);
                var spinBtnImage = opt.imageBasePath + opt.spinBtnImage;
                var btnSpin = new Image();
                btnSpin.src = spinBtnImage;
                var spinUpImage = opt.imageBasePath + opt.spinUpImage;
                var btnSpinUp = new Image();
                btnSpinUp.src = spinUpImage;
                var spinDownImage = opt.imageBasePath + opt.spinDownImage;
                var btnSpinDown = new Image();
                btnSpinDown.src = spinDownImage;
                var btn = $(document.createElement("img"));
                btn.attr("src", spinBtnImage);
                btn.attr("width", 16);
                btn.attr("height", 21);
                if (opt.btnClass) {
                    btn.addClass(opt.btnClass);
                }
                if (opt.btnCss) {
                    btn.css(opt.btnCss);
                }
                if (opt.txtCss) {
                    txt.css(opt.txtCss);
                }
                txt.after(btn);
                if (opt.lock) {
                    txt.focus(function () {
                        txt.blur();
                    });
                }
                function spin(vector) {
                    var val = txt.val();
                    var org_val = val;
                    if (opt.decimal) {
                        val = val.replace(opt.decimal, ".");
                    }
                    if (!isNaN(val)) {
                        val = calcFloat.sum(val, vector * opt.interval);
                        if (opt.min !== null && val < opt.min) {
                            val = opt.min;
                        }
                        if (opt.max !== null && val > opt.max) {
                            val = opt.max;
                        }
                        if (val != txt.val()) {
                            if (opt.decimal) {
                                val = val.toString().replace(".", opt.decimal);
                            }
                            var ret = ($.isFunction(opt.beforeChange) ? opt.beforeChange.apply(txt, [val, org_val]) : true);
                            if (ret !== false) {
                                txt.val(val);
                                if ($.isFunction(opt.changed)) {
                                    opt.changed.apply(txt, [val]);
                                }
                                txt.change();
                                src = (vector > 0 ? spinUpImage : spinDownImage);
                                btn.attr("src", src);
                                if (opt.timeBlink < opt.timeInterval) {
                                    setTimeout(function () {
                                        btn.attr("src", spinBtnImage);
                                    }, opt.timeBlink);
                                }
                            }
                        }
                    }
                    if (vector > 0) {
                        if ($.isFunction(opt.buttonUp)) {
                            opt.buttonUp.apply(txt, [val]);
                        }
                    } else {
                        if ($.isFunction(opt.buttonDown)) {
                            opt.buttonDown.apply(txt, [val]);
                        }
                    }
                }

                btn.mousedown(function (e) {
                    var pos = e.pageY - btn.offset().top;
                    var vector = (btn.height() / 2 > pos ? 1 : -1);
                    (function () {
                        spin(vector);
                        var tk = setTimeout(arguments.callee, opt.timeInterval);
                        $(document).one("mouseup", function () {
                            clearTimeout(tk);
                            btn.attr("src", spinBtnImage);
                        });
                    })();
                    return false;
                });
            });
        }
    });
})(jQuery);