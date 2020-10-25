/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:�Ի������ڻ�ȡһ���ַ���
*/
/************************************************************************/
#pragma once
#include "MCommon.h"
#include <QDialog>
#include "ui_MGetTextDlg.h"

class MGetTextDlg : public QDialog
{
	Q_OBJECT

public:
	MGetTextDlg(QWidget *parent = Q_NULLPTR);
	~MGetTextDlg();

public:
	/************************************************************************/
	/* 
	������ʾ��Ϣ
	*/
	/************************************************************************/
	void setLabelText(const QString &str);

	/************************************************************************/
	/*
	����������е�����
	*/
	/************************************************************************/
	void setTextValue(const QString &str);

	/************************************************************************/
	/* 
	��ȡ������е�����
	*/
	/************************************************************************/
	QString getTextValue();

private slots:
	void onAcceptClick();
	void onCancelClick();

private:
	Ui::MGetTextDlg ui;
	QString m_textValue;
};
