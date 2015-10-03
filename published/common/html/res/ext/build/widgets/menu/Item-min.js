/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.Item = function (A) {
    Ext.menu.Item.superclass.constructor.call(this, A);
    if (this.menu) {
        this.menu = Ext.menu.MenuMgr.get(this.menu)
    }
};
Ext.extend(Ext.menu.Item, Ext.menu.BaseItem, {
    itemCls: "x-menu-item",
    canActivate: true,
    showDelay: 200,
    hideDelay: 200,
    ctype: "Ext.menu.Item",
    onRender: function (B, A) {
        var C = document.createElement("a");
        C.hideFocus = true;
        C.unselectable = "on";
        C.href = this.href || "#";
        if (this.hrefTarget) {
            C.target = this.hrefTarget
        }
        C.className = this.itemCls + (this.menu ? " x-menu-item-arrow" : "") + (this.cls ? " " + this.cls : "");
        C.innerHTML = String.format("<img src=\"{0}\" class=\"x-menu-item-icon {2}\" />{1}", this.icon || Ext.BLANK_IMAGE_URL, this.itemText || this.text, this.iconCls || "");
        this.el = C;
        Ext.menu.Item.superclass.onRender.call(this, B, A)
    },
    setText: function (A) {
        this.text = A;
        if (this.rendered) {
            this.el.update(String.format("<img src=\"{0}\" class=\"x-menu-item-icon {2}\">{1}", this.icon || Ext.BLANK_IMAGE_URL, this.text, this.iconCls || ""));
            this.parentMenu.autoWidth()
        }
    },
    setIconClass: function (A) {
        var B = this.iconCls;
        this.iconCls = A;
        if (this.rendered) {
            this.el.child("img.x-menu-item-icon").replaceClass(B, this.iconCls)
        }
    },
    handleClick: function (A) {
        if (!this.href) {
            A.stopEvent()
        }
        Ext.menu.Item.superclass.handleClick.apply(this, arguments)
    },
    activate: function (A) {
        if (Ext.menu.Item.superclass.activate.apply(this, arguments)) {
            this.focus();
            if (A) {
                this.expandMenu()
            }
        }
        return true
    },
    shouldDeactivate: function (A) {
        if (Ext.menu.Item.superclass.shouldDeactivate.call(this, A)) {
            if (this.menu && this.menu.isVisible()) {
                return !this.menu.getEl().getRegion().contains(A.getPoint())
            }
            return true
        }
        return false
    },
    deactivate: function () {
        Ext.menu.Item.superclass.deactivate.apply(this, arguments);
        this.hideMenu()
    },
    expandMenu: function (A) {
        if (!this.disabled && this.menu) {
            clearTimeout(this.hideTimer);
            delete this.hideTimer;
            if (!this.menu.isVisible() && !this.showTimer) {
                this.showTimer = this.deferExpand.defer(this.showDelay, this, [A])
            } else {
                if (this.menu.isVisible() && A) {
                    this.menu.tryActivate(0, 1)
                }
            }
        }
    },
    deferExpand: function (A) {
        delete this.showTimer;
        this.menu.show(this.container, this.parentMenu.subMenuAlign || "tl-tr?", this.parentMenu);
        if (A) {
            this.menu.tryActivate(0, 1)
        }
    },
    hideMenu: function () {
        clearTimeout(this.showTimer);
        delete this.showTimer;
        if (!this.hideTimer && this.menu && this.menu.isVisible()) {
            this.hideTimer = this.deferHide.defer(this.hideDelay, this)
        }
    },
    deferHide: function () {
        delete this.hideTimer;
        this.menu.hide()
    }
});