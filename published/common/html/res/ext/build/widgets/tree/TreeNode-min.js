/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.tree.TreeNode=function(A){A=A||{};if(typeof A=="string"){A={text:A}}this.childrenRendered=false;this.rendered=false;Ext.tree.TreeNode.superclass.constructor.call(this,A);this.expanded=A.expanded===true;this.isTarget=A.isTarget!==false;this.draggable=A.draggable!==false&&A.allowDrag!==false;this.allowChildren=A.allowChildren!==false&&A.allowDrop!==false;this.text=A.text;this.disabled=A.disabled===true;this.addEvents("textchange","beforeexpand","beforecollapse","expand","disabledchange","collapse","beforeclick","click","checkchange","dblclick","contextmenu","beforechildrenrendered");var B=this.attributes.uiProvider||this.defaultUI||Ext.tree.TreeNodeUI;this.ui=new B(this)};Ext.extend(Ext.tree.TreeNode,Ext.data.Node,{preventHScroll:true,isExpanded:function(){return this.expanded},getUI:function(){return this.ui},setFirstChild:function(A){var B=this.firstChild;Ext.tree.TreeNode.superclass.setFirstChild.call(this,A);if(this.childrenRendered&&B&&A!=B){B.renderIndent(true,true)}if(this.rendered){this.renderIndent(true,true)}},setLastChild:function(B){var A=this.lastChild;Ext.tree.TreeNode.superclass.setLastChild.call(this,B);if(this.childrenRendered&&A&&B!=A){A.renderIndent(true,true)}if(this.rendered){this.renderIndent(true,true)}},appendChild:function(){var A=Ext.tree.TreeNode.superclass.appendChild.apply(this,arguments);if(A&&this.childrenRendered){A.render()}this.ui.updateExpandIcon();return A},removeChild:function(A){this.ownerTree.getSelectionModel().unselect(A);Ext.tree.TreeNode.superclass.removeChild.apply(this,arguments);if(this.childrenRendered){A.ui.remove()}if(this.childNodes.length<1){this.collapse(false,false)}else{this.ui.updateExpandIcon()}if(!this.firstChild&&!this.isHiddenRoot()){this.childrenRendered=false}return A},insertBefore:function(C,A){var B=Ext.tree.TreeNode.superclass.insertBefore.apply(this,arguments);if(B&&A&&this.childrenRendered){C.render()}this.ui.updateExpandIcon();return B},setText:function(B){var A=this.text;this.text=B;this.attributes.text=B;if(this.rendered){this.ui.onTextChange(this,B,A)}this.fireEvent("textchange",this,B,A)},select:function(){this.getOwnerTree().getSelectionModel().select(this)},unselect:function(){this.getOwnerTree().getSelectionModel().unselect(this)},isSelected:function(){return this.getOwnerTree().getSelectionModel().isSelected(this)},expand:function(A,B,C){if(!this.expanded){if(this.fireEvent("beforeexpand",this,A,B)===false){return }if(!this.childrenRendered){this.renderChildren()}this.expanded=true;if(!this.isHiddenRoot()&&(this.getOwnerTree().animate&&B!==false)||B){this.ui.animExpand(function(){this.fireEvent("expand",this);if(typeof C=="function"){C(this)}if(A===true){this.expandChildNodes(true)}}.createDelegate(this));return }else{this.ui.expand();this.fireEvent("expand",this);if(typeof C=="function"){C(this)}}}else{if(typeof C=="function"){C(this)}}if(A===true){this.expandChildNodes(true)}},isHiddenRoot:function(){return this.isRoot&&!this.getOwnerTree().rootVisible},collapse:function(B,E){if(this.expanded&&!this.isHiddenRoot()){if(this.fireEvent("beforecollapse",this,B,E)===false){return }this.expanded=false;if((this.getOwnerTree().animate&&E!==false)||E){this.ui.animCollapse(function(){this.fireEvent("collapse",this);if(B===true){this.collapseChildNodes(true)}}.createDelegate(this));return }else{this.ui.collapse();this.fireEvent("collapse",this)}}if(B===true){var D=this.childNodes;for(var C=0,A=D.length;C<A;C++){D[C].collapse(true,false)}}},delayedExpand:function(A){if(!this.expandProcId){this.expandProcId=this.expand.defer(A,this)}},cancelExpand:function(){if(this.expandProcId){clearTimeout(this.expandProcId)}this.expandProcId=false},toggle:function(){if(this.expanded){this.collapse()}else{this.expand()}},ensureVisible:function(B){var A=this.getOwnerTree();A.expandPath(this.parentNode.getPath(),false,function(){var C=A.getNodeById(this.id);A.getTreeEl().scrollChildIntoView(C.ui.anchor);Ext.callback(B)}.createDelegate(this))},expandChildNodes:function(B){var D=this.childNodes;for(var C=0,A=D.length;C<A;C++){D[C].expand(B)}},collapseChildNodes:function(B){var D=this.childNodes;for(var C=0,A=D.length;C<A;C++){D[C].collapse(B)}},disable:function(){this.disabled=true;this.unselect();if(this.rendered&&this.ui.onDisableChange){this.ui.onDisableChange(this,true)}this.fireEvent("disabledchange",this,true)},enable:function(){this.disabled=false;if(this.rendered&&this.ui.onDisableChange){this.ui.onDisableChange(this,false)}this.fireEvent("disabledchange",this,false)},renderChildren:function(B){if(B!==false){this.fireEvent("beforechildrenrendered",this)}var D=this.childNodes;for(var C=0,A=D.length;C<A;C++){D[C].render(true)}this.childrenRendered=true},sort:function(E,D){Ext.tree.TreeNode.superclass.sort.apply(this,arguments);if(this.childrenRendered){var C=this.childNodes;for(var B=0,A=C.length;B<A;B++){C[B].render(true)}}},render:function(A){this.ui.render(A);if(!this.rendered){this.getOwnerTree().registerNode(this);this.rendered=true;if(this.expanded){this.expanded=false;this.expand(false,false)}}},renderIndent:function(B,E){if(E){this.ui.childIndent=null}this.ui.renderIndent();if(B===true&&this.childrenRendered){var D=this.childNodes;for(var C=0,A=D.length;C<A;C++){D[C].renderIndent(true,E)}}},beginUpdate:function(){this.childrenRendered=false},endUpdate:function(){if(this.expanded){this.renderChildren()}},destroy:function(){for(var B=0,A=this.childNodes.length;B<A;B++){this.childNodes[B].destroy()}this.childNodes=null;if(this.ui.destroy){this.ui.destroy()}}});