/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


Ext = {version: "2.2"};
window["undefined"] = window["undefined"];
Ext.apply = function (C, D, B) {
    if (B) {
        Ext.apply(C, B)
    }
    if (C && D && typeof D == "object") {
        for (var A in D) {
            C[A] = D[A]
        }
    }
    return C
};
(function () {
    var idSeed = 0;
    var ua = navigator.userAgent.toLowerCase();
    var isStrict = document.compatMode == "CSS1Compat", isOpera = ua.indexOf("opera") > -1, isSafari = (/webkit|khtml/).test(ua), isSafari3 = isSafari && ua.indexOf("webkit/5") != -1, isIE = !isOpera && ua.indexOf("msie") > -1, isIE7 = !isOpera && ua.indexOf("msie 7") > -1, isGecko = !isSafari && ua.indexOf("gecko") > -1, isGecko3 = !isSafari && ua.indexOf("rv:1.9") > -1, isBorderBox = isIE && !isStrict, isWindows = (ua.indexOf("windows") != -1 || ua.indexOf("win32") != -1), isMac = (ua.indexOf("macintosh") != -1 || ua.indexOf("mac os x") != -1), isAir = (ua.indexOf("adobeair") != -1), isLinux = (ua.indexOf("linux") != -1), isSecure = window.location.href.toLowerCase().indexOf("https") === 0;
    if (isIE && !isIE7) {
        try {
            document.execCommand("BackgroundImageCache", false, true)
        } catch (e) {
        }
    }
    Ext.apply(Ext, {
        isStrict: isStrict,
        isSecure: isSecure,
        isReady: false,
        enableGarbageCollector: true,
        enableListenerCollection: false,
        SSL_SECURE_URL: "javascript:false",
        BLANK_IMAGE_URL: "http:/" + "/extjs.com/s.gif",
        emptyFn: function () {
        },
        applyIf: function (o, c) {
            if (o && c) {
                for (var p in c) {
                    if (typeof o[p] == "undefined") {
                        o[p] = c[p]
                    }
                }
            }
            return o
        },
        addBehaviors: function (o) {
            if (!Ext.isReady) {
                Ext.onReady(function () {
                    Ext.addBehaviors(o)
                });
                return
            }
            var cache = {};
            for (var b in o) {
                var parts = b.split("@");
                if (parts[1]) {
                    var s = parts[0];
                    if (!cache[s]) {
                        cache[s] = Ext.select(s)
                    }
                    cache[s].on(parts[1], o[b])
                }
            }
            cache = null
        },
        id: function (el, prefix) {
            prefix = prefix || "ext-gen";
            el = Ext.getDom(el);
            var id = prefix + (++idSeed);
            return el ? (el.id ? el.id : (el.id = id)) : id
        },
        extend: function () {
            var io = function (o) {
                for (var m in o) {
                    this[m] = o[m]
                }
            };
            var oc = Object.prototype.constructor;
            return function (sb, sp, overrides) {
                if (typeof sp == "object") {
                    overrides = sp;
                    sp = sb;
                    sb = overrides.constructor != oc ? overrides.constructor : function () {
                        sp.apply(this, arguments)
                    }
                }
                var F = function () {
                }, sbp, spp = sp.prototype;
                F.prototype = spp;
                sbp = sb.prototype = new F();
                sbp.constructor = sb;
                sb.superclass = spp;
                if (spp.constructor == oc) {
                    spp.constructor = sp
                }
                sb.override = function (o) {
                    Ext.override(sb, o)
                };
                sbp.override = io;
                Ext.override(sb, overrides);
                sb.extend = function (o) {
                    Ext.extend(sb, o)
                };
                return sb
            }
        }(),
        override: function (origclass, overrides) {
            if (overrides) {
                var p = origclass.prototype;
                for (var method in overrides) {
                    p[method] = overrides[method]
                }
            }
        },
        namespace: function () {
            var a = arguments, o = null, i, j, d, rt;
            for (i = 0; i < a.length; ++i) {
                d = a[i].split(".");
                rt = d[0];
                eval("if (typeof " + rt + " == \"undefined\"){" + rt + " = {};} o = " + rt + ";");
                for (j = 1; j < d.length; ++j) {
                    o[d[j]] = o[d[j]] || {};
                    o = o[d[j]]
                }
            }
        },
        urlEncode: function (o) {
            if (!o) {
                return ""
            }
            var buf = [];
            for (var key in o) {
                var ov = o[key], k = encodeURIComponent(key);
                var type = typeof ov;
                if (type == "undefined") {
                    buf.push(k, "=&")
                } else {
                    if (type != "function" && type != "object") {
                        buf.push(k, "=", encodeURIComponent(ov), "&")
                    } else {
                        if (Ext.isArray(ov)) {
                            if (ov.length) {
                                for (var i = 0, len = ov.length; i < len; i++) {
                                    buf.push(k, "=", encodeURIComponent(ov[i] === undefined ? "" : ov[i]), "&")
                                }
                            } else {
                                buf.push(k, "=&")
                            }
                        }
                    }
                }
            }
            buf.pop();
            return buf.join("")
        },
        urlDecode: function (string, overwrite) {
            if (!string || !string.length) {
                return {}
            }
            var obj = {};
            var pairs = string.split("&");
            var pair, name, value;
            for (var i = 0, len = pairs.length; i < len; i++) {
                pair = pairs[i].split("=");
                name = decodeURIComponent(pair[0]);
                value = decodeURIComponent(pair[1]);
                if (overwrite !== true) {
                    if (typeof obj[name] == "undefined") {
                        obj[name] = value
                    } else {
                        if (typeof obj[name] == "string") {
                            obj[name] = [obj[name]];
                            obj[name].push(value)
                        } else {
                            obj[name].push(value)
                        }
                    }
                } else {
                    obj[name] = value
                }
            }
            return obj
        },
        each: function (array, fn, scope) {
            if (typeof array.length == "undefined" || typeof array == "string") {
                array = [array]
            }
            for (var i = 0, len = array.length; i < len; i++) {
                if (fn.call(scope || array[i], array[i], i, array) === false) {
                    return i
                }
            }
        },
        combine: function () {
            var as = arguments, l = as.length, r = [];
            for (var i = 0; i < l; i++) {
                var a = as[i];
                if (Ext.isArray(a)) {
                    r = r.concat(a)
                } else {
                    if (a.length !== undefined && !a.substr) {
                        r = r.concat(Array.prototype.slice.call(a, 0))
                    } else {
                        r.push(a)
                    }
                }
            }
            return r
        },
        escapeRe: function (s) {
            return s.replace(/([.*+?^${}()|[\]\/\\])/g, "\\$1")
        },
        callback: function (cb, scope, args, delay) {
            if (typeof cb == "function") {
                if (delay) {
                    cb.defer(delay, scope, args || [])
                } else {
                    cb.apply(scope, args || [])
                }
            }
        },
        getDom: function (el) {
            if (!el || !document) {
                return null
            }
            return el.dom ? el.dom : (typeof el == "string" ? document.getElementById(el) : el)
        },
        getDoc: function () {
            return Ext.get(document)
        },
        getBody: function () {
            return Ext.get(document.body || document.documentElement)
        },
        getCmp: function (id) {
            return Ext.ComponentMgr.get(id)
        },
        num: function (v, defaultValue) {
            if (typeof v != "number") {
                return defaultValue
            }
            return v
        },
        destroy: function () {
            for (var i = 0, a = arguments, len = a.length; i < len; i++) {
                var as = a[i];
                if (as) {
                    if (typeof as.destroy == "function") {
                        as.destroy()
                    } else {
                        if (as.dom) {
                            as.removeAllListeners();
                            as.remove()
                        }
                    }
                }
            }
        },
        removeNode: isIE ? function () {
            var d;
            return function (n) {
                if (n && n.tagName != "BODY") {
                    d = d || document.createElement("div");
                    d.appendChild(n);
                    d.innerHTML = ""
                }
            }
        }() : function (n) {
            if (n && n.parentNode && n.tagName != "BODY") {
                n.parentNode.removeChild(n)
            }
        },
        type: function (o) {
            if (o === undefined || o === null) {
                return false
            }
            if (o.htmlElement) {
                return "element"
            }
            var t = typeof o;
            if (t == "object" && o.nodeName) {
                switch (o.nodeType) {
                    case 1:
                        return "element";
                    case 3:
                        return (/\S/).test(o.nodeValue) ? "textnode" : "whitespace"
                }
            }
            if (t == "object" || t == "function") {
                switch (o.constructor) {
                    case Array:
                        return "array";
                    case RegExp:
                        return "regexp"
                }
                if (typeof o.length == "number" && typeof o.item == "function") {
                    return "nodelist"
                }
            }
            return t
        },
        isEmpty: function (v, allowBlank) {
            return v === null || v === undefined || (!allowBlank ? v === "" : false)
        },
        value: function (v, defaultValue, allowBlank) {
            return Ext.isEmpty(v, allowBlank) ? defaultValue : v
        },
        isArray: function (v) {
            return v && typeof v.length == "number" && typeof v.splice == "function"
        },
        isDate: function (v) {
            return v && typeof v.getFullYear == "function"
        },
        isOpera: isOpera,
        isSafari: isSafari,
        isSafari3: isSafari3,
        isSafari2: isSafari && !isSafari3,
        isIE: isIE,
        isIE6: isIE && !isIE7,
        isIE7: isIE7,
        isGecko: isGecko,
        isGecko2: isGecko && !isGecko3,
        isGecko3: isGecko3,
        isBorderBox: isBorderBox,
        isLinux: isLinux,
        isWindows: isWindows,
        isMac: isMac,
        isAir: isAir,
        useShims: ((isIE && !isIE7) || (isMac && isGecko && !isGecko3))
    });
    Ext.ns = Ext.namespace
})();
Ext.ns("Ext", "Ext.util", "Ext.grid", "Ext.dd", "Ext.tree", "Ext.data", "Ext.form", "Ext.menu", "Ext.state", "Ext.lib", "Ext.layout", "Ext.app", "Ext.ux");
Ext.apply(Function.prototype, {
    createCallback: function () {
        var A = arguments;
        var B = this;
        return function () {
            return B.apply(window, A)
        }
    }, createDelegate: function (C, B, A) {
        var D = this;
        return function () {
            var F = B || arguments;
            if (A === true) {
                F = Array.prototype.slice.call(arguments, 0);
                F = F.concat(B)
            } else {
                if (typeof A == "number") {
                    F = Array.prototype.slice.call(arguments, 0);
                    var E = [A, 0].concat(B);
                    Array.prototype.splice.apply(F, E)
                }
            }
            return D.apply(C || window, F)
        }
    }, defer: function (C, E, B, A) {
        var D = this.createDelegate(E, B, A);
        if (C) {
            return setTimeout(D, C)
        }
        D();
        return 0
    }, createSequence: function (B, A) {
        if (typeof B != "function") {
            return this
        }
        var C = this;
        return function () {
            var D = C.apply(this || window, arguments);
            B.apply(A || this || window, arguments);
            return D
        }
    }, createInterceptor: function (B, A) {
        if (typeof B != "function") {
            return this
        }
        var C = this;
        return function () {
            B.target = this;
            B.method = C;
            if (B.apply(A || this || window, arguments) === false) {
                return
            }
            return C.apply(this || window, arguments)
        }
    }
});
Ext.applyIf(String, {
    escape: function (A) {
        return A.replace(/('|\\)/g, "\\$1")
    }, leftPad: function (D, B, C) {
        var A = new String(D);
        if (!C) {
            C = " "
        }
        while (A.length < B) {
            A = C + A
        }
        return A.toString()
    }, format: function (B) {
        var A = Array.prototype.slice.call(arguments, 1);
        return B.replace(/\{(\d+)\}/g, function (C, D) {
            return A[D]
        })
    }
});
String.prototype.toggle = function (B, A) {
    return this == B ? A : B
};
String.prototype.trim = function () {
    var A = /^\s+|\s+$/g;
    return function () {
        return this.replace(A, "")
    }
}();
Ext.applyIf(Number.prototype, {
    constrain: function (B, A) {
        return Math.min(Math.max(this, B), A)
    }
});
Ext.applyIf(Array.prototype, {
    indexOf: function (C) {
        for (var B = 0, A = this.length; B < A; B++) {
            if (this[B] == C) {
                return B
            }
        }
        return -1
    }, remove: function (B) {
        var A = this.indexOf(B);
        if (A != -1) {
            this.splice(A, 1)
        }
        return this
    }
});
Date.prototype.getElapsed = function (A) {
    return Math.abs((A || new Date()).getTime() - this.getTime())
};


(function () {
    var B;
    Ext.lib.Dom = {
        getViewWidth: function (E) {
            return E ? this.getDocumentWidth() : this.getViewportWidth()
        }, getViewHeight: function (E) {
            return E ? this.getDocumentHeight() : this.getViewportHeight()
        }, getDocumentHeight: function () {
            var E = (document.compatMode != "CSS1Compat") ? document.body.scrollHeight : document.documentElement.scrollHeight;
            return Math.max(E, this.getViewportHeight())
        }, getDocumentWidth: function () {
            var E = (document.compatMode != "CSS1Compat") ? document.body.scrollWidth : document.documentElement.scrollWidth;
            return Math.max(E, this.getViewportWidth())
        }, getViewportHeight: function () {
            if (Ext.isIE) {
                return Ext.isStrict ? document.documentElement.clientHeight : document.body.clientHeight
            } else {
                return self.innerHeight
            }
        }, getViewportWidth: function () {
            if (Ext.isIE) {
                return Ext.isStrict ? document.documentElement.clientWidth : document.body.clientWidth
            } else {
                return self.innerWidth
            }
        }, isAncestor: function (F, G) {
            F = Ext.getDom(F);
            G = Ext.getDom(G);
            if (!F || !G) {
                return false
            }
            if (F.contains && !Ext.isSafari) {
                return F.contains(G)
            } else {
                if (F.compareDocumentPosition) {
                    return !!(F.compareDocumentPosition(G) & 16)
                } else {
                    var E = G.parentNode;
                    while (E) {
                        if (E == F) {
                            return true
                        } else {
                            if (!E.tagName || E.tagName.toUpperCase() == "HTML") {
                                return false
                            }
                        }
                        E = E.parentNode
                    }
                    return false
                }
            }
        }, getRegion: function (E) {
            return Ext.lib.Region.getRegion(E)
        }, getY: function (E) {
            return this.getXY(E)[1]
        }, getX: function (E) {
            return this.getXY(E)[0]
        }, getXY: function (G) {
            var F, K, M, N, J = (document.body || document.documentElement);
            G = Ext.getDom(G);
            if (G == J) {
                return [0, 0]
            }
            if (G.getBoundingClientRect) {
                M = G.getBoundingClientRect();
                N = C(document).getScroll();
                return [M.left + N.left, M.top + N.top]
            }
            var O = 0, L = 0;
            F = G;
            var E = C(G).getStyle("position") == "absolute";
            while (F) {
                O += F.offsetLeft;
                L += F.offsetTop;
                if (!E && C(F).getStyle("position") == "absolute") {
                    E = true
                }
                if (Ext.isGecko) {
                    K = C(F);
                    var P = parseInt(K.getStyle("borderTopWidth"), 10) || 0;
                    var H = parseInt(K.getStyle("borderLeftWidth"), 10) || 0;
                    O += H;
                    L += P;
                    if (F != G && K.getStyle("overflow") != "visible") {
                        O += H;
                        L += P
                    }
                }
                F = F.offsetParent
            }
            if (Ext.isSafari && E) {
                O -= J.offsetLeft;
                L -= J.offsetTop
            }
            if (Ext.isGecko && !E) {
                var I = C(J);
                O += parseInt(I.getStyle("borderLeftWidth"), 10) || 0;
                L += parseInt(I.getStyle("borderTopWidth"), 10) || 0
            }
            F = G.parentNode;
            while (F && F != J) {
                if (!Ext.isOpera || (F.tagName != "TR" && C(F).getStyle("display") != "inline")) {
                    O -= F.scrollLeft;
                    L -= F.scrollTop
                }
                F = F.parentNode
            }
            return [O, L]
        }, setXY: function (E, F) {
            E = Ext.fly(E, "_setXY");
            E.position();
            var G = E.translatePoints(F);
            if (F[0] !== false) {
                E.dom.style.left = G.left + "px"
            }
            if (F[1] !== false) {
                E.dom.style.top = G.top + "px"
            }
        }, setX: function (F, E) {
            this.setXY(F, [E, false])
        }, setY: function (E, F) {
            this.setXY(E, [false, F])
        }
    };
    Ext.lib.Event = function () {
        var F = false;
        var G = [];
        var K = [];
        var I = 0;
        var H = [];
        var E = 0;
        var J = null;
        return {
            POLL_RETRYS: 200,
            POLL_INTERVAL: 20,
            EL: 0,
            TYPE: 1,
            FN: 2,
            WFN: 3,
            OBJ: 3,
            ADJ_SCOPE: 4,
            _interval: null,
            startInterval: function () {
                if (!this._interval) {
                    var L = this;
                    var M = function () {
                        L._tryPreloadAttach()
                    };
                    this._interval = setInterval(M, this.POLL_INTERVAL)
                }
            },
            onAvailable: function (N, L, O, M) {
                H.push({id: N, fn: L, obj: O, override: M, checkReady: false});
                I = this.POLL_RETRYS;
                this.startInterval()
            },
            addListener: function (Q, M, P) {
                Q = Ext.getDom(Q);
                if (!Q || !P) {
                    return false
                }
                if ("unload" == M) {
                    K[K.length] = [Q, M, P];
                    return true
                }
                var O = function (R) {
                    return typeof Ext != "undefined" ? P(Ext.lib.Event.getEvent(R)) : false
                };
                var L = [Q, M, P, O];
                var N = G.length;
                G[N] = L;
                this.doAdd(Q, M, O, false);
                return true
            },
            removeListener: function (S, O, R) {
                var Q, N;
                S = Ext.getDom(S);
                if (!R) {
                    return this.purgeElement(S, false, O)
                }
                if ("unload" == O) {
                    for (Q = 0, N = K.length; Q < N; Q++) {
                        var M = K[Q];
                        if (M && M[0] == S && M[1] == O && M[2] == R) {
                            K.splice(Q, 1);
                            return true
                        }
                    }
                    return false
                }
                var L = null;
                var P = arguments[3];
                if ("undefined" == typeof P) {
                    P = this._getCacheIndex(S, O, R)
                }
                if (P >= 0) {
                    L = G[P]
                }
                if (!S || !L) {
                    return false
                }
                this.doRemove(S, O, L[this.WFN], false);
                delete G[P][this.WFN];
                delete G[P][this.FN];
                G.splice(P, 1);
                return true
            },
            getTarget: function (N, M) {
                N = N.browserEvent || N;
                var L = N.target || N.srcElement;
                return this.resolveTextNode(L)
            },
            resolveTextNode: function (L) {
                if (Ext.isSafari && L && 3 == L.nodeType) {
                    return L.parentNode
                } else {
                    return L
                }
            },
            getPageX: function (M) {
                M = M.browserEvent || M;
                var L = M.pageX;
                if (!L && 0 !== L) {
                    L = M.clientX || 0;
                    if (Ext.isIE) {
                        L += this.getScroll()[1]
                    }
                }
                return L
            },
            getPageY: function (L) {
                L = L.browserEvent || L;
                var M = L.pageY;
                if (!M && 0 !== M) {
                    M = L.clientY || 0;
                    if (Ext.isIE) {
                        M += this.getScroll()[0]
                    }
                }
                return M
            },
            getXY: function (L) {
                L = L.browserEvent || L;
                return [this.getPageX(L), this.getPageY(L)]
            },
            getRelatedTarget: function (M) {
                M = M.browserEvent || M;
                var L = M.relatedTarget;
                if (!L) {
                    if (M.type == "mouseout") {
                        L = M.toElement
                    } else {
                        if (M.type == "mouseover") {
                            L = M.fromElement
                        }
                    }
                }
                return this.resolveTextNode(L)
            },
            getTime: function (N) {
                N = N.browserEvent || N;
                if (!N.time) {
                    var M = new Date().getTime();
                    try {
                        N.time = M
                    } catch (L) {
                        this.lastError = L;
                        return M
                    }
                }
                return N.time
            },
            stopEvent: function (L) {
                this.stopPropagation(L);
                this.preventDefault(L)
            },
            stopPropagation: function (L) {
                L = L.browserEvent || L;
                if (L.stopPropagation) {
                    L.stopPropagation()
                } else {
                    L.cancelBubble = true
                }
            },
            preventDefault: function (L) {
                L = L.browserEvent || L;
                if (L.preventDefault) {
                    L.preventDefault()
                } else {
                    L.returnValue = false
                }
            },
            getEvent: function (M) {
                var L = M || window.event;
                if (!L) {
                    var N = this.getEvent.caller;
                    while (N) {
                        L = N.arguments[0];
                        if (L && Event == L.constructor) {
                            break
                        }
                        N = N.caller
                    }
                }
                return L
            },
            getCharCode: function (L) {
                L = L.browserEvent || L;
                return L.charCode || L.keyCode || 0
            },
            _getCacheIndex: function (Q, N, P) {
                for (var O = 0, M = G.length; O < M; ++O) {
                    var L = G[O];
                    if (L && L[this.FN] == P && L[this.EL] == Q && L[this.TYPE] == N) {
                        return O
                    }
                }
                return -1
            },
            elCache: {},
            getEl: function (L) {
                return document.getElementById(L)
            },
            clearCache: function () {
            },
            _load: function (M) {
                F = true;
                var L = Ext.lib.Event;
                if (Ext.isIE) {
                    L.doRemove(window, "load", L._load)
                }
            },
            _tryPreloadAttach: function () {
                if (this.locked) {
                    return false
                }
                this.locked = true;
                var R = !F;
                if (!R) {
                    R = (I > 0)
                }
                var Q = [];
                for (var M = 0, L = H.length; M < L; ++M) {
                    var P = H[M];
                    if (P) {
                        var O = this.getEl(P.id);
                        if (O) {
                            if (!P.checkReady || F || O.nextSibling || (document && document.body)) {
                                var N = O;
                                if (P.override) {
                                    if (P.override === true) {
                                        N = P.obj
                                    } else {
                                        N = P.override
                                    }
                                }
                                P.fn.call(N, P.obj);
                                H[M] = null
                            }
                        } else {
                            Q.push(P)
                        }
                    }
                }
                I = (Q.length === 0) ? 0 : I - 1;
                if (R) {
                    this.startInterval()
                } else {
                    clearInterval(this._interval);
                    this._interval = null
                }
                this.locked = false;
                return true
            },
            purgeElement: function (P, Q, N) {
                var R = this.getListeners(P, N);
                if (R) {
                    for (var O = 0, L = R.length; O < L; ++O) {
                        var M = R[O];
                        this.removeListener(P, M.type, M.fn)
                    }
                }
                if (Q && P && P.childNodes) {
                    for (O = 0, L = P.childNodes.length; O < L; ++O) {
                        this.purgeElement(P.childNodes[O], Q, N)
                    }
                }
            },
            getListeners: function (M, R) {
                var P = [], L;
                if (!R) {
                    L = [G, K]
                } else {
                    if (R == "unload") {
                        L = [K]
                    } else {
                        L = [G]
                    }
                }
                for (var O = 0; O < L.length; ++O) {
                    var T = L[O];
                    if (T && T.length > 0) {
                        for (var Q = 0, S = T.length; Q < S; ++Q) {
                            var N = T[Q];
                            if (N && N[this.EL] === M && (!R || R === N[this.TYPE])) {
                                P.push({
                                    type: N[this.TYPE],
                                    fn: N[this.FN],
                                    obj: N[this.OBJ],
                                    adjust: N[this.ADJ_SCOPE],
                                    index: Q
                                })
                            }
                        }
                    }
                }
                return (P.length) ? P : null
            },
            _unload: function (S) {
                var R = Ext.lib.Event, P, O, M, L, N;
                for (P = 0, L = K.length; P < L; ++P) {
                    M = K[P];
                    if (M) {
                        var Q = window;
                        if (M[R.ADJ_SCOPE]) {
                            if (M[R.ADJ_SCOPE] === true) {
                                Q = M[R.OBJ]
                            } else {
                                Q = M[R.ADJ_SCOPE]
                            }
                        }
                        M[R.FN].call(Q, R.getEvent(S), M[R.OBJ]);
                        K[P] = null;
                        M = null;
                        Q = null
                    }
                }
                K = null;
                if (G && G.length > 0) {
                    O = G.length;
                    while (O) {
                        N = O - 1;
                        M = G[N];
                        if (M) {
                            R.removeListener(M[R.EL], M[R.TYPE], M[R.FN], N)
                        }
                        O = O - 1
                    }
                    M = null;
                    R.clearCache()
                }
                R.doRemove(window, "unload", R._unload)
            },
            getScroll: function () {
                var L = document.documentElement, M = document.body;
                if (L && (L.scrollTop || L.scrollLeft)) {
                    return [L.scrollTop, L.scrollLeft]
                } else {
                    if (M) {
                        return [M.scrollTop, M.scrollLeft]
                    } else {
                        return [0, 0]
                    }
                }
            },
            doAdd: function () {
                if (window.addEventListener) {
                    return function (O, M, N, L) {
                        O.addEventListener(M, N, (L))
                    }
                } else {
                    if (window.attachEvent) {
                        return function (O, M, N, L) {
                            O.attachEvent("on" + M, N)
                        }
                    } else {
                        return function () {
                        }
                    }
                }
            }(),
            doRemove: function () {
                if (window.removeEventListener) {
                    return function (O, M, N, L) {
                        O.removeEventListener(M, N, (L))
                    }
                } else {
                    if (window.detachEvent) {
                        return function (N, L, M) {
                            N.detachEvent("on" + L, M)
                        }
                    } else {
                        return function () {
                        }
                    }
                }
            }()
        }
    }();
    var D = Ext.lib.Event;
    D.on = D.addListener;
    D.un = D.removeListener;
    if (document && document.body) {
        D._load()
    } else {
        D.doAdd(window, "load", D._load)
    }
    D.doAdd(window, "unload", D._unload);
    D._tryPreloadAttach();
    Ext.lib.Ajax = {
        request: function (K, I, E, J, F) {
            if (F) {
                var G = F.headers;
                if (G) {
                    for (var H in G) {
                        if (G.hasOwnProperty(H)) {
                            this.initHeader(H, G[H], false)
                        }
                    }
                }
                if (F.xmlData) {
                    if (!G || !G["Content-Type"]) {
                        this.initHeader("Content-Type", "text/xml", false)
                    }
                    K = (K ? K : (F.method ? F.method : "POST"));
                    J = F.xmlData
                } else {
                    if (F.jsonData) {
                        if (!G || !G["Content-Type"]) {
                            this.initHeader("Content-Type", "application/json", false)
                        }
                        K = (K ? K : (F.method ? F.method : "POST"));
                        J = typeof F.jsonData == "object" ? Ext.encode(F.jsonData) : F.jsonData
                    }
                }
            }
            return this.asyncRequest(K, I, E, J)
        },
        serializeForm: function (F) {
            if (typeof F == "string") {
                F = (document.getElementById(F) || document.forms[F])
            }
            var G, E, H, J, K = "", M = false;
            for (var L = 0; L < F.elements.length; L++) {
                G = F.elements[L];
                J = F.elements[L].disabled;
                E = F.elements[L].name;
                H = F.elements[L].value;
                if (!J && E) {
                    switch (G.type) {
                        case"select-one":
                        case"select-multiple":
                            for (var I = 0; I < G.options.length; I++) {
                                if (G.options[I].selected) {
                                    if (Ext.isIE) {
                                        K += encodeURIComponent(E) + "=" + encodeURIComponent(G.options[I].attributes["value"].specified ? G.options[I].value : G.options[I].text) + "&"
                                    } else {
                                        K += encodeURIComponent(E) + "=" + encodeURIComponent(G.options[I].hasAttribute("value") ? G.options[I].value : G.options[I].text) + "&"
                                    }
                                }
                            }
                            break;
                        case"radio":
                        case"checkbox":
                            if (G.checked) {
                                K += encodeURIComponent(E) + "=" + encodeURIComponent(H) + "&"
                            }
                            break;
                        case"file":
                        case undefined:
                        case"reset":
                        case"button":
                            break;
                        case"submit":
                            if (M == false) {
                                K += encodeURIComponent(E) + "=" + encodeURIComponent(H) + "&";
                                M = true
                            }
                            break;
                        default:
                            K += encodeURIComponent(E) + "=" + encodeURIComponent(H) + "&";
                            break
                    }
                }
            }
            K = K.substr(0, K.length - 1);
            return K
        },
        headers: {},
        hasHeaders: false,
        useDefaultHeader: true,
        defaultPostHeader: "application/x-www-form-urlencoded; charset=UTF-8",
        useDefaultXhrHeader: true,
        defaultXhrHeader: "XMLHttpRequest",
        hasDefaultHeaders: true,
        defaultHeaders: {},
        poll: {},
        timeout: {},
        pollInterval: 50,
        transactionId: 0,
        setProgId: function (E) {
            this.activeX.unshift(E)
        },
        setDefaultPostHeader: function (E) {
            this.useDefaultHeader = E
        },
        setDefaultXhrHeader: function (E) {
            this.useDefaultXhrHeader = E
        },
        setPollingInterval: function (E) {
            if (typeof E == "number" && isFinite(E)) {
                this.pollInterval = E
            }
        },
        createXhrObject: function (I) {
            var H, E;
            try {
                E = new XMLHttpRequest();
                H = {conn: E, tId: I}
            } catch (G) {
                for (var F = 0; F < this.activeX.length; ++F) {
                    try {
                        E = new ActiveXObject(this.activeX[F]);
                        H = {conn: E, tId: I};
                        break
                    } catch (G) {
                    }
                }
            } finally {
                return H
            }
        },
        getConnectionObject: function () {
            var F;
            var G = this.transactionId;
            try {
                F = this.createXhrObject(G);
                if (F) {
                    this.transactionId++
                }
            } catch (E) {
            } finally {
                return F
            }
        },
        asyncRequest: function (I, F, H, E) {
            var G = this.getConnectionObject();
            if (!G) {
                return null
            } else {
                G.conn.open(I, F, true);
                if (this.useDefaultXhrHeader) {
                    if (!this.defaultHeaders["X-Requested-With"]) {
                        this.initHeader("X-Requested-With", this.defaultXhrHeader, true)
                    }
                }
                if (E && this.useDefaultHeader && (!this.hasHeaders || !this.headers["Content-Type"])) {
                    this.initHeader("Content-Type", this.defaultPostHeader)
                }
                if (this.hasDefaultHeaders || this.hasHeaders) {
                    this.setHeader(G)
                }
                this.handleReadyState(G, H);
                G.conn.send(E || null);
                return G
            }
        },
        handleReadyState: function (F, G) {
            var E = this;
            if (G && G.timeout) {
                this.timeout[F.tId] = window.setTimeout(function () {
                    E.abort(F, G, true)
                }, G.timeout)
            }
            this.poll[F.tId] = window.setInterval(function () {
                if (F.conn && F.conn.readyState == 4) {
                    window.clearInterval(E.poll[F.tId]);
                    delete E.poll[F.tId];
                    if (G && G.timeout) {
                        window.clearTimeout(E.timeout[F.tId]);
                        delete E.timeout[F.tId]
                    }
                    E.handleTransactionResponse(F, G)
                }
            }, this.pollInterval)
        },
        handleTransactionResponse: function (I, J, E) {
            if (!J) {
                this.releaseObject(I);
                return
            }
            var G, F;
            try {
                if (I.conn.status !== undefined && I.conn.status != 0) {
                    G = I.conn.status
                } else {
                    G = 13030
                }
            } catch (H) {
                G = 13030
            }
            if (G >= 200 && G < 300) {
                F = this.createResponseObject(I, J.argument);
                if (J.success) {
                    if (!J.scope) {
                        J.success(F)
                    } else {
                        J.success.apply(J.scope, [F])
                    }
                }
            } else {
                switch (G) {
                    case 12002:
                    case 12029:
                    case 12030:
                    case 12031:
                    case 12152:
                    case 13030:
                        F = this.createExceptionObject(I.tId, J.argument, (E ? E : false));
                        if (J.failure) {
                            if (!J.scope) {
                                J.failure(F)
                            } else {
                                J.failure.apply(J.scope, [F])
                            }
                        }
                        break;
                    default:
                        F = this.createResponseObject(I, J.argument);
                        if (J.failure) {
                            if (!J.scope) {
                                J.failure(F)
                            } else {
                                J.failure.apply(J.scope, [F])
                            }
                        }
                }
            }
            this.releaseObject(I);
            F = null
        },
        createResponseObject: function (E, K) {
            var H = {};
            var M = {};
            try {
                var G = E.conn.getAllResponseHeaders();
                var J = G.split("\n");
                for (var I = 0; I < J.length; I++) {
                    var F = J[I].indexOf(":");
                    if (F != -1) {
                        M[J[I].substring(0, F)] = J[I].substring(F + 2)
                    }
                }
            } catch (L) {
            }
            H.tId = E.tId;
            H.status = E.conn.status;
            H.statusText = E.conn.statusText;
            H.getResponseHeader = M;
            H.getAllResponseHeaders = G;
            H.responseText = E.conn.responseText;
            H.responseXML = E.conn.responseXML;
            if (typeof K !== undefined) {
                H.argument = K
            }
            return H
        },
        createExceptionObject: function (L, H, E) {
            var J = 0;
            var K = "communication failure";
            var G = -1;
            var F = "transaction aborted";
            var I = {};
            I.tId = L;
            if (E) {
                I.status = G;
                I.statusText = F
            } else {
                I.status = J;
                I.statusText = K
            }
            if (H) {
                I.argument = H
            }
            return I
        },
        initHeader: function (E, H, G) {
            var F = (G) ? this.defaultHeaders : this.headers;
            if (F[E] === undefined) {
                F[E] = H
            } else {
                F[E] = H + "," + F[E]
            }
            if (G) {
                this.hasDefaultHeaders = true
            } else {
                this.hasHeaders = true
            }
        },
        setHeader: function (E) {
            if (this.hasDefaultHeaders) {
                for (var F in this.defaultHeaders) {
                    if (this.defaultHeaders.hasOwnProperty(F)) {
                        E.conn.setRequestHeader(F, this.defaultHeaders[F])
                    }
                }
            }
            if (this.hasHeaders) {
                for (var F in this.headers) {
                    if (this.headers.hasOwnProperty(F)) {
                        E.conn.setRequestHeader(F, this.headers[F])
                    }
                }
                this.headers = {};
                this.hasHeaders = false
            }
        },
        resetDefaultHeaders: function () {
            delete this.defaultHeaders;
            this.defaultHeaders = {};
            this.hasDefaultHeaders = false
        },
        abort: function (F, G, E) {
            if (this.isCallInProgress(F)) {
                F.conn.abort();
                window.clearInterval(this.poll[F.tId]);
                delete this.poll[F.tId];
                if (E) {
                    delete this.timeout[F.tId]
                }
                this.handleTransactionResponse(F, G, true);
                return true
            } else {
                return false
            }
        },
        isCallInProgress: function (E) {
            if (E.conn) {
                return E.conn.readyState != 4 && E.conn.readyState != 0
            } else {
                return false
            }
        },
        releaseObject: function (E) {
            E.conn = null;
            E = null
        },
        activeX: ["MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP"]
    };
    Ext.lib.Region = function (G, H, E, F) {
        this.top = G;
        this[1] = G;
        this.right = H;
        this.bottom = E;
        this.left = F;
        this[0] = F
    };
    Ext.lib.Region.prototype = {
        contains: function (E) {
            return (E.left >= this.left && E.right <= this.right && E.top >= this.top && E.bottom <= this.bottom)
        }, getArea: function () {
            return ((this.bottom - this.top) * (this.right - this.left))
        }, intersect: function (I) {
            var G = Math.max(this.top, I.top);
            var H = Math.min(this.right, I.right);
            var E = Math.min(this.bottom, I.bottom);
            var F = Math.max(this.left, I.left);
            if (E >= G && H >= F) {
                return new Ext.lib.Region(G, H, E, F)
            } else {
                return null
            }
        }, union: function (I) {
            var G = Math.min(this.top, I.top);
            var H = Math.max(this.right, I.right);
            var E = Math.max(this.bottom, I.bottom);
            var F = Math.min(this.left, I.left);
            return new Ext.lib.Region(G, H, E, F)
        }, constrainTo: function (E) {
            this.top = this.top.constrain(E.top, E.bottom);
            this.bottom = this.bottom.constrain(E.top, E.bottom);
            this.left = this.left.constrain(E.left, E.right);
            this.right = this.right.constrain(E.left, E.right);
            return this
        }, adjust: function (G, F, E, H) {
            this.top += G;
            this.left += F;
            this.right += H;
            this.bottom += E;
            return this
        }
    };
    Ext.lib.Region.getRegion = function (H) {
        var J = Ext.lib.Dom.getXY(H);
        var G = J[1];
        var I = J[0] + H.offsetWidth;
        var E = J[1] + H.offsetHeight;
        var F = J[0];
        return new Ext.lib.Region(G, I, E, F)
    };
    Ext.lib.Point = function (E, F) {
        if (Ext.isArray(E)) {
            F = E[1];
            E = E[0]
        }
        this.x = this.right = this.left = this[0] = E;
        this.y = this.top = this.bottom = this[1] = F
    };
    Ext.lib.Point.prototype = new Ext.lib.Region();
    Ext.lib.Anim = {
        scroll: function (H, F, I, J, E, G) {
            return this.run(H, F, I, J, E, G, Ext.lib.Scroll)
        }, motion: function (H, F, I, J, E, G) {
            return this.run(H, F, I, J, E, G, Ext.lib.Motion)
        }, color: function (H, F, I, J, E, G) {
            return this.run(H, F, I, J, E, G, Ext.lib.ColorAnim)
        }, run: function (I, F, K, L, E, H, G) {
            G = G || Ext.lib.AnimBase;
            if (typeof L == "string") {
                L = Ext.lib.Easing[L]
            }
            var J = new G(I, F, K, L);
            J.animateX(function () {
                Ext.callback(E, H)
            });
            return J
        }
    };
    function C(E) {
        if (!B) {
            B = new Ext.Element.Flyweight()
        }
        B.dom = E;
        return B
    }

    if (Ext.isIE) {
        function A() {
            var E = Function.prototype;
            delete E.createSequence;
            delete E.defer;
            delete E.createDelegate;
            delete E.createCallback;
            delete E.createInterceptor;
            window.detachEvent("onunload", A)
        }

        window.attachEvent("onunload", A)
    }
    Ext.lib.AnimBase = function (F, E, G, H) {
        if (F) {
            this.init(F, E, G, H)
        }
    };
    Ext.lib.AnimBase.prototype = {
        toString: function () {
            var E = this.getEl();
            var F = E.id || E.tagName;
            return ("Anim " + F)
        },
        patterns: {
            noNegatives: /width|height|opacity|padding/i,
            offsetAttribute: /^((width|height)|(top|left))$/,
            defaultUnit: /width|height|top$|bottom$|left$|right$/i,
            offsetUnit: /\d+(em|%|en|ex|pt|in|cm|mm|pc)$/i
        },
        doMethod: function (E, G, F) {
            return this.method(this.currentFrame, G, F - G, this.totalFrames)
        },
        setAttribute: function (E, G, F) {
            if (this.patterns.noNegatives.test(E)) {
                G = (G > 0) ? G : 0
            }
            Ext.fly(this.getEl(), "_anim").setStyle(E, G + F)
        },
        getAttribute: function (E) {
            var G = this.getEl();
            var I = C(G).getStyle(E);
            if (I !== "auto" && !this.patterns.offsetUnit.test(I)) {
                return parseFloat(I)
            }
            var F = this.patterns.offsetAttribute.exec(E) || [];
            var J = !!(F[3]);
            var H = !!(F[2]);
            if (H || (C(G).getStyle("position") == "absolute" && J)) {
                I = G["offset" + F[0].charAt(0).toUpperCase() + F[0].substr(1)]
            } else {
                I = 0
            }
            return I
        },
        getDefaultUnit: function (E) {
            if (this.patterns.defaultUnit.test(E)) {
                return "px"
            }
            return ""
        },
        animateX: function (G, E) {
            var F = function () {
                this.onComplete.removeListener(F);
                if (typeof G == "function") {
                    G.call(E || this, this)
                }
            };
            this.onComplete.addListener(F, this);
            this.animate()
        },
        setRuntimeAttribute: function (F) {
            var K;
            var G;
            var H = this.attributes;
            this.runtimeAttributes[F] = {};
            var J = function (L) {
                return (typeof L !== "undefined")
            };
            if (!J(H[F]["to"]) && !J(H[F]["by"])) {
                return false
            }
            K = (J(H[F]["from"])) ? H[F]["from"] : this.getAttribute(F);
            if (J(H[F]["to"])) {
                G = H[F]["to"]
            } else {
                if (J(H[F]["by"])) {
                    if (K.constructor == Array) {
                        G = [];
                        for (var I = 0, E = K.length; I < E; ++I) {
                            G[I] = K[I] + H[F]["by"][I]
                        }
                    } else {
                        G = K + H[F]["by"]
                    }
                }
            }
            this.runtimeAttributes[F].start = K;
            this.runtimeAttributes[F].end = G;
            this.runtimeAttributes[F].unit = (J(H[F].unit)) ? H[F]["unit"] : this.getDefaultUnit(F)
        },
        init: function (G, L, K, E) {
            var F = false;
            var H = null;
            var J = 0;
            G = Ext.getDom(G);
            this.attributes = L || {};
            this.duration = K || 1;
            this.method = E || Ext.lib.Easing.easeNone;
            this.useSeconds = true;
            this.currentFrame = 0;
            this.totalFrames = Ext.lib.AnimMgr.fps;
            this.getEl = function () {
                return G
            };
            this.isAnimated = function () {
                return F
            };
            this.getStartTime = function () {
                return H
            };
            this.runtimeAttributes = {};
            this.animate = function () {
                if (this.isAnimated()) {
                    return false
                }
                this.currentFrame = 0;
                this.totalFrames = (this.useSeconds) ? Math.ceil(Ext.lib.AnimMgr.fps * this.duration) : this.duration;
                Ext.lib.AnimMgr.registerElement(this)
            };
            this.stop = function (O) {
                if (O) {
                    this.currentFrame = this.totalFrames;
                    this._onTween.fire()
                }
                Ext.lib.AnimMgr.stop(this)
            };
            var N = function () {
                this.onStart.fire();
                this.runtimeAttributes = {};
                for (var O in this.attributes) {
                    this.setRuntimeAttribute(O)
                }
                F = true;
                J = 0;
                H = new Date()
            };
            var M = function () {
                var Q = {duration: new Date() - this.getStartTime(), currentFrame: this.currentFrame};
                Q.toString = function () {
                    return ("duration: " + Q.duration + ", currentFrame: " + Q.currentFrame)
                };
                this.onTween.fire(Q);
                var P = this.runtimeAttributes;
                for (var O in P) {
                    this.setAttribute(O, this.doMethod(O, P[O].start, P[O].end), P[O].unit)
                }
                J += 1
            };
            var I = function () {
                var O = (new Date() - H) / 1000;
                var P = {duration: O, frames: J, fps: J / O};
                P.toString = function () {
                    return ("duration: " + P.duration + ", frames: " + P.frames + ", fps: " + P.fps)
                };
                F = false;
                J = 0;
                this.onComplete.fire(P)
            };
            this._onStart = new Ext.util.Event(this);
            this.onStart = new Ext.util.Event(this);
            this.onTween = new Ext.util.Event(this);
            this._onTween = new Ext.util.Event(this);
            this.onComplete = new Ext.util.Event(this);
            this._onComplete = new Ext.util.Event(this);
            this._onStart.addListener(N);
            this._onTween.addListener(M);
            this._onComplete.addListener(I)
        }
    };
    Ext.lib.AnimMgr = new function () {
        var G = null;
        var F = [];
        var E = 0;
        this.fps = 1000;
        this.delay = 1;
        this.registerElement = function (J) {
            F[F.length] = J;
            E += 1;
            J._onStart.fire();
            this.start()
        };
        this.unRegister = function (K, J) {
            K._onComplete.fire();
            J = J || I(K);
            if (J != -1) {
                F.splice(J, 1)
            }
            E -= 1;
            if (E <= 0) {
                this.stop()
            }
        };
        this.start = function () {
            if (G === null) {
                G = setInterval(this.run, this.delay)
            }
        };
        this.stop = function (L) {
            if (!L) {
                clearInterval(G);
                for (var K = 0, J = F.length; K < J; ++K) {
                    if (F[0].isAnimated()) {
                        this.unRegister(F[0], 0)
                    }
                }
                F = [];
                G = null;
                E = 0
            } else {
                this.unRegister(L)
            }
        };
        this.run = function () {
            for (var L = 0, J = F.length; L < J; ++L) {
                var K = F[L];
                if (!K || !K.isAnimated()) {
                    continue
                }
                if (K.currentFrame < K.totalFrames || K.totalFrames === null) {
                    K.currentFrame += 1;
                    if (K.useSeconds) {
                        H(K)
                    }
                    K._onTween.fire()
                } else {
                    Ext.lib.AnimMgr.stop(K, L)
                }
            }
        };
        var I = function (L) {
            for (var K = 0, J = F.length; K < J; ++K) {
                if (F[K] == L) {
                    return K
                }
            }
            return -1
        };
        var H = function (K) {
            var N = K.totalFrames;
            var M = K.currentFrame;
            var L = (K.currentFrame * K.duration * 1000 / K.totalFrames);
            var J = (new Date() - K.getStartTime());
            var O = 0;
            if (J < K.duration * 1000) {
                O = Math.round((J / L - 1) * K.currentFrame)
            } else {
                O = N - (M + 1)
            }
            if (O > 0 && isFinite(O)) {
                if (K.currentFrame + O >= N) {
                    O = N - (M + 1)
                }
                K.currentFrame += O
            }
        }
    };
    Ext.lib.Bezier = new function () {
        this.getPosition = function (I, H) {
            var J = I.length;
            var G = [];
            for (var F = 0; F < J; ++F) {
                G[F] = [I[F][0], I[F][1]]
            }
            for (var E = 1; E < J; ++E) {
                for (F = 0; F < J - E; ++F) {
                    G[F][0] = (1 - H) * G[F][0] + H * G[parseInt(F + 1, 10)][0];
                    G[F][1] = (1 - H) * G[F][1] + H * G[parseInt(F + 1, 10)][1]
                }
            }
            return [G[0][0], G[0][1]]
        }
    };
    (function () {
        Ext.lib.ColorAnim = function (I, H, J, K) {
            Ext.lib.ColorAnim.superclass.constructor.call(this, I, H, J, K)
        };
        Ext.extend(Ext.lib.ColorAnim, Ext.lib.AnimBase);
        var F = Ext.lib;
        var G = F.ColorAnim.superclass;
        var E = F.ColorAnim.prototype;
        E.toString = function () {
            var H = this.getEl();
            var I = H.id || H.tagName;
            return ("ColorAnim " + I)
        };
        E.patterns.color = /color$/i;
        E.patterns.rgb = /^rgb\(([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\)$/i;
        E.patterns.hex = /^#?([0-9A-F]{2})([0-9A-F]{2})([0-9A-F]{2})$/i;
        E.patterns.hex3 = /^#?([0-9A-F]{1})([0-9A-F]{1})([0-9A-F]{1})$/i;
        E.patterns.transparent = /^transparent|rgba\(0, 0, 0, 0\)$/;
        E.parseColor = function (H) {
            if (H.length == 3) {
                return H
            }
            var I = this.patterns.hex.exec(H);
            if (I && I.length == 4) {
                return [parseInt(I[1], 16), parseInt(I[2], 16), parseInt(I[3], 16)]
            }
            I = this.patterns.rgb.exec(H);
            if (I && I.length == 4) {
                return [parseInt(I[1], 10), parseInt(I[2], 10), parseInt(I[3], 10)]
            }
            I = this.patterns.hex3.exec(H);
            if (I && I.length == 4) {
                return [parseInt(I[1] + I[1], 16), parseInt(I[2] + I[2], 16), parseInt(I[3] + I[3], 16)]
            }
            return null
        };
        E.getAttribute = function (H) {
            var J = this.getEl();
            if (this.patterns.color.test(H)) {
                var K = C(J).getStyle(H);
                if (this.patterns.transparent.test(K)) {
                    var I = J.parentNode;
                    K = C(I).getStyle(H);
                    while (I && this.patterns.transparent.test(K)) {
                        I = I.parentNode;
                        K = C(I).getStyle(H);
                        if (I.tagName.toUpperCase() == "HTML") {
                            K = "#fff"
                        }
                    }
                }
            } else {
                K = G.getAttribute.call(this, H)
            }
            return K
        };
        E.doMethod = function (I, M, J) {
            var L;
            if (this.patterns.color.test(I)) {
                L = [];
                for (var K = 0, H = M.length; K < H; ++K) {
                    L[K] = G.doMethod.call(this, I, M[K], J[K])
                }
                L = "rgb(" + Math.floor(L[0]) + "," + Math.floor(L[1]) + "," + Math.floor(L[2]) + ")"
            } else {
                L = G.doMethod.call(this, I, M, J)
            }
            return L
        };
        E.setRuntimeAttribute = function (I) {
            G.setRuntimeAttribute.call(this, I);
            if (this.patterns.color.test(I)) {
                var K = this.attributes;
                var M = this.parseColor(this.runtimeAttributes[I].start);
                var J = this.parseColor(this.runtimeAttributes[I].end);
                if (typeof K[I]["to"] === "undefined" && typeof K[I]["by"] !== "undefined") {
                    J = this.parseColor(K[I].by);
                    for (var L = 0, H = M.length; L < H; ++L) {
                        J[L] = M[L] + J[L]
                    }
                }
                this.runtimeAttributes[I].start = M;
                this.runtimeAttributes[I].end = J
            }
        }
    })();
    Ext.lib.Easing = {
        easeNone: function (F, E, H, G) {
            return H * F / G + E
        }, easeIn: function (F, E, H, G) {
            return H * (F /= G) * F + E
        }, easeOut: function (F, E, H, G) {
            return -H * (F /= G) * (F - 2) + E
        }, easeBoth: function (F, E, H, G) {
            if ((F /= G / 2) < 1) {
                return H / 2 * F * F + E
            }
            return -H / 2 * ((--F) * (F - 2) - 1) + E
        }, easeInStrong: function (F, E, H, G) {
            return H * (F /= G) * F * F * F + E
        }, easeOutStrong: function (F, E, H, G) {
            return -H * ((F = F / G - 1) * F * F * F - 1) + E
        }, easeBothStrong: function (F, E, H, G) {
            if ((F /= G / 2) < 1) {
                return H / 2 * F * F * F * F + E
            }
            return -H / 2 * ((F -= 2) * F * F * F - 2) + E
        }, elasticIn: function (G, E, K, J, F, I) {
            if (G == 0) {
                return E
            }
            if ((G /= J) == 1) {
                return E + K
            }
            if (!I) {
                I = J * 0.3
            }
            if (!F || F < Math.abs(K)) {
                F = K;
                var H = I / 4
            } else {
                var H = I / (2 * Math.PI) * Math.asin(K / F)
            }
            return -(F * Math.pow(2, 10 * (G -= 1)) * Math.sin((G * J - H) * (2 * Math.PI) / I)) + E
        }, elasticOut: function (G, E, K, J, F, I) {
            if (G == 0) {
                return E
            }
            if ((G /= J) == 1) {
                return E + K
            }
            if (!I) {
                I = J * 0.3
            }
            if (!F || F < Math.abs(K)) {
                F = K;
                var H = I / 4
            } else {
                var H = I / (2 * Math.PI) * Math.asin(K / F)
            }
            return F * Math.pow(2, -10 * G) * Math.sin((G * J - H) * (2 * Math.PI) / I) + K + E
        }, elasticBoth: function (G, E, K, J, F, I) {
            if (G == 0) {
                return E
            }
            if ((G /= J / 2) == 2) {
                return E + K
            }
            if (!I) {
                I = J * (0.3 * 1.5)
            }
            if (!F || F < Math.abs(K)) {
                F = K;
                var H = I / 4
            } else {
                var H = I / (2 * Math.PI) * Math.asin(K / F)
            }
            if (G < 1) {
                return -0.5 * (F * Math.pow(2, 10 * (G -= 1)) * Math.sin((G * J - H) * (2 * Math.PI) / I)) + E
            }
            return F * Math.pow(2, -10 * (G -= 1)) * Math.sin((G * J - H) * (2 * Math.PI) / I) * 0.5 + K + E
        }, backIn: function (F, E, I, H, G) {
            if (typeof G == "undefined") {
                G = 1.70158
            }
            return I * (F /= H) * F * ((G + 1) * F - G) + E
        }, backOut: function (F, E, I, H, G) {
            if (typeof G == "undefined") {
                G = 1.70158
            }
            return I * ((F = F / H - 1) * F * ((G + 1) * F + G) + 1) + E
        }, backBoth: function (F, E, I, H, G) {
            if (typeof G == "undefined") {
                G = 1.70158
            }
            if ((F /= H / 2) < 1) {
                return I / 2 * (F * F * (((G *= (1.525)) + 1) * F - G)) + E
            }
            return I / 2 * ((F -= 2) * F * (((G *= (1.525)) + 1) * F + G) + 2) + E
        }, bounceIn: function (F, E, H, G) {
            return H - Ext.lib.Easing.bounceOut(G - F, 0, H, G) + E
        }, bounceOut: function (F, E, H, G) {
            if ((F /= G) < (1 / 2.75)) {
                return H * (7.5625 * F * F) + E
            } else {
                if (F < (2 / 2.75)) {
                    return H * (7.5625 * (F -= (1.5 / 2.75)) * F + 0.75) + E
                } else {
                    if (F < (2.5 / 2.75)) {
                        return H * (7.5625 * (F -= (2.25 / 2.75)) * F + 0.9375) + E
                    }
                }
            }
            return H * (7.5625 * (F -= (2.625 / 2.75)) * F + 0.984375) + E
        }, bounceBoth: function (F, E, H, G) {
            if (F < G / 2) {
                return Ext.lib.Easing.bounceIn(F * 2, 0, H, G) * 0.5 + E
            }
            return Ext.lib.Easing.bounceOut(F * 2 - G, 0, H, G) * 0.5 + H * 0.5 + E
        }
    };
    (function () {
        Ext.lib.Motion = function (K, J, L, M) {
            if (K) {
                Ext.lib.Motion.superclass.constructor.call(this, K, J, L, M)
            }
        };
        Ext.extend(Ext.lib.Motion, Ext.lib.ColorAnim);
        var H = Ext.lib;
        var I = H.Motion.superclass;
        var F = H.Motion.prototype;
        F.toString = function () {
            var J = this.getEl();
            var K = J.id || J.tagName;
            return ("Motion " + K)
        };
        F.patterns.points = /^points$/i;
        F.setAttribute = function (J, L, K) {
            if (this.patterns.points.test(J)) {
                K = K || "px";
                I.setAttribute.call(this, "left", L[0], K);
                I.setAttribute.call(this, "top", L[1], K)
            } else {
                I.setAttribute.call(this, J, L, K)
            }
        };
        F.getAttribute = function (J) {
            if (this.patterns.points.test(J)) {
                var K = [I.getAttribute.call(this, "left"), I.getAttribute.call(this, "top")]
            } else {
                K = I.getAttribute.call(this, J)
            }
            return K
        };
        F.doMethod = function (J, N, K) {
            var M = null;
            if (this.patterns.points.test(J)) {
                var L = this.method(this.currentFrame, 0, 100, this.totalFrames) / 100;
                M = H.Bezier.getPosition(this.runtimeAttributes[J], L)
            } else {
                M = I.doMethod.call(this, J, N, K)
            }
            return M
        };
        F.setRuntimeAttribute = function (S) {
            if (this.patterns.points.test(S)) {
                var K = this.getEl();
                var M = this.attributes;
                var J;
                var O = M["points"]["control"] || [];
                var L;
                var P, R;
                if (O.length > 0 && !Ext.isArray(O[0])) {
                    O = [O]
                } else {
                    var N = [];
                    for (P = 0, R = O.length; P < R; ++P) {
                        N[P] = O[P]
                    }
                    O = N
                }
                Ext.fly(K, "_anim").position();
                if (G(M["points"]["from"])) {
                    Ext.lib.Dom.setXY(K, M["points"]["from"])
                } else {
                    Ext.lib.Dom.setXY(K, Ext.lib.Dom.getXY(K))
                }
                J = this.getAttribute("points");
                if (G(M["points"]["to"])) {
                    L = E.call(this, M["points"]["to"], J);
                    var Q = Ext.lib.Dom.getXY(this.getEl());
                    for (P = 0, R = O.length; P < R; ++P) {
                        O[P] = E.call(this, O[P], J)
                    }
                } else {
                    if (G(M["points"]["by"])) {
                        L = [J[0] + M["points"]["by"][0], J[1] + M["points"]["by"][1]];
                        for (P = 0, R = O.length; P < R; ++P) {
                            O[P] = [J[0] + O[P][0], J[1] + O[P][1]]
                        }
                    }
                }
                this.runtimeAttributes[S] = [J];
                if (O.length > 0) {
                    this.runtimeAttributes[S] = this.runtimeAttributes[S].concat(O)
                }
                this.runtimeAttributes[S][this.runtimeAttributes[S].length] = L
            } else {
                I.setRuntimeAttribute.call(this, S)
            }
        };
        var E = function (J, L) {
            var K = Ext.lib.Dom.getXY(this.getEl());
            J = [J[0] - K[0] + L[0], J[1] - K[1] + L[1]];
            return J
        };
        var G = function (J) {
            return (typeof J !== "undefined")
        }
    })();
    (function () {
        Ext.lib.Scroll = function (I, H, J, K) {
            if (I) {
                Ext.lib.Scroll.superclass.constructor.call(this, I, H, J, K)
            }
        };
        Ext.extend(Ext.lib.Scroll, Ext.lib.ColorAnim);
        var F = Ext.lib;
        var G = F.Scroll.superclass;
        var E = F.Scroll.prototype;
        E.toString = function () {
            var H = this.getEl();
            var I = H.id || H.tagName;
            return ("Scroll " + I)
        };
        E.doMethod = function (H, K, I) {
            var J = null;
            if (H == "scroll") {
                J = [this.method(this.currentFrame, K[0], I[0] - K[0], this.totalFrames), this.method(this.currentFrame, K[1], I[1] - K[1], this.totalFrames)]
            } else {
                J = G.doMethod.call(this, H, K, I)
            }
            return J
        };
        E.getAttribute = function (H) {
            var J = null;
            var I = this.getEl();
            if (H == "scroll") {
                J = [I.scrollLeft, I.scrollTop]
            } else {
                J = G.getAttribute.call(this, H)
            }
            return J
        };
        E.setAttribute = function (H, K, J) {
            var I = this.getEl();
            if (H == "scroll") {
                I.scrollLeft = K[0];
                I.scrollTop = K[1]
            } else {
                G.setAttribute.call(this, H, K, J)
            }
        }
    })()
})();


Ext.DomHelper = function () {
    var L = null;
    var F = /^(?:br|frame|hr|img|input|link|meta|range|spacer|wbr|area|param|col)$/i;
    var B = /^table|tbody|tr|td$/i;
    var A = function (T) {
        if (typeof T == "string") {
            return T
        }
        var O = "";
        if (Ext.isArray(T)) {
            for (var R = 0, P = T.length; R < P; R++) {
                O += A(T[R])
            }
            return O
        }
        if (!T.tag) {
            T.tag = "div"
        }
        O += "<" + T.tag;
        for (var N in T) {
            if (N == "tag" || N == "children" || N == "cn" || N == "html" || typeof T[N] == "function") {
                continue
            }
            if (N == "style") {
                var S = T["style"];
                if (typeof S == "function") {
                    S = S.call()
                }
                if (typeof S == "string") {
                    O += " style=\"" + S + "\""
                } else {
                    if (typeof S == "object") {
                        O += " style=\"";
                        for (var Q in S) {
                            if (typeof S[Q] != "function") {
                                O += Q + ":" + S[Q] + ";"
                            }
                        }
                        O += "\""
                    }
                }
            } else {
                if (N == "cls") {
                    O += " class=\"" + T["cls"] + "\""
                } else {
                    if (N == "htmlFor") {
                        O += " for=\"" + T["htmlFor"] + "\""
                    } else {
                        O += " " + N + "=\"" + T[N] + "\""
                    }
                }
            }
        }
        if (F.test(T.tag)) {
            O += "/>"
        } else {
            O += ">";
            var U = T.children || T.cn;
            if (U) {
                O += A(U)
            } else {
                if (T.html) {
                    O += T.html
                }
            }
            O += "</" + T.tag + ">"
        }
        return O
    };
    var M = function (T, O) {
        var S;
        if (Ext.isArray(T)) {
            S = document.createDocumentFragment();
            for (var R = 0, P = T.length; R < P; R++) {
                M(T[R], S)
            }
        } else {
            if (typeof T == "string") {
                S = document.createTextNode(T)
            } else {
                S = document.createElement(T.tag || "div");
                var Q = !!S.setAttribute;
                for (var N in T) {
                    if (N == "tag" || N == "children" || N == "cn" || N == "html" || N == "style" || typeof T[N] == "function") {
                        continue
                    }
                    if (N == "cls") {
                        S.className = T["cls"]
                    } else {
                        if (Q) {
                            S.setAttribute(N, T[N])
                        } else {
                            S[N] = T[N]
                        }
                    }
                }
                Ext.DomHelper.applyStyles(S, T.style);
                var U = T.children || T.cn;
                if (U) {
                    M(U, S)
                } else {
                    if (T.html) {
                        S.innerHTML = T.html
                    }
                }
            }
        }
        if (O) {
            O.appendChild(S)
        }
        return S
    };
    var I = function (S, Q, P, R) {
        L.innerHTML = [Q, P, R].join("");
        var N = -1, O = L;
        while (++N < S) {
            O = O.firstChild
        }
        return O
    };
    var J = "<table>", E = "</table>", C = J + "<tbody>", K = "</tbody>" + E, H = C + "<tr>", D = "</tr>" + K;
    var G = function (N, O, Q, P) {
        if (!L) {
            L = document.createElement("div")
        }
        var R;
        var S = null;
        if (N == "td") {
            if (O == "afterbegin" || O == "beforeend") {
                return
            }
            if (O == "beforebegin") {
                S = Q;
                Q = Q.parentNode
            } else {
                S = Q.nextSibling;
                Q = Q.parentNode
            }
            R = I(4, H, P, D)
        } else {
            if (N == "tr") {
                if (O == "beforebegin") {
                    S = Q;
                    Q = Q.parentNode;
                    R = I(3, C, P, K)
                } else {
                    if (O == "afterend") {
                        S = Q.nextSibling;
                        Q = Q.parentNode;
                        R = I(3, C, P, K)
                    } else {
                        if (O == "afterbegin") {
                            S = Q.firstChild
                        }
                        R = I(4, H, P, D)
                    }
                }
            } else {
                if (N == "tbody") {
                    if (O == "beforebegin") {
                        S = Q;
                        Q = Q.parentNode;
                        R = I(2, J, P, E)
                    } else {
                        if (O == "afterend") {
                            S = Q.nextSibling;
                            Q = Q.parentNode;
                            R = I(2, J, P, E)
                        } else {
                            if (O == "afterbegin") {
                                S = Q.firstChild
                            }
                            R = I(3, C, P, K)
                        }
                    }
                } else {
                    if (O == "beforebegin" || O == "afterend") {
                        return
                    }
                    if (O == "afterbegin") {
                        S = Q.firstChild
                    }
                    R = I(2, J, P, E)
                }
            }
        }
        Q.insertBefore(R, S);
        return R
    };
    return {
        useDom: false, markup: function (N) {
            return A(N)
        }, applyStyles: function (P, Q) {
            if (Q) {
                P = Ext.fly(P);
                if (typeof Q == "string") {
                    var O = /\s?([a-z\-]*)\:\s?([^;]*);?/gi;
                    var R;
                    while ((R = O.exec(Q)) != null) {
                        P.setStyle(R[1], R[2])
                    }
                } else {
                    if (typeof Q == "object") {
                        for (var N in Q) {
                            P.setStyle(N, Q[N])
                        }
                    } else {
                        if (typeof Q == "function") {
                            Ext.DomHelper.applyStyles(P, Q.call())
                        }
                    }
                }
            }
        }, insertHtml: function (P, R, Q) {
            P = P.toLowerCase();
            if (R.insertAdjacentHTML) {
                if (B.test(R.tagName)) {
                    var O;
                    if (O = G(R.tagName.toLowerCase(), P, R, Q)) {
                        return O
                    }
                }
                switch (P) {
                    case"beforebegin":
                        R.insertAdjacentHTML("BeforeBegin", Q);
                        return R.previousSibling;
                    case"afterbegin":
                        R.insertAdjacentHTML("AfterBegin", Q);
                        return R.firstChild;
                    case"beforeend":
                        R.insertAdjacentHTML("BeforeEnd", Q);
                        return R.lastChild;
                    case"afterend":
                        R.insertAdjacentHTML("AfterEnd", Q);
                        return R.nextSibling
                }
                throw"Illegal insertion point -> \"" + P + "\""
            }
            var N = R.ownerDocument.createRange();
            var S;
            switch (P) {
                case"beforebegin":
                    N.setStartBefore(R);
                    S = N.createContextualFragment(Q);
                    R.parentNode.insertBefore(S, R);
                    return R.previousSibling;
                case"afterbegin":
                    if (R.firstChild) {
                        N.setStartBefore(R.firstChild);
                        S = N.createContextualFragment(Q);
                        R.insertBefore(S, R.firstChild);
                        return R.firstChild
                    } else {
                        R.innerHTML = Q;
                        return R.firstChild
                    }
                case"beforeend":
                    if (R.lastChild) {
                        N.setStartAfter(R.lastChild);
                        S = N.createContextualFragment(Q);
                        R.appendChild(S);
                        return R.lastChild
                    } else {
                        R.innerHTML = Q;
                        return R.lastChild
                    }
                case"afterend":
                    N.setStartAfter(R);
                    S = N.createContextualFragment(Q);
                    R.parentNode.insertBefore(S, R.nextSibling);
                    return R.nextSibling
            }
            throw"Illegal insertion point -> \"" + P + "\""
        }, insertBefore: function (N, P, O) {
            return this.doInsert(N, P, O, "beforeBegin")
        }, insertAfter: function (N, P, O) {
            return this.doInsert(N, P, O, "afterEnd", "nextSibling")
        }, insertFirst: function (N, P, O) {
            return this.doInsert(N, P, O, "afterBegin", "firstChild")
        }, doInsert: function (Q, S, R, T, P) {
            Q = Ext.getDom(Q);
            var O;
            if (this.useDom) {
                O = M(S, null);
                (P === "firstChild" ? Q : Q.parentNode).insertBefore(O, P ? Q[P] : Q)
            } else {
                var N = A(S);
                O = this.insertHtml(T, Q, N)
            }
            return R ? Ext.get(O, true) : O
        }, append: function (P, R, Q) {
            P = Ext.getDom(P);
            var O;
            if (this.useDom) {
                O = M(R, null);
                P.appendChild(O)
            } else {
                var N = A(R);
                O = this.insertHtml("beforeEnd", P, N)
            }
            return Q ? Ext.get(O, true) : O
        }, overwrite: function (N, P, O) {
            N = Ext.getDom(N);
            N.innerHTML = A(P);
            return O ? Ext.get(N.firstChild, true) : N.firstChild
        }, createTemplate: function (O) {
            var N = A(O);
            return new Ext.Template(N)
        }
    }
}();


Ext.Template = function (E) {
    var B = arguments;
    if (Ext.isArray(E)) {
        E = E.join("")
    } else {
        if (B.length > 1) {
            var C = [];
            for (var D = 0, A = B.length; D < A; D++) {
                if (typeof B[D] == "object") {
                    Ext.apply(this, B[D])
                } else {
                    C[C.length] = B[D]
                }
            }
            E = C.join("")
        }
    }
    this.html = E;
    if (this.compiled) {
        this.compile()
    }
};
Ext.Template.prototype = {
    applyTemplate: function (B) {
        if (this.compiled) {
            return this.compiled(B)
        }
        var A = this.disableFormats !== true;
        var E = Ext.util.Format, C = this;
        var D = function (G, I, L, H) {
            if (L && A) {
                if (L.substr(0, 5) == "this.") {
                    return C.call(L.substr(5), B[I], B)
                } else {
                    if (H) {
                        var K = /^\s*['"](.*)["']\s*$/;
                        H = H.split(",");
                        for (var J = 0, F = H.length; J < F; J++) {
                            H[J] = H[J].replace(K, "$1")
                        }
                        H = [B[I]].concat(H)
                    } else {
                        H = [B[I]]
                    }
                    return E[L].apply(E, H)
                }
            } else {
                return B[I] !== undefined ? B[I] : ""
            }
        };
        return this.html.replace(this.re, D)
    }, set: function (A, B) {
        this.html = A;
        this.compiled = null;
        if (B) {
            this.compile()
        }
        return this
    }, disableFormats: false, re: /\{([\w-]+)(?:\:([\w\.]*)(?:\((.*?)?\))?)?\}/g, compile: function () {
        var fm = Ext.util.Format;
        var useF = this.disableFormats !== true;
        var sep = Ext.isGecko ? "+" : ",";
        var fn = function (m, name, format, args) {
            if (format && useF) {
                args = args ? "," + args : "";
                if (format.substr(0, 5) != "this.") {
                    format = "fm." + format + "("
                } else {
                    format = "this.call(\"" + format.substr(5) + "\", ";
                    args = ", values"
                }
            } else {
                args = "";
                format = "(values['" + name + "'] == undefined ? '' : "
            }
            return "'" + sep + format + "values['" + name + "']" + args + ")" + sep + "'"
        };
        var body;
        if (Ext.isGecko) {
            body = "this.compiled = function(values){ return '" + this.html.replace(/\\/g, "\\\\").replace(/(\r\n|\n)/g, "\\n").replace(/'/g, "\\'").replace(this.re, fn) + "';};"
        } else {
            body = ["this.compiled = function(values){ return ['"];
            body.push(this.html.replace(/\\/g, "\\\\").replace(/(\r\n|\n)/g, "\\n").replace(/'/g, "\\'").replace(this.re, fn));
            body.push("'].join('');};");
            body = body.join("")
        }
        eval(body);
        return this
    }, call: function (C, B, A) {
        return this[C](B, A)
    }, insertFirst: function (B, A, C) {
        return this.doInsert("afterBegin", B, A, C)
    }, insertBefore: function (B, A, C) {
        return this.doInsert("beforeBegin", B, A, C)
    }, insertAfter: function (B, A, C) {
        return this.doInsert("afterEnd", B, A, C)
    }, append: function (B, A, C) {
        return this.doInsert("beforeEnd", B, A, C)
    }, doInsert: function (C, E, B, A) {
        E = Ext.getDom(E);
        var D = Ext.DomHelper.insertHtml(C, E, this.applyTemplate(B));
        return A ? Ext.get(D, true) : D
    }, overwrite: function (B, A, C) {
        B = Ext.getDom(B);
        B.innerHTML = this.applyTemplate(A);
        return C ? Ext.get(B.firstChild, true) : B.firstChild
    }
};
Ext.Template.prototype.apply = Ext.Template.prototype.applyTemplate;
Ext.DomHelper.Template = Ext.Template;
Ext.Template.from = function (B, A) {
    B = Ext.getDom(B);
    return new Ext.Template(B.value || B.innerHTML, A || "")
};


Ext.DomQuery = function () {
    var cache = {}, simpleCache = {}, valueCache = {};
    var nonSpace = /\S/;
    var trimRe = /^\s+|\s+$/g;
    var tplRe = /\{(\d+)\}/g;
    var modeRe = /^(\s?[\/>+~]\s?|\s|$)/;
    var tagTokenRe = /^(#)?([\w-\*]+)/;
    var nthRe = /(\d*)n\+?(\d*)/, nthRe2 = /\D/;

    function child(p, index) {
        var i = 0;
        var n = p.firstChild;
        while (n) {
            if (n.nodeType == 1) {
                if (++i == index) {
                    return n
                }
            }
            n = n.nextSibling
        }
        return null
    }

    function next(n) {
        while ((n = n.nextSibling) && n.nodeType != 1) {
        }
        return n
    }

    function prev(n) {
        while ((n = n.previousSibling) && n.nodeType != 1) {
        }
        return n
    }

    function children(d) {
        var n = d.firstChild, ni = -1;
        while (n) {
            var nx = n.nextSibling;
            if (n.nodeType == 3 && !nonSpace.test(n.nodeValue)) {
                d.removeChild(n)
            } else {
                n.nodeIndex = ++ni
            }
            n = nx
        }
        return this
    }

    function byClassName(c, a, v) {
        if (!v) {
            return c
        }
        var r = [], ri = -1, cn;
        for (var i = 0, ci; ci = c[i]; i++) {
            if ((" " + ci.className + " ").indexOf(v) != -1) {
                r[++ri] = ci
            }
        }
        return r
    }

    function attrValue(n, attr) {
        if (!n.tagName && typeof n.length != "undefined") {
            n = n[0]
        }
        if (!n) {
            return null
        }
        if (attr == "for") {
            return n.htmlFor
        }
        if (attr == "class" || attr == "className") {
            return n.className
        }
        return n.getAttribute(attr) || n[attr]
    }

    function getNodes(ns, mode, tagName) {
        var result = [], ri = -1, cs;
        if (!ns) {
            return result
        }
        tagName = tagName || "*";
        if (typeof ns.getElementsByTagName != "undefined") {
            ns = [ns]
        }
        if (!mode) {
            for (var i = 0, ni; ni = ns[i]; i++) {
                cs = ni.getElementsByTagName(tagName);
                for (var j = 0, ci; ci = cs[j]; j++) {
                    result[++ri] = ci
                }
            }
        } else {
            if (mode == "/" || mode == ">") {
                var utag = tagName.toUpperCase();
                for (var i = 0, ni, cn; ni = ns[i]; i++) {
                    cn = ni.children || ni.childNodes;
                    for (var j = 0, cj; cj = cn[j]; j++) {
                        if (cj.nodeName == utag || cj.nodeName == tagName || tagName == "*") {
                            result[++ri] = cj
                        }
                    }
                }
            } else {
                if (mode == "+") {
                    var utag = tagName.toUpperCase();
                    for (var i = 0, n; n = ns[i]; i++) {
                        while ((n = n.nextSibling) && n.nodeType != 1) {
                        }
                        if (n && (n.nodeName == utag || n.nodeName == tagName || tagName == "*")) {
                            result[++ri] = n
                        }
                    }
                } else {
                    if (mode == "~") {
                        for (var i = 0, n; n = ns[i]; i++) {
                            while ((n = n.nextSibling) && (n.nodeType != 1 || (tagName == "*" || n.tagName.toLowerCase() != tagName))) {
                            }
                            if (n) {
                                result[++ri] = n
                            }
                        }
                    }
                }
            }
        }
        return result
    }

    function concat(a, b) {
        if (b.slice) {
            return a.concat(b)
        }
        for (var i = 0, l = b.length; i < l; i++) {
            a[a.length] = b[i]
        }
        return a
    }

    function byTag(cs, tagName) {
        if (cs.tagName || cs == document) {
            cs = [cs]
        }
        if (!tagName) {
            return cs
        }
        var r = [], ri = -1;
        tagName = tagName.toLowerCase();
        for (var i = 0, ci; ci = cs[i]; i++) {
            if (ci.nodeType == 1 && ci.tagName.toLowerCase() == tagName) {
                r[++ri] = ci
            }
        }
        return r
    }

    function byId(cs, attr, id) {
        if (cs.tagName || cs == document) {
            cs = [cs]
        }
        if (!id) {
            return cs
        }
        var r = [], ri = -1;
        for (var i = 0, ci; ci = cs[i]; i++) {
            if (ci && ci.id == id) {
                r[++ri] = ci;
                return r
            }
        }
        return r
    }

    function byAttribute(cs, attr, value, op, custom) {
        var r = [], ri = -1, st = custom == "{";
        var f = Ext.DomQuery.operators[op];
        for (var i = 0, ci; ci = cs[i]; i++) {
            var a;
            if (st) {
                a = Ext.DomQuery.getStyle(ci, attr)
            } else {
                if (attr == "class" || attr == "className") {
                    a = ci.className
                } else {
                    if (attr == "for") {
                        a = ci.htmlFor
                    } else {
                        if (attr == "href") {
                            a = ci.getAttribute("href", 2)
                        } else {
                            a = ci.getAttribute(attr)
                        }
                    }
                }
            }
            if ((f && f(a, value)) || (!f && a)) {
                r[++ri] = ci
            }
        }
        return r
    }

    function byPseudo(cs, name, value) {
        return Ext.DomQuery.pseudos[name](cs, value)
    }

    var isIE = window.ActiveXObject ? true : false;
    eval("var batch = 30803;");
    var key = 30803;

    function nodupIEXml(cs) {
        var d = ++key;
        cs[0].setAttribute("_nodup", d);
        var r = [cs[0]];
        for (var i = 1, len = cs.length; i < len; i++) {
            var c = cs[i];
            if (!c.getAttribute("_nodup") != d) {
                c.setAttribute("_nodup", d);
                r[r.length] = c
            }
        }
        for (var i = 0, len = cs.length; i < len; i++) {
            cs[i].removeAttribute("_nodup")
        }
        return r
    }

    function nodup(cs) {
        if (!cs) {
            return []
        }
        var len = cs.length, c, i, r = cs, cj, ri = -1;
        if (!len || typeof cs.nodeType != "undefined" || len == 1) {
            return cs
        }
        if (isIE && typeof cs[0].selectSingleNode != "undefined") {
            return nodupIEXml(cs)
        }
        var d = ++key;
        cs[0]._nodup = d;
        for (i = 1; c = cs[i]; i++) {
            if (c._nodup != d) {
                c._nodup = d
            } else {
                r = [];
                for (var j = 0; j < i; j++) {
                    r[++ri] = cs[j]
                }
                for (j = i + 1; cj = cs[j]; j++) {
                    if (cj._nodup != d) {
                        cj._nodup = d;
                        r[++ri] = cj
                    }
                }
                return r
            }
        }
        return r
    }

    function quickDiffIEXml(c1, c2) {
        var d = ++key;
        for (var i = 0, len = c1.length; i < len; i++) {
            c1[i].setAttribute("_qdiff", d)
        }
        var r = [];
        for (var i = 0, len = c2.length; i < len; i++) {
            if (c2[i].getAttribute("_qdiff") != d) {
                r[r.length] = c2[i]
            }
        }
        for (var i = 0, len = c1.length; i < len; i++) {
            c1[i].removeAttribute("_qdiff")
        }
        return r
    }

    function quickDiff(c1, c2) {
        var len1 = c1.length;
        if (!len1) {
            return c2
        }
        if (isIE && c1[0].selectSingleNode) {
            return quickDiffIEXml(c1, c2)
        }
        var d = ++key;
        for (var i = 0; i < len1; i++) {
            c1[i]._qdiff = d
        }
        var r = [];
        for (var i = 0, len = c2.length; i < len; i++) {
            if (c2[i]._qdiff != d) {
                r[r.length] = c2[i]
            }
        }
        return r
    }

    function quickId(ns, mode, root, id) {
        if (ns == root) {
            var d = root.ownerDocument || root;
            return d.getElementById(id)
        }
        ns = getNodes(ns, mode, "*");
        return byId(ns, null, id)
    }

    return {
        getStyle: function (el, name) {
            return Ext.fly(el).getStyle(name)
        },
        compile: function (path, type) {
            type = type || "select";
            var fn = ["var f = function(root){\n var mode; ++batch; var n = root || document;\n"];
            var q = path, mode, lq;
            var tk = Ext.DomQuery.matchers;
            var tklen = tk.length;
            var mm;
            var lmode = q.match(modeRe);
            if (lmode && lmode[1]) {
                fn[fn.length] = "mode=\"" + lmode[1].replace(trimRe, "") + "\";";
                q = q.replace(lmode[1], "")
            }
            while (path.substr(0, 1) == "/") {
                path = path.substr(1)
            }
            while (q && lq != q) {
                lq = q;
                var tm = q.match(tagTokenRe);
                if (type == "select") {
                    if (tm) {
                        if (tm[1] == "#") {
                            fn[fn.length] = "n = quickId(n, mode, root, \"" + tm[2] + "\");"
                        } else {
                            fn[fn.length] = "n = getNodes(n, mode, \"" + tm[2] + "\");"
                        }
                        q = q.replace(tm[0], "")
                    } else {
                        if (q.substr(0, 1) != "@") {
                            fn[fn.length] = "n = getNodes(n, mode, \"*\");"
                        }
                    }
                } else {
                    if (tm) {
                        if (tm[1] == "#") {
                            fn[fn.length] = "n = byId(n, null, \"" + tm[2] + "\");"
                        } else {
                            fn[fn.length] = "n = byTag(n, \"" + tm[2] + "\");"
                        }
                        q = q.replace(tm[0], "")
                    }
                }
                while (!(mm = q.match(modeRe))) {
                    var matched = false;
                    for (var j = 0; j < tklen; j++) {
                        var t = tk[j];
                        var m = q.match(t.re);
                        if (m) {
                            fn[fn.length] = t.select.replace(tplRe, function (x, i) {
                                return m[i]
                            });
                            q = q.replace(m[0], "");
                            matched = true;
                            break
                        }
                    }
                    if (!matched) {
                        throw"Error parsing selector, parsing failed at \"" + q + "\""
                    }
                }
                if (mm[1]) {
                    fn[fn.length] = "mode=\"" + mm[1].replace(trimRe, "") + "\";";
                    q = q.replace(mm[1], "")
                }
            }
            fn[fn.length] = "return nodup(n);\n}";
            eval(fn.join(""));
            return f
        },
        select: function (path, root, type) {
            if (!root || root == document) {
                root = document
            }
            if (typeof root == "string") {
                root = document.getElementById(root)
            }
            var paths = path.split(",");
            var results = [];
            for (var i = 0, len = paths.length; i < len; i++) {
                var p = paths[i].replace(trimRe, "");
                if (!cache[p]) {
                    cache[p] = Ext.DomQuery.compile(p);
                    if (!cache[p]) {
                        throw p + " is not a valid selector"
                    }
                }
                var result = cache[p](root);
                if (result && result != document) {
                    results = results.concat(result)
                }
            }
            if (paths.length > 1) {
                return nodup(results)
            }
            return results
        },
        selectNode: function (path, root) {
            return Ext.DomQuery.select(path, root)[0]
        },
        selectValue: function (path, root, defaultValue) {
            path = path.replace(trimRe, "");
            if (!valueCache[path]) {
                valueCache[path] = Ext.DomQuery.compile(path, "select")
            }
            var n = valueCache[path](root);
            n = n[0] ? n[0] : n;
            var v = (n && n.firstChild ? n.firstChild.nodeValue : null);
            return ((v === null || v === undefined || v === "") ? defaultValue : v)
        },
        selectNumber: function (path, root, defaultValue) {
            var v = Ext.DomQuery.selectValue(path, root, defaultValue || 0);
            return parseFloat(v)
        },
        is: function (el, ss) {
            if (typeof el == "string") {
                el = document.getElementById(el)
            }
            var isArray = Ext.isArray(el);
            var result = Ext.DomQuery.filter(isArray ? el : [el], ss);
            return isArray ? (result.length == el.length) : (result.length > 0)
        },
        filter: function (els, ss, nonMatches) {
            ss = ss.replace(trimRe, "");
            if (!simpleCache[ss]) {
                simpleCache[ss] = Ext.DomQuery.compile(ss, "simple")
            }
            var result = simpleCache[ss](els);
            return nonMatches ? quickDiff(result, els) : result
        },
        matchers: [{
            re: /^\.([\w-]+)/,
            select: "n = byClassName(n, null, \" {1} \");"
        }, {
            re: /^\:([\w-]+)(?:\(((?:[^\s>\/]*|.*?))\))?/,
            select: "n = byPseudo(n, \"{1}\", \"{2}\");"
        }, {
            re: /^(?:([\[\{])(?:@)?([\w-]+)\s?(?:(=|.=)\s?['"]?(.*?)["']?)?[\]\}])/,
            select: "n = byAttribute(n, \"{2}\", \"{4}\", \"{3}\", \"{1}\");"
        }, {re: /^#([\w-]+)/, select: "n = byId(n, null, \"{1}\");"}, {
            re: /^@([\w-]+)/,
            select: "return {firstChild:{nodeValue:attrValue(n, \"{1}\")}};"
        }],
        operators: {
            "=": function (a, v) {
                return a == v
            }, "!=": function (a, v) {
                return a != v
            }, "^=": function (a, v) {
                return a && a.substr(0, v.length) == v
            }, "$=": function (a, v) {
                return a && a.substr(a.length - v.length) == v
            }, "*=": function (a, v) {
                return a && a.indexOf(v) !== -1
            }, "%=": function (a, v) {
                return (a % v) == 0
            }, "|=": function (a, v) {
                return a && (a == v || a.substr(0, v.length + 1) == v + "-")
            }, "~=": function (a, v) {
                return a && (" " + a + " ").indexOf(" " + v + " ") != -1
            }
        },
        pseudos: {
            "first-child": function (c) {
                var r = [], ri = -1, n;
                for (var i = 0, ci; ci = n = c[i]; i++) {
                    while ((n = n.previousSibling) && n.nodeType != 1) {
                    }
                    if (!n) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "last-child": function (c) {
                var r = [], ri = -1, n;
                for (var i = 0, ci; ci = n = c[i]; i++) {
                    while ((n = n.nextSibling) && n.nodeType != 1) {
                    }
                    if (!n) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "nth-child": function (c, a) {
                var r = [], ri = -1;
                var m = nthRe.exec(a == "even" && "2n" || a == "odd" && "2n+1" || !nthRe2.test(a) && "n+" + a || a);
                var f = (m[1] || 1) - 0, l = m[2] - 0;
                for (var i = 0, n; n = c[i]; i++) {
                    var pn = n.parentNode;
                    if (batch != pn._batch) {
                        var j = 0;
                        for (var cn = pn.firstChild; cn; cn = cn.nextSibling) {
                            if (cn.nodeType == 1) {
                                cn.nodeIndex = ++j
                            }
                        }
                        pn._batch = batch
                    }
                    if (f == 1) {
                        if (l == 0 || n.nodeIndex == l) {
                            r[++ri] = n
                        }
                    } else {
                        if ((n.nodeIndex + l) % f == 0) {
                            r[++ri] = n
                        }
                    }
                }
                return r
            }, "only-child": function (c) {
                var r = [], ri = -1;
                for (var i = 0, ci; ci = c[i]; i++) {
                    if (!prev(ci) && !next(ci)) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "empty": function (c) {
                var r = [], ri = -1;
                for (var i = 0, ci; ci = c[i]; i++) {
                    var cns = ci.childNodes, j = 0, cn, empty = true;
                    while (cn = cns[j]) {
                        ++j;
                        if (cn.nodeType == 1 || cn.nodeType == 3) {
                            empty = false;
                            break
                        }
                    }
                    if (empty) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "contains": function (c, v) {
                var r = [], ri = -1;
                for (var i = 0, ci; ci = c[i]; i++) {
                    if ((ci.textContent || ci.innerText || "").indexOf(v) != -1) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "nodeValue": function (c, v) {
                var r = [], ri = -1;
                for (var i = 0, ci; ci = c[i]; i++) {
                    if (ci.firstChild && ci.firstChild.nodeValue == v) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "checked": function (c) {
                var r = [], ri = -1;
                for (var i = 0, ci; ci = c[i]; i++) {
                    if (ci.checked == true) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "not": function (c, ss) {
                return Ext.DomQuery.filter(c, ss, true)
            }, "any": function (c, selectors) {
                var ss = selectors.split("|");
                var r = [], ri = -1, s;
                for (var i = 0, ci; ci = c[i]; i++) {
                    for (var j = 0; s = ss[j]; j++) {
                        if (Ext.DomQuery.is(ci, s)) {
                            r[++ri] = ci;
                            break
                        }
                    }
                }
                return r
            }, "odd": function (c) {
                return this["nth-child"](c, "odd")
            }, "even": function (c) {
                return this["nth-child"](c, "even")
            }, "nth": function (c, a) {
                return c[a - 1] || []
            }, "first": function (c) {
                return c[0] || []
            }, "last": function (c) {
                return c[c.length - 1] || []
            }, "has": function (c, ss) {
                var s = Ext.DomQuery.select;
                var r = [], ri = -1;
                for (var i = 0, ci; ci = c[i]; i++) {
                    if (s(ss, ci).length > 0) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "next": function (c, ss) {
                var is = Ext.DomQuery.is;
                var r = [], ri = -1;
                for (var i = 0, ci; ci = c[i]; i++) {
                    var n = next(ci);
                    if (n && is(n, ss)) {
                        r[++ri] = ci
                    }
                }
                return r
            }, "prev": function (c, ss) {
                var is = Ext.DomQuery.is;
                var r = [], ri = -1;
                for (var i = 0, ci; ci = c[i]; i++) {
                    var n = prev(ci);
                    if (n && is(n, ss)) {
                        r[++ri] = ci
                    }
                }
                return r
            }
        }
    }
}();
Ext.query = Ext.DomQuery.select;


Ext.util.Observable = function () {
    if (this.listeners) {
        this.on(this.listeners);
        delete this.listeners
    }
};
Ext.util.Observable.prototype = {
    fireEvent: function () {
        if (this.eventsSuspended !== true) {
            var A = this.events[arguments[0].toLowerCase()];
            if (typeof A == "object") {
                return A.fire.apply(A, Array.prototype.slice.call(arguments, 1))
            }
        }
        return true
    }, filterOptRe: /^(?:scope|delay|buffer|single)$/, addListener: function (A, C, B, F) {
        if (typeof A == "object") {
            F = A;
            for (var E in F) {
                if (this.filterOptRe.test(E)) {
                    continue
                }
                if (typeof F[E] == "function") {
                    this.addListener(E, F[E], F.scope, F)
                } else {
                    this.addListener(E, F[E].fn, F[E].scope, F[E])
                }
            }
            return
        }
        F = (!F || typeof F == "boolean") ? {} : F;
        A = A.toLowerCase();
        var D = this.events[A] || true;
        if (typeof D == "boolean") {
            D = new Ext.util.Event(this, A);
            this.events[A] = D
        }
        D.addListener(C, B, F)
    }, removeListener: function (A, C, B) {
        var D = this.events[A.toLowerCase()];
        if (typeof D == "object") {
            D.removeListener(C, B)
        }
    }, purgeListeners: function () {
        for (var A in this.events) {
            if (typeof this.events[A] == "object") {
                this.events[A].clearListeners()
            }
        }
    }, relayEvents: function (F, D) {
        var E = function (G) {
            return function () {
                return this.fireEvent.apply(this, Ext.combine(G, Array.prototype.slice.call(arguments, 0)))
            }
        };
        for (var C = 0, A = D.length; C < A; C++) {
            var B = D[C];
            if (!this.events[B]) {
                this.events[B] = true
            }
            F.on(B, E(B), this)
        }
    }, addEvents: function (D) {
        if (!this.events) {
            this.events = {}
        }
        if (typeof D == "string") {
            for (var C = 0, A = arguments, B; B = A[C]; C++) {
                if (!this.events[A[C]]) {
                    this.events[A[C]] = true
                }
            }
        } else {
            Ext.applyIf(this.events, D)
        }
    }, hasListener: function (A) {
        var B = this.events[A];
        return typeof B == "object" && B.listeners.length > 0
    }, suspendEvents: function () {
        this.eventsSuspended = true
    }, resumeEvents: function () {
        this.eventsSuspended = false
    }, getMethodEvent: function (G) {
        if (!this.methodEvents) {
            this.methodEvents = {}
        }
        var F = this.methodEvents[G];
        if (!F) {
            F = {};
            this.methodEvents[G] = F;
            F.originalFn = this[G];
            F.methodName = G;
            F.before = [];
            F.after = [];
            var C, B, D;
            var E = this;
            var A = function (J, I, H) {
                if ((B = J.apply(I || E, H)) !== undefined) {
                    if (typeof B === "object") {
                        if (B.returnValue !== undefined) {
                            C = B.returnValue
                        } else {
                            C = B
                        }
                        if (B.cancel === true) {
                            D = true
                        }
                    } else {
                        if (B === false) {
                            D = true
                        } else {
                            C = B
                        }
                    }
                }
            };
            this[G] = function () {
                C = B = undefined;
                D = false;
                var I = Array.prototype.slice.call(arguments, 0);
                for (var J = 0, H = F.before.length; J < H; J++) {
                    A(F.before[J].fn, F.before[J].scope, I);
                    if (D) {
                        return C
                    }
                }
                if ((B = F.originalFn.apply(E, I)) !== undefined) {
                    C = B
                }
                for (var J = 0, H = F.after.length; J < H; J++) {
                    A(F.after[J].fn, F.after[J].scope, I);
                    if (D) {
                        return C
                    }
                }
                return C
            }
        }
        return F
    }, beforeMethod: function (D, B, A) {
        var C = this.getMethodEvent(D);
        C.before.push({fn: B, scope: A})
    }, afterMethod: function (D, B, A) {
        var C = this.getMethodEvent(D);
        C.after.push({fn: B, scope: A})
    }, removeMethodListener: function (F, D, C) {
        var E = this.getMethodEvent(F);
        for (var B = 0, A = E.before.length; B < A; B++) {
            if (E.before[B].fn == D && E.before[B].scope == C) {
                E.before.splice(B, 1);
                return
            }
        }
        for (var B = 0, A = E.after.length; B < A; B++) {
            if (E.after[B].fn == D && E.after[B].scope == C) {
                E.after.splice(B, 1);
                return
            }
        }
    }
};
Ext.util.Observable.prototype.on = Ext.util.Observable.prototype.addListener;
Ext.util.Observable.prototype.un = Ext.util.Observable.prototype.removeListener;
Ext.util.Observable.capture = function (C, B, A) {
    C.fireEvent = C.fireEvent.createInterceptor(B, A)
};
Ext.util.Observable.releaseCapture = function (A) {
    A.fireEvent = Ext.util.Observable.prototype.fireEvent
};
(function () {
    var B = function (F, G, E) {
        var D = new Ext.util.DelayedTask();
        return function () {
            D.delay(G.buffer, F, E, Array.prototype.slice.call(arguments, 0))
        }
    };
    var C = function (F, G, E, D) {
        return function () {
            G.removeListener(E, D);
            return F.apply(D, arguments)
        }
    };
    var A = function (E, F, D) {
        return function () {
            var G = Array.prototype.slice.call(arguments, 0);
            setTimeout(function () {
                E.apply(D, G)
            }, F.delay || 10)
        }
    };
    Ext.util.Event = function (E, D) {
        this.name = D;
        this.obj = E;
        this.listeners = []
    };
    Ext.util.Event.prototype = {
        addListener: function (G, F, E) {
            F = F || this.obj;
            if (!this.isListening(G, F)) {
                var D = this.createListener(G, F, E);
                if (!this.firing) {
                    this.listeners.push(D)
                } else {
                    this.listeners = this.listeners.slice(0);
                    this.listeners.push(D)
                }
            }
        }, createListener: function (G, F, H) {
            H = H || {};
            F = F || this.obj;
            var D = {fn: G, scope: F, options: H};
            var E = G;
            if (H.delay) {
                E = A(E, H, F)
            }
            if (H.single) {
                E = C(E, this, G, F)
            }
            if (H.buffer) {
                E = B(E, H, F)
            }
            D.fireFn = E;
            return D
        }, findListener: function (I, H) {
            H = H || this.obj;
            var F = this.listeners;
            for (var G = 0, D = F.length; G < D; G++) {
                var E = F[G];
                if (E.fn == I && E.scope == H) {
                    return G
                }
            }
            return -1
        }, isListening: function (E, D) {
            return this.findListener(E, D) != -1
        }, removeListener: function (F, E) {
            var D;
            if ((D = this.findListener(F, E)) != -1) {
                if (!this.firing) {
                    this.listeners.splice(D, 1)
                } else {
                    this.listeners = this.listeners.slice(0);
                    this.listeners.splice(D, 1)
                }
                return true
            }
            return false
        }, clearListeners: function () {
            this.listeners = []
        }, fire: function () {
            var F = this.listeners, I, D = F.length;
            if (D > 0) {
                this.firing = true;
                var G = Array.prototype.slice.call(arguments, 0);
                for (var H = 0; H < D; H++) {
                    var E = F[H];
                    if (E.fireFn.apply(E.scope || this.obj || window, arguments) === false) {
                        this.firing = false;
                        return false
                    }
                }
                this.firing = false
            }
            return true
        }
    }
})();


Ext.EventManager = function () {
    var X, Q, M = false;
    var N, W, H, S;
    var P = Ext.lib.Event;
    var R = Ext.lib.Dom;
    var A = "Ex" + "t";
    var J = {};
    var O = function (b, E, a, Z, Y) {
        var d = Ext.id(b);
        if (!J[d]) {
            J[d] = {}
        }
        var c = J[d];
        if (!c[E]) {
            c[E] = []
        }
        var D = c[E];
        D.push({id: d, ename: E, fn: a, wrap: Z, scope: Y});
        P.on(b, E, Z);
        if (E == "mousewheel" && b.addEventListener) {
            b.addEventListener("DOMMouseScroll", Z, false);
            P.on(window, "unload", function () {
                b.removeEventListener("DOMMouseScroll", Z, false)
            })
        }
        if (E == "mousedown" && b == document) {
            Ext.EventManager.stoppedMouseDownEvent.addListener(Z)
        }
    };
    var I = function (Y, a, e, g) {
        Y = Ext.getDom(Y);
        var D = Ext.id(Y), f = J[D], E;
        if (f) {
            var c = f[a], Z;
            if (c) {
                for (var b = 0, d = c.length; b < d; b++) {
                    Z = c[b];
                    if (Z.fn == e && (!g || Z.scope == g)) {
                        E = Z.wrap;
                        P.un(Y, a, E);
                        c.splice(b, 1);
                        break
                    }
                }
            }
        }
        if (a == "mousewheel" && Y.addEventListener && E) {
            Y.removeEventListener("DOMMouseScroll", E, false)
        }
        if (a == "mousedown" && Y == document && E) {
            Ext.EventManager.stoppedMouseDownEvent.removeListener(E)
        }
    };
    var F = function (a) {
        a = Ext.getDom(a);
        var c = Ext.id(a), b = J[c], E;
        if (b) {
            for (var Z in b) {
                if (b.hasOwnProperty(Z)) {
                    E = b[Z];
                    for (var Y = 0, D = E.length; Y < D; Y++) {
                        P.un(a, Z, E[Y].wrap);
                        E[Y] = null
                    }
                }
                b[Z] = null
            }
            delete J[c]
        }
    };
    var C = function () {
        if (!M) {
            M = Ext.isReady = true;
            if (Ext.isGecko || Ext.isOpera) {
                document.removeEventListener("DOMContentLoaded", C, false)
            }
        }
        if (Q) {
            clearInterval(Q);
            Q = null
        }
        if (X) {
            X.fire();
            X.clearListeners()
        }
    };
    var B = function () {
        X = new Ext.util.Event();
        if (Ext.isReady) {
            return
        }
        P.on(window, "load", C);
        if (Ext.isGecko || Ext.isOpera) {
            document.addEventListener("DOMContentLoaded", C, false)
        } else {
            if (Ext.isIE) {
                Q = setInterval(function () {
                    try {
                        Ext.isReady || (document.documentElement.doScroll("left"))
                    } catch (D) {
                        return
                    }
                    C()
                }, 5);
                document.onreadystatechange = function () {
                    if (document.readyState == "complete") {
                        document.onreadystatechange = null;
                        C()
                    }
                }
            } else {
                if (Ext.isSafari) {
                    Q = setInterval(function () {
                        var D = document.readyState;
                        if (D == "complete") {
                            C()
                        }
                    }, 10)
                }
            }
        }
    };
    var V = function (E, Y) {
        var D = new Ext.util.DelayedTask(E);
        return function (Z) {
            Z = new Ext.EventObjectImpl(Z);
            D.delay(Y.buffer, E, null, [Z])
        }
    };
    var T = function (a, Z, D, Y, E) {
        return function (b) {
            Ext.EventManager.removeListener(Z, D, Y, E);
            a(b)
        }
    };
    var G = function (D, E) {
        return function (Y) {
            Y = new Ext.EventObjectImpl(Y);
            setTimeout(function () {
                D(Y)
            }, E.delay || 10)
        }
    };
    var L = function (Y, E, D, c, b) {
        var d = (!D || typeof D == "boolean") ? {} : D;
        c = c || d.fn;
        b = b || d.scope;
        var a = Ext.getDom(Y);
        if (!a) {
            throw"Error listening for \"" + E + "\". Element \"" + Y + "\" doesn't exist."
        }
        var Z = function (g) {
            if (!window[A]) {
                return
            }
            g = Ext.EventObject.setEvent(g);
            var f;
            if (d.delegate) {
                f = g.getTarget(d.delegate, a);
                if (!f) {
                    return
                }
            } else {
                f = g.target
            }
            if (d.stopEvent === true) {
                g.stopEvent()
            }
            if (d.preventDefault === true) {
                g.preventDefault()
            }
            if (d.stopPropagation === true) {
                g.stopPropagation()
            }
            if (d.normalized === false) {
                g = g.browserEvent
            }
            c.call(b || a, g, f, d)
        };
        if (d.delay) {
            Z = G(Z, d)
        }
        if (d.single) {
            Z = T(Z, a, E, c, b)
        }
        if (d.buffer) {
            Z = V(Z, d)
        }
        O(a, E, c, Z, b);
        return Z
    };
    var K = /^(?:scope|delay|buffer|single|stopEvent|preventDefault|stopPropagation|normalized|args|delegate)$/;
    var U = {
        addListener: function (Y, D, a, Z, E) {
            if (typeof D == "object") {
                var c = D;
                for (var b in c) {
                    if (K.test(b)) {
                        continue
                    }
                    if (typeof c[b] == "function") {
                        L(Y, b, c, c[b], c.scope)
                    } else {
                        L(Y, b, c[b])
                    }
                }
                return
            }
            return L(Y, D, E, a, Z)
        }, removeListener: function (E, D, Z, Y) {
            return I(E, D, Z, Y)
        }, removeAll: function (D) {
            return F(D)
        }, onDocumentReady: function (Y, E, D) {
            if (!X) {
                B()
            }
            if (M || Ext.isReady) {
                D || (D = {});
                Y.defer(D.delay || 0, E)
            } else {
                X.addListener(Y, E, D)
            }
        }, onWindowResize: function (Y, E, D) {
            if (!N) {
                N = new Ext.util.Event();
                W = new Ext.util.DelayedTask(function () {
                    N.fire(R.getViewWidth(), R.getViewHeight())
                });
                P.on(window, "resize", this.fireWindowResize, this)
            }
            N.addListener(Y, E, D)
        }, fireWindowResize: function () {
            if (N) {
                if ((Ext.isIE || Ext.isAir) && W) {
                    W.delay(50)
                } else {
                    N.fire(R.getViewWidth(), R.getViewHeight())
                }
            }
        }, onTextResize: function (Z, Y, D) {
            if (!H) {
                H = new Ext.util.Event();
                var E = new Ext.Element(document.createElement("div"));
                E.dom.className = "x-text-resize";
                E.dom.innerHTML = "X";
                E.appendTo(document.body);
                S = E.dom.offsetHeight;
                setInterval(function () {
                    if (E.dom.offsetHeight != S) {
                        H.fire(S, S = E.dom.offsetHeight)
                    }
                }, this.textResizeInterval)
            }
            H.addListener(Z, Y, D)
        }, removeResizeListener: function (E, D) {
            if (N) {
                N.removeListener(E, D)
            }
        }, fireResize: function () {
            if (N) {
                N.fire(R.getViewWidth(), R.getViewHeight())
            }
        }, ieDeferSrc: false, textResizeInterval: 50
    };
    U.on = U.addListener;
    U.un = U.removeListener;
    U.stoppedMouseDownEvent = new Ext.util.Event();
    return U
}();
Ext.onReady = Ext.EventManager.onDocumentReady;
(function () {
    var A = function () {
        var C = document.body || document.getElementsByTagName("body")[0];
        if (!C) {
            return false
        }
        var B = [" ", Ext.isIE ? "ext-ie " + (Ext.isIE6 ? "ext-ie6" : "ext-ie7") : Ext.isGecko ? "ext-gecko " + (Ext.isGecko2 ? "ext-gecko2" : "ext-gecko3") : Ext.isOpera ? "ext-opera" : Ext.isSafari ? "ext-safari" : ""];
        if (Ext.isMac) {
            B.push("ext-mac")
        }
        if (Ext.isLinux) {
            B.push("ext-linux")
        }
        if (Ext.isBorderBox) {
            B.push("ext-border-box")
        }
        if (Ext.isStrict) {
            var D = C.parentNode;
            if (D) {
                D.className += " ext-strict"
            }
        }
        C.className += B.join(" ");
        return true
    };
    if (!A()) {
        Ext.onReady(A)
    }
})();
Ext.EventObject = function () {
    var B = Ext.lib.Event;
    var A = {3: 13, 63234: 37, 63235: 39, 63232: 38, 63233: 40, 63276: 33, 63277: 34, 63272: 46, 63273: 36, 63275: 35};
    var C = Ext.isIE ? {1: 0, 4: 1, 2: 2} : (Ext.isSafari ? {1: 0, 2: 1, 3: 2} : {0: 0, 1: 1, 2: 2});
    Ext.EventObjectImpl = function (D) {
        if (D) {
            this.setEvent(D.browserEvent || D)
        }
    };
    Ext.EventObjectImpl.prototype = {
        browserEvent: null,
        button: -1,
        shiftKey: false,
        ctrlKey: false,
        altKey: false,
        BACKSPACE: 8,
        TAB: 9,
        NUM_CENTER: 12,
        ENTER: 13,
        RETURN: 13,
        SHIFT: 16,
        CTRL: 17,
        CONTROL: 17,
        ALT: 18,
        PAUSE: 19,
        CAPS_LOCK: 20,
        ESC: 27,
        SPACE: 32,
        PAGE_UP: 33,
        PAGEUP: 33,
        PAGE_DOWN: 34,
        PAGEDOWN: 34,
        END: 35,
        HOME: 36,
        LEFT: 37,
        UP: 38,
        RIGHT: 39,
        DOWN: 40,
        PRINT_SCREEN: 44,
        INSERT: 45,
        DELETE: 46,
        ZERO: 48,
        ONE: 49,
        TWO: 50,
        THREE: 51,
        FOUR: 52,
        FIVE: 53,
        SIX: 54,
        SEVEN: 55,
        EIGHT: 56,
        NINE: 57,
        A: 65,
        B: 66,
        C: 67,
        D: 68,
        E: 69,
        F: 70,
        G: 71,
        H: 72,
        I: 73,
        J: 74,
        K: 75,
        L: 76,
        M: 77,
        N: 78,
        O: 79,
        P: 80,
        Q: 81,
        R: 82,
        S: 83,
        T: 84,
        U: 85,
        V: 86,
        W: 87,
        X: 88,
        Y: 89,
        Z: 90,
        CONTEXT_MENU: 93,
        NUM_ZERO: 96,
        NUM_ONE: 97,
        NUM_TWO: 98,
        NUM_THREE: 99,
        NUM_FOUR: 100,
        NUM_FIVE: 101,
        NUM_SIX: 102,
        NUM_SEVEN: 103,
        NUM_EIGHT: 104,
        NUM_NINE: 105,
        NUM_MULTIPLY: 106,
        NUM_PLUS: 107,
        NUM_MINUS: 109,
        NUM_PERIOD: 110,
        NUM_DIVISION: 111,
        F1: 112,
        F2: 113,
        F3: 114,
        F4: 115,
        F5: 116,
        F6: 117,
        F7: 118,
        F8: 119,
        F9: 120,
        F10: 121,
        F11: 122,
        F12: 123,
        setEvent: function (D) {
            if (D == this || (D && D.browserEvent)) {
                return D
            }
            this.browserEvent = D;
            if (D) {
                this.button = D.button ? C[D.button] : (D.which ? D.which - 1 : -1);
                if (D.type == "click" && this.button == -1) {
                    this.button = 0
                }
                this.type = D.type;
                this.shiftKey = D.shiftKey;
                this.ctrlKey = D.ctrlKey || D.metaKey;
                this.altKey = D.altKey;
                this.keyCode = D.keyCode;
                this.charCode = D.charCode;
                this.target = B.getTarget(D);
                this.xy = B.getXY(D)
            } else {
                this.button = -1;
                this.shiftKey = false;
                this.ctrlKey = false;
                this.altKey = false;
                this.keyCode = 0;
                this.charCode = 0;
                this.target = null;
                this.xy = [0, 0]
            }
            return this
        },
        stopEvent: function () {
            if (this.browserEvent) {
                if (this.browserEvent.type == "mousedown") {
                    Ext.EventManager.stoppedMouseDownEvent.fire(this)
                }
                B.stopEvent(this.browserEvent)
            }
        },
        preventDefault: function () {
            if (this.browserEvent) {
                B.preventDefault(this.browserEvent)
            }
        },
        isNavKeyPress: function () {
            var D = this.keyCode;
            D = Ext.isSafari ? (A[D] || D) : D;
            return (D >= 33 && D <= 40) || D == this.RETURN || D == this.TAB || D == this.ESC
        },
        isSpecialKey: function () {
            var D = this.keyCode;
            return (this.type == "keypress" && this.ctrlKey) || D == 9 || D == 13 || D == 40 || D == 27 || (D == 16) || (D == 17) || (D >= 18 && D <= 20) || (D >= 33 && D <= 35) || (D >= 36 && D <= 39) || (D >= 44 && D <= 45)
        },
        stopPropagation: function () {
            if (this.browserEvent) {
                if (this.browserEvent.type == "mousedown") {
                    Ext.EventManager.stoppedMouseDownEvent.fire(this)
                }
                B.stopPropagation(this.browserEvent)
            }
        },
        getCharCode: function () {
            return this.charCode || this.keyCode
        },
        getKey: function () {
            var D = this.keyCode || this.charCode;
            return Ext.isSafari ? (A[D] || D) : D
        },
        getPageX: function () {
            return this.xy[0]
        },
        getPageY: function () {
            return this.xy[1]
        },
        getTime: function () {
            if (this.browserEvent) {
                return B.getTime(this.browserEvent)
            }
            return null
        },
        getXY: function () {
            return this.xy
        },
        getTarget: function (E, F, D) {
            return E ? Ext.fly(this.target).findParent(E, F, D) : (D ? Ext.get(this.target) : this.target)
        },
        getRelatedTarget: function () {
            if (this.browserEvent) {
                return B.getRelatedTarget(this.browserEvent)
            }
            return null
        },
        getWheelDelta: function () {
            var D = this.browserEvent;
            var E = 0;
            if (D.wheelDelta) {
                E = D.wheelDelta / 120
            } else {
                if (D.detail) {
                    E = -D.detail / 3
                }
            }
            return E
        },
        hasModifier: function () {
            return ((this.ctrlKey || this.altKey) || this.shiftKey) ? true : false
        },
        within: function (E, F) {
            var D = this[F ? "getRelatedTarget" : "getTarget"]();
            return D && Ext.fly(E).contains(D)
        },
        getPoint: function () {
            return new Ext.lib.Point(this.xy[0], this.xy[1])
        }
    };
    return new Ext.EventObjectImpl()
}();


(function () {
    var D = Ext.lib.Dom;
    var E = Ext.lib.Event;
    var A = Ext.lib.Anim;
    var propCache = {};
    var camelRe = /(-[a-z])/gi;
    var camelFn = function (m, a) {
        return a.charAt(1).toUpperCase()
    };
    var view = document.defaultView;
    Ext.Element = function (element, forceNew) {
        var dom = typeof element == "string" ? document.getElementById(element) : element;
        if (!dom) {
            return null
        }
        var id = dom.id;
        if (forceNew !== true && id && Ext.Element.cache[id]) {
            return Ext.Element.cache[id]
        }
        this.dom = dom;
        this.id = id || Ext.id(dom)
    };
    var El = Ext.Element;
    El.prototype = {
        originalDisplay: "", visibilityMode: 1, defaultUnit: "px", setVisibilityMode: function (visMode) {
            this.visibilityMode = visMode;
            return this
        }, enableDisplayMode: function (display) {
            this.setVisibilityMode(El.DISPLAY);
            if (typeof display != "undefined") {
                this.originalDisplay = display
            }
            return this
        }, findParent: function (simpleSelector, maxDepth, returnEl) {
            var p = this.dom, b = document.body, depth = 0, dq = Ext.DomQuery, stopEl;
            maxDepth = maxDepth || 50;
            if (typeof maxDepth != "number") {
                stopEl = Ext.getDom(maxDepth);
                maxDepth = 10
            }
            while (p && p.nodeType == 1 && depth < maxDepth && p != b && p != stopEl) {
                if (dq.is(p, simpleSelector)) {
                    return returnEl ? Ext.get(p) : p
                }
                depth++;
                p = p.parentNode
            }
            return null
        }, findParentNode: function (simpleSelector, maxDepth, returnEl) {
            var p = Ext.fly(this.dom.parentNode, "_internal");
            return p ? p.findParent(simpleSelector, maxDepth, returnEl) : null
        }, up: function (simpleSelector, maxDepth) {
            return this.findParentNode(simpleSelector, maxDepth, true)
        }, is: function (simpleSelector) {
            return Ext.DomQuery.is(this.dom, simpleSelector)
        }, animate: function (args, duration, onComplete, easing, animType) {
            this.anim(args, {duration: duration, callback: onComplete, easing: easing}, animType);
            return this
        }, anim: function (args, opt, animType, defaultDur, defaultEase, cb) {
            animType = animType || "run";
            opt = opt || {};
            var anim = Ext.lib.Anim[animType](this.dom, args, (opt.duration || defaultDur) || 0.35, (opt.easing || defaultEase) || "easeOut", function () {
                Ext.callback(cb, this);
                Ext.callback(opt.callback, opt.scope || this, [this, opt])
            }, this);
            opt.anim = anim;
            return anim
        }, preanim: function (a, i) {
            return !a[i] ? false : (typeof a[i] == "object" ? a[i] : {
                duration: a[i + 1],
                callback: a[i + 2],
                easing: a[i + 3]
            })
        }, clean: function (forceReclean) {
            if (this.isCleaned && forceReclean !== true) {
                return this
            }
            var ns = /\S/;
            var d = this.dom, n = d.firstChild, ni = -1;
            while (n) {
                var nx = n.nextSibling;
                if (n.nodeType == 3 && !ns.test(n.nodeValue)) {
                    d.removeChild(n)
                } else {
                    n.nodeIndex = ++ni
                }
                n = nx
            }
            this.isCleaned = true;
            return this
        }, scrollIntoView: function (container, hscroll) {
            var c = Ext.getDom(container) || Ext.getBody().dom;
            var el = this.dom;
            var o = this.getOffsetsTo(c), l = o[0] + c.scrollLeft, t = o[1] + c.scrollTop, b = t + el.offsetHeight, r = l + el.offsetWidth;
            var ch = c.clientHeight;
            var ct = parseInt(c.scrollTop, 10);
            var cl = parseInt(c.scrollLeft, 10);
            var cb = ct + ch;
            var cr = cl + c.clientWidth;
            if (el.offsetHeight > ch || t < ct) {
                c.scrollTop = t
            } else {
                if (b > cb) {
                    c.scrollTop = b - ch
                }
            }
            c.scrollTop = c.scrollTop;
            if (hscroll !== false) {
                if (el.offsetWidth > c.clientWidth || l < cl) {
                    c.scrollLeft = l
                } else {
                    if (r > cr) {
                        c.scrollLeft = r - c.clientWidth
                    }
                }
                c.scrollLeft = c.scrollLeft
            }
            return this
        }, scrollChildIntoView: function (child, hscroll) {
            Ext.fly(child, "_scrollChildIntoView").scrollIntoView(this, hscroll)
        }, autoHeight: function (animate, duration, onComplete, easing) {
            var oldHeight = this.getHeight();
            this.clip();
            this.setHeight(1);
            setTimeout(function () {
                var height = parseInt(this.dom.scrollHeight, 10);
                if (!animate) {
                    this.setHeight(height);
                    this.unclip();
                    if (typeof onComplete == "function") {
                        onComplete()
                    }
                } else {
                    this.setHeight(oldHeight);
                    this.setHeight(height, animate, duration, function () {
                        this.unclip();
                        if (typeof onComplete == "function") {
                            onComplete()
                        }
                    }.createDelegate(this), easing)
                }
            }.createDelegate(this), 0);
            return this
        }, contains: function (el) {
            if (!el) {
                return false
            }
            return D.isAncestor(this.dom, el.dom ? el.dom : el)
        }, isVisible: function (deep) {
            var vis = !(this.getStyle("visibility") == "hidden" || this.getStyle("display") == "none");
            if (deep !== true || !vis) {
                return vis
            }
            var p = this.dom.parentNode;
            while (p && p.tagName.toLowerCase() != "body") {
                if (!Ext.fly(p, "_isVisible").isVisible()) {
                    return false
                }
                p = p.parentNode
            }
            return true
        }, select: function (selector, unique) {
            return El.select(selector, unique, this.dom)
        }, query: function (selector) {
            return Ext.DomQuery.select(selector, this.dom)
        }, child: function (selector, returnDom) {
            var n = Ext.DomQuery.selectNode(selector, this.dom);
            return returnDom ? n : Ext.get(n)
        }, down: function (selector, returnDom) {
            var n = Ext.DomQuery.selectNode(" > " + selector, this.dom);
            return returnDom ? n : Ext.get(n)
        }, initDD: function (group, config, overrides) {
            var dd = new Ext.dd.DD(Ext.id(this.dom), group, config);
            return Ext.apply(dd, overrides)
        }, initDDProxy: function (group, config, overrides) {
            var dd = new Ext.dd.DDProxy(Ext.id(this.dom), group, config);
            return Ext.apply(dd, overrides)
        }, initDDTarget: function (group, config, overrides) {
            var dd = new Ext.dd.DDTarget(Ext.id(this.dom), group, config);
            return Ext.apply(dd, overrides)
        }, setVisible: function (visible, animate) {
            if (!animate || !A) {
                if (this.visibilityMode == El.DISPLAY) {
                    this.setDisplayed(visible)
                } else {
                    this.fixDisplay();
                    this.dom.style.visibility = visible ? "visible" : "hidden"
                }
            } else {
                var dom = this.dom;
                var visMode = this.visibilityMode;
                if (visible) {
                    this.setOpacity(0.01);
                    this.setVisible(true)
                }
                this.anim({opacity: {to: (visible ? 1 : 0)}}, this.preanim(arguments, 1), null, 0.35, "easeIn", function () {
                    if (!visible) {
                        if (visMode == El.DISPLAY) {
                            dom.style.display = "none"
                        } else {
                            dom.style.visibility = "hidden"
                        }
                        Ext.get(dom).setOpacity(1)
                    }
                })
            }
            return this
        }, isDisplayed: function () {
            return this.getStyle("display") != "none"
        }, toggle: function (animate) {
            this.setVisible(!this.isVisible(), this.preanim(arguments, 0));
            return this
        }, setDisplayed: function (value) {
            if (typeof value == "boolean") {
                value = value ? this.originalDisplay : "none"
            }
            this.setStyle("display", value);
            return this
        }, focus: function () {
            try {
                this.dom.focus()
            } catch (e) {
            }
            return this
        }, blur: function () {
            try {
                this.dom.blur()
            } catch (e) {
            }
            return this
        }, addClass: function (className) {
            if (Ext.isArray(className)) {
                for (var i = 0, len = className.length; i < len; i++) {
                    this.addClass(className[i])
                }
            } else {
                if (className && !this.hasClass(className)) {
                    this.dom.className = this.dom.className + " " + className
                }
            }
            return this
        }, radioClass: function (className) {
            var siblings = this.dom.parentNode.childNodes;
            for (var i = 0; i < siblings.length; i++) {
                var s = siblings[i];
                if (s.nodeType == 1) {
                    Ext.get(s).removeClass(className)
                }
            }
            this.addClass(className);
            return this
        }, removeClass: function (className) {
            if (!className || !this.dom.className) {
                return this
            }
            if (Ext.isArray(className)) {
                for (var i = 0, len = className.length; i < len; i++) {
                    this.removeClass(className[i])
                }
            } else {
                if (this.hasClass(className)) {
                    var re = this.classReCache[className];
                    if (!re) {
                        re = new RegExp("(?:^|\\s+)" + className + "(?:\\s+|$)", "g");
                        this.classReCache[className] = re
                    }
                    this.dom.className = this.dom.className.replace(re, " ")
                }
            }
            return this
        }, classReCache: {}, toggleClass: function (className) {
            if (this.hasClass(className)) {
                this.removeClass(className)
            } else {
                this.addClass(className)
            }
            return this
        }, hasClass: function (className) {
            return className && (" " + this.dom.className + " ").indexOf(" " + className + " ") != -1
        }, replaceClass: function (oldClassName, newClassName) {
            this.removeClass(oldClassName);
            this.addClass(newClassName);
            return this
        }, getStyles: function () {
            var a = arguments, len = a.length, r = {};
            for (var i = 0; i < len; i++) {
                r[a[i]] = this.getStyle(a[i])
            }
            return r
        }, getStyle: function () {
            return view && view.getComputedStyle ? function (prop) {
                var el = this.dom, v, cs, camel;
                if (prop == "float") {
                    prop = "cssFloat"
                }
                if (v = el.style[prop]) {
                    return v
                }
                if (cs = view.getComputedStyle(el, "")) {
                    if (!(camel = propCache[prop])) {
                        camel = propCache[prop] = prop.replace(camelRe, camelFn)
                    }
                    return cs[camel]
                }
                return null
            } : function (prop) {
                var el = this.dom, v, cs, camel;
                if (prop == "opacity") {
                    if (typeof el.style.filter == "string") {
                        var m = el.style.filter.match(/alpha\(opacity=(.*)\)/i);
                        if (m) {
                            var fv = parseFloat(m[1]);
                            if (!isNaN(fv)) {
                                return fv ? fv / 100 : 0
                            }
                        }
                    }
                    return 1
                } else {
                    if (prop == "float") {
                        prop = "styleFloat"
                    }
                }
                if (!(camel = propCache[prop])) {
                    camel = propCache[prop] = prop.replace(camelRe, camelFn)
                }
                if (v = el.style[camel]) {
                    return v
                }
                if (cs = el.currentStyle) {
                    return cs[camel]
                }
                return null
            }
        }(), setStyle: function (prop, value) {
            if (typeof prop == "string") {
                var camel;
                if (!(camel = propCache[prop])) {
                    camel = propCache[prop] = prop.replace(camelRe, camelFn)
                }
                if (camel == "opacity") {
                    this.setOpacity(value)
                } else {
                    this.dom.style[camel] = value
                }
            } else {
                for (var style in prop) {
                    if (typeof prop[style] != "function") {
                        this.setStyle(style, prop[style])
                    }
                }
            }
            return this
        }, applyStyles: function (style) {
            Ext.DomHelper.applyStyles(this.dom, style);
            return this
        }, getX: function () {
            return D.getX(this.dom)
        }, getY: function () {
            return D.getY(this.dom)
        }, getXY: function () {
            return D.getXY(this.dom)
        }, getOffsetsTo: function (el) {
            var o = this.getXY();
            var e = Ext.fly(el, "_internal").getXY();
            return [o[0] - e[0], o[1] - e[1]]
        }, setX: function (x, animate) {
            if (!animate || !A) {
                D.setX(this.dom, x)
            } else {
                this.setXY([x, this.getY()], this.preanim(arguments, 1))
            }
            return this
        }, setY: function (y, animate) {
            if (!animate || !A) {
                D.setY(this.dom, y)
            } else {
                this.setXY([this.getX(), y], this.preanim(arguments, 1))
            }
            return this
        }, setLeft: function (left) {
            this.setStyle("left", this.addUnits(left));
            return this
        }, setTop: function (top) {
            this.setStyle("top", this.addUnits(top));
            return this
        }, setRight: function (right) {
            this.setStyle("right", this.addUnits(right));
            return this
        }, setBottom: function (bottom) {
            this.setStyle("bottom", this.addUnits(bottom));
            return this
        }, setXY: function (pos, animate) {
            if (!animate || !A) {
                D.setXY(this.dom, pos)
            } else {
                this.anim({points: {to: pos}}, this.preanim(arguments, 1), "motion")
            }
            return this
        }, setLocation: function (x, y, animate) {
            this.setXY([x, y], this.preanim(arguments, 2));
            return this
        }, moveTo: function (x, y, animate) {
            this.setXY([x, y], this.preanim(arguments, 2));
            return this
        }, getRegion: function () {
            return D.getRegion(this.dom)
        }, getHeight: function (contentHeight) {
            var h = this.dom.offsetHeight || 0;
            h = contentHeight !== true ? h : h - this.getBorderWidth("tb") - this.getPadding("tb");
            return h < 0 ? 0 : h
        }, getWidth: function (contentWidth) {
            var w = this.dom.offsetWidth || 0;
            w = contentWidth !== true ? w : w - this.getBorderWidth("lr") - this.getPadding("lr");
            return w < 0 ? 0 : w
        }, getComputedHeight: function () {
            var h = Math.max(this.dom.offsetHeight, this.dom.clientHeight);
            if (!h) {
                h = parseInt(this.getStyle("height"), 10) || 0;
                if (!this.isBorderBox()) {
                    h += this.getFrameWidth("tb")
                }
            }
            return h
        }, getComputedWidth: function () {
            var w = Math.max(this.dom.offsetWidth, this.dom.clientWidth);
            if (!w) {
                w = parseInt(this.getStyle("width"), 10) || 0;
                if (!this.isBorderBox()) {
                    w += this.getFrameWidth("lr")
                }
            }
            return w
        }, getSize: function (contentSize) {
            return {width: this.getWidth(contentSize), height: this.getHeight(contentSize)}
        }, getStyleSize: function () {
            var w, h, d = this.dom, s = d.style;
            if (s.width && s.width != "auto") {
                w = parseInt(s.width, 10);
                if (Ext.isBorderBox) {
                    w -= this.getFrameWidth("lr")
                }
            }
            if (s.height && s.height != "auto") {
                h = parseInt(s.height, 10);
                if (Ext.isBorderBox) {
                    h -= this.getFrameWidth("tb")
                }
            }
            return {width: w || this.getWidth(true), height: h || this.getHeight(true)}
        }, getViewSize: function () {
            var d = this.dom, doc = document, aw = 0, ah = 0;
            if (d == doc || d == doc.body) {
                return {width: D.getViewWidth(), height: D.getViewHeight()}
            } else {
                return {width: d.clientWidth, height: d.clientHeight}
            }
        }, getValue: function (asNumber) {
            return asNumber ? parseInt(this.dom.value, 10) : this.dom.value
        }, adjustWidth: function (width) {
            if (typeof width == "number") {
                if (this.autoBoxAdjust && !this.isBorderBox()) {
                    width -= (this.getBorderWidth("lr") + this.getPadding("lr"))
                }
                if (width < 0) {
                    width = 0
                }
            }
            return width
        }, adjustHeight: function (height) {
            if (typeof height == "number") {
                if (this.autoBoxAdjust && !this.isBorderBox()) {
                    height -= (this.getBorderWidth("tb") + this.getPadding("tb"))
                }
                if (height < 0) {
                    height = 0
                }
            }
            return height
        }, setWidth: function (width, animate) {
            width = this.adjustWidth(width);
            if (!animate || !A) {
                this.dom.style.width = this.addUnits(width)
            } else {
                this.anim({width: {to: width}}, this.preanim(arguments, 1))
            }
            return this
        }, setHeight: function (height, animate) {
            height = this.adjustHeight(height);
            if (!animate || !A) {
                this.dom.style.height = this.addUnits(height)
            } else {
                this.anim({height: {to: height}}, this.preanim(arguments, 1))
            }
            return this
        }, setSize: function (width, height, animate) {
            if (typeof width == "object") {
                height = width.height;
                width = width.width
            }
            width = this.adjustWidth(width);
            height = this.adjustHeight(height);
            if (!animate || !A) {
                this.dom.style.width = this.addUnits(width);
                this.dom.style.height = this.addUnits(height)
            } else {
                this.anim({width: {to: width}, height: {to: height}}, this.preanim(arguments, 2))
            }
            return this
        }, setBounds: function (x, y, width, height, animate) {
            if (!animate || !A) {
                this.setSize(width, height);
                this.setLocation(x, y)
            } else {
                width = this.adjustWidth(width);
                height = this.adjustHeight(height);
                this.anim({
                    points: {to: [x, y]},
                    width: {to: width},
                    height: {to: height}
                }, this.preanim(arguments, 4), "motion")
            }
            return this
        }, setRegion: function (region, animate) {
            this.setBounds(region.left, region.top, region.right - region.left, region.bottom - region.top, this.preanim(arguments, 1));
            return this
        }, addListener: function (eventName, fn, scope, options) {
            Ext.EventManager.on(this.dom, eventName, fn, scope || this, options)
        }, removeListener: function (eventName, fn, scope) {
            Ext.EventManager.removeListener(this.dom, eventName, fn, scope || this);
            return this
        }, removeAllListeners: function () {
            Ext.EventManager.removeAll(this.dom);
            return this
        }, relayEvent: function (eventName, observable) {
            this.on(eventName, function (e) {
                observable.fireEvent(eventName, e)
            })
        }, setOpacity: function (opacity, animate) {
            if (!animate || !A) {
                var s = this.dom.style;
                if (Ext.isIE) {
                    s.zoom = 1;
                    s.filter = (s.filter || "").replace(/alpha\([^\)]*\)/gi, "") + (opacity == 1 ? "" : " alpha(opacity=" + opacity * 100 + ")")
                } else {
                    s.opacity = opacity
                }
            } else {
                this.anim({opacity: {to: opacity}}, this.preanim(arguments, 1), null, 0.35, "easeIn")
            }
            return this
        }, getLeft: function (local) {
            if (!local) {
                return this.getX()
            } else {
                return parseInt(this.getStyle("left"), 10) || 0
            }
        }, getRight: function (local) {
            if (!local) {
                return this.getX() + this.getWidth()
            } else {
                return (this.getLeft(true) + this.getWidth()) || 0
            }
        }, getTop: function (local) {
            if (!local) {
                return this.getY()
            } else {
                return parseInt(this.getStyle("top"), 10) || 0
            }
        }, getBottom: function (local) {
            if (!local) {
                return this.getY() + this.getHeight()
            } else {
                return (this.getTop(true) + this.getHeight()) || 0
            }
        }, position: function (pos, zIndex, x, y) {
            if (!pos) {
                if (this.getStyle("position") == "static") {
                    this.setStyle("position", "relative")
                }
            } else {
                this.setStyle("position", pos)
            }
            if (zIndex) {
                this.setStyle("z-index", zIndex)
            }
            if (x !== undefined && y !== undefined) {
                this.setXY([x, y])
            } else {
                if (x !== undefined) {
                    this.setX(x)
                } else {
                    if (y !== undefined) {
                        this.setY(y)
                    }
                }
            }
        }, clearPositioning: function (value) {
            value = value || "";
            this.setStyle({
                "left": value,
                "right": value,
                "top": value,
                "bottom": value,
                "z-index": "",
                "position": "static"
            });
            return this
        }, getPositioning: function () {
            var l = this.getStyle("left");
            var t = this.getStyle("top");
            return {
                "position": this.getStyle("position"),
                "left": l,
                "right": l ? "" : this.getStyle("right"),
                "top": t,
                "bottom": t ? "" : this.getStyle("bottom"),
                "z-index": this.getStyle("z-index")
            }
        }, getBorderWidth: function (side) {
            return this.addStyles(side, El.borders)
        }, getPadding: function (side) {
            return this.addStyles(side, El.paddings)
        }, setPositioning: function (pc) {
            this.applyStyles(pc);
            if (pc.right == "auto") {
                this.dom.style.right = ""
            }
            if (pc.bottom == "auto") {
                this.dom.style.bottom = ""
            }
            return this
        }, fixDisplay: function () {
            if (this.getStyle("display") == "none") {
                this.setStyle("visibility", "hidden");
                this.setStyle("display", this.originalDisplay);
                if (this.getStyle("display") == "none") {
                    this.setStyle("display", "block")
                }
            }
        }, setOverflow: function (v) {
            if (v == "auto" && Ext.isMac && Ext.isGecko2) {
                this.dom.style.overflow = "hidden";
                (function () {
                    this.dom.style.overflow = "auto"
                }).defer(1, this)
            } else {
                this.dom.style.overflow = v
            }
        }, setLeftTop: function (left, top) {
            this.dom.style.left = this.addUnits(left);
            this.dom.style.top = this.addUnits(top);
            return this
        }, move: function (direction, distance, animate) {
            var xy = this.getXY();
            direction = direction.toLowerCase();
            switch (direction) {
                case"l":
                case"left":
                    this.moveTo(xy[0] - distance, xy[1], this.preanim(arguments, 2));
                    break;
                case"r":
                case"right":
                    this.moveTo(xy[0] + distance, xy[1], this.preanim(arguments, 2));
                    break;
                case"t":
                case"top":
                case"up":
                    this.moveTo(xy[0], xy[1] - distance, this.preanim(arguments, 2));
                    break;
                case"b":
                case"bottom":
                case"down":
                    this.moveTo(xy[0], xy[1] + distance, this.preanim(arguments, 2));
                    break
            }
            return this
        }, clip: function () {
            if (!this.isClipped) {
                this.isClipped = true;
                this.originalClip = {
                    "o": this.getStyle("overflow"),
                    "x": this.getStyle("overflow-x"),
                    "y": this.getStyle("overflow-y")
                };
                this.setStyle("overflow", "hidden");
                this.setStyle("overflow-x", "hidden");
                this.setStyle("overflow-y", "hidden")
            }
            return this
        }, unclip: function () {
            if (this.isClipped) {
                this.isClipped = false;
                var o = this.originalClip;
                if (o.o) {
                    this.setStyle("overflow", o.o)
                }
                if (o.x) {
                    this.setStyle("overflow-x", o.x)
                }
                if (o.y) {
                    this.setStyle("overflow-y", o.y)
                }
            }
            return this
        }, getAnchorXY: function (anchor, local, s) {
            var w, h, vp = false;
            if (!s) {
                var d = this.dom;
                if (d == document.body || d == document) {
                    vp = true;
                    w = D.getViewWidth();
                    h = D.getViewHeight()
                } else {
                    w = this.getWidth();
                    h = this.getHeight()
                }
            } else {
                w = s.width;
                h = s.height
            }
            var x = 0, y = 0, r = Math.round;
            switch ((anchor || "tl").toLowerCase()) {
                case"c":
                    x = r(w * 0.5);
                    y = r(h * 0.5);
                    break;
                case"t":
                    x = r(w * 0.5);
                    y = 0;
                    break;
                case"l":
                    x = 0;
                    y = r(h * 0.5);
                    break;
                case"r":
                    x = w;
                    y = r(h * 0.5);
                    break;
                case"b":
                    x = r(w * 0.5);
                    y = h;
                    break;
                case"tl":
                    x = 0;
                    y = 0;
                    break;
                case"bl":
                    x = 0;
                    y = h;
                    break;
                case"br":
                    x = w;
                    y = h;
                    break;
                case"tr":
                    x = w;
                    y = 0;
                    break
            }
            if (local === true) {
                return [x, y]
            }
            if (vp) {
                var sc = this.getScroll();
                return [x + sc.left, y + sc.top]
            }
            var o = this.getXY();
            return [x + o[0], y + o[1]]
        }, getAlignToXY: function (el, p, o) {
            el = Ext.get(el);
            if (!el || !el.dom) {
                throw"Element.alignToXY with an element that doesn't exist"
            }
            var d = this.dom;
            var c = false;
            var p1 = "", p2 = "";
            o = o || [0, 0];
            if (!p) {
                p = "tl-bl"
            } else {
                if (p == "?") {
                    p = "tl-bl?"
                } else {
                    if (p.indexOf("-") == -1) {
                        p = "tl-" + p
                    }
                }
            }
            p = p.toLowerCase();
            var m = p.match(/^([a-z]+)-([a-z]+)(\?)?$/);
            if (!m) {
                throw"Element.alignTo with an invalid alignment " + p
            }
            p1 = m[1];
            p2 = m[2];
            c = !!m[3];
            var a1 = this.getAnchorXY(p1, true);
            var a2 = el.getAnchorXY(p2, false);
            var x = a2[0] - a1[0] + o[0];
            var y = a2[1] - a1[1] + o[1];
            if (c) {
                var w = this.getWidth(), h = this.getHeight(), r = el.getRegion();
                var dw = D.getViewWidth() - 5, dh = D.getViewHeight() - 5;
                var p1y = p1.charAt(0), p1x = p1.charAt(p1.length - 1);
                var p2y = p2.charAt(0), p2x = p2.charAt(p2.length - 1);
                var swapY = ((p1y == "t" && p2y == "b") || (p1y == "b" && p2y == "t"));
                var swapX = ((p1x == "r" && p2x == "l") || (p1x == "l" && p2x == "r"));
                var doc = document;
                var scrollX = (doc.documentElement.scrollLeft || doc.body.scrollLeft || 0) + 5;
                var scrollY = (doc.documentElement.scrollTop || doc.body.scrollTop || 0) + 5;
                if ((x + w) > dw + scrollX) {
                    x = swapX ? r.left - w : dw + scrollX - w
                }
                if (x < scrollX) {
                    x = swapX ? r.right : scrollX
                }
                if ((y + h) > dh + scrollY) {
                    y = swapY ? r.top - h : dh + scrollY - h
                }
                if (y < scrollY) {
                    y = swapY ? r.bottom : scrollY
                }
            }
            return [x, y]
        }, getConstrainToXY: function () {
            var os = {top: 0, left: 0, bottom: 0, right: 0};
            return function (el, local, offsets, proposedXY) {
                el = Ext.get(el);
                offsets = offsets ? Ext.applyIf(offsets, os) : os;
                var vw, vh, vx = 0, vy = 0;
                if (el.dom == document.body || el.dom == document) {
                    vw = Ext.lib.Dom.getViewWidth();
                    vh = Ext.lib.Dom.getViewHeight()
                } else {
                    vw = el.dom.clientWidth;
                    vh = el.dom.clientHeight;
                    if (!local) {
                        var vxy = el.getXY();
                        vx = vxy[0];
                        vy = vxy[1]
                    }
                }
                var s = el.getScroll();
                vx += offsets.left + s.left;
                vy += offsets.top + s.top;
                vw -= offsets.right;
                vh -= offsets.bottom;
                var vr = vx + vw;
                var vb = vy + vh;
                var xy = proposedXY || (!local ? this.getXY() : [this.getLeft(true), this.getTop(true)]);
                var x = xy[0], y = xy[1];
                var w = this.dom.offsetWidth, h = this.dom.offsetHeight;
                var moved = false;
                if ((x + w) > vr) {
                    x = vr - w;
                    moved = true
                }
                if ((y + h) > vb) {
                    y = vb - h;
                    moved = true
                }
                if (x < vx) {
                    x = vx;
                    moved = true
                }
                if (y < vy) {
                    y = vy;
                    moved = true
                }
                return moved ? [x, y] : false
            }
        }(), adjustForConstraints: function (xy, parent, offsets) {
            return this.getConstrainToXY(parent || document, false, offsets, xy) || xy
        }, alignTo: function (element, position, offsets, animate) {
            var xy = this.getAlignToXY(element, position, offsets);
            this.setXY(xy, this.preanim(arguments, 3));
            return this
        }, anchorTo: function (el, alignment, offsets, animate, monitorScroll, callback) {
            var action = function () {
                this.alignTo(el, alignment, offsets, animate);
                Ext.callback(callback, this)
            };
            Ext.EventManager.onWindowResize(action, this);
            var tm = typeof monitorScroll;
            if (tm != "undefined") {
                Ext.EventManager.on(window, "scroll", action, this, {buffer: tm == "number" ? monitorScroll : 50})
            }
            action.call(this);
            return this
        }, clearOpacity: function () {
            if (window.ActiveXObject) {
                if (typeof this.dom.style.filter == "string" && (/alpha/i).test(this.dom.style.filter)) {
                    this.dom.style.filter = ""
                }
            } else {
                this.dom.style.opacity = "";
                this.dom.style["-moz-opacity"] = "";
                this.dom.style["-khtml-opacity"] = ""
            }
            return this
        }, hide: function (animate) {
            this.setVisible(false, this.preanim(arguments, 0));
            return this
        }, show: function (animate) {
            this.setVisible(true, this.preanim(arguments, 0));
            return this
        }, addUnits: function (size) {
            return Ext.Element.addUnits(size, this.defaultUnit)
        }, update: function (html, loadScripts, callback) {
            if (typeof html == "undefined") {
                html = ""
            }
            if (loadScripts !== true) {
                this.dom.innerHTML = html;
                if (typeof callback == "function") {
                    callback()
                }
                return this
            }
            var id = Ext.id();
            var dom = this.dom;
            html += "<span id=\"" + id + "\"></span>";
            E.onAvailable(id, function () {
                var hd = document.getElementsByTagName("head")[0];
                var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
                var srcRe = /\ssrc=([\'\"])(.*?)\1/i;
                var typeRe = /\stype=([\'\"])(.*?)\1/i;
                var match;
                while (match = re.exec(html)) {
                    var attrs = match[1];
                    var srcMatch = attrs ? attrs.match(srcRe) : false;
                    if (srcMatch && srcMatch[2]) {
                        var s = document.createElement("script");
                        s.src = srcMatch[2];
                        var typeMatch = attrs.match(typeRe);
                        if (typeMatch && typeMatch[2]) {
                            s.type = typeMatch[2]
                        }
                        hd.appendChild(s)
                    } else {
                        if (match[2] && match[2].length > 0) {
                            if (window.execScript) {
                                window.execScript(match[2])
                            } else {
                                window.eval(match[2])
                            }
                        }
                    }
                }
                var el = document.getElementById(id);
                if (el) {
                    Ext.removeNode(el)
                }
                if (typeof callback == "function") {
                    callback()
                }
            });
            dom.innerHTML = html.replace(/(?:<script.*?>)((\n|\r|.)*?)(?:<\/script>)/ig, "");
            return this
        }, load: function () {
            var um = this.getUpdater();
            um.update.apply(um, arguments);
            return this
        }, getUpdater: function () {
            if (!this.updateManager) {
                this.updateManager = new Ext.Updater(this)
            }
            return this.updateManager
        }, unselectable: function () {
            this.dom.unselectable = "on";
            this.swallowEvent("selectstart", true);
            this.applyStyles("-moz-user-select:none;-khtml-user-select:none;");
            this.addClass("x-unselectable");
            return this
        }, getCenterXY: function () {
            return this.getAlignToXY(document, "c-c")
        }, center: function (centerIn) {
            this.alignTo(centerIn || document, "c-c");
            return this
        }, isBorderBox: function () {
            return noBoxAdjust[this.dom.tagName.toLowerCase()] || Ext.isBorderBox
        }, getBox: function (contentBox, local) {
            var xy;
            if (!local) {
                xy = this.getXY()
            } else {
                var left = parseInt(this.getStyle("left"), 10) || 0;
                var top = parseInt(this.getStyle("top"), 10) || 0;
                xy = [left, top]
            }
            var el = this.dom, w = el.offsetWidth, h = el.offsetHeight, bx;
            if (!contentBox) {
                bx = {x: xy[0], y: xy[1], 0: xy[0], 1: xy[1], width: w, height: h}
            } else {
                var l = this.getBorderWidth("l") + this.getPadding("l");
                var r = this.getBorderWidth("r") + this.getPadding("r");
                var t = this.getBorderWidth("t") + this.getPadding("t");
                var b = this.getBorderWidth("b") + this.getPadding("b");
                bx = {x: xy[0] + l, y: xy[1] + t, 0: xy[0] + l, 1: xy[1] + t, width: w - (l + r), height: h - (t + b)}
            }
            bx.right = bx.x + bx.width;
            bx.bottom = bx.y + bx.height;
            return bx
        }, getFrameWidth: function (sides, onlyContentBox) {
            return onlyContentBox && Ext.isBorderBox ? 0 : (this.getPadding(sides) + this.getBorderWidth(sides))
        }, setBox: function (box, adjust, animate) {
            var w = box.width, h = box.height;
            if ((adjust && !this.autoBoxAdjust) && !this.isBorderBox()) {
                w -= (this.getBorderWidth("lr") + this.getPadding("lr"));
                h -= (this.getBorderWidth("tb") + this.getPadding("tb"))
            }
            this.setBounds(box.x, box.y, w, h, this.preanim(arguments, 2));
            return this
        }, repaint: function () {
            var dom = this.dom;
            this.addClass("x-repaint");
            setTimeout(function () {
                Ext.get(dom).removeClass("x-repaint")
            }, 1);
            return this
        }, getMargins: function (side) {
            if (!side) {
                return {
                    top: parseInt(this.getStyle("margin-top"), 10) || 0,
                    left: parseInt(this.getStyle("margin-left"), 10) || 0,
                    bottom: parseInt(this.getStyle("margin-bottom"), 10) || 0,
                    right: parseInt(this.getStyle("margin-right"), 10) || 0
                }
            } else {
                return this.addStyles(side, El.margins)
            }
        }, addStyles: function (sides, styles) {
            var val = 0, v, w;
            for (var i = 0, len = sides.length; i < len; i++) {
                v = this.getStyle(styles[sides.charAt(i)]);
                if (v) {
                    w = parseInt(v, 10);
                    if (w) {
                        val += (w >= 0 ? w : -1 * w)
                    }
                }
            }
            return val
        }, createProxy: function (config, renderTo, matchBox) {
            config = typeof config == "object" ? config : {tag: "div", cls: config};
            var proxy;
            if (renderTo) {
                proxy = Ext.DomHelper.append(renderTo, config, true)
            } else {
                proxy = Ext.DomHelper.insertBefore(this.dom, config, true)
            }
            if (matchBox) {
                proxy.setBox(this.getBox())
            }
            return proxy
        }, mask: function (msg, msgCls) {
            if (this.getStyle("position") == "static") {
                this.setStyle("position", "relative")
            }
            if (this._maskMsg) {
                this._maskMsg.remove()
            }
            if (this._mask) {
                this._mask.remove()
            }
            this._mask = Ext.DomHelper.append(this.dom, {cls: "ext-el-mask"}, true);
            this.addClass("x-masked");
            this._mask.setDisplayed(true);
            if (typeof msg == "string") {
                this._maskMsg = Ext.DomHelper.append(this.dom, {cls: "ext-el-mask-msg", cn: {tag: "div"}}, true);
                var mm = this._maskMsg;
                mm.dom.className = msgCls ? "ext-el-mask-msg " + msgCls : "ext-el-mask-msg";
                mm.dom.firstChild.innerHTML = msg;
                mm.setDisplayed(true);
                mm.center(this)
            }
            if (Ext.isIE && !(Ext.isIE7 && Ext.isStrict) && this.getStyle("height") == "auto") {
                this._mask.setSize(this.dom.clientWidth, this.getHeight())
            }
            return this._mask
        }, unmask: function () {
            if (this._mask) {
                if (this._maskMsg) {
                    this._maskMsg.remove();
                    delete this._maskMsg
                }
                this._mask.remove();
                delete this._mask
            }
            this.removeClass("x-masked")
        }, isMasked: function () {
            return this._mask && this._mask.isVisible()
        }, createShim: function () {
            var el = document.createElement("iframe");
            el.frameBorder = "0";
            el.className = "ext-shim";
            if (Ext.isIE && Ext.isSecure) {
                el.src = Ext.SSL_SECURE_URL
            }
            var shim = Ext.get(this.dom.parentNode.insertBefore(el, this.dom));
            shim.autoBoxAdjust = false;
            return shim
        }, remove: function () {
            Ext.removeNode(this.dom);
            delete El.cache[this.dom.id]
        }, hover: function (overFn, outFn, scope) {
            var preOverFn = function (e) {
                if (!e.within(this, true)) {
                    overFn.apply(scope || this, arguments)
                }
            };
            var preOutFn = function (e) {
                if (!e.within(this, true)) {
                    outFn.apply(scope || this, arguments)
                }
            };
            this.on("mouseover", preOverFn, this.dom);
            this.on("mouseout", preOutFn, this.dom);
            return this
        }, addClassOnOver: function (className) {
            this.hover(function () {
                Ext.fly(this, "_internal").addClass(className)
            }, function () {
                Ext.fly(this, "_internal").removeClass(className)
            });
            return this
        }, addClassOnFocus: function (className) {
            this.on("focus", function () {
                Ext.fly(this, "_internal").addClass(className)
            }, this.dom);
            this.on("blur", function () {
                Ext.fly(this, "_internal").removeClass(className)
            }, this.dom);
            return this
        }, addClassOnClick: function (className) {
            var dom = this.dom;
            this.on("mousedown", function () {
                Ext.fly(dom, "_internal").addClass(className);
                var d = Ext.getDoc();
                var fn = function () {
                    Ext.fly(dom, "_internal").removeClass(className);
                    d.removeListener("mouseup", fn)
                };
                d.on("mouseup", fn)
            });
            return this
        }, swallowEvent: function (eventName, preventDefault) {
            var fn = function (e) {
                e.stopPropagation();
                if (preventDefault) {
                    e.preventDefault()
                }
            };
            if (Ext.isArray(eventName)) {
                for (var i = 0, len = eventName.length; i < len; i++) {
                    this.on(eventName[i], fn)
                }
                return this
            }
            this.on(eventName, fn);
            return this
        }, parent: function (selector, returnDom) {
            return this.matchNode("parentNode", "parentNode", selector, returnDom)
        }, next: function (selector, returnDom) {
            return this.matchNode("nextSibling", "nextSibling", selector, returnDom)
        }, prev: function (selector, returnDom) {
            return this.matchNode("previousSibling", "previousSibling", selector, returnDom)
        }, first: function (selector, returnDom) {
            return this.matchNode("nextSibling", "firstChild", selector, returnDom)
        }, last: function (selector, returnDom) {
            return this.matchNode("previousSibling", "lastChild", selector, returnDom)
        }, matchNode: function (dir, start, selector, returnDom) {
            var n = this.dom[start];
            while (n) {
                if (n.nodeType == 1 && (!selector || Ext.DomQuery.is(n, selector))) {
                    return !returnDom ? Ext.get(n) : n
                }
                n = n[dir]
            }
            return null
        }, appendChild: function (el) {
            el = Ext.get(el);
            el.appendTo(this);
            return this
        }, createChild: function (config, insertBefore, returnDom) {
            config = config || {tag: "div"};
            if (insertBefore) {
                return Ext.DomHelper.insertBefore(insertBefore, config, returnDom !== true)
            }
            return Ext.DomHelper[!this.dom.firstChild ? "overwrite" : "append"](this.dom, config, returnDom !== true)
        }, appendTo: function (el) {
            el = Ext.getDom(el);
            el.appendChild(this.dom);
            return this
        }, insertBefore: function (el) {
            el = Ext.getDom(el);
            el.parentNode.insertBefore(this.dom, el);
            return this
        }, insertAfter: function (el) {
            el = Ext.getDom(el);
            el.parentNode.insertBefore(this.dom, el.nextSibling);
            return this
        }, insertFirst: function (el, returnDom) {
            el = el || {};
            if (typeof el == "object" && !el.nodeType && !el.dom) {
                return this.createChild(el, this.dom.firstChild, returnDom)
            } else {
                el = Ext.getDom(el);
                this.dom.insertBefore(el, this.dom.firstChild);
                return !returnDom ? Ext.get(el) : el
            }
        }, insertSibling: function (el, where, returnDom) {
            var rt;
            if (Ext.isArray(el)) {
                for (var i = 0, len = el.length; i < len; i++) {
                    rt = this.insertSibling(el[i], where, returnDom)
                }
                return rt
            }
            where = where ? where.toLowerCase() : "before";
            el = el || {};
            var refNode = where == "before" ? this.dom : this.dom.nextSibling;
            if (typeof el == "object" && !el.nodeType && !el.dom) {
                if (where == "after" && !this.dom.nextSibling) {
                    rt = Ext.DomHelper.append(this.dom.parentNode, el, !returnDom)
                } else {
                    rt = Ext.DomHelper[where == "after" ? "insertAfter" : "insertBefore"](this.dom, el, !returnDom)
                }
            } else {
                rt = this.dom.parentNode.insertBefore(Ext.getDom(el), refNode);
                if (!returnDom) {
                    rt = Ext.get(rt)
                }
            }
            return rt
        }, wrap: function (config, returnDom) {
            if (!config) {
                config = {tag: "div"}
            }
            var newEl = Ext.DomHelper.insertBefore(this.dom, config, !returnDom);
            newEl.dom ? newEl.dom.appendChild(this.dom) : newEl.appendChild(this.dom);
            return newEl
        }, replace: function (el) {
            el = Ext.get(el);
            this.insertBefore(el);
            el.remove();
            return this
        }, replaceWith: function (el) {
            if (typeof el == "object" && !el.nodeType && !el.dom) {
                el = this.insertSibling(el, "before")
            } else {
                el = Ext.getDom(el);
                this.dom.parentNode.insertBefore(el, this.dom)
            }
            El.uncache(this.id);
            this.dom.parentNode.removeChild(this.dom);
            this.dom = el;
            this.id = Ext.id(el);
            El.cache[this.id] = this;
            return this
        }, insertHtml: function (where, html, returnEl) {
            var el = Ext.DomHelper.insertHtml(where, this.dom, html);
            return returnEl ? Ext.get(el) : el
        }, set: function (o, useSet) {
            var el = this.dom;
            useSet = typeof useSet == "undefined" ? (el.setAttribute ? true : false) : useSet;
            for (var attr in o) {
                if (attr == "style" || typeof o[attr] == "function") {
                    continue
                }
                if (attr == "cls") {
                    el.className = o["cls"]
                } else {
                    if (o.hasOwnProperty(attr)) {
                        if (useSet) {
                            el.setAttribute(attr, o[attr])
                        } else {
                            el[attr] = o[attr]
                        }
                    }
                }
            }
            if (o.style) {
                Ext.DomHelper.applyStyles(el, o.style)
            }
            return this
        }, addKeyListener: function (key, fn, scope) {
            var config;
            if (typeof key != "object" || Ext.isArray(key)) {
                config = {key: key, fn: fn, scope: scope}
            } else {
                config = {key: key.key, shift: key.shift, ctrl: key.ctrl, alt: key.alt, fn: fn, scope: scope}
            }
            return new Ext.KeyMap(this, config)
        }, addKeyMap: function (config) {
            return new Ext.KeyMap(this, config)
        }, isScrollable: function () {
            var dom = this.dom;
            return dom.scrollHeight > dom.clientHeight || dom.scrollWidth > dom.clientWidth
        }, scrollTo: function (side, value, animate) {
            var prop = side.toLowerCase() == "left" ? "scrollLeft" : "scrollTop";
            if (!animate || !A) {
                this.dom[prop] = value
            } else {
                var to = prop == "scrollLeft" ? [value, this.dom.scrollTop] : [this.dom.scrollLeft, value];
                this.anim({scroll: {"to": to}}, this.preanim(arguments, 2), "scroll")
            }
            return this
        }, scroll: function (direction, distance, animate) {
            if (!this.isScrollable()) {
                return
            }
            var el = this.dom;
            var l = el.scrollLeft, t = el.scrollTop;
            var w = el.scrollWidth, h = el.scrollHeight;
            var cw = el.clientWidth, ch = el.clientHeight;
            direction = direction.toLowerCase();
            var scrolled = false;
            var a = this.preanim(arguments, 2);
            switch (direction) {
                case"l":
                case"left":
                    if (w - l > cw) {
                        var v = Math.min(l + distance, w - cw);
                        this.scrollTo("left", v, a);
                        scrolled = true
                    }
                    break;
                case"r":
                case"right":
                    if (l > 0) {
                        var v = Math.max(l - distance, 0);
                        this.scrollTo("left", v, a);
                        scrolled = true
                    }
                    break;
                case"t":
                case"top":
                case"up":
                    if (t > 0) {
                        var v = Math.max(t - distance, 0);
                        this.scrollTo("top", v, a);
                        scrolled = true
                    }
                    break;
                case"b":
                case"bottom":
                case"down":
                    if (h - t > ch) {
                        var v = Math.min(t + distance, h - ch);
                        this.scrollTo("top", v, a);
                        scrolled = true
                    }
                    break
            }
            return scrolled
        }, translatePoints: function (x, y) {
            if (typeof x == "object" || Ext.isArray(x)) {
                y = x[1];
                x = x[0]
            }
            var p = this.getStyle("position");
            var o = this.getXY();
            var l = parseInt(this.getStyle("left"), 10);
            var t = parseInt(this.getStyle("top"), 10);
            if (isNaN(l)) {
                l = (p == "relative") ? 0 : this.dom.offsetLeft
            }
            if (isNaN(t)) {
                t = (p == "relative") ? 0 : this.dom.offsetTop
            }
            return {left: (x - o[0] + l), top: (y - o[1] + t)}
        }, getScroll: function () {
            var d = this.dom, doc = document;
            if (d == doc || d == doc.body) {
                var l, t;
                if (Ext.isIE && Ext.isStrict) {
                    l = doc.documentElement.scrollLeft || (doc.body.scrollLeft || 0);
                    t = doc.documentElement.scrollTop || (doc.body.scrollTop || 0)
                } else {
                    l = window.pageXOffset || (doc.body.scrollLeft || 0);
                    t = window.pageYOffset || (doc.body.scrollTop || 0)
                }
                return {left: l, top: t}
            } else {
                return {left: d.scrollLeft, top: d.scrollTop}
            }
        }, getColor: function (attr, defaultValue, prefix) {
            var v = this.getStyle(attr);
            if (!v || v == "transparent" || v == "inherit") {
                return defaultValue
            }
            var color = typeof prefix == "undefined" ? "#" : prefix;
            if (v.substr(0, 4) == "rgb(") {
                var rvs = v.slice(4, v.length - 1).split(",");
                for (var i = 0; i < 3; i++) {
                    var h = parseInt(rvs[i]);
                    var s = h.toString(16);
                    if (h < 16) {
                        s = "0" + s
                    }
                    color += s
                }
            } else {
                if (v.substr(0, 1) == "#") {
                    if (v.length == 4) {
                        for (var i = 1; i < 4; i++) {
                            var c = v.charAt(i);
                            color += c + c
                        }
                    } else {
                        if (v.length == 7) {
                            color += v.substr(1)
                        }
                    }
                }
            }
            return (color.length > 5 ? color.toLowerCase() : defaultValue)
        }, boxWrap: function (cls) {
            cls = cls || "x-box";
            var el = Ext.get(this.insertHtml("beforeBegin", String.format("<div class=\"{0}\">" + El.boxMarkup + "</div>", cls)));
            el.child("." + cls + "-mc").dom.appendChild(this.dom);
            return el
        }, getAttributeNS: Ext.isIE ? function (ns, name) {
            var d = this.dom;
            var type = typeof d[ns + ":" + name];
            if (type != "undefined" && type != "unknown") {
                return d[ns + ":" + name]
            }
            return d[name]
        } : function (ns, name) {
            var d = this.dom;
            return d.getAttributeNS(ns, name) || d.getAttribute(ns + ":" + name) || d.getAttribute(name) || d[name]
        }, getTextWidth: function (text, min, max) {
            return (Ext.util.TextMetrics.measure(this.dom, Ext.value(text, this.dom.innerHTML, true)).width).constrain(min || 0, max || 1000000)
        }
    };
    var ep = El.prototype;
    ep.on = ep.addListener;
    ep.mon = ep.addListener;
    ep.getUpdateManager = ep.getUpdater;
    ep.un = ep.removeListener;
    ep.autoBoxAdjust = true;
    El.unitPattern = /\d+(px|em|%|en|ex|pt|in|cm|mm|pc)$/i;
    El.addUnits = function (v, defaultUnit) {
        if (v === "" || v == "auto") {
            return v
        }
        if (v === undefined) {
            return ""
        }
        if (typeof v == "number" || !El.unitPattern.test(v)) {
            return v + (defaultUnit || "px")
        }
        return v
    };
    El.boxMarkup = "<div class=\"{0}-tl\"><div class=\"{0}-tr\"><div class=\"{0}-tc\"></div></div></div><div class=\"{0}-ml\"><div class=\"{0}-mr\"><div class=\"{0}-mc\"></div></div></div><div class=\"{0}-bl\"><div class=\"{0}-br\"><div class=\"{0}-bc\"></div></div></div>";
    El.VISIBILITY = 1;
    El.DISPLAY = 2;
    El.borders = {l: "border-left-width", r: "border-right-width", t: "border-top-width", b: "border-bottom-width"};
    El.paddings = {l: "padding-left", r: "padding-right", t: "padding-top", b: "padding-bottom"};
    El.margins = {l: "margin-left", r: "margin-right", t: "margin-top", b: "margin-bottom"};
    El.cache = {};
    var docEl;
    El.get = function (el) {
        var ex, elm, id;
        if (!el) {
            return null
        }
        if (typeof el == "string") {
            if (!(elm = document.getElementById(el))) {
                return null
            }
            if (ex = El.cache[el]) {
                ex.dom = elm
            } else {
                ex = El.cache[el] = new El(elm)
            }
            return ex
        } else {
            if (el.tagName) {
                if (!(id = el.id)) {
                    id = Ext.id(el)
                }
                if (ex = El.cache[id]) {
                    ex.dom = el
                } else {
                    ex = El.cache[id] = new El(el)
                }
                return ex
            } else {
                if (el instanceof El) {
                    if (el != docEl) {
                        el.dom = document.getElementById(el.id) || el.dom;
                        El.cache[el.id] = el
                    }
                    return el
                } else {
                    if (el.isComposite) {
                        return el
                    } else {
                        if (Ext.isArray(el)) {
                            return El.select(el)
                        } else {
                            if (el == document) {
                                if (!docEl) {
                                    var f = function () {
                                    };
                                    f.prototype = El.prototype;
                                    docEl = new f();
                                    docEl.dom = document
                                }
                                return docEl
                            }
                        }
                    }
                }
            }
        }
        return null
    };
    El.uncache = function (el) {
        for (var i = 0, a = arguments, len = a.length; i < len; i++) {
            if (a[i]) {
                delete El.cache[a[i].id || a[i]]
            }
        }
    };
    El.garbageCollect = function () {
        if (!Ext.enableGarbageCollector) {
            clearInterval(El.collectorThread);
            return
        }
        for (var eid in El.cache) {
            var el = El.cache[eid], d = el.dom;
            if (!d || !d.parentNode || (!d.offsetParent && !document.getElementById(eid))) {
                delete El.cache[eid];
                if (d && Ext.enableListenerCollection) {
                    Ext.EventManager.removeAll(d)
                }
            }
        }
    };
    El.collectorThreadId = setInterval(El.garbageCollect, 30000);
    var flyFn = function () {
    };
    flyFn.prototype = El.prototype;
    var _cls = new flyFn();
    El.Flyweight = function (dom) {
        this.dom = dom
    };
    El.Flyweight.prototype = _cls;
    El.Flyweight.prototype.isFlyweight = true;
    El._flyweights = {};
    El.fly = function (el, named) {
        named = named || "_global";
        el = Ext.getDom(el);
        if (!el) {
            return null
        }
        if (!El._flyweights[named]) {
            El._flyweights[named] = new El.Flyweight()
        }
        El._flyweights[named].dom = el;
        return El._flyweights[named]
    };
    Ext.get = El.get;
    Ext.fly = El.fly;
    var noBoxAdjust = Ext.isStrict ? {select: 1} : {input: 1, select: 1, textarea: 1};
    if (Ext.isIE || Ext.isGecko) {
        noBoxAdjust["button"] = 1
    }
    Ext.EventManager.on(window, "unload", function () {
        delete El.cache;
        delete El._flyweights
    })
})();


Ext.enableFx = true;
Ext.Fx = {
    slideIn: function (A, C) {
        var B = this.getFxEl();
        C = C || {};
        B.queueFx(C, function () {
            A = A || "t";
            this.fixDisplay();
            var D = this.getFxRestore();
            var I = this.getBox();
            this.setSize(I);
            var F = this.fxWrap(D.pos, C, "hidden");
            var K = this.dom.style;
            K.visibility = "visible";
            K.position = "absolute";
            var E = function () {
                B.fxUnwrap(F, D.pos, C);
                K.width = D.width;
                K.height = D.height;
                B.afterFx(C)
            };
            var J, L = {to: [I.x, I.y]}, H = {to: I.width}, G = {to: I.height};
            switch (A.toLowerCase()) {
                case"t":
                    F.setSize(I.width, 0);
                    K.left = K.bottom = "0";
                    J = {height: G};
                    break;
                case"l":
                    F.setSize(0, I.height);
                    K.right = K.top = "0";
                    J = {width: H};
                    break;
                case"r":
                    F.setSize(0, I.height);
                    F.setX(I.right);
                    K.left = K.top = "0";
                    J = {width: H, points: L};
                    break;
                case"b":
                    F.setSize(I.width, 0);
                    F.setY(I.bottom);
                    K.left = K.top = "0";
                    J = {height: G, points: L};
                    break;
                case"tl":
                    F.setSize(0, 0);
                    K.right = K.bottom = "0";
                    J = {width: H, height: G};
                    break;
                case"bl":
                    F.setSize(0, 0);
                    F.setY(I.y + I.height);
                    K.right = K.top = "0";
                    J = {width: H, height: G, points: L};
                    break;
                case"br":
                    F.setSize(0, 0);
                    F.setXY([I.right, I.bottom]);
                    K.left = K.top = "0";
                    J = {width: H, height: G, points: L};
                    break;
                case"tr":
                    F.setSize(0, 0);
                    F.setX(I.x + I.width);
                    K.left = K.bottom = "0";
                    J = {width: H, height: G, points: L};
                    break
            }
            this.dom.style.visibility = "visible";
            F.show();
            arguments.callee.anim = F.fxanim(J, C, "motion", 0.5, "easeOut", E)
        });
        return this
    }, slideOut: function (A, C) {
        var B = this.getFxEl();
        C = C || {};
        B.queueFx(C, function () {
            A = A || "t";
            var I = this.getFxRestore();
            var D = this.getBox();
            this.setSize(D);
            var G = this.fxWrap(I.pos, C, "visible");
            var F = this.dom.style;
            F.visibility = "visible";
            F.position = "absolute";
            G.setSize(D);
            var J = function () {
                if (C.useDisplay) {
                    B.setDisplayed(false)
                } else {
                    B.hide()
                }
                B.fxUnwrap(G, I.pos, C);
                F.width = I.width;
                F.height = I.height;
                B.afterFx(C)
            };
            var E, H = {to: 0};
            switch (A.toLowerCase()) {
                case"t":
                    F.left = F.bottom = "0";
                    E = {height: H};
                    break;
                case"l":
                    F.right = F.top = "0";
                    E = {width: H};
                    break;
                case"r":
                    F.left = F.top = "0";
                    E = {width: H, points: {to: [D.right, D.y]}};
                    break;
                case"b":
                    F.left = F.top = "0";
                    E = {height: H, points: {to: [D.x, D.bottom]}};
                    break;
                case"tl":
                    F.right = F.bottom = "0";
                    E = {width: H, height: H};
                    break;
                case"bl":
                    F.right = F.top = "0";
                    E = {width: H, height: H, points: {to: [D.x, D.bottom]}};
                    break;
                case"br":
                    F.left = F.top = "0";
                    E = {width: H, height: H, points: {to: [D.x + D.width, D.bottom]}};
                    break;
                case"tr":
                    F.left = F.bottom = "0";
                    E = {width: H, height: H, points: {to: [D.right, D.y]}};
                    break
            }
            arguments.callee.anim = G.fxanim(E, C, "motion", 0.5, "easeOut", J)
        });
        return this
    }, puff: function (B) {
        var A = this.getFxEl();
        B = B || {};
        A.queueFx(B, function () {
            this.clearOpacity();
            this.show();
            var F = this.getFxRestore();
            var D = this.dom.style;
            var G = function () {
                if (B.useDisplay) {
                    A.setDisplayed(false)
                } else {
                    A.hide()
                }
                A.clearOpacity();
                A.setPositioning(F.pos);
                D.width = F.width;
                D.height = F.height;
                D.fontSize = "";
                A.afterFx(B)
            };
            var E = this.getWidth();
            var C = this.getHeight();
            arguments.callee.anim = this.fxanim({
                width: {to: this.adjustWidth(E * 2)},
                height: {to: this.adjustHeight(C * 2)},
                points: {by: [-(E * 0.5), -(C * 0.5)]},
                opacity: {to: 0},
                fontSize: {to: 200, unit: "%"}
            }, B, "motion", 0.5, "easeOut", G)
        });
        return this
    }, switchOff: function (B) {
        var A = this.getFxEl();
        B = B || {};
        A.queueFx(B, function () {
            this.clearOpacity();
            this.clip();
            var D = this.getFxRestore();
            var C = this.dom.style;
            var E = function () {
                if (B.useDisplay) {
                    A.setDisplayed(false)
                } else {
                    A.hide()
                }
                A.clearOpacity();
                A.setPositioning(D.pos);
                C.width = D.width;
                C.height = D.height;
                A.afterFx(B)
            };
            this.fxanim({opacity: {to: 0.3}}, null, null, 0.1, null, function () {
                this.clearOpacity();
                (function () {
                    this.fxanim({
                        height: {to: 1},
                        points: {by: [0, this.getHeight() * 0.5]}
                    }, B, "motion", 0.3, "easeIn", E)
                }).defer(100, this)
            })
        });
        return this
    }, highlight: function (A, C) {
        var B = this.getFxEl();
        C = C || {};
        B.queueFx(C, function () {
            A = A || "ffff9c";
            var D = C.attr || "backgroundColor";
            this.clearOpacity();
            this.show();
            var G = this.getColor(D);
            var H = this.dom.style[D];
            var F = (C.endColor || G) || "ffffff";
            var I = function () {
                B.dom.style[D] = H;
                B.afterFx(C)
            };
            var E = {};
            E[D] = {from: A, to: F};
            arguments.callee.anim = this.fxanim(E, C, "color", 1, "easeIn", I)
        });
        return this
    }, frame: function (A, C, D) {
        var B = this.getFxEl();
        D = D || {};
        B.queueFx(D, function () {
            A = A || "#C3DAF9";
            if (A.length == 6) {
                A = "#" + A
            }
            C = C || 1;
            var G = D.duration || 1;
            this.show();
            var E = this.getBox();
            var F = function () {
                var H = Ext.getBody().createChild({
                    style: {
                        visbility: "hidden",
                        position: "absolute",
                        "z-index": "35000",
                        border: "0px solid " + A
                    }
                });
                var I = Ext.isBorderBox ? 2 : 1;
                H.animate({
                    top: {from: E.y, to: E.y - 20},
                    left: {from: E.x, to: E.x - 20},
                    borderWidth: {from: 0, to: 10},
                    opacity: {from: 1, to: 0},
                    height: {from: E.height, to: (E.height + (20 * I))},
                    width: {from: E.width, to: (E.width + (20 * I))}
                }, G, function () {
                    H.remove();
                    if (--C > 0) {
                        F()
                    } else {
                        B.afterFx(D)
                    }
                })
            };
            F.call(this)
        });
        return this
    }, pause: function (C) {
        var A = this.getFxEl();
        var B = {};
        A.queueFx(B, function () {
            setTimeout(function () {
                A.afterFx(B)
            }, C * 1000)
        });
        return this
    }, fadeIn: function (B) {
        var A = this.getFxEl();
        B = B || {};
        A.queueFx(B, function () {
            this.setOpacity(0);
            this.fixDisplay();
            this.dom.style.visibility = "visible";
            var C = B.endOpacity || 1;
            arguments.callee.anim = this.fxanim({opacity: {to: C}}, B, null, 0.5, "easeOut", function () {
                if (C == 1) {
                    this.clearOpacity()
                }
                A.afterFx(B)
            })
        });
        return this
    }, fadeOut: function (B) {
        var A = this.getFxEl();
        B = B || {};
        A.queueFx(B, function () {
            arguments.callee.anim = this.fxanim({opacity: {to: B.endOpacity || 0}}, B, null, 0.5, "easeOut", function () {
                if (this.visibilityMode == Ext.Element.DISPLAY || B.useDisplay) {
                    this.dom.style.display = "none"
                } else {
                    this.dom.style.visibility = "hidden"
                }
                this.clearOpacity();
                A.afterFx(B)
            })
        });
        return this
    }, scale: function (A, B, C) {
        this.shift(Ext.apply({}, C, {width: A, height: B}));
        return this
    }, shift: function (B) {
        var A = this.getFxEl();
        B = B || {};
        A.queueFx(B, function () {
            var E = {}, D = B.width, F = B.height, C = B.x, H = B.y, G = B.opacity;
            if (D !== undefined) {
                E.width = {to: this.adjustWidth(D)}
            }
            if (F !== undefined) {
                E.height = {to: this.adjustHeight(F)}
            }
            if (B.left !== undefined) {
                E.left = {to: B.left}
            }
            if (B.top !== undefined) {
                E.top = {to: B.top}
            }
            if (B.right !== undefined) {
                E.right = {to: B.right}
            }
            if (B.bottom !== undefined) {
                E.bottom = {to: B.bottom}
            }
            if (C !== undefined || H !== undefined) {
                E.points = {to: [C !== undefined ? C : this.getX(), H !== undefined ? H : this.getY()]}
            }
            if (G !== undefined) {
                E.opacity = {to: G}
            }
            if (B.xy !== undefined) {
                E.points = {to: B.xy}
            }
            arguments.callee.anim = this.fxanim(E, B, "motion", 0.35, "easeOut", function () {
                A.afterFx(B)
            })
        });
        return this
    }, ghost: function (A, C) {
        var B = this.getFxEl();
        C = C || {};
        B.queueFx(C, function () {
            A = A || "b";
            var H = this.getFxRestore();
            var E = this.getWidth(), G = this.getHeight();
            var F = this.dom.style;
            var J = function () {
                if (C.useDisplay) {
                    B.setDisplayed(false)
                } else {
                    B.hide()
                }
                B.clearOpacity();
                B.setPositioning(H.pos);
                F.width = H.width;
                F.height = H.height;
                B.afterFx(C)
            };
            var D = {opacity: {to: 0}, points: {}}, I = D.points;
            switch (A.toLowerCase()) {
                case"t":
                    I.by = [0, -G];
                    break;
                case"l":
                    I.by = [-E, 0];
                    break;
                case"r":
                    I.by = [E, 0];
                    break;
                case"b":
                    I.by = [0, G];
                    break;
                case"tl":
                    I.by = [-E, -G];
                    break;
                case"bl":
                    I.by = [-E, G];
                    break;
                case"br":
                    I.by = [E, G];
                    break;
                case"tr":
                    I.by = [E, -G];
                    break
            }
            arguments.callee.anim = this.fxanim(D, C, "motion", 0.5, "easeOut", J)
        });
        return this
    }, syncFx: function () {
        this.fxDefaults = Ext.apply(this.fxDefaults || {}, {block: false, concurrent: true, stopFx: false});
        return this
    }, sequenceFx: function () {
        this.fxDefaults = Ext.apply(this.fxDefaults || {}, {block: false, concurrent: false, stopFx: false});
        return this
    }, nextFx: function () {
        var A = this.fxQueue[0];
        if (A) {
            A.call(this)
        }
    }, hasActiveFx: function () {
        return this.fxQueue && this.fxQueue[0]
    }, stopFx: function () {
        if (this.hasActiveFx()) {
            var A = this.fxQueue[0];
            if (A && A.anim && A.anim.isAnimated()) {
                this.fxQueue = [A];
                A.anim.stop(true)
            }
        }
        return this
    }, beforeFx: function (A) {
        if (this.hasActiveFx() && !A.concurrent) {
            if (A.stopFx) {
                this.stopFx();
                return true
            }
            return false
        }
        return true
    }, hasFxBlock: function () {
        var A = this.fxQueue;
        return A && A[0] && A[0].block
    }, queueFx: function (C, A) {
        if (!this.fxQueue) {
            this.fxQueue = []
        }
        if (!this.hasFxBlock()) {
            Ext.applyIf(C, this.fxDefaults);
            if (!C.concurrent) {
                var B = this.beforeFx(C);
                A.block = C.block;
                this.fxQueue.push(A);
                if (B) {
                    this.nextFx()
                }
            } else {
                A.call(this)
            }
        }
        return this
    }, fxWrap: function (F, D, C) {
        var B;
        if (!D.wrap || !(B = Ext.get(D.wrap))) {
            var A;
            if (D.fixPosition) {
                A = this.getXY()
            }
            var E = document.createElement("div");
            E.style.visibility = C;
            B = Ext.get(this.dom.parentNode.insertBefore(E, this.dom));
            B.setPositioning(F);
            if (B.getStyle("position") == "static") {
                B.position("relative")
            }
            this.clearPositioning("auto");
            B.clip();
            B.dom.appendChild(this.dom);
            if (A) {
                B.setXY(A)
            }
        }
        return B
    }, fxUnwrap: function (A, C, B) {
        this.clearPositioning();
        this.setPositioning(C);
        if (!B.wrap) {
            A.dom.parentNode.insertBefore(this.dom, A.dom);
            A.remove()
        }
    }, getFxRestore: function () {
        var A = this.dom.style;
        return {pos: this.getPositioning(), width: A.width, height: A.height}
    }, afterFx: function (A) {
        if (A.afterStyle) {
            this.applyStyles(A.afterStyle)
        }
        if (A.afterCls) {
            this.addClass(A.afterCls)
        }
        if (A.remove === true) {
            this.remove()
        }
        Ext.callback(A.callback, A.scope, [this]);
        if (!A.concurrent) {
            this.fxQueue.shift();
            this.nextFx()
        }
    }, getFxEl: function () {
        return Ext.get(this.dom)
    }, fxanim: function (D, E, B, F, C, A) {
        B = B || "run";
        E = E || {};
        var G = Ext.lib.Anim[B](this.dom, D, (E.duration || F) || 0.35, (E.easing || C) || "easeOut", function () {
            Ext.callback(A, this)
        }, this);
        E.anim = G;
        return G
    }
};
Ext.Fx.resize = Ext.Fx.scale;
Ext.apply(Ext.Element.prototype, Ext.Fx);


Ext.CompositeElement = function (A) {
    this.elements = [];
    this.addElements(A)
};
Ext.CompositeElement.prototype = {
    isComposite: true, addElements: function (E) {
        if (!E) {
            return this
        }
        if (typeof E == "string") {
            E = Ext.Element.selectorFunction(E)
        }
        var D = this.elements;
        var B = D.length - 1;
        for (var C = 0, A = E.length; C < A; C++) {
            D[++B] = Ext.get(E[C])
        }
        return this
    }, fill: function (A) {
        this.elements = [];
        this.add(A);
        return this
    }, filter: function (A) {
        var B = [];
        this.each(function (C) {
            if (C.is(A)) {
                B[B.length] = C.dom
            }
        });
        this.fill(B);
        return this
    }, invoke: function (E, B) {
        var D = this.elements;
        for (var C = 0, A = D.length; C < A; C++) {
            Ext.Element.prototype[E].apply(D[C], B)
        }
        return this
    }, add: function (A) {
        if (typeof A == "string") {
            this.addElements(Ext.Element.selectorFunction(A))
        } else {
            if (A.length !== undefined) {
                this.addElements(A)
            } else {
                this.addElements([A])
            }
        }
        return this
    }, each: function (E, D) {
        var C = this.elements;
        for (var B = 0, A = C.length; B < A; B++) {
            if (E.call(D || C[B], C[B], this, B) === false) {
                break
            }
        }
        return this
    }, item: function (A) {
        return this.elements[A] || null
    }, first: function () {
        return this.item(0)
    }, last: function () {
        return this.item(this.elements.length - 1)
    }, getCount: function () {
        return this.elements.length
    }, contains: function (A) {
        return this.indexOf(A) !== -1
    }, indexOf: function (A) {
        return this.elements.indexOf(Ext.get(A))
    }, removeElement: function (D, F) {
        if (Ext.isArray(D)) {
            for (var C = 0, A = D.length; C < A; C++) {
                this.removeElement(D[C])
            }
            return this
        }
        var B = typeof D == "number" ? D : this.indexOf(D);
        if (B !== -1 && this.elements[B]) {
            if (F) {
                var E = this.elements[B];
                if (E.dom) {
                    E.remove()
                } else {
                    Ext.removeNode(E)
                }
            }
            this.elements.splice(B, 1)
        }
        return this
    }, replaceElement: function (D, C, A) {
        var B = typeof D == "number" ? D : this.indexOf(D);
        if (B !== -1) {
            if (A) {
                this.elements[B].replaceWith(C)
            } else {
                this.elements.splice(B, 1, Ext.get(C))
            }
        }
        return this
    }, clear: function () {
        this.elements = []
    }
};
(function () {
    Ext.CompositeElement.createCall = function (B, C) {
        if (!B[C]) {
            B[C] = function () {
                return this.invoke(C, arguments)
            }
        }
    };
    for (var A in Ext.Element.prototype) {
        if (typeof Ext.Element.prototype[A] == "function") {
            Ext.CompositeElement.createCall(Ext.CompositeElement.prototype, A)
        }
    }
})();
Ext.CompositeElementLite = function (A) {
    Ext.CompositeElementLite.superclass.constructor.call(this, A);
    this.el = new Ext.Element.Flyweight()
};
Ext.extend(Ext.CompositeElementLite, Ext.CompositeElement, {
    addElements: function (E) {
        if (E) {
            if (Ext.isArray(E)) {
                this.elements = this.elements.concat(E)
            } else {
                var D = this.elements;
                var B = D.length - 1;
                for (var C = 0, A = E.length; C < A; C++) {
                    D[++B] = E[C]
                }
            }
        }
        return this
    }, invoke: function (F, B) {
        var D = this.elements;
        var E = this.el;
        for (var C = 0, A = D.length; C < A; C++) {
            E.dom = D[C];
            Ext.Element.prototype[F].apply(E, B)
        }
        return this
    }, item: function (A) {
        if (!this.elements[A]) {
            return null
        }
        this.el.dom = this.elements[A];
        return this.el
    }, addListener: function (B, G, F, E) {
        var D = this.elements;
        for (var C = 0, A = D.length; C < A; C++) {
            Ext.EventManager.on(D[C], B, G, F || D[C], E)
        }
        return this
    }, each: function (F, E) {
        var C = this.elements;
        var D = this.el;
        for (var B = 0, A = C.length; B < A; B++) {
            D.dom = C[B];
            if (F.call(E || D, D, this, B) === false) {
                break
            }
        }
        return this
    }, indexOf: function (A) {
        return this.elements.indexOf(Ext.getDom(A))
    }, replaceElement: function (D, C, A) {
        var B = typeof D == "number" ? D : this.indexOf(D);
        if (B !== -1) {
            C = Ext.getDom(C);
            if (A) {
                var E = this.elements[B];
                E.parentNode.insertBefore(C, E);
                Ext.removeNode(E)
            }
            this.elements.splice(B, 1, C)
        }
        return this
    }
});
Ext.CompositeElementLite.prototype.on = Ext.CompositeElementLite.prototype.addListener;
if (Ext.DomQuery) {
    Ext.Element.selectorFunction = Ext.DomQuery.select
}
Ext.Element.select = function (A, D, B) {
    var C;
    if (typeof A == "string") {
        C = Ext.Element.selectorFunction(A, B)
    } else {
        if (A.length !== undefined) {
            C = A
        } else {
            throw"Invalid selector"
        }
    }
    if (D === true) {
        return new Ext.CompositeElement(C)
    } else {
        return new Ext.CompositeElementLite(C)
    }
};
Ext.select = Ext.Element.select;


Ext.data.Connection = function (A) {
    Ext.apply(this, A);
    this.addEvents("beforerequest", "requestcomplete", "requestexception");
    Ext.data.Connection.superclass.constructor.call(this)
};
Ext.extend(Ext.data.Connection, Ext.util.Observable, {
    timeout: 30000,
    autoAbort: false,
    disableCaching: true,
    disableCachingParam: "_dc",
    request: function (E) {
        if (this.fireEvent("beforerequest", this, E) !== false) {
            var C = E.params;
            if (typeof C == "function") {
                C = C.call(E.scope || window, E)
            }
            if (typeof C == "object") {
                C = Ext.urlEncode(C)
            }
            if (this.extraParams) {
                var G = Ext.urlEncode(this.extraParams);
                C = C ? (C + "&" + G) : G
            }
            var B = E.url || this.url;
            if (typeof B == "function") {
                B = B.call(E.scope || window, E)
            }
            if (E.form) {
                var D = Ext.getDom(E.form);
                B = B || D.action;
                var J = D.getAttribute("enctype");
                if (E.isUpload || (J && J.toLowerCase() == "multipart/form-data")) {
                    return this.doFormUpload(E, C, B)
                }
                var I = Ext.lib.Ajax.serializeForm(D);
                C = C ? (C + "&" + I) : I
            }
            var K = E.headers;
            if (this.defaultHeaders) {
                K = Ext.apply(K || {}, this.defaultHeaders);
                if (!E.headers) {
                    E.headers = K
                }
            }
            var F = {
                success: this.handleResponse,
                failure: this.handleFailure,
                scope: this,
                argument: {options: E},
                timeout: E.timeout || this.timeout
            };
            var A = E.method || this.method || ((C || E.xmlData || E.jsonData) ? "POST" : "GET");
            if (A == "GET" && (this.disableCaching && E.disableCaching !== false) || E.disableCaching === true) {
                var H = E.disableCachingParam || this.disableCachingParam;
                B += (B.indexOf("?") != -1 ? "&" : "?") + H + "=" + (new Date().getTime())
            }
            if (typeof E.autoAbort == "boolean") {
                if (E.autoAbort) {
                    this.abort()
                }
            } else {
                if (this.autoAbort !== false) {
                    this.abort()
                }
            }
            if ((A == "GET" || E.xmlData || E.jsonData) && C) {
                B += (B.indexOf("?") != -1 ? "&" : "?") + C;
                C = ""
            }
            this.transId = Ext.lib.Ajax.request(A, B, F, C, E);
            return this.transId
        } else {
            Ext.callback(E.callback, E.scope, [E, null, null]);
            return null
        }
    },
    isLoading: function (A) {
        if (A) {
            return Ext.lib.Ajax.isCallInProgress(A)
        } else {
            return this.transId ? true : false
        }
    },
    abort: function (A) {
        if (A || this.isLoading()) {
            Ext.lib.Ajax.abort(A || this.transId)
        }
    },
    handleResponse: function (A) {
        this.transId = false;
        var B = A.argument.options;
        A.argument = B ? B.argument : null;
        this.fireEvent("requestcomplete", this, A, B);
        Ext.callback(B.success, B.scope, [A, B]);
        Ext.callback(B.callback, B.scope, [B, true, A])
    },
    handleFailure: function (A, C) {
        this.transId = false;
        var B = A.argument.options;
        A.argument = B ? B.argument : null;
        this.fireEvent("requestexception", this, A, B, C);
        Ext.callback(B.failure, B.scope, [A, B]);
        Ext.callback(B.callback, B.scope, [B, false, A])
    },
    doFormUpload: function (E, A, B) {
        var C = Ext.id();
        var F = document.createElement("iframe");
        F.id = C;
        F.name = C;
        F.className = "x-hidden";
        if (Ext.isIE) {
            F.src = Ext.SSL_SECURE_URL
        }
        document.body.appendChild(F);
        if (Ext.isIE) {
            document.frames[C].name = C
        }
        var D = Ext.getDom(E.form);
        D.target = C;
        D.method = "POST";
        D.enctype = D.encoding = "multipart/form-data";
        if (B) {
            D.action = B
        }
        var L, J;
        if (A) {
            L = [];
            A = Ext.urlDecode(A, false);
            for (var H in A) {
                if (A.hasOwnProperty(H)) {
                    J = document.createElement("input");
                    J.type = "hidden";
                    J.name = H;
                    J.value = A[H];
                    D.appendChild(J);
                    L.push(J)
                }
            }
        }
        function G() {
            var M = {responseText: "", responseXML: null};
            M.argument = E ? E.argument : null;
            try {
                var O;
                if (Ext.isIE) {
                    O = F.contentWindow.document
                } else {
                    O = (F.contentDocument || window.frames[C].document)
                }
                if (O && O.body) {
                    M.responseText = O.body.innerHTML
                }
                if (O && O.XMLDocument) {
                    M.responseXML = O.XMLDocument
                } else {
                    M.responseXML = O
                }
            } catch (N) {
            }
            Ext.EventManager.removeListener(F, "load", G, this);
            this.fireEvent("requestcomplete", this, M, E);
            Ext.callback(E.success, E.scope, [M, E]);
            Ext.callback(E.callback, E.scope, [E, true, M]);
            setTimeout(function () {
                Ext.removeNode(F)
            }, 100)
        }

        Ext.EventManager.on(F, "load", G, this);
        D.submit();
        if (L) {
            for (var I = 0, K = L.length; I < K; I++) {
                Ext.removeNode(L[I])
            }
        }
    }
});
Ext.Ajax = new Ext.data.Connection({
    autoAbort: false, serializeForm: function (A) {
        return Ext.lib.Ajax.serializeForm(A)
    }
});


Ext.Updater = Ext.extend(Ext.util.Observable, {
    constructor: function (B, A) {
        B = Ext.get(B);
        if (!A && B.updateManager) {
            return B.updateManager
        }
        this.el = B;
        this.defaultUrl = null;
        this.addEvents("beforeupdate", "update", "failure");
        var C = Ext.Updater.defaults;
        this.sslBlankUrl = C.sslBlankUrl;
        this.disableCaching = C.disableCaching;
        this.indicatorText = C.indicatorText;
        this.showLoadIndicator = C.showLoadIndicator;
        this.timeout = C.timeout;
        this.loadScripts = C.loadScripts;
        this.transaction = null;
        this.refreshDelegate = this.refresh.createDelegate(this);
        this.updateDelegate = this.update.createDelegate(this);
        this.formUpdateDelegate = this.formUpdate.createDelegate(this);
        if (!this.renderer) {
            this.renderer = this.getDefaultRenderer()
        }
        Ext.Updater.superclass.constructor.call(this)
    }, getDefaultRenderer: function () {
        return new Ext.Updater.BasicRenderer()
    }, getEl: function () {
        return this.el
    }, update: function (B, F, G, D) {
        if (this.fireEvent("beforeupdate", this.el, B, F) !== false) {
            var A, C;
            if (typeof B == "object") {
                A = B;
                B = A.url;
                F = F || A.params;
                G = G || A.callback;
                D = D || A.discardUrl;
                C = A.scope;
                if (typeof A.nocache != "undefined") {
                    this.disableCaching = A.nocache
                }
                if (typeof A.text != "undefined") {
                    this.indicatorText = "<div class=\"loading-indicator\">" + A.text + "</div>"
                }
                if (typeof A.scripts != "undefined") {
                    this.loadScripts = A.scripts
                }
                if (typeof A.timeout != "undefined") {
                    this.timeout = A.timeout
                }
            }
            this.showLoading();
            if (!D) {
                this.defaultUrl = B
            }
            if (typeof B == "function") {
                B = B.call(this)
            }
            var E = Ext.apply({}, {
                url: B,
                params: (typeof F == "function" && C) ? F.createDelegate(C) : F,
                success: this.processSuccess,
                failure: this.processFailure,
                scope: this,
                callback: undefined,
                timeout: (this.timeout * 1000),
                disableCaching: this.disableCaching,
                argument: {"options": A, "url": B, "form": null, "callback": G, "scope": C || window, "params": F}
            }, A);
            this.transaction = Ext.Ajax.request(E)
        }
    }, formUpdate: function (C, A, B, D) {
        if (this.fireEvent("beforeupdate", this.el, C, A) !== false) {
            if (typeof A == "function") {
                A = A.call(this)
            }
            C = Ext.getDom(C);
            this.transaction = Ext.Ajax.request({
                form: C,
                url: A,
                success: this.processSuccess,
                failure: this.processFailure,
                scope: this,
                timeout: (this.timeout * 1000),
                argument: {"url": A, "form": C, "callback": D, "reset": B}
            });
            this.showLoading.defer(1, this)
        }
    }, refresh: function (A) {
        if (this.defaultUrl == null) {
            return
        }
        this.update(this.defaultUrl, null, A, true)
    }, startAutoRefresh: function (B, C, D, E, A) {
        if (A) {
            this.update(C || this.defaultUrl, D, E, true)
        }
        if (this.autoRefreshProcId) {
            clearInterval(this.autoRefreshProcId)
        }
        this.autoRefreshProcId = setInterval(this.update.createDelegate(this, [C || this.defaultUrl, D, E, true]), B * 1000)
    }, stopAutoRefresh: function () {
        if (this.autoRefreshProcId) {
            clearInterval(this.autoRefreshProcId);
            delete this.autoRefreshProcId
        }
    }, isAutoRefreshing: function () {
        return this.autoRefreshProcId ? true : false
    }, showLoading: function () {
        if (this.showLoadIndicator) {
            this.el.update(this.indicatorText)
        }
    }, processSuccess: function (A) {
        this.transaction = null;
        if (A.argument.form && A.argument.reset) {
            try {
                A.argument.form.reset()
            } catch (B) {
            }
        }
        if (this.loadScripts) {
            this.renderer.render(this.el, A, this, this.updateComplete.createDelegate(this, [A]))
        } else {
            this.renderer.render(this.el, A, this);
            this.updateComplete(A)
        }
    }, updateComplete: function (A) {
        this.fireEvent("update", this.el, A);
        if (typeof A.argument.callback == "function") {
            A.argument.callback.call(A.argument.scope, this.el, true, A, A.argument.options)
        }
    }, processFailure: function (A) {
        this.transaction = null;
        this.fireEvent("failure", this.el, A);
        if (typeof A.argument.callback == "function") {
            A.argument.callback.call(A.argument.scope, this.el, false, A, A.argument.options)
        }
    }, setRenderer: function (A) {
        this.renderer = A
    }, getRenderer: function () {
        return this.renderer
    }, setDefaultUrl: function (A) {
        this.defaultUrl = A
    }, abort: function () {
        if (this.transaction) {
            Ext.Ajax.abort(this.transaction)
        }
    }, isUpdating: function () {
        if (this.transaction) {
            return Ext.Ajax.isLoading(this.transaction)
        }
        return false
    }
});
Ext.Updater.defaults = {
    timeout: 30,
    loadScripts: false,
    sslBlankUrl: (Ext.SSL_SECURE_URL || "javascript:false"),
    disableCaching: false,
    showLoadIndicator: true,
    indicatorText: "<div class=\"loading-indicator\">Loading...</div>"
};
Ext.Updater.updateElement = function (D, C, E, B) {
    var A = Ext.get(D).getUpdater();
    Ext.apply(A, B);
    A.update(C, E, B ? B.callback : null)
};
Ext.Updater.BasicRenderer = function () {
};
Ext.Updater.BasicRenderer.prototype = {
    render: function (C, A, B, D) {
        C.update(A.responseText, B.loadScripts, D)
    }
};
Ext.UpdateManager = Ext.Updater;


Ext.util.DelayedTask = function (E, D, A) {
    var G = null, F, B;
    var C = function () {
        var H = new Date().getTime();
        if (H - B >= F) {
            clearInterval(G);
            G = null;
            E.apply(D, A || [])
        }
    };
    this.delay = function (I, K, J, H) {
        if (G && I != F) {
            this.cancel()
        }
        F = I;
        B = new Date().getTime();
        E = K || E;
        D = J || D;
        A = H || A;
        if (!G) {
            G = setInterval(C, F)
        }
    };
    this.cancel = function () {
        if (G) {
            clearInterval(G);
            G = null
        }
    }
};


Ext.util.MixedCollection = function (B, A) {
    this.items = [];
    this.map = {};
    this.keys = [];
    this.length = 0;
    this.addEvents("clear", "add", "replace", "remove", "sort");
    this.allowFunctions = B === true;
    if (A) {
        this.getKey = A
    }
    Ext.util.MixedCollection.superclass.constructor.call(this)
};
Ext.extend(Ext.util.MixedCollection, Ext.util.Observable, {
    allowFunctions: false, add: function (B, C) {
        if (arguments.length == 1) {
            C = arguments[0];
            B = this.getKey(C)
        }
        if (typeof B == "undefined" || B === null) {
            this.length++;
            this.items.push(C);
            this.keys.push(null)
        } else {
            var A = this.map[B];
            if (A) {
                return this.replace(B, C)
            }
            this.length++;
            this.items.push(C);
            this.map[B] = C;
            this.keys.push(B)
        }
        this.fireEvent("add", this.length - 1, C, B);
        return C
    }, getKey: function (A) {
        return A.id
    }, replace: function (C, D) {
        if (arguments.length == 1) {
            D = arguments[0];
            C = this.getKey(D)
        }
        var A = this.item(C);
        if (typeof C == "undefined" || C === null || typeof A == "undefined") {
            return this.add(C, D)
        }
        var B = this.indexOfKey(C);
        this.items[B] = D;
        this.map[C] = D;
        this.fireEvent("replace", C, A, D);
        return D
    }, addAll: function (E) {
        if (arguments.length > 1 || Ext.isArray(E)) {
            var B = arguments.length > 1 ? arguments : E;
            for (var D = 0, A = B.length; D < A; D++) {
                this.add(B[D])
            }
        } else {
            for (var C in E) {
                if (this.allowFunctions || typeof E[C] != "function") {
                    this.add(C, E[C])
                }
            }
        }
    }, each: function (E, D) {
        var B = [].concat(this.items);
        for (var C = 0, A = B.length; C < A; C++) {
            if (E.call(D || B[C], B[C], C, A) === false) {
                break
            }
        }
    }, eachKey: function (D, C) {
        for (var B = 0, A = this.keys.length; B < A; B++) {
            D.call(C || window, this.keys[B], this.items[B], B, A)
        }
    }, find: function (D, C) {
        for (var B = 0, A = this.items.length; B < A; B++) {
            if (D.call(C || window, this.items[B], this.keys[B])) {
                return this.items[B]
            }
        }
        return null
    }, insert: function (A, B, C) {
        if (arguments.length == 2) {
            C = arguments[1];
            B = this.getKey(C)
        }
        if (A >= this.length) {
            return this.add(B, C)
        }
        this.length++;
        this.items.splice(A, 0, C);
        if (typeof B != "undefined" && B != null) {
            this.map[B] = C
        }
        this.keys.splice(A, 0, B);
        this.fireEvent("add", A, C, B);
        return C
    }, remove: function (A) {
        return this.removeAt(this.indexOf(A))
    }, removeAt: function (A) {
        if (A < this.length && A >= 0) {
            this.length--;
            var C = this.items[A];
            this.items.splice(A, 1);
            var B = this.keys[A];
            if (typeof B != "undefined") {
                delete this.map[B]
            }
            this.keys.splice(A, 1);
            this.fireEvent("remove", C, B);
            return C
        }
        return false
    }, removeKey: function (A) {
        return this.removeAt(this.indexOfKey(A))
    }, getCount: function () {
        return this.length
    }, indexOf: function (A) {
        return this.items.indexOf(A)
    }, indexOfKey: function (A) {
        return this.keys.indexOf(A)
    }, item: function (A) {
        var B = typeof this.map[A] != "undefined" ? this.map[A] : this.items[A];
        return typeof B != "function" || this.allowFunctions ? B : null
    }, itemAt: function (A) {
        return this.items[A]
    }, key: function (A) {
        return this.map[A]
    }, contains: function (A) {
        return this.indexOf(A) != -1
    }, containsKey: function (A) {
        return typeof this.map[A] != "undefined"
    }, clear: function () {
        this.length = 0;
        this.items = [];
        this.keys = [];
        this.map = {};
        this.fireEvent("clear")
    }, first: function () {
        return this.items[0]
    }, last: function () {
        return this.items[this.length - 1]
    }, _sort: function (I, A, H) {
        var C = String(A).toUpperCase() == "DESC" ? -1 : 1;
        H = H || function (K, J) {
                return K - J
            };
        var G = [], B = this.keys, F = this.items;
        for (var D = 0, E = F.length; D < E; D++) {
            G[G.length] = {key: B[D], value: F[D], index: D}
        }
        G.sort(function (K, J) {
            var L = H(K[I], J[I]) * C;
            if (L == 0) {
                L = (K.index < J.index ? -1 : 1)
            }
            return L
        });
        for (var D = 0, E = G.length; D < E; D++) {
            F[D] = G[D].value;
            B[D] = G[D].key
        }
        this.fireEvent("sort", this)
    }, sort: function (A, B) {
        this._sort("value", A, B)
    }, keySort: function (A, B) {
        this._sort("key", A, B || function (D, C) {
                return String(D).toUpperCase() - String(C).toUpperCase()
            })
    }, getRange: function (E, A) {
        var B = this.items;
        if (B.length < 1) {
            return []
        }
        E = E || 0;
        A = Math.min(typeof A == "undefined" ? this.length - 1 : A, this.length - 1);
        var D = [];
        if (E <= A) {
            for (var C = E; C <= A; C++) {
                D[D.length] = B[C]
            }
        } else {
            for (var C = E; C >= A; C--) {
                D[D.length] = B[C]
            }
        }
        return D
    }, filter: function (C, B, D, A) {
        if (Ext.isEmpty(B, false)) {
            return this.clone()
        }
        B = this.createValueMatcher(B, D, A);
        return this.filterBy(function (E) {
            return E && B.test(E[C])
        })
    }, filterBy: function (F, E) {
        var G = new Ext.util.MixedCollection();
        G.getKey = this.getKey;
        var B = this.keys, D = this.items;
        for (var C = 0, A = D.length; C < A; C++) {
            if (F.call(E || this, D[C], B[C])) {
                G.add(B[C], D[C])
            }
        }
        return G
    }, findIndex: function (C, B, E, D, A) {
        if (Ext.isEmpty(B, false)) {
            return -1
        }
        B = this.createValueMatcher(B, D, A);
        return this.findIndexBy(function (F) {
            return F && B.test(F[C])
        }, null, E)
    }, findIndexBy: function (F, E, G) {
        var B = this.keys, D = this.items;
        for (var C = (G || 0), A = D.length; C < A; C++) {
            if (F.call(E || this, D[C], B[C])) {
                return C
            }
        }
        if (typeof G == "number" && G > 0) {
            for (var C = 0; C < G; C++) {
                if (F.call(E || this, D[C], B[C])) {
                    return C
                }
            }
        }
        return -1
    }, createValueMatcher: function (B, C, A) {
        if (!B.exec) {
            B = String(B);
            B = new RegExp((C === true ? "" : "^") + Ext.escapeRe(B), A ? "" : "i")
        }
        return B
    }, clone: function () {
        var E = new Ext.util.MixedCollection();
        var B = this.keys, D = this.items;
        for (var C = 0, A = D.length; C < A; C++) {
            E.add(B[C], D[C])
        }
        E.getKey = this.getKey;
        return E
    }
});
Ext.util.MixedCollection.prototype.get = Ext.util.MixedCollection.prototype.item;


Ext.util.JSON = new (function () {
    var useHasOwn = !!{}.hasOwnProperty;
    var pad = function (n) {
        return n < 10 ? "0" + n : n
    };
    var m = {"\b": "\\b", "\t": "\\t", "\n": "\\n", "\f": "\\f", "\r": "\\r", "\"": "\\\"", "\\": "\\\\"};
    var encodeString = function (s) {
        if (/["\\\x00-\x1f]/.test(s)) {
            return "\"" + s.replace(/([\x00-\x1f\\"])/g, function (a, b) {
                    var c = m[b];
                    if (c) {
                        return c
                    }
                    c = b.charCodeAt();
                    return "\\u00" + Math.floor(c / 16).toString(16) + (c % 16).toString(16)
                }) + "\""
        }
        return "\"" + s + "\""
    };
    var encodeArray = function (o) {
        var a = ["["], b, i, l = o.length, v;
        for (i = 0; i < l; i += 1) {
            v = o[i];
            switch (typeof v) {
                case"undefined":
                case"function":
                case"unknown":
                    break;
                default:
                    if (b) {
                        a.push(",")
                    }
                    a.push(v === null ? "null" : Ext.util.JSON.encode(v));
                    b = true
            }
        }
        a.push("]");
        return a.join("")
    };
    this.encodeDate = function (o) {
        return "\"" + o.getFullYear() + "-" + pad(o.getMonth() + 1) + "-" + pad(o.getDate()) + "T" + pad(o.getHours()) + ":" + pad(o.getMinutes()) + ":" + pad(o.getSeconds()) + "\""
    };
    this.encode = function (o) {
        if (typeof o == "undefined" || o === null) {
            return "null"
        } else {
            if (Ext.isArray(o)) {
                return encodeArray(o)
            } else {
                if (Ext.isDate(o)) {
                    return Ext.util.JSON.encodeDate(o)
                } else {
                    if (typeof o == "string") {
                        return encodeString(o)
                    } else {
                        if (typeof o == "number") {
                            return isFinite(o) ? String(o) : "null"
                        } else {
                            if (typeof o == "boolean") {
                                return String(o)
                            } else {
                                var a = ["{"], b, i, v;
                                for (i in o) {
                                    if (!useHasOwn || o.hasOwnProperty(i)) {
                                        v = o[i];
                                        switch (typeof v) {
                                            case"undefined":
                                            case"function":
                                            case"unknown":
                                                break;
                                            default:
                                                if (b) {
                                                    a.push(",")
                                                }
                                                a.push(this.encode(i), ":", v === null ? "null" : this.encode(v));
                                                b = true
                                        }
                                    }
                                }
                                a.push("}");
                                return a.join("")
                            }
                        }
                    }
                }
            }
        }
    };
    this.decode = function (json) {
        return eval("(" + json + ")")
    }
})();
Ext.encode = Ext.util.JSON.encode;
Ext.decode = Ext.util.JSON.decode;


Ext.data.SortTypes = {
    none: function (A) {
        return A
    }, stripTagsRE: /<\/?[^>]+>/gi, asText: function (A) {
        return String(A).replace(this.stripTagsRE, "")
    }, asUCText: function (A) {
        return String(A).toUpperCase().replace(this.stripTagsRE, "")
    }, asUCString: function (A) {
        return String(A).toUpperCase()
    }, asDate: function (A) {
        if (!A) {
            return 0
        }
        if (Ext.isDate(A)) {
            return A.getTime()
        }
        return Date.parse(String(A))
    }, asFloat: function (A) {
        var B = parseFloat(String(A).replace(/,/g, ""));
        if (isNaN(B)) {
            B = 0
        }
        return B
    }, asInt: function (A) {
        var B = parseInt(String(A).replace(/,/g, ""));
        if (isNaN(B)) {
            B = 0
        }
        return B
    }
};


Ext.data.Record = function (A, B) {
    this.id = (B || B === 0) ? B : ++Ext.data.Record.AUTO_ID;
    this.data = A
};
Ext.data.Record.create = function (E) {
    var C = Ext.extend(Ext.data.Record, {});
    var D = C.prototype;
    D.fields = new Ext.util.MixedCollection(false, function (F) {
        return F.name
    });
    for (var B = 0, A = E.length; B < A; B++) {
        D.fields.add(new Ext.data.Field(E[B]))
    }
    C.getField = function (F) {
        return D.fields.get(F)
    };
    return C
};
Ext.data.Record.AUTO_ID = 1000;
Ext.data.Record.EDIT = "edit";
Ext.data.Record.REJECT = "reject";
Ext.data.Record.COMMIT = "commit";
Ext.data.Record.prototype = {
    dirty: false, editing: false, error: null, modified: null, join: function (A) {
        this.store = A
    }, set: function (A, B) {
        if (String(this.data[A]) == String(B)) {
            return
        }
        this.dirty = true;
        if (!this.modified) {
            this.modified = {}
        }
        if (typeof this.modified[A] == "undefined") {
            this.modified[A] = this.data[A]
        }
        this.data[A] = B;
        if (!this.editing && this.store) {
            this.store.afterEdit(this)
        }
    }, get: function (A) {
        return this.data[A]
    }, beginEdit: function () {
        this.editing = true;
        this.modified = {}
    }, cancelEdit: function () {
        this.editing = false;
        delete this.modified
    }, endEdit: function () {
        this.editing = false;
        if (this.dirty && this.store) {
            this.store.afterEdit(this)
        }
    }, reject: function (B) {
        var A = this.modified;
        for (var C in A) {
            if (typeof A[C] != "function") {
                this.data[C] = A[C]
            }
        }
        this.dirty = false;
        delete this.modified;
        this.editing = false;
        if (this.store && B !== true) {
            this.store.afterReject(this)
        }
    }, commit: function (A) {
        this.dirty = false;
        delete this.modified;
        this.editing = false;
        if (this.store && A !== true) {
            this.store.afterCommit(this)
        }
    }, getChanges: function () {
        var A = this.modified, B = {};
        for (var C in A) {
            if (A.hasOwnProperty(C)) {
                B[C] = this.data[C]
            }
        }
        return B
    }, hasError: function () {
        return this.error != null
    }, clearError: function () {
        this.error = null
    }, copy: function (A) {
        return new this.constructor(Ext.apply({}, this.data), A || this.id)
    }, isModified: function (A) {
        return !!(this.modified && this.modified.hasOwnProperty(A))
    }
};


Ext.data.Store = function (A) {
    this.data = new Ext.util.MixedCollection(false);
    this.data.getKey = function (B) {
        return B.id
    };
    this.baseParams = {};
    this.paramNames = {"start": "start", "limit": "limit", "sort": "sort", "dir": "dir"};
    if (A && A.data) {
        this.inlineData = A.data;
        delete A.data
    }
    Ext.apply(this, A);
    if (this.url && !this.proxy) {
        this.proxy = new Ext.data.HttpProxy({url: this.url})
    }
    if (this.reader) {
        if (!this.recordType) {
            this.recordType = this.reader.recordType
        }
        if (this.reader.onMetaChange) {
            this.reader.onMetaChange = this.onMetaChange.createDelegate(this)
        }
    }
    if (this.recordType) {
        this.fields = this.recordType.prototype.fields
    }
    this.modified = [];
    this.addEvents("datachanged", "metachange", "add", "remove", "update", "clear", "beforeload", "load", "loadexception");
    if (this.proxy) {
        this.relayEvents(this.proxy, ["loadexception"])
    }
    this.sortToggle = {};
    if (this.sortInfo) {
        this.setDefaultSort(this.sortInfo.field, this.sortInfo.direction)
    }
    Ext.data.Store.superclass.constructor.call(this);
    if (this.storeId || this.id) {
        Ext.StoreMgr.register(this)
    }
    if (this.inlineData) {
        this.loadData(this.inlineData);
        delete this.inlineData
    } else {
        if (this.autoLoad) {
            this.load.defer(10, this, [typeof this.autoLoad == "object" ? this.autoLoad : undefined])
        }
    }
};
Ext.extend(Ext.data.Store, Ext.util.Observable, {
    remoteSort: false,
    pruneModifiedRecords: false,
    lastOptions: null,
    destroy: function () {
        if (this.id) {
            Ext.StoreMgr.unregister(this)
        }
        this.data = null;
        this.purgeListeners()
    },
    add: function (B) {
        B = [].concat(B);
        if (B.length < 1) {
            return
        }
        for (var D = 0, A = B.length; D < A; D++) {
            B[D].join(this)
        }
        var C = this.data.length;
        this.data.addAll(B);
        if (this.snapshot) {
            this.snapshot.addAll(B)
        }
        this.fireEvent("add", this, B, C)
    },
    addSorted: function (A) {
        var B = this.findInsertIndex(A);
        this.insert(B, A)
    },
    remove: function (A) {
        var B = this.data.indexOf(A);
        this.data.removeAt(B);
        if (this.pruneModifiedRecords) {
            this.modified.remove(A)
        }
        if (this.snapshot) {
            this.snapshot.remove(A)
        }
        this.fireEvent("remove", this, A, B)
    },
    removeAll: function () {
        this.data.clear();
        if (this.snapshot) {
            this.snapshot.clear()
        }
        if (this.pruneModifiedRecords) {
            this.modified = []
        }
        this.fireEvent("clear", this)
    },
    insert: function (C, B) {
        B = [].concat(B);
        for (var D = 0, A = B.length; D < A; D++) {
            this.data.insert(C, B[D]);
            B[D].join(this)
        }
        this.fireEvent("add", this, B, C)
    },
    indexOf: function (A) {
        return this.data.indexOf(A)
    },
    indexOfId: function (A) {
        return this.data.indexOfKey(A)
    },
    getById: function (A) {
        return this.data.key(A)
    },
    getAt: function (A) {
        return this.data.itemAt(A)
    },
    getRange: function (B, A) {
        return this.data.getRange(B, A)
    },
    storeOptions: function (A) {
        A = Ext.apply({}, A);
        delete A.callback;
        delete A.scope;
        this.lastOptions = A
    },
    load: function (B) {
        B = B || {};
        if (this.fireEvent("beforeload", this, B) !== false) {
            this.storeOptions(B);
            var C = Ext.apply(B.params || {}, this.baseParams);
            if (this.sortInfo && this.remoteSort) {
                var A = this.paramNames;
                C[A["sort"]] = this.sortInfo.field;
                C[A["dir"]] = this.sortInfo.direction
            }
            this.proxy.load(C, this.reader, this.loadRecords, this, B);
            return true
        } else {
            return false
        }
    },
    reload: function (A) {
        this.load(Ext.applyIf(A || {}, this.lastOptions))
    },
    loadRecords: function (G, B, F) {
        if (!G || F === false) {
            if (F !== false) {
                this.fireEvent("load", this, [], B)
            }
            if (B.callback) {
                B.callback.call(B.scope || this, [], B, false)
            }
            return
        }
        var E = G.records, D = G.totalRecords || E.length;
        if (!B || B.add !== true) {
            if (this.pruneModifiedRecords) {
                this.modified = []
            }
            for (var C = 0, A = E.length; C < A; C++) {
                E[C].join(this)
            }
            if (this.snapshot) {
                this.data = this.snapshot;
                delete this.snapshot
            }
            this.data.clear();
            this.data.addAll(E);
            this.totalLength = D;
            this.applySort();
            this.fireEvent("datachanged", this)
        } else {
            this.totalLength = Math.max(D, this.data.length + E.length);
            this.add(E)
        }
        this.fireEvent("load", this, E, B);
        if (B.callback) {
            B.callback.call(B.scope || this, E, B, true)
        }
    },
    loadData: function (C, A) {
        var B = this.reader.readRecords(C);
        this.loadRecords(B, {add: A}, true)
    },
    getCount: function () {
        return this.data.length || 0
    },
    getTotalCount: function () {
        return this.totalLength || 0
    },
    getSortState: function () {
        return this.sortInfo
    },
    applySort: function () {
        if (this.sortInfo && !this.remoteSort) {
            var A = this.sortInfo, B = A.field;
            this.sortData(B, A.direction)
        }
    },
    sortData: function (C, D) {
        D = D || "ASC";
        var A = this.fields.get(C).sortType;
        var B = function (F, E) {
            var H = A(F.data[C]), G = A(E.data[C]);
            return H > G ? 1 : (H < G ? -1 : 0)
        };
        this.data.sort(D, B);
        if (this.snapshot && this.snapshot != this.data) {
            this.snapshot.sort(D, B)
        }
    },
    setDefaultSort: function (B, A) {
        A = A ? A.toUpperCase() : "ASC";
        this.sortInfo = {field: B, direction: A};
        this.sortToggle[B] = A
    },
    sort: function (E, C) {
        var D = this.fields.get(E);
        if (!D) {
            return false
        }
        if (!C) {
            if (this.sortInfo && this.sortInfo.field == D.name) {
                C = (this.sortToggle[D.name] || "ASC").toggle("ASC", "DESC")
            } else {
                C = D.sortDir
            }
        }
        var B = (this.sortToggle) ? this.sortToggle[D.name] : null;
        var A = (this.sortInfo) ? this.sortInfo : null;
        this.sortToggle[D.name] = C;
        this.sortInfo = {field: D.name, direction: C};
        if (!this.remoteSort) {
            this.applySort();
            this.fireEvent("datachanged", this)
        } else {
            if (!this.load(this.lastOptions)) {
                if (B) {
                    this.sortToggle[D.name] = B
                }
                if (A) {
                    this.sortInfo = A
                }
            }
        }
    },
    each: function (B, A) {
        this.data.each(B, A)
    },
    getModifiedRecords: function () {
        return this.modified
    },
    createFilterFn: function (C, B, D, A) {
        if (Ext.isEmpty(B, false)) {
            return false
        }
        B = this.data.createValueMatcher(B, D, A);
        return function (E) {
            return B.test(E.data[C])
        }
    },
    sum: function (E, F, A) {
        var C = this.data.items, B = 0;
        F = F || 0;
        A = (A || A === 0) ? A : C.length - 1;
        for (var D = F; D <= A; D++) {
            B += (C[D].data[E] || 0)
        }
        return B
    },
    filter: function (D, C, E, A) {
        var B = this.createFilterFn(D, C, E, A);
        return B ? this.filterBy(B) : this.clearFilter()
    },
    filterBy: function (B, A) {
        this.snapshot = this.snapshot || this.data;
        this.data = this.queryBy(B, A || this);
        this.fireEvent("datachanged", this)
    },
    query: function (D, C, E, A) {
        var B = this.createFilterFn(D, C, E, A);
        return B ? this.queryBy(B) : this.data.clone()
    },
    queryBy: function (B, A) {
        var C = this.snapshot || this.data;
        return C.filterBy(B, A || this)
    },
    find: function (D, C, F, E, A) {
        var B = this.createFilterFn(D, C, E, A);
        return B ? this.data.findIndexBy(B, null, F) : -1
    },
    findBy: function (B, A, C) {
        return this.data.findIndexBy(B, A, C)
    },
    collect: function (G, H, B) {
        var F = (B === true && this.snapshot) ? this.snapshot.items : this.data.items;
        var I, J, A = [], C = {};
        for (var D = 0, E = F.length; D < E; D++) {
            I = F[D].data[G];
            J = String(I);
            if ((H || !Ext.isEmpty(I)) && !C[J]) {
                C[J] = true;
                A[A.length] = I
            }
        }
        return A
    },
    clearFilter: function (A) {
        if (this.isFiltered()) {
            this.data = this.snapshot;
            delete this.snapshot;
            if (A !== true) {
                this.fireEvent("datachanged", this)
            }
        }
    },
    isFiltered: function () {
        return this.snapshot && this.snapshot != this.data
    },
    afterEdit: function (A) {
        if (this.modified.indexOf(A) == -1) {
            this.modified.push(A)
        }
        this.fireEvent("update", this, A, Ext.data.Record.EDIT)
    },
    afterReject: function (A) {
        this.modified.remove(A);
        this.fireEvent("update", this, A, Ext.data.Record.REJECT)
    },
    afterCommit: function (A) {
        this.modified.remove(A);
        this.fireEvent("update", this, A, Ext.data.Record.COMMIT)
    },
    commitChanges: function () {
        var B = this.modified.slice(0);
        this.modified = [];
        for (var C = 0, A = B.length; C < A; C++) {
            B[C].commit()
        }
    },
    rejectChanges: function () {
        var B = this.modified.slice(0);
        this.modified = [];
        for (var C = 0, A = B.length; C < A; C++) {
            B[C].reject()
        }
    },
    onMetaChange: function (B, A, C) {
        this.recordType = A;
        this.fields = A.prototype.fields;
        delete this.snapshot;
        this.sortInfo = B.sortInfo;
        this.modified = [];
        this.fireEvent("metachange", this, this.reader.meta)
    },
    findInsertIndex: function (A) {
        this.suspendEvents();
        var C = this.data.clone();
        this.data.add(A);
        this.applySort();
        var B = this.data.indexOf(A);
        this.data = C;
        this.resumeEvents();
        return B
    }
});


Ext.data.SimpleStore = function (A) {
    Ext.data.SimpleStore.superclass.constructor.call(this, Ext.apply(A, {reader: new Ext.data.ArrayReader({id: A.id}, Ext.data.Record.create(A.fields))}))
};
Ext.extend(Ext.data.SimpleStore, Ext.data.Store, {
    loadData: function (E, B) {
        if (this.expandData === true) {
            var D = [];
            for (var C = 0, A = E.length; C < A; C++) {
                D[D.length] = [E[C]]
            }
            E = D
        }
        Ext.data.SimpleStore.superclass.loadData.call(this, E, B)
    }
});


Ext.data.Field = function (D) {
    if (typeof D == "string") {
        D = {name: D}
    }
    Ext.apply(this, D);
    if (!this.type) {
        this.type = "auto"
    }
    var C = Ext.data.SortTypes;
    if (typeof this.sortType == "string") {
        this.sortType = C[this.sortType]
    }
    if (!this.sortType) {
        switch (this.type) {
            case"string":
                this.sortType = C.asUCString;
                break;
            case"date":
                this.sortType = C.asDate;
                break;
            default:
                this.sortType = C.none
        }
    }
    var E = /[\$,%]/g;
    if (!this.convert) {
        var B, A = this.dateFormat;
        switch (this.type) {
            case"":
            case"auto":
            case undefined:
                B = function (F) {
                    return F
                };
                break;
            case"string":
                B = function (F) {
                    return (F === undefined || F === null) ? "" : String(F)
                };
                break;
            case"int":
                B = function (F) {
                    return F !== undefined && F !== null && F !== "" ? parseInt(String(F).replace(E, ""), 10) : ""
                };
                break;
            case"float":
                B = function (F) {
                    return F !== undefined && F !== null && F !== "" ? parseFloat(String(F).replace(E, ""), 10) : ""
                };
                break;
            case"bool":
            case"boolean":
                B = function (F) {
                    return F === true || F === "true" || F == 1
                };
                break;
            case"date":
                B = function (G) {
                    if (!G) {
                        return ""
                    }
                    if (Ext.isDate(G)) {
                        return G
                    }
                    if (A) {
                        if (A == "timestamp") {
                            return new Date(G * 1000)
                        }
                        if (A == "time") {
                            return new Date(parseInt(G, 10))
                        }
                        return Date.parseDate(G, A)
                    }
                    var F = Date.parse(G);
                    return F ? new Date(F) : null
                };
                break
        }
        this.convert = B
    }
};
Ext.data.Field.prototype = {dateFormat: null, defaultValue: "", mapping: null, sortType: null, sortDir: "ASC"};


Ext.data.DataReader = function (A, B) {
    this.meta = A;
    this.recordType = Ext.isArray(B) ? Ext.data.Record.create(B) : B
};
Ext.data.DataReader.prototype = {};


Ext.data.DataProxy = function () {
    this.addEvents("beforeload", "load");
    Ext.data.DataProxy.superclass.constructor.call(this)
};
Ext.extend(Ext.data.DataProxy, Ext.util.Observable);


Ext.data.MemoryProxy = function (A) {
    Ext.data.MemoryProxy.superclass.constructor.call(this);
    this.data = A
};
Ext.extend(Ext.data.MemoryProxy, Ext.data.DataProxy, {
    load: function (F, C, G, D, B) {
        F = F || {};
        var A;
        try {
            A = C.readRecords(this.data)
        } catch (E) {
            this.fireEvent("loadexception", this, B, null, E);
            G.call(D, null, B, false);
            return
        }
        G.call(D, A, B, true)
    }, update: function (B, A) {
    }
});


Ext.data.HttpProxy = function (A) {
    Ext.data.HttpProxy.superclass.constructor.call(this);
    this.conn = A;
    this.useAjax = !A || !A.events
};
Ext.extend(Ext.data.HttpProxy, Ext.data.DataProxy, {
    getConnection: function () {
        return this.useAjax ? Ext.Ajax : this.conn
    }, load: function (E, B, F, C, A) {
        if (this.fireEvent("beforeload", this, E) !== false) {
            var D = {
                params: E || {},
                request: {callback: F, scope: C, arg: A},
                reader: B,
                callback: this.loadResponse,
                scope: this
            };
            if (this.useAjax) {
                Ext.applyIf(D, this.conn);
                if (this.activeRequest) {
                    Ext.Ajax.abort(this.activeRequest)
                }
                this.activeRequest = Ext.Ajax.request(D)
            } else {
                this.conn.request(D)
            }
        } else {
            F.call(C || this, null, A, false)
        }
    }, loadResponse: function (E, D, B) {
        delete this.activeRequest;
        if (!D) {
            this.fireEvent("loadexception", this, E, B);
            E.request.callback.call(E.request.scope, null, E.request.arg, false);
            return
        }
        var A;
        try {
            A = E.reader.read(B)
        } catch (C) {
            this.fireEvent("loadexception", this, E, B, C);
            E.request.callback.call(E.request.scope, null, E.request.arg, false);
            return
        }
        this.fireEvent("load", this, E, E.request.arg);
        E.request.callback.call(E.request.scope, A, E.request.arg, true)
    }, update: function (A) {
    }, updateResponse: function (A) {
    }
});


Ext.data.ScriptTagProxy = function (A) {
    Ext.data.ScriptTagProxy.superclass.constructor.call(this);
    Ext.apply(this, A);
    this.head = document.getElementsByTagName("head")[0]
};
Ext.data.ScriptTagProxy.TRANS_ID = 1000;
Ext.extend(Ext.data.ScriptTagProxy, Ext.data.DataProxy, {
    timeout: 30000,
    callbackParam: "callback",
    nocache: true,
    load: function (E, F, H, I, J) {
        if (this.fireEvent("beforeload", this, E) !== false) {
            var C = Ext.urlEncode(Ext.apply(E, this.extraParams));
            var B = this.url;
            B += (B.indexOf("?") != -1 ? "&" : "?") + C;
            if (this.nocache) {
                B += "&_dc=" + (new Date().getTime())
            }
            var A = ++Ext.data.ScriptTagProxy.TRANS_ID;
            var K = {
                id: A,
                cb: "stcCallback" + A,
                scriptId: "stcScript" + A,
                params: E,
                arg: J,
                url: B,
                callback: H,
                scope: I,
                reader: F
            };
            var D = this;
            window[K.cb] = function (L) {
                D.handleResponse(L, K)
            };
            B += String.format("&{0}={1}", this.callbackParam, K.cb);
            if (this.autoAbort !== false) {
                this.abort()
            }
            K.timeoutId = this.handleFailure.defer(this.timeout, this, [K]);
            var G = document.createElement("script");
            G.setAttribute("src", B);
            G.setAttribute("type", "text/javascript");
            G.setAttribute("id", K.scriptId);
            this.head.appendChild(G);
            this.trans = K
        } else {
            H.call(I || this, null, J, false)
        }
    },
    isLoading: function () {
        return this.trans ? true : false
    },
    abort: function () {
        if (this.isLoading()) {
            this.destroyTrans(this.trans)
        }
    },
    destroyTrans: function (B, A) {
        this.head.removeChild(document.getElementById(B.scriptId));
        clearTimeout(B.timeoutId);
        if (A) {
            window[B.cb] = undefined;
            try {
                delete window[B.cb]
            } catch (C) {
            }
        } else {
            window[B.cb] = function () {
                window[B.cb] = undefined;
                try {
                    delete window[B.cb]
                } catch (D) {
                }
            }
        }
    },
    handleResponse: function (D, B) {
        this.trans = false;
        this.destroyTrans(B, true);
        var A;
        try {
            A = B.reader.readRecords(D)
        } catch (C) {
            this.fireEvent("loadexception", this, D, B.arg, C);
            B.callback.call(B.scope || window, null, B.arg, false);
            return
        }
        this.fireEvent("load", this, D, B.arg);
        B.callback.call(B.scope || window, A, B.arg, true)
    },
    handleFailure: function (A) {
        this.trans = false;
        this.destroyTrans(A, false);
        this.fireEvent("loadexception", this, null, A.arg);
        A.callback.call(A.scope || window, null, A.arg, false)
    }
});


Ext.data.JsonReader = function (A, B) {
    A = A || {};
    Ext.data.JsonReader.superclass.constructor.call(this, A, B || A.fields)
};
Ext.extend(Ext.data.JsonReader, Ext.data.DataReader, {
    read: function (response) {
        var json = response.responseText;
        var o = eval("(" + json + ")");
        if (!o) {
            throw {message: "JsonReader.read: Json object not found"}
        }
        return this.readRecords(o)
    }, onMetaChange: function (A, C, B) {
    }, simpleAccess: function (B, A) {
        return B[A]
    }, getJsonAccessor: function () {
        var A = /[\[\.]/;
        return function (C) {
            try {
                return (A.test(C)) ? new Function("obj", "return obj." + C) : function (D) {
                    return D[C]
                }
            } catch (B) {
            }
            return Ext.emptyFn
        }
    }(), readRecords: function (K) {
        this.jsonData = K;
        if (K.metaData) {
            delete this.ef;
            this.meta = K.metaData;
            this.recordType = Ext.data.Record.create(K.metaData.fields);
            this.onMetaChange(this.meta, this.recordType, K)
        }
        var H = this.meta, A = this.recordType, R = A.prototype.fields, F = R.items, E = R.length;
        if (!this.ef) {
            if (H.totalProperty) {
                this.getTotal = this.getJsonAccessor(H.totalProperty)
            }
            if (H.successProperty) {
                this.getSuccess = this.getJsonAccessor(H.successProperty)
            }
            this.getRoot = H.root ? this.getJsonAccessor(H.root) : function (U) {
                return U
            };
            if (H.id) {
                var Q = this.getJsonAccessor(H.id);
                this.getId = function (V) {
                    var U = Q(V);
                    return (U === undefined || U === "") ? null : U
                }
            } else {
                this.getId = function () {
                    return null
                }
            }
            this.ef = [];
            for (var O = 0; O < E; O++) {
                R = F[O];
                var T = (R.mapping !== undefined && R.mapping !== null) ? R.mapping : R.name;
                this.ef[O] = this.getJsonAccessor(T)
            }
        }
        var M = this.getRoot(K), S = M.length, I = S, D = true;
        if (H.totalProperty) {
            var G = parseInt(this.getTotal(K), 10);
            if (!isNaN(G)) {
                I = G
            }
        }
        if (H.successProperty) {
            var G = this.getSuccess(K);
            if (G === false || G === "false") {
                D = false
            }
        }
        var P = [];
        for (var O = 0; O < S; O++) {
            var L = M[O];
            var B = {};
            var J = this.getId(L);
            for (var N = 0; N < E; N++) {
                R = F[N];
                var G = this.ef[N](L);
                B[R.name] = R.convert((G !== undefined) ? G : R.defaultValue, L)
            }
            var C = new A(B, J);
            C.json = L;
            P[O] = C
        }
        return {success: D, records: P, totalRecords: I}
    }
});


Ext.data.XmlReader = function (A, B) {
    A = A || {};
    Ext.data.XmlReader.superclass.constructor.call(this, A, B || A.fields)
};
Ext.extend(Ext.data.XmlReader, Ext.data.DataReader, {
    read: function (A) {
        var B = A.responseXML;
        if (!B) {
            throw {message: "XmlReader.read: XML Document not available"}
        }
        return this.readRecords(B)
    }, readRecords: function (T) {
        this.xmlData = T;
        var N = T.documentElement || T;
        var I = Ext.DomQuery;
        var B = this.recordType, L = B.prototype.fields;
        var D = this.meta.id;
        var G = 0, E = true;
        if (this.meta.totalRecords) {
            G = I.selectNumber(this.meta.totalRecords, N, 0)
        }
        if (this.meta.success) {
            var K = I.selectValue(this.meta.success, N, true);
            E = K !== false && K !== "false"
        }
        var Q = [];
        var U = I.select(this.meta.record, N);
        for (var P = 0, R = U.length; P < R; P++) {
            var M = U[P];
            var A = {};
            var J = D ? I.selectValue(D, M) : undefined;
            for (var O = 0, H = L.length; O < H; O++) {
                var S = L.items[O];
                var F = I.selectValue(S.mapping || S.name, M, S.defaultValue);
                F = S.convert(F, M);
                A[S.name] = F
            }
            var C = new B(A, J);
            C.node = M;
            Q[Q.length] = C
        }
        return {success: E, records: Q, totalRecords: G || Q.length}
    }
});


Ext.data.ArrayReader = Ext.extend(Ext.data.JsonReader, {
    readRecords: function (C) {
        var B = this.meta ? this.meta.id : null;
        var G = this.recordType, K = G.prototype.fields;
        var E = [];
        var M = C;
        for (var I = 0; I < M.length; I++) {
            var D = M[I];
            var O = {};
            var A = ((B || B === 0) && D[B] !== undefined && D[B] !== "" ? D[B] : null);
            for (var H = 0, P = K.length; H < P; H++) {
                var L = K.items[H];
                var F = L.mapping !== undefined && L.mapping !== null ? L.mapping : H;
                var N = D[F] !== undefined ? D[F] : L.defaultValue;
                N = L.convert(N, D);
                O[L.name] = N
            }
            var J = new G(O, A);
            J.json = D;
            E[E.length] = J
        }
        return {records: E, totalRecords: E.length}
    }
});


Ext.data.Tree = function (A) {
    this.nodeHash = {};
    this.root = null;
    if (A) {
        this.setRootNode(A)
    }
    this.addEvents("append", "remove", "move", "insert", "beforeappend", "beforeremove", "beforemove", "beforeinsert");
    Ext.data.Tree.superclass.constructor.call(this)
};
Ext.extend(Ext.data.Tree, Ext.util.Observable, {
    pathSeparator: "/", proxyNodeEvent: function () {
        return this.fireEvent.apply(this, arguments)
    }, getRootNode: function () {
        return this.root
    }, setRootNode: function (A) {
        this.root = A;
        A.ownerTree = this;
        A.isRoot = true;
        this.registerNode(A);
        return A
    }, getNodeById: function (A) {
        return this.nodeHash[A]
    }, registerNode: function (A) {
        this.nodeHash[A.id] = A
    }, unregisterNode: function (A) {
        delete this.nodeHash[A.id]
    }, toString: function () {
        return "[Tree" + (this.id ? " " + this.id : "") + "]"
    }
});
Ext.data.Node = function (A) {
    this.attributes = A || {};
    this.leaf = this.attributes.leaf;
    this.id = this.attributes.id;
    if (!this.id) {
        this.id = Ext.id(null, "ynode-");
        this.attributes.id = this.id
    }
    this.childNodes = [];
    if (!this.childNodes.indexOf) {
        this.childNodes.indexOf = function (D) {
            for (var C = 0, B = this.length; C < B; C++) {
                if (this[C] == D) {
                    return C
                }
            }
            return -1
        }
    }
    this.parentNode = null;
    this.firstChild = null;
    this.lastChild = null;
    this.previousSibling = null;
    this.nextSibling = null;
    this.addEvents({
        "append": true,
        "remove": true,
        "move": true,
        "insert": true,
        "beforeappend": true,
        "beforeremove": true,
        "beforemove": true,
        "beforeinsert": true
    });
    this.listeners = this.attributes.listeners;
    Ext.data.Node.superclass.constructor.call(this)
};
Ext.extend(Ext.data.Node, Ext.util.Observable, {
    fireEvent: function (B) {
        if (Ext.data.Node.superclass.fireEvent.apply(this, arguments) === false) {
            return false
        }
        var A = this.getOwnerTree();
        if (A) {
            if (A.proxyNodeEvent.apply(A, arguments) === false) {
                return false
            }
        }
        return true
    }, isLeaf: function () {
        return this.leaf === true
    }, setFirstChild: function (A) {
        this.firstChild = A
    }, setLastChild: function (A) {
        this.lastChild = A
    }, isLast: function () {
        return (!this.parentNode ? true : this.parentNode.lastChild == this)
    }, isFirst: function () {
        return (!this.parentNode ? true : this.parentNode.firstChild == this)
    }, hasChildNodes: function () {
        return !this.isLeaf() && this.childNodes.length > 0
    }, isExpandable: function () {
        return this.attributes.expandable || this.hasChildNodes()
    }, appendChild: function (E) {
        var F = false;
        if (Ext.isArray(E)) {
            F = E
        } else {
            if (arguments.length > 1) {
                F = arguments
            }
        }
        if (F) {
            for (var D = 0, A = F.length; D < A; D++) {
                this.appendChild(F[D])
            }
        } else {
            if (this.fireEvent("beforeappend", this.ownerTree, this, E) === false) {
                return false
            }
            var B = this.childNodes.length;
            var C = E.parentNode;
            if (C) {
                if (E.fireEvent("beforemove", E.getOwnerTree(), E, C, this, B) === false) {
                    return false
                }
                C.removeChild(E)
            }
            B = this.childNodes.length;
            if (B == 0) {
                this.setFirstChild(E)
            }
            this.childNodes.push(E);
            E.parentNode = this;
            var G = this.childNodes[B - 1];
            if (G) {
                E.previousSibling = G;
                G.nextSibling = E
            } else {
                E.previousSibling = null
            }
            E.nextSibling = null;
            this.setLastChild(E);
            E.setOwnerTree(this.getOwnerTree());
            this.fireEvent("append", this.ownerTree, this, E, B);
            if (C) {
                E.fireEvent("move", this.ownerTree, E, C, this, B)
            }
            return E
        }
    }, removeChild: function (B) {
        var A = this.childNodes.indexOf(B);
        if (A == -1) {
            return false
        }
        if (this.fireEvent("beforeremove", this.ownerTree, this, B) === false) {
            return false
        }
        this.childNodes.splice(A, 1);
        if (B.previousSibling) {
            B.previousSibling.nextSibling = B.nextSibling
        }
        if (B.nextSibling) {
            B.nextSibling.previousSibling = B.previousSibling
        }
        if (this.firstChild == B) {
            this.setFirstChild(B.nextSibling)
        }
        if (this.lastChild == B) {
            this.setLastChild(B.previousSibling)
        }
        B.setOwnerTree(null);
        B.parentNode = null;
        B.previousSibling = null;
        B.nextSibling = null;
        this.fireEvent("remove", this.ownerTree, this, B);
        return B
    }, insertBefore: function (D, A) {
        if (!A) {
            return this.appendChild(D)
        }
        if (D == A) {
            return false
        }
        if (this.fireEvent("beforeinsert", this.ownerTree, this, D, A) === false) {
            return false
        }
        var B = this.childNodes.indexOf(A);
        var C = D.parentNode;
        var E = B;
        if (C == this && this.childNodes.indexOf(D) < B) {
            E--
        }
        if (C) {
            if (D.fireEvent("beforemove", D.getOwnerTree(), D, C, this, B, A) === false) {
                return false
            }
            C.removeChild(D)
        }
        if (E == 0) {
            this.setFirstChild(D)
        }
        this.childNodes.splice(E, 0, D);
        D.parentNode = this;
        var F = this.childNodes[E - 1];
        if (F) {
            D.previousSibling = F;
            F.nextSibling = D
        } else {
            D.previousSibling = null
        }
        D.nextSibling = A;
        A.previousSibling = D;
        D.setOwnerTree(this.getOwnerTree());
        this.fireEvent("insert", this.ownerTree, this, D, A);
        if (C) {
            D.fireEvent("move", this.ownerTree, D, C, this, E, A)
        }
        return D
    }, remove: function () {
        this.parentNode.removeChild(this);
        return this
    }, item: function (A) {
        return this.childNodes[A]
    }, replaceChild: function (A, B) {
        this.insertBefore(A, B);
        this.removeChild(B);
        return B
    }, indexOf: function (A) {
        return this.childNodes.indexOf(A)
    }, getOwnerTree: function () {
        if (!this.ownerTree) {
            var A = this;
            while (A) {
                if (A.ownerTree) {
                    this.ownerTree = A.ownerTree;
                    break
                }
                A = A.parentNode
            }
        }
        return this.ownerTree
    }, getDepth: function () {
        var B = 0;
        var A = this;
        while (A.parentNode) {
            ++B;
            A = A.parentNode
        }
        return B
    }, setOwnerTree: function (B) {
        if (B != this.ownerTree) {
            if (this.ownerTree) {
                this.ownerTree.unregisterNode(this)
            }
            this.ownerTree = B;
            var D = this.childNodes;
            for (var C = 0, A = D.length; C < A; C++) {
                D[C].setOwnerTree(B)
            }
            if (B) {
                B.registerNode(this)
            }
        }
    }, getPath: function (B) {
        B = B || "id";
        var D = this.parentNode;
        var A = [this.attributes[B]];
        while (D) {
            A.unshift(D.attributes[B]);
            D = D.parentNode
        }
        var C = this.getOwnerTree().pathSeparator;
        return C + A.join(C)
    }, bubble: function (C, B, A) {
        var D = this;
        while (D) {
            if (C.apply(B || D, A || [D]) === false) {
                break
            }
            D = D.parentNode
        }
    }, cascade: function (F, E, B) {
        if (F.apply(E || this, B || [this]) !== false) {
            var D = this.childNodes;
            for (var C = 0, A = D.length; C < A; C++) {
                D[C].cascade(F, E, B)
            }
        }
    }, eachChild: function (F, E, B) {
        var D = this.childNodes;
        for (var C = 0, A = D.length; C < A; C++) {
            if (F.apply(E || this, B || [D[C]]) === false) {
                break
            }
        }
    }, findChild: function (D, E) {
        var C = this.childNodes;
        for (var B = 0, A = C.length; B < A; B++) {
            if (C[B].attributes[D] == E) {
                return C[B]
            }
        }
        return null
    }, findChildBy: function (E, D) {
        var C = this.childNodes;
        for (var B = 0, A = C.length; B < A; B++) {
            if (E.call(D || C[B], C[B]) === true) {
                return C[B]
            }
        }
        return null
    }, sort: function (E, D) {
        var C = this.childNodes;
        var A = C.length;
        if (A > 0) {
            var F = D ? function () {
                E.apply(D, arguments)
            } : E;
            C.sort(F);
            for (var B = 0; B < A; B++) {
                var G = C[B];
                G.previousSibling = C[B - 1];
                G.nextSibling = C[B + 1];
                if (B == 0) {
                    this.setFirstChild(G)
                }
                if (B == A - 1) {
                    this.setLastChild(G)
                }
            }
        }
    }, contains: function (A) {
        return A.isAncestor(this)
    }, isAncestor: function (A) {
        var B = this.parentNode;
        while (B) {
            if (B == A) {
                return true
            }
            B = B.parentNode
        }
        return false
    }, toString: function () {
        return "[Node" + (this.id ? " " + this.id : "") + "]"
    }
});


Ext.ComponentMgr = function () {
    var B = new Ext.util.MixedCollection();
    var A = {};
    return {
        register: function (C) {
            B.add(C)
        }, unregister: function (C) {
            B.remove(C)
        }, get: function (C) {
            return B.get(C)
        }, onAvailable: function (E, D, C) {
            B.on("add", function (F, G) {
                if (G.id == E) {
                    D.call(C || G, G);
                    B.un("add", D, C)
                }
            })
        }, all: B, registerType: function (D, C) {
            A[D] = C;
            C.xtype = D
        }, create: function (C, D) {
            return new A[C.xtype || D](C)
        }
    }
}();
Ext.reg = Ext.ComponentMgr.registerType;


Ext.Component = function (B) {
    B = B || {};
    if (B.initialConfig) {
        if (B.isAction) {
            this.baseAction = B
        }
        B = B.initialConfig
    } else {
        if (B.tagName || B.dom || typeof B == "string") {
            B = {applyTo: B, id: B.id || B}
        }
    }
    this.initialConfig = B;
    Ext.apply(this, B);
    this.addEvents("disable", "enable", "beforeshow", "show", "beforehide", "hide", "beforerender", "render", "beforedestroy", "destroy", "beforestaterestore", "staterestore", "beforestatesave", "statesave");
    this.getId();
    Ext.ComponentMgr.register(this);
    Ext.Component.superclass.constructor.call(this);
    if (this.baseAction) {
        this.baseAction.addComponent(this)
    }
    this.initComponent();
    if (this.plugins) {
        if (Ext.isArray(this.plugins)) {
            for (var C = 0, A = this.plugins.length; C < A; C++) {
                this.plugins[C] = this.initPlugin(this.plugins[C])
            }
        } else {
            this.plugins = this.initPlugin(this.plugins)
        }
    }
    if (this.stateful !== false) {
        this.initState(B)
    }
    if (this.applyTo) {
        this.applyToMarkup(this.applyTo);
        delete this.applyTo
    } else {
        if (this.renderTo) {
            this.render(this.renderTo);
            delete this.renderTo
        }
    }
};
Ext.Component.AUTO_ID = 1000;
Ext.extend(Ext.Component, Ext.util.Observable, {
    disabledClass: "x-item-disabled",
    allowDomMove: true,
    autoShow: false,
    hideMode: "display",
    hideParent: false,
    hidden: false,
    disabled: false,
    rendered: false,
    ctype: "Ext.Component",
    actionMode: "el",
    getActionEl: function () {
        return this[this.actionMode]
    },
    initPlugin: function (A) {
        A.init(this);
        return A
    },
    initComponent: Ext.emptyFn,
    render: function (B, A) {
        if (!this.rendered && this.fireEvent("beforerender", this) !== false) {
            if (!B && this.el) {
                this.el = Ext.get(this.el);
                B = this.el.dom.parentNode;
                this.allowDomMove = false
            }
            this.container = Ext.get(B);
            if (this.ctCls) {
                this.container.addClass(this.ctCls)
            }
            this.rendered = true;
            if (A !== undefined) {
                if (typeof A == "number") {
                    A = this.container.dom.childNodes[A]
                } else {
                    A = Ext.getDom(A)
                }
            }
            this.onRender(this.container, A || null);
            if (this.autoShow) {
                this.el.removeClass(["x-hidden", "x-hide-" + this.hideMode])
            }
            if (this.cls) {
                this.el.addClass(this.cls);
                delete this.cls
            }
            if (this.style) {
                this.el.applyStyles(this.style);
                delete this.style
            }
            this.fireEvent("render", this);
            this.afterRender(this.container);
            if (this.hidden) {
                this.hide()
            }
            if (this.disabled) {
                this.disable()
            }
            if (this.stateful !== false) {
                this.initStateEvents()
            }
        }
        return this
    },
    initState: function (A) {
        if (Ext.state.Manager) {
            var B = Ext.state.Manager.get(this.stateId || this.id);
            if (B) {
                if (this.fireEvent("beforestaterestore", this, B) !== false) {
                    this.applyState(B);
                    this.fireEvent("staterestore", this, B)
                }
            }
        }
    },
    initStateEvents: function () {
        if (this.stateEvents) {
            for (var A = 0, B; B = this.stateEvents[A]; A++) {
                this.on(B, this.saveState, this, {delay: 100})
            }
        }
    },
    applyState: function (B, A) {
        if (B) {
            Ext.apply(this, B)
        }
    },
    getState: function () {
        return null
    },
    saveState: function () {
        if (Ext.state.Manager) {
            var A = this.getState();
            if (this.fireEvent("beforestatesave", this, A) !== false) {
                Ext.state.Manager.set(this.stateId || this.id, A);
                this.fireEvent("statesave", this, A)
            }
        }
    },
    applyToMarkup: function (A) {
        this.allowDomMove = false;
        this.el = Ext.get(A);
        this.render(this.el.dom.parentNode)
    },
    addClass: function (A) {
        if (this.el) {
            this.el.addClass(A)
        } else {
            this.cls = this.cls ? this.cls + " " + A : A
        }
    },
    removeClass: function (A) {
        if (this.el) {
            this.el.removeClass(A)
        } else {
            if (this.cls) {
                this.cls = this.cls.split(" ").remove(A).join(" ")
            }
        }
    },
    onRender: function (B, A) {
        if (this.autoEl) {
            if (typeof this.autoEl == "string") {
                this.el = document.createElement(this.autoEl)
            } else {
                var C = document.createElement("div");
                Ext.DomHelper.overwrite(C, this.autoEl);
                this.el = C.firstChild
            }
            if (!this.el.id) {
                this.el.id = this.getId()
            }
        }
        if (this.el) {
            this.el = Ext.get(this.el);
            if (this.allowDomMove !== false) {
                B.dom.insertBefore(this.el.dom, A)
            }
            if (this.overCls) {
                this.el.addClassOnOver(this.overCls)
            }
        }
    },
    getAutoCreate: function () {
        var A = typeof this.autoCreate == "object" ? this.autoCreate : Ext.apply({}, this.defaultAutoCreate);
        if (this.id && !A.id) {
            A.id = this.id
        }
        return A
    },
    afterRender: Ext.emptyFn,
    destroy: function () {
        if (this.fireEvent("beforedestroy", this) !== false) {
            this.beforeDestroy();
            if (this.rendered) {
                this.el.removeAllListeners();
                this.el.remove();
                if (this.actionMode == "container") {
                    this.container.remove()
                }
            }
            this.onDestroy();
            Ext.ComponentMgr.unregister(this);
            this.fireEvent("destroy", this);
            this.purgeListeners()
        }
    },
    beforeDestroy: Ext.emptyFn,
    onDestroy: Ext.emptyFn,
    getEl: function () {
        return this.el
    },
    getId: function () {
        return this.id || (this.id = "ext-comp-" + (++Ext.Component.AUTO_ID))
    },
    getItemId: function () {
        return this.itemId || this.getId()
    },
    focus: function (B, A) {
        if (A) {
            this.focus.defer(typeof A == "number" ? A : 10, this, [B, false]);
            return
        }
        if (this.rendered) {
            this.el.focus();
            if (B === true) {
                this.el.dom.select()
            }
        }
        return this
    },
    blur: function () {
        if (this.rendered) {
            this.el.blur()
        }
        return this
    },
    disable: function () {
        if (this.rendered) {
            this.onDisable()
        }
        this.disabled = true;
        this.fireEvent("disable", this);
        return this
    },
    onDisable: function () {
        this.getActionEl().addClass(this.disabledClass);
        this.el.dom.disabled = true
    },
    enable: function () {
        if (this.rendered) {
            this.onEnable()
        }
        this.disabled = false;
        this.fireEvent("enable", this);
        return this
    },
    onEnable: function () {
        this.getActionEl().removeClass(this.disabledClass);
        this.el.dom.disabled = false
    },
    setDisabled: function (A) {
        this[A ? "disable" : "enable"]()
    },
    show: function () {
        if (this.fireEvent("beforeshow", this) !== false) {
            this.hidden = false;
            if (this.autoRender) {
                this.render(typeof this.autoRender == "boolean" ? Ext.getBody() : this.autoRender)
            }
            if (this.rendered) {
                this.onShow()
            }
            this.fireEvent("show", this)
        }
        return this
    },
    onShow: function () {
        if (this.hideParent) {
            this.container.removeClass("x-hide-" + this.hideMode)
        } else {
            this.getActionEl().removeClass("x-hide-" + this.hideMode)
        }
    },
    hide: function () {
        if (this.fireEvent("beforehide", this) !== false) {
            this.hidden = true;
            if (this.rendered) {
                this.onHide()
            }
            this.fireEvent("hide", this)
        }
        return this
    },
    onHide: function () {
        if (this.hideParent) {
            this.container.addClass("x-hide-" + this.hideMode)
        } else {
            this.getActionEl().addClass("x-hide-" + this.hideMode)
        }
    },
    setVisible: function (A) {
        if (A) {
            this.show()
        } else {
            this.hide()
        }
        return this
    },
    isVisible: function () {
        return this.rendered && this.getActionEl().isVisible()
    },
    cloneConfig: function (B) {
        B = B || {};
        var C = B.id || Ext.id();
        var A = Ext.applyIf(B, this.initialConfig);
        A.id = C;
        return new this.constructor(A)
    },
    getXType: function () {
        return this.constructor.xtype
    },
    isXType: function (B, A) {
        return !A ? ("/" + this.getXTypes() + "/").indexOf("/" + B + "/") != -1 : this.constructor.xtype == B
    },
    getXTypes: function () {
        var A = this.constructor;
        if (!A.xtypes) {
            var C = [], B = this;
            while (B && B.constructor.xtype) {
                C.unshift(B.constructor.xtype);
                B = B.constructor.superclass
            }
            A.xtypeChain = C;
            A.xtypes = C.join("/")
        }
        return A.xtypes
    },
    findParentBy: function (A) {
        for (var B = this.ownerCt; (B != null) && !A(B, this); B = B.ownerCt) {
        }
        return B || null
    },
    findParentByType: function (A) {
        return typeof A == "function" ? this.findParentBy(function (B) {
            return B.constructor === A
        }) : this.findParentBy(function (B) {
            return B.constructor.xtype === A
        })
    },
    mon: function (E, B, D, C, A) {
        if (!this.mons) {
            this.mons = [];
            this.on("beforedestroy", function () {
                for (var H = 0, G = this.mons.length; H < G; H++) {
                    var F = this.mons[H];
                    F.item.un(F.ename, F.fn, F.scope)
                }
            }, this)
        }
        this.mons.push({item: E, ename: B, fn: D, scope: C});
        E.on(B, D, C, A)
    }
});
Ext.reg("component", Ext.Component);


(function () {
    Ext.Layer = function (D, C) {
        D = D || {};
        var E = Ext.DomHelper;
        var G = D.parentEl, F = G ? Ext.getDom(G) : document.body;
        if (C) {
            this.dom = Ext.getDom(C)
        }
        if (!this.dom) {
            var H = D.dh || {tag: "div", cls: "x-layer"};
            this.dom = E.append(F, H)
        }
        if (D.cls) {
            this.addClass(D.cls)
        }
        this.constrain = D.constrain !== false;
        this.visibilityMode = Ext.Element.VISIBILITY;
        if (D.id) {
            this.id = this.dom.id = D.id
        } else {
            this.id = Ext.id(this.dom)
        }
        this.zindex = D.zindex || this.getZIndex();
        this.position("absolute", this.zindex);
        if (D.shadow) {
            this.shadowOffset = D.shadowOffset || 4;
            this.shadow = new Ext.Shadow({offset: this.shadowOffset, mode: D.shadow})
        } else {
            this.shadowOffset = 0
        }
        this.useShim = D.shim !== false && Ext.useShims;
        this.useDisplay = D.useDisplay;
        this.hide()
    };
    var A = Ext.Element.prototype;
    var B = [];
    Ext.extend(Ext.Layer, Ext.Element, {
        getZIndex: function () {
            return this.zindex || parseInt(this.getStyle("z-index"), 10) || 11000
        }, getShim: function () {
            if (!this.useShim) {
                return null
            }
            if (this.shim) {
                return this.shim
            }
            var D = B.shift();
            if (!D) {
                D = this.createShim();
                D.enableDisplayMode("block");
                D.dom.style.display = "none";
                D.dom.style.visibility = "visible"
            }
            var C = this.dom.parentNode;
            if (D.dom.parentNode != C) {
                C.insertBefore(D.dom, this.dom)
            }
            D.setStyle("z-index", this.getZIndex() - 2);
            this.shim = D;
            return D
        }, hideShim: function () {
            if (this.shim) {
                this.shim.setDisplayed(false);
                B.push(this.shim);
                delete this.shim
            }
        }, disableShadow: function () {
            if (this.shadow) {
                this.shadowDisabled = true;
                this.shadow.hide();
                this.lastShadowOffset = this.shadowOffset;
                this.shadowOffset = 0
            }
        }, enableShadow: function (C) {
            if (this.shadow) {
                this.shadowDisabled = false;
                this.shadowOffset = this.lastShadowOffset;
                delete this.lastShadowOffset;
                if (C) {
                    this.sync(true)
                }
            }
        }, sync: function (C) {
            var I = this.shadow;
            if (!this.updating && this.isVisible() && (I || this.useShim)) {
                var F = this.getShim();
                var H = this.getWidth(), E = this.getHeight();
                var D = this.getLeft(true), J = this.getTop(true);
                if (I && !this.shadowDisabled) {
                    if (C && !I.isVisible()) {
                        I.show(this)
                    } else {
                        I.realign(D, J, H, E)
                    }
                    if (F) {
                        if (C) {
                            F.show()
                        }
                        var G = I.adjusts, K = F.dom.style;
                        K.left = (Math.min(D, D + G.l)) + "px";
                        K.top = (Math.min(J, J + G.t)) + "px";
                        K.width = (H + G.w) + "px";
                        K.height = (E + G.h) + "px"
                    }
                } else {
                    if (F) {
                        if (C) {
                            F.show()
                        }
                        F.setSize(H, E);
                        F.setLeftTop(D, J)
                    }
                }
            }
        }, destroy: function () {
            this.hideShim();
            if (this.shadow) {
                this.shadow.hide()
            }
            this.removeAllListeners();
            Ext.removeNode(this.dom);
            Ext.Element.uncache(this.id)
        }, remove: function () {
            this.destroy()
        }, beginUpdate: function () {
            this.updating = true
        }, endUpdate: function () {
            this.updating = false;
            this.sync(true)
        }, hideUnders: function (C) {
            if (this.shadow) {
                this.shadow.hide()
            }
            this.hideShim()
        }, constrainXY: function () {
            if (this.constrain) {
                var G = Ext.lib.Dom.getViewWidth(), C = Ext.lib.Dom.getViewHeight();
                var L = Ext.getDoc().getScroll();
                var K = this.getXY();
                var H = K[0], F = K[1];
                var I = this.dom.offsetWidth + this.shadowOffset, D = this.dom.offsetHeight + this.shadowOffset;
                var E = false;
                if ((H + I) > G + L.left) {
                    H = G - I - this.shadowOffset;
                    E = true
                }
                if ((F + D) > C + L.top) {
                    F = C - D - this.shadowOffset;
                    E = true
                }
                if (H < L.left) {
                    H = L.left;
                    E = true
                }
                if (F < L.top) {
                    F = L.top;
                    E = true
                }
                if (E) {
                    if (this.avoidY) {
                        var J = this.avoidY;
                        if (F <= J && (F + D) >= J) {
                            F = J - D - 5
                        }
                    }
                    K = [H, F];
                    this.storeXY(K);
                    A.setXY.call(this, K);
                    this.sync()
                }
            }
        }, isVisible: function () {
            return this.visible
        }, showAction: function () {
            this.visible = true;
            if (this.useDisplay === true) {
                this.setDisplayed("")
            } else {
                if (this.lastXY) {
                    A.setXY.call(this, this.lastXY)
                } else {
                    if (this.lastLT) {
                        A.setLeftTop.call(this, this.lastLT[0], this.lastLT[1])
                    }
                }
            }
        }, hideAction: function () {
            this.visible = false;
            if (this.useDisplay === true) {
                this.setDisplayed(false)
            } else {
                this.setLeftTop(-10000, -10000)
            }
        }, setVisible: function (E, D, G, H, F) {
            if (E) {
                this.showAction()
            }
            if (D && E) {
                var C = function () {
                    this.sync(true);
                    if (H) {
                        H()
                    }
                }.createDelegate(this);
                A.setVisible.call(this, true, true, G, C, F)
            } else {
                if (!E) {
                    this.hideUnders(true)
                }
                var C = H;
                if (D) {
                    C = function () {
                        this.hideAction();
                        if (H) {
                            H()
                        }
                    }.createDelegate(this)
                }
                A.setVisible.call(this, E, D, G, C, F);
                if (E) {
                    this.sync(true)
                } else {
                    if (!D) {
                        this.hideAction()
                    }
                }
            }
        }, storeXY: function (C) {
            delete this.lastLT;
            this.lastXY = C
        }, storeLeftTop: function (D, C) {
            delete this.lastXY;
            this.lastLT = [D, C]
        }, beforeFx: function () {
            this.beforeAction();
            return Ext.Layer.superclass.beforeFx.apply(this, arguments)
        }, afterFx: function () {
            Ext.Layer.superclass.afterFx.apply(this, arguments);
            this.sync(this.isVisible())
        }, beforeAction: function () {
            if (!this.updating && this.shadow) {
                this.shadow.hide()
            }
        }, setLeft: function (C) {
            this.storeLeftTop(C, this.getTop(true));
            A.setLeft.apply(this, arguments);
            this.sync()
        }, setTop: function (C) {
            this.storeLeftTop(this.getLeft(true), C);
            A.setTop.apply(this, arguments);
            this.sync()
        }, setLeftTop: function (D, C) {
            this.storeLeftTop(D, C);
            A.setLeftTop.apply(this, arguments);
            this.sync()
        }, setXY: function (F, D, G, H, E) {
            this.fixDisplay();
            this.beforeAction();
            this.storeXY(F);
            var C = this.createCB(H);
            A.setXY.call(this, F, D, G, C, E);
            if (!D) {
                C()
            }
        }, createCB: function (D) {
            var C = this;
            return function () {
                C.constrainXY();
                C.sync(true);
                if (D) {
                    D()
                }
            }
        }, setX: function (C, D, F, G, E) {
            this.setXY([C, this.getY()], D, F, G, E)
        }, setY: function (G, C, E, F, D) {
            this.setXY([this.getX(), G], C, E, F, D)
        }, setSize: function (E, F, D, H, I, G) {
            this.beforeAction();
            var C = this.createCB(I);
            A.setSize.call(this, E, F, D, H, C, G);
            if (!D) {
                C()
            }
        }, setWidth: function (E, D, G, H, F) {
            this.beforeAction();
            var C = this.createCB(H);
            A.setWidth.call(this, E, D, G, C, F);
            if (!D) {
                C()
            }
        }, setHeight: function (E, D, G, H, F) {
            this.beforeAction();
            var C = this.createCB(H);
            A.setHeight.call(this, E, D, G, C, F);
            if (!D) {
                C()
            }
        }, setBounds: function (J, H, K, D, I, F, G, E) {
            this.beforeAction();
            var C = this.createCB(G);
            if (!I) {
                this.storeXY([J, H]);
                A.setXY.call(this, [J, H]);
                A.setSize.call(this, K, D, I, F, C, E);
                C()
            } else {
                A.setBounds.call(this, J, H, K, D, I, F, C, E)
            }
            return this
        }, setZIndex: function (C) {
            this.zindex = C;
            this.setStyle("z-index", C + 2);
            if (this.shadow) {
                this.shadow.setZIndex(C + 1)
            }
            if (this.shim) {
                this.shim.setStyle("z-index", C)
            }
        }
    })
})();


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


Ext.BoxComponent = Ext.extend(Ext.Component, {
    initComponent: function () {
        Ext.BoxComponent.superclass.initComponent.call(this);
        this.addEvents("resize", "move")
    }, boxReady: false, deferHeight: false, setSize: function (B, D) {
        if (typeof B == "object") {
            D = B.height;
            B = B.width
        }
        if (!this.boxReady) {
            this.width = B;
            this.height = D;
            return this
        }
        if (this.lastSize && this.lastSize.width == B && this.lastSize.height == D) {
            return this
        }
        this.lastSize = {width: B, height: D};
        var C = this.adjustSize(B, D);
        var F = C.width, A = C.height;
        if (F !== undefined || A !== undefined) {
            var E = this.getResizeEl();
            if (!this.deferHeight && F !== undefined && A !== undefined) {
                E.setSize(F, A)
            } else {
                if (!this.deferHeight && A !== undefined) {
                    E.setHeight(A)
                } else {
                    if (F !== undefined) {
                        E.setWidth(F)
                    }
                }
            }
            this.onResize(F, A, B, D);
            this.fireEvent("resize", this, F, A, B, D)
        }
        return this
    }, setWidth: function (A) {
        return this.setSize(A)
    }, setHeight: function (A) {
        return this.setSize(undefined, A)
    }, getSize: function () {
        return this.el.getSize()
    }, getPosition: function (A) {
        if (A === true) {
            return [this.el.getLeft(true), this.el.getTop(true)]
        }
        return this.xy || this.el.getXY()
    }, getBox: function (A) {
        var B = this.el.getSize();
        if (A === true) {
            B.x = this.el.getLeft(true);
            B.y = this.el.getTop(true)
        } else {
            var C = this.xy || this.el.getXY();
            B.x = C[0];
            B.y = C[1]
        }
        return B
    }, updateBox: function (A) {
        this.setSize(A.width, A.height);
        this.setPagePosition(A.x, A.y);
        return this
    }, getResizeEl: function () {
        return this.resizeEl || this.el
    }, getPositionEl: function () {
        return this.positionEl || this.el
    }, setPosition: function (A, F) {
        if (A && typeof A[1] == "number") {
            F = A[1];
            A = A[0]
        }
        this.x = A;
        this.y = F;
        if (!this.boxReady) {
            return this
        }
        var B = this.adjustPosition(A, F);
        var E = B.x, D = B.y;
        var C = this.getPositionEl();
        if (E !== undefined || D !== undefined) {
            if (E !== undefined && D !== undefined) {
                C.setLeftTop(E, D)
            } else {
                if (E !== undefined) {
                    C.setLeft(E)
                } else {
                    if (D !== undefined) {
                        C.setTop(D)
                    }
                }
            }
            this.onPosition(E, D);
            this.fireEvent("move", this, E, D)
        }
        return this
    }, setPagePosition: function (A, C) {
        if (A && typeof A[1] == "number") {
            C = A[1];
            A = A[0]
        }
        this.pageX = A;
        this.pageY = C;
        if (!this.boxReady) {
            return
        }
        if (A === undefined || C === undefined) {
            return
        }
        var B = this.el.translatePoints(A, C);
        this.setPosition(B.left, B.top);
        return this
    }, onRender: function (B, A) {
        Ext.BoxComponent.superclass.onRender.call(this, B, A);
        if (this.resizeEl) {
            this.resizeEl = Ext.get(this.resizeEl)
        }
        if (this.positionEl) {
            this.positionEl = Ext.get(this.positionEl)
        }
    }, afterRender: function () {
        Ext.BoxComponent.superclass.afterRender.call(this);
        this.boxReady = true;
        this.setSize(this.width, this.height);
        if (this.x || this.y) {
            this.setPosition(this.x, this.y)
        } else {
            if (this.pageX || this.pageY) {
                this.setPagePosition(this.pageX, this.pageY)
            }
        }
    }, syncSize: function () {
        delete this.lastSize;
        this.setSize(this.autoWidth ? undefined : this.el.getWidth(), this.autoHeight ? undefined : this.el.getHeight());
        return this
    }, onResize: function (D, B, A, C) {
    }, onPosition: function (A, B) {
    }, adjustSize: function (A, B) {
        if (this.autoWidth) {
            A = "auto"
        }
        if (this.autoHeight) {
            B = "auto"
        }
        return {width: A, height: B}
    }, adjustPosition: function (A, B) {
        return {x: A, y: B}
    }
});
Ext.reg("box", Ext.BoxComponent);


Ext.Container = Ext.extend(Ext.BoxComponent, {
    autoDestroy: true, defaultType: "panel", initComponent: function () {
        Ext.Container.superclass.initComponent.call(this);
        this.addEvents("afterlayout", "beforeadd", "beforeremove", "add", "remove");
        var A = this.items;
        if (A) {
            delete this.items;
            if (Ext.isArray(A)) {
                this.add.apply(this, A)
            } else {
                this.add(A)
            }
        }
    }, initItems: function () {
        if (!this.items) {
            this.items = new Ext.util.MixedCollection(false, this.getComponentId);
            this.getLayout()
        }
    }, setLayout: function (A) {
        if (this.layout && this.layout != A) {
            this.layout.setContainer(null)
        }
        this.initItems();
        this.layout = A;
        A.setContainer(this)
    }, render: function () {
        Ext.Container.superclass.render.apply(this, arguments);
        if (this.layout) {
            if (typeof this.layout == "string") {
                this.layout = new Ext.Container.LAYOUTS[this.layout.toLowerCase()](this.layoutConfig)
            }
            this.setLayout(this.layout);
            if (this.activeItem !== undefined) {
                var A = this.activeItem;
                delete this.activeItem;
                this.layout.setActiveItem(A);
                return
            }
        }
        if (!this.ownerCt) {
            this.doLayout()
        }
        if (this.monitorResize === true) {
            Ext.EventManager.onWindowResize(this.doLayout, this, [false])
        }
    }, getLayoutTarget: function () {
        return this.el
    }, getComponentId: function (A) {
        return A.itemId || A.id
    }, add: function (C) {
        if (!this.items) {
            this.initItems()
        }
        var B = arguments, A = B.length;
        if (A > 1) {
            for (var D = 0; D < A; D++) {
                this.add(B[D])
            }
            return
        }
        var F = this.lookupComponent(this.applyDefaults(C));
        var E = this.items.length;
        if (this.fireEvent("beforeadd", this, F, E) !== false && this.onBeforeAdd(F) !== false) {
            this.items.add(F);
            F.ownerCt = this;
            this.fireEvent("add", this, F, E)
        }
        return F
    }, insert: function (D, C) {
        if (!this.items) {
            this.initItems()
        }
        var B = arguments, A = B.length;
        if (A > 2) {
            for (var E = A - 1; E >= 1; --E) {
                this.insert(D, B[E])
            }
            return
        }
        var F = this.lookupComponent(this.applyDefaults(C));
        if (F.ownerCt == this && this.items.indexOf(F) < D) {
            --D
        }
        if (this.fireEvent("beforeadd", this, F, D) !== false && this.onBeforeAdd(F) !== false) {
            this.items.insert(D, F);
            F.ownerCt = this;
            this.fireEvent("add", this, F, D)
        }
        return F
    }, applyDefaults: function (A) {
        if (this.defaults) {
            if (typeof A == "string") {
                A = Ext.ComponentMgr.get(A);
                Ext.apply(A, this.defaults)
            } else {
                if (!A.events) {
                    Ext.applyIf(A, this.defaults)
                } else {
                    Ext.apply(A, this.defaults)
                }
            }
        }
        return A
    }, onBeforeAdd: function (A) {
        if (A.ownerCt) {
            A.ownerCt.remove(A, false)
        }
        if (this.hideBorders === true) {
            A.border = (A.border === true)
        }
    }, remove: function (A, B) {
        var C = this.getComponent(A);
        if (C && this.fireEvent("beforeremove", this, C) !== false) {
            this.items.remove(C);
            delete C.ownerCt;
            if (B === true || (B !== false && this.autoDestroy)) {
                C.destroy()
            }
            if (this.layout && this.layout.activeItem == C) {
                delete this.layout.activeItem
            }
            this.fireEvent("remove", this, C)
        }
        return C
    }, getComponent: function (A) {
        if (typeof A == "object") {
            return A
        }
        return this.items.get(A)
    }, lookupComponent: function (A) {
        if (typeof A == "string") {
            return Ext.ComponentMgr.get(A)
        } else {
            if (!A.events) {
                return this.createComponent(A)
            }
        }
        return A
    }, createComponent: function (A) {
        return Ext.ComponentMgr.create(A, this.defaultType)
    }, doLayout: function (D) {
        if (this.rendered && this.layout) {
            this.layout.layout()
        }
        if (D !== false && this.items) {
            var C = this.items.items;
            for (var B = 0, A = C.length; B < A; B++) {
                var E = C[B];
                if (E.doLayout) {
                    E.doLayout()
                }
            }
        }
    }, getLayout: function () {
        if (!this.layout) {
            var A = new Ext.layout.ContainerLayout(this.layoutConfig);
            this.setLayout(A)
        }
        return this.layout
    }, beforeDestroy: function () {
        if (this.items) {
            Ext.destroy.apply(Ext, this.items.items)
        }
        if (this.monitorResize) {
            Ext.EventManager.removeResizeListener(this.doLayout, this)
        }
        if (this.layout && this.layout.destroy) {
            this.layout.destroy()
        }
        Ext.Container.superclass.beforeDestroy.call(this)
    }, bubble: function (C, B, A) {
        var D = this;
        while (D) {
            if (C.apply(B || D, A || [D]) === false) {
                break
            }
            D = D.ownerCt
        }
    }, cascade: function (F, E, B) {
        if (F.apply(E || this, B || [this]) !== false) {
            if (this.items) {
                var D = this.items.items;
                for (var C = 0, A = D.length; C < A; C++) {
                    if (D[C].cascade) {
                        D[C].cascade(F, E, B)
                    } else {
                        F.apply(E || D[C], B || [D[C]])
                    }
                }
            }
        }
    }, findById: function (C) {
        var A, B = this;
        this.cascade(function (D) {
            if (B != D && D.id === C) {
                A = D;
                return false
            }
        });
        return A || null
    }, findByType: function (A) {
        return typeof A == "function" ? this.findBy(function (B) {
            return B.constructor === A
        }) : this.findBy(function (B) {
            return B.constructor.xtype === A
        })
    }, find: function (B, A) {
        return this.findBy(function (C) {
            return C[B] === A
        })
    }, findBy: function (D, C) {
        var A = [], B = this;
        this.cascade(function (E) {
            if (B != E && D.call(C || E, E, B) === true) {
                A.push(E)
            }
        });
        return A
    }
});
Ext.Container.LAYOUTS = {};
Ext.reg("container", Ext.Container);


Ext.Panel = Ext.extend(Ext.Container, {
    baseCls: "x-panel",
    collapsedCls: "x-panel-collapsed",
    maskDisabled: true,
    animCollapse: Ext.enableFx,
    headerAsText: true,
    buttonAlign: "right",
    collapsed: false,
    collapseFirst: true,
    minButtonWidth: 75,
    elements: "body",
    toolTarget: "header",
    collapseEl: "bwrap",
    slideAnchor: "t",
    disabledClass: "",
    deferHeight: true,
    expandDefaults: {duration: 0.25},
    collapseDefaults: {duration: 0.25},
    initComponent: function () {
        Ext.Panel.superclass.initComponent.call(this);
        this.addEvents("bodyresize", "titlechange", "collapse", "expand", "beforecollapse", "beforeexpand", "beforeclose", "close", "activate", "deactivate");
        if (this.tbar) {
            this.elements += ",tbar";
            if (typeof this.tbar == "object") {
                this.topToolbar = this.tbar
            }
            delete this.tbar
        }
        if (this.bbar) {
            this.elements += ",bbar";
            if (typeof this.bbar == "object") {
                this.bottomToolbar = this.bbar
            }
            delete this.bbar
        }
        if (this.header === true) {
            this.elements += ",header";
            delete this.header
        } else {
            if (this.title && this.header !== false) {
                this.elements += ",header"
            }
        }
        if (this.footer === true) {
            this.elements += ",footer";
            delete this.footer
        }
        if (this.buttons) {
            var C = this.buttons;
            this.buttons = [];
            for (var B = 0, A = C.length; B < A; B++) {
                if (C[B].render) {
                    C[B].ownerCt = this;
                    this.buttons.push(C[B])
                } else {
                    this.addButton(C[B])
                }
            }
        }
        if (this.autoLoad) {
            this.on("render", this.doAutoLoad, this, {delay: 10})
        }
    },
    createElement: function (A, C) {
        if (this[A]) {
            C.appendChild(this[A].dom);
            return
        }
        if (A === "bwrap" || this.elements.indexOf(A) != -1) {
            if (this[A + "Cfg"]) {
                this[A] = Ext.fly(C).createChild(this[A + "Cfg"])
            } else {
                var B = document.createElement("div");
                B.className = this[A + "Cls"];
                this[A] = Ext.get(C.appendChild(B))
            }
        }
    },
    onRender: function (H, G) {
        Ext.Panel.superclass.onRender.call(this, H, G);
        this.createClasses();
        if (this.el) {
            this.el.addClass(this.baseCls);
            this.header = this.el.down("." + this.headerCls);
            this.bwrap = this.el.down("." + this.bwrapCls);
            var M = this.bwrap ? this.bwrap : this.el;
            this.tbar = M.down("." + this.tbarCls);
            this.body = M.down("." + this.bodyCls);
            this.bbar = M.down("." + this.bbarCls);
            this.footer = M.down("." + this.footerCls);
            this.fromMarkup = true
        } else {
            this.el = H.createChild({id: this.id, cls: this.baseCls}, G)
        }
        var A = this.el, K = A.dom;
        if (this.cls) {
            this.el.addClass(this.cls)
        }
        if (this.buttons) {
            this.elements += ",footer"
        }
        if (this.frame) {
            A.insertHtml("afterBegin", String.format(Ext.Element.boxMarkup, this.baseCls));
            this.createElement("header", K.firstChild.firstChild.firstChild);
            this.createElement("bwrap", K);
            var O = this.bwrap.dom;
            var E = K.childNodes[1], B = K.childNodes[2];
            O.appendChild(E);
            O.appendChild(B);
            var P = O.firstChild.firstChild.firstChild;
            this.createElement("tbar", P);
            this.createElement("body", P);
            this.createElement("bbar", P);
            this.createElement("footer", O.lastChild.firstChild.firstChild);
            if (!this.footer) {
                this.bwrap.dom.lastChild.className += " x-panel-nofooter"
            }
        } else {
            this.createElement("header", K);
            this.createElement("bwrap", K);
            var O = this.bwrap.dom;
            this.createElement("tbar", O);
            this.createElement("body", O);
            this.createElement("bbar", O);
            this.createElement("footer", O);
            if (!this.header) {
                this.body.addClass(this.bodyCls + "-noheader");
                if (this.tbar) {
                    this.tbar.addClass(this.tbarCls + "-noheader")
                }
            }
        }
        if (this.border === false) {
            this.el.addClass(this.baseCls + "-noborder");
            this.body.addClass(this.bodyCls + "-noborder");
            if (this.header) {
                this.header.addClass(this.headerCls + "-noborder")
            }
            if (this.footer) {
                this.footer.addClass(this.footerCls + "-noborder")
            }
            if (this.tbar) {
                this.tbar.addClass(this.tbarCls + "-noborder")
            }
            if (this.bbar) {
                this.bbar.addClass(this.bbarCls + "-noborder")
            }
        }
        if (this.bodyBorder === false) {
            this.body.addClass(this.bodyCls + "-noborder")
        }
        if (this.bodyStyle) {
            this.body.applyStyles(this.bodyStyle)
        }
        this.bwrap.enableDisplayMode("block");
        if (this.header) {
            this.header.unselectable();
            if (this.headerAsText) {
                this.header.dom.innerHTML = "<span class=\"" + this.headerTextCls + "\">" + this.header.dom.innerHTML + "</span>";
                if (this.iconCls) {
                    this.setIconClass(this.iconCls)
                }
            }
        }
        if (this.floating) {
            this.makeFloating(this.floating)
        }
        if (this.collapsible) {
            this.tools = this.tools ? this.tools.slice(0) : [];
            if (!this.hideCollapseTool) {
                this.tools[this.collapseFirst ? "unshift" : "push"]({
                    id: "toggle",
                    handler: this.toggleCollapse,
                    scope: this
                })
            }
            if (this.titleCollapse && this.header) {
                this.header.on("click", this.toggleCollapse, this);
                this.header.setStyle("cursor", "pointer")
            }
        }
        if (this.tools) {
            var J = this.tools;
            this.tools = {};
            this.addTool.apply(this, J)
        } else {
            this.tools = {}
        }
        if (this.buttons && this.buttons.length > 0) {
            var D = this.footer.createChild({
                cls: "x-panel-btns-ct",
                cn: {
                    cls: "x-panel-btns x-panel-btns-" + this.buttonAlign,
                    html: "<table cellspacing=\"0\"><tbody><tr></tr></tbody></table><div class=\"x-clear\"></div>"
                }
            }, null, true);
            var L = D.getElementsByTagName("tr")[0];
            for (var F = 0, I = this.buttons.length; F < I; F++) {
                var N = this.buttons[F];
                var C = document.createElement("td");
                C.className = "x-panel-btn-td";
                N.render(L.appendChild(C))
            }
        }
        if (this.tbar && this.topToolbar) {
            if (Ext.isArray(this.topToolbar)) {
                this.topToolbar = new Ext.Toolbar(this.topToolbar)
            }
            this.topToolbar.render(this.tbar);
            this.topToolbar.ownerCt = this
        }
        if (this.bbar && this.bottomToolbar) {
            if (Ext.isArray(this.bottomToolbar)) {
                this.bottomToolbar = new Ext.Toolbar(this.bottomToolbar)
            }
            this.bottomToolbar.render(this.bbar);
            this.bottomToolbar.ownerCt = this
        }
    },
    setIconClass: function (B) {
        var A = this.iconCls;
        this.iconCls = B;
        if (this.rendered && this.header) {
            if (this.frame) {
                this.header.addClass("x-panel-icon");
                this.header.replaceClass(A, this.iconCls)
            } else {
                var D = this.header.dom;
                var C = D.firstChild && String(D.firstChild.tagName).toLowerCase() == "img" ? D.firstChild : null;
                if (C) {
                    Ext.fly(C).replaceClass(A, this.iconCls)
                } else {
                    Ext.DomHelper.insertBefore(D.firstChild, {
                        tag: "img",
                        src: Ext.BLANK_IMAGE_URL,
                        cls: "x-panel-inline-icon " + this.iconCls
                    })
                }
            }
        }
    },
    makeFloating: function (A) {
        this.floating = true;
        this.el = new Ext.Layer(typeof A == "object" ? A : {
            shadow: this.shadow !== undefined ? this.shadow : "sides",
            shadowOffset: this.shadowOffset,
            constrain: false,
            shim: this.shim === false ? false : undefined
        }, this.el)
    },
    getTopToolbar: function () {
        return this.topToolbar
    },
    getBottomToolbar: function () {
        return this.bottomToolbar
    },
    addButton: function (A, D, C) {
        var E = {handler: D, scope: C, minWidth: this.minButtonWidth, hideParent: true};
        if (typeof A == "string") {
            E.text = A
        } else {
            Ext.apply(E, A)
        }
        var B = new Ext.Button(E);
        B.ownerCt = this;
        if (!this.buttons) {
            this.buttons = []
        }
        this.buttons.push(B);
        return B
    },
    addTool: function () {
        if (!this[this.toolTarget]) {
            return
        }
        if (!this.toolTemplate) {
            var F = new Ext.Template("<div class=\"x-tool x-tool-{id}\">&#160;</div>");
            F.disableFormats = true;
            F.compile();
            Ext.Panel.prototype.toolTemplate = F
        }
        for (var E = 0, C = arguments, B = C.length; E < B; E++) {
            var A = C[E], G = "x-tool-" + A.id + "-over";
            var D = this.toolTemplate.insertFirst((A.align !== "left") ? this[this.toolTarget] : this[this.toolTarget].child("span"), A, true);
            this.tools[A.id] = D;
            D.enableDisplayMode("block");
            D.on("click", this.createToolHandler(D, A, G, this));
            if (A.on) {
                D.on(A.on)
            }
            if (A.hidden) {
                D.hide()
            }
            if (A.qtip) {
                if (typeof A.qtip == "object") {
                    Ext.QuickTips.register(Ext.apply({target: D.id}, A.qtip))
                } else {
                    D.dom.qtip = A.qtip
                }
            }
            D.addClassOnOver(G)
        }
    },
    onShow: function () {
        if (this.floating) {
            return this.el.show()
        }
        Ext.Panel.superclass.onShow.call(this)
    },
    onHide: function () {
        if (this.floating) {
            return this.el.hide()
        }
        Ext.Panel.superclass.onHide.call(this)
    },
    createToolHandler: function (C, A, D, B) {
        return function (E) {
            C.removeClass(D);
            E.stopEvent();
            if (A.handler) {
                A.handler.call(A.scope || C, E, C, B)
            }
        }
    },
    afterRender: function () {
        if (this.fromMarkup && this.height === undefined && !this.autoHeight) {
            this.height = this.el.getHeight()
        }
        if (this.floating && !this.hidden && !this.initHidden) {
            this.el.show()
        }
        if (this.title) {
            this.setTitle(this.title)
        }
        this.setAutoScroll();
        if (this.html) {
            this.body.update(typeof this.html == "object" ? Ext.DomHelper.markup(this.html) : this.html);
            delete this.html
        }
        if (this.contentEl) {
            var A = Ext.getDom(this.contentEl);
            Ext.fly(A).removeClass(["x-hidden", "x-hide-display"]);
            this.body.dom.appendChild(A)
        }
        if (this.collapsed) {
            this.collapsed = false;
            this.collapse(false)
        }
        Ext.Panel.superclass.afterRender.call(this);
        this.initEvents()
    },
    setAutoScroll: function () {
        if (this.rendered && this.autoScroll) {
            var A = this.body || this.el;
            if (A) {
                A.setOverflow("auto")
            }
        }
    },
    getKeyMap: function () {
        if (!this.keyMap) {
            this.keyMap = new Ext.KeyMap(this.el, this.keys)
        }
        return this.keyMap
    },
    initEvents: function () {
        if (this.keys) {
            this.getKeyMap()
        }
        if (this.draggable) {
            this.initDraggable()
        }
    },
    initDraggable: function () {
        this.dd = new Ext.Panel.DD(this, typeof this.draggable == "boolean" ? null : this.draggable)
    },
    beforeEffect: function () {
        if (this.floating) {
            this.el.beforeAction()
        }
        this.el.addClass("x-panel-animated")
    },
    afterEffect: function () {
        this.syncShadow();
        this.el.removeClass("x-panel-animated")
    },
    createEffect: function (B, A, C) {
        var D = {scope: C, block: true};
        if (B === true) {
            D.callback = A;
            return D
        } else {
            if (!B.callback) {
                D.callback = A
            } else {
                D.callback = function () {
                    A.call(C);
                    Ext.callback(B.callback, B.scope)
                }
            }
        }
        return Ext.applyIf(D, B)
    },
    collapse: function (B) {
        if (this.collapsed || this.el.hasFxBlock() || this.fireEvent("beforecollapse", this, B) === false) {
            return
        }
        var A = B === true || (B !== false && this.animCollapse);
        this.beforeEffect();
        this.onCollapse(A, B);
        return this
    },
    onCollapse: function (A, B) {
        if (A) {
            this[this.collapseEl].slideOut(this.slideAnchor, Ext.apply(this.createEffect(B || true, this.afterCollapse, this), this.collapseDefaults))
        } else {
            this[this.collapseEl].hide();
            this.afterCollapse()
        }
    },
    afterCollapse: function () {
        this.collapsed = true;
        this.el.addClass(this.collapsedCls);
        this.afterEffect();
        this.fireEvent("collapse", this)
    },
    expand: function (B) {
        if (!this.collapsed || this.el.hasFxBlock() || this.fireEvent("beforeexpand", this, B) === false) {
            return
        }
        var A = B === true || (B !== false && this.animCollapse);
        this.el.removeClass(this.collapsedCls);
        this.beforeEffect();
        this.onExpand(A, B);
        return this
    },
    onExpand: function (A, B) {
        if (A) {
            this[this.collapseEl].slideIn(this.slideAnchor, Ext.apply(this.createEffect(B || true, this.afterExpand, this), this.expandDefaults))
        } else {
            this[this.collapseEl].show();
            this.afterExpand()
        }
    },
    afterExpand: function () {
        this.collapsed = false;
        this.afterEffect();
        this.fireEvent("expand", this)
    },
    toggleCollapse: function (A) {
        this[this.collapsed ? "expand" : "collapse"](A);
        return this
    },
    onDisable: function () {
        if (this.rendered && this.maskDisabled) {
            this.el.mask()
        }
        Ext.Panel.superclass.onDisable.call(this)
    },
    onEnable: function () {
        if (this.rendered && this.maskDisabled) {
            this.el.unmask()
        }
        Ext.Panel.superclass.onEnable.call(this)
    },
    onResize: function (A, B) {
        if (A !== undefined || B !== undefined) {
            if (!this.collapsed) {
                if (typeof A == "number") {
                    this.body.setWidth(this.adjustBodyWidth(A - this.getFrameWidth()))
                } else {
                    if (A == "auto") {
                        this.body.setWidth(A)
                    }
                }
                if (typeof B == "number") {
                    this.body.setHeight(this.adjustBodyHeight(B - this.getFrameHeight()))
                } else {
                    if (B == "auto") {
                        this.body.setHeight(B)
                    }
                }
                if (this.disabled && this.el._mask) {
                    this.el._mask.setSize(this.el.dom.clientWidth, this.el.getHeight())
                }
            } else {
                this.queuedBodySize = {width: A, height: B};
                if (!this.queuedExpand && this.allowQueuedExpand !== false) {
                    this.queuedExpand = true;
                    this.on("expand", function () {
                        delete this.queuedExpand;
                        this.onResize(this.queuedBodySize.width, this.queuedBodySize.height);
                        this.doLayout()
                    }, this, {single: true})
                }
            }
            this.fireEvent("bodyresize", this, A, B)
        }
        this.syncShadow()
    },
    adjustBodyHeight: function (A) {
        return A
    },
    adjustBodyWidth: function (A) {
        return A
    },
    onPosition: function () {
        this.syncShadow()
    },
    getFrameWidth: function () {
        var B = this.el.getFrameWidth("lr");
        if (this.frame) {
            var A = this.bwrap.dom.firstChild;
            B += (Ext.fly(A).getFrameWidth("l") + Ext.fly(A.firstChild).getFrameWidth("r"));
            var C = this.bwrap.dom.firstChild.firstChild.firstChild;
            B += Ext.fly(C).getFrameWidth("lr")
        }
        return B
    },
    getFrameHeight: function () {
        var A = this.el.getFrameWidth("tb");
        A += (this.tbar ? this.tbar.getHeight() : 0) + (this.bbar ? this.bbar.getHeight() : 0);
        if (this.frame) {
            var C = this.el.dom.firstChild;
            var D = this.bwrap.dom.lastChild;
            A += (C.offsetHeight + D.offsetHeight);
            var B = this.bwrap.dom.firstChild.firstChild.firstChild;
            A += Ext.fly(B).getFrameWidth("tb")
        } else {
            A += (this.header ? this.header.getHeight() : 0) + (this.footer ? this.footer.getHeight() : 0)
        }
        return A
    },
    getInnerWidth: function () {
        return this.getSize().width - this.getFrameWidth()
    },
    getInnerHeight: function () {
        return this.getSize().height - this.getFrameHeight()
    },
    syncShadow: function () {
        if (this.floating) {
            this.el.sync(true)
        }
    },
    getLayoutTarget: function () {
        return this.body
    },
    setTitle: function (B, A) {
        this.title = B;
        if (this.header && this.headerAsText) {
            this.header.child("span").update(B)
        }
        if (A) {
            this.setIconClass(A)
        }
        this.fireEvent("titlechange", this, B);
        return this
    },
    getUpdater: function () {
        return this.body.getUpdater()
    },
    load: function () {
        var A = this.body.getUpdater();
        A.update.apply(A, arguments);
        return this
    },
    beforeDestroy: function () {
        Ext.Element.uncache(this.header, this.tbar, this.bbar, this.footer, this.body);
        if (this.tools) {
            for (var B in this.tools) {
                Ext.destroy(this.tools[B])
            }
        }
        if (this.buttons) {
            for (var A in this.buttons) {
                Ext.destroy(this.buttons[A])
            }
        }
        Ext.destroy(this.topToolbar, this.bottomToolbar);
        Ext.Panel.superclass.beforeDestroy.call(this)
    },
    createClasses: function () {
        this.headerCls = this.baseCls + "-header";
        this.headerTextCls = this.baseCls + "-header-text";
        this.bwrapCls = this.baseCls + "-bwrap";
        this.tbarCls = this.baseCls + "-tbar";
        this.bodyCls = this.baseCls + "-body";
        this.bbarCls = this.baseCls + "-bbar";
        this.footerCls = this.baseCls + "-footer"
    },
    createGhost: function (A, E, B) {
        var D = document.createElement("div");
        D.className = "x-panel-ghost " + (A ? A : "");
        if (this.header) {
            D.appendChild(this.el.dom.firstChild.cloneNode(true))
        }
        Ext.fly(D.appendChild(document.createElement("ul"))).setHeight(this.bwrap.getHeight());
        D.style.width = this.el.dom.offsetWidth + "px";
        if (!B) {
            this.container.dom.appendChild(D)
        } else {
            Ext.getDom(B).appendChild(D)
        }
        if (E !== false && this.el.useShim !== false) {
            var C = new Ext.Layer({shadow: false, useDisplay: true, constrain: false}, D);
            C.show();
            return C
        } else {
            return new Ext.Element(D)
        }
    },
    doAutoLoad: function () {
        this.body.load(typeof this.autoLoad == "object" ? this.autoLoad : {url: this.autoLoad})
    }
});
Ext.reg("panel", Ext.Panel);


Ext.Editor = function (B, A) {
    this.field = B;
    Ext.Editor.superclass.constructor.call(this, A)
};
Ext.extend(Ext.Editor, Ext.Component, {
    value: "",
    alignment: "c-c?",
    shadow: "frame",
    constrain: false,
    swallowKeys: true,
    completeOnEnter: false,
    cancelOnEsc: false,
    updateEl: false,
    initComponent: function () {
        Ext.Editor.superclass.initComponent.call(this);
        this.addEvents("beforestartedit", "startedit", "beforecomplete", "complete", "canceledit", "specialkey")
    },
    onRender: function (B, A) {
        this.el = new Ext.Layer({
            shadow: this.shadow,
            cls: "x-editor",
            parentEl: B,
            shim: this.shim,
            shadowOffset: 4,
            id: this.id,
            constrain: this.constrain
        });
        this.el.setStyle("overflow", Ext.isGecko ? "auto" : "hidden");
        if (this.field.msgTarget != "title") {
            this.field.msgTarget = "qtip"
        }
        this.field.inEditor = true;
        this.field.render(this.el);
        if (Ext.isGecko) {
            this.field.el.dom.setAttribute("autocomplete", "off")
        }
        this.field.on("specialkey", this.onSpecialKey, this);
        if (this.swallowKeys) {
            this.field.el.swallowEvent(["keydown", "keypress"])
        }
        this.field.show();
        this.field.on("blur", this.onBlur, this);
        if (this.field.grow) {
            this.field.on("autosize", this.el.sync, this.el, {delay: 1})
        }
    },
    onSpecialKey: function (C, B) {
        var A = B.getKey();
        if (this.completeOnEnter && A == B.ENTER) {
            B.stopEvent();
            this.completeEdit()
        } else {
            if (this.cancelOnEsc && A == B.ESC) {
                this.cancelEdit()
            } else {
                this.fireEvent("specialkey", C, B)
            }
        }
        if (this.field.triggerBlur && (A == B.ENTER || A == B.ESC || A == B.TAB)) {
            this.field.triggerBlur()
        }
    },
    startEdit: function (B, C) {
        if (this.editing) {
            this.completeEdit()
        }
        this.boundEl = Ext.get(B);
        var A = C !== undefined ? C : this.boundEl.dom.innerHTML;
        if (!this.rendered) {
            this.render(this.parentEl || document.body)
        }
        if (this.fireEvent("beforestartedit", this, this.boundEl, A) === false) {
            return
        }
        this.startValue = A;
        this.field.setValue(A);
        this.doAutoSize();
        this.el.alignTo(this.boundEl, this.alignment);
        this.editing = true;
        this.show()
    },
    doAutoSize: function () {
        if (this.autoSize) {
            var A = this.boundEl.getSize();
            switch (this.autoSize) {
                case"width":
                    this.setSize(A.width, "");
                    break;
                case"height":
                    this.setSize("", A.height);
                    break;
                default:
                    this.setSize(A.width, A.height)
            }
        }
    },
    setSize: function (A, B) {
        delete this.field.lastSize;
        this.field.setSize(A, B);
        if (this.el) {
            if (Ext.isGecko2 || Ext.isOpera) {
                this.el.setSize(A, B)
            }
            this.el.sync()
        }
    },
    realign: function () {
        this.el.alignTo(this.boundEl, this.alignment)
    },
    completeEdit: function (A) {
        if (!this.editing) {
            return
        }
        var B = this.getValue();
        if (this.revertInvalid !== false && !this.field.isValid()) {
            B = this.startValue;
            this.cancelEdit(true)
        }
        if (String(B) === String(this.startValue) && this.ignoreNoChange) {
            this.editing = false;
            this.hide();
            return
        }
        if (this.fireEvent("beforecomplete", this, B, this.startValue) !== false) {
            this.editing = false;
            if (this.updateEl && this.boundEl) {
                this.boundEl.update(B)
            }
            if (A !== true) {
                this.hide()
            }
            this.fireEvent("complete", this, B, this.startValue)
        }
    },
    onShow: function () {
        this.el.show();
        if (this.hideEl !== false) {
            this.boundEl.hide()
        }
        this.field.show();
        if (Ext.isIE && !this.fixIEFocus) {
            this.fixIEFocus = true;
            this.deferredFocus.defer(50, this)
        } else {
            this.field.focus()
        }
        this.fireEvent("startedit", this.boundEl, this.startValue)
    },
    deferredFocus: function () {
        if (this.editing) {
            this.field.focus()
        }
    },
    cancelEdit: function (A) {
        if (this.editing) {
            var B = this.getValue();
            this.setValue(this.startValue);
            if (A !== true) {
                this.hide()
            }
            this.fireEvent("canceledit", this, B, this.startValue)
        }
    },
    onBlur: function () {
        if (this.allowBlur !== true && this.editing) {
            this.completeEdit()
        }
    },
    onHide: function () {
        if (this.editing) {
            this.completeEdit();
            return
        }
        this.field.blur();
        if (this.field.collapse) {
            this.field.collapse()
        }
        this.el.hide();
        if (this.hideEl !== false) {
            this.boundEl.show()
        }
    },
    setValue: function (A) {
        this.field.setValue(A)
    },
    getValue: function () {
        return this.field.getValue()
    },
    beforeDestroy: function () {
        this.field.destroy();
        this.field = null
    }
});
Ext.reg("editor", Ext.Editor);


Ext.tree.TreePanel = Ext.extend(Ext.Panel, {
    rootVisible: true,
    animate: Ext.enableFx,
    lines: true,
    enableDD: false,
    hlDrop: Ext.enableFx,
    pathSeparator: "/",
    initComponent: function () {
        Ext.tree.TreePanel.superclass.initComponent.call(this);
        if (!this.eventModel) {
            this.eventModel = new Ext.tree.TreeEventModel(this)
        }
        var A = this.loader;
        if (!A) {
            A = new Ext.tree.TreeLoader({dataUrl: this.dataUrl})
        } else {
            if (typeof A == "object" && !A.load) {
                A = new Ext.tree.TreeLoader(A)
            }
        }
        this.loader = A;
        this.nodeHash = {};
        if (this.root) {
            this.setRootNode(this.root)
        }
        this.addEvents("append", "remove", "movenode", "insert", "beforeappend", "beforeremove", "beforemovenode", "beforeinsert", "beforeload", "load", "textchange", "beforeexpandnode", "beforecollapsenode", "expandnode", "disabledchange", "collapsenode", "beforeclick", "click", "checkchange", "dblclick", "contextmenu", "beforechildrenrendered", "startdrag", "enddrag", "dragdrop", "beforenodedrop", "nodedrop", "nodedragover");
        if (this.singleExpand) {
            this.on("beforeexpandnode", this.restrictExpand, this)
        }
    },
    proxyNodeEvent: function (C, B, A, G, F, E, D) {
        if (C == "collapse" || C == "expand" || C == "beforecollapse" || C == "beforeexpand" || C == "move" || C == "beforemove") {
            C = C + "node"
        }
        return this.fireEvent(C, B, A, G, F, E, D)
    },
    getRootNode: function () {
        return this.root
    },
    setRootNode: function (B) {
        if (!B.render) {
            B = this.loader.createNode(B)
        }
        this.root = B;
        B.ownerTree = this;
        B.isRoot = true;
        this.registerNode(B);
        if (!this.rootVisible) {
            var A = B.attributes.uiProvider;
            B.ui = A ? new A(B) : new Ext.tree.RootTreeNodeUI(B)
        }
        return B
    },
    getNodeById: function (A) {
        return this.nodeHash[A]
    },
    registerNode: function (A) {
        this.nodeHash[A.id] = A
    },
    unregisterNode: function (A) {
        delete this.nodeHash[A.id]
    },
    toString: function () {
        return "[Tree" + (this.id ? " " + this.id : "") + "]"
    },
    restrictExpand: function (A) {
        var B = A.parentNode;
        if (B) {
            if (B.expandedChild && B.expandedChild.parentNode == B) {
                B.expandedChild.collapse()
            }
            B.expandedChild = A
        }
    },
    getChecked: function (A, B) {
        B = B || this.root;
        var C = [];
        var D = function () {
            if (this.attributes.checked) {
                C.push(!A ? this : (A == "id" ? this.id : this.attributes[A]))
            }
        };
        B.cascade(D);
        return C
    },
    getEl: function () {
        return this.el
    },
    getLoader: function () {
        return this.loader
    },
    expandAll: function () {
        this.root.expand(true)
    },
    collapseAll: function () {
        this.root.collapse(true)
    },
    getSelectionModel: function () {
        if (!this.selModel) {
            this.selModel = new Ext.tree.DefaultSelectionModel()
        }
        return this.selModel
    },
    expandPath: function (F, A, G) {
        A = A || "id";
        var D = F.split(this.pathSeparator);
        var C = this.root;
        if (C.attributes[A] != D[1]) {
            if (G) {
                G(false, null)
            }
            return
        }
        var B = 1;
        var E = function () {
            if (++B == D.length) {
                if (G) {
                    G(true, C)
                }
                return
            }
            var H = C.findChild(A, D[B]);
            if (!H) {
                if (G) {
                    G(false, C)
                }
                return
            }
            C = H;
            H.expand(false, false, E)
        };
        C.expand(false, false, E)
    },
    selectPath: function (E, A, F) {
        A = A || "id";
        var C = E.split(this.pathSeparator);
        var B = C.pop();
        if (C.length > 0) {
            var D = function (H, G) {
                if (H && G) {
                    var I = G.findChild(A, B);
                    if (I) {
                        I.select();
                        if (F) {
                            F(true, I)
                        }
                    } else {
                        if (F) {
                            F(false, I)
                        }
                    }
                } else {
                    if (F) {
                        F(false, I)
                    }
                }
            };
            this.expandPath(C.join(this.pathSeparator), A, D)
        } else {
            this.root.select();
            if (F) {
                F(true, this.root)
            }
        }
    },
    getTreeEl: function () {
        return this.body
    },
    onRender: function (B, A) {
        Ext.tree.TreePanel.superclass.onRender.call(this, B, A);
        this.el.addClass("x-tree");
        this.innerCt = this.body.createChild({
            tag: "ul",
            cls: "x-tree-root-ct " + (this.useArrows ? "x-tree-arrows" : this.lines ? "x-tree-lines" : "x-tree-no-lines")
        })
    },
    initEvents: function () {
        Ext.tree.TreePanel.superclass.initEvents.call(this);
        if (this.containerScroll) {
            Ext.dd.ScrollManager.register(this.body)
        }
        if ((this.enableDD || this.enableDrop) && !this.dropZone) {
            this.dropZone = new Ext.tree.TreeDropZone(this, this.dropConfig || {
                    ddGroup: this.ddGroup || "TreeDD",
                    appendOnly: this.ddAppendOnly === true
                })
        }
        if ((this.enableDD || this.enableDrag) && !this.dragZone) {
            this.dragZone = new Ext.tree.TreeDragZone(this, this.dragConfig || {
                    ddGroup: this.ddGroup || "TreeDD",
                    scroll: this.ddScroll
                })
        }
        this.getSelectionModel().init(this)
    },
    afterRender: function () {
        Ext.tree.TreePanel.superclass.afterRender.call(this);
        this.root.render();
        if (!this.rootVisible) {
            this.root.renderChildren()
        }
    },
    onDestroy: function () {
        if (this.rendered) {
            this.body.removeAllListeners();
            Ext.dd.ScrollManager.unregister(this.body);
            if (this.dropZone) {
                this.dropZone.unreg()
            }
            if (this.dragZone) {
                this.dragZone.unreg()
            }
        }
        this.root.destroy();
        this.nodeHash = null;
        Ext.tree.TreePanel.superclass.onDestroy.call(this)
    }
});
Ext.tree.TreePanel.nodeTypes = {};
Ext.reg("treepanel", Ext.tree.TreePanel);


Ext.tree.TreeEventModel = function (A) {
    this.tree = A;
    this.tree.on("render", this.initEvents, this)
};
Ext.tree.TreeEventModel.prototype = {
    initEvents: function () {
        var A = this.tree.getTreeEl();
        A.on("click", this.delegateClick, this);
        if (this.tree.trackMouseOver !== false) {
            A.on("mouseover", this.delegateOver, this);
            A.on("mouseout", this.delegateOut, this)
        }
        A.on("dblclick", this.delegateDblClick, this);
        A.on("contextmenu", this.delegateContextMenu, this)
    }, getNode: function (B) {
        var A;
        if (A = B.getTarget(".x-tree-node-el", 10)) {
            var C = Ext.fly(A, "_treeEvents").getAttributeNS("ext", "tree-node-id");
            if (C) {
                return this.tree.getNodeById(C)
            }
        }
        return null
    }, getNodeTarget: function (B) {
        var A = B.getTarget(".x-tree-node-icon", 1);
        if (!A) {
            A = B.getTarget(".x-tree-node-el", 6)
        }
        return A
    }, delegateOut: function (B, A) {
        if (!this.beforeEvent(B)) {
            return
        }
        if (B.getTarget(".x-tree-ec-icon", 1)) {
            var C = this.getNode(B);
            this.onIconOut(B, C);
            if (C == this.lastEcOver) {
                delete this.lastEcOver
            }
        }
        if ((A = this.getNodeTarget(B)) && !B.within(A, true)) {
            this.onNodeOut(B, this.getNode(B))
        }
    }, delegateOver: function (B, A) {
        if (!this.beforeEvent(B)) {
            return
        }
        if (this.lastEcOver) {
            this.onIconOut(B, this.lastEcOver);
            delete this.lastEcOver
        }
        if (B.getTarget(".x-tree-ec-icon", 1)) {
            this.lastEcOver = this.getNode(B);
            this.onIconOver(B, this.lastEcOver)
        }
        if (A = this.getNodeTarget(B)) {
            this.onNodeOver(B, this.getNode(B))
        }
    }, delegateClick: function (B, A) {
        if (!this.beforeEvent(B)) {
            return
        }
        if (B.getTarget("input[type=checkbox]", 1)) {
            this.onCheckboxClick(B, this.getNode(B))
        } else {
            if (B.getTarget(".x-tree-ec-icon", 1)) {
                this.onIconClick(B, this.getNode(B))
            } else {
                if (this.getNodeTarget(B)) {
                    this.onNodeClick(B, this.getNode(B))
                }
            }
        }
    }, delegateDblClick: function (B, A) {
        if (this.beforeEvent(B) && this.getNodeTarget(B)) {
            this.onNodeDblClick(B, this.getNode(B))
        }
    }, delegateContextMenu: function (B, A) {
        if (this.beforeEvent(B) && this.getNodeTarget(B)) {
            this.onNodeContextMenu(B, this.getNode(B))
        }
    }, onNodeClick: function (B, A) {
        A.ui.onClick(B)
    }, onNodeOver: function (B, A) {
        A.ui.onOver(B)
    }, onNodeOut: function (B, A) {
        A.ui.onOut(B)
    }, onIconOver: function (B, A) {
        A.ui.addClass("x-tree-ec-over")
    }, onIconOut: function (B, A) {
        A.ui.removeClass("x-tree-ec-over")
    }, onIconClick: function (B, A) {
        A.ui.ecClick(B)
    }, onCheckboxClick: function (B, A) {
        A.ui.onCheckChange(B)
    }, onNodeDblClick: function (B, A) {
        A.ui.onDblClick(B)
    }, onNodeContextMenu: function (B, A) {
        A.ui.onContextMenu(B)
    }, beforeEvent: function (A) {
        if (this.disabled) {
            A.stopEvent();
            return false
        }
        return true
    }, disable: function () {
        this.disabled = true
    }, enable: function () {
        this.disabled = false
    }
};


Ext.tree.DefaultSelectionModel = function (A) {
    this.selNode = null;
    this.addEvents("selectionchange", "beforeselect");
    Ext.apply(this, A);
    Ext.tree.DefaultSelectionModel.superclass.constructor.call(this)
};
Ext.extend(Ext.tree.DefaultSelectionModel, Ext.util.Observable, {
    init: function (A) {
        this.tree = A;
        A.getTreeEl().on("keydown", this.onKeyDown, this);
        A.on("click", this.onNodeClick, this)
    }, onNodeClick: function (A, B) {
        this.select(A)
    }, select: function (B) {
        var A = this.selNode;
        if (A != B && this.fireEvent("beforeselect", this, B, A) !== false) {
            if (A) {
                A.ui.onSelectedChange(false)
            }
            this.selNode = B;
            B.ui.onSelectedChange(true);
            this.fireEvent("selectionchange", this, B, A)
        }
        return B
    }, unselect: function (A) {
        if (this.selNode == A) {
            this.clearSelections()
        }
    }, clearSelections: function () {
        var A = this.selNode;
        if (A) {
            A.ui.onSelectedChange(false);
            this.selNode = null;
            this.fireEvent("selectionchange", this, null)
        }
        return A
    }, getSelectedNode: function () {
        return this.selNode
    }, isSelected: function (A) {
        return this.selNode == A
    }, selectPrevious: function () {
        var A = this.selNode || this.lastSelNode;
        if (!A) {
            return null
        }
        var C = A.previousSibling;
        if (C) {
            if (!C.isExpanded() || C.childNodes.length < 1) {
                return this.select(C)
            } else {
                var B = C.lastChild;
                while (B && B.isExpanded() && B.childNodes.length > 0) {
                    B = B.lastChild
                }
                return this.select(B)
            }
        } else {
            if (A.parentNode && (this.tree.rootVisible || !A.parentNode.isRoot)) {
                return this.select(A.parentNode)
            }
        }
        return null
    }, selectNext: function () {
        var B = this.selNode || this.lastSelNode;
        if (!B) {
            return null
        }
        if (B.firstChild && B.isExpanded()) {
            return this.select(B.firstChild)
        } else {
            if (B.nextSibling) {
                return this.select(B.nextSibling)
            } else {
                if (B.parentNode) {
                    var A = null;
                    B.parentNode.bubble(function () {
                        if (this.nextSibling) {
                            A = this.getOwnerTree().selModel.select(this.nextSibling);
                            return false
                        }
                    });
                    return A
                }
            }
        }
        return null
    }, onKeyDown: function (C) {
        var B = this.selNode || this.lastSelNode;
        var D = this;
        if (!B) {
            return
        }
        var A = C.getKey();
        switch (A) {
            case C.DOWN:
                C.stopEvent();
                this.selectNext();
                break;
            case C.UP:
                C.stopEvent();
                this.selectPrevious();
                break;
            case C.RIGHT:
                C.preventDefault();
                if (B.hasChildNodes()) {
                    if (!B.isExpanded()) {
                        B.expand()
                    } else {
                        if (B.firstChild) {
                            this.select(B.firstChild, C)
                        }
                    }
                }
                break;
            case C.LEFT:
                C.preventDefault();
                if (B.hasChildNodes() && B.isExpanded()) {
                    B.collapse()
                } else {
                    if (B.parentNode && (this.tree.rootVisible || B.parentNode != this.tree.getRootNode())) {
                        this.select(B.parentNode, C)
                    }
                }
                break
        }
    }
});
Ext.tree.MultiSelectionModel = function (A) {
    this.selNodes = [];
    this.selMap = {};
    this.addEvents("selectionchange");
    Ext.apply(this, A);
    Ext.tree.MultiSelectionModel.superclass.constructor.call(this)
};
Ext.extend(Ext.tree.MultiSelectionModel, Ext.util.Observable, {
    init: function (A) {
        this.tree = A;
        A.getTreeEl().on("keydown", this.onKeyDown, this);
        A.on("click", this.onNodeClick, this)
    },
    onNodeClick: function (A, B) {
        this.select(A, B, B.ctrlKey)
    },
    select: function (A, C, B) {
        if (B !== true) {
            this.clearSelections(true)
        }
        if (this.isSelected(A)) {
            this.lastSelNode = A;
            return A
        }
        this.selNodes.push(A);
        this.selMap[A.id] = A;
        this.lastSelNode = A;
        A.ui.onSelectedChange(true);
        this.fireEvent("selectionchange", this, this.selNodes);
        return A
    },
    unselect: function (B) {
        if (this.selMap[B.id]) {
            B.ui.onSelectedChange(false);
            var C = this.selNodes;
            var A = C.indexOf(B);
            if (A != -1) {
                this.selNodes.splice(A, 1)
            }
            delete this.selMap[B.id];
            this.fireEvent("selectionchange", this, this.selNodes)
        }
    },
    clearSelections: function (B) {
        var D = this.selNodes;
        if (D.length > 0) {
            for (var C = 0, A = D.length; C < A; C++) {
                D[C].ui.onSelectedChange(false)
            }
            this.selNodes = [];
            this.selMap = {};
            if (B !== true) {
                this.fireEvent("selectionchange", this, this.selNodes)
            }
        }
    },
    isSelected: function (A) {
        return this.selMap[A.id] ? true : false
    },
    getSelectedNodes: function () {
        return this.selNodes
    },
    onKeyDown: Ext.tree.DefaultSelectionModel.prototype.onKeyDown,
    selectNext: Ext.tree.DefaultSelectionModel.prototype.selectNext,
    selectPrevious: Ext.tree.DefaultSelectionModel.prototype.selectPrevious
});


Ext.tree.TreeNode = function (A) {
    A = A || {};
    if (typeof A == "string") {
        A = {text: A}
    }
    this.childrenRendered = false;
    this.rendered = false;
    Ext.tree.TreeNode.superclass.constructor.call(this, A);
    this.expanded = A.expanded === true;
    this.isTarget = A.isTarget !== false;
    this.draggable = A.draggable !== false && A.allowDrag !== false;
    this.allowChildren = A.allowChildren !== false && A.allowDrop !== false;
    this.text = A.text;
    this.disabled = A.disabled === true;
    this.addEvents("textchange", "beforeexpand", "beforecollapse", "expand", "disabledchange", "collapse", "beforeclick", "click", "checkchange", "dblclick", "contextmenu", "beforechildrenrendered");
    var B = this.attributes.uiProvider || this.defaultUI || Ext.tree.TreeNodeUI;
    this.ui = new B(this)
};
Ext.extend(Ext.tree.TreeNode, Ext.data.Node, {
    preventHScroll: true, isExpanded: function () {
        return this.expanded
    }, getUI: function () {
        return this.ui
    }, getLoader: function () {
        var A;
        return this.loader || ((A = this.getOwnerTree()) && A.loader ? A.loader : new Ext.tree.TreeLoader())
    }, setFirstChild: function (A) {
        var B = this.firstChild;
        Ext.tree.TreeNode.superclass.setFirstChild.call(this, A);
        if (this.childrenRendered && B && A != B) {
            B.renderIndent(true, true)
        }
        if (this.rendered) {
            this.renderIndent(true, true)
        }
    }, setLastChild: function (B) {
        var A = this.lastChild;
        Ext.tree.TreeNode.superclass.setLastChild.call(this, B);
        if (this.childrenRendered && A && B != A) {
            A.renderIndent(true, true)
        }
        if (this.rendered) {
            this.renderIndent(true, true)
        }
    }, appendChild: function (B) {
        if (!B.render && !Ext.isArray(B)) {
            B = this.getLoader().createNode(B)
        }
        var A = Ext.tree.TreeNode.superclass.appendChild.call(this, B);
        if (A && this.childrenRendered) {
            A.render()
        }
        this.ui.updateExpandIcon();
        return A
    }, removeChild: function (A) {
        this.ownerTree.getSelectionModel().unselect(A);
        Ext.tree.TreeNode.superclass.removeChild.apply(this, arguments);
        if (this.childrenRendered) {
            A.ui.remove()
        }
        if (this.childNodes.length < 1) {
            this.collapse(false, false)
        } else {
            this.ui.updateExpandIcon()
        }
        if (!this.firstChild && !this.isHiddenRoot()) {
            this.childrenRendered = false
        }
        return A
    }, insertBefore: function (C, A) {
        if (!C.render) {
            C = this.getLoader().createNode(C)
        }
        var B = Ext.tree.TreeNode.superclass.insertBefore.apply(this, arguments);
        if (B && A && this.childrenRendered) {
            C.render()
        }
        this.ui.updateExpandIcon();
        return B
    }, setText: function (B) {
        var A = this.text;
        this.text = B;
        this.attributes.text = B;
        if (this.rendered) {
            this.ui.onTextChange(this, B, A)
        }
        this.fireEvent("textchange", this, B, A)
    }, select: function () {
        this.getOwnerTree().getSelectionModel().select(this)
    }, unselect: function () {
        this.getOwnerTree().getSelectionModel().unselect(this)
    }, isSelected: function () {
        return this.getOwnerTree().getSelectionModel().isSelected(this)
    }, expand: function (A, B, C) {
        if (!this.expanded) {
            if (this.fireEvent("beforeexpand", this, A, B) === false) {
                return
            }
            if (!this.childrenRendered) {
                this.renderChildren()
            }
            this.expanded = true;
            if (!this.isHiddenRoot() && (this.getOwnerTree().animate && B !== false) || B) {
                this.ui.animExpand(function () {
                    this.fireEvent("expand", this);
                    if (typeof C == "function") {
                        C(this)
                    }
                    if (A === true) {
                        this.expandChildNodes(true)
                    }
                }.createDelegate(this));
                return
            } else {
                this.ui.expand();
                this.fireEvent("expand", this);
                if (typeof C == "function") {
                    C(this)
                }
            }
        } else {
            if (typeof C == "function") {
                C(this)
            }
        }
        if (A === true) {
            this.expandChildNodes(true)
        }
    }, isHiddenRoot: function () {
        return this.isRoot && !this.getOwnerTree().rootVisible
    }, collapse: function (B, E) {
        if (this.expanded && !this.isHiddenRoot()) {
            if (this.fireEvent("beforecollapse", this, B, E) === false) {
                return
            }
            this.expanded = false;
            if ((this.getOwnerTree().animate && E !== false) || E) {
                this.ui.animCollapse(function () {
                    this.fireEvent("collapse", this);
                    if (B === true) {
                        this.collapseChildNodes(true)
                    }
                }.createDelegate(this));
                return
            } else {
                this.ui.collapse();
                this.fireEvent("collapse", this)
            }
        }
        if (B === true) {
            var D = this.childNodes;
            for (var C = 0, A = D.length; C < A; C++) {
                D[C].collapse(true, false)
            }
        }
    }, delayedExpand: function (A) {
        if (!this.expandProcId) {
            this.expandProcId = this.expand.defer(A, this)
        }
    }, cancelExpand: function () {
        if (this.expandProcId) {
            clearTimeout(this.expandProcId)
        }
        this.expandProcId = false
    }, toggle: function () {
        if (this.expanded) {
            this.collapse()
        } else {
            this.expand()
        }
    }, ensureVisible: function (B) {
        var A = this.getOwnerTree();
        A.expandPath(this.parentNode.getPath(), false, function () {
            var C = A.getNodeById(this.id);
            A.getTreeEl().scrollChildIntoView(C.ui.anchor);
            Ext.callback(B)
        }.createDelegate(this))
    }, expandChildNodes: function (B) {
        var D = this.childNodes;
        for (var C = 0, A = D.length; C < A; C++) {
            D[C].expand(B)
        }
    }, collapseChildNodes: function (B) {
        var D = this.childNodes;
        for (var C = 0, A = D.length; C < A; C++) {
            D[C].collapse(B)
        }
    }, disable: function () {
        this.disabled = true;
        this.unselect();
        if (this.rendered && this.ui.onDisableChange) {
            this.ui.onDisableChange(this, true)
        }
        this.fireEvent("disabledchange", this, true)
    }, enable: function () {
        this.disabled = false;
        if (this.rendered && this.ui.onDisableChange) {
            this.ui.onDisableChange(this, false)
        }
        this.fireEvent("disabledchange", this, false)
    }, renderChildren: function (B) {
        if (B !== false) {
            this.fireEvent("beforechildrenrendered", this)
        }
        var D = this.childNodes;
        for (var C = 0, A = D.length; C < A; C++) {
            D[C].render(true)
        }
        this.childrenRendered = true
    }, sort: function (E, D) {
        Ext.tree.TreeNode.superclass.sort.apply(this, arguments);
        if (this.childrenRendered) {
            var C = this.childNodes;
            for (var B = 0, A = C.length; B < A; B++) {
                C[B].render(true)
            }
        }
    }, render: function (A) {
        this.ui.render(A);
        if (!this.rendered) {
            this.getOwnerTree().registerNode(this);
            this.rendered = true;
            if (this.expanded) {
                this.expanded = false;
                this.expand(false, false)
            }
        }
    }, renderIndent: function (B, E) {
        if (E) {
            this.ui.childIndent = null
        }
        this.ui.renderIndent();
        if (B === true && this.childrenRendered) {
            var D = this.childNodes;
            for (var C = 0, A = D.length; C < A; C++) {
                D[C].renderIndent(true, E)
            }
        }
    }, beginUpdate: function () {
        this.childrenRendered = false
    }, endUpdate: function () {
        if (this.expanded && this.rendered) {
            this.renderChildren()
        }
    }, destroy: function () {
        if (this.childNodes) {
            for (var B = 0, A = this.childNodes.length; B < A; B++) {
                this.childNodes[B].destroy()
            }
            this.childNodes = null
        }
        if (this.ui.destroy) {
            this.ui.destroy()
        }
    }
});
Ext.tree.TreePanel.nodeTypes.node = Ext.tree.TreeNode;


Ext.tree.AsyncTreeNode = function (A) {
    this.loaded = A && A.loaded === true;
    this.loading = false;
    Ext.tree.AsyncTreeNode.superclass.constructor.apply(this, arguments);
    this.addEvents("beforeload", "load")
};
Ext.extend(Ext.tree.AsyncTreeNode, Ext.tree.TreeNode, {
    expand: function (B, D, F) {
        if (this.loading) {
            var E;
            var C = function () {
                if (!this.loading) {
                    clearInterval(E);
                    this.expand(B, D, F)
                }
            }.createDelegate(this);
            E = setInterval(C, 200);
            return
        }
        if (!this.loaded) {
            if (this.fireEvent("beforeload", this) === false) {
                return
            }
            this.loading = true;
            this.ui.beforeLoad(this);
            var A = this.loader || this.attributes.loader || this.getOwnerTree().getLoader();
            if (A) {
                A.load(this, this.loadComplete.createDelegate(this, [B, D, F]));
                return
            }
        }
        Ext.tree.AsyncTreeNode.superclass.expand.call(this, B, D, F)
    }, isLoading: function () {
        return this.loading
    }, loadComplete: function (A, B, C) {
        this.loading = false;
        this.loaded = true;
        this.ui.afterLoad(this);
        this.fireEvent("load", this);
        this.expand(A, B, C)
    }, isLoaded: function () {
        return this.loaded
    }, hasChildNodes: function () {
        if (!this.isLeaf() && !this.loaded) {
            return true
        } else {
            return Ext.tree.AsyncTreeNode.superclass.hasChildNodes.call(this)
        }
    }, reload: function (A) {
        this.collapse(false, false);
        while (this.firstChild) {
            this.removeChild(this.firstChild)
        }
        this.childrenRendered = false;
        this.loaded = false;
        if (this.isHiddenRoot()) {
            this.expanded = false
        }
        this.expand(false, false, A)
    }
});
Ext.tree.TreePanel.nodeTypes.async = Ext.tree.AsyncTreeNode;


Ext.tree.TreeNodeUI = function (A) {
    this.node = A;
    this.rendered = false;
    this.animating = false;
    this.wasLeaf = true;
    this.ecc = "x-tree-ec-icon x-tree-elbow";
    this.emptyIcon = Ext.BLANK_IMAGE_URL
};
Ext.tree.TreeNodeUI.prototype = {
    removeChild: function (A) {
        if (this.rendered) {
            this.ctNode.removeChild(A.ui.getEl())
        }
    }, beforeLoad: function () {
        this.addClass("x-tree-node-loading")
    }, afterLoad: function () {
        this.removeClass("x-tree-node-loading")
    }, onTextChange: function (B, C, A) {
        if (this.rendered) {
            this.textNode.innerHTML = C
        }
    }, onDisableChange: function (A, B) {
        this.disabled = B;
        if (this.checkbox) {
            this.checkbox.disabled = B
        }
        if (B) {
            this.addClass("x-tree-node-disabled")
        } else {
            this.removeClass("x-tree-node-disabled")
        }
    }, onSelectedChange: function (A) {
        if (A) {
            this.focus();
            this.addClass("x-tree-selected")
        } else {
            this.removeClass("x-tree-selected")
        }
    }, onMove: function (A, G, E, F, D, B) {
        this.childIndent = null;
        if (this.rendered) {
            var H = F.ui.getContainer();
            if (!H) {
                this.holder = document.createElement("div");
                this.holder.appendChild(this.wrap);
                return
            }
            var C = B ? B.ui.getEl() : null;
            if (C) {
                H.insertBefore(this.wrap, C)
            } else {
                H.appendChild(this.wrap)
            }
            this.node.renderIndent(true)
        }
    }, addClass: function (A) {
        if (this.elNode) {
            Ext.fly(this.elNode).addClass(A)
        }
    }, removeClass: function (A) {
        if (this.elNode) {
            Ext.fly(this.elNode).removeClass(A)
        }
    }, remove: function () {
        if (this.rendered) {
            this.holder = document.createElement("div");
            this.holder.appendChild(this.wrap)
        }
    }, fireEvent: function () {
        return this.node.fireEvent.apply(this.node, arguments)
    }, initEvents: function () {
        this.node.on("move", this.onMove, this);
        if (this.node.disabled) {
            this.addClass("x-tree-node-disabled");
            if (this.checkbox) {
                this.checkbox.disabled = true
            }
        }
        if (this.node.hidden) {
            this.hide()
        }
        var B = this.node.getOwnerTree();
        var A = B.enableDD || B.enableDrag || B.enableDrop;
        if (A && (!this.node.isRoot || B.rootVisible)) {
            Ext.dd.Registry.register(this.elNode, {node: this.node, handles: this.getDDHandles(), isHandle: false})
        }
    }, getDDHandles: function () {
        return [this.iconNode, this.textNode, this.elNode]
    }, hide: function () {
        this.node.hidden = true;
        if (this.wrap) {
            this.wrap.style.display = "none"
        }
    }, show: function () {
        this.node.hidden = false;
        if (this.wrap) {
            this.wrap.style.display = ""
        }
    }, onContextMenu: function (A) {
        if (this.node.hasListener("contextmenu") || this.node.getOwnerTree().hasListener("contextmenu")) {
            A.preventDefault();
            this.focus();
            this.fireEvent("contextmenu", this.node, A)
        }
    }, onClick: function (B) {
        if (this.dropping) {
            B.stopEvent();
            return
        }
        if (this.fireEvent("beforeclick", this.node, B) !== false) {
            var A = B.getTarget("a");
            if (!this.disabled && this.node.attributes.href && A) {
                this.fireEvent("click", this.node, B);
                return
            } else {
                if (A && B.ctrlKey) {
                    B.stopEvent()
                }
            }
            B.preventDefault();
            if (this.disabled) {
                return
            }
            if (this.node.attributes.singleClickExpand && !this.animating && this.node.isExpandable()) {
                this.node.toggle()
            }
            this.fireEvent("click", this.node, B)
        } else {
            B.stopEvent()
        }
    }, onDblClick: function (A) {
        A.preventDefault();
        if (this.disabled) {
            return
        }
        if (this.checkbox) {
            this.toggleCheck()
        }
        if (!this.animating && this.node.isExpandable()) {
            this.node.toggle()
        }
        this.fireEvent("dblclick", this.node, A)
    }, onOver: function (A) {
        this.addClass("x-tree-node-over")
    }, onOut: function (A) {
        this.removeClass("x-tree-node-over")
    }, onCheckChange: function () {
        var A = this.checkbox.checked;
        this.checkbox.defaultChecked = A;
        this.node.attributes.checked = A;
        this.fireEvent("checkchange", this.node, A)
    }, ecClick: function (A) {
        if (!this.animating && this.node.isExpandable()) {
            this.node.toggle()
        }
    }, startDrop: function () {
        this.dropping = true
    }, endDrop: function () {
        setTimeout(function () {
            this.dropping = false
        }.createDelegate(this), 50)
    }, expand: function () {
        this.updateExpandIcon();
        this.ctNode.style.display = ""
    }, focus: function () {
        if (!this.node.preventHScroll) {
            try {
                this.anchor.focus()
            } catch (C) {
            }
        } else {
            if (!Ext.isIE) {
                try {
                    var B = this.node.getOwnerTree().getTreeEl().dom;
                    var A = B.scrollLeft;
                    this.anchor.focus();
                    B.scrollLeft = A
                } catch (C) {
                }
            }
        }
    }, toggleCheck: function (B) {
        var A = this.checkbox;
        if (A) {
            A.checked = (B === undefined ? !A.checked : B);
            this.onCheckChange()
        }
    }, blur: function () {
        try {
            this.anchor.blur()
        } catch (A) {
        }
    }, animExpand: function (B) {
        var A = Ext.get(this.ctNode);
        A.stopFx();
        if (!this.node.isExpandable()) {
            this.updateExpandIcon();
            this.ctNode.style.display = "";
            Ext.callback(B);
            return
        }
        this.animating = true;
        this.updateExpandIcon();
        A.slideIn("t", {
            callback: function () {
                this.animating = false;
                Ext.callback(B)
            }, scope: this, duration: this.node.ownerTree.duration || 0.25
        })
    }, highlight: function () {
        var A = this.node.getOwnerTree();
        Ext.fly(this.wrap).highlight(A.hlColor || "C3DAF9", {endColor: A.hlBaseColor})
    }, collapse: function () {
        this.updateExpandIcon();
        this.ctNode.style.display = "none"
    }, animCollapse: function (B) {
        var A = Ext.get(this.ctNode);
        A.enableDisplayMode("block");
        A.stopFx();
        this.animating = true;
        this.updateExpandIcon();
        A.slideOut("t", {
            callback: function () {
                this.animating = false;
                Ext.callback(B)
            }, scope: this, duration: this.node.ownerTree.duration || 0.25
        })
    }, getContainer: function () {
        return this.ctNode
    }, getEl: function () {
        return this.wrap
    }, appendDDGhost: function (A) {
        A.appendChild(this.elNode.cloneNode(true))
    }, getDDRepairXY: function () {
        return Ext.lib.Dom.getXY(this.iconNode)
    }, onRender: function () {
        this.render()
    }, render: function (B) {
        var D = this.node, A = D.attributes;
        var C = D.parentNode ? D.parentNode.ui.getContainer() : D.ownerTree.innerCt.dom;
        if (!this.rendered) {
            this.rendered = true;
            this.renderElements(D, A, C, B);
            if (A.qtip) {
                if (this.textNode.setAttributeNS) {
                    this.textNode.setAttributeNS("ext", "qtip", A.qtip);
                    if (A.qtipTitle) {
                        this.textNode.setAttributeNS("ext", "qtitle", A.qtipTitle)
                    }
                } else {
                    this.textNode.setAttribute("ext:qtip", A.qtip);
                    if (A.qtipTitle) {
                        this.textNode.setAttribute("ext:qtitle", A.qtipTitle)
                    }
                }
            } else {
                if (A.qtipCfg) {
                    A.qtipCfg.target = Ext.id(this.textNode);
                    Ext.QuickTips.register(A.qtipCfg)
                }
            }
            this.initEvents();
            if (!this.node.expanded) {
                this.updateExpandIcon(true)
            }
        } else {
            if (B === true) {
                C.appendChild(this.wrap)
            }
        }
    }, renderElements: function (D, I, H, J) {
        this.indentMarkup = D.parentNode ? D.parentNode.ui.getChildIndent() : "";
        var E = typeof I.checked == "boolean";
        var B = I.href ? I.href : Ext.isGecko ? "" : "#";
        var C = ["<li class=\"x-tree-node\"><div ext:tree-node-id=\"", D.id, "\" class=\"x-tree-node-el x-tree-node-leaf x-unselectable ", I.cls, "\" unselectable=\"on\">", "<span class=\"x-tree-node-indent\">", this.indentMarkup, "</span>", "<img src=\"", this.emptyIcon, "\" class=\"x-tree-ec-icon x-tree-elbow\" />", "<img src=\"", I.icon || this.emptyIcon, "\" class=\"x-tree-node-icon", (I.icon ? " x-tree-node-inline-icon" : ""), (I.iconCls ? " " + I.iconCls : ""), "\" unselectable=\"on\" />", E ? ("<input class=\"x-tree-node-cb\" type=\"checkbox\" " + (I.checked ? "checked=\"checked\" />" : "/>")) : "", "<a hidefocus=\"on\" class=\"x-tree-node-anchor\" href=\"", B, "\" tabIndex=\"1\" ", I.hrefTarget ? " target=\"" + I.hrefTarget + "\"" : "", "><span unselectable=\"on\">", D.text, "</span></a></div>", "<ul class=\"x-tree-node-ct\" style=\"display:none;\"></ul>", "</li>"].join("");
        var A;
        if (J !== true && D.nextSibling && (A = D.nextSibling.ui.getEl())) {
            this.wrap = Ext.DomHelper.insertHtml("beforeBegin", A, C)
        } else {
            this.wrap = Ext.DomHelper.insertHtml("beforeEnd", H, C)
        }
        this.elNode = this.wrap.childNodes[0];
        this.ctNode = this.wrap.childNodes[1];
        var G = this.elNode.childNodes;
        this.indentNode = G[0];
        this.ecNode = G[1];
        this.iconNode = G[2];
        var F = 3;
        if (E) {
            this.checkbox = G[3];
            this.checkbox.defaultChecked = this.checkbox.checked;
            F++
        }
        this.anchor = G[F];
        this.textNode = G[F].firstChild
    }, getAnchor: function () {
        return this.anchor
    }, getTextEl: function () {
        return this.textNode
    }, getIconEl: function () {
        return this.iconNode
    }, isChecked: function () {
        return this.checkbox ? this.checkbox.checked : false
    }, updateExpandIcon: function () {
        if (this.rendered) {
            var E = this.node, D, C;
            var A = E.isLast() ? "x-tree-elbow-end" : "x-tree-elbow";
            if (E.isExpandable()) {
                if (E.expanded) {
                    A += "-minus";
                    D = "x-tree-node-collapsed";
                    C = "x-tree-node-expanded"
                } else {
                    A += "-plus";
                    D = "x-tree-node-expanded";
                    C = "x-tree-node-collapsed"
                }
                if (this.wasLeaf) {
                    this.removeClass("x-tree-node-leaf");
                    this.wasLeaf = false
                }
                if (this.c1 != D || this.c2 != C) {
                    Ext.fly(this.elNode).replaceClass(D, C);
                    this.c1 = D;
                    this.c2 = C
                }
            } else {
                if (!this.wasLeaf) {
                    Ext.fly(this.elNode).replaceClass("x-tree-node-expanded", "x-tree-node-leaf");
                    delete this.c1;
                    delete this.c2;
                    this.wasLeaf = true
                }
            }
            var B = "x-tree-ec-icon " + A;
            if (this.ecc != B) {
                this.ecNode.className = B;
                this.ecc = B
            }
        }
    }, getChildIndent: function () {
        if (!this.childIndent) {
            var A = [];
            var B = this.node;
            while (B) {
                if (!B.isRoot || (B.isRoot && B.ownerTree.rootVisible)) {
                    if (!B.isLast()) {
                        A.unshift("<img src=\"" + this.emptyIcon + "\" class=\"x-tree-elbow-line\" />")
                    } else {
                        A.unshift("<img src=\"" + this.emptyIcon + "\" class=\"x-tree-icon\" />")
                    }
                }
                B = B.parentNode
            }
            this.childIndent = A.join("")
        }
        return this.childIndent
    }, renderIndent: function () {
        if (this.rendered) {
            var A = "";
            var B = this.node.parentNode;
            if (B) {
                A = B.ui.getChildIndent()
            }
            if (this.indentMarkup != A) {
                this.indentNode.innerHTML = A;
                this.indentMarkup = A
            }
            this.updateExpandIcon()
        }
    }, destroy: function () {
        if (this.elNode) {
            Ext.dd.Registry.unregister(this.elNode.id)
        }
        delete this.elNode;
        delete this.ctNode;
        delete this.indentNode;
        delete this.ecNode;
        delete this.iconNode;
        delete this.checkbox;
        delete this.anchor;
        delete this.textNode;
        Ext.removeNode(this.ctNode)
    }
};
Ext.tree.RootTreeNodeUI = Ext.extend(Ext.tree.TreeNodeUI, {
    render: function () {
        if (!this.rendered) {
            var A = this.node.ownerTree.innerCt.dom;
            this.node.expanded = true;
            A.innerHTML = "<div class=\"x-tree-root-node\"></div>";
            this.wrap = this.ctNode = A.firstChild
        }
    }, collapse: Ext.emptyFn, expand: Ext.emptyFn
});


Ext.tree.TreeLoader = function (A) {
    this.baseParams = {};
    Ext.apply(this, A);
    this.addEvents("beforeload", "load", "loadexception");
    Ext.tree.TreeLoader.superclass.constructor.call(this)
};
Ext.extend(Ext.tree.TreeLoader, Ext.util.Observable, {
    uiProviders: {}, clearOnLoad: true, load: function (A, B) {
        if (this.clearOnLoad) {
            while (A.firstChild) {
                A.removeChild(A.firstChild)
            }
        }
        if (this.doPreload(A)) {
            if (typeof B == "function") {
                B()
            }
        } else {
            if (this.dataUrl || this.url) {
                this.requestData(A, B)
            }
        }
    }, doPreload: function (D) {
        if (D.attributes.children) {
            if (D.childNodes.length < 1) {
                var C = D.attributes.children;
                D.beginUpdate();
                for (var B = 0, A = C.length; B < A; B++) {
                    var E = D.appendChild(this.createNode(C[B]));
                    if (this.preloadChildren) {
                        this.doPreload(E)
                    }
                }
                D.endUpdate()
            }
            return true
        } else {
            return false
        }
    }, getParams: function (D) {
        var A = [], C = this.baseParams;
        for (var B in C) {
            if (typeof C[B] != "function") {
                A.push(encodeURIComponent(B), "=", encodeURIComponent(C[B]), "&")
            }
        }
        A.push("node=", encodeURIComponent(D.id));
        return A.join("")
    }, requestData: function (A, B) {
        if (this.fireEvent("beforeload", this, A, B) !== false) {
            this.transId = Ext.Ajax.request({
                method: this.requestMethod,
                url: this.dataUrl || this.url,
                success: this.handleResponse,
                failure: this.handleFailure,
                scope: this,
                argument: {callback: B, node: A},
                params: this.getParams(A)
            })
        } else {
            if (typeof B == "function") {
                B()
            }
        }
    }, isLoading: function () {
        return !!this.transId
    }, abort: function () {
        if (this.isLoading()) {
            Ext.Ajax.abort(this.transId)
        }
    }, createNode: function (attr) {
        if (this.baseAttrs) {
            Ext.applyIf(attr, this.baseAttrs)
        }
        if (this.applyLoader !== false) {
            attr.loader = this
        }
        if (typeof attr.uiProvider == "string") {
            attr.uiProvider = this.uiProviders[attr.uiProvider] || eval(attr.uiProvider)
        }
        if (attr.nodeType) {
            return new Ext.tree.TreePanel.nodeTypes[attr.nodeType](attr)
        } else {
            return attr.leaf ? new Ext.tree.TreeNode(attr) : new Ext.tree.AsyncTreeNode(attr)
        }
    }, processResponse: function (response, node, callback) {
        var json = response.responseText;
        try {
            var o = eval("(" + json + ")");
            node.beginUpdate();
            for (var i = 0, len = o.length; i < len; i++) {
                var n = this.createNode(o[i]);
                if (n) {
                    node.appendChild(n)
                }
            }
            node.endUpdate();
            if (typeof callback == "function") {
                callback(this, node)
            }
        } catch (e) {
            this.handleFailure(response)
        }
    }, handleResponse: function (B) {
        this.transId = false;
        var A = B.argument;
        this.processResponse(B, A.node, A.callback);
        this.fireEvent("load", this, A.node, B)
    }, handleFailure: function (B) {
        this.transId = false;
        var A = B.argument;
        this.fireEvent("loadexception", this, A.node, B);
        if (typeof A.callback == "function") {
            A.callback(this, A.node)
        }
    }
});


if (Ext.dd.DropZone) {
    Ext.tree.TreeDropZone = function (A, B) {
        this.allowParentInsert = false;
        this.allowContainerDrop = false;
        this.appendOnly = false;
        Ext.tree.TreeDropZone.superclass.constructor.call(this, A.innerCt, B);
        this.tree = A;
        this.dragOverData = {};
        this.lastInsertClass = "x-tree-no-status"
    };
    Ext.extend(Ext.tree.TreeDropZone, Ext.dd.DropZone, {
        ddGroup: "TreeDD", expandDelay: 1000, expandNode: function (A) {
            if (A.hasChildNodes() && !A.isExpanded()) {
                A.expand(false, null, this.triggerCacheRefresh.createDelegate(this))
            }
        }, queueExpand: function (A) {
            this.expandProcId = this.expandNode.defer(this.expandDelay, this, [A])
        }, cancelExpand: function () {
            if (this.expandProcId) {
                clearTimeout(this.expandProcId);
                this.expandProcId = false
            }
        }, isValidDropPoint: function (A, I, G, D, C) {
            if (!A || !C) {
                return false
            }
            var E = A.node;
            var F = C.node;
            if (!(E && E.isTarget && I)) {
                return false
            }
            if (I == "append" && E.allowChildren === false) {
                return false
            }
            if ((I == "above" || I == "below") && (E.parentNode && E.parentNode.allowChildren === false)) {
                return false
            }
            if (F && (E == F || F.contains(E))) {
                return false
            }
            var B = this.dragOverData;
            B.tree = this.tree;
            B.target = E;
            B.data = C;
            B.point = I;
            B.source = G;
            B.rawEvent = D;
            B.dropNode = F;
            B.cancel = false;
            var H = this.tree.fireEvent("nodedragover", B);
            return B.cancel === false && H !== false
        }, getDropPoint: function (E, D, I) {
            var J = D.node;
            if (J.isRoot) {
                return J.allowChildren !== false ? "append" : false
            }
            var B = D.ddel;
            var K = Ext.lib.Dom.getY(B), G = K + B.offsetHeight;
            var F = Ext.lib.Event.getPageY(E);
            var H = J.allowChildren === false || J.isLeaf();
            if (this.appendOnly || J.parentNode.allowChildren === false) {
                return H ? false : "append"
            }
            var C = false;
            if (!this.allowParentInsert) {
                C = J.hasChildNodes() && J.isExpanded()
            }
            var A = (G - K) / (H ? 2 : 3);
            if (F >= K && F < (K + A)) {
                return "above"
            } else {
                if (!C && (H || F >= G - A && F <= G)) {
                    return "below"
                } else {
                    return "append"
                }
            }
        }, onNodeEnter: function (D, A, C, B) {
            this.cancelExpand()
        }, onNodeOver: function (B, G, F, E) {
            var I = this.getDropPoint(F, B, G);
            var C = B.node;
            if (!this.expandProcId && I == "append" && C.hasChildNodes() && !B.node.isExpanded()) {
                this.queueExpand(C)
            } else {
                if (I != "append") {
                    this.cancelExpand()
                }
            }
            var D = this.dropNotAllowed;
            if (this.isValidDropPoint(B, I, G, F, E)) {
                if (I) {
                    var A = B.ddel;
                    var H;
                    if (I == "above") {
                        D = B.node.isFirst() ? "x-tree-drop-ok-above" : "x-tree-drop-ok-between";
                        H = "x-tree-drag-insert-above"
                    } else {
                        if (I == "below") {
                            D = B.node.isLast() ? "x-tree-drop-ok-below" : "x-tree-drop-ok-between";
                            H = "x-tree-drag-insert-below"
                        } else {
                            D = "x-tree-drop-ok-append";
                            H = "x-tree-drag-append"
                        }
                    }
                    if (this.lastInsertClass != H) {
                        Ext.fly(A).replaceClass(this.lastInsertClass, H);
                        this.lastInsertClass = H
                    }
                }
            }
            return D
        }, onNodeOut: function (D, A, C, B) {
            this.cancelExpand();
            this.removeDropIndicators(D)
        }, onNodeDrop: function (C, I, E, D) {
            var H = this.getDropPoint(E, C, I);
            var F = C.node;
            F.ui.startDrop();
            if (!this.isValidDropPoint(C, H, I, E, D)) {
                F.ui.endDrop();
                return false
            }
            var G = D.node || (I.getTreeNode ? I.getTreeNode(D, F, H, E) : null);
            var B = {
                tree: this.tree,
                target: F,
                data: D,
                point: H,
                source: I,
                rawEvent: E,
                dropNode: G,
                cancel: !G,
                dropStatus: false
            };
            var A = this.tree.fireEvent("beforenodedrop", B);
            if (A === false || B.cancel === true || !B.dropNode) {
                F.ui.endDrop();
                return B.dropStatus
            }
            F = B.target;
            if (H == "append" && !F.isExpanded()) {
                F.expand(false, null, function () {
                    this.completeDrop(B)
                }.createDelegate(this))
            } else {
                this.completeDrop(B)
            }
            return true
        }, completeDrop: function (G) {
            var D = G.dropNode, E = G.point, C = G.target;
            if (!Ext.isArray(D)) {
                D = [D]
            }
            var F;
            for (var B = 0, A = D.length; B < A; B++) {
                F = D[B];
                if (E == "above") {
                    C.parentNode.insertBefore(F, C)
                } else {
                    if (E == "below") {
                        C.parentNode.insertBefore(F, C.nextSibling)
                    } else {
                        C.appendChild(F)
                    }
                }
            }
            F.ui.focus();
            if (Ext.enableFx && this.tree.hlDrop) {
                F.ui.highlight()
            }
            C.ui.endDrop();
            this.tree.fireEvent("nodedrop", G)
        }, afterNodeMoved: function (A, C, E, D, B) {
            if (Ext.enableFx && this.tree.hlDrop) {
                B.ui.focus();
                B.ui.highlight()
            }
            this.tree.fireEvent("nodedrop", this.tree, D, C, A, E)
        }, getTree: function () {
            return this.tree
        }, removeDropIndicators: function (B) {
            if (B && B.ddel) {
                var A = B.ddel;
                Ext.fly(A).removeClass(["x-tree-drag-insert-above", "x-tree-drag-insert-below", "x-tree-drag-append"]);
                this.lastInsertClass = "_noclass"
            }
        }, beforeDragDrop: function (B, A, C) {
            this.cancelExpand();
            return true
        }, afterRepair: function (A) {
            if (A && Ext.enableFx) {
                A.node.ui.highlight()
            }
            this.hideProxy()
        }
    })
}
;


if (Ext.dd.DragZone) {
    Ext.tree.TreeDragZone = function (A, B) {
        Ext.tree.TreeDragZone.superclass.constructor.call(this, A.getTreeEl(), B);
        this.tree = A
    };
    Ext.extend(Ext.tree.TreeDragZone, Ext.dd.DragZone, {
        ddGroup: "TreeDD", onBeforeDrag: function (A, B) {
            var C = A.node;
            return C && C.draggable && !C.disabled
        }, onInitDrag: function (B) {
            var A = this.dragData;
            this.tree.getSelectionModel().select(A.node);
            this.tree.eventModel.disable();
            this.proxy.update("");
            A.node.ui.appendDDGhost(this.proxy.ghost.dom);
            this.tree.fireEvent("startdrag", this.tree, A.node, B)
        }, getRepairXY: function (B, A) {
            return A.node.ui.getDDRepairXY()
        }, onEndDrag: function (A, B) {
            this.tree.eventModel.enable.defer(100, this.tree.eventModel);
            this.tree.fireEvent("enddrag", this.tree, A.node, B)
        }, onValidDrop: function (A, B, C) {
            this.tree.fireEvent("dragdrop", this.tree, this.dragData.node, A, B);
            this.hideProxy()
        }, beforeInvalidDrop: function (A, C) {
            var B = this.tree.getSelectionModel();
            B.clearSelections();
            B.select(this.dragData.node)
        }, afterRepair: function () {
            if (Ext.enableFx && this.tree.hlDrop) {
                Ext.Element.fly(this.dragData.ddel).highlight(this.hlColor || "c3daf9")
            }
            this.dragging = false
        }
    })
}
;


Ext.tree.TreeEditor = function (A, C, B) {
    C = C || {};
    var D = C.events ? C : new Ext.form.TextField(C);
    Ext.tree.TreeEditor.superclass.constructor.call(this, D, B);
    this.tree = A;
    if (!A.rendered) {
        A.on("render", this.initEditor, this)
    } else {
        this.initEditor(A)
    }
};
Ext.extend(Ext.tree.TreeEditor, Ext.Editor, {
    alignment: "l-l",
    autoSize: false,
    hideEl: false,
    cls: "x-small-editor x-tree-editor",
    shim: false,
    shadow: "frame",
    maxWidth: 250,
    editDelay: 350,
    initEditor: function (A) {
        A.on("beforeclick", this.beforeNodeClick, this);
        A.on("dblclick", this.onNodeDblClick, this);
        this.on("complete", this.updateNode, this);
        this.on("beforestartedit", this.fitToTree, this);
        this.on("startedit", this.bindScroll, this, {delay: 10});
        this.on("specialkey", this.onSpecialKey, this)
    },
    fitToTree: function (B, C) {
        var E = this.tree.getTreeEl().dom, D = C.dom;
        if (E.scrollLeft > D.offsetLeft) {
            E.scrollLeft = D.offsetLeft
        }
        var A = Math.min(this.maxWidth, (E.clientWidth > 20 ? E.clientWidth : E.offsetWidth) - Math.max(0, D.offsetLeft - E.scrollLeft) - 5);
        this.setSize(A, "")
    },
    triggerEdit: function (A, B) {
        this.completeEdit();
        if (A.attributes.editable !== false) {
            this.editNode = A;
            if (this.tree.autoScroll) {
                A.ui.getEl().scrollIntoView(this.tree.body)
            }
            this.autoEditTimer = this.startEdit.defer(this.editDelay, this, [A.ui.textNode, A.text]);
            return false
        }
    },
    bindScroll: function () {
        this.tree.getTreeEl().on("scroll", this.cancelEdit, this)
    },
    beforeNodeClick: function (A, B) {
        clearTimeout(this.autoEditTimer);
        if (this.tree.getSelectionModel().isSelected(A)) {
            B.stopEvent();
            return this.triggerEdit(A)
        }
    },
    onNodeDblClick: function (A, B) {
        clearTimeout(this.autoEditTimer)
    },
    updateNode: function (A, B) {
        this.tree.getTreeEl().un("scroll", this.cancelEdit, this);
        this.editNode.setText(B)
    },
    onHide: function () {
        Ext.tree.TreeEditor.superclass.onHide.call(this);
        if (this.editNode) {
            this.editNode.ui.focus.defer(50, this.editNode.ui)
        }
    },
    onSpecialKey: function (C, B) {
        var A = B.getKey();
        if (A == B.ESC) {
            B.stopEvent();
            this.cancelEdit()
        } else {
            if (A == B.ENTER && !B.hasModifier()) {
                B.stopEvent();
                this.completeEdit()
            }
        }
    }
});


Ext.form.Field = Ext.extend(Ext.BoxComponent, {
    invalidClass: "x-form-invalid",
    invalidText: "The value in this field is invalid",
    focusClass: "x-form-focus",
    validationEvent: "keyup",
    validateOnBlur: true,
    validationDelay: 250,
    defaultAutoCreate: {tag: "input", type: "text", size: "20", autocomplete: "off"},
    fieldClass: "x-form-field",
    msgTarget: "qtip",
    msgFx: "normal",
    readOnly: false,
    disabled: false,
    isFormField: true,
    hasFocus: false,
    initComponent: function () {
        Ext.form.Field.superclass.initComponent.call(this);
        this.addEvents("focus", "blur", "specialkey", "change", "invalid", "valid")
    },
    getName: function () {
        return this.rendered && this.el.dom.name ? this.el.dom.name : (this.hiddenName || "")
    },
    onRender: function (C, A) {
        Ext.form.Field.superclass.onRender.call(this, C, A);
        if (!this.el) {
            var B = this.getAutoCreate();
            if (!B.name) {
                B.name = this.name || this.id
            }
            if (this.inputType) {
                B.type = this.inputType
            }
            this.el = C.createChild(B, A)
        }
        var D = this.el.dom.type;
        if (D) {
            if (D == "password") {
                D = "text"
            }
            this.el.addClass("x-form-" + D)
        }
        if (this.readOnly) {
            this.el.dom.readOnly = true
        }
        if (this.tabIndex !== undefined) {
            this.el.dom.setAttribute("tabIndex", this.tabIndex)
        }
        this.el.addClass([this.fieldClass, this.cls])
    },
    initValue: function () {
        if (this.value !== undefined) {
            this.setValue(this.value)
        } else {
            if (this.el.dom.value.length > 0 && this.el.dom.value != this.emptyText) {
                this.setValue(this.el.dom.value)
            }
        }
        this.originalValue = this.getValue()
    },
    isDirty: function () {
        if (this.disabled) {
            return false
        }
        return String(this.getValue()) !== String(this.originalValue)
    },
    afterRender: function () {
        Ext.form.Field.superclass.afterRender.call(this);
        this.initEvents();
        this.initValue()
    },
    fireKey: function (A) {
        if (A.isSpecialKey()) {
            this.fireEvent("specialkey", this, A)
        }
    },
    reset: function () {
        this.setValue(this.originalValue);
        this.clearInvalid()
    },
    initEvents: function () {
        this.el.on(Ext.isIE || Ext.isSafari3 ? "keydown" : "keypress", this.fireKey, this);
        this.el.on("focus", this.onFocus, this);
        var A = this.inEditor && Ext.isWindows && Ext.isGecko ? {buffer: 10} : null;
        this.el.on("blur", this.onBlur, this, A);
        this.originalValue = this.getValue()
    },
    onFocus: function () {
        if (!Ext.isOpera && this.focusClass) {
            this.el.addClass(this.focusClass)
        }
        if (!this.hasFocus) {
            this.hasFocus = true;
            this.startValue = this.getValue();
            this.fireEvent("focus", this)
        }
    },
    beforeBlur: Ext.emptyFn,
    onBlur: function () {
        this.beforeBlur();
        if (!Ext.isOpera && this.focusClass) {
            this.el.removeClass(this.focusClass)
        }
        this.hasFocus = false;
        if (this.validationEvent !== false && this.validateOnBlur && this.validationEvent != "blur") {
            this.validate()
        }
        var A = this.getValue();
        if (String(A) !== String(this.startValue)) {
            this.fireEvent("change", this, A, this.startValue)
        }
        this.fireEvent("blur", this)
    },
    isValid: function (A) {
        if (this.disabled) {
            return true
        }
        var C = this.preventMark;
        this.preventMark = A === true;
        var B = this.validateValue(this.processValue(this.getRawValue()));
        this.preventMark = C;
        return B
    },
    validate: function () {
        if (this.disabled || this.validateValue(this.processValue(this.getRawValue()))) {
            this.clearInvalid();
            return true
        }
        return false
    },
    processValue: function (A) {
        return A
    },
    validateValue: function (A) {
        return true
    },
    markInvalid: function (C) {
        if (!this.rendered || this.preventMark) {
            return
        }
        this.el.addClass(this.invalidClass);
        C = C || this.invalidText;
        switch (this.msgTarget) {
            case"qtip":
                this.el.dom.qtip = C;
                this.el.dom.qclass = "x-form-invalid-tip";
                if (Ext.QuickTips) {
                    Ext.QuickTips.enable()
                }
                break;
            case"title":
                this.el.dom.title = C;
                break;
            case"under":
                if (!this.errorEl) {
                    var B = this.getErrorCt();
                    if (!B) {
                        this.el.dom.title = C;
                        break
                    }
                    this.errorEl = B.createChild({cls: "x-form-invalid-msg"});
                    this.errorEl.setWidth(B.getWidth(true) - 20)
                }
                this.errorEl.update(C);
                Ext.form.Field.msgFx[this.msgFx].show(this.errorEl, this);
                break;
            case"side":
                if (!this.errorIcon) {
                    var B = this.getErrorCt();
                    if (!B) {
                        this.el.dom.title = C;
                        break
                    }
                    this.errorIcon = B.createChild({cls: "x-form-invalid-icon"})
                }
                this.alignErrorIcon();
                this.errorIcon.dom.qtip = C;
                this.errorIcon.dom.qclass = "x-form-invalid-tip";
                this.errorIcon.show();
                this.on("resize", this.alignErrorIcon, this);
                break;
            default:
                var A = Ext.getDom(this.msgTarget);
                A.innerHTML = C;
                A.style.display = this.msgDisplay;
                break
        }
        this.fireEvent("invalid", this, C)
    },
    getErrorCt: function () {
        return this.el.findParent(".x-form-element", 5, true) || this.el.findParent(".x-form-field-wrap", 5, true)
    },
    alignErrorIcon: function () {
        this.errorIcon.alignTo(this.el, "tl-tr", [2, 0])
    },
    clearInvalid: function () {
        if (!this.rendered || this.preventMark) {
            return
        }
        this.el.removeClass(this.invalidClass);
        switch (this.msgTarget) {
            case"qtip":
                this.el.dom.qtip = "";
                break;
            case"title":
                this.el.dom.title = "";
                break;
            case"under":
                if (this.errorEl) {
                    Ext.form.Field.msgFx[this.msgFx].hide(this.errorEl, this)
                }
                break;
            case"side":
                if (this.errorIcon) {
                    this.errorIcon.dom.qtip = "";
                    this.errorIcon.hide();
                    this.un("resize", this.alignErrorIcon, this)
                }
                break;
            default:
                var A = Ext.getDom(this.msgTarget);
                A.innerHTML = "";
                A.style.display = "none";
                break
        }
        this.fireEvent("valid", this)
    },
    getRawValue: function () {
        var A = this.rendered ? this.el.getValue() : Ext.value(this.value, "");
        if (A === this.emptyText) {
            A = ""
        }
        return A
    },
    getValue: function () {
        if (!this.rendered) {
            return this.value
        }
        var A = this.el.getValue();
        if (A === this.emptyText || A === undefined) {
            A = ""
        }
        return A
    },
    setRawValue: function (A) {
        return this.el.dom.value = (A === null || A === undefined ? "" : A)
    },
    setValue: function (A) {
        this.value = A;
        if (this.rendered) {
            this.el.dom.value = (A === null || A === undefined ? "" : A);
            this.validate()
        }
    },
    adjustSize: function (A, C) {
        var B = Ext.form.Field.superclass.adjustSize.call(this, A, C);
        B.width = this.adjustWidth(this.el.dom.tagName, B.width);
        return B
    },
    adjustWidth: function (A, B) {
        A = A.toLowerCase();
        if (typeof B == "number" && !Ext.isSafari) {
            if (Ext.isIE && (A == "input" || A == "textarea")) {
                if (A == "input" && !Ext.isStrict) {
                    return this.inEditor ? B : B - 3
                }
                if (A == "input" && Ext.isStrict) {
                    return B - (Ext.isIE6 ? 4 : 1)
                }
                if (A == "textarea" && Ext.isStrict) {
                    return B - 2
                }
            } else {
                if (Ext.isOpera && Ext.isStrict) {
                    if (A == "input") {
                        return B + 2
                    }
                    if (A == "textarea") {
                        return B - 2
                    }
                }
            }
        }
        return B
    }
});
Ext.form.MessageTargets = {
    "qtip": {
        mark: function (A) {
            this.el.dom.qtip = msg;
            this.el.dom.qclass = "x-form-invalid-tip";
            if (Ext.QuickTips) {
                Ext.QuickTips.enable()
            }
        }, clear: function (A) {
            this.el.dom.qtip = ""
        }
    }, "title": {
        mark: function (A) {
            this.el.dom.title = msg
        }, clear: function (A) {
            this.el.dom.title = ""
        }
    }, "under": {
        mark: function (B) {
            if (!this.errorEl) {
                var A = this.getErrorCt();
                if (!A) {
                    this.el.dom.title = msg;
                    return
                }
                this.errorEl = A.createChild({cls: "x-form-invalid-msg"});
                this.errorEl.setWidth(A.getWidth(true) - 20)
            }
            this.errorEl.update(msg);
            Ext.form.Field.msgFx[this.msgFx].show(this.errorEl, this)
        }, clear: function (A) {
            if (this.errorEl) {
                Ext.form.Field.msgFx[this.msgFx].hide(this.errorEl, this)
            } else {
                this.el.dom.title = ""
            }
        }
    }, "side": {
        mark: function (B) {
            if (!this.errorIcon) {
                var A = this.getErrorCt();
                if (!A) {
                    this.el.dom.title = msg;
                    return
                }
                this.errorIcon = A.createChild({cls: "x-form-invalid-icon"})
            }
            this.alignErrorIcon();
            this.errorIcon.dom.qtip = msg;
            this.errorIcon.dom.qclass = "x-form-invalid-tip";
            this.errorIcon.show();
            this.on("resize", this.alignErrorIcon, this)
        }, clear: function (A) {
            if (this.errorIcon) {
                this.errorIcon.dom.qtip = "";
                this.errorIcon.hide();
                this.un("resize", this.alignErrorIcon, this)
            } else {
                this.el.dom.title = ""
            }
        }
    }, "around": {
        mark: function (A) {
        }, clear: function (A) {
        }
    }
};
Ext.form.Field.msgFx = {
    normal: {
        show: function (A, B) {
            A.setDisplayed("block")
        }, hide: function (A, B) {
            A.setDisplayed(false).update("")
        }
    }, slide: {
        show: function (A, B) {
            A.slideIn("t", {stopFx: true})
        }, hide: function (A, B) {
            A.slideOut("t", {stopFx: true, useDisplay: true})
        }
    }, slideRight: {
        show: function (A, B) {
            A.fixDisplay();
            A.alignTo(B.el, "tl-tr");
            A.slideIn("l", {stopFx: true})
        }, hide: function (A, B) {
            A.slideOut("l", {stopFx: true, useDisplay: true})
        }
    }
};
Ext.reg("field", Ext.form.Field);


Ext.form.TextField = Ext.extend(Ext.form.Field, {
    grow: false,
    growMin: 30,
    growMax: 800,
    vtype: null,
    maskRe: null,
    disableKeyFilter: false,
    allowBlank: true,
    minLength: 0,
    maxLength: Number.MAX_VALUE,
    minLengthText: "The minimum length for this field is {0}",
    maxLengthText: "The maximum length for this field is {0}",
    selectOnFocus: false,
    blankText: "This field is required",
    validator: null,
    regex: null,
    regexText: "",
    emptyText: null,
    emptyClass: "x-form-empty-field",
    initComponent: function () {
        Ext.form.TextField.superclass.initComponent.call(this);
        this.addEvents("autosize", "keydown", "keyup", "keypress")
    },
    initEvents: function () {
        Ext.form.TextField.superclass.initEvents.call(this);
        if (this.validationEvent == "keyup") {
            this.validationTask = new Ext.util.DelayedTask(this.validate, this);
            this.el.on("keyup", this.filterValidation, this)
        } else {
            if (this.validationEvent !== false) {
                this.el.on(this.validationEvent, this.validate, this, {buffer: this.validationDelay})
            }
        }
        if (this.selectOnFocus || this.emptyText) {
            this.on("focus", this.preFocus, this);
            this.el.on("mousedown", function () {
                if (!this.hasFocus) {
                    this.el.on("mouseup", function (A) {
                        A.preventDefault()
                    }, this, {single: true})
                }
            }, this);
            if (this.emptyText) {
                this.on("blur", this.postBlur, this);
                this.applyEmptyText()
            }
        }
        if (this.maskRe || (this.vtype && this.disableKeyFilter !== true && (this.maskRe = Ext.form.VTypes[this.vtype + "Mask"]))) {
            this.el.on("keypress", this.filterKeys, this)
        }
        if (this.grow) {
            this.el.on("keyup", this.onKeyUpBuffered, this, {buffer: 50});
            this.el.on("click", this.autoSize, this)
        }
        if (this.enableKeyEvents) {
            this.el.on("keyup", this.onKeyUp, this);
            this.el.on("keydown", this.onKeyDown, this);
            this.el.on("keypress", this.onKeyPress, this)
        }
    },
    processValue: function (A) {
        if (this.stripCharsRe) {
            var B = A.replace(this.stripCharsRe, "");
            if (B !== A) {
                this.setRawValue(B);
                return B
            }
        }
        return A
    },
    filterValidation: function (A) {
        if (!A.isNavKeyPress()) {
            this.validationTask.delay(this.validationDelay)
        }
    },
    onKeyUpBuffered: function (A) {
        if (!A.isNavKeyPress()) {
            this.autoSize()
        }
    },
    onKeyUp: function (A) {
        this.fireEvent("keyup", this, A)
    },
    onKeyDown: function (A) {
        this.fireEvent("keydown", this, A)
    },
    onKeyPress: function (A) {
        this.fireEvent("keypress", this, A)
    },
    reset: function () {
        Ext.form.TextField.superclass.reset.call(this);
        this.applyEmptyText()
    },
    applyEmptyText: function () {
        if (this.rendered && this.emptyText && this.getRawValue().length < 1) {
            this.setRawValue(this.emptyText);
            this.el.addClass(this.emptyClass)
        }
    },
    preFocus: function () {
        if (this.emptyText) {
            if (this.el.dom.value == this.emptyText) {
                this.setRawValue("")
            }
            this.el.removeClass(this.emptyClass)
        }
        if (this.selectOnFocus) {
            this.el.dom.select()
        }
    },
    postBlur: function () {
        this.applyEmptyText()
    },
    filterKeys: function (B) {
        if (B.ctrlKey) {
            return
        }
        var A = B.getKey();
        if (Ext.isGecko && (B.isNavKeyPress() || A == B.BACKSPACE || (A == B.DELETE && B.button == -1))) {
            return
        }
        var D = B.getCharCode(), C = String.fromCharCode(D);
        if (!Ext.isGecko && B.isSpecialKey() && !C) {
            return
        }
        if (!this.maskRe.test(C)) {
            B.stopEvent()
        }
    },
    setValue: function (A) {
        if (this.emptyText && this.el && A !== undefined && A !== null && A !== "") {
            this.el.removeClass(this.emptyClass)
        }
        Ext.form.TextField.superclass.setValue.apply(this, arguments);
        this.applyEmptyText();
        this.autoSize()
    },
    validateValue: function (A) {
        if (A.length < 1 || A === this.emptyText) {
            if (this.allowBlank) {
                this.clearInvalid();
                return true
            } else {
                this.markInvalid(this.blankText);
                return false
            }
        }
        if (A.length < this.minLength) {
            this.markInvalid(String.format(this.minLengthText, this.minLength));
            return false
        }
        if (A.length > this.maxLength) {
            this.markInvalid(String.format(this.maxLengthText, this.maxLength));
            return false
        }
        if (this.vtype) {
            var C = Ext.form.VTypes;
            if (!C[this.vtype](A, this)) {
                this.markInvalid(this.vtypeText || C[this.vtype + "Text"]);
                return false
            }
        }
        if (typeof this.validator == "function") {
            var B = this.validator(A);
            if (B !== true) {
                this.markInvalid(B);
                return false
            }
        }
        if (this.regex && !this.regex.test(A)) {
            this.markInvalid(this.regexText);
            return false
        }
        return true
    },
    selectText: function (E, A) {
        var C = this.getRawValue();
        if (C.length > 0) {
            E = E === undefined ? 0 : E;
            A = A === undefined ? C.length : A;
            var D = this.el.dom;
            if (D.setSelectionRange) {
                D.setSelectionRange(E, A)
            } else {
                if (D.createTextRange) {
                    var B = D.createTextRange();
                    B.moveStart("character", E);
                    B.moveEnd("character", A - C.length);
                    B.select()
                }
            }
        }
    },
    autoSize: function () {
        if (!this.grow || !this.rendered) {
            return
        }
        if (!this.metrics) {
            this.metrics = Ext.util.TextMetrics.createInstance(this.el)
        }
        var C = this.el;
        var B = C.dom.value;
        var D = document.createElement("div");
        D.appendChild(document.createTextNode(B));
        B = D.innerHTML;
        D = null;
        B += "&#160;";
        var A = Math.min(this.growMax, Math.max(this.metrics.getWidth(B) + 10, this.growMin));
        this.el.setWidth(A);
        this.fireEvent("autosize", this, A)
    }
});
Ext.reg("textfield", Ext.form.TextField);
