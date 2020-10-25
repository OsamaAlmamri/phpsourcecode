/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:��QT��ص�һЩ���ù���
*/
/************************************************************************/

#pragma once
#include "MCommon.h"
#include <QInputDialog>
#include <QMessageBox>

class MQtCommon
{
public:
	MQtCommon();
	~MQtCommon();

public:
	/************************************************************************/
	/* 
	��ȡ�ַ����Ի������ѷ���
	*/
	/************************************************************************/
	static QString getText(QWidget *parent, const QString &title, const QString &label,
		QLineEdit::EchoMode echo = QLineEdit::Normal,
		const QString &text = QString(), bool *ok = Q_NULLPTR,
		Qt::WindowFlags flags = Qt::WindowFlags(),
		Qt::InputMethodHints inputMethodHints = Qt::ImhNone);

	/************************************************************************/
	/*
	confirm�Ի���
	*/
	/************************************************************************/
	static QMessageBox::StandardButton question(QWidget *parent, 
		const QString &title, const QString &content);

	/************************************************************************/
	/*
	���ַ���תjson�����json����ת�ַ���
	*/
	/************************************************************************/
	static bool StringToJsonArray(const QString &str, QJsonArray &arr,const QString &errMsg);
	static bool StringToJsonObject(const QString &str, QJsonObject &obj, const QString &errMsg);

	static QString JsonObjectToString(const QJsonObject &obj);
	
};

