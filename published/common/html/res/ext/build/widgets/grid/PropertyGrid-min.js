/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.PropertyRecord = Ext.data.Record.create([{name: "name", type: "string"}, "value"]);
Ext.grid.PropertyStore = function (A, B) {
    this.grid = A;
    this.store = new Ext.data.Store({recordType: Ext.grid.PropertyRecord});
    this.store.on("update", this.onUpdate, this);
    if (B) {
        this.setSource(B)
    }
    Ext.grid.PropertyStore.superclass.constructor.call(this)
};
Ext.extend(Ext.grid.PropertyStore, Ext.util.Observable, {
    setSource: function (C) {
        this.source = C;
        this.store.removeAll();
        var B = [];
        for (var A in C) {
            if (this.isEditableValue(C[A])) {
                B.push(new Ext.grid.PropertyRecord({name: A, value: C[A]}, A))
            }
        }
        this.store.loadRecords({records: B}, {}, true)
    }, onUpdate: function (E, A, D) {
        if (D == Ext.data.Record.EDIT) {
            var B = A.data["value"];
            var C = A.modified["value"];
            if (this.grid.fireEvent("beforepropertychange", this.source, A.id, B, C) !== false) {
                this.source[A.id] = B;
                A.commit();
                this.grid.fireEvent("propertychange", this.source, A.id, B, C)
            } else {
                A.reject()
            }
        }
    }, getProperty: function (A) {
        return this.store.getAt(A)
    }, isEditableValue: function (A) {
        if (A && A instanceof Date) {
            return true
        } else {
            if (typeof A == "object" || typeof A == "function") {
                return false
            }
        }
        return true
    }, setValue: function (B, A) {
        this.source[B] = A;
        this.store.getById(B).set("value", A)
    }, getSource: function () {
        return this.source
    }
});
Ext.grid.PropertyColumnModel = function (C, B) {
    this.grid = C;
    var D = Ext.grid;
    D.PropertyColumnModel.superclass.constructor.call(this, [{
        header: this.nameText,
        width: 50,
        sortable: true,
        dataIndex: "name",
        id: "name"
    }, {header: this.valueText, width: 50, resizable: false, dataIndex: "value", id: "value"}]);
    this.store = B;
    this.bselect = Ext.DomHelper.append(document.body, {
        tag: "select",
        cls: "x-grid-editor x-hide-display",
        children: [{tag: "option", value: "true", html: "true"}, {tag: "option", value: "false", html: "false"}]
    });
    var E = Ext.form;
    var A = new E.Field({
        el: this.bselect, bselect: this.bselect, autoShow: true, getValue: function () {
            return this.bselect.value == "true"
        }
    });
    this.editors = {
        "date": new D.GridEditor(new E.DateField({selectOnFocus: true})),
        "string": new D.GridEditor(new E.TextField({selectOnFocus: true})),
        "number": new D.GridEditor(new E.NumberField({selectOnFocus: true, style: "text-align:left;"})),
        "boolean": new D.GridEditor(A)
    };
    this.renderCellDelegate = this.renderCell.createDelegate(this);
    this.renderPropDelegate = this.renderProp.createDelegate(this)
};
Ext.extend(Ext.grid.PropertyColumnModel, Ext.grid.ColumnModel, {
    nameText: "Name",
    valueText: "Value",
    dateFormat: "m/j/Y",
    renderDate: function (A) {
        return A.dateFormat(this.dateFormat)
    },
    renderBool: function (A) {
        return A ? "true" : "false"
    },
    isCellEditable: function (A, B) {
        return A == 1
    },
    getRenderer: function (A) {
        return A == 1 ? this.renderCellDelegate : this.renderPropDelegate
    },
    renderProp: function (A) {
        return this.getPropertyName(A)
    },
    renderCell: function (A) {
        var B = A;
        if (A instanceof Date) {
            B = this.renderDate(A)
        } else {
            if (typeof A == "boolean") {
                B = this.renderBool(A)
            }
        }
        return Ext.util.Format.htmlEncode(B)
    },
    getPropertyName: function (B) {
        var A = this.grid.propertyNames;
        return A && A[B] ? A[B] : B
    },
    getCellEditor: function (A, E) {
        var B = this.store.getProperty(E);
        var D = B.data["name"], C = B.data["value"];
        if (this.grid.customEditors[D]) {
            return this.grid.customEditors[D]
        }
        if (C instanceof Date) {
            return this.editors["date"]
        } else {
            if (typeof C == "number") {
                return this.editors["number"]
            } else {
                if (typeof C == "boolean") {
                    return this.editors["boolean"]
                } else {
                    return this.editors["string"]
                }
            }
        }
    }
});
Ext.grid.PropertyGrid = Ext.extend(Ext.grid.EditorGridPanel, {
    enableColumnMove: false,
    stripeRows: false,
    trackMouseOver: false,
    clicksToEdit: 1,
    enableHdMenu: false,
    viewConfig: {forceFit: true},
    initComponent: function () {
        this.customEditors = this.customEditors || {};
        this.lastEditRow = null;
        var B = new Ext.grid.PropertyStore(this);
        this.propStore = B;
        var A = new Ext.grid.PropertyColumnModel(this, B);
        B.store.sort("name", "ASC");
        this.addEvents("beforepropertychange", "propertychange");
        this.cm = A;
        this.ds = B.store;
        Ext.grid.PropertyGrid.superclass.initComponent.call(this);
        this.selModel.on("beforecellselect", function (E, D, C) {
            if (C === 0) {
                this.startEditing.defer(200, this, [D, 1]);
                return false
            }
        }, this)
    },
    onRender: function () {
        Ext.grid.PropertyGrid.superclass.onRender.apply(this, arguments);
        this.getGridEl().addClass("x-props-grid")
    },
    afterRender: function () {
        Ext.grid.PropertyGrid.superclass.afterRender.apply(this, arguments);
        if (this.source) {
            this.setSource(this.source)
        }
    },
    setSource: function (A) {
        this.propStore.setSource(A)
    },
    getSource: function () {
        return this.propStore.getSource()
    }
});