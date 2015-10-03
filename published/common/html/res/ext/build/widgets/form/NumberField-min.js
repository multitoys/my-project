/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.NumberField = Ext.extend(Ext.form.TextField, {
    fieldClass: "x-form-field x-form-num-field",
    allowDecimals: true,
    decimalSeparator: ".",
    decimalPrecision: 2,
    allowNegative: true,
    minValue: Number.NEGATIVE_INFINITY,
    maxValue: Number.MAX_VALUE,
    minText: "The minimum value for this field is {0}",
    maxText: "The maximum value for this field is {0}",
    nanText: "{0} is not a valid number",
    baseChars: "0123456789",
    initEvents: function () {
        Ext.form.NumberField.superclass.initEvents.call(this);
        var B = this.baseChars + "";
        if (this.allowDecimals) {
            B += this.decimalSeparator
        }
        if (this.allowNegative) {
            B += "-"
        }
        this.stripCharsRe = new RegExp("[^" + B + "]", "gi");
        var A = function (D) {
            var C = D.getKey();
            if (!Ext.isIE && (D.isSpecialKey() || C == D.BACKSPACE || C == D.DELETE)) {
                return
            }
            var E = D.getCharCode();
            if (B.indexOf(String.fromCharCode(E)) === -1) {
                D.stopEvent()
            }
        };
        this.el.on("keypress", A, this)
    },
    validateValue: function (B) {
        if (!Ext.form.NumberField.superclass.validateValue.call(this, B)) {
            return false
        }
        if (B.length < 1) {
            return true
        }
        B = String(B).replace(this.decimalSeparator, ".");
        if (isNaN(B)) {
            this.markInvalid(String.format(this.nanText, B));
            return false
        }
        var A = this.parseValue(B);
        if (A < this.minValue) {
            this.markInvalid(String.format(this.minText, this.minValue));
            return false
        }
        if (A > this.maxValue) {
            this.markInvalid(String.format(this.maxText, this.maxValue));
            return false
        }
        return true
    },
    getValue: function () {
        return this.fixPrecision(this.parseValue(Ext.form.NumberField.superclass.getValue.call(this)))
    },
    setValue: function (A) {
        A = parseFloat(A);
        A = isNaN(A) ? "" : String(A).replace(".", this.decimalSeparator);
        Ext.form.NumberField.superclass.setValue.call(this, A)
    },
    parseValue: function (A) {
        A = parseFloat(String(A).replace(this.decimalSeparator, "."));
        return isNaN(A) ? "" : A
    },
    fixPrecision: function (B) {
        var A = isNaN(B);
        if (!this.allowDecimals || this.decimalPrecision == -1 || A || !B) {
            return A ? "" : B
        }
        return parseFloat(parseFloat(B).toFixed(this.decimalPrecision))
    },
    beforeBlur: function () {
        var A = this.parseValue(this.getRawValue());
        if (A) {
            this.setValue(this.fixPrecision(A))
        }
    }
});
Ext.reg("numberfield", Ext.form.NumberField);