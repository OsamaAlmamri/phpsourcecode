/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:ֻ����һ��Fish-MD���̣�����Ѿ���Fish-MD���̣������ʾ�Ѵ򿪵�Fish-MD����QLocalServer�ķ�ʽ��ʵ��
*/
/************************************************************************/
#pragma once

#include <QObject>
#include <QLocalServer>
#include <QLocalSocket>


class MSingleQMD : public QObject
{
	Q_OBJECT

public:
	MSingleQMD(QObject *parent = nullptr);
	~MSingleQMD();

	/************************************************************************/
	/* 
	��ʼ��QLocalServer������Ѿ���QLocalServer�����ˣ���᷵��false�����򷵻�true
	*/
	/************************************************************************/
	bool init(const QString &serverName);

	/************************************************************************/
	/* 
	���õ�ǰ��������
	*/
	/************************************************************************/
	void setCurMainWnd(QWidget *curMainWnd) { m_curMainWnd = curMainWnd; }

private slots:
	/************************************************************************/
	/*
	ʱ�亯��
	������µ�QLocalServer�ͻ��������ӵ�ʱ�򱻵��ã�������ʾ��������
	*/
	/************************************************************************/
	void newConnection();

private:
	/************************************************************************/
	/* 
	�ж�QLocalServer�Ƿ��Ѿ�����
	*/
	/************************************************************************/
	bool isServerRun(const QString &serverName);

private:
	QLocalServer *m_server;
	QWidget *m_curMainWnd;
};
