/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.Updater = function (B, A) {
    B = Ext.get(B);
    if (!A && B.updateManager) {
        return B.updateManager
    }
    this.el = B;
    this.defaultUrl = null;
    this.addEvents("beforeupdate", "update", "failure");
    var C = Ext.Updater.defaults;
    this.sslBlankUrl = C.sslBlankUrl;
    this.disableCaching = C.disableCaching;
    this.indicatorText = C.indicatorText;
    this.showLoadIndicator = C.showLoadIndicator;
    this.timeout = C.timeout;
    this.loadScripts = C.loadScripts;
    this.transaction = null;
    this.autoRefreshProcId = null;
    this.refreshDelegate = this.refresh.createDelegate(this);
    this.updateDelegate = this.update.createDelegate(this);
    this.formUpdateDelegate = this.formUpdate.createDelegate(this);
    if (!this.renderer) {
        this.renderer = new Ext.Updater.BasicRenderer()
    }
    Ext.Updater.superclass.constructor.call(this)
};
Ext.extend(Ext.Updater, Ext.util.Observable, {
    getEl: function () {
        return this.el
    }, update: function (B, F, H, D) {
        if (this.fireEvent("beforeupdate", this.el, B, F) !== false) {
            var G = this.method, A, C;
            if (typeof B == "object") {
                A = B;
                B = A.url;
                F = F || A.params;
                H = H || A.callback;
                D = D || A.discardUrl;
                C = A.scope;
                if (typeof A.method != "undefined") {
                    G = A.method
                }
                if (typeof A.nocache != "undefined") {
                    this.disableCaching = A.nocache
                }
                if (typeof A.text != "undefined") {
                    this.indicatorText = "<div class=\"loading-indicator\">" + A.text + "</div>"
                }
                if (typeof A.scripts != "undefined") {
                    this.loadScripts = A.scripts
                }
                if (typeof A.timeout != "undefined") {
                    this.timeout = A.timeout
                }
            }
            this.showLoading();
            if (!D) {
                this.defaultUrl = B
            }
            if (typeof B == "function") {
                B = B.call(this)
            }
            G = G || (F ? "POST" : "GET");
            if (G == "GET") {
                B = this.prepareUrl(B)
            }
            var E = Ext.apply(A || {}, {
                url: B,
                params: (typeof F == "function" && C) ? F.createDelegate(C) : F,
                success: this.processSuccess,
                failure: this.processFailure,
                scope: this,
                callback: undefined,
                timeout: (this.timeout * 1000),
                argument: {"options": A, "url": B, "form": null, "callback": H, "scope": C || window, "params": F}
            });
            this.transaction = Ext.Ajax.request(E)
        }
    }, formUpdate: function (C, A, B, D) {
        if (this.fireEvent("beforeupdate", this.el, C, A) !== false) {
            if (typeof A == "function") {
                A = A.call(this)
            }
            C = Ext.getDom(C);
            this.transaction = Ext.Ajax.request({
                form: C,
                url: A,
                success: this.processSuccess,
                failure: this.processFailure,
                scope: this,
                timeout: (this.timeout * 1000),
                argument: {"url": A, "form": C, "callback": D, "reset": B}
            });
            this.showLoading.defer(1, this)
        }
    }, refresh: function (A) {
        if (this.defaultUrl == null) {
            return
        }
        this.update(this.defaultUrl, null, A, true)
    }, startAutoRefresh: function (B, C, D, E, A) {
        if (A) {
            this.update(C || this.defaultUrl, D, E, true)
        }
        if (this.autoRefreshProcId) {
            clearInterval(this.autoRefreshProcId)
        }
        this.autoRefreshProcId = setInterval(this.update.createDelegate(this, [C || this.defaultUrl, D, E, true]), B * 1000)
    }, stopAutoRefresh: function () {
        if (this.autoRefreshProcId) {
            clearInterval(this.autoRefreshProcId);
            delete this.autoRefreshProcId
        }
    }, isAutoRefreshing: function () {
        return this.autoRefreshProcId ? true : false
    }, showLoading: function () {
        if (this.showLoadIndicator) {
            this.el.update(this.indicatorText)
        }
    }, prepareUrl: function (B) {
        if (this.disableCaching) {
            var A = "_dc=" + (new Date().getTime());
            if (B.indexOf("?") !== -1) {
                B += "&" + A
            } else {
                B += "?" + A
            }
        }
        return B
    }, processSuccess: function (A) {
        this.transaction = null;
        if (A.argument.form && A.argument.reset) {
            try {
                A.argument.form.reset()
            } catch (B) {
            }
        }
        if (this.loadScripts) {
            this.renderer.render(this.el, A, this, this.updateComplete.createDelegate(this, [A]))
        } else {
            this.renderer.render(this.el, A, this);
            this.updateComplete(A)
        }
    }, updateComplete: function (A) {
        this.fireEvent("update", this.el, A);
        if (typeof A.argument.callback == "function") {
            A.argument.callback.call(A.argument.scope, this.el, true, A, A.argument.options)
        }
    }, processFailure: function (A) {
        this.transaction = null;
        this.fireEvent("failure", this.el, A);
        if (typeof A.argument.callback == "function") {
            A.argument.callback.call(A.argument.scope, this.el, false, A, A.argument.options)
        }
    }, setRenderer: function (A) {
        this.renderer = A
    }, getRenderer: function () {
        return this.renderer
    }, setDefaultUrl: function (A) {
        this.defaultUrl = A
    }, abort: function () {
        if (this.transaction) {
            Ext.Ajax.abort(this.transaction)
        }
    }, isUpdating: function () {
        if (this.transaction) {
            return Ext.Ajax.isLoading(this.transaction)
        }
        return false
    }
});
Ext.Updater.defaults = {
    timeout: 30,
    loadScripts: false,
    sslBlankUrl: (Ext.SSL_SECURE_URL || "javascript:false"),
    disableCaching: false,
    showLoadIndicator: true,
    indicatorText: "<div class=\"loading-indicator\">Loading...</div>"
};
Ext.Updater.updateElement = function (D, C, E, B) {
    var A = Ext.get(D).getUpdater();
    Ext.apply(A, B);
    A.update(C, E, B ? B.callback : null)
};
Ext.Updater.update = Ext.Updater.updateElement;
Ext.Updater.BasicRenderer = function () {
};
Ext.Updater.BasicRenderer.prototype = {
    render: function (C, A, B, D) {
        C.update(A.responseText, B.loadScripts, D)
    }
};
Ext.UpdateManager = Ext.Updater;