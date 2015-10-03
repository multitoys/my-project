/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.GridView = function (A) {
    Ext.apply(this, A);
    this.addEvents("beforerowremoved", "beforerowsinserted", "beforerefresh", "rowremoved", "rowsinserted", "rowupdated", "refresh");
    Ext.grid.GridView.superclass.constructor.call(this)
};
Ext.extend(Ext.grid.GridView, Ext.util.Observable, {
    scrollOffset: 19,
    autoFill: false,
    forceFit: false,
    sortClasses: ["sort-asc", "sort-desc"],
    sortAscText: "Sort Ascending",
    sortDescText: "Sort Descending",
    columnsText: "Columns",
    borderWidth: 2,
    initTemplates: function () {
        var C = this.templates || {};
        if (!C.master) {
            C.master = new Ext.Template("<div class=\"x-grid3\" hidefocus=\"true\">", "<div class=\"x-grid3-viewport\">", "<div class=\"x-grid3-header\"><div class=\"x-grid3-header-inner\"><div class=\"x-grid3-header-offset\">{header}</div></div><div class=\"x-clear\"></div></div>", "<div class=\"x-grid3-scroller\"><div class=\"x-grid3-body\">{body}</div><a href=\"#\" class=\"x-grid3-focus\" tabIndex=\"-1\"></a></div>", "</div>", "<div class=\"x-grid3-resize-marker\">&#160;</div>", "<div class=\"x-grid3-resize-proxy\">&#160;</div>", "</div>")
        }
        if (!C.header) {
            C.header = new Ext.Template("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"{tstyle}\">", "<thead><tr class=\"x-grid3-hd-row\">{cells}</tr></thead>", "</table>")
        }
        if (!C.hcell) {
            C.hcell = new Ext.Template("<td class=\"x-grid3-hd x-grid3-cell x-grid3-td-{id}\" style=\"{style}\"><div {tooltip} {attr} class=\"x-grid3-hd-inner x-grid3-hd-{id}\" unselectable=\"on\" style=\"{istyle}\">", this.grid.enableHdMenu ? "<a class=\"x-grid3-hd-btn\" href=\"#\"></a>" : "", "{value}<img class=\"x-grid3-sort-icon\" src=\"", Ext.BLANK_IMAGE_URL, "\" />", "</div></td>")
        }
        if (!C.body) {
            C.body = new Ext.Template("{rows}")
        }
        if (!C.row) {
            C.row = new Ext.Template("<div class=\"x-grid3-row {alt}\" style=\"{tstyle}\"><table class=\"x-grid3-row-table\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"{tstyle}\">", "<tbody><tr>{cells}</tr>", (this.enableRowBody ? "<tr class=\"x-grid3-row-body-tr\" style=\"{bodyStyle}\"><td colspan=\"{cols}\" class=\"x-grid3-body-cell\" tabIndex=\"0\" hidefocus=\"on\"><div class=\"x-grid3-row-body\">{body}</div></td></tr>" : ""), "</tbody></table></div>")
        }
        if (!C.cell) {
            C.cell = new Ext.Template("<td class=\"x-grid3-col x-grid3-cell x-grid3-td-{id} {css}\" style=\"{style}\" tabIndex=\"0\" {cellAttr}>", "<div class=\"x-grid3-cell-inner x-grid3-col-{id}\" unselectable=\"on\" {attr}>{value}</div>", "</td>")
        }
        for (var A in C) {
            var B = C[A];
            if (B && typeof B.compile == "function" && !B.compiled) {
                B.disableFormats = true;
                B.compile()
            }
        }
        this.templates = C;
        this.tdClass = "x-grid3-cell";
        this.cellSelector = "td.x-grid3-cell";
        this.hdCls = "x-grid3-hd";
        this.rowSelector = "div.x-grid3-row";
        this.colRe = new RegExp("x-grid3-td-([^\\s]+)", "")
    },
    fly: function (A) {
        if (!this._flyweight) {
            this._flyweight = new Ext.Element.Flyweight(document.body)
        }
        this._flyweight.dom = A;
        return this._flyweight
    },
    getEditorParent: function (A) {
        return this.scroller.dom
    },
    initElements: function () {
        var C = Ext.Element;
        var B = this.grid.getGridEl().dom.firstChild;
        var A = B.childNodes;
        this.el = new C(B);
        this.mainWrap = new C(A[0]);
        this.mainHd = new C(this.mainWrap.dom.firstChild);
        this.innerHd = this.mainHd.dom.firstChild;
        this.scroller = new C(this.mainWrap.dom.childNodes[1]);
        if (this.forceFit) {
            this.scroller.setStyle("overflow-x", "hidden")
        }
        this.mainBody = new C(this.scroller.dom.firstChild);
        this.focusEl = new C(this.scroller.dom.childNodes[1]);
        this.focusEl.swallowEvent("click", true);
        this.resizeMarker = new C(A[1]);
        this.resizeProxy = new C(A[2])
    },
    getRows: function () {
        return this.hasRows() ? this.mainBody.dom.childNodes : []
    },
    findCell: function (A) {
        if (!A) {
            return false
        }
        return this.fly(A).findParent(this.cellSelector, 3)
    },
    findCellIndex: function (C, B) {
        var A = this.findCell(C);
        if (A && (!B || this.fly(A).hasClass(B))) {
            return this.getCellIndex(A)
        }
        return false
    },
    getCellIndex: function (B) {
        if (B) {
            var A = B.className.match(this.colRe);
            if (A && A[1]) {
                return this.cm.getIndexById(A[1])
            }
        }
        return false
    },
    findHeaderCell: function (B) {
        var A = this.findCell(B);
        return A && this.fly(A).hasClass(this.hdCls) ? A : null
    },
    findHeaderIndex: function (A) {
        return this.findCellIndex(A, this.hdCls)
    },
    findRow: function (A) {
        if (!A) {
            return false
        }
        return this.fly(A).findParent(this.rowSelector, 10)
    },
    findRowIndex: function (A) {
        var B = this.findRow(A);
        return B ? B.rowIndex : false
    },
    getRow: function (A) {
        return this.getRows()[A]
    },
    getCell: function (B, A) {
        return this.getRow(B).getElementsByTagName("td")[A]
    },
    getHeaderCell: function (A) {
        return this.mainHd.dom.getElementsByTagName("td")[A]
    },
    addRowClass: function (C, A) {
        var B = this.getRow(C);
        if (B) {
            this.fly(B).addClass(A)
        }
    },
    removeRowClass: function (C, A) {
        var B = this.getRow(C);
        if (B) {
            this.fly(B).removeClass(A)
        }
    },
    removeRow: function (A) {
        Ext.removeNode(this.getRow(A))
    },
    removeRows: function (C, A) {
        var B = this.mainBody.dom;
        for (var D = C; D <= A; D++) {
            Ext.removeNode(B.childNodes[C])
        }
    },
    getScrollState: function () {
        var A = this.scroller.dom;
        return {left: A.scrollLeft, top: A.scrollTop}
    },
    restoreScroll: function (A) {
        var B = this.scroller.dom;
        B.scrollLeft = A.left;
        B.scrollTop = A.top
    },
    scrollToTop: function () {
        this.scroller.dom.scrollTop = 0;
        this.scroller.dom.scrollLeft = 0
    },
    syncScroll: function () {
        this.syncHeaderScroll();
        var A = this.scroller.dom;
        this.grid.fireEvent("bodyscroll", A.scrollLeft, A.scrollTop)
    },
    syncHeaderScroll: function () {
        var A = this.scroller.dom;
        this.innerHd.scrollLeft = A.scrollLeft;
        this.innerHd.scrollLeft = A.scrollLeft
    },
    updateSortIcon: function (B, A) {
        var D = this.sortClasses;
        var C = this.mainHd.select("td").removeClass(D);
        C.item(B).addClass(D[A == "DESC" ? 1 : 0])
    },
    updateAllColumnWidths: function () {
        var D = this.getTotalWidth();
        var H = this.cm.getColumnCount();
        var F = [];
        for (var B = 0; B < H; B++) {
            F[B] = this.getColumnWidth(B)
        }
        this.innerHd.firstChild.firstChild.style.width = D;
        for (var B = 0; B < H; B++) {
            var C = this.getHeaderCell(B);
            C.style.width = F[B]
        }
        var G = this.getRows();
        for (var B = 0, E = G.length; B < E; B++) {
            G[B].style.width = D;
            G[B].firstChild.style.width = D;
            var I = G[B].firstChild.rows[0];
            for (var A = 0; A < H; A++) {
                I.childNodes[A].style.width = F[A]
            }
        }
        this.onAllColumnWidthsUpdated(F, D)
    },
    updateColumnWidth: function (D, G) {
        var B = this.getColumnWidth(D);
        var C = this.getTotalWidth();
        this.innerHd.firstChild.firstChild.style.width = C;
        var H = this.getHeaderCell(D);
        H.style.width = B;
        var F = this.getRows();
        for (var E = 0, A = F.length; E < A; E++) {
            F[E].style.width = C;
            F[E].firstChild.style.width = C;
            F[E].firstChild.rows[0].childNodes[D].style.width = B
        }
        this.onColumnWidthUpdated(D, B, C)
    },
    updateColumnHidden: function (C, F) {
        var B = this.getTotalWidth();
        this.innerHd.firstChild.firstChild.style.width = B;
        var H = F ? "none" : "";
        var G = this.getHeaderCell(C);
        G.style.display = H;
        var E = this.getRows();
        for (var D = 0, A = E.length; D < A; D++) {
            E[D].style.width = B;
            E[D].firstChild.style.width = B;
            E[D].firstChild.rows[0].childNodes[C].style.display = H
        }
        this.onColumnHiddenUpdated(C, F, B);
        delete this.lastViewWidth;
        this.layout()
    },
    doRender: function (E, G, M, A, L, Q) {
        var B = this.templates, D = B.cell, F = B.row, H = L - 1;
        var C = "width:" + this.getTotalWidth() + ";";
        var T = [], N, U, O = {}, I = {tstyle: C}, K;
        for (var P = 0, S = G.length; P < S; P++) {
            K = G[P];
            N = [];
            var J = (P + A);
            for (var R = 0; R < L; R++) {
                U = E[R];
                O.id = U.id;
                O.css = R == 0 ? "x-grid3-cell-first " : (R == H ? "x-grid3-cell-last " : "");
                O.attr = O.cellAttr = "";
                O.value = U.renderer(K.data[U.name], O, K, J, R, M);
                O.style = U.style;
                if (O.value == undefined || O.value === "") {
                    O.value = "&#160;"
                }
                if (K.dirty && typeof K.modified[U.name] !== "undefined") {
                    O.css += " x-grid3-dirty-cell"
                }
                N[N.length] = D.apply(O)
            }
            var V = [];
            if (Q && ((J + 1) % 2 == 0)) {
                V[0] = "x-grid3-row-alt"
            }
            if (K.dirty) {
                V[1] = " x-grid3-dirty-row"
            }
            I.cols = L;
            if (this.getRowClass) {
                V[2] = this.getRowClass(K, J, I, M)
            }
            I.alt = V.join(" ");
            I.cells = N.join("");
            T[T.length] = F.apply(I)
        }
        return T.join("")
    },
    processRows: function (E, D) {
        if (this.ds.getCount() < 1) {
            return
        }
        D = D || !this.grid.stripeRows;
        E = E || 0;
        var I = this.getRows();
        var F = " x-grid3-row-alt ";
        for (var B = E, C = I.length; B < C; B++) {
            var H = I[B];
            H.rowIndex = B;
            if (!D) {
                var A = ((B + 1) % 2 == 0);
                var G = (" " + H.className + " ").indexOf(F) != -1;
                if (A == G) {
                    continue
                }
                if (A) {
                    H.className += " x-grid3-row-alt"
                } else {
                    H.className = H.className.replace("x-grid3-row-alt", "")
                }
            }
        }
    },
    renderUI: function () {
        var E = this.renderHeaders();
        var B = this.templates.body.apply({rows: ""});
        var C = this.templates.master.apply({body: B, header: E});
        var D = this.grid;
        D.getGridEl().dom.innerHTML = C;
        this.initElements();
        this.mainBody.dom.innerHTML = this.renderRows();
        this.processRows(0, true);
        Ext.fly(this.innerHd).on("click", this.handleHdDown, this);
        this.mainHd.on("mouseover", this.handleHdOver, this);
        this.mainHd.on("mouseout", this.handleHdOut, this);
        this.mainHd.on("mousemove", this.handleHdMove, this);
        this.scroller.on("scroll", this.syncScroll, this);
        if (D.enableColumnResize !== false) {
            this.splitone = new Ext.grid.GridView.SplitDragZone(D, this.mainHd.dom)
        }
        if (D.enableColumnMove) {
            this.columnDrag = new Ext.grid.GridView.ColumnDragZone(D, this.innerHd);
            this.columnDrop = new Ext.grid.HeaderDropZone(D, this.mainHd.dom)
        }
        if (D.enableHdMenu !== false) {
            if (D.enableColumnHide !== false) {
                this.colMenu = new Ext.menu.Menu({id: D.id + "-hcols-menu"});
                this.colMenu.on("beforeshow", this.beforeColMenuShow, this);
                this.colMenu.on("itemclick", this.handleHdMenuClick, this)
            }
            this.hmenu = new Ext.menu.Menu({id: D.id + "-hctx"});
            this.hmenu.add({id: "asc", text: this.sortAscText, cls: "xg-hmenu-sort-asc"}, {
                id: "desc",
                text: this.sortDescText,
                cls: "xg-hmenu-sort-desc"
            });
            if (D.enableColumnHide !== false) {
                this.hmenu.add("-", {id: "columns", text: this.columnsText, menu: this.colMenu, iconCls: "x-cols-icon"})
            }
            this.hmenu.on("itemclick", this.handleHdMenuClick, this)
        }
        if (D.enableDragDrop || D.enableDrag) {
            var A = new Ext.grid.GridDragZone(D, {ddGroup: D.ddGroup || "GridDD"})
        }
        this.updateHeaderSortState()
    },
    layout: function () {
        if (!this.mainBody) {
            return
        }
        var E = this.grid;
        var G = E.getGridEl(), I = this.cm, B = E.autoExpandColumn, A = this;
        var C = G.getSize(true);
        var H = C.width;
        if (H < 20 || C.height < 20) {
            return
        }
        if (E.autoHeight) {
            this.scroller.dom.style.overflow = "visible"
        } else {
            this.el.setSize(C.width, C.height);
            var F = this.mainHd.getHeight();
            var D = C.height - (F);
            this.scroller.setSize(H, D);
            if (this.innerHd) {
                this.innerHd.style.width = (H) + "px"
            }
        }
        if (this.forceFit) {
            if (this.lastViewWidth != H) {
                this.fitColumns(false, false);
                this.lastViewWidth = H
            }
        } else {
            this.autoExpand();
            this.syncHeaderScroll()
        }
        this.onLayout(H, D)
    },
    onLayout: function (A, B) {
    },
    onColumnWidthUpdated: function (C, A, B) {
    },
    onAllColumnWidthsUpdated: function (A, B) {
    },
    onColumnHiddenUpdated: function (B, C, A) {
    },
    updateColumnText: function (A, B) {
    },
    afterMove: function (A) {
    },
    init: function (A) {
        this.grid = A;
        this.initTemplates();
        this.initData(A.store, A.colModel);
        this.initUI(A)
    },
    getColumnId: function (A) {
        return this.cm.getColumnId(A)
    },
    renderHeaders: function () {
        var C = this.cm, F = this.templates;
        var E = F.hcell;
        var B = [], H = [], G = {};
        for (var D = 0, A = C.getColumnCount(); D < A; D++) {
            G.id = C.getColumnId(D);
            G.value = C.getColumnHeader(D) || "";
            G.style = this.getColumnStyle(D, true);
            G.tooltip = this.getColumnTooltip(D);
            if (C.config[D].align == "right") {
                G.istyle = "padding-right:16px"
            } else {
                delete G.istyle
            }
            B[B.length] = E.apply(G)
        }
        return F.header.apply({cells: B.join(""), tstyle: "width:" + this.getTotalWidth() + ";"})
    },
    getColumnTooltip: function (A) {
        var B = this.cm.getColumnTooltip(A);
        if (B) {
            if (Ext.QuickTips.isEnabled()) {
                return "ext:qtip=\"" + B + "\""
            } else {
                return "title=\"" + B + "\""
            }
        }
        return ""
    },
    beforeUpdate: function () {
        this.grid.stopEditing()
    },
    updateHeaders: function () {
        this.innerHd.firstChild.innerHTML = this.renderHeaders()
    },
    focusRow: function (A) {
        this.focusCell(A, 0, false)
    },
    focusCell: function (D, A, C) {
        var B = this.ensureVisible(D, A, C);
        this.focusEl.setXY(B);
        if (Ext.isGecko) {
            this.focusEl.focus()
        } else {
            this.focusEl.focus.defer(1, this.focusEl)
        }
    },
    ensureVisible: function (P, E, D) {
        if (typeof P != "number") {
            P = P.rowIndex
        }
        if (!this.ds) {
            return
        }
        if (P < 0 || P >= this.ds.getCount()) {
            return
        }
        E = (E !== undefined ? E : 0);
        var I = this.getRow(P), F;
        if (!(D === false && E === 0)) {
            while (this.cm.isHidden(E)) {
                E++
            }
            F = this.getCell(P, E)
        }
        if (!I) {
            return
        }
        var L = this.scroller.dom;
        var O = 0;
        var C = I, M = this.el.dom;
        while (C && C != M) {
            O += C.offsetTop;
            C = C.offsetParent
        }
        O -= this.mainHd.dom.offsetHeight;
        var N = O + I.offsetHeight;
        var A = L.clientHeight;
        var M = parseInt(L.scrollTop, 10);
        var K = M + A;
        if (O < M) {
            L.scrollTop = O
        } else {
            if (N > K) {
                L.scrollTop = N - A
            }
        }
        if (D !== false) {
            var J = parseInt(F.offsetLeft, 10);
            var H = J + F.offsetWidth;
            var G = parseInt(L.scrollLeft, 10);
            var B = G + L.clientWidth;
            if (J < G) {
                L.scrollLeft = J
            } else {
                if (H > B) {
                    L.scrollLeft = H - L.clientWidth
                }
            }
        }
        return F ? Ext.fly(F).getXY() : [L.scrollLeft, Ext.fly(I).getY()]
    },
    insertRows: function (A, F, C, E) {
        if (F === 0 && C == A.getCount() - 1) {
            this.refresh()
        } else {
            if (!E) {
                this.fireEvent("beforerowsinserted", this, F, C)
            }
            var B = this.renderRows(F, C);
            var D = this.getRow(F);
            if (D) {
                Ext.DomHelper.insertHtml("beforeBegin", D, B)
            } else {
                Ext.DomHelper.insertHtml("beforeEnd", this.mainBody.dom, B)
            }
            if (!E) {
                this.fireEvent("rowsinserted", this, F, C);
                this.processRows(F)
            }
        }
    },
    deleteRows: function (A, C, B) {
        if (A.getRowCount() < 1) {
            this.refresh()
        } else {
            this.fireEvent("beforerowsdeleted", this, C, B);
            this.removeRows(C, B);
            this.processRows(C);
            this.fireEvent("rowsdeleted", this, C, B)
        }
    },
    getColumnStyle: function (A, C) {
        var B = !C ? (this.cm.config[A].css || "") : "";
        B += "width:" + this.getColumnWidth(A) + ";";
        if (this.cm.isHidden(A)) {
            B += "display:none;"
        }
        var D = this.cm.config[A].align;
        if (D) {
            B += "text-align:" + D + ";"
        }
        return B
    },
    getColumnWidth: function (B) {
        var A = this.cm.getColumnWidth(B);
        if (typeof A == "number") {
            return (Ext.isBorderBox ? A : (A - this.borderWidth > 0 ? A - this.borderWidth : 0)) + "px"
        }
        return A
    },
    getTotalWidth: function () {
        return this.cm.getTotalWidth() + "px"
    },
    fitColumns: function (D, G, E) {
        var F = this.cm, S, L, O;
        var R = F.getTotalWidth(false);
        var J = this.grid.getGridEl().getWidth(true) - this.scrollOffset;
        if (J < 20) {
            return
        }
        var B = J - R;
        if (B === 0) {
            return false
        }
        var A = F.getColumnCount(true);
        var P = A - (typeof E == "number" ? 1 : 0);
        if (P === 0) {
            P = 1;
            E = undefined
        }
        var K = F.getColumnCount();
        var I = [];
        var N = 0;
        var M = 0;
        var H;
        for (O = 0; O < K; O++) {
            if (!F.isHidden(O) && !F.isFixed(O) && O !== E) {
                H = F.getColumnWidth(O);
                I.push(O);
                N = O;
                I.push(H);
                M += H
            }
        }
        var C = (J - F.getTotalWidth()) / M;
        while (I.length) {
            H = I.pop();
            O = I.pop();
            F.setColumnWidth(O, Math.max(this.grid.minColumnWidth, Math.floor(H + H * C)), true)
        }
        if ((R = F.getTotalWidth(false)) > J) {
            var Q = P != A ? E : N;
            F.setColumnWidth(Q, Math.max(1, F.getColumnWidth(Q) - (R - J)), true)
        }
        if (D !== true) {
            this.updateAllColumnWidths()
        }
        return true
    },
    autoExpand: function (B) {
        var G = this.grid, A = this.cm;
        if (!this.userResized && G.autoExpandColumn) {
            var D = A.getTotalWidth(false);
            var H = this.grid.getGridEl().getWidth(true) - this.scrollOffset;
            if (D != H) {
                var F = A.getIndexById(G.autoExpandColumn);
                var E = A.getColumnWidth(F);
                var C = Math.min(Math.max(((H - D) + E), G.autoExpandMin), G.autoExpandMax);
                if (C != E) {
                    A.setColumnWidth(F, C, true);
                    if (B !== true) {
                        this.updateColumnWidth(F, C)
                    }
                }
            }
        }
    },
    getColumnData: function () {
        var D = [], A = this.cm, E = A.getColumnCount();
        for (var C = 0; C < E; C++) {
            var B = A.getDataIndex(C);
            D[C] = {
                name: (typeof B == "undefined" ? this.ds.fields.get(C).name : B),
                renderer: A.getRenderer(C),
                id: A.getColumnId(C),
                style: this.getColumnStyle(C)
            }
        }
        return D
    },
    renderRows: function (H, C) {
        var D = this.grid, F = D.colModel, A = D.store, I = D.stripeRows;
        var G = F.getColumnCount();
        if (A.getCount() < 1) {
            return ""
        }
        var E = this.getColumnData();
        H = H || 0;
        C = typeof C == "undefined" ? A.getCount() - 1 : C;
        var B = A.getRange(H, C);
        return this.doRender(E, B, A, H, G, I)
    },
    renderBody: function () {
        var A = this.renderRows();
        return this.templates.body.apply({rows: A})
    },
    refreshRow: function (B) {
        var D = this.ds, C;
        if (typeof B == "number") {
            C = B;
            B = D.getAt(C)
        } else {
            C = D.indexOf(B)
        }
        var A = [];
        this.insertRows(D, C, C, true);
        this.getRow(C).rowIndex = C;
        this.onRemove(D, B, C + 1, true);
        this.fireEvent("rowupdated", this, C, B)
    },
    refresh: function (B) {
        this.fireEvent("beforerefresh", this);
        this.grid.stopEditing();
        var A = this.renderBody();
        this.mainBody.update(A);
        if (B === true) {
            this.updateHeaders();
            this.updateHeaderSortState()
        }
        this.processRows(0, true);
        this.layout();
        this.applyEmptyText();
        this.fireEvent("refresh", this)
    },
    applyEmptyText: function () {
        if (this.emptyText && !this.hasRows()) {
            this.mainBody.update("<div class=\"x-grid-empty\">" + this.emptyText + "</div>")
        }
    },
    updateHeaderSortState: function () {
        var B = this.ds.getSortState();
        if (!B) {
            return
        }
        if (!this.sortState || (this.sortState.field != B.field || this.sortState.direction != B.direction)) {
            this.grid.fireEvent("sortchange", this.grid, B)
        }
        this.sortState = B;
        var C = this.cm.findColumnIndex(B.field);
        if (C != -1) {
            var A = B.direction;
            this.updateSortIcon(C, A)
        }
    },
    destroy: function () {
        if (this.colMenu) {
            this.colMenu.removeAll();
            Ext.menu.MenuMgr.unregister(this.colMenu);
            this.colMenu.getEl().remove();
            delete this.colMenu
        }
        if (this.hmenu) {
            this.hmenu.removeAll();
            Ext.menu.MenuMgr.unregister(this.hmenu);
            this.hmenu.getEl().remove();
            delete this.hmenu
        }
        if (this.grid.enableColumnMove) {
            var C = Ext.dd.DDM.ids["gridHeader" + this.grid.getGridEl().id];
            if (C) {
                for (var A in C) {
                    if (!C[A].config.isTarget && C[A].dragElId) {
                        var B = C[A].dragElId;
                        C[A].unreg();
                        Ext.get(B).remove()
                    } else {
                        if (C[A].config.isTarget) {
                            C[A].proxyTop.remove();
                            C[A].proxyBottom.remove();
                            C[A].unreg()
                        }
                    }
                    if (Ext.dd.DDM.locationCache[A]) {
                        delete Ext.dd.DDM.locationCache[A]
                    }
                }
                delete Ext.dd.DDM.ids["gridHeader" + this.grid.getGridEl().id]
            }
        }
        Ext.destroy(this.resizeMarker, this.resizeProxy);
        this.initData(null, null);
        Ext.EventManager.removeResizeListener(this.onWindowResize, this)
    },
    onDenyColumnHide: function () {
    },
    render: function () {
        var A = this.cm;
        var B = A.getColumnCount();
        if (this.grid.monitorWindowResize === true) {
            Ext.EventManager.onWindowResize(this.onWindowResize, this, true)
        }
        if (this.autoFill) {
            this.fitColumns(true, true)
        } else {
            if (this.forceFit) {
                this.fitColumns(true, false)
            } else {
                if (this.grid.autoExpandColumn) {
                    this.autoExpand(true)
                }
            }
        }
        this.renderUI()
    },
    onWindowResize: function () {
        if (!this.grid.monitorWindowResize || this.grid.autoHeight) {
            return
        }
        this.layout()
    },
    initData: function (B, A) {
        if (this.ds) {
            this.ds.un("load", this.onLoad, this);
            this.ds.un("datachanged", this.onDataChange, this);
            this.ds.un("add", this.onAdd, this);
            this.ds.un("remove", this.onRemove, this);
            this.ds.un("update", this.onUpdate, this);
            this.ds.un("clear", this.onClear, this)
        }
        if (B) {
            B.on("load", this.onLoad, this);
            B.on("datachanged", this.onDataChange, this);
            B.on("add", this.onAdd, this);
            B.on("remove", this.onRemove, this);
            B.on("update", this.onUpdate, this);
            B.on("clear", this.onClear, this)
        }
        this.ds = B;
        if (this.cm) {
            this.cm.un("configchange", this.onColConfigChange, this);
            this.cm.un("widthchange", this.onColWidthChange, this);
            this.cm.un("headerchange", this.onHeaderChange, this);
            this.cm.un("hiddenchange", this.onHiddenChange, this);
            this.cm.un("columnmoved", this.onColumnMove, this);
            this.cm.un("columnlockchange", this.onColumnLock, this)
        }
        if (A) {
            A.on("configchange", this.onColConfigChange, this);
            A.on("widthchange", this.onColWidthChange, this);
            A.on("headerchange", this.onHeaderChange, this);
            A.on("hiddenchange", this.onHiddenChange, this);
            A.on("columnmoved", this.onColumnMove, this);
            A.on("columnlockchange", this.onColumnLock, this)
        }
        this.cm = A
    },
    onDataChange: function () {
        this.refresh();
        this.updateHeaderSortState()
    },
    onClear: function () {
        this.refresh()
    },
    onUpdate: function (B, A) {
        this.refreshRow(A)
    },
    onAdd: function (C, A, B) {
        this.insertRows(C, B, B + (A.length - 1))
    },
    onRemove: function (D, A, B, C) {
        if (C !== true) {
            this.fireEvent("beforerowremoved", this, B, A)
        }
        this.removeRow(B);
        if (C !== true) {
            this.processRows(B);
            this.applyEmptyText();
            this.fireEvent("rowremoved", this, B, A)
        }
    },
    onLoad: function () {
        this.scrollToTop()
    },
    onColWidthChange: function (A, B, C) {
        this.updateColumnWidth(B, C)
    },
    onHeaderChange: function (A, B, C) {
        this.updateHeaders()
    },
    onHiddenChange: function (A, B, C) {
        this.updateColumnHidden(B, C)
    },
    onColumnMove: function (A, D, B) {
        this.indexMap = null;
        var C = this.getScrollState();
        this.refresh(true);
        this.restoreScroll(C);
        this.afterMove(B)
    },
    onColConfigChange: function () {
        delete this.lastViewWidth;
        this.indexMap = null;
        this.refresh(true)
    },
    initUI: function (A) {
        A.on("headerclick", this.onHeaderClick, this);
        if (A.trackMouseOver) {
            A.on("mouseover", this.onRowOver, this);
            A.on("mouseout", this.onRowOut, this)
        }
    },
    initEvents: function () {
    },
    onHeaderClick: function (B, A) {
        if (this.headersDisabled || !this.cm.isSortable(A)) {
            return
        }
        B.stopEditing();
        B.store.sort(this.cm.getDataIndex(A))
    },
    onRowOver: function (B, A) {
        var C;
        if ((C = this.findRowIndex(A)) !== false) {
            this.addRowClass(C, "x-grid3-row-over")
        }
    },
    onRowOut: function (B, A) {
        var C;
        if ((C = this.findRowIndex(A)) !== false && C !== this.findRowIndex(B.getRelatedTarget())) {
            this.removeRowClass(C, "x-grid3-row-over")
        }
    },
    handleWheel: function (A) {
        A.stopPropagation()
    },
    onRowSelect: function (A) {
        this.addRowClass(A, "x-grid3-row-selected")
    },
    onRowDeselect: function (A) {
        this.removeRowClass(A, "x-grid3-row-selected")
    },
    onCellSelect: function (C, B) {
        var A = this.getCell(C, B);
        if (A) {
            this.fly(A).addClass("x-grid3-cell-selected")
        }
    },
    onCellDeselect: function (C, B) {
        var A = this.getCell(C, B);
        if (A) {
            this.fly(A).removeClass("x-grid3-cell-selected")
        }
    },
    onColumnSplitterMoved: function (C, B) {
        this.userResized = true;
        var A = this.grid.colModel;
        A.setColumnWidth(C, B, true);
        if (this.forceFit) {
            this.fitColumns(true, false, C);
            this.updateAllColumnWidths()
        } else {
            this.updateColumnWidth(C, B)
        }
        this.grid.fireEvent("columnresize", C, B)
    },
    handleHdMenuClick: function (C) {
        var B = this.hdCtxIndex;
        var A = this.cm, D = this.ds;
        switch (C.id) {
            case"asc":
                D.sort(A.getDataIndex(B), "ASC");
                break;
            case"desc":
                D.sort(A.getDataIndex(B), "DESC");
                break;
            default:
                B = A.getIndexById(C.id.substr(4));
                if (B != -1) {
                    if (C.checked && A.getColumnsBy(this.isHideableColumn, this).length <= 1) {
                        this.onDenyColumnHide();
                        return false
                    }
                    A.setHidden(B, C.checked)
                }
        }
        return true
    },
    isHideableColumn: function (A) {
        return !A.hidden && !A.fixed
    },
    beforeColMenuShow: function () {
        var A = this.cm, C = A.getColumnCount();
        this.colMenu.removeAll();
        for (var B = 0; B < C; B++) {
            if (A.config[B].fixed !== true && A.config[B].hideable !== false) {
                this.colMenu.add(new Ext.menu.CheckItem({
                    id: "col-" + A.getColumnId(B),
                    text: A.getColumnHeader(B),
                    checked: !A.isHidden(B),
                    hideOnClick: false,
                    disabled: A.config[B].hideable === false
                }))
            }
        }
    },
    handleHdDown: function (F, D) {
        if (Ext.fly(D).hasClass("x-grid3-hd-btn")) {
            F.stopEvent();
            var E = this.findHeaderCell(D);
            Ext.fly(E).addClass("x-grid3-hd-menu-open");
            var C = this.getCellIndex(E);
            this.hdCtxIndex = C;
            var B = this.hmenu.items, A = this.cm;
            B.get("asc").setDisabled(!A.isSortable(C));
            B.get("desc").setDisabled(!A.isSortable(C));
            this.hmenu.on("hide", function () {
                Ext.fly(E).removeClass("x-grid3-hd-menu-open")
            }, this, {single: true});
            this.hmenu.show(D, "tl-bl?")
        }
    },
    handleHdOver: function (D, A) {
        var C = this.findHeaderCell(A);
        if (C && !this.headersDisabled) {
            this.activeHd = C;
            this.activeHdIndex = this.getCellIndex(C);
            var B = this.fly(C);
            this.activeHdRegion = B.getRegion();
            if (this.cm.isSortable(this.activeHdIndex) && !this.cm.isFixed(this.activeHdIndex)) {
                B.addClass("x-grid3-hd-over");
                this.activeHdBtn = B.child(".x-grid3-hd-btn");
                if (this.activeHdBtn) {
                    this.activeHdBtn.dom.style.height = (C.firstChild.offsetHeight - 1) + "px"
                }
            }
        }
    },
    handleHdMove: function (F, D) {
        if (this.activeHd && !this.headersDisabled) {
            var B = this.splitHandleWidth || 5;
            var E = this.activeHdRegion;
            var A = F.getPageX();
            var C = this.activeHd.style;
            if (A - E.left <= B && this.cm.isResizable(this.activeHdIndex - 1)) {
                if (Ext.isSafari) {
                    C.cursor = "e-resize"
                } else {
                    C.cursor = "col-resize"
                }
            } else {
                if (E.right - A <= (!this.activeHdBtn ? B : 2) && this.cm.isResizable(this.activeHdIndex)) {
                    if (Ext.isSafari) {
                        C.cursor = "w-resize"
                    } else {
                        C.cursor = "col-resize"
                    }
                } else {
                    C.cursor = ""
                }
            }
        }
    },
    handleHdOut: function (C, A) {
        var B = this.findHeaderCell(A);
        if (B && (!Ext.isIE || !C.within(B, true))) {
            this.activeHd = null;
            this.fly(B).removeClass("x-grid3-hd-over");
            B.style.cursor = ""
        }
    },
    hasRows: function () {
        var A = this.mainBody.dom.firstChild;
        return A && A.className != "x-grid-empty"
    },
    bind: function (A, B) {
        this.initData(A, B)
    }
});
Ext.grid.GridView.SplitDragZone = function (A, B) {
    this.grid = A;
    this.view = A.getView();
    this.marker = this.view.resizeMarker;
    this.proxy = this.view.resizeProxy;
    Ext.grid.GridView.SplitDragZone.superclass.constructor.call(this, B, "gridSplitters" + this.grid.getGridEl().id, {
        dragElId: Ext.id(this.proxy.dom),
        resizeFrame: false
    });
    this.scroll = false;
    this.hw = this.view.splitHandleWidth || 5
};
Ext.extend(Ext.grid.GridView.SplitDragZone, Ext.dd.DDProxy, {
    b4StartDrag: function (A, E) {
        this.view.headersDisabled = true;
        var D = this.view.mainWrap.getHeight();
        this.marker.setHeight(D);
        this.marker.show();
        this.marker.alignTo(this.view.getHeaderCell(this.cellIndex), "tl-tl", [-2, 0]);
        this.proxy.setHeight(D);
        var B = this.cm.getColumnWidth(this.cellIndex);
        var C = Math.max(B - this.grid.minColumnWidth, 0);
        this.resetConstraints();
        this.setXConstraint(C, 1000);
        this.setYConstraint(0, 0);
        this.minX = A - C;
        this.maxX = A + 1000;
        this.startPos = A;
        Ext.dd.DDProxy.prototype.b4StartDrag.call(this, A, E)
    }, handleMouseDown: function (A) {
        var H = this.view.findHeaderCell(A.getTarget());
        if (H) {
            var K = this.view.fly(H).getXY(), E = K[0], D = K[1];
            var I = A.getXY(), C = I[0], B = I[1];
            var G = H.offsetWidth, F = false;
            if ((C - E) <= this.hw) {
                F = -1
            } else {
                if ((E + G) - C <= this.hw) {
                    F = 0
                }
            }
            if (F !== false) {
                this.cm = this.grid.colModel;
                var J = this.view.getCellIndex(H);
                if (F == -1) {
                    if (J + F < 0) {
                        return
                    }
                    while (this.cm.isHidden(J + F)) {
                        --F;
                        if (J + F < 0) {
                            return
                        }
                    }
                }
                this.cellIndex = J + F;
                this.split = H.dom;
                if (this.cm.isResizable(this.cellIndex) && !this.cm.isFixed(this.cellIndex)) {
                    Ext.grid.GridView.SplitDragZone.superclass.handleMouseDown.apply(this, arguments)
                }
            } else {
                if (this.view.columnDrag) {
                    this.view.columnDrag.callHandleMouseDown(A)
                }
            }
        }
    }, endDrag: function (D) {
        this.marker.hide();
        var A = this.view;
        var B = Math.max(this.minX, D.getPageX());
        var C = B - this.startPos;
        A.onColumnSplitterMoved(this.cellIndex, this.cm.getColumnWidth(this.cellIndex) + C);
        setTimeout(function () {
            A.headersDisabled = false
        }, 50)
    }, autoOffset: function () {
        this.setDelta(0, 0)
    }
});