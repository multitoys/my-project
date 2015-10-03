/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.util.MixedCollection = function (B, A) {
    this.items = [];
    this.map = {};
    this.keys = [];
    this.length = 0;
    this.addEvents("clear", "add", "replace", "remove", "sort");
    this.allowFunctions = B === true;
    if (A) {
        this.getKey = A
    }
    Ext.util.MixedCollection.superclass.constructor.call(this)
};
Ext.extend(Ext.util.MixedCollection, Ext.util.Observable, {
    allowFunctions: false, add: function (B, C) {
        if (arguments.length == 1) {
            C = arguments[0];
            B = this.getKey(C)
        }
        if (typeof B == "undefined" || B === null) {
            this.length++;
            this.items.push(C);
            this.keys.push(null)
        } else {
            var A = this.map[B];
            if (A) {
                return this.replace(B, C)
            }
            this.length++;
            this.items.push(C);
            this.map[B] = C;
            this.keys.push(B)
        }
        this.fireEvent("add", this.length - 1, C, B);
        return C
    }, getKey: function (A) {
        return A.id
    }, replace: function (C, D) {
        if (arguments.length == 1) {
            D = arguments[0];
            C = this.getKey(D)
        }
        var A = this.item(C);
        if (typeof C == "undefined" || C === null || typeof A == "undefined") {
            return this.add(C, D)
        }
        var B = this.indexOfKey(C);
        this.items[B] = D;
        this.map[C] = D;
        this.fireEvent("replace", C, A, D);
        return D
    }, addAll: function (E) {
        if (arguments.length > 1 || E instanceof Array) {
            var B = arguments.length > 1 ? arguments : E;
            for (var D = 0, A = B.length; D < A; D++) {
                this.add(B[D])
            }
        } else {
            for (var C in E) {
                if (this.allowFunctions || typeof E[C] != "function") {
                    this.add(C, E[C])
                }
            }
        }
    }, each: function (E, D) {
        var B = [].concat(this.items);
        for (var C = 0, A = B.length; C < A; C++) {
            if (E.call(D || B[C], B[C], C, A) === false) {
                break
            }
        }
    }, eachKey: function (D, C) {
        for (var B = 0, A = this.keys.length; B < A; B++) {
            D.call(C || window, this.keys[B], this.items[B], B, A)
        }
    }, find: function (D, C) {
        for (var B = 0, A = this.items.length; B < A; B++) {
            if (D.call(C || window, this.items[B], this.keys[B])) {
                return this.items[B]
            }
        }
        return null
    }, insert: function (A, B, C) {
        if (arguments.length == 2) {
            C = arguments[1];
            B = this.getKey(C)
        }
        if (A >= this.length) {
            return this.add(B, C)
        }
        this.length++;
        this.items.splice(A, 0, C);
        if (typeof B != "undefined" && B != null) {
            this.map[B] = C
        }
        this.keys.splice(A, 0, B);
        this.fireEvent("add", A, C, B);
        return C
    }, remove: function (A) {
        return this.removeAt(this.indexOf(A))
    }, removeAt: function (A) {
        if (A < this.length && A >= 0) {
            this.length--;
            var C = this.items[A];
            this.items.splice(A, 1);
            var B = this.keys[A];
            if (typeof B != "undefined") {
                delete this.map[B]
            }
            this.keys.splice(A, 1);
            this.fireEvent("remove", C, B);
            return C
        }
        return false
    }, removeKey: function (A) {
        return this.removeAt(this.indexOfKey(A))
    }, getCount: function () {
        return this.length
    }, indexOf: function (A) {
        return this.items.indexOf(A)
    }, indexOfKey: function (A) {
        return this.keys.indexOf(A)
    }, item: function (A) {
        var B = typeof this.map[A] != "undefined" ? this.map[A] : this.items[A];
        return typeof B != "function" || this.allowFunctions ? B : null
    }, itemAt: function (A) {
        return this.items[A]
    }, key: function (A) {
        return this.map[A]
    }, contains: function (A) {
        return this.indexOf(A) != -1
    }, containsKey: function (A) {
        return typeof this.map[A] != "undefined"
    }, clear: function () {
        this.length = 0;
        this.items = [];
        this.keys = [];
        this.map = {};
        this.fireEvent("clear")
    }, first: function () {
        return this.items[0]
    }, last: function () {
        return this.items[this.length - 1]
    }, _sort: function (I, A, H) {
        var C = String(A).toUpperCase() == "DESC" ? -1 : 1;
        H = H || function (K, J) {
                return K - J
            };
        var G = [], B = this.keys, F = this.items;
        for (var D = 0, E = F.length; D < E; D++) {
            G[G.length] = {key: B[D], value: F[D], index: D}
        }
        G.sort(function (K, J) {
            var L = H(K[I], J[I]) * C;
            if (L == 0) {
                L = (K.index < J.index ? -1 : 1)
            }
            return L
        });
        for (var D = 0, E = G.length; D < E; D++) {
            F[D] = G[D].value;
            B[D] = G[D].key
        }
        this.fireEvent("sort", this)
    }, sort: function (A, B) {
        this._sort("value", A, B)
    }, keySort: function (A, B) {
        this._sort("key", A, B || function (D, C) {
                return String(D).toUpperCase() - String(C).toUpperCase()
            })
    }, getRange: function (E, A) {
        var B = this.items;
        if (B.length < 1) {
            return []
        }
        E = E || 0;
        A = Math.min(typeof A == "undefined" ? this.length - 1 : A, this.length - 1);
        var D = [];
        if (E <= A) {
            for (var C = E; C <= A; C++) {
                D[D.length] = B[C]
            }
        } else {
            for (var C = E; C >= A; C--) {
                D[D.length] = B[C]
            }
        }
        return D
    }, filter: function (C, B, D, A) {
        if (Ext.isEmpty(B, false)) {
            return this.clone()
        }
        B = this.createValueMatcher(B, D, A);
        return this.filterBy(function (E) {
            return E && B.test(E[C])
        })
    }, filterBy: function (F, E) {
        var G = new Ext.util.MixedCollection();
        G.getKey = this.getKey;
        var B = this.keys, D = this.items;
        for (var C = 0, A = D.length; C < A; C++) {
            if (F.call(E || this, D[C], B[C])) {
                G.add(B[C], D[C])
            }
        }
        return G
    }, findIndex: function (C, B, E, D, A) {
        if (Ext.isEmpty(B, false)) {
            return -1
        }
        B = this.createValueMatcher(B, D, A);
        return this.findIndexBy(function (F) {
            return F && B.test(F[C])
        }, null, E)
    }, findIndexBy: function (F, E, G) {
        var B = this.keys, D = this.items;
        for (var C = (G || 0), A = D.length; C < A; C++) {
            if (F.call(E || this, D[C], B[C])) {
                return C
            }
        }
        if (typeof G == "number" && G > 0) {
            for (var C = 0; C < G; C++) {
                if (F.call(E || this, D[C], B[C])) {
                    return C
                }
            }
        }
        return -1
    }, createValueMatcher: function (B, C, A) {
        if (!B.exec) {
            B = String(B);
            B = new RegExp((C === true ? "" : "^") + Ext.escapeRe(B), A ? "" : "i")
        }
        return B
    }, clone: function () {
        var E = new Ext.util.MixedCollection();
        var B = this.keys, D = this.items;
        for (var C = 0, A = D.length; C < A; C++) {
            E.add(B[C], D[C])
        }
        E.getKey = this.getKey;
        return E
    }
});
Ext.util.MixedCollection.prototype.get = Ext.util.MixedCollection.prototype.item;