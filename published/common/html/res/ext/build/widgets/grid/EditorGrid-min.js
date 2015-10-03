/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.EditorGridPanel = Ext.extend(Ext.grid.GridPanel, {
    clicksToEdit: 2,
    isEditor: true,
    detectEdit: false,
    autoEncode: false,
    trackMouseOver: false,
    initComponent: function () {
        Ext.grid.EditorGridPanel.superclass.initComponent.call(this);
        if (!this.selModel) {
            this.selModel = new Ext.grid.CellSelectionModel()
        }
        this.activeEditor = null;
        this.addEvents("beforeedit", "afteredit", "validateedit")
    },
    initEvents: function () {
        Ext.grid.EditorGridPanel.superclass.initEvents.call(this);
        this.on("bodyscroll", this.stopEditing, this);
        if (this.clicksToEdit == 1) {
            this.on("cellclick", this.onCellDblClick, this)
        } else {
            if (this.clicksToEdit == "auto" && this.view.mainBody) {
                this.view.mainBody.on("mousedown", this.onAutoEditClick, this)
            }
            this.on("celldblclick", this.onCellDblClick, this)
        }
        this.getGridEl().addClass("xedit-grid")
    },
    onCellDblClick: function (B, C, A) {
        this.startEditing(C, A)
    },
    onAutoEditClick: function (C, B) {
        var E = this.view.findRowIndex(B);
        var A = this.view.findCellIndex(B);
        if (E !== false && A !== false) {
            if (this.selModel.getSelectedCell) {
                var D = this.selModel.getSelectedCell();
                if (D && D.cell[0] === E && D.cell[1] === A) {
                    this.startEditing(E, A)
                }
            } else {
                if (this.selModel.isSelected(E)) {
                    this.startEditing(E, A)
                }
            }
        }
    },
    onEditComplete: function (B, D, A) {
        this.editing = false;
        this.activeEditor = null;
        B.un("specialkey", this.selModel.onEditorKey, this.selModel);
        var C = B.record;
        var F = this.colModel.getDataIndex(B.col);
        D = this.postEditValue(D, A, C, F);
        if (String(D) !== String(A)) {
            var E = {
                grid: this,
                record: C,
                field: F,
                originalValue: A,
                value: D,
                row: B.row,
                column: B.col,
                cancel: false
            };
            if (this.fireEvent("validateedit", E) !== false && !E.cancel) {
                C.set(F, E.value);
                delete E.cancel;
                this.fireEvent("afteredit", E)
            }
        }
        this.view.focusCell(B.row, B.col)
    },
    startEditing: function (F, B) {
        this.stopEditing();
        if (this.colModel.isCellEditable(B, F)) {
            this.view.ensureVisible(F, B, true);
            var C = this.store.getAt(F);
            var E = this.colModel.getDataIndex(B);
            var D = {grid: this, record: C, field: E, value: C.data[E], row: F, column: B, cancel: false};
            if (this.fireEvent("beforeedit", D) !== false && !D.cancel) {
                this.editing = true;
                var A = this.colModel.getCellEditor(B, F);
                if (!A.rendered) {
                    A.render(this.view.getEditorParent(A))
                }
                (function () {
                    A.row = F;
                    A.col = B;
                    A.record = C;
                    A.on("complete", this.onEditComplete, this, {single: true});
                    A.on("specialkey", this.selModel.onEditorKey, this.selModel);
                    this.activeEditor = A;
                    var G = this.preEditValue(C, E);
                    A.startEdit(this.view.getCell(F, B), G)
                }).defer(50, this)
            }
        }
    },
    preEditValue: function (A, B) {
        return this.autoEncode ? Ext.util.Format.htmlDecode(A.data[B]) : A.data[B]
    },
    postEditValue: function (C, A, B, D) {
        return this.autoEncode ? Ext.util.Format.htmlEncode(C) : C
    },
    stopEditing: function () {
        if (this.activeEditor) {
            this.activeEditor.completeEdit()
        }
        this.activeEditor = null
    }
});
Ext.reg("editorgrid", Ext.grid.EditorGridPanel);