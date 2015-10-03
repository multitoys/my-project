/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ColorPalette = function (A) {
    Ext.ColorPalette.superclass.constructor.call(this, A);
    this.addEvents("select");
    if (this.handler) {
        this.on("select", this.handler, this.scope, true)
    }
};
Ext.extend(Ext.ColorPalette, Ext.Component, {
    itemCls: "x-color-palette",
    value: null,
    clickEvent: "click",
    ctype: "Ext.ColorPalette",
    allowReselect: false,
    colors: ["000000", "993300", "333300", "003300", "003366", "000080", "333399", "333333", "800000", "FF6600", "808000", "008000", "008080", "0000FF", "666699", "808080", "FF0000", "FF9900", "99CC00", "339966", "33CCCC", "3366FF", "800080", "969696", "FF00FF", "FFCC00", "FFFF00", "00FF00", "00FFFF", "00CCFF", "993366", "C0C0C0", "FF99CC", "FFCC99", "FFFF99", "CCFFCC", "CCFFFF", "99CCFF", "CC99FF", "FFFFFF"],
    onRender: function (B, A) {
        var C = new Ext.XTemplate("<tpl for=\".\"><a href=\"#\" class=\"color-{.}\" hidefocus=\"on\"><em><span style=\"background:#{.}\" unselectable=\"on\">&#160;</span></em></a></tpl>");
        var D = document.createElement("div");
        D.className = this.itemCls;
        C.overwrite(D, this.colors);
        B.dom.insertBefore(D, A);
        this.el = Ext.get(D);
        this.el.on(this.clickEvent, this.handleClick, this, {delegate: "a"});
        if (this.clickEvent != "click") {
            this.el.on("click", Ext.emptyFn, this, {delegate: "a", preventDefault: true})
        }
    },
    afterRender: function () {
        Ext.ColorPalette.superclass.afterRender.call(this);
        if (this.value) {
            var A = this.value;
            this.value = null;
            this.select(A)
        }
    },
    handleClick: function (B, A) {
        B.preventDefault();
        if (!this.disabled) {
            var C = A.className.match(/(?:^|\s)color-(.{6})(?:\s|$)/)[1];
            this.select(C.toUpperCase())
        }
    },
    select: function (A) {
        A = A.replace("#", "");
        if (A != this.value || this.allowReselect) {
            var B = this.el;
            if (this.value) {
                B.child("a.color-" + this.value).removeClass("x-color-palette-sel")
            }
            B.child("a.color-" + A).addClass("x-color-palette-sel");
            this.value = A;
            this.fireEvent("select", this, A)
        }
    }
});
Ext.reg("colorpalette", Ext.ColorPalette);