/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:�������ǶԻ���
*/
/************************************************************************/

#include "MAboutDlg.h"
#include "MVersion.h"

MAboutDlg::MAboutDlg(QWidget *parent)
	: QDialog(parent)
{
	ui.setupUi(this);

	ui.label->setText(
		"<h3>��������</h3>\
	 	Fish-MD��һ��markdown���ĵ��Ʊʼǣ��ͳ����<br>\
 	markdown�ʼ���ȣ�������ͼƬ��ճ���������ƶ�<br>\
 	ͬ�����ܡ�Ŀǰֻ�ṩwindows�汾\
 	<h3>����</h3>crazycooler\
 	<h3>��ϵ��ʽ</h3>crazycooler@qq.com\
 	<h3>�汾</h3>" + QString(FISH_MD_VERSION) + "<br>"
	);
}

MAboutDlg::~MAboutDlg()
{
}
