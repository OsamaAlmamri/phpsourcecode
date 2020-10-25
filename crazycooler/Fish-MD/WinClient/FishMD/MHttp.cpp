/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:��QT��װ��Httpͨ����
*/
/************************************************************************/
#include "MHttp.h"
#include <QTextCodec>
#include <QMessageBox>
#include <MStringTrans.h>
#include <ctime>
#include "utils/md5.h"
#include "utils/MIniFile.h"
#include "MSqldb.h"

#define HOST_URL_PATH QString("http://localhost/qmd/api")

using namespace std;

MHttp::MHttp(QObject *parent)
	: QObject(parent)
{
	m_count = 0;
	m_manager = new QNetworkAccessManager(this);
	m_waitTokenUpdate = false;

	connect(m_manager, &QNetworkAccessManager::finished, this, &MHttp::replayFinished);

	m_hostUrl = str2qstr(g_global->getIniFile()->get<std::string>("net.hostUrl"));
}

MHttp::~MHttp()
{

}

void MHttp::refreshTokenCallback(int status, const MResponseData &data)
{
	if(data.status == 0)
	{
		const QJsonObject &root = data.root;
		m_token = root["token"].toString();

		MKVdb *kvDB = g_global->getKVDB();
		kvDB->setT("token", m_token.toUtf8().data());

		m_waitTokenUpdate = false;
		for (int i = 0; i < m_waitList.size(); i++)
		{
			post(m_waitList[i].url, m_waitList[i].data, m_waitList[i].callback, m_waitList[i].auth);
		}
		m_waitList.clear();
	}
	else
	{
		qCritical() << "[MHttp::refreshTokenCallback] refresh token failed ( " << QString::fromUtf8(data.data) <<" )";
		QMessageBox::warning(NULL, "����", "tokenˢ��ʧ��", QMessageBox::Ok);
		for (int i = 0; i < m_waitList.size(); i++)
		{
			MRequestData &rd = m_waitList[i];
			MResponseData resData;
			resData.data = QByteArray();
			resData.other = rd.other;
			resData.request = &rd;
			resData.status = RESPONSE_AUTH_ERROR;
			rd.callback(QNetworkReply::AuthenticationRequiredError,resData);
		}
	}
}


void MHttp::replayFinished(QNetworkReply *reply)
{
	auto error = reply->error();
	QByteArray data = reply->readAll();

	QByteArray funcId = reply->request().rawHeader("Func-ID");
	int count = QString(funcId).toInt();
	auto findIt = m_cbMap.find(count);
	if (findIt != m_cbMap.end())
	{
		MRequestData &rd = findIt->second;
		if (rd.auth && error == QNetworkReply::AuthenticationRequiredError)
		{
			m_waitTokenUpdate = true;
			m_waitList.push_back(findIt->second);

			QJsonObject param;
			param["token"] = m_token;
			post("/refresh",
				param,
				std::bind(&MHttp::refreshTokenCallback,this,std::placeholders::_1, std::placeholders::_2),
				false);
		}
		else
		{
			QJsonParseError errJson;
			QJsonDocument d = QJsonDocument::fromJson(data, &errJson);

			MResponseData resData;
			resData.data = data;
			resData.other = rd.other;
			resData.request = &rd;
			

			if (errJson.error == QJsonParseError::NoError)
			{
				resData.root = d.object();
				resData.status = 0;
			}
			else
			{
				resData.status = RESPONSE_NOT_JSON;
			}

			if (!(resData.root["error"].isDouble() && resData.root["error"].toInt() == 0))
				resData.status = RESPONSE_ERROR_FLAG;

			rd.callback(error, resData);
		}

		m_cbMap.erase(findIt);
	}

	qDebug() << "<server> url:" << reply->url().toString() << "  data" << QString::fromUtf8(data);
	reply->deleteLater();
}


void MHttp::setToken(const QString &token)
{
	m_token = token;
}

QByteArray MHttp::makeMultipart(const QByteArray &boundary,const QByteArray &data,const QByteArray &md5)
{
	QByteArray dashdash = "--";
	QByteArray crlf = "\r\n";

	QByteArray buffer;
	buffer += dashdash + boundary + crlf;

	buffer += "Content-Disposition: form-data; name=\"md5\"" + crlf;
	buffer += crlf;
	buffer += md5 + crlf;
	buffer += dashdash + boundary + crlf;
	buffer += "Content-Disposition: form-data; name=\"file\"; filename=\"gene_logo.jpg\"" + crlf;
	buffer += "Content-Type: image/jpeg"+crlf;
	buffer += crlf;
	buffer += data + crlf;
	buffer += dashdash + boundary + crlf + dashdash;

	return buffer;
}

void MHttp::postImage(const QString &url, 
	const QByteArray &data, 
	std::function<void(int, const MResponseData &)> callback,
	bool auth)
{
	m_count++;

	MRequestData rd;
	rd.url = url;
	rd.data = data;
	rd.callback = callback;
	rd.auth = auth;

	if (m_waitTokenUpdate)
	{
		m_waitList.push_back(rd);
		return;
	}

	m_cbMap.insert(make_pair(m_count, rd));

	QString fullUrl = m_hostUrl + url;
	QNetworkRequest request(QUrl(fullUrl.toUtf8()));

	int t = time(0);
	MD5 md5Code((unsigned char*)data.data(), data.size());
	QByteArray md5 = md5Code.toString().c_str();
	QByteArray boundary = "----multipartformboundary" + QByteArray::number(t);
	QByteArray buffer = makeMultipart(boundary, data,md5);
	
	QByteArray postDataSize = QByteArray::number(buffer.size());
	QByteArray funcId = QByteArray::number(m_count);

	request.setRawHeader("Content-Type", "multipart/form-data; boundary="+boundary);
	request.setRawHeader("Content-Length", postDataSize);
	request.setRawHeader("Func-ID", funcId);

	if (auth)
	{
		QString authToken = "bearer " + m_token;
		request.setRawHeader("authorization", authToken.toUtf8());
	}
	m_manager->post(request, buffer);
}

void MHttp::post(const QString &url, 
	const QJsonObject &data, 
	std::function<void(int, const MResponseData &)> callback,
	bool auth,
	void *other)
{
	QJsonDocument d;
	d.setObject(data);
	QByteArray buffer = d.toJson(QJsonDocument::Compact);
	post(url, buffer, callback, auth,other);
}

void MHttp::post(const QString &url,
	const QByteArray &data, 
	std::function<void(int, const MResponseData &)> callback,
	bool auth,
	void *other)
{
	qDebug() << "<client> url:" << url << "  data" << QString::fromUtf8(data);
	m_count++;

	MRequestData rd;
	rd.url = url;
	rd.data = data;
	rd.callback = callback;
	rd.auth = auth;
	rd.other = other;

	if (m_waitTokenUpdate && url != "/refresh")
	{
		//�ȴ�ˢ��token��ʱ�򣬽�ԭ��������ѹ�뵽�����У�Ȼ��������token��ִ��
		m_waitList.push_back(rd);
		return;
	}

	m_cbMap.insert(make_pair(m_count, rd));

	QString fullUrl = m_hostUrl + url;
	QNetworkRequest request(QUrl(fullUrl.toUtf8()));

	QByteArray jsonString = data;
	QByteArray postDataSize = QByteArray::number(jsonString.size());
	QByteArray funcId = QByteArray::number(m_count);

	request.setRawHeader("Content-Type", "application/json");
	request.setRawHeader("Content-Length", postDataSize);
	request.setRawHeader("Func-ID", funcId);

	if (auth)
	{
		QString authToken = "bearer " + m_token;
		request.setRawHeader("authorization", authToken.toUtf8());
	}
	m_manager->post(request, jsonString);
}