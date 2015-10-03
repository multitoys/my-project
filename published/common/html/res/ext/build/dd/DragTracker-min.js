/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.dd.DragTracker = function (A) {
    Ext.apply(this, A);
    this.addEvents("mousedown", "mouseup", "mousemove", "dragstart", "dragend", "drag");
    this.dragRegion = new Ext.lib.Region(0, 0, 0, 0);
    if (this.el) {
        this.initEl(this.el)
    }
};
Ext.extend(Ext.dd.DragTracker, Ext.util.Observable, {
    active: false,
    tolerance: 5,
    autoStart: false,
    initEl: function (A) {
        this.el = Ext.get(A);
        A.on("mousedown", this.onMouseDown, this, this.delegate ? {delegate: this.delegate} : undefined)
    },
    destroy: function () {
        this.el.un("mousedown", this.onMouseDown, this)
    },
    onMouseDown: function (C, B) {
        if (this.fireEvent("mousedown", this, C) !== false && this.onBeforeStart(C) !== false) {
            this.startXY = this.lastXY = C.getXY();
            this.dragTarget = this.delegate ? B : this.el.dom;
            C.preventDefault();
            var A = Ext.getDoc();
            A.on("mouseup", this.onMouseUp, this);
            A.on("mousemove", this.onMouseMove, this);
            A.on("selectstart", this.stopSelect, this);
            if (this.autoStart) {
                this.timer = this.triggerStart.defer(this.autoStart === true ? 1000 : this.autoStart, this)
            }
        }
    },
    onMouseMove: function (D, C) {
        D.preventDefault();
        var B = D.getXY(), A = this.startXY;
        this.lastXY = B;
        if (!this.active) {
            if (Math.abs(A[0] - B[0]) > this.tolerance || Math.abs(A[1] - B[1]) > this.tolerance) {
                this.triggerStart()
            } else {
                return
            }
        }
        this.fireEvent("mousemove", this, D);
        this.onDrag(D);
        this.fireEvent("drag", this, D)
    },
    onMouseUp: function (B) {
        var A = Ext.getDoc();
        A.un("mousemove", this.onMouseMove, this);
        A.un("mouseup", this.onMouseUp, this);
        A.un("selectstart", this.stopSelect, this);
        B.preventDefault();
        this.clearStart();
        this.active = false;
        delete this.elRegion;
        this.fireEvent("mouseup", this, B);
        this.onEnd(B);
        this.fireEvent("dragend", this, B)
    },
    triggerStart: function (A) {
        this.clearStart();
        this.active = true;
        this.onStart(this.startXY);
        this.fireEvent("dragstart", this, this.startXY)
    },
    clearStart: function () {
        if (this.timer) {
            clearTimeout(this.timer);
            delete this.timer
        }
    },
    stopSelect: function (A) {
        A.stopEvent();
        return false
    },
    onBeforeStart: function (A) {
    },
    onStart: function (A) {
    },
    onDrag: function (A) {
    },
    onEnd: function (A) {
    },
    getDragTarget: function () {
        return this.dragTarget
    },
    getDragCt: function () {
        return this.el
    },
    getXY: function (A) {
        return A ? this.constrainModes[A].call(this, this.lastXY) : this.lastXY
    },
    getOffset: function (C) {
        var B = this.getXY(C);
        var A = this.startXY;
        return [A[0] - B[0], A[1] - B[1]]
    },
    constrainModes: {
        "point": function (B) {
            if (!this.elRegion) {
                this.elRegion = this.getDragCt().getRegion()
            }
            var A = this.dragRegion;
            A.left = B[0];
            A.top = B[1];
            A.right = B[0];
            A.bottom = B[1];
            A.constrainTo(this.elRegion);
            return [A.left, A.top]
        }
    }
});