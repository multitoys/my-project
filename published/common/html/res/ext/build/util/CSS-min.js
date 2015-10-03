/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.util.CSS = function () {
    var D = null;
    var C = document;
    var B = /(-[a-z])/gi;
    var A = function (E, F) {
        return F.charAt(1).toUpperCase()
    };
    return {
        createStyleSheet: function (G, J) {
            var F;
            var E = C.getElementsByTagName("head")[0];
            var I = C.createElement("style");
            I.setAttribute("type", "text/css");
            if (J) {
                I.setAttribute("id", J)
            }
            if (Ext.isIE) {
                E.appendChild(I);
                F = I.styleSheet;
                F.cssText = G
            } else {
                try {
                    I.appendChild(C.createTextNode(G))
                } catch (H) {
                    I.cssText = G
                }
                E.appendChild(I);
                F = I.styleSheet ? I.styleSheet : (I.sheet || C.styleSheets[C.styleSheets.length - 1])
            }
            this.cacheStyleSheet(F);
            return F
        }, removeStyleSheet: function (F) {
            var E = C.getElementById(F);
            if (E) {
                E.parentNode.removeChild(E)
            }
        }, swapStyleSheet: function (G, E) {
            this.removeStyleSheet(G);
            var F = C.createElement("link");
            F.setAttribute("rel", "stylesheet");
            F.setAttribute("type", "text/css");
            F.setAttribute("id", G);
            F.setAttribute("href", E);
            C.getElementsByTagName("head")[0].appendChild(F)
        }, refreshCache: function () {
            return this.getRules(true)
        }, cacheStyleSheet: function (F) {
            if (!D) {
                D = {}
            }
            try {
                var H = F.cssRules || F.rules;
                for (var E = H.length - 1; E >= 0; --E) {
                    D[H[E].selectorText] = H[E]
                }
            } catch (G) {
            }
        }, getRules: function (F) {
            if (D == null || F) {
                D = {};
                var H = C.styleSheets;
                for (var G = 0, E = H.length; G < E; G++) {
                    try {
                        this.cacheStyleSheet(H[G])
                    } catch (I) {
                    }
                }
            }
            return D
        }, getRule: function (E, G) {
            var F = this.getRules(G);
            if (!(E instanceof Array)) {
                return F[E]
            }
            for (var H = 0; H < E.length; H++) {
                if (F[E[H]]) {
                    return F[E[H]]
                }
            }
            return null
        }, updateRule: function (E, H, G) {
            if (!(E instanceof Array)) {
                var I = this.getRule(E);
                if (I) {
                    I.style[H.replace(B, A)] = G;
                    return true
                }
            } else {
                for (var F = 0; F < E.length; F++) {
                    if (this.updateRule(E[F], H, G)) {
                        return true
                    }
                }
            }
            return false
        }
    }
}();