/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

YAHOO.util.Anim = function (B, A, C, D) {
    if (B) {
        this.init(B, A, C, D)
    }
};
YAHOO.util.Anim.prototype = {
    toString: function () {
        var A = this.getEl();
        var B = A.id || A.tagName;
        return ("Anim " + B)
    },
    patterns: {
        noNegatives: /width|height|opacity|padding/i,
        offsetAttribute: /^((width|height)|(top|left))$/,
        defaultUnit: /width|height|top$|bottom$|left$|right$/i,
        offsetUnit: /\d+(em|%|en|ex|pt|in|cm|mm|pc)$/i
    },
    doMethod: function (A, C, B) {
        return this.method(this.currentFrame, C, B - C, this.totalFrames)
    },
    setAttribute: function (A, C, B) {
        if (this.patterns.noNegatives.test(A)) {
            C = (C > 0) ? C : 0
        }
        YAHOO.util.Dom.setStyle(this.getEl(), A, C + B)
    },
    getAttribute: function (A) {
        var C = this.getEl();
        var E = YAHOO.util.Dom.getStyle(C, A);
        if (E !== "auto" && !this.patterns.offsetUnit.test(E)) {
            return parseFloat(E)
        }
        var B = this.patterns.offsetAttribute.exec(A) || [];
        var F = !!(B[3]);
        var D = !!(B[2]);
        if (D || (YAHOO.util.Dom.getStyle(C, "position") == "absolute" && F)) {
            E = C["offset" + B[0].charAt(0).toUpperCase() + B[0].substr(1)]
        } else {
            E = 0
        }
        return E
    },
    getDefaultUnit: function (A) {
        if (this.patterns.defaultUnit.test(A)) {
            return "px"
        }
        return ""
    },
    setRuntimeAttribute: function (B) {
        var G;
        var C;
        var D = this.attributes;
        this.runtimeAttributes[B] = {};
        var F = function (H) {
            return (typeof H !== "undefined")
        };
        if (!F(D[B]["to"]) && !F(D[B]["by"])) {
            return false
        }
        G = (F(D[B]["from"])) ? D[B]["from"] : this.getAttribute(B);
        if (F(D[B]["to"])) {
            C = D[B]["to"]
        } else {
            if (F(D[B]["by"])) {
                if (G.constructor == Array) {
                    C = [];
                    for (var E = 0, A = G.length; E < A; ++E) {
                        C[E] = G[E] + D[B]["by"][E]
                    }
                } else {
                    C = G + D[B]["by"]
                }
            }
        }
        this.runtimeAttributes[B].start = G;
        this.runtimeAttributes[B].end = C;
        this.runtimeAttributes[B].unit = (F(D[B].unit)) ? D[B]["unit"] : this.getDefaultUnit(B)
    },
    init: function (C, H, G, A) {
        var B = false;
        var D = null;
        var F = 0;
        C = YAHOO.util.Dom.get(C);
        this.attributes = H || {};
        this.duration = G || 1;
        this.method = A || YAHOO.util.Easing.easeNone;
        this.useSeconds = true;
        this.currentFrame = 0;
        this.totalFrames = YAHOO.util.AnimMgr.fps;
        this.getEl = function () {
            return C
        };
        this.isAnimated = function () {
            return B
        };
        this.getStartTime = function () {
            return D
        };
        this.runtimeAttributes = {};
        this.animate = function () {
            if (this.isAnimated()) {
                return false
            }
            this.currentFrame = 0;
            this.totalFrames = (this.useSeconds) ? Math.ceil(YAHOO.util.AnimMgr.fps * this.duration) : this.duration;
            YAHOO.util.AnimMgr.registerElement(this)
        };
        this.stop = function (K) {
            if (K) {
                this.currentFrame = this.totalFrames;
                this._onTween.fire()
            }
            YAHOO.util.AnimMgr.stop(this)
        };
        var J = function () {
            this.onStart.fire();
            this.runtimeAttributes = {};
            for (var K in this.attributes) {
                this.setRuntimeAttribute(K)
            }
            B = true;
            F = 0;
            D = new Date()
        };
        var I = function () {
            var M = {duration: new Date() - this.getStartTime(), currentFrame: this.currentFrame};
            M.toString = function () {
                return ("duration: " + M.duration + ", currentFrame: " + M.currentFrame)
            };
            this.onTween.fire(M);
            var L = this.runtimeAttributes;
            for (var K in L) {
                this.setAttribute(K, this.doMethod(K, L[K].start, L[K].end), L[K].unit)
            }
            F += 1
        };
        var E = function () {
            var K = (new Date() - D) / 1000;
            var L = {duration: K, frames: F, fps: F / K};
            L.toString = function () {
                return ("duration: " + L.duration + ", frames: " + L.frames + ", fps: " + L.fps)
            };
            B = false;
            F = 0;
            this.onComplete.fire(L)
        };
        this._onStart = new YAHOO.util.CustomEvent("_start", this, true);
        this.onStart = new YAHOO.util.CustomEvent("start", this);
        this.onTween = new YAHOO.util.CustomEvent("tween", this);
        this._onTween = new YAHOO.util.CustomEvent("_tween", this, true);
        this.onComplete = new YAHOO.util.CustomEvent("complete", this);
        this._onComplete = new YAHOO.util.CustomEvent("_complete", this, true);
        this._onStart.subscribe(J);
        this._onTween.subscribe(I);
        this._onComplete.subscribe(E)
    }
};
YAHOO.util.AnimMgr = new function () {
    var C = null;
    var B = [];
    var A = 0;
    this.fps = 1000;
    this.delay = 1;
    this.registerElement = function (F) {
        B[B.length] = F;
        A += 1;
        F._onStart.fire();
        this.start()
    };
    this.unRegister = function (G, F) {
        G._onComplete.fire();
        F = F || E(G);
        if (F != -1) {
            B.splice(F, 1)
        }
        A -= 1;
        if (A <= 0) {
            this.stop()
        }
    };
    this.start = function () {
        if (C === null) {
            C = setInterval(this.run, this.delay)
        }
    };
    this.stop = function (H) {
        if (!H) {
            clearInterval(C);
            for (var G = 0, F = B.length; G < F; ++G) {
                if (B[0].isAnimated()) {
                    this.unRegister(B[0], 0)
                }
            }
            B = [];
            C = null;
            A = 0
        } else {
            this.unRegister(H)
        }
    };
    this.run = function () {
        for (var H = 0, F = B.length; H < F; ++H) {
            var G = B[H];
            if (!G || !G.isAnimated()) {
                continue
            }
            if (G.currentFrame < G.totalFrames || G.totalFrames === null) {
                G.currentFrame += 1;
                if (G.useSeconds) {
                    D(G)
                }
                G._onTween.fire()
            } else {
                YAHOO.util.AnimMgr.stop(G, H)
            }
        }
    };
    var E = function (H) {
        for (var G = 0, F = B.length; G < F; ++G) {
            if (B[G] == H) {
                return G
            }
        }
        return -1
    };
    var D = function (G) {
        var J = G.totalFrames;
        var I = G.currentFrame;
        var H = (G.currentFrame * G.duration * 1000 / G.totalFrames);
        var F = (new Date() - G.getStartTime());
        var K = 0;
        if (F < G.duration * 1000) {
            K = Math.round((F / H - 1) * G.currentFrame)
        } else {
            K = J - (I + 1)
        }
        if (K > 0 && isFinite(K)) {
            if (G.currentFrame + K >= J) {
                K = J - (I + 1)
            }
            G.currentFrame += K
        }
    }
};
YAHOO.util.Bezier = new function () {
    this.getPosition = function (E, D) {
        var F = E.length;
        var C = [];
        for (var B = 0; B < F; ++B) {
            C[B] = [E[B][0], E[B][1]]
        }
        for (var A = 1; A < F; ++A) {
            for (B = 0; B < F - A; ++B) {
                C[B][0] = (1 - D) * C[B][0] + D * C[parseInt(B + 1, 10)][0];
                C[B][1] = (1 - D) * C[B][1] + D * C[parseInt(B + 1, 10)][1]
            }
        }
        return [C[0][0], C[0][1]]
    }
};
(function () {
    YAHOO.util.ColorAnim = function (E, D, F, G) {
        YAHOO.util.ColorAnim.superclass.constructor.call(this, E, D, F, G)
    };
    YAHOO.extend(YAHOO.util.ColorAnim, YAHOO.util.Anim);
    var B = YAHOO.util;
    var C = B.ColorAnim.superclass;
    var A = B.ColorAnim.prototype;
    A.toString = function () {
        var D = this.getEl();
        var E = D.id || D.tagName;
        return ("ColorAnim " + E)
    };
    A.patterns.color = /color$/i;
    A.patterns.rgb = /^rgb\(([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\)$/i;
    A.patterns.hex = /^#?([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})$/i;
    A.patterns.hex3 = /^#?([0-9A-F]{1})([0-9A-F]{1})([0-9A-F]{1})$/i;
    A.patterns.transparent = /^transparent|rgba\(0, 0, 0, 0\)$/;
    A.parseColor = function (D) {
        if (D.length == 3) {
            return D
        }
        var E = this.patterns.hex.exec(D);
        if (E && E.length == 4) {
            return [parseInt(E[1], 16), parseInt(E[2], 16), parseInt(E[3], 16)]
        }
        E = this.patterns.rgb.exec(D);
        if (E && E.length == 4) {
            return [parseInt(E[1], 10), parseInt(E[2], 10), parseInt(E[3], 10)]
        }
        E = this.patterns.hex3.exec(D);
        if (E && E.length == 4) {
            return [parseInt(E[1] + E[1], 16), parseInt(E[2] + E[2], 16), parseInt(E[3] + E[3], 16)]
        }
        return null
    };
    A.getAttribute = function (D) {
        var F = this.getEl();
        if (this.patterns.color.test(D)) {
            var G = YAHOO.util.Dom.getStyle(F, D);
            if (this.patterns.transparent.test(G)) {
                var E = F.parentNode;
                G = B.Dom.getStyle(E, D);
                while (E && this.patterns.transparent.test(G)) {
                    E = E.parentNode;
                    G = B.Dom.getStyle(E, D);
                    if (E.tagName.toUpperCase() == "HTML") {
                        G = "#fff"
                    }
                }
            }
        } else {
            G = C.getAttribute.call(this, D)
        }
        return G
    };
    A.doMethod = function (E, I, F) {
        var H;
        if (this.patterns.color.test(E)) {
            H = [];
            for (var G = 0, D = I.length; G < D; ++G) {
                H[G] = C.doMethod.call(this, E, I[G], F[G])
            }
            H = "rgb(" + Math.floor(H[0]) + "," + Math.floor(H[1]) + "," + Math.floor(H[2]) + ")"
        } else {
            H = C.doMethod.call(this, E, I, F)
        }
        return H
    };
    A.setRuntimeAttribute = function (E) {
        C.setRuntimeAttribute.call(this, E);
        if (this.patterns.color.test(E)) {
            var G = this.attributes;
            var I = this.parseColor(this.runtimeAttributes[E].start);
            var F = this.parseColor(this.runtimeAttributes[E].end);
            if (typeof G[E]["to"] === "undefined" && typeof G[E]["by"] !== "undefined") {
                F = this.parseColor(G[E].by);
                for (var H = 0, D = I.length; H < D; ++H) {
                    F[H] = I[H] + F[H]
                }
            }
            this.runtimeAttributes[E].start = I;
            this.runtimeAttributes[E].end = F
        }
    }
})();
YAHOO.util.Easing = {
    easeNone: function (B, A, D, C) {
        return D * B / C + A
    }, easeIn: function (B, A, D, C) {
        return D * (B /= C) * B + A
    }, easeOut: function (B, A, D, C) {
        return -D * (B /= C) * (B - 2) + A
    }, easeBoth: function (B, A, D, C) {
        if ((B /= C / 2) < 1) {
            return D / 2 * B * B + A
        }
        return -D / 2 * ((--B) * (B - 2) - 1) + A
    }, easeInStrong: function (B, A, D, C) {
        return D * (B /= C) * B * B * B + A
    }, easeOutStrong: function (B, A, D, C) {
        return -D * ((B = B / C - 1) * B * B * B - 1) + A
    }, easeBothStrong: function (B, A, D, C) {
        if ((B /= C / 2) < 1) {
            return D / 2 * B * B * B * B + A
        }
        return -D / 2 * ((B -= 2) * B * B * B - 2) + A
    }, elasticIn: function (C, A, G, F, B, E) {
        if (C == 0) {
            return A
        }
        if ((C /= F) == 1) {
            return A + G
        }
        if (!E) {
            E = F * 0.3
        }
        if (!B || B < Math.abs(G)) {
            B = G;
            var D = E / 4
        } else {
            var D = E / (2 * Math.PI) * Math.asin(G / B)
        }
        return -(B * Math.pow(2, 10 * (C -= 1)) * Math.sin((C * F - D) * (2 * Math.PI) / E)) + A
    }, elasticOut: function (C, A, G, F, B, E) {
        if (C == 0) {
            return A
        }
        if ((C /= F) == 1) {
            return A + G
        }
        if (!E) {
            E = F * 0.3
        }
        if (!B || B < Math.abs(G)) {
            B = G;
            var D = E / 4
        } else {
            var D = E / (2 * Math.PI) * Math.asin(G / B)
        }
        return B * Math.pow(2, -10 * C) * Math.sin((C * F - D) * (2 * Math.PI) / E) + G + A
    }, elasticBoth: function (C, A, G, F, B, E) {
        if (C == 0) {
            return A
        }
        if ((C /= F / 2) == 2) {
            return A + G
        }
        if (!E) {
            E = F * (0.3 * 1.5)
        }
        if (!B || B < Math.abs(G)) {
            B = G;
            var D = E / 4
        } else {
            var D = E / (2 * Math.PI) * Math.asin(G / B)
        }
        if (C < 1) {
            return -0.5 * (B * Math.pow(2, 10 * (C -= 1)) * Math.sin((C * F - D) * (2 * Math.PI) / E)) + A
        }
        return B * Math.pow(2, -10 * (C -= 1)) * Math.sin((C * F - D) * (2 * Math.PI) / E) * 0.5 + G + A
    }, backIn: function (B, A, E, D, C) {
        if (typeof C == "undefined") {
            C = 1.70158
        }
        return E * (B /= D) * B * ((C + 1) * B - C) + A
    }, backOut: function (B, A, E, D, C) {
        if (typeof C == "undefined") {
            C = 1.70158
        }
        return E * ((B = B / D - 1) * B * ((C + 1) * B + C) + 1) + A
    }, backBoth: function (B, A, E, D, C) {
        if (typeof C == "undefined") {
            C = 1.70158
        }
        if ((B /= D / 2) < 1) {
            return E / 2 * (B * B * (((C *= (1.525)) + 1) * B - C)) + A
        }
        return E / 2 * ((B -= 2) * B * (((C *= (1.525)) + 1) * B + C) + 2) + A
    }, bounceIn: function (B, A, D, C) {
        return D - YAHOO.util.Easing.bounceOut(C - B, 0, D, C) + A
    }, bounceOut: function (B, A, D, C) {
        if ((B /= C) < (1 / 2.75)) {
            return D * (7.5625 * B * B) + A
        } else {
            if (B < (2 / 2.75)) {
                return D * (7.5625 * (B -= (1.5 / 2.75)) * B + 0.75) + A
            } else {
                if (B < (2.5 / 2.75)) {
                    return D * (7.5625 * (B -= (2.25 / 2.75)) * B + 0.9375) + A
                }
            }
        }
        return D * (7.5625 * (B -= (2.625 / 2.75)) * B + 0.984375) + A
    }, bounceBoth: function (B, A, D, C) {
        if (B < C / 2) {
            return YAHOO.util.Easing.bounceIn(B * 2, 0, D, C) * 0.5 + A
        }
        return YAHOO.util.Easing.bounceOut(B * 2 - C, 0, D, C) * 0.5 + D * 0.5 + A
    }
};
(function () {
    YAHOO.util.Motion = function (G, F, H, I) {
        if (G) {
            YAHOO.util.Motion.superclass.constructor.call(this, G, F, H, I)
        }
    };
    YAHOO.extend(YAHOO.util.Motion, YAHOO.util.ColorAnim);
    var D = YAHOO.util;
    var E = D.Motion.superclass;
    var B = D.Motion.prototype;
    B.toString = function () {
        var F = this.getEl();
        var G = F.id || F.tagName;
        return ("Motion " + G)
    };
    B.patterns.points = /^points$/i;
    B.setAttribute = function (F, H, G) {
        if (this.patterns.points.test(F)) {
            G = G || "px";
            E.setAttribute.call(this, "left", H[0], G);
            E.setAttribute.call(this, "top", H[1], G)
        } else {
            E.setAttribute.call(this, F, H, G)
        }
    };
    B.getAttribute = function (F) {
        if (this.patterns.points.test(F)) {
            var G = [E.getAttribute.call(this, "left"), E.getAttribute.call(this, "top")]
        } else {
            G = E.getAttribute.call(this, F)
        }
        return G
    };
    B.doMethod = function (F, J, G) {
        var I = null;
        if (this.patterns.points.test(F)) {
            var H = this.method(this.currentFrame, 0, 100, this.totalFrames) / 100;
            I = D.Bezier.getPosition(this.runtimeAttributes[F], H)
        } else {
            I = E.doMethod.call(this, F, J, G)
        }
        return I
    };
    B.setRuntimeAttribute = function (O) {
        if (this.patterns.points.test(O)) {
            var G = this.getEl();
            var I = this.attributes;
            var F;
            var K = I["points"]["control"] || [];
            var H;
            var L, N;
            if (K.length > 0 && !(K[0] instanceof Array)) {
                K = [K]
            } else {
                var J = [];
                for (L = 0, N = K.length; L < N; ++L) {
                    J[L] = K[L]
                }
                K = J
            }
            if (D.Dom.getStyle(G, "position") == "static") {
                D.Dom.setStyle(G, "position", "relative")
            }
            if (C(I["points"]["from"])) {
                D.Dom.setXY(G, I["points"]["from"])
            } else {
                D.Dom.setXY(G, D.Dom.getXY(G))
            }
            F = this.getAttribute("points");
            if (C(I["points"]["to"])) {
                H = A.call(this, I["points"]["to"], F);
                var M = D.Dom.getXY(this.getEl());
                for (L = 0, N = K.length; L < N; ++L) {
                    K[L] = A.call(this, K[L], F)
                }
            } else {
                if (C(I["points"]["by"])) {
                    H = [F[0] + I["points"]["by"][0], F[1] + I["points"]["by"][1]];
                    for (L = 0, N = K.length; L < N; ++L) {
                        K[L] = [F[0] + K[L][0], F[1] + K[L][1]]
                    }
                }
            }
            this.runtimeAttributes[O] = [F];
            if (K.length > 0) {
                this.runtimeAttributes[O] = this.runtimeAttributes[O].concat(K)
            }
            this.runtimeAttributes[O][this.runtimeAttributes[O].length] = H
        } else {
            E.setRuntimeAttribute.call(this, O)
        }
    };
    var A = function (F, H) {
        var G = D.Dom.getXY(this.getEl());
        F = [F[0] - G[0] + H[0], F[1] - G[1] + H[1]];
        return F
    };
    var C = function (F) {
        return (typeof F !== "undefined")
    }
})();
(function () {
    YAHOO.util.Scroll = function (E, D, F, G) {
        if (E) {
            YAHOO.util.Scroll.superclass.constructor.call(this, E, D, F, G)
        }
    };
    YAHOO.extend(YAHOO.util.Scroll, YAHOO.util.ColorAnim);
    var B = YAHOO.util;
    var C = B.Scroll.superclass;
    var A = B.Scroll.prototype;
    A.toString = function () {
        var D = this.getEl();
        var E = D.id || D.tagName;
        return ("Scroll " + E)
    };
    A.doMethod = function (D, G, E) {
        var F = null;
        if (D == "scroll") {
            F = [this.method(this.currentFrame, G[0], E[0] - G[0], this.totalFrames), this.method(this.currentFrame, G[1], E[1] - G[1], this.totalFrames)]
        } else {
            F = C.doMethod.call(this, D, G, E)
        }
        return F
    };
    A.getAttribute = function (D) {
        var F = null;
        var E = this.getEl();
        if (D == "scroll") {
            F = [E.scrollLeft, E.scrollTop]
        } else {
            F = C.getAttribute.call(this, D)
        }
        return F
    };
    A.setAttribute = function (D, G, F) {
        var E = this.getEl();
        if (D == "scroll") {
            E.scrollLeft = G[0];
            E.scrollTop = G[1]
        } else {
            C.setAttribute.call(this, D, G, F)
        }
    }
})();
YAHOO.register("animation", YAHOO.util.Anim, {version: "2.2.0", build: "127"});