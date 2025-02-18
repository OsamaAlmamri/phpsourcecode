<?php
// -----------------------------------------------------------------------------
// -- Reportico -----------------------------------------------------------------
// -----------------------------------------------------------------------------
// Module : config.php
//
// General User Configuration Settings for Reportico Operation
// -----------------------------------------------------------------------------

// Password required to gain access to Administration Panel
// Set to Blank to allow reset from browser
define('SW_ADMIN_PASSWORD','luobadm');

// Default System Language
define('SW_LANGUAGE','en_gb');

define('SW_DEFAULT_PROJECT', 'reports');

// Project Title used at the top of menus
define('SW_PROJECT_TITLE','Reportico Administration Page');

// Identify whether to always run in into Debug Mode
define('SW_ALLOW_OUTPUT', false);
define('SW_ALLOW_DEBUG', true);

// Identify whether Show Criteria is default option
define('SW_DEFAULT_SHOWCRITERIA', false);

// Specification of Safe Mode. Turn on SAFE mode by specifying true.
// In SAFE mode, design of reports is allowed but Code and SQL Injection
// are prevented. This means that the designer prevents entry of potentially
// cdangerous ustom PHP source in the Custom Source Section or potentially
// dangerous SQL statements in Pre-Execute Criteria sections
define('SW_SAFE_DESIGN_MODE', true);

// If false prevents any designing of reports
define('SW_ALLOW_MAINTAIN', true);

// DB connection details for ADODB
define('SW_DB_DRIVER', 'none');
define('SW_DB_USER', '');
define('SW_DB_PASSWORD', '');
define('SW_DB_HOST', 'localhost');
define('SW_DB_DATABASE', '');
define('SW_DB_CONNECT_FROM_CONFIG', true);
define('SW_DB_DATEFORMAT', 'Y-m-d');
define('SW_PREP_DATEFORMAT', 'Y-m-d');
define('SW_DB_SERVER', '');
define('SW_DB_PROTOCOL', '');
define('SW_DB_ENCODING', 'None');

//HTML Output Encoding
define('SW_OUTPUT_ENCODING', 'UTF8');

// Identify temp area
define('SW_TMP_DIR', "tmp");

// SOAP Environment
define('SW_SOAP_NAMESPACE', 'reportico.org');
define('SW_SOAP_SERVICEBASEURL', 'http://www.reportico.co.uk/swsite/site/tutorials');

// Parameter Defaults
define('SW_DEFAULT_PageSize', 'A4');
define('SW_DEFAULT_PageOrientation', 'Landscape');
define('SW_DEFAULT_TopMargin', "1cm");
define('SW_DEFAULT_BottomMargin', "2cm");
define('SW_DEFAULT_LeftMargin', "1cm");
define('SW_DEFAULT_RightMargin', "1cm");
define('SW_DEFAULT_pdfFont', "Helvetica");
define('SW_DEFAULT_pdfFontSize', "10");

// FPDF parameters
define('FPDF_FONTPATH', './fpdf/font/');

// Graph Defaults
define('SW_DEFAULT_GraphWidth', 800);
define('SW_DEFAULT_GraphHeight', 400);
define('SW_DEFAULT_GraphWidthPDF', 500);
define('SW_DEFAULT_GraphHeightPDF', 250);
define('SW_DEFAULT_GraphColor', "yellow");
define('SW_DEFAULT_MarginTop', "20");
define('SW_DEFAULT_MarginBottom', "80");
define('SW_DEFAULT_MarginLeft', "50");
define('SW_DEFAULT_MarginRight', "50");
define('SW_DEFAULT_MarginColor', "red");
define('SW_DEFAULT_XTickLabelInterval', "4");
define('SW_DEFAULT_YTickLabelInterval', "2");
define('SW_DEFAULT_XTickInterval', "1");
define('SW_DEFAULT_YTickInterval', "1");
define('SW_DEFAULT_GridPosition', "back");
define('SW_DEFAULT_XGridDisplay', "none");
define('SW_DEFAULT_XGridColor', "gray");
define('SW_DEFAULT_YGridDisplay', "major");
define('SW_DEFAULT_YGridColor', "gray");
define('SW_DEFAULT_TitleFont', "Font2");
define('SW_DEFAULT_TitleFontStyle', "Normal");
define('SW_DEFAULT_TitleFontSize', "12");
define('SW_DEFAULT_TitleColor', "black");
define('SW_DEFAULT_XTitleFont', "Font1");
define('SW_DEFAULT_XTitleFontStyle', "Normal");
define('SW_DEFAULT_XTitleFontSize', "12");
define('SW_DEFAULT_XTitleColor', "black");
define('SW_DEFAULT_YTitleFont', "Font1");
define('SW_DEFAULT_YTitleFontStyle', "Normal");
define('SW_DEFAULT_YTitleFontSize', "12");
define('SW_DEFAULT_YTitleColor', "black");
define('SW_DEFAULT_XAxisFont', "Font1");
define('SW_DEFAULT_XAxisFontStyle', "Normal");
define('SW_DEFAULT_XAxisFontSize', "12");
define('SW_DEFAULT_XAxisFontColor', "black");
define('SW_DEFAULT_XAxisColor', "black");
define('SW_DEFAULT_YAxisFont', "Font1");
define('SW_DEFAULT_YAxisFontStyle', "Normal");
define('SW_DEFAULT_YAxisFontSize', "12");
define('SW_DEFAULT_YAxisFontColor', "black");
define('SW_DEFAULT_YAxisColor', "black");
?>
