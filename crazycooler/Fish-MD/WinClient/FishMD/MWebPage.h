/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:web page������
*/
/************************************************************************/

#pragma once

#include <QWebEnginePage>

class MWebPage : public QWebEnginePage
{
	Q_OBJECT

public:
	MWebPage(QObject *parent = nullptr);
	~MWebPage();
};
