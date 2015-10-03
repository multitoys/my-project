/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

if (typeof YAHOO == "undefined") {
    throw"Unable to load Ext, core YUI utilities (yahoo, dom, event) not found."
}
(function () {
    var I = YAHOO.util.Event;
    var J = YAHOO.util.Dom;
    var C = YAHOO.util.Connect;
    var K = YAHOO.util.Easing;
    var B = YAHOO.util.Anim;
    var G;
    Ext.lib.Dom = {
        getViewWidth: function (A) {
            return A ? J.getDocumentWidth() : J.getViewportWidth()
        }, getViewHeight: function (A) {
            return A ? J.getDocumentHeight() : J.getViewportHeight()
        }, isAncestor: function (A, D) {
            return J.isAncestor(A, D)
        }, getRegion: function (A) {
            return J.getRegion(A)
        }, getY: function (A) {
            return this.getXY(A)[1]
        }, getX: function (A) {
            return this.getXY(A)[0]
        }, getXY: function (E) {
            var D, O, Q, R, N = (document.body || document.documentElement);
            E = Ext.getDom(E);
            if (E == N) {
                return [0, 0]
            }
            if (E.getBoundingClientRect) {
                Q = E.getBoundingClientRect();
                R = H(document).getScroll();
                return [Q.left + R.left, Q.top + R.top]
            }
            var S = 0, P = 0;
            D = E;
            var A = H(E).getStyle("position") == "absolute";
            while (D) {
                S += D.offsetLeft;
                P += D.offsetTop;
                if (!A && H(D).getStyle("position") == "absolute") {
                    A = true
                }
                if (Ext.isGecko) {
                    O = H(D);
                    var T = parseInt(O.getStyle("borderTopWidth"), 10) || 0;
                    var L = parseInt(O.getStyle("borderLeftWidth"), 10) || 0;
                    S += L;
                    P += T;
                    if (D != E && O.getStyle("overflow") != "visible") {
                        S += L;
                        P += T
                    }
                }
                D = D.offsetParent
            }
            if (Ext.isSafari && A) {
                S -= N.offsetLeft;
                P -= N.offsetTop
            }
            if (Ext.isGecko && !A) {
                var M = H(N);
                S += parseInt(M.getStyle("borderLeftWidth"), 10) || 0;
                P += parseInt(M.getStyle("borderTopWidth"), 10) || 0
            }
            D = E.parentNode;
            while (D && D != N) {
                if (!Ext.isOpera || (D.tagName != "TR" && H(D).getStyle("display") != "inline")) {
                    S -= D.scrollLeft;
                    P -= D.scrollTop
                }
                D = D.parentNode
            }
            return [S, P]
        }, setXY: function (A, D) {
            A = Ext.fly(A, "_setXY");
            A.position();
            var E = A.translatePoints(D);
            if (D[0] !== false) {
                A.dom.style.left = E.left + "px"
            }
            if (D[1] !== false) {
                A.dom.style.top = E.top + "px"
            }
        }, setX: function (D, A) {
            this.setXY(D, [A, false])
        }, setY: function (A, D) {
            this.setXY(A, [false, D])
        }
    };
    Ext.lib.Event = {
        getPageX: function (A) {
            return I.getPageX(A.browserEvent || A)
        }, getPageY: function (A) {
            return I.getPageY(A.browserEvent || A)
        }, getXY: function (A) {
            return I.getXY(A.browserEvent || A)
        }, getTarget: function (A) {
            return I.getTarget(A.browserEvent || A)
        }, getRelatedTarget: function (A) {
            return I.getRelatedTarget(A.browserEvent || A)
        }, on: function (M, A, L, E, D) {
            I.on(M, A, L, E, D)
        }, un: function (E, A, D) {
            I.removeListener(E, A, D)
        }, purgeElement: function (A) {
            I.purgeElement(A)
        }, preventDefault: function (A) {
            I.preventDefault(A.browserEvent || A)
        }, stopPropagation: function (A) {
            I.stopPropagation(A.browserEvent || A)
        }, stopEvent: function (A) {
            I.stopEvent(A.browserEvent || A)
        }, onAvailable: function (L, E, D, A) {
            return I.onAvailable(L, E, D, A)
        }
    };
    Ext.lib.Ajax = {
        request: function (O, M, A, N, D) {
            if (D) {
                var E = D.headers;
                if (E) {
                    for (var L in E) {
                        if (E.hasOwnProperty(L)) {
                            C.initHeader(L, E[L], false)
                        }
                    }
                }
                if (D.xmlData) {
                    C.initHeader("Content-Type", "text/xml", false);
                    O = "POST";
                    N = D.xmlData
                } else {
                    if (D.jsonData) {
                        C.initHeader("Content-Type", "text/javascript", false);
                        O = "POST";
                        N = typeof D.jsonData == "object" ? Ext.encode(D.jsonData) : D.jsonData
                    }
                }
            }
            return C.asyncRequest(O, M, A, N)
        }, formRequest: function (M, L, D, N, A, E) {
            C.setForm(M, A, E);
            return C.asyncRequest(Ext.getDom(M).method || "POST", L, D, N)
        }, isCallInProgress: function (A) {
            return C.isCallInProgress(A)
        }, abort: function (A) {
            return C.abort(A)
        }, serializeForm: function (A) {
            var D = C.setForm(A.dom || A);
            C.resetFormState();
            return D
        }
    };
    Ext.lib.Region = YAHOO.util.Region;
    Ext.lib.Point = YAHOO.util.Point;
    Ext.lib.Anim = {
        scroll: function (L, D, M, N, A, E) {
            this.run(L, D, M, N, A, E, YAHOO.util.Scroll)
        }, motion: function (L, D, M, N, A, E) {
            this.run(L, D, M, N, A, E, YAHOO.util.Motion)
        }, color: function (L, D, M, N, A, E) {
            this.run(L, D, M, N, A, E, YAHOO.util.ColorAnim)
        }, run: function (M, D, O, P, A, L, E) {
            E = E || YAHOO.util.Anim;
            if (typeof P == "string") {
                P = YAHOO.util.Easing[P]
            }
            var N = new E(M, D, O, P);
            N.animateX(function () {
                Ext.callback(A, L)
            });
            return N
        }
    };
    function H(A) {
        if (!G) {
            G = new Ext.Element.Flyweight()
        }
        G.dom = A;
        return G
    }

    if (Ext.isIE) {
        function F() {
            var A = Function.prototype;
            delete A.createSequence;
            delete A.defer;
            delete A.createDelegate;
            delete A.createCallback;
            delete A.createInterceptor;
            window.detachEvent("onunload", F)
        }

        window.attachEvent("onunload", F)
    }
    if (YAHOO.util.Anim) {
        YAHOO.util.Anim.prototype.animateX = function (E, A) {
            var D = function () {
                this.onComplete.unsubscribe(D);
                if (typeof E == "function") {
                    E.call(A || this, this)
                }
            };
            this.onComplete.subscribe(D, this, true);
            this.animate()
        }
    }
    if (YAHOO.util.DragDrop && Ext.dd.DragDrop) {
        YAHOO.util.DragDrop.defaultPadding = Ext.dd.DragDrop.defaultPadding;
        YAHOO.util.DragDrop.constrainTo = Ext.dd.DragDrop.constrainTo
    }
    YAHOO.util.Dom.getXY = function (A) {
        var D = function (E) {
            return Ext.lib.Dom.getXY(E)
        };
        return YAHOO.util.Dom.batch(A, D, YAHOO.util.Dom, true)
    };
    if (YAHOO.util.AnimMgr) {
        YAHOO.util.AnimMgr.fps = 1000
    }
    YAHOO.util.Region.prototype.adjust = function (E, D, A, L) {
        this.top += E;
        this.left += D;
        this.right += L;
        this.bottom += A;
        return this
    };
    YAHOO.util.Region.prototype.constrainTo = function (A) {
        this.top = this.top.constrain(A.top, A.bottom);
        this.bottom = this.bottom.constrain(A.top, A.bottom);
        this.left = this.left.constrain(A.left, A.right);
        this.right = this.right.constrain(A.left, A.right);
        return this
    }
})();