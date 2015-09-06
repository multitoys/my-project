var preview = $(".preview");
var quantity = $(".cart_product_quantity");
var scrollPane = $(".scroll-pane1");
var productLists = $(".cpt_product_lists");
var myCart = $("#my__cart");
var content = $("#content");
function RealMarginLi() {
    var idCenter = $("#center").find(".product_topview_area");
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
    setTimeout(RealResizeCatalog, 500);
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
    setTimeout(load_cart, 300);
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
    setTimeout(load_cart, 250);
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
}
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