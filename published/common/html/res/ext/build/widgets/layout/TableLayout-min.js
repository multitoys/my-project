/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.layout.TableLayout = Ext.extend(Ext.layout.ContainerLayout, {
    monitorResize: false, setContainer: function (A) {
        Ext.layout.TableLayout.superclass.setContainer.call(this, A);
        this.currentRow = 0;
        this.currentColumn = 0;
        this.spanCells = []
    }, onLayout: function (C, E) {
        var D = C.items.items, A = D.length, F, B;
        if (!this.table) {
            E.addClass("x-table-layout-ct");
            this.table = E.createChild({
                tag: "table",
                cls: "x-table-layout",
                cellspacing: 0,
                cn: {tag: "tbody"}
            }, null, true);
            this.renderAll(C, E)
        }
    }, getRow: function (A) {
        var B = this.table.tBodies[0].childNodes[A];
        if (!B) {
            B = document.createElement("tr");
            this.table.tBodies[0].appendChild(B)
        }
        return B
    }, getNextCell: function (E) {
        var D = document.createElement("td"), I, G;
        if (!this.columns) {
            I = this.getRow(0)
        } else {
            G = this.currentColumn;
            if (G !== 0 && (G % this.columns === 0)) {
                this.currentRow++;
                G = (E.colspan || 1)
            } else {
                G += (E.colspan || 1)
            }
            var H = this.getNextNonSpan(G, this.currentRow);
            this.currentColumn = H[0];
            if (H[1] != this.currentRow) {
                this.currentRow = H[1];
                if (E.colspan) {
                    this.currentColumn += E.colspan - 1
                }
            }
            I = this.getRow(this.currentRow)
        }
        if (E.colspan) {
            D.colSpan = E.colspan
        }
        D.className = "x-table-layout-cell";
        if (E.rowspan) {
            D.rowSpan = E.rowspan;
            var F = this.currentRow, C = E.colspan || 1;
            for (var A = F + 1; A < F + E.rowspan; A++) {
                for (var B = this.currentColumn - C + 1; B <= this.currentColumn; B++) {
                    if (!this.spanCells[B]) {
                        this.spanCells[B] = []
                    }
                    this.spanCells[B][A] = 1
                }
            }
        }
        I.appendChild(D);
        return D
    }, getNextNonSpan: function (A, E) {
        var D = (A <= this.columns ? A : this.columns), C = E;
        for (var B = D; B <= this.columns; B++) {
            if (this.spanCells[B] && this.spanCells[B][C]) {
                if (++D > this.columns) {
                    return this.getNextNonSpan(1, ++C)
                }
            } else {
                break
            }
        }
        return [D, C]
    }, renderItem: function (C, A, B) {
        if (C && !C.rendered) {
            C.render(this.getNextCell(C))
        }
    }, isValidParent: function (B, A) {
        return true
    }
});
Ext.Container.LAYOUTS["table"] = Ext.layout.TableLayout;