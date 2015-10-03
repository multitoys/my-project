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
Ext.Template = function (E) {
    var B = arguments;
    if (E instanceof Array) {
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
            var isArray = (el instanceof Array);
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
                    D[A[C]] = true
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
    var T, M, I = false;
    var K, S, C, O;
    var L = Ext.lib.Event;
    var N = Ext.lib.Dom;
    var B = function () {
        if (!I) {
            I = true;
            Ext.isReady = true;
            if (M) {
                clearInterval(M)
            }
            if (Ext.isGecko || Ext.isOpera) {
                document.removeEventListener("DOMContentLoaded", B, false)
            }
            if (Ext.isIE) {
                var D = document.getElementById("ie-deferred-loader");
                if (D) {
                    D.onreadystatechange = null;
                    D.parentNode.removeChild(D)
                }
            }
            if (T) {
                T.fire();
                T.clearListeners()
            }
        }
    };
    var A = function () {
        T = new Ext.util.Event();
        if (Ext.isGecko || Ext.isOpera) {
            document.addEventListener("DOMContentLoaded", B, false)
        } else {
            if (Ext.isIE) {
                document.write("<s" + "cript id=\"ie-deferred-loader\" defer=\"defer\" src=\"/" + "/:\"></s" + "cript>");
                var D = document.getElementById("ie-deferred-loader");
                D.onreadystatechange = function () {
                    if (this.readyState == "complete") {
                        B()
                    }
                }
            } else {
                if (Ext.isSafari) {
                    M = setInterval(function () {
                        var E = document.readyState;
                        if (E == "complete") {
                            B()
                        }
                    }, 10)
                }
            }
        }
        L.on(window, "load", B)
    };
    var R = function (E, U) {
        var D = new Ext.util.DelayedTask(E);
        return function (V) {
            V = new Ext.EventObjectImpl(V);
            D.delay(U.buffer, E, null, [V])
        }
    };
    var P = function (V, U, D, E) {
        return function (W) {
            Ext.EventManager.removeListener(U, D, E);
            V(W)
        }
    };
    var F = function (D, E) {
        return function (U) {
            U = new Ext.EventObjectImpl(U);
            setTimeout(function () {
                D(U)
            }, E.delay || 10)
        }
    };
    var J = function (U, E, D, Y, X) {
        var Z = (!D || typeof D == "boolean") ? {} : D;
        Y = Y || Z.fn;
        X = X || Z.scope;
        var W = Ext.getDom(U);
        if (!W) {
            throw"Error listening for \"" + E + "\". Element \"" + U + "\" doesn't exist."
        }
        var V = function (b) {
            b = Ext.EventObject.setEvent(b);
            var a;
            if (Z.delegate) {
                a = b.getTarget(Z.delegate, W);
                if (!a) {
                    return
                }
            } else {
                a = b.target
            }
            if (Z.stopEvent === true) {
                b.stopEvent()
            }
            if (Z.preventDefault === true) {
                b.preventDefault()
            }
            if (Z.stopPropagation === true) {
                b.stopPropagation()
            }
            if (Z.normalized === false) {
                b = b.browserEvent
            }
            Y.call(X || W, b, a, Z)
        };
        if (Z.delay) {
            V = F(V, Z)
        }
        if (Z.single) {
            V = P(V, W, E, Y)
        }
        if (Z.buffer) {
            V = R(V, Z)
        }
        Y._handlers = Y._handlers || [];
        Y._handlers.push([Ext.id(W), E, V]);
        L.on(W, E, V);
        if (E == "mousewheel" && W.addEventListener) {
            W.addEventListener("DOMMouseScroll", V, false);
            L.on(window, "unload", function () {
                W.removeEventListener("DOMMouseScroll", V, false)
            })
        }
        if (E == "mousedown" && W == document) {
            Ext.EventManager.stoppedMouseDownEvent.addListener(V)
        }
        return V
    };
    var G = function (E, U, Z) {
        var D = Ext.id(E), a = Z._handlers, X = Z;
        if (a) {
            for (var V = 0, Y = a.length; V < Y; V++) {
                var W = a[V];
                if (W[0] == D && W[1] == U) {
                    X = W[2];
                    a.splice(V, 1);
                    break
                }
            }
        }
        L.un(E, U, X);
        E = Ext.getDom(E);
        if (U == "mousewheel" && E.addEventListener) {
            E.removeEventListener("DOMMouseScroll", X, false)
        }
        if (U == "mousedown" && E == document) {
            Ext.EventManager.stoppedMouseDownEvent.removeListener(X)
        }
    };
    var H = /^(?:scope|delay|buffer|single|stopEvent|preventDefault|stopPropagation|normalized|args|delegate)$/;
    var Q = {
        addListener: function (U, D, W, V, E) {
            if (typeof D == "object") {
                var Y = D;
                for (var X in Y) {
                    if (H.test(X)) {
                        continue
                    }
                    if (typeof Y[X] == "function") {
                        J(U, X, Y, Y[X], Y.scope)
                    } else {
                        J(U, X, Y[X])
                    }
                }
                return
            }
            return J(U, D, E, W, V)
        }, removeListener: function (E, D, U) {
            return G(E, D, U)
        }, onDocumentReady: function (U, E, D) {
            if (I) {
                T.addListener(U, E, D);
                T.fire();
                T.clearListeners();
                return
            }
            if (!T) {
                A()
            }
            T.addListener(U, E, D)
        }, onWindowResize: function (U, E, D) {
            if (!K) {
                K = new Ext.util.Event();
                S = new Ext.util.DelayedTask(function () {
                    K.fire(N.getViewWidth(), N.getViewHeight())
                });
                L.on(window, "resize", this.fireWindowResize, this)
            }
            K.addListener(U, E, D)
        }, fireWindowResize: function () {
            if (K) {
                if ((Ext.isIE || Ext.isAir) && S) {
                    S.delay(50)
                } else {
                    K.fire(N.getViewWidth(), N.getViewHeight())
                }
            }
        }, onTextResize: function (V, U, D) {
            if (!C) {
                C = new Ext.util.Event();
                var E = new Ext.Element(document.createElement("div"));
                E.dom.className = "x-text-resize";
                E.dom.innerHTML = "X";
                E.appendTo(document.body);
                O = E.dom.offsetHeight;
                setInterval(function () {
                    if (E.dom.offsetHeight != O) {
                        C.fire(O, O = E.dom.offsetHeight)
                    }
                }, this.textResizeInterval)
            }
            C.addListener(V, U, D)
        }, removeResizeListener: function (E, D) {
            if (K) {
                K.removeListener(E, D)
            }
        }, fireResize: function () {
            if (K) {
                K.fire(N.getViewWidth(), N.getViewHeight())
            }
        }, ieDeferSrc: false, textResizeInterval: 50
    };
    Q.on = Q.addListener;
    Q.un = Q.removeListener;
    Q.stoppedMouseDownEvent = new Ext.util.Event();
    return Q
}();
Ext.onReady = Ext.EventManager.onDocumentReady;
Ext.onReady(function () {
    var B = Ext.getBody();
    if (!B) {
        return
    }
    var A = [Ext.isIE ? "ext-ie " + (Ext.isIE6 ? "ext-ie6" : "ext-ie7") : Ext.isGecko ? "ext-gecko" : Ext.isOpera ? "ext-opera" : Ext.isSafari ? "ext-safari" : ""];
    if (Ext.isMac) {
        A.push("ext-mac")
    }
    if (Ext.isLinux) {
        A.push("ext-linux")
    }
    if (Ext.isBorderBox) {
        A.push("ext-border-box")
    }
    if (Ext.isStrict) {
        var C = B.dom.parentNode;
        if (C) {
            C.className += " ext-strict"
        }
    }
    B.addClass(A.join(" "))
});
Ext.EventObject = function () {
    var B = Ext.lib.Event;
    var A = {63234: 37, 63235: 39, 63232: 38, 63233: 40, 63276: 33, 63277: 34, 63272: 46, 63273: 36, 63275: 35};
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
        RETURN: 13,
        ENTER: 13,
        SHIFT: 16,
        CONTROL: 17,
        ESC: 27,
        SPACE: 32,
        PAGEUP: 33,
        PAGEDOWN: 34,
        END: 35,
        HOME: 36,
        LEFT: 37,
        UP: 38,
        RIGHT: 39,
        DOWN: 40,
        DELETE: 46,
        F5: 116,
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
        getTarget: function (E, G, D) {
            var F = Ext.get(this.target);
            return E ? F.findParent(E, G, D) : (D ? F : this.target)
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
        }, query: function (selector, unique) {
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
            if (className instanceof Array) {
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
            if (className instanceof Array) {
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
        }, removeListener: function (eventName, fn) {
            Ext.EventManager.removeListener(this.dom, eventName, fn);
            return this
        }, removeAllListeners: function () {
            E.purgeElement(this.dom);
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
            if (v == "auto" && Ext.isMac && Ext.isGecko) {
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
            el.frameBorder = "no";
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
        }, addClassOnOver: function (className, preventFlicker) {
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
            if (eventName instanceof Array) {
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
            if (el instanceof Array) {
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
            if (typeof key != "object" || key instanceof Array) {
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
            if (typeof x == "object" || x instanceof Array) {
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
                        if (el instanceof Array) {
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
                    E.purgeElement(d)
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
        if (D instanceof Array) {
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
            if (E instanceof Array) {
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
                var I = D.getAttribute("enctype");
                if (E.isUpload || (I && I.toLowerCase() == "multipart/form-data")) {
                    return this.doFormUpload(E, C, B)
                }
                var H = Ext.lib.Ajax.serializeForm(D);
                C = C ? (C + "&" + H) : H
            }
            var J = E.headers;
            if (this.defaultHeaders) {
                J = Ext.apply(J || {}, this.defaultHeaders);
                if (!E.headers) {
                    E.headers = J
                }
            }
            var F = {
                success: this.handleResponse,
                failure: this.handleFailure,
                scope: this,
                argument: {options: E},
                timeout: E.timeout || this.timeout
            };
            var A = E.method || this.method || (C ? "POST" : "GET");
            if (A == "GET" && (this.disableCaching && E.disableCaching !== false) || E.disableCaching === true) {
                B += (B.indexOf("?") != -1 ? "&" : "?") + "_dc=" + (new Date().getTime())
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
            if ((A == "GET" && C) || E.xmlData || E.jsonData) {
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
Ext.Updater = function (B, A) {
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
    this.autoRefreshProcId = null;
    this.refreshDelegate = this.refresh.createDelegate(this);
    this.updateDelegate = this.update.createDelegate(this);
    this.formUpdateDelegate = this.formUpdate.createDelegate(this);
    if (!this.renderer) {
        this.renderer = new Ext.Updater.BasicRenderer()
    }
    Ext.Updater.superclass.constructor.call(this)
};
Ext.extend(Ext.Updater, Ext.util.Observable, {
    getEl: function () {
        return this.el
    }, update: function (B, F, H, D) {
        if (this.fireEvent("beforeupdate", this.el, B, F) !== false) {
            var G = this.method, A, C;
            if (typeof B == "object") {
                A = B;
                B = A.url;
                F = F || A.params;
                H = H || A.callback;
                D = D || A.discardUrl;
                C = A.scope;
                if (typeof A.method != "undefined") {
                    G = A.method
                }
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
            G = G || (F ? "POST" : "GET");
            if (G == "GET") {
                B = this.prepareUrl(B)
            }
            var E = Ext.apply(A || {}, {
                url: B,
                params: (typeof F == "function" && C) ? F.createDelegate(C) : F,
                success: this.processSuccess,
                failure: this.processFailure,
                scope: this,
                callback: undefined,
                timeout: (this.timeout * 1000),
                argument: {"options": A, "url": B, "form": null, "callback": H, "scope": C || window, "params": F}
            });
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
    }, prepareUrl: function (B) {
        if (this.disableCaching) {
            var A = "_dc=" + (new Date().getTime());
            if (B.indexOf("?") !== -1) {
                B += "&" + A
            } else {
                B += "?" + A
            }
        }
        return B
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
Ext.Updater.update = Ext.Updater.updateElement;
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
