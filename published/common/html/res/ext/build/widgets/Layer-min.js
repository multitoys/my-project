/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

(function () {
    Ext.Layer = function (D, C) {
        D = D || {};
        var E = Ext.DomHelper;
        var G = D.parentEl, F = G ? Ext.getDom(G) : document.body;
        if (C) {
            this.dom = Ext.getDom(C)
        }
        if (!this.dom) {
            var H = D.dh || {tag: "div", cls: "x-layer"};
            this.dom = E.append(F, H)
        }
        if (D.cls) {
            this.addClass(D.cls)
        }
        this.constrain = D.constrain !== false;
        this.visibilityMode = Ext.Element.VISIBILITY;
        if (D.id) {
            this.id = this.dom.id = D.id
        } else {
            this.id = Ext.id(this.dom)
        }
        this.zindex = D.zindex || this.getZIndex();
        this.position("absolute", this.zindex);
        if (D.shadow) {
            this.shadowOffset = D.shadowOffset || 4;
            this.shadow = new Ext.Shadow({offset: this.shadowOffset, mode: D.shadow})
        } else {
            this.shadowOffset = 0
        }
        this.useShim = D.shim !== false && Ext.useShims;
        this.useDisplay = D.useDisplay;
        this.hide()
    };
    var A = Ext.Element.prototype;
    var B = [];
    Ext.extend(Ext.Layer, Ext.Element, {
        getZIndex: function () {
            return this.zindex || parseInt(this.getStyle("z-index"), 10) || 11000
        }, getShim: function () {
            if (!this.useShim) {
                return null
            }
            if (this.shim) {
                return this.shim
            }
            var D = B.shift();
            if (!D) {
                D = this.createShim();
                D.enableDisplayMode("block");
                D.dom.style.display = "none";
                D.dom.style.visibility = "visible"
            }
            var C = this.dom.parentNode;
            if (D.dom.parentNode != C) {
                C.insertBefore(D.dom, this.dom)
            }
            D.setStyle("z-index", this.getZIndex() - 2);
            this.shim = D;
            return D
        }, hideShim: function () {
            if (this.shim) {
                this.shim.setDisplayed(false);
                B.push(this.shim);
                delete this.shim
            }
        }, disableShadow: function () {
            if (this.shadow) {
                this.shadowDisabled = true;
                this.shadow.hide();
                this.lastShadowOffset = this.shadowOffset;
                this.shadowOffset = 0
            }
        }, enableShadow: function (C) {
            if (this.shadow) {
                this.shadowDisabled = false;
                this.shadowOffset = this.lastShadowOffset;
                delete this.lastShadowOffset;
                if (C) {
                    this.sync(true)
                }
            }
        }, sync: function (C) {
            var I = this.shadow;
            if (!this.updating && this.isVisible() && (I || this.useShim)) {
                var F = this.getShim();
                var H = this.getWidth(), E = this.getHeight();
                var D = this.getLeft(true), J = this.getTop(true);
                if (I && !this.shadowDisabled) {
                    if (C && !I.isVisible()) {
                        I.show(this)
                    } else {
                        I.realign(D, J, H, E)
                    }
                    if (F) {
                        if (C) {
                            F.show()
                        }
                        var G = I.adjusts, K = F.dom.style;
                        K.left = (Math.min(D, D + G.l)) + "px";
                        K.top = (Math.min(J, J + G.t)) + "px";
                        K.width = (H + G.w) + "px";
                        K.height = (E + G.h) + "px"
                    }
                } else {
                    if (F) {
                        if (C) {
                            F.show()
                        }
                        F.setSize(H, E);
                        F.setLeftTop(D, J)
                    }
                }
            }
        }, destroy: function () {
            this.hideShim();
            if (this.shadow) {
                this.shadow.hide()
            }
            this.removeAllListeners();
            Ext.removeNode(this.dom);
            Ext.Element.uncache(this.id)
        }, remove: function () {
            this.destroy()
        }, beginUpdate: function () {
            this.updating = true
        }, endUpdate: function () {
            this.updating = false;
            this.sync(true)
        }, hideUnders: function (C) {
            if (this.shadow) {
                this.shadow.hide()
            }
            this.hideShim()
        }, constrainXY: function () {
            if (this.constrain) {
                var G = Ext.lib.Dom.getViewWidth(), C = Ext.lib.Dom.getViewHeight();
                var L = Ext.getDoc().getScroll();
                var K = this.getXY();
                var H = K[0], F = K[1];
                var I = this.dom.offsetWidth + this.shadowOffset, D = this.dom.offsetHeight + this.shadowOffset;
                var E = false;
                if ((H + I) > G + L.left) {
                    H = G - I - this.shadowOffset;
                    E = true
                }
                if ((F + D) > C + L.top) {
                    F = C - D - this.shadowOffset;
                    E = true
                }
                if (H < L.left) {
                    H = L.left;
                    E = true
                }
                if (F < L.top) {
                    F = L.top;
                    E = true
                }
                if (E) {
                    if (this.avoidY) {
                        var J = this.avoidY;
                        if (F <= J && (F + D) >= J) {
                            F = J - D - 5
                        }
                    }
                    K = [H, F];
                    this.storeXY(K);
                    A.setXY.call(this, K);
                    this.sync()
                }
            }
        }, isVisible: function () {
            return this.visible
        }, showAction: function () {
            this.visible = true;
            if (this.useDisplay === true) {
                this.setDisplayed("")
            } else {
                if (this.lastXY) {
                    A.setXY.call(this, this.lastXY)
                } else {
                    if (this.lastLT) {
                        A.setLeftTop.call(this, this.lastLT[0], this.lastLT[1])
                    }
                }
            }
        }, hideAction: function () {
            this.visible = false;
            if (this.useDisplay === true) {
                this.setDisplayed(false)
            } else {
                this.setLeftTop(-10000, -10000)
            }
        }, setVisible: function (E, D, G, H, F) {
            if (E) {
                this.showAction()
            }
            if (D && E) {
                var C = function () {
                    this.sync(true);
                    if (H) {
                        H()
                    }
                }.createDelegate(this);
                A.setVisible.call(this, true, true, G, C, F)
            } else {
                if (!E) {
                    this.hideUnders(true)
                }
                var C = H;
                if (D) {
                    C = function () {
                        this.hideAction();
                        if (H) {
                            H()
                        }
                    }.createDelegate(this)
                }
                A.setVisible.call(this, E, D, G, C, F);
                if (E) {
                    this.sync(true)
                } else {
                    if (!D) {
                        this.hideAction()
                    }
                }
            }
        }, storeXY: function (C) {
            delete this.lastLT;
            this.lastXY = C
        }, storeLeftTop: function (D, C) {
            delete this.lastXY;
            this.lastLT = [D, C]
        }, beforeFx: function () {
            this.beforeAction();
            return Ext.Layer.superclass.beforeFx.apply(this, arguments)
        }, afterFx: function () {
            Ext.Layer.superclass.afterFx.apply(this, arguments);
            this.sync(this.isVisible())
        }, beforeAction: function () {
            if (!this.updating && this.shadow) {
                this.shadow.hide()
            }
        }, setLeft: function (C) {
            this.storeLeftTop(C, this.getTop(true));
            A.setLeft.apply(this, arguments);
            this.sync()
        }, setTop: function (C) {
            this.storeLeftTop(this.getLeft(true), C);
            A.setTop.apply(this, arguments);
            this.sync()
        }, setLeftTop: function (D, C) {
            this.storeLeftTop(D, C);
            A.setLeftTop.apply(this, arguments);
            this.sync()
        }, setXY: function (F, D, G, H, E) {
            this.fixDisplay();
            this.beforeAction();
            this.storeXY(F);
            var C = this.createCB(H);
            A.setXY.call(this, F, D, G, C, E);
            if (!D) {
                C()
            }
        }, createCB: function (D) {
            var C = this;
            return function () {
                C.constrainXY();
                C.sync(true);
                if (D) {
                    D()
                }
            }
        }, setX: function (C, D, F, G, E) {
            this.setXY([C, this.getY()], D, F, G, E)
        }, setY: function (G, C, E, F, D) {
            this.setXY([this.getX(), G], C, E, F, D)
        }, setSize: function (E, F, D, H, I, G) {
            this.beforeAction();
            var C = this.createCB(I);
            A.setSize.call(this, E, F, D, H, C, G);
            if (!D) {
                C()
            }
        }, setWidth: function (E, D, G, H, F) {
            this.beforeAction();
            var C = this.createCB(H);
            A.setWidth.call(this, E, D, G, C, F);
            if (!D) {
                C()
            }
        }, setHeight: function (E, D, G, H, F) {
            this.beforeAction();
            var C = this.createCB(H);
            A.setHeight.call(this, E, D, G, C, F);
            if (!D) {
                C()
            }
        }, setBounds: function (J, H, K, D, I, F, G, E) {
            this.beforeAction();
            var C = this.createCB(G);
            if (!I) {
                this.storeXY([J, H]);
                A.setXY.call(this, [J, H]);
                A.setSize.call(this, K, D, I, F, C, E);
                C()
            } else {
                A.setBounds.call(this, J, H, K, D, I, F, C, E)
            }
            return this
        }, setZIndex: function (C) {
            this.zindex = C;
            this.setStyle("z-index", C + 2);
            if (this.shadow) {
                this.shadow.setZIndex(C + 1)
            }
            if (this.shim) {
                this.shim.setStyle("z-index", C)
            }
        }
    })
})();