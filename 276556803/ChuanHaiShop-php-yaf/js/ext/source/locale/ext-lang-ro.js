﻿/*
 * Ext JS Library 2.1
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * Translation by Lucian Lature 04-24-2007
 * Romanian Translations
 */

Ext.UpdateManager.defaults.indicatorText = '<div class="loading-indicator">^Incarcare...</div>';

if(Ext.View){
   Ext.View.prototype.emptyText = "";
}

if(Ext.grid.Grid){
   Ext.grid.Grid.prototype.ddText = "{0} r^and(uri) selectate";
}

if(Ext.TabPanelItem){
   Ext.TabPanelItem.prototype.closeText = "^Inchide acest tab";
}

if(Ext.form.Field){
   Ext.form.Field.prototype.invalidText = "Valoarea acestui c^amp este invalida";
}

if(Ext.LoadMask){
    Ext.LoadMask.prototype.msg = "^Incarcare...";
}

Date.monthNames = [
   "Ianuarie",
   "Februarie",
   "Martie",
   "Aprilie",
   "Mai",
   "Iunie",
   "Iulie",
   "August",
   "Septembrie",
   "Octombrie",
   "Noiembrie",
   "Decembrie"
];

Date.dayNames = [
   "Duminica",
   "Luni",
   "Marti",
   "Miercuri",
   "Joi",
   "Vineri",
   "S^ambata"
];

if(Ext.MessageBox){
   Ext.MessageBox.buttonText = {
      ok     : "OK",
      cancel : "Renunta",
      yes    : "Da",
      no     : "Nu"
   };
}

if(Ext.util.Format){
   Ext.util.Format.date = function(v, format){
      if(!v) return "";
      if(!(v instanceof Date)) v = new Date(Date.parse(v));
      return v.dateFormat(format || "d-m-Y");
   };
}

if(Ext.DatePicker){
   Ext.apply(Ext.DatePicker.prototype, {
      todayText         : "Astazi",
      minText           : "Aceasta zi este ^inaintea datei de ^inceput",
      maxText           : "Aceasta zi este dupa ultimul termen",
      disabledDaysText  : "",
      disabledDatesText : "",
      monthNames	: Date.monthNames,
      dayNames		: Date.dayNames,
      nextText          : 'Urmatoarea luna (Control+Right)',
      prevText          : 'Luna anterioara (Control+Left)',
      monthYearText     : 'Alege o luna (Control+Up/Down pentru a parcurge anii)',
      todayTip          : "{0} (Spacebar)",
      format            : "d-m-y"
   });
}

if(Ext.PagingToolbar){
   Ext.apply(Ext.PagingToolbar.prototype, {
      beforePageText : "Pagina",
      afterPageText  : "din {0}",
      firstText      : "Prima pagina",
      prevText       : "Pagina precedenta",
      nextText       : "Urmatoarea pagina",
      lastText       : "Ultima pagina",
      refreshText    : "Re^improspatare",
      displayMsg     : "Afiseaza {0} - {1} din {2}",
      emptyMsg       : 'Nu sunt date de afisat'
   });
}

if(Ext.form.TextField){
   Ext.apply(Ext.form.TextField.prototype, {
      minLengthText : "Lungimea minima pentru acest c^amp este de {0}",
      maxLengthText : "Lungimea maxima pentru acest c^amp este {0}",
      blankText     : "Acest c^amp este obligatoriu",
      regexText     : "",
      emptyText     : null
   });
}

if(Ext.form.NumberField){
   Ext.apply(Ext.form.NumberField.prototype, {
      minText : "Valoarea minima permisa a acestui c^amp este {0}",
      maxText : "Valaorea maxima permisa a acestui c^amp este {0}",
      nanText : "{0} nu este un numar valid"
   });
}

if(Ext.form.DateField){
   Ext.apply(Ext.form.DateField.prototype, {
      disabledDaysText  : "Inactiv",
      disabledDatesText : "Inactiv",
      minText           : "Data acestui c^amp trebuie sa fie dupa {0}",
      maxText           : "Data acestui c^amp trebuie sa fie ^inainte de {0}",
      invalidText       : "{0} nu este o data valida - trebuie sa fie ^in formatul {1}",
      format            : "d-m-y"
   });
}

if(Ext.form.ComboBox){
   Ext.apply(Ext.form.ComboBox.prototype, {
      loadingText       : "^Incarcare...",
      valueNotFoundText : undefined
   });
}

if(Ext.form.VTypes){
   Ext.apply(Ext.form.VTypes, {
      emailText    : 'Acest c^amp trebuie sa contina o adresa de e-mail ^in formatul "user@domain.com"',
      urlText      : 'Acest c^amp trebuie sa contina o adresa URL ^in formatul "http:/'+'/www.domain.com"',
      alphaText    : 'Acest c^amp trebuie sa contina doar litere si _',
      alphanumText : 'Acest c^amp trebuie sa contina doar litere, cifre si _'
   });
}

if(Ext.grid.GridView){
   Ext.apply(Ext.grid.GridView.prototype, {
      sortAscText  : "Sortare ascendenta",
      sortDescText : "Sortare descendenta",
      lockText     : "Blocheaza coloana",
      unlockText   : "Deblocheaza coloana",
      columnsText  : "Coloane"
   });
}

if(Ext.grid.PropertyColumnModel){
   Ext.apply(Ext.grid.PropertyColumnModel.prototype, {
      nameText   : "Nume",
      valueText  : "Valoare",
      dateFormat : "m/j/Y"
   });
}

if(Ext.layout.BorderLayout.SplitRegion){
   Ext.apply(Ext.layout.BorderLayout.SplitRegion.prototype, {
      splitTip            : "Trage pentru redimensionare.",
      collapsibleSplitTip : "Trage pentru redimensionare. Dublu-click pentru ascundere."
   });
}