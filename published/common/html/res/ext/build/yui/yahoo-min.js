/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

if (typeof YAHOO == "undefined") {
    var YAHOO = {}
}
YAHOO.namespace = function () {
    var A = arguments, E = null, C, B, D;
    for (C = 0; C < A.length; C = C + 1) {
        D = A[C].split(".");
        E = YAHOO;
        for (B = (D[0] == "YAHOO") ? 1 : 0; B < D.length; B = B + 1) {
            E[D[B]] = E[D[B]] || {};
            E = E[D[B]]
        }
    }
    return E
};
YAHOO.log = function (D, A, C) {
    var B = YAHOO.widget.Logger;
    if (B && B.log) {
        return B.log(D, A, C)
    } else {
        return false
    }
};
YAHOO.init = function () {
    this.namespace("util", "widget", "example");
    if (typeof YAHOO_config != "undefined") {
        var B = YAHOO_config.listener, A = YAHOO.env.listeners, D = true, C;
        if (B) {
            for (C = 0; C < A.length; C = C + 1) {
                if (A[C] == B) {
                    D = false;
                    break
                }
            }
            if (D) {
                A.push(B)
            }
        }
    }
};
YAHOO.register = function (A, E, D) {
    var I = YAHOO.env.modules;
    if (!I[A]) {
        I[A] = {versions: [], builds: []}
    }
    var B = I[A], H = D.version, G = D.build, F = YAHOO.env.listeners;
    B.name = A;
    B.version = H;
    B.build = G;
    B.versions.push(H);
    B.builds.push(G);
    B.mainClass = E;
    for (var C = 0; C < F.length; C = C + 1) {
        F[C](B)
    }
    if (E) {
        E.VERSION = H;
        E.BUILD = G
    } else {
        YAHOO.log("mainClass is undefined for module " + A, "warn")
    }
};
YAHOO.env = YAHOO.env || {
        modules: [], listeners: [], getVersion: function (A) {
            return YAHOO.env.modules[A] || null
        }
    };
YAHOO.lang = {
    isArray: function (A) {
        if (A.constructor && A.constructor.toString().indexOf("Array") > -1) {
            return true
        } else {
            return YAHOO.lang.isObject(A) && A.constructor == Array
        }
    }, isBoolean: function (A) {
        return typeof A == "boolean"
    }, isFunction: function (A) {
        return typeof A == "function"
    }, isNull: function (A) {
        return A === null
    }, isNumber: function (A) {
        return typeof A == "number" && isFinite(A)
    }, isObject: function (A) {
        return typeof A == "object" || YAHOO.lang.isFunction(A)
    }, isString: function (A) {
        return typeof A == "string"
    }, isUndefined: function (A) {
        return typeof A == "undefined"
    }, hasOwnProperty: function (A, B) {
        if (Object.prototype.hasOwnProperty) {
            return A.hasOwnProperty(B)
        }
        return !YAHOO.lang.isUndefined(A[B]) && A.constructor.prototype[B] !== A[B]
    }, extend: function (D, E, C) {
        var B = function () {
        };
        B.prototype = E.prototype;
        D.prototype = new B();
        D.prototype.constructor = D;
        D.superclass = E.prototype;
        if (E.prototype.constructor == Object.prototype.constructor) {
            E.prototype.constructor = E
        }
        if (C) {
            for (var A in C) {
                D.prototype[A] = C[A]
            }
        }
    }, augment: function (E, D) {
        var C = E.prototype, F = D.prototype, A = arguments, B, G;
        if (A[2]) {
            for (B = 2; B < A.length; B = B + 1) {
                C[A[B]] = F[A[B]]
            }
        } else {
            for (G in F) {
                if (!C[G]) {
                    C[G] = F[G]
                }
            }
        }
    }
};
YAHOO.init();
YAHOO.util.Lang = YAHOO.lang;
YAHOO.augment = YAHOO.lang.augment;
YAHOO.extend = YAHOO.lang.extend;
YAHOO.register("yahoo", YAHOO, {version: "2.2.0", build: "127"});