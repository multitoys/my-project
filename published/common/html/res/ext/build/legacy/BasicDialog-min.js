/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.BasicDialog = function (C, B) {
    this.el = Ext.get(C);
    var D = Ext.DomHelper;
    if (!this.el && B && B.autoCreate) {
        if (typeof B.autoCreate == "object") {
            if (!B.autoCreate.id) {
                B.autoCreate.id = C
            }
            this.el = D.append(document.body, B.autoCreate, true)
        } else {
            this.el = D.append(document.body, {tag: "div", id: C, style: "visibility:hidden;"}, true)
        }
    }
    C = this.el;
    C.setDisplayed(true);
    C.hide = this.hideAction;
    this.id = C.id;
    C.addClass("x-dlg");
    Ext.apply(this, B);
    this.proxy = C.createProxy("x-dlg-proxy");
    this.proxy.hide = this.hideAction;
    this.proxy.setOpacity(0.5);
    this.proxy.hide();
    if (B.width) {
        C.setWidth(B.width)
    }
    if (B.height) {
        C.setHeight(B.height)
    }
    this.size = C.getSize();
    if (typeof B.x != "undefined" && typeof B.y != "undefined") {
        this.xy = [B.x, B.y]
    } else {
        this.xy = C.getCenterXY(true)
    }
    this.header = C.child("> .x-dlg-hd");
    this.body = C.child("> .x-dlg-bd");
    this.footer = C.child("> .x-dlg-ft");
    if (!this.header) {
        this.header = C.createChild({tag: "div", cls: "x-dlg-hd", html: "&#160;"}, this.body ? this.body.dom : null)
    }
    if (!this.body) {
        this.body = C.createChild({tag: "div", cls: "x-dlg-bd"})
    }
    this.header.unselectable();
    if (this.title) {
        this.header.update(this.title)
    }
    this.focusEl = C.createChild({tag: "a", href: "#", cls: "x-dlg-focus", tabIndex: "-1"});
    this.focusEl.swallowEvent("click", true);
    this.header.wrap({cls: "x-dlg-hd-right"}).wrap({cls: "x-dlg-hd-left"}, true);
    this.bwrap = this.body.wrap({tag: "div", cls: "x-dlg-dlg-body"});
    if (this.footer) {
        this.bwrap.dom.appendChild(this.footer.dom)
    }
    this.bg = this.el.createChild({
        tag: "div",
        cls: "x-dlg-bg",
        html: "<div class=\"x-dlg-bg-left\"><div class=\"x-dlg-bg-right\"><div class=\"x-dlg-bg-center\">&#160;</div></div></div>"
    });
    this.centerBg = this.bg.child("div.x-dlg-bg-center");
    if (this.autoScroll !== false && !this.autoTabs) {
        this.body.setStyle("overflow", "auto")
    }
    this.toolbox = this.el.createChild({cls: "x-dlg-toolbox"});
    if (this.closable !== false) {
        this.el.addClass("x-dlg-closable");
        this.close = this.toolbox.createChild({cls: "x-dlg-close"});
        this.close.on("click", this.closeClick, this);
        this.close.addClassOnOver("x-dlg-close-over")
    }
    if (this.collapsible !== false) {
        this.collapseBtn = this.toolbox.createChild({cls: "x-dlg-collapse"});
        this.collapseBtn.on("click", this.collapseClick, this);
        this.collapseBtn.addClassOnOver("x-dlg-collapse-over");
        this.header.on("dblclick", this.collapseClick, this)
    }
    if (this.resizable !== false) {
        this.el.addClass("x-dlg-resizable");
        this.resizer = new Ext.Resizable(C, {
            minWidth: this.minWidth || 80,
            minHeight: this.minHeight || 80,
            handles: this.resizeHandles || "all",
            pinned: true
        });
        this.resizer.on("beforeresize", this.beforeResize, this);
        this.resizer.on("resize", this.onResize, this)
    }
    if (this.draggable !== false) {
        C.addClass("x-dlg-draggable");
        if (!this.proxyDrag) {
            var A = new Ext.dd.DD(C.dom.id, "WindowDrag")
        } else {
            var A = new Ext.dd.DDProxy(C.dom.id, "WindowDrag", {dragElId: this.proxy.id})
        }
        A.setHandleElId(this.header.id);
        A.endDrag = this.endMove.createDelegate(this);
        A.startDrag = this.startMove.createDelegate(this);
        A.onDrag = this.onDrag.createDelegate(this);
        A.scroll = false;
        this.dd = A
    }
    if (this.modal) {
        this.mask = D.append(document.body, {tag: "div", cls: "x-dlg-mask"}, true);
        this.mask.enableDisplayMode("block");
        this.mask.hide();
        this.el.addClass("x-dlg-modal")
    }
    if (this.shadow) {
        this.shadow = new Ext.Shadow({
            mode: typeof this.shadow == "string" ? this.shadow : "sides",
            offset: this.shadowOffset
        })
    } else {
        this.shadowOffset = 0
    }
    if (Ext.useShims && this.shim !== false) {
        this.shim = this.el.createShim();
        this.shim.hide = this.hideAction;
        this.shim.hide()
    } else {
        this.shim = false
    }
    if (this.autoTabs) {
        this.initTabs()
    }
    this.addEvents({
        "keydown": true,
        "move": true,
        "resize": true,
        "beforehide": true,
        "hide": true,
        "beforeshow": true,
        "show": true
    });
    C.on("keydown", this.onKeyDown, this);
    C.on("mousedown", this.toFront, this);
    Ext.EventManager.onWindowResize(this.adjustViewport, this, true);
    this.el.hide();
    Ext.DialogManager.register(this);
    Ext.BasicDialog.superclass.constructor.call(this)
};
Ext.extend(Ext.BasicDialog, Ext.util.Observable, {
    shadowOffset: Ext.isIE ? 6 : 5,
    minHeight: 80,
    minWidth: 200,
    minButtonWidth: 75,
    defaultButton: null,
    buttonAlign: "right",
    tabTag: "div",
    firstShow: true,
    setTitle: function (A) {
        this.header.update(A);
        return this
    },
    closeClick: function () {
        this.hide()
    },
    collapseClick: function () {
        this[this.collapsed ? "expand" : "collapse"]()
    },
    collapse: function () {
        if (!this.collapsed) {
            this.collapsed = true;
            this.el.addClass("x-dlg-collapsed");
            this.restoreHeight = this.el.getHeight();
            this.resizeTo(this.el.getWidth(), this.header.getHeight())
        }
    },
    expand: function () {
        if (this.collapsed) {
            this.collapsed = false;
            this.el.removeClass("x-dlg-collapsed");
            this.resizeTo(this.el.getWidth(), this.restoreHeight)
        }
    },
    initTabs: function () {
        var A = this.getTabs();
        while (A.getTab(0)) {
            A.removeTab(0)
        }
        this.el.select(this.tabTag + ".x-dlg-tab").each(function (B) {
            var C = B.dom;
            A.addTab(Ext.id(C), C.title);
            C.title = ""
        });
        A.activate(0);
        return A
    },
    beforeResize: function () {
        this.resizer.minHeight = Math.max(this.minHeight, this.getHeaderFooterHeight(true) + 40)
    },
    onResize: function () {
        this.refreshSize();
        this.syncBodyHeight();
        this.adjustAssets();
        this.focus();
        this.fireEvent("resize", this, this.size.width, this.size.height)
    },
    onKeyDown: function (A) {
        if (this.isVisible()) {
            this.fireEvent("keydown", this, A)
        }
    },
    resizeTo: function (B, A) {
        this.el.setSize(B, A);
        this.size = {width: B, height: A};
        this.syncBodyHeight();
        if (this.fixedcenter) {
            this.center()
        }
        if (this.isVisible()) {
            this.constrainXY();
            this.adjustAssets()
        }
        this.fireEvent("resize", this, B, A);
        return this
    },
    setContentSize: function (A, B) {
        B += this.getHeaderFooterHeight() + this.body.getMargins("tb");
        A += this.body.getMargins("lr") + this.bwrap.getMargins("lr") + this.centerBg.getPadding("lr");
        B += this.body.getPadding("tb") + this.bwrap.getBorderWidth("tb") + this.body.getBorderWidth("tb") + this.el.getBorderWidth("tb");
        A += this.body.getPadding("lr") + this.bwrap.getBorderWidth("lr") + this.body.getBorderWidth("lr") + this.bwrap.getPadding("lr") + this.el.getBorderWidth("lr");
        if (this.tabs) {
            B += this.tabs.stripWrap.getHeight() + this.tabs.bodyEl.getMargins("tb") + this.tabs.bodyEl.getPadding("tb");
            A += this.tabs.bodyEl.getMargins("lr") + this.tabs.bodyEl.getPadding("lr")
        }
        this.resizeTo(A, B);
        return this
    },
    addKeyListener: function (B, E, D) {
        var H, A, F, G;
        if (typeof B == "object" && !(B instanceof Array)) {
            H = B["key"];
            A = B["shift"];
            F = B["ctrl"];
            G = B["alt"]
        } else {
            H = B
        }
        var C = function (M, L) {
            if ((!A || L.shiftKey) && (!F || L.ctrlKey) && (!G || L.altKey)) {
                var J = L.getKey();
                if (H instanceof Array) {
                    for (var K = 0, I = H.length; K < I; K++) {
                        if (H[K] == J) {
                            E.call(D || window, M, J, L);
                            return
                        }
                    }
                } else {
                    if (J == H) {
                        E.call(D || window, M, J, L)
                    }
                }
            }
        };
        this.on("keydown", C);
        return this
    },
    getTabs: function () {
        if (!this.tabs) {
            this.el.addClass("x-dlg-auto-tabs");
            this.body.addClass(this.tabPosition == "bottom" ? "x-tabs-bottom" : "x-tabs-top");
            this.tabs = new Ext.TabPanel(this.body.dom, this.tabPosition == "bottom")
        }
        return this.tabs
    },
    addButton: function (B, F, E) {
        var G = Ext.DomHelper;
        if (!this.footer) {
            this.footer = G.append(this.bwrap, {tag: "div", cls: "x-dlg-ft"}, true)
        }
        if (!this.btnContainer) {
            var A = this.footer.createChild({
                cls: "x-dlg-btns x-dlg-btns-" + this.buttonAlign,
                html: "<table cellspacing=\"0\"><tbody><tr></tr></tbody></table><div class=\"x-clear\"></div>"
            }, null, true);
            this.btnContainer = A.firstChild.firstChild.firstChild
        }
        var D = {handler: F, scope: E, minWidth: this.minButtonWidth, hideParent: true};
        if (typeof B == "string") {
            D.text = B
        } else {
            if (B.tag) {
                D.dhconfig = B
            } else {
                Ext.apply(D, B)
            }
        }
        var C = new Ext.Button(D);
        C.render(this.btnContainer.appendChild(document.createElement("td")));
        this.syncBodyHeight();
        if (!this.buttons) {
            this.buttons = []
        }
        this.buttons.push(C);
        return C
    },
    setDefaultButton: function (A) {
        this.defaultButton = A;
        return this
    },
    getHeaderFooterHeight: function (C) {
        var A = 0;
        if (this.header) {
            A += this.header.getHeight()
        }
        if (this.footer) {
            var B = this.footer.getMargins();
            A += (this.footer.getHeight() + B.top + B.bottom)
        }
        A += this.bwrap.getPadding("tb") + this.bwrap.getBorderWidth("tb");
        A += this.centerBg.getPadding("tb");
        return A
    },
    syncBodyHeight: function () {
        var E = this.body, B = this.centerBg, F = this.bwrap;
        var A = this.size.height - this.getHeaderFooterHeight(false);
        E.setHeight(A - E.getMargins("tb"));
        var C = this.header.getHeight();
        var D = this.size.height - C;
        B.setHeight(D);
        F.setLeftTop(B.getPadding("l"), C + B.getPadding("t"));
        F.setHeight(D - B.getPadding("tb"));
        F.setWidth(this.el.getWidth(true) - B.getPadding("lr"));
        E.setWidth(F.getWidth(true));
        if (this.tabs) {
            this.tabs.syncHeight();
            if (Ext.isIE) {
                this.tabs.el.repaint()
            }
        }
    },
    restoreState: function () {
        var A = Ext.state.Manager.get(this.stateId || (this.el.id + "-state"));
        if (A && A.width) {
            this.xy = [A.x, A.y];
            this.resizeTo(A.width, A.height)
        }
        return this
    },
    beforeShow: function () {
        this.expand();
        if (this.fixedcenter) {
            this.xy = this.el.getCenterXY(true)
        }
        if (this.modal) {
            Ext.getBody().addClass("x-body-masked");
            this.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true));
            this.mask.show()
        }
        this.constrainXY()
    },
    animShow: function () {
        var A = Ext.get(this.animateTarget, true).getBox();
        this.proxy.setSize(A.width, A.height);
        this.proxy.setLocation(A.x, A.y);
        this.proxy.show();
        this.proxy.setBounds(this.xy[0], this.xy[1], this.size.width, this.size.height, true, 0.35, this.showEl.createDelegate(this))
    },
    show: function (A) {
        if (this.fireEvent("beforeshow", this) === false) {
            return
        }
        if (this.syncHeightBeforeShow) {
            this.syncBodyHeight()
        } else {
            if (this.firstShow) {
                this.firstShow = false;
                this.syncBodyHeight()
            }
        }
        this.animateTarget = A || this.animateTarget;
        if (!this.el.isVisible()) {
            this.beforeShow();
            if (this.animateTarget) {
                this.animShow()
            } else {
                this.showEl()
            }
        }
        return this
    },
    showEl: function () {
        this.proxy.hide();
        this.el.setXY(this.xy);
        this.el.show();
        this.adjustAssets(true);
        this.toFront();
        this.focus();
        if (Ext.isIE) {
            this.el.repaint()
        }
        this.fireEvent("show", this)
    },
    focus: function () {
        if (this.defaultButton) {
            this.defaultButton.focus()
        } else {
            this.focusEl.focus()
        }
    },
    constrainXY: function () {
        if (this.constraintoviewport !== false) {
            if (!this.viewSize) {
                if (this.container) {
                    var E = this.container.getSize();
                    this.viewSize = [E.width, E.height]
                } else {
                    this.viewSize = [Ext.lib.Dom.getViewWidth(), Ext.lib.Dom.getViewHeight()]
                }
            }
            var E = Ext.get(this.container || document).getScroll();
            var A = this.xy[0], H = this.xy[1];
            var B = this.size.width, D = this.size.height;
            var F = this.viewSize[0], G = this.viewSize[1];
            var C = false;
            if (A + B > F + E.left) {
                A = F - B;
                C = true
            }
            if (H + D > G + E.top) {
                H = G - D;
                C = true
            }
            if (A < E.left) {
                A = E.left;
                C = true
            }
            if (H < E.top) {
                H = E.top;
                C = true
            }
            if (C) {
                this.xy = [A, H];
                if (this.isVisible()) {
                    this.el.setLocation(A, H);
                    this.adjustAssets()
                }
            }
        }
    },
    onDrag: function () {
        if (!this.proxyDrag) {
            this.xy = this.el.getXY();
            this.adjustAssets()
        }
    },
    adjustAssets: function (D) {
        var A = this.xy[0], E = this.xy[1];
        var B = this.size.width, C = this.size.height;
        if (D === true) {
            if (this.shadow) {
                this.shadow.show(this.el)
            }
            if (this.shim) {
                this.shim.show()
            }
        }
        if (this.shadow && this.shadow.isVisible()) {
            this.shadow.show(this.el)
        }
        if (this.shim && this.shim.isVisible()) {
            this.shim.setBounds(A, E, B, C)
        }
    },
    adjustViewport: function (A, B) {
        if (!A || !B) {
            A = Ext.lib.Dom.getViewWidth();
            B = Ext.lib.Dom.getViewHeight()
        }
        this.viewSize = [A, B];
        if (this.modal && this.mask.isVisible()) {
            this.mask.setSize(A, B);
            this.mask.setSize(Ext.lib.Dom.getViewWidth(true), Ext.lib.Dom.getViewHeight(true))
        }
        if (this.isVisible()) {
            this.constrainXY()
        }
    },
    destroy: function (C) {
        if (this.isVisible()) {
            this.animateTarget = null;
            this.hide()
        }
        Ext.EventManager.removeResizeListener(this.adjustViewport, this);
        if (this.tabs) {
            this.tabs.destroy(C)
        }
        Ext.destroy(this.shim, this.proxy, this.resizer, this.close, this.mask);
        if (this.dd) {
            this.dd.unreg()
        }
        if (this.buttons) {
            for (var B = 0, A = this.buttons.length; B < A; B++) {
                this.buttons[B].destroy()
            }
        }
        this.el.removeAllListeners();
        if (C === true) {
            this.el.update("");
            this.el.remove()
        }
        Ext.DialogManager.unregister(this)
    },
    startMove: function () {
        if (this.proxyDrag) {
            this.proxy.show()
        }
        if (this.constraintoviewport !== false) {
            this.dd.constrainTo(document.body, {right: this.shadowOffset, bottom: this.shadowOffset})
        }
    },
    endMove: function () {
        if (!this.proxyDrag) {
            Ext.dd.DD.prototype.endDrag.apply(this.dd, arguments)
        } else {
            Ext.dd.DDProxy.prototype.endDrag.apply(this.dd, arguments);
            this.proxy.hide()
        }
        this.refreshSize();
        this.adjustAssets();
        this.focus();
        this.fireEvent("move", this, this.xy[0], this.xy[1])
    },
    toFront: function () {
        Ext.DialogManager.bringToFront(this);
        return this
    },
    toBack: function () {
        Ext.DialogManager.sendToBack(this);
        return this
    },
    center: function () {
        var A = this.el.getCenterXY(true);
        this.moveTo(A[0], A[1]);
        return this
    },
    moveTo: function (A, B) {
        this.xy = [A, B];
        if (this.isVisible()) {
            this.el.setXY(this.xy);
            this.adjustAssets()
        }
        return this
    },
    alignTo: function (B, A, C) {
        this.xy = this.el.getAlignToXY(B, A, C);
        if (this.isVisible()) {
            this.el.setXY(this.xy);
            this.adjustAssets()
        }
        return this
    },
    anchorTo: function (C, F, D, B) {
        var E = function () {
            this.alignTo(C, F, D)
        };
        Ext.EventManager.onWindowResize(E, this);
        var A = typeof B;
        if (A != "undefined") {
            Ext.EventManager.on(window, "scroll", E, this, {buffer: A == "number" ? B : 50})
        }
        E.call(this);
        return this
    },
    isVisible: function () {
        return this.el.isVisible()
    },
    animHide: function (B) {
        var A = Ext.get(this.animateTarget).getBox();
        this.proxy.show();
        this.proxy.setBounds(this.xy[0], this.xy[1], this.size.width, this.size.height);
        this.el.hide();
        this.proxy.setBounds(A.x, A.y, A.width, A.height, true, 0.35, this.hideEl.createDelegate(this, [B]))
    },
    hide: function (A) {
        if (this.fireEvent("beforehide", this) === false) {
            return
        }
        if (this.shadow) {
            this.shadow.hide()
        }
        if (this.shim) {
            this.shim.hide()
        }
        if (this.animateTarget) {
            this.animHide(A)
        } else {
            this.el.hide();
            this.hideEl(A)
        }
        return this
    },
    hideEl: function (A) {
        this.proxy.hide();
        if (this.modal) {
            this.mask.hide();
            Ext.getBody().removeClass("x-body-masked")
        }
        this.fireEvent("hide", this);
        if (typeof A == "function") {
            A()
        }
    },
    hideAction: function () {
        this.setLeft("-10000px");
        this.setTop("-10000px");
        this.setStyle("visibility", "hidden")
    },
    refreshSize: function () {
        this.size = this.el.getSize();
        this.xy = this.el.getXY();
        Ext.state.Manager.set(this.stateId || this.el.id + "-state", this.el.getBox())
    },
    setZIndex: function (A) {
        if (this.modal) {
            this.mask.setStyle("z-index", A)
        }
        if (this.shim) {
            this.shim.setStyle("z-index", ++A)
        }
        if (this.shadow) {
            this.shadow.setZIndex(++A)
        }
        this.el.setStyle("z-index", ++A);
        if (this.proxy) {
            this.proxy.setStyle("z-index", ++A)
        }
        if (this.resizer) {
            this.resizer.proxy.setStyle("z-index", ++A)
        }
        this.lastZIndex = A
    },
    getEl: function () {
        return this.el
    }
});
Ext.DialogManager = function () {
    var D = {};
    var B = [];
    var C = null;
    var A = function (G, F) {
        return (!G._lastAccess || G._lastAccess < F._lastAccess) ? -1 : 1
    };
    var E = function () {
        B.sort(A);
        var G = Ext.DialogManager.zseed;
        for (var H = 0, F = B.length; H < F; H++) {
            var I = B[H];
            if (I) {
                I.setZIndex(G + (H * 10))
            }
        }
    };
    return {
        zseed: 9000, register: function (F) {
            D[F.id] = F;
            B.push(F)
        }, unregister: function (H) {
            delete D[H.id];
            if (!B.indexOf) {
                for (var G = 0, F = B.length; G < F; G++) {
                    if (B[G] == H) {
                        B.splice(G, 1);
                        return
                    }
                }
            } else {
                var G = B.indexOf(H);
                if (G != -1) {
                    B.splice(G, 1)
                }
            }
        }, get: function (F) {
            return typeof F == "object" ? F : D[F]
        }, bringToFront: function (F) {
            F = this.get(F);
            if (F != C) {
                C = F;
                F._lastAccess = new Date().getTime();
                E()
            }
            return F
        }, sendToBack: function (F) {
            F = this.get(F);
            F._lastAccess = -(new Date().getTime());
            E();
            return F
        }, hideAll: function () {
            for (var F in D) {
                if (D[F] && typeof D[F] != "function" && D[F].isVisible()) {
                    D[F].hide()
                }
            }
        }
    }
}();
Ext.LayoutDialog = function (B, A) {
    A.autoTabs = false;
    Ext.LayoutDialog.superclass.constructor.call(this, B, A);
    this.body.setStyle({overflow: "hidden", position: "relative"});
    this.layout = new Ext.BorderLayout(this.body.dom, A);
    this.layout.monitorWindowResize = false;
    this.el.addClass("x-dlg-auto-layout");
    this.center = Ext.BasicDialog.prototype.center;
    this.on("show", this.layout.layout, this.layout, true)
};
Ext.extend(Ext.LayoutDialog, Ext.BasicDialog, {
    endUpdate: function () {
        this.layout.endUpdate()
    }, beginUpdate: function () {
        this.layout.beginUpdate()
    }, getLayout: function () {
        return this.layout
    }, showEl: function () {
        Ext.LayoutDialog.superclass.showEl.apply(this, arguments);
        if (Ext.isIE7) {
            this.layout.layout()
        }
    }, syncBodyHeight: function () {
        Ext.LayoutDialog.superclass.syncBodyHeight.call(this);
        if (this.layout) {
            this.layout.layout()
        }
    }
});