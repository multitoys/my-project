/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.LayoutRegion = function (C, A, D) {
    Ext.LayoutRegion.superclass.constructor.call(this, C, A, D, true);
    var B = Ext.DomHelper;
    this.el = B.append(C.el.dom, {tag: "div", cls: "x-layout-panel x-layout-panel-" + this.position}, true);
    this.titleEl = B.append(this.el.dom, {
        tag: "div",
        unselectable: "on",
        cls: "x-unselectable x-layout-panel-hd x-layout-title-" + this.position,
        children: [{
            tag: "span",
            cls: "x-unselectable x-layout-panel-hd-text",
            unselectable: "on",
            html: "&#160;"
        }, {tag: "div", cls: "x-unselectable x-layout-panel-hd-tools", unselectable: "on"}]
    }, true);
    this.titleEl.enableDisplayMode();
    this.titleTextEl = this.titleEl.dom.firstChild;
    this.tools = Ext.get(this.titleEl.dom.childNodes[1], true);
    this.closeBtn = this.createTool(this.tools.dom, "x-layout-close");
    this.closeBtn.enableDisplayMode();
    this.closeBtn.on("click", this.closeClicked, this);
    this.closeBtn.hide();
    this.createBody(A);
    this.visible = true;
    this.collapsed = false;
    if (A.hideWhenEmpty) {
        this.hide();
        this.on("paneladded", this.validateVisibility, this);
        this.on("panelremoved", this.validateVisibility, this)
    }
    this.applyConfig(A)
};
Ext.extend(Ext.LayoutRegion, Ext.BasicLayoutRegion, {
    createBody: function () {
        this.bodyEl = this.el.createChild({tag: "div", cls: "x-layout-panel-body"})
    }, applyConfig: function (B) {
        if (B.collapsible && this.position != "center" && !this.collapsedEl) {
            var A = Ext.DomHelper;
            if (B.titlebar !== false) {
                this.collapseBtn = this.createTool(this.tools.dom, "x-layout-collapse-" + this.position);
                this.collapseBtn.on("click", this.collapse, this);
                this.collapseBtn.enableDisplayMode();
                if (B.showPin === true || this.showPin) {
                    this.stickBtn = this.createTool(this.tools.dom, "x-layout-stick");
                    this.stickBtn.enableDisplayMode();
                    this.stickBtn.on("click", this.expand, this);
                    this.stickBtn.hide()
                }
            }
            this.collapsedEl = A.append(this.mgr.el.dom, {
                cls: "x-layout-collapsed x-layout-collapsed-" + this.position,
                children: [{cls: "x-layout-collapsed-tools", children: [{cls: "x-layout-ctools-inner"}]}]
            }, true);
            if (B.floatable !== false) {
                this.collapsedEl.addClassOnOver("x-layout-collapsed-over");
                this.collapsedEl.on("click", this.collapseClick, this)
            }
            if (B.collapsedTitle && (this.position == "north" || this.position == "south")) {
                this.collapsedTitleTextEl = A.append(this.collapsedEl.dom, {
                    tag: "div",
                    cls: "x-unselectable x-layout-panel-hd-text",
                    id: "message",
                    unselectable: "on",
                    style: {"float": "left"}
                });
                this.collapsedTitleTextEl.innerHTML = B.collapsedTitle
            }
            this.expandBtn = this.createTool(this.collapsedEl.dom.firstChild.firstChild, "x-layout-expand-" + this.position);
            this.expandBtn.on("click", this.expand, this)
        }
        if (this.collapseBtn) {
            this.collapseBtn.setVisible(B.collapsible == true)
        }
        this.cmargins = B.cmargins || this.cmargins || (this.position == "west" || this.position == "east" ? {
                top: 0,
                left: 2,
                right: 2,
                bottom: 0
            } : {top: 2, left: 0, right: 0, bottom: 2});
        this.margins = B.margins || this.margins || {top: 0, left: 0, right: 0, bottom: 0};
        this.bottomTabs = B.tabPosition != "top";
        this.autoScroll = B.autoScroll || false;
        if (this.autoScroll) {
            this.bodyEl.setStyle("overflow", "auto")
        } else {
            this.bodyEl.setStyle("overflow", "hidden")
        }
        if ((!B.titlebar && !B.title) || B.titlebar === false) {
            this.titleEl.hide()
        } else {
            this.titleEl.show();
            if (B.title) {
                this.titleTextEl.innerHTML = B.title
            }
        }
        this.duration = B.duration || 0.3;
        this.slideDuration = B.slideDuration || 0.45;
        this.config = B;
        if (B.collapsed) {
            this.collapse(true)
        }
        if (B.hidden) {
            this.hide()
        }
    }, isVisible: function () {
        return this.visible
    }, setCollapsedTitle: function (A) {
        A = A || "&#160;";
        if (this.collapsedTitleTextEl) {
            this.collapsedTitleTextEl.innerHTML = A
        }
    }, getBox: function () {
        var A;
        if (!this.collapsed) {
            A = this.el.getBox(false, true)
        } else {
            A = this.collapsedEl.getBox(false, true)
        }
        return A
    }, getMargins: function () {
        return this.collapsed ? this.cmargins : this.margins
    }, highlight: function () {
        this.el.addClass("x-layout-panel-dragover")
    }, unhighlight: function () {
        this.el.removeClass("x-layout-panel-dragover")
    }, updateBox: function (A) {
        this.box = A;
        if (!this.collapsed) {
            this.el.dom.style.left = A.x + "px";
            this.el.dom.style.top = A.y + "px";
            this.updateBody(A.width, A.height)
        } else {
            this.collapsedEl.dom.style.left = A.x + "px";
            this.collapsedEl.dom.style.top = A.y + "px";
            this.collapsedEl.setSize(A.width, A.height)
        }
        if (this.tabs) {
            this.tabs.autoSizeTabs()
        }
    }, updateBody: function (A, C) {
        if (A !== null) {
            this.el.setWidth(A);
            A -= this.el.getBorderWidth("rl");
            if (this.config.adjustments) {
                A += this.config.adjustments[0]
            }
        }
        if (C !== null) {
            this.el.setHeight(C);
            C = this.titleEl && this.titleEl.isDisplayed() ? C - (this.titleEl.getHeight() || 0) : C;
            C -= this.el.getBorderWidth("tb");
            if (this.config.adjustments) {
                C += this.config.adjustments[1]
            }
            this.bodyEl.setHeight(C);
            if (this.tabs) {
                C = this.tabs.syncHeight(C)
            }
        }
        if (this.panelSize) {
            A = A !== null ? A : this.panelSize.width;
            C = C !== null ? C : this.panelSize.height
        }
        if (this.activePanel) {
            var B = this.activePanel.getEl();
            A = A !== null ? A : B.getWidth();
            C = C !== null ? C : B.getHeight();
            this.panelSize = {width: A, height: C};
            this.activePanel.setSize(A, C)
        }
        if (Ext.isIE && this.tabs) {
            this.tabs.el.repaint()
        }
    }, getEl: function () {
        return this.el
    }, hide: function () {
        if (!this.collapsed) {
            this.el.dom.style.left = "-2000px";
            this.el.hide()
        } else {
            this.collapsedEl.dom.style.left = "-2000px";
            this.collapsedEl.hide()
        }
        this.visible = false;
        this.fireEvent("visibilitychange", this, false)
    }, show: function () {
        if (!this.collapsed) {
            this.el.show()
        } else {
            this.collapsedEl.show()
        }
        this.visible = true;
        this.fireEvent("visibilitychange", this, true)
    }, closeClicked: function () {
        if (this.activePanel) {
            this.remove(this.activePanel)
        }
    }, collapseClick: function (A) {
        if (this.isSlid) {
            A.stopPropagation();
            this.slideIn()
        } else {
            A.stopPropagation();
            this.slideOut()
        }
    }, collapse: function (A) {
        if (this.collapsed) {
            return
        }
        this.collapsed = true;
        if (this.split) {
            this.split.el.hide()
        }
        if (this.config.animate && A !== true) {
            this.fireEvent("invalidated", this);
            this.animateCollapse()
        } else {
            this.el.setLocation(-20000, -20000);
            this.el.hide();
            this.collapsedEl.show();
            this.fireEvent("collapsed", this);
            this.fireEvent("invalidated", this)
        }
    }, animateCollapse: function () {
    }, expand: function (B, A) {
        if (B) {
            B.stopPropagation()
        }
        if (!this.collapsed || this.el.hasActiveFx()) {
            return
        }
        if (this.isSlid) {
            this.afterSlideIn();
            A = true
        }
        this.collapsed = false;
        if (this.config.animate && A !== true) {
            this.animateExpand()
        } else {
            this.el.show();
            if (this.split) {
                this.split.el.show()
            }
            this.collapsedEl.setLocation(-2000, -2000);
            this.collapsedEl.hide();
            this.fireEvent("invalidated", this);
            this.fireEvent("expanded", this)
        }
    }, animateExpand: function () {
    }, initTabs: function () {
        this.bodyEl.setStyle("overflow", "hidden");
        var A = new Ext.TabPanel(this.bodyEl.dom, {
            tabPosition: this.bottomTabs ? "bottom" : "top",
            disableTooltips: this.config.disableTabTips
        });
        if (this.config.hideTabs) {
            A.stripWrap.setDisplayed(false)
        }
        this.tabs = A;
        A.resizeTabs = this.config.resizeTabs === true;
        A.minTabWidth = this.config.minTabWidth || 40;
        A.maxTabWidth = this.config.maxTabWidth || 250;
        A.preferredTabWidth = this.config.preferredTabWidth || 150;
        A.monitorResize = false;
        A.bodyEl.setStyle("overflow", this.config.autoScroll ? "auto" : "hidden");
        A.bodyEl.addClass("x-layout-tabs-body");
        this.panels.each(this.initPanelAsTab, this)
    }, initPanelAsTab: function (A) {
        var B = this.tabs.addTab(A.getEl().id, A.getTitle(), null, this.config.closeOnTab && A.isClosable());
        if (A.tabTip !== undefined) {
            B.setTooltip(A.tabTip)
        }
        B.on("activate", function () {
            this.setActivePanel(A)
        }, this);
        if (this.config.closeOnTab) {
            B.on("beforeclose", function (C, D) {
                D.cancel = true;
                this.remove(A)
            }, this)
        }
        return B
    }, updatePanelTitle: function (A, C) {
        if (this.activePanel == A) {
            this.updateTitle(C)
        }
        if (this.tabs) {
            var B = this.tabs.getTab(A.getEl().id);
            B.setText(C);
            if (A.tabTip !== undefined) {
                B.setTooltip(A.tabTip)
            }
        }
    }, updateTitle: function (A) {
        if (this.titleTextEl && !this.config.title) {
            this.titleTextEl.innerHTML = (typeof A != "undefined" && A.length > 0 ? A : "&#160;")
        }
    }, setActivePanel: function (A) {
        A = this.getPanel(A);
        if (this.activePanel && this.activePanel != A) {
            this.activePanel.setActiveState(false)
        }
        this.activePanel = A;
        A.setActiveState(true);
        if (this.panelSize) {
            A.setSize(this.panelSize.width, this.panelSize.height)
        }
        if (this.closeBtn) {
            this.closeBtn.setVisible(!this.config.closeOnTab && !this.isSlid && A.isClosable())
        }
        this.updateTitle(A.getTitle());
        if (this.tabs) {
            this.fireEvent("invalidated", this)
        }
        this.fireEvent("panelactivated", this, A)
    }, showPanel: function (A) {
        if (A = this.getPanel(A)) {
            if (this.tabs) {
                var B = this.tabs.getTab(A.getEl().id);
                if (B.isHidden()) {
                    this.tabs.unhideTab(B.id)
                }
                B.activate()
            } else {
                this.setActivePanel(A)
            }
        }
        return A
    }, getActivePanel: function () {
        return this.activePanel
    }, validateVisibility: function () {
        if (this.panels.getCount() < 1) {
            this.updateTitle("&#160;");
            this.closeBtn.hide();
            this.hide()
        } else {
            if (!this.isVisible()) {
                this.show()
            }
        }
    }, add: function (B) {
        if (arguments.length > 1) {
            for (var C = 0, A = arguments.length; C < A; C++) {
                this.add(arguments[C])
            }
            return null
        }
        if (this.hasPanel(B)) {
            this.showPanel(B);
            return B
        }
        B.setRegion(this);
        this.panels.add(B);
        if (this.panels.getCount() == 1 && !this.config.alwaysShowTabs) {
            this.bodyEl.dom.appendChild(B.getEl().dom);
            if (B.background !== true) {
                this.setActivePanel(B)
            }
            this.fireEvent("paneladded", this, B);
            return B
        }
        if (!this.tabs) {
            this.initTabs()
        } else {
            this.initPanelAsTab(B)
        }
        if (B.background !== true) {
            this.tabs.activate(B.getEl().id)
        }
        this.fireEvent("paneladded", this, B);
        return B
    }, hidePanel: function (A) {
        if (this.tabs && (A = this.getPanel(A))) {
            this.tabs.hideTab(A.getEl().id)
        }
    }, unhidePanel: function (A) {
        if (this.tabs && (A = this.getPanel(A))) {
            this.tabs.unhideTab(A.getEl().id)
        }
    }, clearPanels: function () {
        while (this.panels.getCount() > 0) {
            this.remove(this.panels.first())
        }
    }, remove: function (B, A) {
        B = this.getPanel(B);
        if (!B) {
            return null
        }
        var E = {};
        this.fireEvent("beforeremove", this, B, E);
        if (E.cancel === true) {
            return null
        }
        A = (typeof A != "undefined" ? A : (this.config.preservePanels === true || B.preserve === true));
        var C = B.getId();
        this.panels.removeKey(C);
        if (A) {
            document.body.appendChild(B.getEl().dom)
        }
        if (this.tabs) {
            this.tabs.removeTab(B.getEl().id)
        } else {
            if (!A) {
                this.bodyEl.dom.removeChild(B.getEl().dom)
            }
        }
        if (this.panels.getCount() == 1 && this.tabs && !this.config.alwaysShowTabs) {
            var D = this.panels.first();
            var F = document.createElement("div");
            F.appendChild(D.getEl().dom);
            this.bodyEl.update("");
            this.bodyEl.dom.appendChild(D.getEl().dom);
            F = null;
            this.updateTitle(D.getTitle());
            this.tabs = null;
            this.bodyEl.setStyle("overflow", this.config.autoScroll ? "auto" : "hidden");
            this.setActivePanel(D)
        }
        B.setRegion(null);
        if (this.activePanel == B) {
            this.activePanel = null
        }
        if (this.config.autoDestroy !== false && A !== true) {
            try {
                B.destroy()
            } catch (E) {
            }
        }
        this.fireEvent("panelremoved", this, B);
        return B
    }, getTabs: function () {
        return this.tabs
    }, createTool: function (C, B) {
        var A = Ext.DomHelper.append(C, {
            tag: "div",
            cls: "x-layout-tools-button",
            children: [{tag: "div", cls: "x-layout-tools-button-inner " + B, html: "&#160;"}]
        }, true);
        A.addClassOnOver("x-layout-tools-button-over");
        return A
    }
});