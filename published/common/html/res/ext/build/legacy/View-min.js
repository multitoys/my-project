/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.View = function (A, C, B) {
    this.el = Ext.get(A);
    if (typeof C == "string") {
        C = new Ext.Template(C)
    }
    C.compile();
    this.tpl = C;
    Ext.apply(this, B);
    this.addEvents({
        "beforeclick": true,
        "click": true,
        "dblclick": true,
        "contextmenu": true,
        "selectionchange": true,
        "beforeselect": true
    });
    this.el.on({"click": this.onClick, "dblclick": this.onDblClick, "contextmenu": this.onContextMenu, scope: this});
    this.selections = [];
    this.nodes = [];
    this.cmp = new Ext.CompositeElementLite([]);
    if (this.store) {
        this.setStore(this.store, true)
    }
    Ext.View.superclass.constructor.call(this)
};
Ext.extend(Ext.View, Ext.util.Observable, {
    selectedClass: "x-view-selected", emptyText: "", getEl: function () {
        return this.el
    }, refresh: function () {
        var E = this.tpl;
        this.clearSelections();
        this.el.update("");
        var D = [];
        var B = this.store.getRange();
        if (B.length < 1) {
            this.el.update(this.emptyText);
            return
        }
        for (var C = 0, A = B.length; C < A; C++) {
            var F = this.prepareData(B[C].data, C, B[C]);
            D[D.length] = E.apply(F)
        }
        this.el.update(D.join(""));
        this.nodes = this.el.dom.childNodes;
        this.updateIndexes(0)
    }, prepareData: function (A) {
        return A
    }, onUpdate: function (C, A) {
        this.clearSelections();
        var B = this.store.indexOf(A);
        var D = this.nodes[B];
        this.tpl.insertBefore(D, this.prepareData(A.data));
        D.parentNode.removeChild(D);
        this.updateIndexes(B, B)
    }, onAdd: function (E, B, C) {
        this.clearSelections();
        if (this.nodes.length == 0) {
            this.refresh();
            return
        }
        var G = this.nodes[C];
        for (var D = 0, A = B.length; D < A; D++) {
            var F = this.prepareData(B[D].data);
            if (G) {
                this.tpl.insertBefore(G, F)
            } else {
                this.tpl.append(this.el, F)
            }
        }
        this.updateIndexes(C)
    }, onRemove: function (C, A, B) {
        this.clearSelections();
        this.el.dom.removeChild(this.nodes[B]);
        this.updateIndexes(B)
    }, refreshNode: function (A) {
        this.onUpdate(this.store, this.store.getAt(A))
    }, updateIndexes: function (D, C) {
        var B = this.nodes;
        D = D || 0;
        C = C || B.length - 1;
        for (var A = D; A <= C; A++) {
            B[A].nodeIndex = A
        }
    }, setStore: function (A, B) {
        if (!B && this.store) {
            this.store.un("datachanged", this.refresh, this);
            this.store.un("add", this.onAdd, this);
            this.store.un("remove", this.onRemove, this);
            this.store.un("update", this.onUpdate, this);
            this.store.un("clear", this.refresh, this)
        }
        if (A) {
            A.on("datachanged", this.refresh, this);
            A.on("add", this.onAdd, this);
            A.on("remove", this.onRemove, this);
            A.on("update", this.onUpdate, this);
            A.on("clear", this.refresh, this)
        }
        this.store = A;
        if (A) {
            this.refresh()
        }
    }, findItemFromChild: function (B) {
        var A = this.el.dom;
        if (!B || B.parentNode == A) {
            return B
        }
        var C = B.parentNode;
        while (C && C != A) {
            if (C.parentNode == A) {
                return C
            }
            C = C.parentNode
        }
        return null
    }, onClick: function (C) {
        var B = this.findItemFromChild(C.getTarget());
        if (B) {
            var A = this.indexOf(B);
            if (this.onItemClick(B, A, C) !== false) {
                this.fireEvent("click", this, A, B, C)
            }
        } else {
            this.clearSelections()
        }
    }, onContextMenu: function (B) {
        var A = this.findItemFromChild(B.getTarget());
        if (A) {
            this.fireEvent("contextmenu", this, this.indexOf(A), A, B)
        }
    }, onDblClick: function (B) {
        var A = this.findItemFromChild(B.getTarget());
        if (A) {
            this.fireEvent("dblclick", this, this.indexOf(A), A, B)
        }
    }, onItemClick: function (B, A, C) {
        if (this.fireEvent("beforeclick", this, A, B, C) === false) {
            return false
        }
        if (this.multiSelect || this.singleSelect) {
            if (this.multiSelect && C.shiftKey && this.lastSelection) {
                this.select(this.getNodes(this.indexOf(this.lastSelection), A), false)
            } else {
                this.select(B, this.multiSelect && C.ctrlKey);
                this.lastSelection = B
            }
            C.preventDefault()
        }
        return true
    }, getSelectionCount: function () {
        return this.selections.length
    }, getSelectedNodes: function () {
        return this.selections
    }, getSelectedIndexes: function () {
        var B = [], D = this.selections;
        for (var C = 0, A = D.length; C < A; C++) {
            B.push(D[C].nodeIndex)
        }
        return B
    }, clearSelections: function (A) {
        if (this.nodes && (this.multiSelect || this.singleSelect) && this.selections.length > 0) {
            this.cmp.elements = this.selections;
            this.cmp.removeClass(this.selectedClass);
            this.selections = [];
            if (!A) {
                this.fireEvent("selectionchange", this, this.selections)
            }
        }
    }, isSelected: function (B) {
        var A = this.selections;
        if (A.length < 1) {
            return false
        }
        B = this.getNode(B);
        return A.indexOf(B) !== -1
    }, select: function (D, F, B) {
        if (D instanceof Array) {
            if (!F) {
                this.clearSelections(true)
            }
            for (var C = 0, A = D.length; C < A; C++) {
                this.select(D[C], true, true)
            }
        } else {
            var E = this.getNode(D);
            if (E && !this.isSelected(E)) {
                if (!F) {
                    this.clearSelections(true)
                }
                if (this.fireEvent("beforeselect", this, E, this.selections) !== false) {
                    Ext.fly(E).addClass(this.selectedClass);
                    this.selections.push(E);
                    if (!B) {
                        this.fireEvent("selectionchange", this, this.selections)
                    }
                }
            }
        }
    }, getNode: function (A) {
        if (typeof A == "string") {
            return document.getElementById(A)
        } else {
            if (typeof A == "number") {
                return this.nodes[A]
            }
        }
        return A
    }, getNodes: function (E, A) {
        var D = this.nodes;
        E = E || 0;
        A = typeof A == "undefined" ? D.length - 1 : A;
        var B = [];
        if (E <= A) {
            for (var C = E; C <= A; C++) {
                B.push(D[C])
            }
        } else {
            for (var C = E; C >= A; C--) {
                B.push(D[C])
            }
        }
        return B
    }, indexOf: function (D) {
        D = this.getNode(D);
        if (typeof D.nodeIndex == "number") {
            return D.nodeIndex
        }
        var C = this.nodes;
        for (var B = 0, A = C.length; B < A; B++) {
            if (C[B] == D) {
                return B
            }
        }
        return -1
    }
});