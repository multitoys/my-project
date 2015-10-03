/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.dd.PanelProxy = function (A, B) {
    this.panel = A;
    this.id = this.panel.id + "-ddproxy";
    Ext.apply(this, B)
};
Ext.dd.PanelProxy.prototype = {
    insertProxy: true,
    setStatus: Ext.emptyFn,
    reset: Ext.emptyFn,
    update: Ext.emptyFn,
    stop: Ext.emptyFn,
    sync: Ext.emptyFn,
    getEl: function () {
        return this.ghost
    },
    getGhost: function () {
        return this.ghost
    },
    getProxy: function () {
        return this.proxy
    },
    hide: function () {
        if (this.ghost) {
            if (this.proxy) {
                this.proxy.remove();
                delete this.proxy
            }
            this.panel.el.dom.style.display = "";
            this.ghost.remove();
            delete this.ghost
        }
    },
    show: function () {
        if (!this.ghost) {
            this.ghost = this.panel.createGhost(undefined, undefined, Ext.getBody());
            this.ghost.setXY(this.panel.el.getXY());
            if (this.insertProxy) {
                this.proxy = this.panel.el.insertSibling({cls: "x-panel-dd-spacer"});
                this.proxy.setSize(this.panel.getSize())
            }
            this.panel.el.dom.style.display = "none"
        }
    },
    repair: function (B, C, A) {
        this.hide();
        if (typeof C == "function") {
            C.call(A || this)
        }
    },
    moveProxy: function (A, B) {
        if (this.proxy) {
            A.insertBefore(this.proxy.dom, B)
        }
    }
};
Ext.Panel.DD = function (B, A) {
    this.panel = B;
    this.dragData = {panel: B};
    this.proxy = new Ext.dd.PanelProxy(B, A);
    Ext.Panel.DD.superclass.constructor.call(this, B.el, A);
    this.setHandleElId(B.header.id);
    B.header.setStyle("cursor", "move");
    this.scroll = false
};
Ext.extend(Ext.Panel.DD, Ext.dd.DragSource, {
    showFrame: Ext.emptyFn,
    startDrag: Ext.emptyFn,
    b4StartDrag: function (A, B) {
        this.proxy.show()
    },
    b4MouseDown: function (B) {
        var A = B.getPageX();
        var C = B.getPageY();
        this.autoOffset(A, C)
    },
    onInitDrag: function (A, B) {
        this.onStartDrag(A, B);
        return true
    },
    createFrame: Ext.emptyFn,
    getDragEl: function (A) {
        return this.proxy.ghost.dom
    },
    endDrag: function (A) {
        this.proxy.hide();
        this.panel.saveState()
    },
    autoOffset: function (A, B) {
        A -= this.startPageX;
        B -= this.startPageY;
        this.setDelta(A, B)
    }
});