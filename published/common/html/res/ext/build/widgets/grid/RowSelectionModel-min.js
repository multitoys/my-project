/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.RowSelectionModel = function (A) {
    Ext.apply(this, A);
    this.selections = new Ext.util.MixedCollection(false, function (B) {
        return B.id
    });
    this.last = false;
    this.lastActive = false;
    this.addEvents("selectionchange", "beforerowselect", "rowselect", "rowdeselect");
    Ext.grid.RowSelectionModel.superclass.constructor.call(this)
};
Ext.extend(Ext.grid.RowSelectionModel, Ext.grid.AbstractSelectionModel, {
    singleSelect: false, initEvents: function () {
        if (!this.grid.enableDragDrop && !this.grid.enableDrag) {
            this.grid.on("rowmousedown", this.handleMouseDown, this)
        } else {
            this.grid.on("rowclick", function (B, D, C) {
                if (C.button === 0 && !C.shiftKey && !C.ctrlKey) {
                    this.selectRow(D, false);
                    B.view.focusRow(D)
                }
            }, this)
        }
        this.rowNav = new Ext.KeyNav(this.grid.getGridEl(), {
            "up": function (C) {
                if (!C.shiftKey) {
                    this.selectPrevious(C.shiftKey)
                } else {
                    if (this.last !== false && this.lastActive !== false) {
                        var B = this.last;
                        this.selectRange(this.last, this.lastActive - 1);
                        this.grid.getView().focusRow(this.lastActive);
                        if (B !== false) {
                            this.last = B
                        }
                    } else {
                        this.selectFirstRow()
                    }
                }
            }, "down": function (C) {
                if (!C.shiftKey) {
                    this.selectNext(C.shiftKey)
                } else {
                    if (this.last !== false && this.lastActive !== false) {
                        var B = this.last;
                        this.selectRange(this.last, this.lastActive + 1);
                        this.grid.getView().focusRow(this.lastActive);
                        if (B !== false) {
                            this.last = B
                        }
                    } else {
                        this.selectFirstRow()
                    }
                }
            }, scope: this
        });
        var A = this.grid.view;
        A.on("refresh", this.onRefresh, this);
        A.on("rowupdated", this.onRowUpdated, this);
        A.on("rowremoved", this.onRemove, this)
    }, onRefresh: function () {
        var F = this.grid.store, B;
        var D = this.getSelections();
        this.clearSelections(true);
        for (var C = 0, A = D.length; C < A; C++) {
            var E = D[C];
            if ((B = F.indexOfId(E.id)) != -1) {
                this.selectRow(B, true)
            }
        }
        if (D.length != this.selections.getCount()) {
            this.fireEvent("selectionchange", this)
        }
    }, onRemove: function (A, B, C) {
        if (this.selections.remove(C) !== false) {
            this.fireEvent("selectionchange", this)
        }
    }, onRowUpdated: function (A, B, C) {
        if (this.isSelected(C)) {
            A.onRowSelect(B)
        }
    }, selectRecords: function (B, E) {
        if (!E) {
            this.clearSelections()
        }
        var D = this.grid.store;
        for (var C = 0, A = B.length; C < A; C++) {
            this.selectRow(D.indexOf(B[C]), true)
        }
    }, getCount: function () {
        return this.selections.length
    }, selectFirstRow: function () {
        this.selectRow(0)
    }, selectLastRow: function (A) {
        this.selectRow(this.grid.store.getCount() - 1, A)
    }, selectNext: function (A) {
        if (this.hasNext()) {
            this.selectRow(this.last + 1, A);
            this.grid.getView().focusRow(this.last);
            return true
        }
        return false
    }, selectPrevious: function (A) {
        if (this.hasPrevious()) {
            this.selectRow(this.last - 1, A);
            this.grid.getView().focusRow(this.last);
            return true
        }
        return false
    }, hasNext: function () {
        return this.last !== false && (this.last + 1) < this.grid.store.getCount()
    }, hasPrevious: function () {
        return !!this.last
    }, getSelections: function () {
        return [].concat(this.selections.items)
    }, getSelected: function () {
        return this.selections.itemAt(0)
    }, each: function (E, D) {
        var C = this.getSelections();
        for (var B = 0, A = C.length; B < A; B++) {
            if (E.call(D || this, C[B], B) === false) {
                return false
            }
        }
        return true
    }, clearSelections: function (A) {
        if (this.locked) {
            return
        }
        if (A !== true) {
            var C = this.grid.store;
            var B = this.selections;
            B.each(function (D) {
                this.deselectRow(C.indexOfId(D.id))
            }, this);
            B.clear()
        } else {
            this.selections.clear()
        }
        this.last = false
    }, selectAll: function () {
        if (this.locked) {
            return
        }
        this.selections.clear();
        for (var B = 0, A = this.grid.store.getCount(); B < A; B++) {
            this.selectRow(B, true)
        }
    }, hasSelection: function () {
        return this.selections.length > 0
    }, isSelected: function (A) {
        var B = typeof A == "number" ? this.grid.store.getAt(A) : A;
        return (B && this.selections.key(B.id) ? true : false)
    }, isIdSelected: function (A) {
        return (this.selections.key(A) ? true : false)
    }, handleMouseDown: function (D, F, E) {
        if (E.button !== 0 || this.isLocked()) {
            return
        }
        var A = this.grid.getView();
        if (E.shiftKey && this.last !== false) {
            var C = this.last;
            this.selectRange(C, F, E.ctrlKey);
            this.last = C;
            A.focusRow(F)
        } else {
            var B = this.isSelected(F);
            if (E.ctrlKey && B) {
                this.deselectRow(F)
            } else {
                if (!B || this.getCount() > 1) {
                    this.selectRow(F, E.ctrlKey || E.shiftKey);
                    A.focusRow(F)
                }
            }
        }
    }, selectRows: function (C, D) {
        if (!D) {
            this.clearSelections()
        }
        for (var B = 0, A = C.length; B < A; B++) {
            this.selectRow(C[B], true)
        }
    }, selectRange: function (B, A, D) {
        if (this.locked) {
            return
        }
        if (!D) {
            this.clearSelections()
        }
        if (B <= A) {
            for (var C = B; C <= A; C++) {
                this.selectRow(C, true)
            }
        } else {
            for (var C = B; C >= A; C--) {
                this.selectRow(C, true)
            }
        }
    }, deselectRange: function (C, B, A) {
        if (this.locked) {
            return
        }
        for (var D = C; D <= B; D++) {
            this.deselectRow(D, A)
        }
    }, selectRow: function (B, D, A) {
        if (this.locked || (B < 0 || B >= this.grid.store.getCount())) {
            return
        }
        var C = this.grid.store.getAt(B);
        if (C && this.fireEvent("beforerowselect", this, B, D, C) !== false) {
            if (!D || this.singleSelect) {
                this.clearSelections()
            }
            this.selections.add(C);
            this.last = this.lastActive = B;
            if (!A) {
                this.grid.getView().onRowSelect(B)
            }
            this.fireEvent("rowselect", this, B, C);
            this.fireEvent("selectionchange", this)
        }
    }, deselectRow: function (B, A) {
        if (this.locked) {
            return
        }
        if (this.last == B) {
            this.last = false
        }
        if (this.lastActive == B) {
            this.lastActive = false
        }
        var C = this.grid.store.getAt(B);
        if (C) {
            this.selections.remove(C);
            if (!A) {
                this.grid.getView().onRowDeselect(B)
            }
            this.fireEvent("rowdeselect", this, B, C);
            this.fireEvent("selectionchange", this)
        }
    }, restoreLast: function () {
        if (this._last) {
            this.last = this._last
        }
    }, acceptsNav: function (C, B, A) {
        return !A.isHidden(B) && A.isCellEditable(B, C)
    }, onEditorKey: function (F, E) {
        var C = E.getKey(), G, D = this.grid, B = D.activeEditor;
        var A = E.shiftKey;
        if (C == E.TAB) {
            E.stopEvent();
            B.completeEdit();
            if (A) {
                G = D.walkCells(B.row, B.col - 1, -1, this.acceptsNav, this)
            } else {
                G = D.walkCells(B.row, B.col + 1, 1, this.acceptsNav, this)
            }
        } else {
            if (C == E.ENTER) {
                E.stopEvent();
                B.completeEdit();
                if (A) {
                    G = D.walkCells(B.row - 1, B.col, -1, this.acceptsNav, this)
                } else {
                    G = D.walkCells(B.row + 1, B.col, 1, this.acceptsNav, this)
                }
            } else {
                if (C == E.ESC) {
                    B.cancelEdit()
                }
            }
        }
        if (G) {
            D.startEditing(G[0], G[1])
        }
    }
});