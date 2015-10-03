/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext = {version: "2.0.1"};
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
    var isStrict = document.compatMode == "CSS1Compat", isOpera = ua.indexOf("opera") > -1, isSafari = (/webkit|khtml/).test(ua), isIE = !isOpera && ua.indexOf("msie") > -1, isIE7 = !isOpera && ua.indexOf("msie 7") > -1, isGecko = !isSafari && ua.indexOf("gecko") > -1, isBorderBox = isIE && !isStrict, isWindows = (ua.indexOf("windows") != -1 || ua.indexOf("win32") != -1), isMac = (ua.indexOf("macintosh") != -1 || ua.indexOf("mac os x") != -1), isLinux = (ua.indexOf("linux") != -1), isSecure = window.location.href.toLowerCase().indexOf("https") === 0;
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
            return function (sb, sp, overrides) {
                if (typeof sp == "object") {
                    overrides = sp;
                    sp = sb;
                    sb = function () {
                        sp.apply(this, arguments)
                    }
                }
                var F = function () {
                }, sbp, spp = sp.prototype;
                F.prototype = spp;
                sbp = sb.prototype = new F();
                sbp.constructor = sb;
                sb.superclass = spp;
                if (spp.constructor == Object.prototype.constructor) {
                    spp.constructor = sp
                }
                sb.override = function (o) {
                    Ext.override(sb, o)
                };
                sbp.override = io;
                Ext.override(sb, overrides);
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
                        if (ov instanceof Array) {
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
                if (a instanceof Array) {
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
                    if (as.dom) {
                        as.removeAllListeners();
                        as.remove();
                        continue
                    }
                    if (typeof as.destroy == "function") {
                        as.destroy()
                    }
                }
            }
        },
        removeNode: isIE ? function () {
            var d;
            return function (n) {
                if (n) {
                    d = d || document.createElement("div");
                    d.appendChild(n);
                    d.innerHTML = ""
                }
            }
        }() : function (n) {
            if (n && n.parentNode) {
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
        isOpera: isOpera,
        isSafari: isSafari,
        isIE: isIE,
        isIE6: isIE && !isIE7,
        isIE7: isIE7,
        isGecko: isGecko,
        isBorderBox: isBorderBox,
        isLinux: isLinux,
        isWindows: isWindows,
        isMac: isMac,
        isAir: !!window.air,
        useShims: ((isIE && !isIE7) || (isGecko && isMac))
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