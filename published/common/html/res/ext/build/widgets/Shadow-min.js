/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.Shadow = function (C) {
    Ext.apply(this, C);
    if (typeof this.mode != "string") {
        this.mode = this.defaultMode
    }
    var D = this.offset, B = {h: 0};
    var A = Math.floor(this.offset / 2);
    switch (this.mode.toLowerCase()) {
        case"drop":
            B.w = 0;
            B.l = B.t = D;
            B.t -= 1;
            if (Ext.isIE) {
                B.l -= this.offset + A;
                B.t -= this.offset + A;
                B.w -= A;
                B.h -= A;
                B.t += 1
            }
            break;
        case"sides":
            B.w = (D * 2);
            B.l = -D;
            B.t = D - 1;
            if (Ext.isIE) {
                B.l -= (this.offset - A);
                B.t -= this.offset + A;
                B.l += 1;
                B.w -= (this.offset - A) * 2;
                B.w -= A + 1;
                B.h -= 1
            }
            break;
        case"frame":
            B.w = B.h = (D * 2);
            B.l = B.t = -D;
            B.t += 1;
            B.h -= 2;
            if (Ext.isIE) {
                B.l -= (this.offset - A);
                B.t -= (this.offset - A);
                B.l += 1;
                B.w -= (this.offset + A + 1);
                B.h -= (this.offset + A);
                B.h += 1
            }
            break
    }
    this.adjusts = B
};
Ext.Shadow.prototype = {
    offset: 4, defaultMode: "drop", show: function (A) {
        A = Ext.get(A);
        if (!this.el) {
            this.el = Ext.Shadow.Pool.pull();
            if (this.el.dom.nextSibling != A.dom) {
                this.el.insertBefore(A)
            }
        }
        this.el.setStyle("z-index", this.zIndex || parseInt(A.getStyle("z-index"), 10) - 1);
        if (Ext.isIE) {
            this.el.dom.style.filter = "progid:DXImageTransform.Microsoft.alpha(opacity=50) progid:DXImageTransform.Microsoft.Blur(pixelradius=" + (this.offset) + ")"
        }
        this.realign(A.getLeft(true), A.getTop(true), A.getWidth(), A.getHeight());
        this.el.dom.style.display = "block"
    }, isVisible: function () {
        return this.el ? true : false
    }, realign: function (A, M, L, D) {
        if (!this.el) {
            return
        }
        var I = this.adjusts, G = this.el.dom, N = G.style;
        var E = 0;
        N.left = (A + I.l) + "px";
        N.top = (M + I.t) + "px";
        var K = (L + I.w), C = (D + I.h), F = K + "px", J = C + "px";
        if (N.width != F || N.height != J) {
            N.width = F;
            N.height = J;
            if (!Ext.isIE) {
                var H = G.childNodes;
                var B = Math.max(0, (K - 12)) + "px";
                H[0].childNodes[1].style.width = B;
                H[1].childNodes[1].style.width = B;
                H[2].childNodes[1].style.width = B;
                H[1].style.height = Math.max(0, (C - 12)) + "px"
            }
        }
    }, hide: function () {
        if (this.el) {
            this.el.dom.style.display = "none";
            Ext.Shadow.Pool.push(this.el);
            delete this.el
        }
    }, setZIndex: function (A) {
        this.zIndex = A;
        if (this.el) {
            this.el.setStyle("z-index", A)
        }
    }
};
Ext.Shadow.Pool = function () {
    var B = [];
    var A = Ext.isIE ? "<div class=\"x-ie-shadow\"></div>" : "<div class=\"x-shadow\"><div class=\"xst\"><div class=\"xstl\"></div><div class=\"xstc\"></div><div class=\"xstr\"></div></div><div class=\"xsc\"><div class=\"xsml\"></div><div class=\"xsmc\"></div><div class=\"xsmr\"></div></div><div class=\"xsb\"><div class=\"xsbl\"></div><div class=\"xsbc\"></div><div class=\"xsbr\"></div></div></div>";
    return {
        pull: function () {
            var C = B.shift();
            if (!C) {
                C = Ext.get(Ext.DomHelper.insertHtml("beforeBegin", document.body.firstChild, A));
                C.autoBoxAdjust = false
            }
            return C
        }, push: function (C) {
            B.push(C)
        }
    }
}();