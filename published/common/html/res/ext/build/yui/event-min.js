/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

if (!YAHOO.util.Event) {
    YAHOO.util.Event = function () {
        var H = false;
        var I = [];
        var J = [];
        var F = [];
        var D = [];
        var C = 0;
        var E = [];
        var B = [];
        var A = 0;
        var G = null;
        return {
            POLL_RETRYS: 200,
            POLL_INTERVAL: 20,
            EL: 0,
            TYPE: 1,
            FN: 2,
            WFN: 3,
            OBJ: 3,
            ADJ_SCOPE: 4,
            isSafari: (/KHTML/gi).test(navigator.userAgent),
            webkit: function () {
                var K = navigator.userAgent.match(/AppleWebKit\/([^ ]*)/);
                if (K && K[1]) {
                    return K[1]
                }
                return null
            }(),
            isIE: (!this.webkit && !navigator.userAgent.match(/opera/gi) && navigator.userAgent.match(/msie/gi)),
            _interval: null,
            startInterval: function () {
                if (!this._interval) {
                    var K = this;
                    var L = function () {
                        K._tryPreloadAttach()
                    };
                    this._interval = setInterval(L, this.POLL_INTERVAL)
                }
            },
            onAvailable: function (M, K, N, L) {
                E.push({id: M, fn: K, obj: N, override: L, checkReady: false});
                C = this.POLL_RETRYS;
                this.startInterval()
            },
            onContentReady: function (M, K, N, L) {
                E.push({id: M, fn: K, obj: N, override: L, checkReady: true});
                C = this.POLL_RETRYS;
                this.startInterval()
            },
            addListener: function (M, K, V, Q, L) {
                if (!V || !V.call) {
                    return false
                }
                if (this._isValidCollection(M)) {
                    var W = true;
                    for (var R = 0, T = M.length; R < T; ++R) {
                        W = this.on(M[R], K, V, Q, L) && W
                    }
                    return W
                } else {
                    if (typeof M == "string") {
                        var P = this.getEl(M);
                        if (P) {
                            M = P
                        } else {
                            this.onAvailable(M, function () {
                                YAHOO.util.Event.on(M, K, V, Q, L)
                            });
                            return true
                        }
                    }
                }
                if (!M) {
                    return false
                }
                if ("unload" == K && Q !== this) {
                    J[J.length] = [M, K, V, Q, L];
                    return true
                }
                var Y = M;
                if (L) {
                    if (L === true) {
                        Y = Q
                    } else {
                        Y = L
                    }
                }
                var N = function (Z) {
                    return V.call(Y, YAHOO.util.Event.getEvent(Z), Q)
                };
                var X = [M, K, V, N, Y];
                var S = I.length;
                I[S] = X;
                if (this.useLegacyEvent(M, K)) {
                    var O = this.getLegacyIndex(M, K);
                    if (O == -1 || M != F[O][0]) {
                        O = F.length;
                        B[M.id + K] = O;
                        F[O] = [M, K, M["on" + K]];
                        D[O] = [];
                        M["on" + K] = function (Z) {
                            YAHOO.util.Event.fireLegacyEvent(YAHOO.util.Event.getEvent(Z), O)
                        }
                    }
                    D[O].push(X)
                } else {
                    try {
                        this._simpleAdd(M, K, N, false)
                    } catch (U) {
                        this.lastError = U;
                        this.removeListener(M, K, V);
                        return false
                    }
                }
                return true
            },
            fireLegacyEvent: function (O, M) {
                var Q = true, K, S, R, T, P;
                S = D[M];
                for (var L = 0, N = S.length; L < N; ++L) {
                    R = S[L];
                    if (R && R[this.WFN]) {
                        T = R[this.ADJ_SCOPE];
                        P = R[this.WFN].call(T, O);
                        Q = (Q && P)
                    }
                }
                K = F[M];
                if (K && K[2]) {
                    K[2](O)
                }
                return Q
            },
            getLegacyIndex: function (L, M) {
                var K = this.generateId(L) + M;
                if (typeof B[K] == "undefined") {
                    return -1
                } else {
                    return B[K]
                }
            },
            useLegacyEvent: function (L, M) {
                if (this.webkit && ("click" == M || "dblclick" == M)) {
                    var K = parseInt(this.webkit, 10);
                    if (!isNaN(K) && K < 418) {
                        return true
                    }
                }
                return false
            },
            removeListener: function (L, K, T) {
                var O, R;
                if (typeof L == "string") {
                    L = this.getEl(L)
                } else {
                    if (this._isValidCollection(L)) {
                        var U = true;
                        for (O = 0, R = L.length; O < R; ++O) {
                            U = (this.removeListener(L[O], K, T) && U)
                        }
                        return U
                    }
                }
                if (!T || !T.call) {
                    return this.purgeElement(L, false, K)
                }
                if ("unload" == K) {
                    for (O = 0, R = J.length; O < R; O++) {
                        var V = J[O];
                        if (V && V[0] == L && V[1] == K && V[2] == T) {
                            J.splice(O, 1);
                            return true
                        }
                    }
                    return false
                }
                var P = null;
                var Q = arguments[3];
                if ("undefined" == typeof Q) {
                    Q = this._getCacheIndex(L, K, T)
                }
                if (Q >= 0) {
                    P = I[Q]
                }
                if (!L || !P) {
                    return false
                }
                if (this.useLegacyEvent(L, K)) {
                    var N = this.getLegacyIndex(L, K);
                    var M = D[N];
                    if (M) {
                        for (O = 0, R = M.length; O < R; ++O) {
                            V = M[O];
                            if (V && V[this.EL] == L && V[this.TYPE] == K && V[this.FN] == T) {
                                M.splice(O, 1);
                                break
                            }
                        }
                    }
                } else {
                    try {
                        this._simpleRemove(L, K, P[this.WFN], false)
                    } catch (S) {
                        this.lastError = S;
                        return false
                    }
                }
                delete I[Q][this.WFN];
                delete I[Q][this.FN];
                I.splice(Q, 1);
                return true
            },
            getTarget: function (M, L) {
                var K = M.target || M.srcElement;
                return this.resolveTextNode(K)
            },
            resolveTextNode: function (K) {
                if (K && 3 == K.nodeType) {
                    return K.parentNode
                } else {
                    return K
                }
            },
            getPageX: function (L) {
                var K = L.pageX;
                if (!K && 0 !== K) {
                    K = L.clientX || 0;
                    if (this.isIE) {
                        K += this._getScrollLeft()
                    }
                }
                return K
            },
            getPageY: function (K) {
                var L = K.pageY;
                if (!L && 0 !== L) {
                    L = K.clientY || 0;
                    if (this.isIE) {
                        L += this._getScrollTop()
                    }
                }
                return L
            },
            getXY: function (K) {
                return [this.getPageX(K), this.getPageY(K)]
            },
            getRelatedTarget: function (L) {
                var K = L.relatedTarget;
                if (!K) {
                    if (L.type == "mouseout") {
                        K = L.toElement
                    } else {
                        if (L.type == "mouseover") {
                            K = L.fromElement
                        }
                    }
                }
                return this.resolveTextNode(K)
            },
            getTime: function (M) {
                if (!M.time) {
                    var L = new Date().getTime();
                    try {
                        M.time = L
                    } catch (K) {
                        this.lastError = K;
                        return L
                    }
                }
                return M.time
            },
            stopEvent: function (K) {
                this.stopPropagation(K);
                this.preventDefault(K)
            },
            stopPropagation: function (K) {
                if (K.stopPropagation) {
                    K.stopPropagation()
                } else {
                    K.cancelBubble = true
                }
            },
            preventDefault: function (K) {
                if (K.preventDefault) {
                    K.preventDefault()
                } else {
                    K.returnValue = false
                }
            },
            getEvent: function (L) {
                var K = L || window.event;
                if (!K) {
                    var M = this.getEvent.caller;
                    while (M) {
                        K = M.arguments[0];
                        if (K && Event == K.constructor) {
                            break
                        }
                        M = M.caller
                    }
                }
                return K
            },
            getCharCode: function (K) {
                return K.charCode || K.keyCode || 0
            },
            _getCacheIndex: function (O, P, N) {
                for (var M = 0, L = I.length; M < L; ++M) {
                    var K = I[M];
                    if (K && K[this.FN] == N && K[this.EL] == O && K[this.TYPE] == P) {
                        return M
                    }
                }
                return -1
            },
            generateId: function (K) {
                var L = K.id;
                if (!L) {
                    L = "yuievtautoid-" + A;
                    ++A;
                    K.id = L
                }
                return L
            },
            _isValidCollection: function (K) {
                return (K && K.length && typeof K != "string" && !K.tagName && !K.alert && typeof K[0] != "undefined")
            },
            elCache: {},
            getEl: function (K) {
                return document.getElementById(K)
            },
            clearCache: function () {
            },
            _load: function (L) {
                H = true;
                var K = YAHOO.util.Event;
                if (this.isIE) {
                    K._simpleRemove(window, "load", K._load)
                }
            },
            _tryPreloadAttach: function () {
                if (this.locked) {
                    return false
                }
                this.locked = true;
                var Q = !H;
                if (!Q) {
                    Q = (C > 0)
                }
                var P = [];
                for (var L = 0, K = E.length; L < K; ++L) {
                    var O = E[L];
                    if (O) {
                        var N = this.getEl(O.id);
                        if (N) {
                            if (!O.checkReady || H || N.nextSibling || (document && document.body)) {
                                var M = N;
                                if (O.override) {
                                    if (O.override === true) {
                                        M = O.obj
                                    } else {
                                        M = O.override
                                    }
                                }
                                O.fn.call(M, O.obj);
                                E[L] = null
                            }
                        } else {
                            P.push(O)
                        }
                    }
                }
                C = (P.length === 0) ? 0 : C - 1;
                if (Q) {
                    this.startInterval()
                } else {
                    clearInterval(this._interval);
                    this._interval = null
                }
                this.locked = false;
                return true
            },
            purgeElement: function (N, O, Q) {
                var P = this.getListeners(N, Q);
                if (P) {
                    for (var M = 0, K = P.length; M < K; ++M) {
                        var L = P[M];
                        this.removeListener(N, L.type, L.fn)
                    }
                }
                if (O && N && N.childNodes) {
                    for (M = 0, K = N.childNodes.length; M < K; ++M) {
                        this.purgeElement(N.childNodes[M], O, Q)
                    }
                }
            },
            getListeners: function (M, K) {
                var P = [], L;
                if (!K) {
                    L = [I, J]
                } else {
                    if (K == "unload") {
                        L = [J]
                    } else {
                        L = [I]
                    }
                }
                for (var O = 0; O < L.length; ++O) {
                    var S = L[O];
                    if (S && S.length > 0) {
                        for (var Q = 0, R = S.length; Q < R; ++Q) {
                            var N = S[Q];
                            if (N && N[this.EL] === M && (!K || K === N[this.TYPE])) {
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
            _unload: function (R) {
                var Q = YAHOO.util.Event, O, N, L, K, M;
                for (O = 0, K = J.length; O < K; ++O) {
                    L = J[O];
                    if (L) {
                        var P = window;
                        if (L[Q.ADJ_SCOPE]) {
                            if (L[Q.ADJ_SCOPE] === true) {
                                P = L[Q.OBJ]
                            } else {
                                P = L[Q.ADJ_SCOPE]
                            }
                        }
                        L[Q.FN].call(P, Q.getEvent(R), L[Q.OBJ]);
                        J[O] = null;
                        L = null;
                        P = null
                    }
                }
                J = null;
                if (I && I.length > 0) {
                    N = I.length;
                    while (N) {
                        M = N - 1;
                        L = I[M];
                        if (L) {
                            Q.removeListener(L[Q.EL], L[Q.TYPE], L[Q.FN], M)
                        }
                        N = N - 1
                    }
                    L = null;
                    Q.clearCache()
                }
                for (O = 0, K = F.length; O < K; ++O) {
                    F[O][0] = null;
                    F[O] = null
                }
                F = null;
                Q._simpleRemove(window, "unload", Q._unload)
            },
            _getScrollLeft: function () {
                return this._getScroll()[1]
            },
            _getScrollTop: function () {
                return this._getScroll()[0]
            },
            _getScroll: function () {
                var K = document.documentElement, L = document.body;
                if (K && (K.scrollTop || K.scrollLeft)) {
                    return [K.scrollTop, K.scrollLeft]
                } else {
                    if (L) {
                        return [L.scrollTop, L.scrollLeft]
                    } else {
                        return [0, 0]
                    }
                }
            },
            regCE: function () {
            },
            _simpleAdd: function () {
                if (window.addEventListener) {
                    return function (M, N, L, K) {
                        M.addEventListener(N, L, (K))
                    }
                } else {
                    if (window.attachEvent) {
                        return function (M, N, L, K) {
                            M.attachEvent("on" + N, L)
                        }
                    } else {
                        return function () {
                        }
                    }
                }
            }(),
            _simpleRemove: function () {
                if (window.removeEventListener) {
                    return function (M, N, L, K) {
                        M.removeEventListener(N, L, (K))
                    }
                } else {
                    if (window.detachEvent) {
                        return function (L, M, K) {
                            L.detachEvent("on" + M, K)
                        }
                    } else {
                        return function () {
                        }
                    }
                }
            }()
        }
    }();
    (function () {
        var A = YAHOO.util.Event;
        A.on = A.addListener;
        if (document && document.body) {
            A._load()
        } else {
            A._simpleAdd(window, "load", A._load)
        }
        A._simpleAdd(window, "unload", A._unload);
        A._tryPreloadAttach()
    })()
}
YAHOO.util.CustomEvent = function (D, B, C, A) {
    this.type = D;
    this.scope = B || window;
    this.silent = C;
    this.signature = A || YAHOO.util.CustomEvent.LIST;
    this.subscribers = [];
    if (!this.silent) {
    }
    var E = "_YUICEOnSubscribe";
    if (D !== E) {
        this.subscribeEvent = new YAHOO.util.CustomEvent(E, this, true)
    }
};
YAHOO.util.CustomEvent.LIST = 0;
YAHOO.util.CustomEvent.FLAT = 1;
YAHOO.util.CustomEvent.prototype = {
    subscribe: function (B, C, A) {
        if (this.subscribeEvent) {
            this.subscribeEvent.fire(B, C, A)
        }
        this.subscribers.push(new YAHOO.util.Subscriber(B, C, A))
    }, unsubscribe: function (D, F) {
        if (!D) {
            return this.unsubscribeAll()
        }
        var E = false;
        for (var B = 0, A = this.subscribers.length; B < A; ++B) {
            var C = this.subscribers[B];
            if (C && C.contains(D, F)) {
                this._delete(B);
                E = true
            }
        }
        return E
    }, fire: function () {
        var A = this.subscribers.length;
        if (!A && this.silent) {
            return true
        }
        var C = [], B = true, D;
        for (D = 0; D < arguments.length; ++D) {
            C.push(arguments[D])
        }
        var G = C.length;
        if (!this.silent) {
        }
        for (D = 0; D < A; ++D) {
            var F = this.subscribers[D];
            if (F) {
                if (!this.silent) {
                }
                var E = F.getScope(this.scope);
                if (this.signature == YAHOO.util.CustomEvent.FLAT) {
                    var H = null;
                    if (C.length > 0) {
                        H = C[0]
                    }
                    B = F.fn.call(E, H, F.obj)
                } else {
                    B = F.fn.call(E, this.type, C, F.obj)
                }
                if (false === B) {
                    if (!this.silent) {
                    }
                    return false
                }
            }
        }
        return true
    }, unsubscribeAll: function () {
        for (var B = 0, A = this.subscribers.length; B < A; ++B) {
            this._delete(A - 1 - B)
        }
        return B
    }, _delete: function (A) {
        var B = this.subscribers[A];
        if (B) {
            delete B.fn;
            delete B.obj
        }
        this.subscribers.splice(A, 1)
    }, toString: function () {
        return "CustomEvent: " + "'" + this.type + "', " + "scope: " + this.scope
    }
};
YAHOO.util.Subscriber = function (B, C, A) {
    this.fn = B;
    this.obj = C || null;
    this.override = A
};
YAHOO.util.Subscriber.prototype.getScope = function (A) {
    if (this.override) {
        if (this.override === true) {
            return this.obj
        } else {
            return this.override
        }
    }
    return A
};
YAHOO.util.Subscriber.prototype.contains = function (A, B) {
    if (B) {
        return (this.fn == A && this.obj == B)
    } else {
        return (this.fn == A)
    }
};
YAHOO.util.Subscriber.prototype.toString = function () {
    return "Subscriber { obj: " + (this.obj || "") + ", override: " + (this.override || "no") + " }"
};
YAHOO.util.EventProvider = function () {
};
YAHOO.util.EventProvider.prototype = {
    __yui_events: null, __yui_subscribers: null, subscribe: function (A, C, F, E) {
        this.__yui_events = this.__yui_events || {};
        var D = this.__yui_events[A];
        if (D) {
            D.subscribe(C, F, E)
        } else {
            this.__yui_subscribers = this.__yui_subscribers || {};
            var B = this.__yui_subscribers;
            if (!B[A]) {
                B[A] = []
            }
            B[A].push({fn: C, obj: F, override: E})
        }
    }, unsubscribe: function (A, B, D) {
        this.__yui_events = this.__yui_events || {};
        var C = this.__yui_events[A];
        if (C) {
            return C.unsubscribe(B, D)
        } else {
            return false
        }
    }, unsubscribeAll: function (A) {
        return this.unsubscribe(A)
    }, createEvent: function (G, D) {
        this.__yui_events = this.__yui_events || {};
        var A = D || {};
        var I = this.__yui_events;
        if (I[G]) {
        } else {
            var H = A.scope || this;
            var E = A.silent || null;
            var B = new YAHOO.util.CustomEvent(G, H, E, YAHOO.util.CustomEvent.FLAT);
            I[G] = B;
            if (A.onSubscribeCallback) {
                B.subscribeEvent.subscribe(A.onSubscribeCallback)
            }
            this.__yui_subscribers = this.__yui_subscribers || {};
            var F = this.__yui_subscribers[G];
            if (F) {
                for (var C = 0; C < F.length; ++C) {
                    B.subscribe(F[C].fn, F[C].obj, F[C].override)
                }
            }
        }
        return I[G]
    }, fireEvent: function (E, D, A, C) {
        this.__yui_events = this.__yui_events || {};
        var G = this.__yui_events[E];
        if (G) {
            var B = [];
            for (var F = 1; F < arguments.length; ++F) {
                B.push(arguments[F])
            }
            return G.fire.apply(G, B)
        } else {
            return null
        }
    }, hasEvent: function (A) {
        if (this.__yui_events) {
            if (this.__yui_events[A]) {
                return true
            }
        }
        return false
    }
};
YAHOO.util.KeyListener = function (A, F, B, C) {
    if (!A) {
    } else {
        if (!F) {
        } else {
            if (!B) {
            }
        }
    }
    if (!C) {
        C = YAHOO.util.KeyListener.KEYDOWN
    }
    var D = new YAHOO.util.CustomEvent("keyPressed");
    this.enabledEvent = new YAHOO.util.CustomEvent("enabled");
    this.disabledEvent = new YAHOO.util.CustomEvent("disabled");
    if (typeof A == "string") {
        A = document.getElementById(A)
    }
    if (typeof B == "function") {
        D.subscribe(B)
    } else {
        D.subscribe(B.fn, B.scope, B.correctScope)
    }
    function E(K, J) {
        if (!F.shift) {
            F.shift = false
        }
        if (!F.alt) {
            F.alt = false
        }
        if (!F.ctrl) {
            F.ctrl = false
        }
        if (K.shiftKey == F.shift && K.altKey == F.alt && K.ctrlKey == F.ctrl) {
            var H;
            var G;
            if (F.keys instanceof Array) {
                for (var I = 0; I < F.keys.length; I++) {
                    H = F.keys[I];
                    if (H == K.charCode) {
                        D.fire(K.charCode, K);
                        break
                    } else {
                        if (H == K.keyCode) {
                            D.fire(K.keyCode, K);
                            break
                        }
                    }
                }
            } else {
                H = F.keys;
                if (H == K.charCode) {
                    D.fire(K.charCode, K)
                } else {
                    if (H == K.keyCode) {
                        D.fire(K.keyCode, K)
                    }
                }
            }
        }
    }

    this.enable = function () {
        if (!this.enabled) {
            YAHOO.util.Event.addListener(A, C, E);
            this.enabledEvent.fire(F)
        }
        this.enabled = true
    };
    this.disable = function () {
        if (this.enabled) {
            YAHOO.util.Event.removeListener(A, C, E);
            this.disabledEvent.fire(F)
        }
        this.enabled = false
    };
    this.toString = function () {
        return "KeyListener [" + F.keys + "] " + A.tagName + (A.id ? "[" + A.id + "]" : "")
    }
};
YAHOO.util.KeyListener.KEYDOWN = "keydown";
YAHOO.util.KeyListener.KEYUP = "keyup";
YAHOO.register("event", YAHOO.util.Event, {version: "2.2.0", build: "127"});