/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.CenterLayoutRegion = function (B, A) {
    Ext.CenterLayoutRegion.superclass.constructor.call(this, B, A, "center");
    this.visible = true;
    this.minWidth = A.minWidth || 20;
    this.minHeight = A.minHeight || 20
};
Ext.extend(Ext.CenterLayoutRegion, Ext.LayoutRegion, {
    hide: function () {
    }, show: function () {
    }, getMinWidth: function () {
        return this.minWidth
    }, getMinHeight: function () {
        return this.minHeight
    }
});
Ext.NorthLayoutRegion = function (C, A) {
    Ext.NorthLayoutRegion.superclass.constructor.call(this, C, A, "north", "n-resize");
    if (this.split) {
        this.split.placement = Ext.SplitBar.TOP;
        this.split.orientation = Ext.SplitBar.VERTICAL;
        this.split.el.addClass("x-layout-split-v")
    }
    var B = A.initialSize || A.height;
    if (typeof B != "undefined") {
        this.el.setHeight(B)
    }
};
Ext.extend(Ext.NorthLayoutRegion, Ext.SplitLayoutRegion, {
    orientation: Ext.SplitBar.VERTICAL, getBox: function () {
        if (this.collapsed) {
            return this.collapsedEl.getBox()
        }
        var A = this.el.getBox();
        if (this.split) {
            A.height += this.split.el.getHeight()
        }
        return A
    }, updateBox: function (A) {
        if (this.split && !this.collapsed) {
            A.height -= this.split.el.getHeight();
            this.split.el.setLeft(A.x);
            this.split.el.setTop(A.y + A.height);
            this.split.el.setWidth(A.width)
        }
        if (this.collapsed) {
            this.updateBody(A.width, null)
        }
        Ext.NorthLayoutRegion.superclass.updateBox.call(this, A)
    }
});
Ext.SouthLayoutRegion = function (C, A) {
    Ext.SouthLayoutRegion.superclass.constructor.call(this, C, A, "south", "s-resize");
    if (this.split) {
        this.split.placement = Ext.SplitBar.BOTTOM;
        this.split.orientation = Ext.SplitBar.VERTICAL;
        this.split.el.addClass("x-layout-split-v")
    }
    var B = A.initialSize || A.height;
    if (typeof B != "undefined") {
        this.el.setHeight(B)
    }
};
Ext.extend(Ext.SouthLayoutRegion, Ext.SplitLayoutRegion, {
    orientation: Ext.SplitBar.VERTICAL, getBox: function () {
        if (this.collapsed) {
            return this.collapsedEl.getBox()
        }
        var B = this.el.getBox();
        if (this.split) {
            var A = this.split.el.getHeight();
            B.height += A;
            B.y -= A
        }
        return B
    }, updateBox: function (B) {
        if (this.split && !this.collapsed) {
            var A = this.split.el.getHeight();
            B.height -= A;
            B.y += A;
            this.split.el.setLeft(B.x);
            this.split.el.setTop(B.y - A);
            this.split.el.setWidth(B.width)
        }
        if (this.collapsed) {
            this.updateBody(B.width, null)
        }
        Ext.SouthLayoutRegion.superclass.updateBox.call(this, B)
    }
});
Ext.EastLayoutRegion = function (C, A) {
    Ext.EastLayoutRegion.superclass.constructor.call(this, C, A, "east", "e-resize");
    if (this.split) {
        this.split.placement = Ext.SplitBar.RIGHT;
        this.split.orientation = Ext.SplitBar.HORIZONTAL;
        this.split.el.addClass("x-layout-split-h")
    }
    var B = A.initialSize || A.width;
    if (typeof B != "undefined") {
        this.el.setWidth(B)
    }
};
Ext.extend(Ext.EastLayoutRegion, Ext.SplitLayoutRegion, {
    orientation: Ext.SplitBar.HORIZONTAL, getBox: function () {
        if (this.collapsed) {
            return this.collapsedEl.getBox()
        }
        var B = this.el.getBox();
        if (this.split) {
            var A = this.split.el.getWidth();
            B.width += A;
            B.x -= A
        }
        return B
    }, updateBox: function (B) {
        if (this.split && !this.collapsed) {
            var A = this.split.el.getWidth();
            B.width -= A;
            this.split.el.setLeft(B.x);
            this.split.el.setTop(B.y);
            this.split.el.setHeight(B.height);
            B.x += A
        }
        if (this.collapsed) {
            this.updateBody(null, B.height)
        }
        Ext.EastLayoutRegion.superclass.updateBox.call(this, B)
    }
});
Ext.WestLayoutRegion = function (C, A) {
    Ext.WestLayoutRegion.superclass.constructor.call(this, C, A, "west", "w-resize");
    if (this.split) {
        this.split.placement = Ext.SplitBar.LEFT;
        this.split.orientation = Ext.SplitBar.HORIZONTAL;
        this.split.el.addClass("x-layout-split-h")
    }
    var B = A.initialSize || A.width;
    if (typeof B != "undefined") {
        this.el.setWidth(B)
    }
};
Ext.extend(Ext.WestLayoutRegion, Ext.SplitLayoutRegion, {
    orientation: Ext.SplitBar.HORIZONTAL, getBox: function () {
        if (this.collapsed) {
            return this.collapsedEl.getBox()
        }
        var A = this.el.getBox();
        if (this.split) {
            A.width += this.split.el.getWidth()
        }
        return A
    }, updateBox: function (B) {
        if (this.split && !this.collapsed) {
            var A = this.split.el.getWidth();
            B.width -= A;
            this.split.el.setLeft(B.x + B.width);
            this.split.el.setTop(B.y);
            this.split.el.setHeight(B.height)
        }
        if (this.collapsed) {
            this.updateBody(null, B.height)
        }
        Ext.WestLayoutRegion.superclass.updateBox.call(this, B)
    }
});