// JavaScript Document
var BrowserDetect = {
    init: function () {
        this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
        this.version = this.searchVersion(navigator.userAgent)
            || this.searchVersion(navigator.appVersion)
            || "an unknown version";
        this.OS = this.searchString(this.dataOS) || "an unknown OS";
    },
    searchString: function (data) {
        for (var i = 0; i < data.length; i++) {
            var dataString = data[i].string;
            var dataProp = data[i].prop;
            this.versionSearchString = data[i].versionSearch || data[i].identity;
            if (dataString) {
                if (dataString.indexOf(data[i].subString) != -1)
                    return data[i].identity;
            }
            else if (dataProp)
                return data[i].identity;
        }
    },
    searchVersion: function (dataString) {
        var index = dataString.indexOf(this.versionSearchString);
        if (index == -1) return;
        return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
    },
    dataBrowser: [
        {
            string: navigator.vendor,
            subString: "Apple",
            identity: "Safari"
        }
    ],
    dataOS: []
};

function roundElems() {
    BrowserDetect.init();
    if (BrowserDetect.browser == 'Safari') {
        var oldonload = window.onload;
        window.onload = function () {
            if (oldonload)oldonload();

            Nifty("div.cpt_currency_selection", "height bottom");
            Nifty("div.cpt_survey", "bottom");
            Nifty("div.cpt_shopping_cart_info", "top transparent");
            Nifty("div.cpt_category_tree", "bottom");
            Nifty("div.cpt_news_short_list", "bottom");
            Nifty("div.cpt_language_selection", "bottom");
            Nifty("div.col_header", "top");
            Nifty("div#cat_advproduct_search", "");
            Nifty("div.cpt_tag_cloud", "");
            Nifty("div.shp_main_header", "top transparent");

        }

    } else {


        Nifty("div.cpt_currency_selection", "height bottom");
        Nifty("div.cpt_survey", "bottom");
        Nifty("div.cpt_shopping_cart_info", "top transparent");
        Nifty("div.cpt_category_tree", "bottom");
        Nifty("div.cpt_news_short_list", "bottom");
        Nifty("div.cpt_language_selection", "bottom");
        Nifty("div.col_header", "top");
        Nifty("div#cat_advproduct_search", "");
        Nifty("div.cpt_tag_cloud", "");
        Nifty("div.shp_main_header", "top transparent");
    }
}