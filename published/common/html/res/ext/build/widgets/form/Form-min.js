/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.FormPanel = Ext.extend(Ext.Panel, {
    buttonAlign: "center",
    minButtonWidth: 75,
    labelAlign: "left",
    monitorValid: false,
    monitorPoll: 200,
    layout: "form",
    initComponent: function () {
        this.form = this.createForm();
        Ext.FormPanel.superclass.initComponent.call(this);
        this.addEvents("clientvalidation");
        this.relayEvents(this.form, ["beforeaction", "actionfailed", "actioncomplete"])
    },
    createForm: function () {
        delete this.initialConfig.listeners;
        return new Ext.form.BasicForm(null, this.initialConfig)
    },
    initFields: function () {
        var C = this.form;
        var A = this;
        var B = function (D) {
            if (D.doLayout && D != A) {
                Ext.applyIf(D, {
                    labelAlign: D.ownerCt.labelAlign,
                    labelWidth: D.ownerCt.labelWidth,
                    itemCls: D.ownerCt.itemCls
                });
                if (D.items) {
                    D.items.each(B)
                }
            } else {
                if (D.isFormField) {
                    C.add(D)
                }
            }
        };
        this.items.each(B)
    },
    getLayoutTarget: function () {
        return this.form.el
    },
    getForm: function () {
        return this.form
    },
    onRender: function (B, A) {
        this.initFields();
        Ext.FormPanel.superclass.onRender.call(this, B, A);
        var C = {tag: "form", method: this.method || "POST", id: this.formId || Ext.id()};
        if (this.fileUpload) {
            C.enctype = "multipart/form-data"
        }
        this.form.initEl(this.body.createChild(C))
    },
    beforeDestroy: function () {
        Ext.FormPanel.superclass.beforeDestroy.call(this);
        Ext.destroy(this.form)
    },
    initEvents: function () {
        Ext.FormPanel.superclass.initEvents.call(this);
        this.items.on("remove", this.onRemove, this);
        this.items.on("add", this.onAdd, this);
        if (this.monitorValid) {
            this.startMonitoring()
        }
    },
    onAdd: function (A, B) {
        if (B.isFormField) {
            this.form.add(B)
        }
    },
    onRemove: function (A) {
        if (A.isFormField) {
            Ext.destroy(A.container.up(".x-form-item"));
            this.form.remove(A)
        }
    },
    startMonitoring: function () {
        if (!this.bound) {
            this.bound = true;
            Ext.TaskMgr.start({run: this.bindHandler, interval: this.monitorPoll || 200, scope: this})
        }
    },
    stopMonitoring: function () {
        this.bound = false
    },
    load: function () {
        this.form.load.apply(this.form, arguments)
    },
    onDisable: function () {
        Ext.FormPanel.superclass.onDisable.call(this);
        if (this.form) {
            this.form.items.each(function () {
                this.disable()
            })
        }
    },
    onEnable: function () {
        Ext.FormPanel.superclass.onEnable.call(this);
        if (this.form) {
            this.form.items.each(function () {
                this.enable()
            })
        }
    },
    bindHandler: function () {
        if (!this.bound) {
            return false
        }
        var D = true;
        this.form.items.each(function (E) {
            if (!E.isValid(true)) {
                D = false;
                return false
            }
        });
        if (this.buttons) {
            for (var C = 0, A = this.buttons.length; C < A; C++) {
                var B = this.buttons[C];
                if (B.formBind === true && B.disabled === D) {
                    B.setDisabled(!D)
                }
            }
        }
        this.fireEvent("clientvalidation", this, D)
    }
});
Ext.reg("form", Ext.FormPanel);
Ext.form.FormPanel = Ext.FormPanel;