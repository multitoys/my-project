/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.TriggerField = Ext.extend(Ext.form.TextField, {
    defaultAutoCreate: {
        tag: "input",
        type: "text",
        size: "16",
        autocomplete: "off"
    },
    hideTrigger: false,
    autoSize: Ext.emptyFn,
    monitorTab: true,
    deferHeight: true,
    mimicing: false,
    onResize: function (A, B) {
        Ext.form.TriggerField.superclass.onResize.call(this, A, B);
        if (typeof A == "number") {
            this.el.setWidth(this.adjustWidth("input", A - this.trigger.getWidth()))
        }
        this.wrap.setWidth(this.el.getWidth() + this.trigger.getWidth())
    },
    adjustSize: Ext.BoxComponent.prototype.adjustSize,
    getResizeEl: function () {
        return this.wrap
    },
    getPositionEl: function () {
        return this.wrap
    },
    alignErrorIcon: function () {
        this.errorIcon.alignTo(this.wrap, "tl-tr", [2, 0])
    },
    onRender: function (B, A) {
        Ext.form.TriggerField.superclass.onRender.call(this, B, A);
        this.wrap = this.el.wrap({cls: "x-form-field-wrap"});
        this.trigger = this.wrap.createChild(this.triggerConfig || {
                tag: "img",
                src: Ext.BLANK_IMAGE_URL,
                cls: "x-form-trigger " + this.triggerClass
            });
        if (this.hideTrigger) {
            this.trigger.setDisplayed(false)
        }
        this.initTrigger();
        if (!this.width) {
            this.wrap.setWidth(this.el.getWidth() + this.trigger.getWidth())
        }
    },
    initTrigger: function () {
        this.trigger.on("click", this.onTriggerClick, this, {preventDefault: true});
        this.trigger.addClassOnOver("x-form-trigger-over");
        this.trigger.addClassOnClick("x-form-trigger-click")
    },
    onDestroy: function () {
        if (this.trigger) {
            this.trigger.removeAllListeners();
            this.trigger.remove()
        }
        if (this.wrap) {
            this.wrap.remove()
        }
        Ext.form.TriggerField.superclass.onDestroy.call(this)
    },
    onFocus: function () {
        Ext.form.TriggerField.superclass.onFocus.call(this);
        if (!this.mimicing) {
            this.wrap.addClass("x-trigger-wrap-focus");
            this.mimicing = true;
            Ext.get(Ext.isIE ? document.body : document).on("mousedown", this.mimicBlur, this, {delay: 10});
            if (this.monitorTab) {
                this.el.on("keydown", this.checkTab, this)
            }
        }
    },
    checkTab: function (A) {
        if (A.getKey() == A.TAB) {
            this.triggerBlur()
        }
    },
    onBlur: function () {
    },
    mimicBlur: function (A) {
        if (!this.wrap.contains(A.target) && this.validateBlur(A)) {
            this.triggerBlur()
        }
    },
    triggerBlur: function () {
        this.mimicing = false;
        Ext.get(Ext.isIE ? document.body : document).un("mousedown", this.mimicBlur);
        if (this.monitorTab) {
            this.el.un("keydown", this.checkTab, this)
        }
        this.beforeBlur();
        this.wrap.removeClass("x-trigger-wrap-focus");
        Ext.form.TriggerField.superclass.onBlur.call(this)
    },
    beforeBlur: Ext.emptyFn,
    validateBlur: function (A) {
        return true
    },
    onDisable: function () {
        Ext.form.TriggerField.superclass.onDisable.call(this);
        if (this.wrap) {
            this.wrap.addClass("x-item-disabled")
        }
    },
    onEnable: function () {
        Ext.form.TriggerField.superclass.onEnable.call(this);
        if (this.wrap) {
            this.wrap.removeClass("x-item-disabled")
        }
    },
    onShow: function () {
        if (this.wrap) {
            this.wrap.dom.style.display = "";
            this.wrap.dom.style.visibility = "visible"
        }
    },
    onHide: function () {
        this.wrap.dom.style.display = "none"
    },
    onTriggerClick: Ext.emptyFn
});
Ext.form.TwinTriggerField = Ext.extend(Ext.form.TriggerField, {
    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: "span",
            cls: "x-form-twin-triggers",
            cn: [{tag: "img", src: Ext.BLANK_IMAGE_URL, cls: "x-form-trigger " + this.trigger1Class}, {
                tag: "img",
                src: Ext.BLANK_IMAGE_URL,
                cls: "x-form-trigger " + this.trigger2Class
            }]
        }
    }, getTrigger: function (A) {
        return this.triggers[A]
    }, initTrigger: function () {
        var A = this.trigger.select(".x-form-trigger", true);
        this.wrap.setStyle("overflow", "hidden");
        var B = this;
        A.each(function (D, F, C) {
            D.hide = function () {
                var G = B.wrap.getWidth();
                this.dom.style.display = "none";
                B.el.setWidth(G - B.trigger.getWidth())
            };
            D.show = function () {
                var G = B.wrap.getWidth();
                this.dom.style.display = "";
                B.el.setWidth(G - B.trigger.getWidth())
            };
            var E = "Trigger" + (C + 1);
            if (this["hide" + E]) {
                D.dom.style.display = "none"
            }
            D.on("click", this["on" + E + "Click"], this, {preventDefault: true});
            D.addClassOnOver("x-form-trigger-over");
            D.addClassOnClick("x-form-trigger-click")
        }, this);
        this.triggers = A.elements
    }, onTrigger1Click: Ext.emptyFn, onTrigger2Click: Ext.emptyFn
});
Ext.reg("trigger", Ext.form.TriggerField);