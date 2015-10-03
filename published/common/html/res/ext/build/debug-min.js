/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.debug = {};
(function () {
    var B;

    function A() {
        var E = new Ext.debug.ScriptsPanel();
        var G = new Ext.debug.LogPanel();
        var C = new Ext.debug.DomTree();
        var D = new Ext.TabPanel({
            activeTab: 0,
            border: false,
            tabPosition: "bottom",
            items: [{title: "Debug Console", layout: "border", items: [G, E]}, {
                title: "DOM Inspector",
                layout: "border",
                items: [C]
            }]
        });
        B = new Ext.Panel({
            id: "x-debug-browser",
            title: "Console",
            collapsible: true,
            animCollapse: false,
            style: "position:absolute;left:0;bottom:0;",
            height: 200,
            logView: G,
            layout: "fit",
            tools: [{
                id: "close", handler: function () {
                    B.destroy();
                    B = null;
                    Ext.EventManager.removeResizeListener(F)
                }
            }],
            items: D
        });
        B.render(document.body);
        B.resizer = new Ext.Resizable(B.el, {
            minHeight: 50,
            handles: "n",
            pinned: true,
            transparent: true,
            resizeElement: function () {
                var H = this.proxy.getBox();
                this.proxy.hide();
                B.setHeight(H.height);
                return H
            }
        });
        function F() {
            B.setWidth(Ext.getBody().getViewSize().width)
        }

        Ext.EventManager.onWindowResize(F);
        F()
    }

    Ext.apply(Ext, {
        log: function () {
            if (!B) {
                A()
            }
            B.logView.log.apply(B.logView, arguments)
        }, logf: function (F, E, C, D) {
            Ext.log(String.format.apply(String, arguments))
        }, dump: function (F) {
            if (typeof F == "string" || typeof F == "number" || typeof F == "undefined" || F instanceof Date) {
                Ext.log(F)
            } else {
                if (!F) {
                    Ext.log("null")
                } else {
                    if (typeof F != "object") {
                        Ext.log("Unknown return type")
                    } else {
                        if (F instanceof Array) {
                            Ext.log("[" + F.join(",") + "]")
                        } else {
                            var C = ["{\n"];
                            for (var D in F) {
                                var G = typeof F[D];
                                if (G != "function" && G != "object") {
                                    C.push(String.format("  {0}: {1},\n", D, F[D]))
                                }
                            }
                            var E = C.join("");
                            if (E.length > 3) {
                                E = E.substr(0, E.length - 2)
                            }
                            Ext.log(E + "\n}")
                        }
                    }
                }
            }
        }, _timers: {}, time: function (C) {
            C = C || "def";
            Ext._timers[C] = new Date().getTime()
        }, timeEnd: function (D, F) {
            var E = new Date().getTime();
            D = D || "def";
            var C = String.format("{0} ms", E - Ext._timers[D]);
            Ext._timers[D] = new Date().getTime();
            if (F !== false) {
                Ext.log("Timer " + (D == "def" ? C : D + ": " + C))
            }
            return C
        }
    })
})();
Ext.debug.ScriptsPanel = Ext.extend(Ext.Panel, {
    id: "x-debug-scripts",
    region: "east",
    minWidth: 200,
    split: true,
    width: 350,
    border: false,
    layout: "anchor",
    style: "border-width:0 0 0 1px;",
    initComponent: function () {
        this.scriptField = new Ext.form.TextArea({anchor: "100% -26", style: "border-width:0;"});
        this.trapBox = new Ext.form.Checkbox({id: "console-trap", boxLabel: "Trap Errors", checked: true});
        this.toolbar = new Ext.Toolbar([{text: "Run", scope: this, handler: this.evalScript}, {
            text: "Clear",
            scope: this,
            handler: this.clear
        }, "->", this.trapBox, " ", " "]);
        this.items = [this.toolbar, this.scriptField];
        Ext.debug.ScriptsPanel.superclass.initComponent.call(this)
    },
    evalScript: function () {
        var s = this.scriptField.getValue();
        if (this.trapBox.getValue()) {
            try {
                var rt = eval(s);
                Ext.dump(rt === undefined ? "(no return)" : rt)
            } catch (e) {
                Ext.log(e.message || e.descript)
            }
        } else {
            var rt = eval(s);
            Ext.dump(rt === undefined ? "(no return)" : rt)
        }
    },
    clear: function () {
        this.scriptField.setValue("");
        this.scriptField.focus()
    }
});
Ext.debug.LogPanel = Ext.extend(Ext.Panel, {
    autoScroll: true,
    region: "center",
    border: false,
    style: "border-width:0 1px 0 0",
    log: function () {
        var A = ["<div style=\"padding:5px !important;border-bottom:1px solid #ccc;\">", Ext.util.Format.htmlEncode(Array.prototype.join.call(arguments, ", ")).replace(/\n/g, "<br />").replace(/\s/g, "&#160;"), "</div>"].join("");
        this.body.insertHtml("beforeend", A);
        this.body.scrollTo("top", 100000)
    },
    clear: function () {
        this.body.update("");
        this.body.dom.scrollTop = 0
    }
});
Ext.debug.DomTree = Ext.extend(Ext.tree.TreePanel, {
    enableDD: false,
    lines: false,
    rootVisible: false,
    animate: false,
    hlColor: "ffff9c",
    autoScroll: true,
    region: "center",
    border: false,
    initComponent: function () {
        Ext.debug.DomTree.superclass.initComponent.call(this);
        var H = false, A;
        var I = /^\s*$/;
        var E = Ext.util.Format.htmlEncode;
        var G = Ext.util.Format.ellipsis;
        var D = /\s?([a-z\-]*)\:([^;]*)(?:[;\s\n\r]*)/gi;

        function B(P) {
            if (!P || P.nodeType != 1 || P == document.body || P == document) {
                return false
            }
            var L = [P], N = P;
            while ((N = N.parentNode) && N.nodeType == 1 && N.tagName.toUpperCase() != "HTML") {
                L.unshift(N)
            }
            var O = A;
            for (var M = 0, J = L.length; M < J; M++) {
                O.expand();
                O = O.findChild("htmlNode", L[M]);
                if (!O) {
                    return false
                }
            }
            O.select();
            var K = O.ui.anchor;
            treeEl.dom.scrollTop = Math.max(0, K.offsetTop - 10);
            O.highlight();
            return true
        }

        function F(K) {
            var J = K.tagName;
            if (K.id) {
                J += "#" + K.id
            } else {
                if (K.className) {
                    J += "." + K.className
                }
            }
            return J
        }

        function C(V, J, S) {
            return;
            if (S && S.unframe) {
                S.unframe()
            }
            var P = {};
            if (J && J.htmlNode) {
                if (frameEl.pressed) {
                    J.frame()
                }
                if (inspecting) {
                    return
                }
                addStyle.enable();
                reload.setDisabled(J.leaf);
                var M = J.htmlNode;
                stylePanel.setTitle(F(M));
                if (H && !showAll.pressed) {
                    var W = M.style ? M.style.cssText : "";
                    if (W) {
                        var K;
                        while ((K = D.exec(W)) != null) {
                            P[K[1].toLowerCase()] = K[2]
                        }
                    }
                } else {
                    if (H) {
                        var T = Ext.debug.cssList;
                        var W = M.style, L = Ext.fly(M);
                        if (W) {
                            for (var N = 0, O = T.length; N < O; N++) {
                                var U = T[N];
                                var R = W[U] || L.getStyle(U);
                                if (R != undefined && R !== null && R !== "") {
                                    P[U] = R
                                }
                            }
                        }
                    } else {
                        for (var Q in M) {
                            var R = M[Q];
                            if ((isNaN(Q + 10)) && R != undefined && R !== null && R !== "" && !(Ext.isGecko && Q[0] == Q[0].toUpperCase())) {
                                P[Q] = R
                            }
                        }
                    }
                }
            } else {
                if (inspecting) {
                    return
                }
                addStyle.disable();
                reload.disabled()
            }
            stylesGrid.setSource(P);
            stylesGrid.treeNode = J;
            stylesGrid.view.fitColumns()
        }

        this.loader = new Ext.tree.TreeLoader();
        this.loader.load = function (O, J) {
            var K = O.htmlNode == document.body;
            var N = O.htmlNode.childNodes;
            for (var L = 0, M; M = N[L]; L++) {
                if (K && M.id == "x-debug-browser") {
                    continue
                }
                if (M.nodeType == 1) {
                    O.appendChild(new Ext.debug.HtmlNode(M))
                } else {
                    if (M.nodeType == 3 && !I.test(M.nodeValue)) {
                        O.appendChild(new Ext.tree.TreeNode({
                            text: "<em>" + G(E(String(M.nodeValue)), 35) + "</em>",
                            cls: "x-tree-noicon"
                        }))
                    }
                }
            }
            J()
        };
        this.root = this.setRootNode(new Ext.tree.TreeNode("Ext"));
        A = this.root.appendChild(new Ext.debug.HtmlNode(document.getElementsByTagName("html")[0]))
    }
});
Ext.debug.HtmlNode = function () {
    var D = Ext.util.Format.htmlEncode;
    var B = Ext.util.Format.ellipsis;
    var A = /^\s*$/;
    var C = [{n: "id", v: "id"}, {n: "className", v: "class"}, {n: "name", v: "name"}, {
        n: "type",
        v: "type"
    }, {n: "src", v: "src"}, {n: "href", v: "href"}];

    function F(J) {
        for (var H = 0, I; I = J.childNodes[H]; H++) {
            if (I.nodeType == 1) {
                return true
            }
        }
        return false
    }

    function E(I, L) {
        var P = I.tagName.toLowerCase();
        var O = "&lt;" + P;
        for (var J = 0, K = C.length; J < K; J++) {
            var M = C[J];
            var N = I[M.n];
            if (N && !A.test(N)) {
                O += " " + M.v + "=&quot;<i>" + D(N) + "</i>&quot;"
            }
        }
        var H = I.style ? I.style.cssText : "";
        if (H) {
            O += " style=&quot;<i>" + D(H.toLowerCase()) + "</i>&quot;"
        }
        if (L && I.childNodes.length > 0) {
            O += "&gt;<em>" + B(D(String(I.innerHTML)), 35) + "</em>&lt;/" + P + "&gt;"
        } else {
            if (L) {
                O += " /&gt;"
            } else {
                O += "&gt;"
            }
        }
        return O
    }

    var G = function (J) {
        var I = !F(J);
        this.htmlNode = J;
        this.tagName = J.tagName.toLowerCase();
        var H = {text: E(J, I), leaf: I, cls: "x-tree-noicon"};
        G.superclass.constructor.call(this, H);
        this.attributes.htmlNode = J;
        if (!I) {
            this.on("expand", this.onExpand, this);
            this.on("collapse", this.onCollapse, this)
        }
    };
    Ext.extend(G, Ext.tree.AsyncTreeNode, {
        cls: "x-tree-noicon", preventHScroll: true, refresh: function (I) {
            var H = !F(this.htmlNode);
            this.setText(E(this.htmlNode, H));
            if (I) {
                Ext.fly(this.ui.textNode).highlight()
            }
        }, onExpand: function () {
            if (!this.closeNode && this.parentNode) {
                this.closeNode = this.parentNode.insertBefore(new Ext.tree.TreeNode({
                    text: "&lt;/" + this.tagName + "&gt;",
                    cls: "x-tree-noicon"
                }), this.nextSibling)
            } else {
                if (this.closeNode) {
                    this.closeNode.ui.show()
                }
            }
        }, onCollapse: function () {
            if (this.closeNode) {
                this.closeNode.ui.hide()
            }
        }, render: function (H) {
            G.superclass.render.call(this, H)
        }, highlightNode: function () {
        }, highlight: function () {
        }, frame: function () {
            this.htmlNode.style.border = "1px solid #0000ff"
        }, unframe: function () {
            this.htmlNode.style.border = ""
        }
    });
    return G
}();