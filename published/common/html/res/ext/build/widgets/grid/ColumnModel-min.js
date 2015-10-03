/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.ColumnModel = function (A) {
    this.setConfig(A, true);
    this.defaultWidth = 100;
    this.defaultSortable = false;
    this.addEvents("widthchange", "headerchange", "hiddenchange", "columnmoved", "columnlockchange", "configchange");
    Ext.grid.ColumnModel.superclass.constructor.call(this)
};
Ext.extend(Ext.grid.ColumnModel, Ext.util.Observable, {
    getColumnId: function (A) {
        return this.config[A].id
    }, setConfig: function (C, B) {
        if (!B) {
            delete this.totalWidth;
            for (var D = 0, A = this.config.length; D < A; D++) {
                var E = this.config[D];
                if (E.editor) {
                    E.editor.destroy()
                }
            }
        }
        this.config = C;
        this.lookup = {};
        for (var D = 0, A = C.length; D < A; D++) {
            var E = C[D];
            if (typeof E.renderer == "string") {
                E.renderer = Ext.util.Format[E.renderer]
            }
            if (typeof E.id == "undefined") {
                E.id = D
            }
            if (E.editor && E.editor.isFormField) {
                E.editor = new Ext.grid.GridEditor(E.editor)
            }
            this.lookup[E.id] = E
        }
        if (!B) {
            this.fireEvent("configchange", this)
        }
    }, getColumnById: function (A) {
        return this.lookup[A]
    }, getIndexById: function (C) {
        for (var B = 0, A = this.config.length; B < A; B++) {
            if (this.config[B].id == C) {
                return B
            }
        }
        return -1
    }, moveColumn: function (C, A) {
        var B = this.config[C];
        this.config.splice(C, 1);
        this.config.splice(A, 0, B);
        this.dataMap = null;
        this.fireEvent("columnmoved", this, C, A)
    }, isLocked: function (A) {
        return this.config[A].locked === true
    }, setLocked: function (B, C, A) {
        if (this.isLocked(B) == C) {
            return
        }
        this.config[B].locked = C;
        if (!A) {
            this.fireEvent("columnlockchange", this, B, C)
        }
    }, getTotalLockedWidth: function () {
        var A = 0;
        for (var B = 0; B < this.config.length; B++) {
            if (this.isLocked(B) && !this.isHidden(B)) {
                this.totalWidth += this.getColumnWidth(B)
            }
        }
        return A
    }, getLockedCount: function () {
        for (var B = 0, A = this.config.length; B < A; B++) {
            if (!this.isLocked(B)) {
                return B
            }
        }
    }, getColumnCount: function (C) {
        if (C === true) {
            var D = 0;
            for (var B = 0, A = this.config.length; B < A; B++) {
                if (!this.isHidden(B)) {
                    D++
                }
            }
            return D
        }
        return this.config.length
    }, getColumnsBy: function (D, C) {
        var E = [];
        for (var B = 0, A = this.config.length; B < A; B++) {
            var F = this.config[B];
            if (D.call(C || this, F, B) === true) {
                E[E.length] = F
            }
        }
        return E
    }, isSortable: function (A) {
        if (typeof this.config[A].sortable == "undefined") {
            return this.defaultSortable
        }
        return this.config[A].sortable
    }, getRenderer: function (A) {
        if (!this.config[A].renderer) {
            return Ext.grid.ColumnModel.defaultRenderer
        }
        return this.config[A].renderer
    }, setRenderer: function (A, B) {
        this.config[A].renderer = B
    }, getColumnWidth: function (A) {
        return this.config[A].width || this.defaultWidth
    }, setColumnWidth: function (B, C, A) {
        this.config[B].width = C;
        this.totalWidth = null;
        if (!A) {
            this.fireEvent("widthchange", this, B, C)
        }
    }, getTotalWidth: function (B) {
        if (!this.totalWidth) {
            this.totalWidth = 0;
            for (var C = 0, A = this.config.length; C < A; C++) {
                if (B || !this.isHidden(C)) {
                    this.totalWidth += this.getColumnWidth(C)
                }
            }
        }
        return this.totalWidth
    }, getColumnHeader: function (A) {
        return this.config[A].header
    }, setColumnHeader: function (A, B) {
        this.config[A].header = B;
        this.fireEvent("headerchange", this, A, B)
    }, getColumnTooltip: function (A) {
        return this.config[A].tooltip
    }, setColumnTooltip: function (A, B) {
        this.config[A].tooltip = B
    }, getDataIndex: function (A) {
        return this.config[A].dataIndex
    }, setDataIndex: function (A, B) {
        this.config[A].dataIndex = B
    }, findColumnIndex: function (C) {
        var D = this.config;
        for (var B = 0, A = D.length; B < A; B++) {
            if (D[B].dataIndex == C) {
                return B
            }
        }
        return -1
    }, isCellEditable: function (A, B) {
        return (this.config[A].editable || (typeof this.config[A].editable == "undefined" && this.config[A].editor)) ? true : false
    }, getCellEditor: function (A, B) {
        return this.config[A].editor
    }, setEditable: function (A, B) {
        this.config[A].editable = B
    }, isHidden: function (A) {
        return this.config[A].hidden
    }, isFixed: function (A) {
        return this.config[A].fixed
    }, isResizable: function (A) {
        return A >= 0 && this.config[A].resizable !== false && this.config[A].fixed !== true
    }, setHidden: function (A, B) {
        var C = this.config[A];
        if (C.hidden !== B) {
            C.hidden = B;
            this.totalWidth = null;
            this.fireEvent("hiddenchange", this, A, B)
        }
    }, setEditor: function (A, B) {
        this.config[A].editor = B
    }
});
Ext.grid.ColumnModel.defaultRenderer = function (A) {
    if (typeof A == "string" && A.length < 1) {
        return "&#160;"
    }
    return A
};
Ext.grid.DefaultColumnModel = Ext.grid.ColumnModel;