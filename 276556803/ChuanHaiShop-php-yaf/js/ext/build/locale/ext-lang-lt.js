﻿/**
 * Lithuanian Translations (UTF-8)
 * By Vladas Saulis, October 18, 2007
 */

Ext.UpdateManager.defaults.indicatorText = '<div class="loading-indicator">Kraunasi...</div>';

if(Ext.View){
  Ext.View.prototype.emptyText = "";
}

if(Ext.grid.Grid){
  Ext.grid.Grid.prototype.ddText = "{0} pazymeta";
}

if(Ext.TabPanelItem){
  Ext.TabPanelItem.prototype.closeText = "Uzdaryti sia uzsklanda";
}

if(Ext.form.Field){
  Ext.form.Field.prototype.invalidText = "Sio lauko reiksme neteisinga";
}

if(Ext.LoadMask){
  Ext.LoadMask.prototype.msg = "Kraunasi...";
}

Date.monthNames = [
  "Saulis",
  "Vasaris",
  "Kovas",
  "Balandis",
  "Geguze",
  "Birzelis",
  "Liepa",
  "Rugpjūtis",
  "Rugsejis",
  "Spalis",
  "Lapkritis",
  "Gruodis"
];

Date.getShortMonthName = function(month) {
  return [
    "Sau",
    "Vas",
    "Kov",
    "Bal",
    "Geg",
    "Bir",
    "Lie",
    "Rgp",
    "Rgs",
    "Spa",
    "Lap",
    "Grd"
    ];
};

Date.monthNumbers = {
  Jan : 0,
  Feb : 1,
  Mar : 2,
  Apr : 3,
  May : 4,
  Jun : 5,
  Jul : 6,
  Aug : 7,
  Sep : 8,
  Oct : 9,
  Nov : 10,
  Dec : 11
};

Date.getMonthNumber = function(name) {
  return Date.monthNumbers[name.substring(0, 1).toUpperCase() + name.substring(1, 3).toLowerCase()];
};

Date.dayNames = [
  "Pirmadienis",
  "Antradienis",
  "Treciadienis",
  "Ketvirtadienis",
  "Penktadienis",
  "Sestadienis",
  "Sekmadienis"
];

Date.getShortDayName = function(day) {
  return Date.dayNames[day].substring(0, 3);
};

if(Ext.MessageBox){
  Ext.MessageBox.buttonText = {
    ok     : "Gerai",
    cancel : "Atsisakyti",
    yes    : "Taip",
    no     : "Ne"
  };
}

if(Ext.util.Format){
  Ext.util.Format.date = function(v, format){
    if(!v) return "";
    if(!(v instanceof Date)) v = new Date(Date.parse(v));
    return v.dateFormat(format || "Y-m-d");
  };
}

if(Ext.DatePicker){
  Ext.apply(Ext.DatePicker.prototype, {
    todayText         : "Siandien",
    minText           : "Si data yra mazesne uz leistina",
    maxText           : "Si data yra didesne uz leistina",
    disabledDaysText  : "",
    disabledDatesText : "",
    monthNames        : Date.monthNames,
    dayNames          : Date.dayNames,
    nextText          : 'Next Month (Control+Right)',
    prevText          : 'Previous Month (Control+Left)',
    monthYearText     : 'Choose a month (Control+Up/Down perejimui tarp metu)',
    todayTip          : "{0} (Spacebar)",
    format            : "y-m-d",
    okText            : "&#160;Gerai&#160;",
    cancelText        : "Atsisaktyi",
    startDay          : 1
  });
}

if(Ext.PagingToolbar){
  Ext.apply(Ext.PagingToolbar.prototype, {
    beforePageText : "Puslapis",
    afterPageText  : "is {0}",
    firstText      : "Pirmas puslapis",
    prevText       : "Ankstesnis pusl.",
    nextText       : "Kitas puslapis",
    lastText       : "Pakutinis pusl.",
    refreshText    : "Atnaujinti",
    displayMsg     : "Rodomi irasai {0} - {1} is {2}",
    emptyMsg       : 'Nera duomenu'
  });
}

if(Ext.form.TextField){
  Ext.apply(Ext.form.TextField.prototype, {
    minLengthText : "Minimalus sio lauko ilgis yra {0}",
    maxLengthText : "Maksimalus sio lauko ilgis yra {0}",
    blankText     : "Sis laukas yra reikalingas",
    regexText     : "",
    emptyText     : null
  });
}

if(Ext.form.NumberField){
  Ext.apply(Ext.form.NumberField.prototype, {
    minText : "Minimalus sio lauko ilgis yra {0}",
    maxText : "Maksimalus sio lauko ilgis yra {0}",
    nanText : "{0} yra neleistina reiksme"
  });
}

if(Ext.form.DateField){
  Ext.apply(Ext.form.DateField.prototype, {
    disabledDaysText  : "Neprieinama",
    disabledDatesText : "Neprieinama",
    minText           : "Siame lauke data turi būti didesne uz {0}",
    maxText           : "Siame lauke data turi būti mazesnee uz {0}",
    invalidText       : "{0} yra neteisinga data - ji turi būti ivesta formatu {1}",
    format            : "y-m-d",
    altFormats        : "y-m-d|y/m/d|Y-m-d|m/d|m-d|md|ymd|Ymd|d|Y-m-d"
  });
}

if(Ext.form.ComboBox){
  Ext.apply(Ext.form.ComboBox.prototype, {
    loadingText       : "Kraunasi...",
    valueNotFoundText : undefined
  });
}

if(Ext.form.VTypes){
  Ext.apply(Ext.form.VTypes, {
    emailText    : 'Siame lauke turi būti el.pasto adresas formatu "user@domain.com"',
    urlText      : 'Siame lauke turi būti nuoroda (URL) formatu "http:/'+'/www.domain.com"',
    alphaText    : 'Siame lauke gali būti tik raides ir zenklas "_"',
    alphanumText : 'Siame lauke gali būti tik raides, skaiciai ir zenklas "_"'
  });
}

if(Ext.form.HtmlEditor){
  Ext.apply(Ext.form.HtmlEditor.prototype, {
    createLinkText : 'Iveskite URL siai nuorodai:',
    buttonTips : {
      bold : {
        title: 'Bold (Ctrl+B)',
        text: 'Teksto paryskinimas.',
        cls: 'x-html-editor-tip'
      },
      italic : {
        title: 'Italic (Ctrl+I)',
        text: 'Kursyvinis tekstas.',
        cls: 'x-html-editor-tip'
      },
      underline : {
        title: 'Underline (Ctrl+U)',
        text: 'Teksto pabraukimas.',
        cls: 'x-html-editor-tip'
      },
      increasefontsize : {
        title: 'Padidinti srifta',
        text: 'Padidinti srifto dydi.',
        cls: 'x-html-editor-tip'
      },
      decreasefontsize : {
        title: 'Sumazinti srifta',
        text: 'Sumazinti srifto dydi.',
        cls: 'x-html-editor-tip'
      },
      backcolor : {
        title: 'Nuspalvinti teksto fona',
        text: 'Pakeisti teksto fono spalva.',
        cls: 'x-html-editor-tip'
      },
      forecolor : {
        title: 'Teksto spalva',
        text: 'Pakeisti pazymeto teksto spalva.',
        cls: 'x-html-editor-tip'
      },
      justifyleft : {
        title: 'Islyginti kairen',
        text: 'Islyginti teksta i kaire.',
        cls: 'x-html-editor-tip'
      },
      justifycenter : {
        title: 'Centruoti teksta',
        text: 'Centruoti tekta redaktoriaus lange.',
        cls: 'x-html-editor-tip'
      },
      justifyright : {
        title: 'Islyginti desinen',
        text: 'Islyginti teksta i desine.',
        cls: 'x-html-editor-tip'
      },
      insertunorderedlist : {
        title: 'Paprastas sarasas',
        text: 'Pradeti neorganizuota sarasa.',
        cls: 'x-html-editor-tip'
      },
      insertorderedlist : {
        title: 'Numeruotas sarasas',
        text: 'Pradeti numeruota sarasa.',
        cls: 'x-html-editor-tip'
      },
      createlink : {
        title: 'Nuoroda',
        text: 'Padaryti pazymeta teksta nuoroda.',
        cls: 'x-html-editor-tip'
      },
      sourceedit : {
        title: 'Iseities tekstas',
        text: 'Persijungti i iseities teksto koregavimo rezima.',
        cls: 'x-html-editor-tip'
      }
    }
  });
}

if(Ext.grid.GridView){
  Ext.apply(Ext.grid.GridView.prototype, {
    sortAscText  : "Rūsiuoti didejancia tvarka",
    sortDescText : "Rūsiuoti mazejancia tvarka",
    lockText     : "Uzfiksuoti stulpeli",
    unlockText   : "Atlaisvinti stulpeli",
    columnsText  : "Stulpeliai"
  });
}

if(Ext.grid.GroupingView){
  Ext.apply(Ext.grid.GroupingView.prototype, {
    emptyGroupText : '(Nera)',
    groupByText    : 'Grupuoti pagal si lauka',
    showGroupsText : 'Rodyti grupese'
  });
}

if(Ext.grid.PropertyColumnModel){
  Ext.apply(Ext.grid.PropertyColumnModel.prototype, {
    nameText   : "Pavadinimas",
    valueText  : "Reiksme",
    dateFormat : "Y-m-d"
  });
}

if(Ext.layout.BorderLayout.SplitRegion){
  Ext.apply(Ext.layout.BorderLayout.SplitRegion.prototype, {
    splitTip            : "Patraukite juostele.",
    collapsibleSplitTip : "Patraukite juostele arba Paspauskite dvigubai kad paslepti."
  });
}
