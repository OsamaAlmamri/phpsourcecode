/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:webview�����������������Ҽ��˵�
*/
/************************************************************************/
#pragma once

#include <QWebEngineView>

class MWebEngineView : public QWebEngineView
{
	Q_OBJECT

public:
	MWebEngineView(QWidget *parent);
	~MWebEngineView();

	void contextMenuEvent(QContextMenuEvent *event);

	QMenu *m_menu;
};
