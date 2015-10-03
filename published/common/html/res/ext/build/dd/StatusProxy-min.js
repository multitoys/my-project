/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.dd.StatusProxy = function (A) {
    Ext.apply(this, A);
    this.id = this.id || Ext.id();
    this.el = new Ext.Layer({
        dh: {
            id: this.id,
            tag: "div",
            cls: "x-dd-drag-proxy " + this.dropNotAllowed,
            children: [{tag: "div", cls: "x-dd-drop-icon"}, {tag: "div", cls: "x-dd-drag-ghost"}]
        }, shadow: !A || A.shadow !== false
    });
    this.ghost = Ext.get(this.el.dom.childNodes[1]);
    this.dropStatus = this.dropNotAllowed
};
Ext.dd.StatusProxy.prototype = {
    dropAllowed: "x-dd-drop-ok",
    dropNotAllowed: "x-dd-drop-nodrop",
    setStatus: function (A) {
        A = A || this.dropNotAllowed;
        if (this.dropStatus != A) {
            this.el.replaceClass(this.dropStatus, A);
            this.dropStatus = A
        }
    },
    reset: function (A) {
        this.el.dom.className = "x-dd-drag-proxy " + this.dropNotAllowed;
        this.dropStatus = this.dropNotAllowed;
        if (A) {
            this.ghost.update("")
        }
    },
    update: function (A) {
        if (typeof A == "string") {
            this.ghost.update(A)
        } else {
            this.ghost.update("");
            A.style.margin = "0";
            this.ghost.dom.appendChild(A)
        }
    },
    getEl: function () {
        return this.el
    },
    getGhost: function () {
        return this.ghost
    },
    hide: function (A) {
        this.el.hide();
        if (A) {
            this.reset(true)
        }
    },
    stop: function () {
        if (this.anim && this.anim.isAnimated && this.anim.isAnimated()) {
            this.anim.stop()
        }
    },
    show: function () {
        this.el.show()
    },
    sync: function () {
        this.el.sync()
    },
    repair: function (B, C, A) {
        this.callback = C;
        this.scope = A;
        if (B && this.animRepair !== false) {
            this.el.addClass("x-dd-drag-repair");
            this.el.hideUnders(true);
            this.anim = this.el.shift({
                duration: this.repairDuration || 0.5,
                easing: "easeOut",
                xy: B,
                stopFx: true,
                callback: this.afterRepair,
                scope: this
            })
        } else {
            this.afterRepair()
        }
    },
    afterRepair: function () {
        this.hide(true);
        if (typeof this.callback == "function") {
            this.callback.call(this.scope || this)
        }
        this.callback = null;
        this.scope = null
    }
};