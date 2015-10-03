/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.BorderLayout = function (B, C) {
    C = C || {};
    Ext.BorderLayout.superclass.constructor.call(this, B, C);
    this.factory = C.factory || Ext.BorderLayout.RegionFactory;
    for (var D = 0, A = this.factory.validRegions.length; D < A; D++) {
        var E = this.factory.validRegions[D];
        if (C[E]) {
            this.addRegion(E, C[E])
        }
    }
};
Ext.extend(Ext.BorderLayout, Ext.LayoutManager, {
    addRegion: function (C, A) {
        if (!this.regions[C]) {
            var B = this.factory.create(C, this, A);
            this.bindRegion(C, B)
        }
        return this.regions[C]
    }, bindRegion: function (A, B) {
        this.regions[A] = B;
        B.on("visibilitychange", this.layout, this);
        B.on("paneladded", this.layout, this);
        B.on("panelremoved", this.layout, this);
        B.on("invalidated", this.layout, this);
        B.on("resized", this.onRegionResized, this);
        B.on("collapsed", this.onRegionCollapsed, this);
        B.on("expanded", this.onRegionExpanded, this)
    }, layout: function () {
        if (this.updating) {
            return
        }
        var J = this.getViewSize();
        var H = J.width, N = J.height;
        var G = H, M = N, E = 0, F = 0;
        var D = this.regions;
        var K = D["north"], I = D["south"], C = D["west"], O = D["east"], P = D["center"];
        if (K && K.isVisible()) {
            var Q = K.getBox();
            var L = K.getMargins();
            Q.width = H - (L.left + L.right);
            Q.x = L.left;
            Q.y = L.top;
            E = Q.height + Q.y + L.bottom;
            M -= E;
            K.updateBox(this.safeBox(Q))
        }
        if (I && I.isVisible()) {
            var Q = I.getBox();
            var L = I.getMargins();
            Q.width = H - (L.left + L.right);
            Q.x = L.left;
            var R = (Q.height + L.top + L.bottom);
            Q.y = N - R + L.top;
            M -= R;
            I.updateBox(this.safeBox(Q))
        }
        if (C && C.isVisible()) {
            var Q = C.getBox();
            var L = C.getMargins();
            Q.height = M - (L.top + L.bottom);
            Q.x = L.left;
            Q.y = E + L.top;
            var A = (Q.width + L.left + L.right);
            F += A;
            G -= A;
            C.updateBox(this.safeBox(Q))
        }
        if (O && O.isVisible()) {
            var Q = O.getBox();
            var L = O.getMargins();
            Q.height = M - (L.top + L.bottom);
            var A = (Q.width + L.left + L.right);
            Q.x = H - A + L.left;
            Q.y = E + L.top;
            G -= A;
            O.updateBox(this.safeBox(Q))
        }
        if (P) {
            var L = P.getMargins();
            var B = {x: F + L.left, y: E + L.top, width: G - (L.left + L.right), height: M - (L.top + L.bottom)};
            P.updateBox(this.safeBox(B))
        }
        this.el.repaint();
        this.fireEvent("layout", this)
    }, safeBox: function (A) {
        A.width = Math.max(0, A.width);
        A.height = Math.max(0, A.height);
        return A
    }, add: function (B, A) {
        B = B.toLowerCase();
        return this.regions[B].add(A)
    }, remove: function (B, A) {
        B = B.toLowerCase();
        return this.regions[B].remove(A)
    }, findPanel: function (B) {
        var A = this.regions;
        for (var D in A) {
            if (typeof A[D] != "function") {
                var C = A[D].getPanel(B);
                if (C) {
                    return C
                }
            }
        }
        return null
    }, showPanel: function (B) {
        var A = this.regions;
        for (var D in A) {
            var C = A[D];
            if (typeof C != "function") {
                if (C.hasPanel(B)) {
                    return C.showPanel(B)
                }
            }
        }
        return null
    }, restoreState: function (A) {
        if (!A) {
            A = Ext.state.Manager
        }
        var B = new Ext.LayoutStateManager();
        B.init(this, A)
    }, batchAdd: function (C) {
        this.beginUpdate();
        for (var B in C) {
            var A = this.regions[B];
            if (A) {
                this.addTypedPanels(A, C[B])
            }
        }
        this.endUpdate()
    }, addTypedPanels: function (B, E) {
        if (typeof E == "string") {
            B.add(new Ext.ContentPanel(E))
        } else {
            if (E instanceof Array) {
                for (var C = 0, A = E.length; C < A; C++) {
                    this.addTypedPanels(B, E[C])
                }
            } else {
                if (!E.events) {
                    var D = E.el;
                    delete E.el;
                    B.add(new Ext.ContentPanel(D || Ext.id(), E))
                } else {
                    B.add(E)
                }
            }
        }
    }
});
Ext.BorderLayout.create = function (C, H) {
    var G = new Ext.BorderLayout(H || document.body, C);
    G.beginUpdate();
    var D = Ext.BorderLayout.RegionFactory.validRegions;
    for (var E = 0, I = D.length; E < I; E++) {
        var F = D[E];
        if (G.regions[F] && C[F].panels) {
            var B = G.regions[F];
            var A = C[F].panels;
            G.addTypedPanels(B, A)
        }
    }
    G.endUpdate();
    return G
};
Ext.BorderLayout.RegionFactory = {
    validRegions: ["north", "south", "east", "west", "center"],
    create: function (C, B, A) {
        C = C.toLowerCase();
        if (A.lightweight || A.basic) {
            return new Ext.BasicLayoutRegion(B, A, C)
        }
        switch (C) {
            case"north":
                return new Ext.NorthLayoutRegion(B, A);
            case"south":
                return new Ext.SouthLayoutRegion(B, A);
            case"east":
                return new Ext.EastLayoutRegion(B, A);
            case"west":
                return new Ext.WestLayoutRegion(B, A);
            case"center":
                return new Ext.CenterLayoutRegion(B, A)
        }
        throw"Layout region \"" + C + "\" not supported."
    }
};