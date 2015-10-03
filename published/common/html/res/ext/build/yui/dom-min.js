/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

(function () {
    var C = YAHOO.util, J, H, G = 0, I = {};
    var B = navigator.userAgent.toLowerCase(), D = (B.indexOf("opera") > -1), K = (B.indexOf("safari") > -1), A = (!D && !K && B.indexOf("gecko") > -1), F = (!D && B.indexOf("msie") > -1);
    var E = {HYPHEN: /(-[a-z])/i};
    var L = function (M) {
        if (!E.HYPHEN.test(M)) {
            return M
        }
        if (I[M]) {
            return I[M]
        }
        while (E.HYPHEN.exec(M)) {
            M = M.replace(RegExp.$1, RegExp.$1.substr(1).toUpperCase())
        }
        I[M] = M;
        return M
    };
    if (document.defaultView && document.defaultView.getComputedStyle) {
        J = function (M, P) {
            var O = null;
            var N = document.defaultView.getComputedStyle(M, "");
            if (N) {
                O = N[L(P)]
            }
            return M.style[P] || O
        }
    } else {
        if (document.documentElement.currentStyle && F) {
            J = function (M, O) {
                switch (L(O)) {
                    case"opacity":
                        var Q = 100;
                        try {
                            Q = M.filters["DXImageTransform.Microsoft.Alpha"].opacity
                        } catch (P) {
                            try {
                                Q = M.filters("alpha").opacity
                            } catch (P) {
                            }
                        }
                        return Q / 100;
                        break;
                    default:
                        var N = M.currentStyle ? M.currentStyle[O] : null;
                        return (M.style[O] || N)
                }
            }
        } else {
            J = function (M, N) {
                return M.style[N]
            }
        }
    }
    if (F) {
        H = function (M, N, O) {
            switch (N) {
                case"opacity":
                    if (typeof M.style.filter == "string") {
                        M.style.filter = "alpha(opacity=" + O * 100 + ")";
                        if (!M.currentStyle || !M.currentStyle.hasLayout) {
                            M.style.zoom = 1
                        }
                    }
                    break;
                default:
                    M.style[N] = O
            }
        }
    } else {
        H = function (M, N, O) {
            M.style[N] = O
        }
    }
    YAHOO.util.Dom = {
        get: function (O) {
            if (!O) {
                return null
            }
            if (typeof O != "string" && !(O instanceof Array)) {
                return O
            }
            if (typeof O == "string") {
                return document.getElementById(O)
            } else {
                var P = [];
                for (var N = 0, M = O.length; N < M; ++N) {
                    P[P.length] = C.Dom.get(O[N])
                }
                return P
            }
            return null
        }, getStyle: function (M, O) {
            O = L(O);
            var N = function (P) {
                return J(P, O)
            };
            return C.Dom.batch(M, N, C.Dom, true)
        }, setStyle: function (M, O, P) {
            O = L(O);
            var N = function (Q) {
                H(Q, O, P)
            };
            C.Dom.batch(M, N, C.Dom, true)
        }, getXY: function (M) {
            var N = function (P) {
                if (P.parentNode === null || P.offsetParent === null || this.getStyle(P, "display") == "none") {
                    return false
                }
                var O = null;
                var U = [];
                var Q;
                if (P.getBoundingClientRect) {
                    Q = P.getBoundingClientRect();
                    var S = document;
                    if (!this.inDocument(P) && parent.document != document) {
                        S = parent.document;
                        if (!this.isAncestor(S.documentElement, P)) {
                            return false
                        }
                    }
                    var R = Math.max(S.documentElement.scrollTop, S.body.scrollTop);
                    var T = Math.max(S.documentElement.scrollLeft, S.body.scrollLeft);
                    return [Q.left + T, Q.top + R]
                } else {
                    U = [P.offsetLeft, P.offsetTop];
                    O = P.offsetParent;
                    if (O != P) {
                        while (O) {
                            U[0] += O.offsetLeft;
                            U[1] += O.offsetTop;
                            O = O.offsetParent
                        }
                    }
                    if (K && this.getStyle(P, "position") == "absolute") {
                        U[0] -= document.body.offsetLeft;
                        U[1] -= document.body.offsetTop
                    }
                }
                if (P.parentNode) {
                    O = P.parentNode
                } else {
                    O = null
                }
                while (O && O.tagName.toUpperCase() != "BODY" && O.tagName.toUpperCase() != "HTML") {
                    if (C.Dom.getStyle(O, "display") != "inline") {
                        U[0] -= O.scrollLeft;
                        U[1] -= O.scrollTop
                    }
                    if (O.parentNode) {
                        O = O.parentNode
                    } else {
                        O = null
                    }
                }
                return U
            };
            return C.Dom.batch(M, N, C.Dom, true)
        }, getX: function (M) {
            var N = function (O) {
                return C.Dom.getXY(O)[0]
            };
            return C.Dom.batch(M, N, C.Dom, true)
        }, getY: function (M) {
            var N = function (O) {
                return C.Dom.getXY(O)[1]
            };
            return C.Dom.batch(M, N, C.Dom, true)
        }, setXY: function (M, P, O) {
            var N = function (S) {
                var R = this.getStyle(S, "position");
                if (R == "static") {
                    this.setStyle(S, "position", "relative");
                    R = "relative"
                }
                var U = this.getXY(S);
                if (U === false) {
                    return false
                }
                var T = [parseInt(this.getStyle(S, "left"), 10), parseInt(this.getStyle(S, "top"), 10)];
                if (isNaN(T[0])) {
                    T[0] = (R == "relative") ? 0 : S.offsetLeft
                }
                if (isNaN(T[1])) {
                    T[1] = (R == "relative") ? 0 : S.offsetTop
                }
                if (P[0] !== null) {
                    S.style.left = P[0] - U[0] + T[0] + "px"
                }
                if (P[1] !== null) {
                    S.style.top = P[1] - U[1] + T[1] + "px"
                }
                if (!O) {
                    var Q = this.getXY(S);
                    if ((P[0] !== null && Q[0] != P[0]) || (P[1] !== null && Q[1] != P[1])) {
                        this.setXY(S, P, true)
                    }
                }
            };
            C.Dom.batch(M, N, C.Dom, true)
        }, setX: function (N, M) {
            C.Dom.setXY(N, [M, null])
        }, setY: function (M, N) {
            C.Dom.setXY(M, [null, N])
        }, getRegion: function (M) {
            var N = function (O) {
                var P = new C.Region.getRegion(O);
                return P
            };
            return C.Dom.batch(M, N, C.Dom, true)
        }, getClientWidth: function () {
            return C.Dom.getViewportWidth()
        }, getClientHeight: function () {
            return C.Dom.getViewportHeight()
        }, getElementsByClassName: function (O, M, N) {
            var P = function (Q) {
                return C.Dom.hasClass(Q, O)
            };
            return C.Dom.getElementsBy(P, M, N)
        }, hasClass: function (O, N) {
            var M = new RegExp("(?:^|\\s+)" + N + "(?:\\s+|$)");
            var P = function (Q) {
                return M.test(Q["className"])
            };
            return C.Dom.batch(O, P, C.Dom, true)
        }, addClass: function (N, M) {
            var O = function (P) {
                if (this.hasClass(P, M)) {
                    return
                }
                P["className"] = [P["className"], M].join(" ")
            };
            C.Dom.batch(N, O, C.Dom, true)
        }, removeClass: function (O, N) {
            var M = new RegExp("(?:^|\\s+)" + N + "(?:\\s+|$)", "g");
            var P = function (Q) {
                if (!this.hasClass(Q, N)) {
                    return
                }
                var R = Q["className"];
                Q["className"] = R.replace(M, " ");
                if (this.hasClass(Q, N)) {
                    this.removeClass(Q, N)
                }
            };
            C.Dom.batch(O, P, C.Dom, true)
        }, replaceClass: function (P, N, M) {
            if (N === M) {
                return false
            }
            var O = new RegExp("(?:^|\\s+)" + N + "(?:\\s+|$)", "g");
            var Q = function (R) {
                if (!this.hasClass(R, N)) {
                    this.addClass(R, M);
                    return
                }
                R["className"] = R["className"].replace(O, " " + M + " ");
                if (this.hasClass(R, N)) {
                    this.replaceClass(R, N, M)
                }
            };
            C.Dom.batch(P, Q, C.Dom, true)
        }, generateId: function (M, O) {
            O = O || "yui-gen";
            M = M || {};
            var N = function (P) {
                if (P) {
                    P = C.Dom.get(P)
                } else {
                    P = {}
                }
                if (!P.id) {
                    P.id = O + G++
                }
                return P.id
            };
            return C.Dom.batch(M, N, C.Dom, true)
        }, isAncestor: function (N, O) {
            N = C.Dom.get(N);
            if (!N || !O) {
                return false
            }
            var M = function (Q) {
                if (N.contains && !K) {
                    return N.contains(Q)
                } else {
                    if (N.compareDocumentPosition) {
                        return !!(N.compareDocumentPosition(Q) & 16)
                    } else {
                        var P = Q.parentNode;
                        while (P) {
                            if (P == N) {
                                return true
                            } else {
                                if (!P.tagName || P.tagName.toUpperCase() == "HTML") {
                                    return false
                                }
                            }
                            P = P.parentNode
                        }
                        return false
                    }
                }
            };
            return C.Dom.batch(O, M, C.Dom, true)
        }, inDocument: function (M) {
            var N = function (O) {
                return this.isAncestor(document.documentElement, O)
            };
            return C.Dom.batch(M, N, C.Dom, true)
        }, getElementsBy: function (S, N, O) {
            N = N || "*";
            var P = [];
            if (O) {
                O = C.Dom.get(O);
                if (!O) {
                    return P
                }
            } else {
                O = document
            }
            var R = O.getElementsByTagName(N);
            if (!R.length && (N == "*" && O.all)) {
                R = O.all
            }
            for (var Q = 0, M = R.length; Q < M; ++Q) {
                if (S(R[Q])) {
                    P[P.length] = R[Q]
                }
            }
            return P
        }, batch: function (Q, M, P, O) {
            var N = Q;
            Q = C.Dom.get(Q);
            var U = (O) ? P : window;
            if (!Q || Q.tagName || !Q.length) {
                if (!Q) {
                    return false
                }
                return M.call(U, Q, P)
            }
            var S = [];
            for (var R = 0, T = Q.length; R < T; ++R) {
                if (!Q[R]) {
                    N = Q[R]
                }
                S[S.length] = M.call(U, Q[R], P)
            }
            return S
        }, getDocumentHeight: function () {
            var N = (document.compatMode != "CSS1Compat") ? document.body.scrollHeight : document.documentElement.scrollHeight;
            var M = Math.max(N, C.Dom.getViewportHeight());
            return M
        }, getDocumentWidth: function () {
            var N = (document.compatMode != "CSS1Compat") ? document.body.scrollWidth : document.documentElement.scrollWidth;
            var M = Math.max(N, C.Dom.getViewportWidth());
            return M
        }, getViewportHeight: function () {
            var M = self.innerHeight;
            var N = document.compatMode;
            if ((N || F) && !D) {
                M = (N == "CSS1Compat") ? document.documentElement.clientHeight : document.body.clientHeight
            }
            return M
        }, getViewportWidth: function () {
            var M = self.innerWidth;
            var N = document.compatMode;
            if (N || F) {
                M = (N == "CSS1Compat") ? document.documentElement.clientWidth : document.body.clientWidth
            }
            return M
        }
    }
})();
YAHOO.util.Region = function (C, D, A, B) {
    this.top = C;
    this[1] = C;
    this.right = D;
    this.bottom = A;
    this.left = B;
    this[0] = B
};
YAHOO.util.Region.prototype.contains = function (A) {
    return (A.left >= this.left && A.right <= this.right && A.top >= this.top && A.bottom <= this.bottom)
};
YAHOO.util.Region.prototype.getArea = function () {
    return ((this.bottom - this.top) * (this.right - this.left))
};
YAHOO.util.Region.prototype.intersect = function (E) {
    var C = Math.max(this.top, E.top);
    var D = Math.min(this.right, E.right);
    var A = Math.min(this.bottom, E.bottom);
    var B = Math.max(this.left, E.left);
    if (A >= C && D >= B) {
        return new YAHOO.util.Region(C, D, A, B)
    } else {
        return null
    }
};
YAHOO.util.Region.prototype.union = function (E) {
    var C = Math.min(this.top, E.top);
    var D = Math.max(this.right, E.right);
    var A = Math.max(this.bottom, E.bottom);
    var B = Math.min(this.left, E.left);
    return new YAHOO.util.Region(C, D, A, B)
};
YAHOO.util.Region.prototype.toString = function () {
    return ("Region {" + "top: " + this.top + ", right: " + this.right + ", bottom: " + this.bottom + ", left: " + this.left + "}")
};
YAHOO.util.Region.getRegion = function (D) {
    var F = YAHOO.util.Dom.getXY(D);
    var C = F[1];
    var E = F[0] + D.offsetWidth;
    var A = F[1] + D.offsetHeight;
    var B = F[0];
    return new YAHOO.util.Region(C, E, A, B)
};
YAHOO.util.Point = function (A, B) {
    if (A instanceof Array) {
        B = A[1];
        A = A[0]
    }
    this.x = this.right = this.left = this[0] = A;
    this.y = this.top = this.bottom = this[1] = B
};
YAHOO.util.Point.prototype = new YAHOO.util.Region();
YAHOO.register("dom", YAHOO.util.Dom, {version: "2.2.0", build: "127"});