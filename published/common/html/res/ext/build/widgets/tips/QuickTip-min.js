/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.QuickTip = Ext.extend(Ext.ToolTip, {
    interceptTitles: false,
    tagConfig: {
        namespace: "ext",
        attribute: "qtip",
        width: "qwidth",
        target: "target",
        title: "qtitle",
        hide: "hide",
        cls: "qclass",
        align: "qalign"
    },
    initComponent: function () {
        this.target = this.target || Ext.getDoc();
        this.targets = this.targets || {};
        Ext.QuickTip.superclass.initComponent.call(this)
    },
    register: function (D) {
        var F = D instanceof Array ? D : arguments;
        for (var E = 0, A = F.length; E < A; E++) {
            var H = F[E];
            var G = H.target;
            if (G) {
                if (G instanceof Array) {
                    for (var C = 0, B = G.length; C < B; C++) {
                        this.targets[Ext.id(G[C])] = H
                    }
                } else {
                    this.targets[Ext.id(G)] = H
                }
            }
        }
    },
    unregister: function (A) {
        delete this.targets[Ext.id(A)]
    },
    onTargetOver: function (G) {
        if (this.disabled) {
            return
        }
        this.targetXY = G.getXY();
        var C = G.getTarget();
        if (!C || C.nodeType !== 1 || C == document || C == document.body) {
            return
        }
        if (this.activeTarget && C == this.activeTarget.el) {
            this.clearTimer("hide");
            this.show();
            return
        }
        if (C && this.targets[C.id]) {
            this.activeTarget = this.targets[C.id];
            this.activeTarget.el = C;
            this.delayShow();
            return
        }
        var E, F = Ext.fly(C), B = this.tagConfig;
        var D = B.namespace;
        if (this.interceptTitles && C.title) {
            E = C.title;
            C.qtip = E;
            C.removeAttribute("title");
            G.preventDefault()
        } else {
            E = C.qtip || F.getAttributeNS(D, B.attribute)
        }
        if (E) {
            var A = F.getAttributeNS(D, B.hide);
            this.activeTarget = {
                el: C,
                text: E,
                width: F.getAttributeNS(D, B.width),
                autoHide: A != "user" && A !== "false",
                title: F.getAttributeNS(D, B.title),
                cls: F.getAttributeNS(D, B.cls),
                align: F.getAttributeNS(D, B.align)
            };
            this.delayShow()
        }
    },
    onTargetOut: function (A) {
        this.clearTimer("show");
        if (this.autoHide !== false) {
            this.delayHide()
        }
    },
    showAt: function (B) {
        var A = this.activeTarget;
        if (A) {
            if (!this.rendered) {
                this.render(Ext.getBody());
                this.activeTarget = A
            }
            if (A.width) {
                this.setWidth(A.width);
                this.body.setWidth(this.adjustBodyWidth(A.width - this.getFrameWidth()));
                this.measureWidth = false
            } else {
                this.measureWidth = true
            }
            this.setTitle(A.title || "");
            this.body.update(A.text);
            this.autoHide = A.autoHide;
            this.dismissDelay = A.dismissDelay || this.dismissDelay;
            if (this.lastCls) {
                this.el.removeClass(this.lastCls);
                delete this.lastCls
            }
            if (A.cls) {
                this.el.addClass(A.cls);
                this.lastCls = A.cls
            }
            if (A.align) {
                B = this.el.getAlignToXY(A.el, A.align);
                this.constrainPosition = false
            } else {
                this.constrainPosition = true
            }
        }
        Ext.QuickTip.superclass.showAt.call(this, B)
    },
    hide: function () {
        delete this.activeTarget;
        Ext.QuickTip.superclass.hide.call(this)
    }
});