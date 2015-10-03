/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.DomHelper = function () {
    var L = null;
    var F = /^(?:br|frame|hr|img|input|link|meta|range|spacer|wbr|area|param|col)$/i;
    var B = /^table|tbody|tr|td$/i;
    var A = function (T) {
        if (typeof T == "string") {
            return T
        }
        var P = "";
        if (!T.tag) {
            T.tag = "div"
        }
        P += "<" + T.tag;
        for (var O in T) {
            if (O == "tag" || O == "children" || O == "cn" || O == "html" || typeof T[O] == "function") {
                continue
            }
            if (O == "style") {
                var S = T["style"];
                if (typeof S == "function") {
                    S = S.call()
                }
                if (typeof S == "string") {
                    P += " style=\"" + S + "\""
                } else {
                    if (typeof S == "object") {
                        P += " style=\"";
                        for (var R in S) {
                            if (typeof S[R] != "function") {
                                P += R + ":" + S[R] + ";"
                            }
                        }
                        P += "\""
                    }
                }
            } else {
                if (O == "cls") {
                    P += " class=\"" + T["cls"] + "\""
                } else {
                    if (O == "htmlFor") {
                        P += " for=\"" + T["htmlFor"] + "\""
                    } else {
                        P += " " + O + "=\"" + T[O] + "\""
                    }
                }
            }
        }
        if (F.test(T.tag)) {
            P += "/>"
        } else {
            P += ">";
            var U = T.children || T.cn;
            if (U) {
                if (U instanceof Array) {
                    for (var Q = 0, N = U.length; Q < N; Q++) {
                        P += A(U[Q], P)
                    }
                } else {
                    P += A(U, P)
                }
            }
            if (T.html) {
                P += T.html
            }
            P += "</" + T.tag + ">"
        }
        return P
    };
    var M = function (T, P) {
        var S = document.createElement(T.tag || "div");
        var Q = S.setAttribute ? true : false;
        for (var O in T) {
            if (O == "tag" || O == "children" || O == "cn" || O == "html" || O == "style" || typeof T[O] == "function") {
                continue
            }
            if (O == "cls") {
                S.className = T["cls"]
            } else {
                if (Q) {
                    S.setAttribute(O, T[O])
                } else {
                    S[O] = T[O]
                }
            }
        }
        Ext.DomHelper.applyStyles(S, T.style);
        var U = T.children || T.cn;
        if (U) {
            if (U instanceof Array) {
                for (var R = 0, N = U.length; R < N; R++) {
                    M(U[R], S)
                }
            } else {
                M(U, S)
            }
        }
        if (T.html) {
            S.innerHTML = T.html
        }
        if (P) {
            P.appendChild(S)
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