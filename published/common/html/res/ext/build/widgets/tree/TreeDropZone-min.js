/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

if (Ext.dd.DropZone) {
    Ext.tree.TreeDropZone = function (A, B) {
        this.allowParentInsert = false;
        this.allowContainerDrop = false;
        this.appendOnly = false;
        Ext.tree.TreeDropZone.superclass.constructor.call(this, A.innerCt, B);
        this.tree = A;
        this.dragOverData = {};
        this.lastInsertClass = "x-tree-no-status"
    };
    Ext.extend(Ext.tree.TreeDropZone, Ext.dd.DropZone, {
        ddGroup: "TreeDD", expandDelay: 1000, expandNode: function (A) {
            if (A.hasChildNodes() && !A.isExpanded()) {
                A.expand(false, null, this.triggerCacheRefresh.createDelegate(this))
            }
        }, queueExpand: function (A) {
            this.expandProcId = this.expandNode.defer(this.expandDelay, this, [A])
        }, cancelExpand: function () {
            if (this.expandProcId) {
                clearTimeout(this.expandProcId);
                this.expandProcId = false
            }
        }, isValidDropPoint: function (A, I, G, D, C) {
            if (!A || !C) {
                return false
            }
            var E = A.node;
            var F = C.node;
            if (!(E && E.isTarget && I)) {
                return false
            }
            if (I == "append" && E.allowChildren === false) {
                return false
            }
            if ((I == "above" || I == "below") && (E.parentNode && E.parentNode.allowChildren === false)) {
                return false
            }
            if (F && (E == F || F.contains(E))) {
                return false
            }
            var B = this.dragOverData;
            B.tree = this.tree;
            B.target = E;
            B.data = C;
            B.point = I;
            B.source = G;
            B.rawEvent = D;
            B.dropNode = F;
            B.cancel = false;
            var H = this.tree.fireEvent("nodedragover", B);
            return B.cancel === false && H !== false
        }, getDropPoint: function (E, D, I) {
            var J = D.node;
            if (J.isRoot) {
                return J.allowChildren !== false ? "append" : false
            }
            var B = D.ddel;
            var K = Ext.lib.Dom.getY(B), G = K + B.offsetHeight;
            var F = Ext.lib.Event.getPageY(E);
            var H = J.allowChildren === false || J.isLeaf();
            if (this.appendOnly || J.parentNode.allowChildren === false) {
                return H ? false : "append"
            }
            var C = false;
            if (!this.allowParentInsert) {
                C = J.hasChildNodes() && J.isExpanded()
            }
            var A = (G - K) / (H ? 2 : 3);
            if (F >= K && F < (K + A)) {
                return "above"
            } else {
                if (!C && (H || F >= G - A && F <= G)) {
                    return "below"
                } else {
                    return "append"
                }
            }
        }, onNodeEnter: function (D, A, C, B) {
            this.cancelExpand()
        }, onNodeOver: function (B, G, F, E) {
            var I = this.getDropPoint(F, B, G);
            var C = B.node;
            if (!this.expandProcId && I == "append" && C.hasChildNodes() && !B.node.isExpanded()) {
                this.queueExpand(C)
            } else {
                if (I != "append") {
                    this.cancelExpand()
                }
            }
            var D = this.dropNotAllowed;
            if (this.isValidDropPoint(B, I, G, F, E)) {
                if (I) {
                    var A = B.ddel;
                    var H;
                    if (I == "above") {
                        D = B.node.isFirst() ? "x-tree-drop-ok-above" : "x-tree-drop-ok-between";
                        H = "x-tree-drag-insert-above"
                    } else {
                        if (I == "below") {
                            D = B.node.isLast() ? "x-tree-drop-ok-below" : "x-tree-drop-ok-between";
                            H = "x-tree-drag-insert-below"
                        } else {
                            D = "x-tree-drop-ok-append";
                            H = "x-tree-drag-append"
                        }
                    }
                    if (this.lastInsertClass != H) {
                        Ext.fly(A).replaceClass(this.lastInsertClass, H);
                        this.lastInsertClass = H
                    }
                }
            }
            return D
        }, onNodeOut: function (D, A, C, B) {
            this.cancelExpand();
            this.removeDropIndicators(D)
        }, onNodeDrop: function (C, I, E, D) {
            var H = this.getDropPoint(E, C, I);
            var F = C.node;
            F.ui.startDrop();
            if (!this.isValidDropPoint(C, H, I, E, D)) {
                F.ui.endDrop();
                return false
            }
            var G = D.node || (I.getTreeNode ? I.getTreeNode(D, F, H, E) : null);
            var B = {tree: this.tree, target: F, data: D, point: H, source: I, rawEvent: E, dropNode: G, cancel: !G};
            var A = this.tree.fireEvent("beforenodedrop", B);
            if (A === false || B.cancel === true || !B.dropNode) {
                F.ui.endDrop();
                return false
            }
            F = B.target;
            if (H == "append" && !F.isExpanded()) {
                F.expand(false, null, function () {
                    this.completeDrop(B)
                }.createDelegate(this))
            } else {
                this.completeDrop(B)
            }
            return true
        }, completeDrop: function (G) {
            var D = G.dropNode, E = G.point, C = G.target;
            if (!(D instanceof Array)) {
                D = [D]
            }
            var F;
            for (var B = 0, A = D.length; B < A; B++) {
                F = D[B];
                if (E == "above") {
                    C.parentNode.insertBefore(F, C)
                } else {
                    if (E == "below") {
                        C.parentNode.insertBefore(F, C.nextSibling)
                    } else {
                        C.appendChild(F)
                    }
                }
            }
            F.ui.focus();
            if (this.tree.hlDrop) {
                F.ui.highlight()
            }
            C.ui.endDrop();
            this.tree.fireEvent("nodedrop", G)
        }, afterNodeMoved: function (A, C, E, D, B) {
            if (this.tree.hlDrop) {
                B.ui.focus();
                B.ui.highlight()
            }
            this.tree.fireEvent("nodedrop", this.tree, D, C, A, E)
        }, getTree: function () {
            return this.tree
        }, removeDropIndicators: function (B) {
            if (B && B.ddel) {
                var A = B.ddel;
                Ext.fly(A).removeClass(["x-tree-drag-insert-above", "x-tree-drag-insert-below", "x-tree-drag-append"]);
                this.lastInsertClass = "_noclass"
            }
        }, beforeDragDrop: function (B, A, C) {
            this.cancelExpand();
            return true
        }, afterRepair: function (A) {
            if (A && Ext.enableFx) {
                A.node.ui.highlight()
            }
            this.hideProxy()
        }
    })
}
;