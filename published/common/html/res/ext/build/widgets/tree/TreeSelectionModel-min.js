/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.tree.DefaultSelectionModel = function (A) {
    this.selNode = null;
    this.addEvents("selectionchange", "beforeselect");
    Ext.apply(this, A);
    Ext.tree.DefaultSelectionModel.superclass.constructor.call(this)
};
Ext.extend(Ext.tree.DefaultSelectionModel, Ext.util.Observable, {
    init: function (A) {
        this.tree = A;
        A.getTreeEl().on("keydown", this.onKeyDown, this);
        A.on("click", this.onNodeClick, this)
    }, onNodeClick: function (A, B) {
        this.select(A)
    }, select: function (B) {
        var A = this.selNode;
        if (A != B && this.fireEvent("beforeselect", this, B, A) !== false) {
            if (A) {
                A.ui.onSelectedChange(false)
            }
            this.selNode = B;
            B.ui.onSelectedChange(true);
            this.fireEvent("selectionchange", this, B, A)
        }
        return B
    }, unselect: function (A) {
        if (this.selNode == A) {
            this.clearSelections()
        }
    }, clearSelections: function () {
        var A = this.selNode;
        if (A) {
            A.ui.onSelectedChange(false);
            this.selNode = null;
            this.fireEvent("selectionchange", this, null)
        }
        return A
    }, getSelectedNode: function () {
        return this.selNode
    }, isSelected: function (A) {
        return this.selNode == A
    }, selectPrevious: function () {
        var A = this.selNode || this.lastSelNode;
        if (!A) {
            return null
        }
        var C = A.previousSibling;
        if (C) {
            if (!C.isExpanded() || C.childNodes.length < 1) {
                return this.select(C)
            } else {
                var B = C.lastChild;
                while (B && B.isExpanded() && B.childNodes.length > 0) {
                    B = B.lastChild
                }
                return this.select(B)
            }
        } else {
            if (A.parentNode && (this.tree.rootVisible || !A.parentNode.isRoot)) {
                return this.select(A.parentNode)
            }
        }
        return null
    }, selectNext: function () {
        var B = this.selNode || this.lastSelNode;
        if (!B) {
            return null
        }
        if (B.firstChild && B.isExpanded()) {
            return this.select(B.firstChild)
        } else {
            if (B.nextSibling) {
                return this.select(B.nextSibling)
            } else {
                if (B.parentNode) {
                    var A = null;
                    B.parentNode.bubble(function () {
                        if (this.nextSibling) {
                            A = this.getOwnerTree().selModel.select(this.nextSibling);
                            return false
                        }
                    });
                    return A
                }
            }
        }
        return null
    }, onKeyDown: function (C) {
        var B = this.selNode || this.lastSelNode;
        var D = this;
        if (!B) {
            return
        }
        var A = C.getKey();
        switch (A) {
            case C.DOWN:
                C.stopEvent();
                this.selectNext();
                break;
            case C.UP:
                C.stopEvent();
                this.selectPrevious();
                break;
            case C.RIGHT:
                C.preventDefault();
                if (B.hasChildNodes()) {
                    if (!B.isExpanded()) {
                        B.expand()
                    } else {
                        if (B.firstChild) {
                            this.select(B.firstChild, C)
                        }
                    }
                }
                break;
            case C.LEFT:
                C.preventDefault();
                if (B.hasChildNodes() && B.isExpanded()) {
                    B.collapse()
                } else {
                    if (B.parentNode && (this.tree.rootVisible || B.parentNode != this.tree.getRootNode())) {
                        this.select(B.parentNode, C)
                    }
                }
                break
        }
    }
});
Ext.tree.MultiSelectionModel = function (A) {
    this.selNodes = [];
    this.selMap = {};
    this.addEvents("selectionchange");
    Ext.apply(this, A);
    Ext.tree.MultiSelectionModel.superclass.constructor.call(this)
};
Ext.extend(Ext.tree.MultiSelectionModel, Ext.util.Observable, {
    init: function (A) {
        this.tree = A;
        A.getTreeEl().on("keydown", this.onKeyDown, this);
        A.on("click", this.onNodeClick, this)
    },
    onNodeClick: function (A, B) {
        this.select(A, B, B.ctrlKey)
    },
    select: function (A, C, B) {
        if (B !== true) {
            this.clearSelections(true)
        }
        if (this.isSelected(A)) {
            this.lastSelNode = A;
            return A
        }
        this.selNodes.push(A);
        this.selMap[A.id] = A;
        this.lastSelNode = A;
        A.ui.onSelectedChange(true);
        this.fireEvent("selectionchange", this, this.selNodes);
        return A
    },
    unselect: function (B) {
        if (this.selMap[B.id]) {
            B.ui.onSelectedChange(false);
            var C = this.selNodes;
            var A = C.indexOf(B);
            if (A != -1) {
                this.selNodes.splice(A, 1)
            }
            delete this.selMap[B.id];
            this.fireEvent("selectionchange", this, this.selNodes)
        }
    },
    clearSelections: function (B) {
        var D = this.selNodes;
        if (D.length > 0) {
            for (var C = 0, A = D.length; C < A; C++) {
                D[C].ui.onSelectedChange(false)
            }
            this.selNodes = [];
            this.selMap = {};
            if (B !== true) {
                this.fireEvent("selectionchange", this, this.selNodes)
            }
        }
    },
    isSelected: function (A) {
        return this.selMap[A.id] ? true : false
    },
    getSelectedNodes: function () {
        return this.selNodes
    },
    onKeyDown: Ext.tree.DefaultSelectionModel.prototype.onKeyDown,
    selectNext: Ext.tree.DefaultSelectionModel.prototype.selectNext,
    selectPrevious: Ext.tree.DefaultSelectionModel.prototype.selectPrevious
});