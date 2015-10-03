/**
 * List compiled by mystix on the extjs.com forums.
 * Thank you Mystix!
 */

/**
 * Turkish translation by Hüseyin Tüfekçilerli
 * 04-11-2007, 09:52 AM
 * Updated by madrabaz, 13-11-2007
 */

Ext.UpdateManager.defaults.indicatorText = '<div class="loading-indicator">Yükleniyor...</div>';

if (Ext.View) {
    Ext.View.prototype.emptyText = "";
}

if (Ext.grid.Grid) {
    Ext.grid.Grid.prototype.ddText = "{0} satır seçildi";
}

if (Ext.TabPanelItem) {
    Ext.TabPanelItem.prototype.closeText = "Bu sekmeyi kapat";
}

if (Ext.form.Field) {
    Ext.form.Field.prototype.invalidText = "Bu alandaki değer geçersiz";
}

if (Ext.LoadMask) {
    Ext.LoadMask.prototype.msg = "Yükleniyor...";
}

Date.monthNames = [
    "Ocak",
    "Şubat",
    "Mart",
    "Nisan",
    "Mayıs",
    "Haziran",
    "Temmuz",
    "Ağustos",
    "Eylül",
    "Ekim",
    "Kasım",
    "Aralık"
];

Date.getShortMonthName = function (month) {
    return Date.monthNames[month].substring(0, 3);
};

Date.monthNumbers = {
    Jan: 0,
    Feb: 1,
    Mar: 2,
    Apr: 3,
    May: 4,
    Jun: 5,
    Jul: 6,
    Aug: 7,
    Sep: 8,
    Oct: 9,
    Nov: 10,
    Dec: 11
};

Date.getMonthNumber = function (name) {
    return Date.monthNumbers[name.substring(0, 1).toUpperCase() + name.substring(1, 3).toLowerCase()];
};

Date.dayNames = [
    "Pazar",
    "Pazartesi",
    "Salı",
    "Çarşamba",
    "Perşembe",
    "Cuma",
    "Cumartesi"
];

Date.getShortDayName = function (day) {
    return Date.dayNames[day].substring(0, 3);
};

if (Ext.MessageBox) {
    Ext.MessageBox.buttonText = {
        ok: "Tamam",
        cancel: "İptal",
        yes: "Evet",
        no: "Hayır"
    };
}

if (Ext.util.Format) {
    Ext.util.Format.date = function (v, format) {
        if (!v) return "";
        if (!(v instanceof Date)) v = new Date(Date.parse(v));
        return v.dateFormat(format || "d/m/Y");
    };
}

if (Ext.DatePicker) {
    Ext.apply(Ext.DatePicker.prototype, {
        todayText: "Bugün",
        minText: "Bu tarih minimum tarihten önce",
        maxText: "Bu tarih maximum tarihten önce",
        disabledDaysText: "",
        disabledDatesText: "",
        monthNames: Date.monthNames,
        dayNames: Date.dayNames,
        nextText: 'Sonraki ay (Ctrl+Sağ)',
        prevText: 'Önceki ay (Ctrl+Sol)',
        monthYearText: 'Bir ay seçin (Yılları değiştirmek için Ctrl+Yukarı/Aşağı)',
        todayTip: "{0} (Boşluk Çubuğu)",
        format: "d/m/y",
        okText: "&#160;İptal&#160;",
        cancelText: "Cancel",
        startDay: 1
    });
}

if (Ext.PagingToolbar) {
    Ext.apply(Ext.PagingToolbar.prototype, {
        beforePageText: "Sayfa",
        afterPageText: " / {0}",
        firstText: "İlk Sayfa",
        prevText: "Önceki Sayfa",
        nextText: "Sonraki Sayfa",
        lastText: "Son Sayfa",
        refreshText: "Yenile",
        displayMsg: "{2} satırdan {0} - {1} arası gösteriliyor",
        emptyMsg: 'Gösterilecek kayıt yok'
    });
}

if (Ext.form.TextField) {
    Ext.apply(Ext.form.TextField.prototype, {
        minLengthText: "Bu alan için minimum uzunluk {0}",
        maxLengthText: "Bu alan için maximum uzunluk {0}",
        blankText: "Bu alan zorunlu",
        regexText: "",
        emptyText: null
    });
}

if (Ext.form.NumberField) {
    Ext.apply(Ext.form.NumberField.prototype, {
        minText: "Bu alan için minimum değer {0}",
        maxText: "Bu alan için maximum değer {0}",
        nanText: "{0} geçerli bir sayı değil"
    });
}

if (Ext.form.DateField) {
    Ext.apply(Ext.form.DateField.prototype, {
        disabledDaysText: "Pasif",
        disabledDatesText: "Pasif",
        minText: "Bu alana {0} tarihinden sonraki bir tarih girilmeli",
        maxText: "Bu alana {0} tarihinden önceki bir tarih girilmeli",
        invalidText: "{0} geçerli bir tarih değil - {1} biçiminde olmalı",
        format: "m/d/y",
        altFormats: "m/d/Y|d-m-y|d-m-Y|m/d|d-m|dm|dmy|dmY|d|Y-m-d"
    });
}

if (Ext.form.ComboBox) {
    Ext.apply(Ext.form.ComboBox.prototype, {
        loadingText: "Yükleniyor...",
        valueNotFoundText: undefined
    });
}

if (Ext.form.VTypes) {
    Ext.apply(Ext.form.VTypes, {
        emailText: 'Bu alan bir e-mail adresi biçiminde olmalı "kullanici@alanadi.com"',
        urlText: 'Bu alan bir URL biçiminde olmalu "http:/' + '/www.alanadi.com"',
        alphaText: 'Bu alan sadece harf ve _ içermeli',
        alphanumText: 'Bu alan sadece harf, sayı ve _ içermeli'
    });
}

if (Ext.form.HtmlEditor) {
    Ext.apply(Ext.form.HtmlEditor.prototype, {
        createLinkText: 'Lütfen link için URL giriniz:',
        buttonTips: {
            bold: {
                title: 'Kalın (Ctrl+B)',
                text: 'Seçilen metni kalın yap.',
                cls: 'x-html-editor-tip'
            },
            italic: {
                title: 'Yatık (Ctrl+I)',
                text: 'Seçilen metni yatık yap.',
                cls: 'x-html-editor-tip'
            },
            underline: {
                title: 'Altçizgi (Ctrl+U)',
                text: 'Seçilen metnin altını çiz.',
                cls: 'x-html-editor-tip'
            },
            increasefontsize: {
                title: 'Metni büyüt',
                text: 'Yazi tipini büyüt.',
                cls: 'x-html-editor-tip'
            },
            decreasefontsize: {
                title: 'Metni küçült',
                text: 'Yazi tipini küçült.',
                cls: 'x-html-editor-tip'
            },
            backcolor: {
                title: 'Metin arkaplan rengi',
                text: 'Seçilen metnin arkaplan rengini değiştir.',
                cls: 'x-html-editor-tip'
            },
            forecolor: {
                title: 'Metin rengi',
                text: 'Seçilen metnin rengini değiştir.',
                cls: 'x-html-editor-tip'
            },
            justifyleft: {
                title: 'Metni sola yasla',
                text: 'Metni sola yasla',
                cls: 'x-html-editor-tip'
            },
            justifycenter: {
                title: 'Metni ortala',
                text: 'Metni ortala',
                cls: 'x-html-editor-tip'
            },
            justifyright: {
                title: 'Metni sağa yasla',
                text: 'Metni sağa yasla',
                cls: 'x-html-editor-tip'
            },
            insertunorderedlist: {
                title: 'Sırasız liste',
                text: 'Sırasız liste başlat.',
                cls: 'x-html-editor-tip'
            },
            insertorderedlist: {
                title: 'Sıralı liste',
                text: 'Sıralı liste başlat',
                cls: 'x-html-editor-tip'
            },
            createlink: {
                title: 'Bağlanti',
                text: 'Seçilen yazıya bağlantı ver.',
                cls: 'x-html-editor-tip'
            },
            sourceedit: {
                title: 'Kaynağı düzenle',
                text: 'Kaynak düzenle görünümüne geç.',
                cls: 'x-html-editor-tip'
            }
        }
    });
}

if (Ext.grid.GridView) {
    Ext.apply(Ext.grid.GridView.prototype, {
        sortAscText: "Artan Sıra",
        sortDescText: "Azalan Sıra",
        lockText: "Sütunu Kilitle",
        unlockText: "Sütunun Kilidini Kaldır",
        columnsText: "Sütunlar"
    });
}

if (Ext.grid.GroupingView) {
    Ext.apply(Ext.grid.GroupingView.prototype, {
        emptyGroupText: '(Yok)',
        groupByText: 'Bu Alana Göre Grupla',
        showGroupsText: 'Grup Şeklinde Göster'
    });
}

if (Ext.grid.PropertyColumnModel) {
    Ext.apply(Ext.grid.PropertyColumnModel.prototype, {
        nameText: "Ad",
        valueText: "Değer",
        dateFormat: "d/m/Y"
    });
}

if (Ext.layout.BorderLayout.SplitRegion) {
    Ext.apply(Ext.layout.BorderLayout.SplitRegion.prototype, {
        splitTip: "Boyutlandırmak için sürükleyin.",
        collapsibleSplitTip: "Boyutlandırmak için sürükleyin. Gizlemek için çift tıklayın."
    });
}
