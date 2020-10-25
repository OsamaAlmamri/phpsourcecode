/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:��QT��װ��Httpͨ����
*/
/************************************************************************/

#pragma once
#include "MCommon.h"
#include <QObject>
#include <QtNetwork/QNetworkAccessManager>
#include <QtNetwork/QNetworkRequest>
#include <QtNetwork/QNetworkReply>
#include <map>
#include <functional>
#include <string>
#include <vector>



struct MRequestData;

/************************************************************************/
/* 
������Ӧ������
*/
/************************************************************************/
struct MResponseData
{
	//��������Χ��ԭʼ�ֽ���
	QByteArray data;
	//����Ϊjson���
	QJsonObject root;
	//���������ص�״̬��
	int status;
	//����ʱ�����ݰ�
	MRequestData *request;
	//�������ݣ�������Ҫ��ӣ�һ����������request��response֮�䴫��������
	void *other;
};

/************************************************************************/
/* 
���������������
*/
/************************************************************************/
struct MRequestData
{
	//�����url��ַ
	QString url;
	//�����ԭʼ�ֽ���
	QByteArray data;
	//��������Ӧ���Ļص�����
	std::function<void(int, const MResponseData &)> callback;
	//�Ƿ���ҪȨ��
	bool auth;
	//�������ݣ�������Ҫ��ӣ�һ����������request��response֮�䴫��������
	void *other;
};



class MHttp : public QObject
{
	Q_OBJECT

public:
	MHttp(QObject *parent=nullptr);
	~MHttp();

	/************************************************************************/
	/* 
	������������post������
	@param url ��ַ
	@param data ����Ĳ���
	@param callback ��������Ӧ��ʱ��Ļص�����
	@param auth �Ƿ���ҪȨ��
	@param other ������һ����������request��response֮�䴫��������
	*/
	/************************************************************************/
	void post(const QString &url, 
		const QJsonObject &data, 
		std::function<void(int, const MResponseData &)> callback,
		bool auth = true,
		void *other = NULL
		);
	void post(const QString &url, 
		const QByteArray &data, 
		std::function<void(int, const MResponseData &)> callback,
		bool auth = true,
		void *other = NULL
		);

	/************************************************************************/
	/* 
	���������������ͼƬ����
	@param url ��ַ
	@param data ͼƬ����
	@param callback ��������Ӧ��ʱ��Ļص�����
	@param auth �Ƿ���ҪȨ��
	*/
	/************************************************************************/
	void postImage(const QString &url, 
		const QByteArray &data, 
		std::function<void(int, const MResponseData &)> callback,
		bool auth = true);

	/************************************************************************/
	/* 
	����token
	������ͨ��token�������û�Ȩ�ޣ����û���¼ʱ���ȡһ��token�������������������ʱ��
	��token���ϣ��������ͻ�֪����˭���������ݡ�token���й��ڻ��Ƶ�
	*/
	/************************************************************************/
	void setToken(const QString &token);

private:
	/************************************************************************/
	/* 
	����ͼ�������ϴ�ʱ�����ݰ�װ����Ϥǰ�˵�С���Ӧ��֪��multi partָ����ʲô��
	����ͨ����form���ݺ�image��binary���ݴ��һ�����ݰ�
	*/
	/************************************************************************/
	QByteArray makeMultipart(const QByteArray &boundary, const QByteArray &data, const QByteArray &md5);

	/************************************************************************/
	/* 
	ˢ��tokenʱ�Ļص�����
	����token�й��ڻ��ƣ���˵�token���ڵ�ʱ�����Ҫrefreshһ��
	*/
	/************************************************************************/
	void refreshTokenCallback(int status, const MResponseData &data);
private slots:
	/************************************************************************/
	/* 
	Http���������е�Ӧ�𶼻�����������
	*/
	/************************************************************************/
	void replayFinished(QNetworkReply *reply);

private:
	QNetworkAccessManager *m_manager;
	//�ۼ�����������request��ID
	int m_count;
	//�洢������Щû�б�Ӧ�������
	std::map<int, MRequestData> m_cbMap;
	//token
	QString m_token;
	//�Ƿ����ڵȴ�token refresh����Ϊ��refresh�����в�������Ҫauth�Ĳ���
	bool m_waitTokenUpdate;
	//������ڵȴ�token refresh��������request���������������У���refresh���������
	std::vector<MRequestData> m_waitList;
	//Http����������������
	QString m_hostUrl;
};
