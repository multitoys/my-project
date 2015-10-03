/**
 * @name  wbsPopup
 * @type  jQuery
 * @param String  target                div
 * @param Hash    options                additional options
 * @param Iteger  options[width]
 * @param Iteger  options[height]
 * @param String  options[backgroundColor] background css background-color
 * @param Float  options[opacity]
 * @param String  options[url]  url content
 * @param Function  options[loadComplite]  callback
 * @param Function  options[hidePopup]  callback
 */
(function ($) {

    $.fn.wbsPopup = function (options) {

        var settings = {
            width: 500,
            height: 400,
            backgroundColor: '#000000',
            opacity: 0.5,
            iframe: false
        };

        if (options) {
            $.extend(settings, options);
        }

        var loadComplite = settings.loadComplite || function () {
            };
        var hidePopup = settings.hidePopup || function () {
            };

        document.onkeydown = function (e) {
            if (e == null) { // ie
                keycode = event.keyCode;
            } else { // mozilla
                keycode = e.which;
            }
            if (keycode == 27) { // close
                wbsPopupClose();
            } else if (keycode == 190) { // display previous image
                if (!(TB_NextHTML == "")) {
                    document.onkeydown = "";
                    goNext();
                }
            } else if (keycode == 188) { // display next image
                if (!(TB_PrevHTML == "")) {
                    document.onkeydown = "";
                    goPrev();
                }
            }
        };

        return this.each(function () {
            var $target = $(this);

            var $body = $('body');

            var $background = $("<div id='wbs-popup-bg'></div>");
            $body.append($background);
            $background.css({
                opacity: settings.opacity,
                'z-index': 500,
                position: 'absolute',
                top: 0,
                left: 0,
                height: '100%',
                width: '100%',
                backgroundColor: settings.backgroundColor
            });

            $target.css({
                marginLeft: '-' + parseInt((settings.width / 2), 10) + 'px',
                width: settings.width + 'px',
                position: 'absolute',
                display: 'block',
                height: settings.height,
                width: settings.width,
                left: '50%',
                top: '50%',
                'z-index': 502
            });
            if (!(jQuery.browser.msie && jQuery.browser.version < 7)) { // take away IE6
                $target.css({
                    marginTop: '-' + parseInt((settings.height / 2), 10) + 'px'
                });
            }

            if (settings.url) {
                if (!settings.iframe) {
                    $target.load(settings.url, loadComplite);
                }
                else {
                    $target.hidePopup = hidePopup;
                    $target.append('<iframe src="' + settings.url + '" style="width:100%; height:100%; border: 0;"/>')
                    window.closePopup = function () {
                        this.wbsPopupClose();
                        this.hidePopup();
                    }.bind($target);
                }
            }
            $target.show();
        });
    };

    $.fn.wbsPopupClose = function (target, options) {
        return this.each(function () {
            $(this).children('iframe').remove();
            $('#wbs-popup-bg').remove();
            $(this).hide();
        });
    };

})(jQuery);