/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.FormLayout = Ext.extend(Ext.layout.AnchorLayout, {
    labelSeparator: ":", getAnchorViewSize: function (A, B) {
        return A.body.getStyleSize()
    }, setContainer: function (B) {
        Ext.layout.FormLayout.superclass.setContainer.call(this, B);
        if (B.labelAlign) {
            B.addClass("x-form-label-" + B.labelAlign)
        }
        if (B.hideLabels) {
            this.labelStyle = "display:none";
            this.elementStyle = "padding-left:0;";
            this.labelAdjust = 0
        } else {
            this.labelSeparator = B.labelSeparator || this.labelSeparator;
            B.labelWidth = B.labelWidth || 100;
            if (typeof B.labelWidth == "number") {
                var C = (typeof B.labelPad == "number" ? B.labelPad : 5);
                this.labelAdjust = B.labelWidth + C;
                this.labelStyle = "width:" + B.labelWidth + "px;";
                this.elementStyle = "padding-left:" + (B.labelWidth + C) + "px"
            }
            if (B.labelAlign == "top") {
                this.labelStyle = "width:auto;";
                this.labelAdjust = 0;
                this.elementStyle = "padding-left:0;"
            }
        }
        if (!this.fieldTpl) {
            var A = new Ext.Template("<div class=\"x-form-item {5}\" tabIndex=\"-1\">", "<label for=\"{0}\" style=\"{2}\" class=\"x-form-item-label\">{1}{4}</label>", "<div class=\"x-form-element\" id=\"x-form-el-{0}\" style=\"{3}\">", "</div><div class=\"{6}\"></div>", "</div>");
            A.disableFormats = true;
            A.compile();
            Ext.layout.FormLayout.prototype.fieldTpl = A
        }
    }, renderItem: function (D, A, C) {
        if (D && !D.rendered && D.isFormField && D.inputType != "hidden") {
            var B = [D.id, D.fieldLabel, D.labelStyle || this.labelStyle || "", this.elementStyle || "", typeof D.labelSeparator == "undefined" ? this.labelSeparator : D.labelSeparator, (D.itemCls || this.container.itemCls || "") + (D.hideLabel ? " x-hide-label" : ""), D.clearCls || "x-form-clear-left"];
            if (typeof A == "number") {
                A = C.dom.childNodes[A] || null
            }
            if (A) {
                this.fieldTpl.insertBefore(A, B)
            } else {
                this.fieldTpl.append(C, B)
            }
            D.render("x-form-el-" + D.id)
        } else {
            Ext.layout.FormLayout.superclass.renderItem.apply(this, arguments)
        }
    }, adjustWidthAnchor: function (B, A) {
        return B - (A.hideLabel ? 0 : this.labelAdjust)
    }, isValidParent: function (B, A) {
        return true
    }
});
Ext.Container.LAYOUTS["form"] = Ext.layout.FormLayout;