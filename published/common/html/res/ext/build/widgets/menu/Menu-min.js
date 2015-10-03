/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.Menu = function (A) {
    if (A instanceof Array) {
        A = {items: A}
    }
    Ext.apply(this, A);
    this.id = this.id || Ext.id();
    this.addEvents("beforeshow", "beforehide", "show", "hide", "click", "mouseover", "mouseout", "itemclick");
    Ext.menu.MenuMgr.register(this);
    Ext.menu.Menu.superclass.constructor.call(this);
    var B = this.items;
    this.items = new Ext.util.MixedCollection();
    if (B) {
        this.add.apply(this, B)
    }
};
Ext.extend(Ext.menu.Menu, Ext.util.Observable, {
    minWidth: 120,
    shadow: "sides",
    subMenuAlign: "tl-tr?",
    defaultAlign: "tl-bl?",
    allowOtherMenus: false,
    hidden: true,
    createEl: function () {
        return new Ext.Layer({
            cls: "x-menu",
            shadow: this.shadow,
            constrain: false,
            parentEl: this.parentEl || document.body,
            zindex: 15000
        })
    },
    render: function () {
        if (this.el) {
            return
        }
        var B = this.el = this.createEl();
        this.keyNav = new Ext.menu.MenuNav(this);
        if (this.plain) {
            B.addClass("x-menu-plain")
        }
        if (this.cls) {
            B.addClass(this.cls)
        }
        this.focusEl = B.createChild({
            tag: "a",
            cls: "x-menu-focus",
            href: "#",
            onclick: "return false;",
            tabIndex: "-1"
        });
        var A = B.createChild({tag: "ul", cls: "x-menu-list"});
        A.on("click", this.onClick, this);
        A.on("mouseover", this.onMouseOver, this);
        A.on("mouseout", this.onMouseOut, this);
        this.items.each(function (D) {
            var C = document.createElement("li");
            C.className = "x-menu-list-item";
            A.dom.appendChild(C);
            D.render(C, this)
        }, this);
        this.ul = A;
        this.autoWidth()
    },
    autoWidth: function () {
        var D = this.el, C = this.ul;
        if (!D) {
            return
        }
        var A = this.width;
        if (A) {
            D.setWidth(A)
        } else {
            if (Ext.isIE) {
                D.setWidth(this.minWidth);
                var B = D.dom.offsetWidth;
                D.setWidth(C.getWidth() + D.getFrameWidth("lr"))
            }
        }
    },
    delayAutoWidth: function () {
        if (this.el) {
            if (!this.awTask) {
                this.awTask = new Ext.util.DelayedTask(this.autoWidth, this)
            }
            this.awTask.delay(20)
        }
    },
    findTargetItem: function (B) {
        var A = B.getTarget(".x-menu-list-item", this.ul, true);
        if (A && A.menuItemId) {
            return this.items.get(A.menuItemId)
        }
    },
    onClick: function (B) {
        var A;
        if (A = this.findTargetItem(B)) {
            A.onClick(B);
            this.fireEvent("click", this, A, B)
        }
    },
    setActiveItem: function (A, B) {
        if (A != this.activeItem) {
            if (this.activeItem) {
                this.activeItem.deactivate()
            }
            this.activeItem = A;
            A.activate(B)
        } else {
            if (B) {
                A.expandMenu()
            }
        }
    },
    tryActivate: function (F, E) {
        var B = this.items;
        for (var C = F, A = B.length; C >= 0 && C < A; C += E) {
            var D = B.get(C);
            if (!D.disabled && D.canActivate) {
                this.setActiveItem(D, false);
                return D
            }
        }
        return false
    },
    onMouseOver: function (B) {
        var A;
        if (A = this.findTargetItem(B)) {
            if (A.canActivate && !A.disabled) {
                this.setActiveItem(A, true)
            }
        }
        this.fireEvent("mouseover", this, B, A)
    },
    onMouseOut: function (B) {
        var A;
        if (A = this.findTargetItem(B)) {
            if (A == this.activeItem && A.shouldDeactivate(B)) {
                this.activeItem.deactivate();
                delete this.activeItem
            }
        }
        this.fireEvent("mouseout", this, B, A)
    },
    isVisible: function () {
        return this.el && !this.hidden
    },
    show: function (B, C, A) {
        this.parentMenu = A;
        if (!this.el) {
            this.render()
        }
        this.fireEvent("beforeshow", this);
        this.showAt(this.el.getAlignToXY(B, C || this.defaultAlign), A, false)
    },
    showAt: function (C, B, A) {
        this.parentMenu = B;
        if (!this.el) {
            this.render()
        }
        if (A !== false) {
            this.fireEvent("beforeshow", this);
            C = this.el.adjustForConstraints(C)
        }
        this.el.setXY(C);
        this.el.show();
        this.hidden = false;
        this.focus();
        this.fireEvent("show", this)
    },
    focus: function () {
        if (!this.hidden) {
            this.doFocus.defer(50, this)
        }
    },
    doFocus: function () {
        if (!this.hidden) {
            this.focusEl.focus()
        }
    },
    hide: function (A) {
        if (this.el && this.isVisible()) {
            this.fireEvent("beforehide", this);
            if (this.activeItem) {
                this.activeItem.deactivate();
                this.activeItem = null
            }
            this.el.hide();
            this.hidden = true;
            this.fireEvent("hide", this)
        }
        if (A === true && this.parentMenu) {
            this.parentMenu.hide(true)
        }
    },
    add: function () {
        var B = arguments, A = B.length, E;
        for (var C = 0; C < A; C++) {
            var D = B[C];
            if (D.render) {
                E = this.addItem(D)
            } else {
                if (typeof D == "string") {
                    if (D == "separator" || D == "-") {
                        E = this.addSeparator()
                    } else {
                        E = this.addText(D)
                    }
                } else {
                    if (D.tagName || D.el) {
                        E = this.addElement(D)
                    } else {
                        if (typeof D == "object") {
                            Ext.applyIf(D, this.defaults);
                            E = this.addMenuItem(D)
                        }
                    }
                }
            }
        }
        return E
    },
    getEl: function () {
        if (!this.el) {
            this.render()
        }
        return this.el
    },
    addSeparator: function () {
        return this.addItem(new Ext.menu.Separator())
    },
    addElement: function (A) {
        return this.addItem(new Ext.menu.BaseItem(A))
    },
    addItem: function (B) {
        this.items.add(B);
        if (this.ul) {
            var A = document.createElement("li");
            A.className = "x-menu-list-item";
            this.ul.dom.appendChild(A);
            B.render(A, this);
            this.delayAutoWidth()
        }
        return B
    },
    addMenuItem: function (A) {
        if (!(A instanceof Ext.menu.Item)) {
            if (typeof A.checked == "boolean") {
                A = new Ext.menu.CheckItem(A)
            } else {
                A = new Ext.menu.Item(A)
            }
        }
        return this.addItem(A)
    },
    addText: function (A) {
        return this.addItem(new Ext.menu.TextItem(A))
    },
    insert: function (B, C) {
        this.items.insert(B, C);
        if (this.ul) {
            var A = document.createElement("li");
            A.className = "x-menu-list-item";
            this.ul.dom.insertBefore(A, this.ul.dom.childNodes[B]);
            C.render(A, this);
            this.delayAutoWidth()
        }
        return C
    },
    remove: function (A) {
        this.items.removeKey(A.id);
        A.destroy()
    },
    removeAll: function () {
        var A;
        while (A = this.items.first()) {
            this.remove(A)
        }
    },
    destroy: function () {
        this.beforeDestroy();
        Ext.menu.MenuMgr.unregister(this);
        if (this.keyNav) {
            this.keyNav.disable()
        }
        this.removeAll();
        if (this.ul) {
            this.ul.removeAllListeners()
        }
        if (this.el) {
            this.el.destroy()
        }
    },
    beforeDestroy: Ext.emptyFn
});
Ext.menu.MenuNav = function (A) {
    Ext.menu.MenuNav.superclass.constructor.call(this, A.el);
    this.scope = this.menu = A
};
Ext.extend(Ext.menu.MenuNav, Ext.KeyNav, {
    doRelay: function (C, B) {
        var A = C.getKey();
        if (!this.menu.activeItem && C.isNavKeyPress() && A != C.SPACE && A != C.RETURN) {
            this.menu.tryActivate(0, 1);
            return false
        }
        return B.call(this.scope || this, C, this.menu)
    }, up: function (B, A) {
        if (!A.tryActivate(A.items.indexOf(A.activeItem) - 1, -1)) {
            A.tryActivate(A.items.length - 1, -1)
        }
    }, down: function (B, A) {
        if (!A.tryActivate(A.items.indexOf(A.activeItem) + 1, 1)) {
            A.tryActivate(0, 1)
        }
    }, right: function (B, A) {
        if (A.activeItem) {
            A.activeItem.expandMenu(true)
        }
    }, left: function (B, A) {
        A.hide();
        if (A.parentMenu && A.parentMenu.activeItem) {
            A.parentMenu.activeItem.activate()
        }
    }, enter: function (B, A) {
        if (A.activeItem) {
            B.stopPropagation();
            A.activeItem.onClick(B);
            A.fireEvent("click", this, A.activeItem);
            return true
        }
    }
});