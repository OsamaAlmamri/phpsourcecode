/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:ֻ����һ��Fish-MD���̣�����Ѿ���Fish-MD���̣������ʾ�Ѵ򿪵�Fish-MD����QLocalServer�ķ�ʽ��ʵ��
*/
/************************************************************************/
#include "MSingleQMD.h"
#include <QWidget>

MSingleQMD::MSingleQMD(QObject *parent)
	: QObject(parent)
{
	m_server = NULL;
	m_curMainWnd = NULL;
}

MSingleQMD::~MSingleQMD()
{
	if (m_server)
	{
		delete m_server;
	}
}

bool MSingleQMD::init(const QString &serverName)
{
	if (isServerRun(serverName)) {
		return false;
	}

	m_server = new QLocalServer;
	QLocalServer::removeServer(serverName);
	m_server->listen(serverName);
	connect(m_server, &QLocalServer::newConnection, this, &MSingleQMD::newConnection);
	return true;
}

void MSingleQMD::newConnection()
{
	if (m_curMainWnd)
	{
		m_curMainWnd->raise();
		m_curMainWnd->activateWindow();
		m_curMainWnd->showNormal();
	}
}


bool MSingleQMD::isServerRun(const QString &serverName)
{
	QLocalSocket ls;
	ls.connectToServer(serverName);
	if(ls.waitForConnected(1000))
	{
		ls.disconnectFromServer();
		ls.close();
		return true;
	}

	return false;
}