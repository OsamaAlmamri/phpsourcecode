/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:���ں�Markdown��Ӧweb�Ľ������ܣ������c++��javascript�������Խ���������
*/
/************************************************************************/
#pragma once
#include "MCommon.h"
#include <QObject>

class MResponseData;

class MDocument : public QObject
{
	Q_OBJECT
	//����initData��javascript
	Q_PROPERTY(QString initData MEMBER m_initData NOTIFY initDataChanged)

public:
	MDocument(QObject *parent=nullptr);
	~MDocument();

public:
	/************************************************************************/
	/* 
	��õ�ǰ��ʾ��markdown�ĵ�������
	@param mback ��ʾ������ݺ�Ĳ�������
	*/
	/************************************************************************/
	void getValue(const QString &mback = "");

	/************************************************************************/
	/* 
	���õ�ǰmarkdown�ĵ�������
	*setValue����Ȼ���Խ�m_changeFlag����Ϊfalse
	*/
	/************************************************************************/
	void setValue(const QString &value);

	/************************************************************************/
	/* 
	һЩ����������������ʱû���õ�
	*/
	/************************************************************************/
	void undo();
	void redo();
	void preview();
	void showToolbar(bool isShow);

	/************************************************************************/
	/* 
	�л�doc����ʾģʽ��EDITOR_MODE_EDIT : EDITOR_MODE_PREVIEW
	*/
	/************************************************************************/
	void changeMode(int mode);

	/************************************************************************/
	/* 
	web��ʼ�����ݵ����ã��û����ݸ�javascript
	*/
	/************************************************************************/
	void setInitData(const QString &initData) { m_initData = initData; }

	/************************************************************************/
	/* 
	ճ��ͼƬ
	*/
	/************************************************************************/
	void pasteImage();

	/************************************************************************/
	/* 
	��õ�ǰ�ĵ�����ʾģʽ
	*/
	/************************************************************************/
	int getEditorMode() { return m_editorMode; }

	/************************************************************************/
	/* 
	�����ĵ�
	*/
	/************************************************************************/
	void save();

signals:
	/************************************************************************/
	/* 
	���ڸ�markdownҳ�淢��Action��������
	@param action ��������
	@param value  ��Ӧ������Ҫ������
	@param mback  ������ɺ󣬺�����������ִ�е�����
	*/
	/************************************************************************/
	void doActionEmit(const QString &action,const QString &value = "",const QString &mback = "");

	/************************************************************************/
	/* 
	�ĵ���ɱ������ź�
	*/
	/************************************************************************/
	void afterSave();

	/************************************************************************/
	/* 
	����javascript�˶�initData�޸ĺ���ź�
	*/
	/************************************************************************/
	void initDataChanged(QString initData);
public slots:
	/************************************************************************/
	/* 
	����javascript�˷��ͻ������ź�
	@param action : ��������
	@param value:�����ʽ���ݲ������;�����һ����json
	@param mback:doActionEmit���͵�ԭ������
	*/
	/************************************************************************/
	void onEventNotify(const QString &action,const QString &value,const QString &mback="");
	

public:
	/************************************************************************/
	/* 
	�����ĵ�
	@param id �ĵ�ID
	@param isEditMode �Ƿ�༭ģʽ����
	*/
	/************************************************************************/
	void loadDoc(const QString& id, bool isEditMode=false);

	/************************************************************************/
	/* 
	�������ĵ�
	@param id �ĵ�ID
	@param content �ĵ�����
	*/
	/************************************************************************/
	void loadNewDoc(const QString& id,const QString &content);


	/************************************************************************/
	/* 
	ͼƬ���͸����������Ӧ��
	*/
	/************************************************************************/
	void pasteImageCallback(int status, const MResponseData &data);

protected:
	/************************************************************************/
	/* 
	��ʱ����������ʱ��doc����
	*/
	/************************************************************************/
	virtual void timerEvent(QTimerEvent *event);

private:
	void __loadDoc(const QString& id, bool isEditMode = false);
	void __loadNewDoc(const QString &id, const QString &content);
	void __updateDocToLocal(const QString &id, const QString &content);

private:
	/************************************************************************/
	/* 
	����javascript���¼�"getvalue"
	onEventNotify ������Ϣ�ַ�
	*/
	/************************************************************************/
	void onGetValue(const QString &value, const QString &mback);

	/************************************************************************/
	/* 
	����javascript���¼�"changeMode"
	onEventNotify ������Ϣ�ַ�
	*/
	/************************************************************************/
	void onChangeMode(const QString &mode);

private:
	//���ڳ�ʼ����������Ϣ
	QString m_initData;

	//��ʱ��ID
	int m_nTimerId;

	//��ǰ�ĵ���ID
	QString m_curDocId;
	//��ǰ�ĵ���ԭʼ���ݣ�Ҳ�������һ���޸ı������ݿ������
	QString m_srcDocData;

	//markdown��ǰ��ģʽ
	int m_editorMode;
	//markdown��Ҫ��ģʽ
	int m_wantEditorMode;
};
