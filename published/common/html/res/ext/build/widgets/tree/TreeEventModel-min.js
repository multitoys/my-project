/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.tree.TreeEventModel = function (A) {
    this.tree = A;
    this.tree.on("render", this.initEvents, this)
};
Ext.tree.TreeEventModel.prototype = {
    initEvents: function () {
        var A = this.tree.getTreeEl();
        A.on("click", this.delegateClick, this);
        A.on("mouseover", this.delegateOver, this);
        A.on("mouseout", this.delegateOut, this);
        A.on("dblclick", this.delegateDblClick, this);
        A.on("contextmenu", this.delegateContextMenu, this)
    }, getNode: function (B) {
        var A;
        if (A = B.getTarget(".x-tree-node-el", 10)) {
            var C = Ext.fly(A, "_treeEvents").getAttributeNS("ext", "tree-node-id");
            if (C) {
                return this.tree.getNodeById(C)
            }
        }
        return null
    }, getNodeTarget: function (B) {
        var A = B.getTarget(".x-tree-node-icon", 1);
        if (!A) {
            A = B.getTarget(".x-tree-node-el", 6)
        }
        return A
    }, delegateOut: function (B, A) {
        if (!this.beforeEvent(B)) {
            return
        }
        A = this.getNodeTarget(B);
        if (A && !B.within(A, true)) {
            this.onNodeOut(B, this.getNode(B))
        }
    }, delegateOver: function (B, A) {
        if (!this.beforeEvent(B)) {
            return
        }
        A = this.getNodeTarget(B);
        if (A) {
            this.onNodeOver(B, this.getNode(B))
        }
    }, delegateClick: function (B, A) {
        if (!this.beforeEvent(B)) {
            return
        }
        if (B.getTarget("input[type=checkbox]", 1)) {
            this.onCheckboxClick(B, this.getNode(B))
        } else {
            if (B.getTarget(".x-tree-ec-icon", 1)) {
                this.onIconClick(B, this.getNode(B))
            } else {
                if (this.getNodeTarget(B)) {
                    this.onNodeClick(B, this.getNode(B))
                }
            }
        }
    }, delegateDblClick: function (B, A) {
        if (this.beforeEvent(B) && this.getNodeTarget(B)) {
            this.onNodeDblClick(B, this.getNode(B))
        }
    }, delegateContextMenu: function (B, A) {
        if (this.beforeEvent(B) && this.getNodeTarget(B)) {
            this.onNodeContextMenu(B, this.getNode(B))
        }
    }, onNodeClick: function (B, A) {
        A.ui.onClick(B)
    }, onNodeOver: function (B, A) {
        A.ui.onOver(B)
    }, onNodeOut: function (B, A) {
        A.ui.onOut(B)
    }, onIconClick: function (B, A) {
        A.ui.ecClick(B)
    }, onCheckboxClick: function (B, A) {
        A.ui.onCheckChange(B)
    }, onNodeDblClick: function (B, A) {
        A.ui.onDblClick(B)
    }, onNodeContextMenu: function (B, A) {
        A.ui.onContextMenu(B)
    }, beforeEvent: function (A) {
        if (this.disabled) {
            A.stopEvent();
            return false
        }
        return true
    }, disable: function () {
        this.disabled = true
    }, enable: function () {
        this.disabled = false
    }
};