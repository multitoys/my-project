/**
 * User Script for Mobile Theme
 * */

// variable for event touch data
var UserTouch = ( function() {

    var min_touch_length = 5,
        touch_is_vertical,
        finger_place_x_start,
        finger_place_y_start,
        finger_place_x_end,
        finger_place_y_end,
        touch_delta_x,
        touch_delta_y,
        time_start,
        time_end,
        element;

    var on_touch_start = function(event) {

        finger_place_x_start = event.touches[0].pageX;
        finger_place_y_start = event.touches[0].pageY;
        finger_place_x_end = null;
        finger_place_y_end = null;
        touch_delta_x = null;
        touch_delta_y = null;
        touch_is_vertical = false,
            time_start = ( new Date() ).getTime(),
            time_end = false;

        UserTouch = {
            offsetStart: {
                top: finger_place_y_start,
                left: finger_place_x_start
            },
            offsetEnd: {
                top: false,
                left: false
            },
            offsetDelta: {
                x: false,
                y: false
            },
            orientation: {
                x: false,
                y: false
            },
            touchTime: false
        };

        element.addEventListener("touchmove", on_touch_move, false);
        element.addEventListener("touchend", on_touch_end, false);
    };

    var on_touch_move = function(event) {
        time_end = (new Date()).getTime();
        finger_place_x_end = event.touches[0].pageX;
        finger_place_y_end = event.touches[0].pageY;
        touch_delta_x = finger_place_x_end - finger_place_x_start;
        touch_delta_y = finger_place_y_end - finger_place_y_start;

        var is_horizontal = ( Math.abs(touch_delta_x) > Math.abs(touch_delta_y) && Math.abs(touch_delta_x) > min_touch_length );
        if (is_horizontal) {
            event.preventDefault();
        }

        UserTouch.offsetEnd = {
            top: finger_place_y_end,
            left: finger_place_x_end
        };

        UserTouch.offsetDelta = {
            x: touch_delta_x,
            y: touch_delta_y
        };

        if ( Math.abs(touch_delta_y) > min_touch_length ) {
            if ( touch_delta_y < 0 ) {
                UserTouch.orientation.y = "top";
            } else {
                UserTouch.orientation.y = "bottom";
            }
        }

        if ( Math.abs(touch_delta_x) > min_touch_length ) {
            if ( touch_delta_x < 0 ) {
                UserTouch.orientation.x = "left";
            } else {
                UserTouch.orientation.x = "right";
            }
        }

        UserTouch.touchTime = (time_end - time_start);
    };

    var on_touch_end = function() {
        // отключаем обработчики
        element.removeEventListener("touchmove", on_touch_move);
        element.removeEventListener("touchend", on_touch_end);
    };

    var bindEvents = function() {
        element = document.body;
        element.addEventListener("touchstart", on_touch_start, false);
    };

    document.addEventListener("DOMContentLoaded", function() {
        bindEvents();
    });

    return {
        offsetStart: {
            top: false,
            left: false
        },
        offsetEnd: {
            top: false,
            left: false
        },
        offsetDelta: {
            x: false,
            y: false
        },
        orientation: {
            x: false,
            y: false
        },
        touchTime: false
    };

})();

// Menu JS
( function($) {

    var storage = {
        activeMenuClass: "menu-is-shown",
        swipeTime: 300,
        swipe_horizontal_percent: 35,
        getWrapper: function() {
            return $("body");
        },
        getMenu: function() {
            return $(".hidden-menu-wrapper");
        },
        menu_is_active: function() {
            return this.getWrapper().hasClass( this.activeMenuClass );
        }
    };

    // Обработчики кликов
    var bindEvents = function() {
        // Открываем меню
        $(document).on( "click", ".show-hidden-menu", function() {
            showHiddenMenu();
            return false;
        });

        // Закрываем меню
        $(".hidden-menu-wrapper").on( "click", function() {
            hideHiddenMenu();
            return false;
        });

        // Блокируем всплытие кликов у меню-контейнера
        $(".menu-block-wrapper").on( "click", function(event) {
            event.stopPropagation();
        });

        // Клик по ссылке в меню
        $(".menu-block-wrapper .nav-wrapper").on( "click", "a", function() {
            onMenuClick( $(this) );
            return false;
        });

        var $body = document.body,
            $link = document.querySelectorAll(".show-hidden-menu")[0];

        $body.addEventListener("touchend", onTouchEndController, false);
        $link.addEventListener("touchend", onMenuTouchEnd, false);
    };

    var onTouchEndController = function(event) {
        var cancelTargetClass = [
            "shop-slider-wrapper",
            "tab-list-wrapper"
        ];

        var checkTargetClass = function($target, elementClass) {
            var result;

            if ( $target.hasClass(elementClass) ) {
                result = $target;

            } else if ( $target.closest("." + elementClass).length ) {
                result = $target.closest("." + elementClass);

            } else {
                result = false;
            }

            return result;
        };

        var is_passed = true;
        for (var i in cancelTargetClass ) {
            if (cancelTargetClass.hasOwnProperty(i)) {
                if ( checkTargetClass( $(event.target), cancelTargetClass[i]) ) {
                    is_passed = false;
                }
            }
        }

        if (is_passed) {
            onTouchEnd();
        }
    };

    // Клик по ссылке в меню
    var onMenuClick = function(selector) {
        var link_href = selector.attr("href"),
            menu_animate_time,
            animation_time;

        // Вычисляем время
        menu_animate_time = parseFloat( $(".hidden-menu-wrapper").css("transition-duration") ) * 1000;
        animation_time = menu_animate_time || 300;

        // Выполняем редирект после закрытия меню
        if (link_href) {

            // Скрываем меню
            hideHiddenMenu();

            // Если URL отличается от текущего, то редирект
            if ( location.pathname !== link_href ) {
                // Выполняем редирект после закрытия меню
                setTimeout( function() {
                    location.href = link_href;
                }, animation_time);
            }
        }
    };

    var onTouchEnd = function() {
        var menu_is_active = storage.menu_is_active(),
            orientation_x = (UserTouch.orientation.x),
            is_swipe = ( storage.swipeTime >= UserTouch.touchTime ),
            is_horizontal_swipe = false;

        if (is_swipe) {
            is_horizontal_swipe = ( Math.abs( parseInt( ( UserTouch.offsetDelta.y / UserTouch.offsetDelta.x ) * 100 ) ) <= storage.swipe_horizontal_percent );
            if (is_horizontal_swipe) {
                if (orientation_x === "left" && menu_is_active) {
                    hideHiddenMenu();
                }
            }
        }
    };

    var onMenuTouchEnd = function() {
        var menu_is_active = storage.menu_is_active(),
            orientation_x = (UserTouch.orientation.x),
            is_swipe = ( storage.swipeTime >= UserTouch.touchTime ),
            is_horizontal_swipe = false;

        if (is_swipe) {
            is_horizontal_swipe = ( Math.abs( parseInt( ( UserTouch.offsetDelta.y / UserTouch.offsetDelta.x ) * 100 ) ) <= storage.swipe_horizontal_percent );
            if (is_horizontal_swipe) {
                if (orientation_x === "right" && !menu_is_active) {
                    showHiddenMenu();
                }
            }

        }
    };

    // Показать скрытое меню
    var showHiddenMenu = function() {
        $("body").addClass(storage.activeMenuClass);
    };

    // Скрыть скрытое меню
    var hideHiddenMenu = function() {
        $("body").removeClass(storage.activeMenuClass);
    };

    // Вызов
    $(document).ready( function() {
        bindEvents();
    });

})(jQuery);

// Comments JS
( function($) {

    var bindEvents = function() {

        // Проверка на наличие формы на странице
        var $commentForm = $(".comment-form-wrapper");
        if ( $commentForm.length ) {
            var $submitButton = $commentForm.find(".comment-submit input[type='submit']"),
                $textarea = $commentForm.find(".comment-form-fields textarea");

            // Блокировка отправки сообщения с пустым полем
            $submitButton.on("click", function() {
                if ( !$textarea.val() ) {
                    $textarea
                        .attr("placeholder", "Введите ваш комментарий")
                        .focus()
                    ;
                    return false;
                }
            });
        }

        // Клик по "оставить ответ к комментарию"
        $(".reply-comment-link").on( "click", function() {
            var commentID = $(this).closest(".comment-item").data("comment-id");

            if (commentID) {
                setParentCommentID(commentID);
            }
        });
    };

    // Проставляет ID коммента, к которому оставят ответ
    var setParentCommentID = function(commentID) {
        var $commentForm = $(".comment-form-wrapper"),
            $input = $commentForm.find("input[name=parent]"),
            $textarea = $commentForm.find(".comment-form-fields textarea");

        // Проставляем в поле ID
        if ($input) {
            $input.val(commentID);
        }

        // Скролим окно до поля
        var $textareaAnchorTop = $textarea.offset().top;
        $("body").scrollTop($textareaAnchorTop);

        // Делаем фокус
        $textarea.focus();
    };

    $(document).ready(function() {
        bindEvents();
    });

})(jQuery);

// Cash Type Change JS
( function($) {

    var bindEvents = function() {
        var $selector = $("#currency");
        if ($selector.length) {
            $selector.change( function () {
                var url = location.href;
                url += (url.indexOf('?') == -1) ? '?' : '&';
                location.href = url + 'currency=' + $(this).val();
            });
        }
    };

    $(document).ready( function() {
        bindEvents();
    });

})(jQuery);

// Show Catalog Filter JS
( function($) {
    var bindEvents = function() {
        $(".show-filter-content-link").on( "click", function() {
            toggleFilterContent( $(this) );
            return false;
        });
    };

    var toggleFilterContent = function($link) {
        var $wrapper = $link.closest(".catalog-filter-wrapper"),
            activeClass = "is-shown";

        // Change Link Text
        if ($wrapper.hasClass(activeClass)) {
            $link.text( $link.data("hide-text") )
        } else {
            $link.text( $link.data("show-text") )
        }

        // Toggle Content
        $wrapper.toggleClass(activeClass);
    };

    $(document).ready( function() {
        bindEvents();
    });
})(jQuery);

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
    // myCart.html('<div style="float:right"><p style="font-size:14px;line-height:37px;color:white">Загрузка товаров...</p></div>');
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
    // myCart.html('Загрузка товаров...');
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

$(document).ready(function () {
    loadCart();
});