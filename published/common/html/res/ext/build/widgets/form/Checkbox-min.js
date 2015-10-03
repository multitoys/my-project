/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.Checkbox = Ext.extend(Ext.form.Field, {
    focusClass: undefined,
    fieldClass: "x-form-field",
    checked: false,
    defaultAutoCreate: {tag: "input", type: "checkbox", autocomplete: "off"},
    initComponent: function () {
        Ext.form.Checkbox.superclass.initComponent.call(this);
        this.addEvents("check")
    },
    onResize: function () {
        Ext.form.Checkbox.superclass.onResize.apply(this, arguments);
        if (!this.boxLabel) {
            this.el.alignTo(this.wrap, "c-c")
        }
    },
    initEvents: function () {
        Ext.form.Checkbox.superclass.initEvents.call(this);
        this.el.on("click", this.onClick, this);
        this.el.on("change", this.onClick, this)
    },
    getResizeEl: function () {
        return this.wrap
    },
    getPositionEl: function () {
        return this.wrap
    },
    markInvalid: Ext.emptyFn,
    clearInvalid: Ext.emptyFn,
    onRender: function (B, A) {
        Ext.form.Checkbox.superclass.onRender.call(this, B, A);
        if (this.inputValue !== undefined) {
            this.el.dom.value = this.inputValue
        }
        this.wrap = this.el.wrap({cls: "x-form-check-wrap"});
        if (this.boxLabel) {
            this.wrap.createChild({tag: "label", htmlFor: this.el.id, cls: "x-form-cb-label", html: this.boxLabel})
        }
        if (this.checked) {
            this.setValue(true)
        } else {
            this.checked = this.el.dom.checked
        }
    },
    onDestroy: function () {
        if (this.wrap) {
            this.wrap.remove()
        }
        Ext.form.Checkbox.superclass.onDestroy.call(this)
    },
    initValue: Ext.emptyFn,
    getValue: function () {
        if (this.rendered) {
            return this.el.dom.checked
        }
        return false
    },
    onClick: function () {
        if (this.el.dom.checked != this.checked) {
            this.setValue(this.el.dom.checked)
        }
    },
    setValue: function (A) {
        this.checked = (A === true || A === "true" || A == "1" || String(A).toLowerCase() == "on");
        if (this.el && this.el.dom) {
            this.el.dom.checked = this.checked;
            this.el.dom.defaultChecked = this.checked
        }
        this.fireEvent("check", this, this.checked)
    }
});
Ext.reg("checkbox", Ext.form.Checkbox);