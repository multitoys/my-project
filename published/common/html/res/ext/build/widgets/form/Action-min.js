/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.Action = function (B, A) {
    this.form = B;
    this.options = A || {}
};
Ext.form.Action.CLIENT_INVALID = "client";
Ext.form.Action.SERVER_INVALID = "server";
Ext.form.Action.CONNECT_FAILURE = "connect";
Ext.form.Action.LOAD_FAILURE = "load";
Ext.form.Action.prototype = {
    type: "default", run: function (A) {
    }, success: function (A) {
    }, handleResponse: function (A) {
    }, failure: function (A) {
        this.response = A;
        this.failureType = Ext.form.Action.CONNECT_FAILURE;
        this.form.afterAction(this, false)
    }, processResponse: function (A) {
        this.response = A;
        if (!A.responseText) {
            return true
        }
        this.result = this.handleResponse(A);
        return this.result
    }, getUrl: function (C) {
        var A = this.options.url || this.form.url || this.form.el.dom.action;
        if (C) {
            var B = this.getParams();
            if (B) {
                A += (A.indexOf("?") != -1 ? "&" : "?") + B
            }
        }
        return A
    }, getMethod: function () {
        return (this.options.method || this.form.method || this.form.el.dom.method || "POST").toUpperCase()
    }, getParams: function () {
        var A = this.form.baseParams;
        var B = this.options.params;
        if (B) {
            if (typeof B == "object") {
                B = Ext.urlEncode(Ext.applyIf(B, A))
            } else {
                if (typeof B == "string" && A) {
                    B += "&" + Ext.urlEncode(A)
                }
            }
        } else {
            if (A) {
                B = Ext.urlEncode(A)
            }
        }
        return B
    }, createCallback: function (A) {
        var A = A || {};
        return {
            success: this.success,
            failure: this.failure,
            scope: this,
            timeout: (A.timeout * 1000) || (this.form.timeout * 1000),
            upload: this.form.fileUpload ? this.success : undefined
        }
    }
};
Ext.form.Action.Submit = function (B, A) {
    Ext.form.Action.Submit.superclass.constructor.call(this, B, A)
};
Ext.extend(Ext.form.Action.Submit, Ext.form.Action, {
    type: "submit", run: function () {
        var B = this.options;
        var C = this.getMethod();
        var A = C == "POST";
        if (B.clientValidation === false || this.form.isValid()) {
            Ext.Ajax.request(Ext.apply(this.createCallback(B), {
                form: this.form.el.dom,
                url: this.getUrl(!A),
                method: C,
                params: A ? this.getParams() : null,
                isUpload: this.form.fileUpload
            }))
        } else {
            if (B.clientValidation !== false) {
                this.failureType = Ext.form.Action.CLIENT_INVALID;
                this.form.afterAction(this, false)
            }
        }
    }, success: function (B) {
        var A = this.processResponse(B);
        if (A === true || A.success) {
            this.form.afterAction(this, true);
            return
        }
        if (A.errors) {
            this.form.markInvalid(A.errors);
            this.failureType = Ext.form.Action.SERVER_INVALID
        }
        this.form.afterAction(this, false)
    }, handleResponse: function (C) {
        if (this.form.errorReader) {
            var B = this.form.errorReader.read(C);
            var F = [];
            if (B.records) {
                for (var D = 0, A = B.records.length; D < A; D++) {
                    var E = B.records[D];
                    F[D] = E.data
                }
            }
            if (F.length < 1) {
                F = null
            }
            return {success: B.success, errors: F}
        }
        return Ext.decode(C.responseText)
    }
});
Ext.form.Action.Load = function (B, A) {
    Ext.form.Action.Load.superclass.constructor.call(this, B, A);
    this.reader = this.form.reader
};
Ext.extend(Ext.form.Action.Load, Ext.form.Action, {
    type: "load", run: function () {
        Ext.Ajax.request(Ext.apply(this.createCallback(this.options), {
            method: this.getMethod(),
            url: this.getUrl(false),
            params: this.getParams()
        }))
    }, success: function (B) {
        var A = this.processResponse(B);
        if (A === true || !A.success || !A.data) {
            this.failureType = Ext.form.Action.LOAD_FAILURE;
            this.form.afterAction(this, false);
            return
        }
        this.form.clearInvalid();
        this.form.setValues(A.data);
        this.form.afterAction(this, true)
    }, handleResponse: function (B) {
        if (this.form.reader) {
            var A = this.form.reader.read(B);
            var C = A.records && A.records[0] ? A.records[0].data : null;
            return {success: A.success, data: C}
        }
        return Ext.decode(B.responseText)
    }
});
Ext.form.Action.ACTION_TYPES = {"load": Ext.form.Action.Load, "submit": Ext.form.Action.Submit};