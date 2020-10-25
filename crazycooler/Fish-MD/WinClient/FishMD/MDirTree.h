/************************************************************************/
/* 
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:������������Ŀ¼�ؼ�
*/
/************************************************************************/

#pragma once
#include "MCommon.h"
#include <QWidget>
#include "ui_MDirTree.h"
#include <QTreeView>
#include <QMenu>
#include <QStandardItemModel>
#include <QMouseEvent>
#include "MDirTreeModel.h"

class MResponseData;
class MDocument;

class MDirTree : public QWidget
{
	Q_OBJECT

public:
	MDirTree(QWidget *parent,MDocument *doc);
	~MDirTree();
	/************************************************************************/
	/* 
	����dir���ݵ��ؼ���
	@param [QString] strData Ϊjson��ʽ������
	 ���磺
		[
		 {"id":"KBYFWikA","name":"md-name1","type":2},
		 {"name":"dir-name1","type":1,"children":[{"id":"Oy3gWSkA","name":"md-name2","type":2}]}
		]
	 type:1 ��ʾΪĿ¼��2 ��ʾΪ�ĵ�
	@return �Ƿ����óɹ�
	*/
	/************************************************************************/
	bool setData(const QString& strData);

	/************************************************************************/
	/* 
	����Ŀ¼ת��Ϊjson��Ҳ����setData���������
	*/
	/************************************************************************/
	QString dirTreeToJson();

	/************************************************************************/
	/* 
	�����޸ĵ�dir���ݣ�Ŀǰֻ�ڳ����˳�ʱ���ã�
	*/
	/************************************************************************/
	void save();

private slots:
	/************************************************************************/
	/* 
	��ʾ�˵�������doc��folder���ֲ�ͬ��ʽ������ʾ
	��Ӧ��signal QTreeView::customContextMenuRequested
	*/
	/************************************************************************/
	void treeViewMenu(const QPoint& pos);


	/************************************************************************/
	/*
	����Ϊ�˵����󶨵�һЩ��������
	����doc
	����folder
	������
	ɾ��
	���doc
	�༭doc
	*/
	/************************************************************************/
	void newDoc();
	void newDir();
	void rename();
	void del();

	void viewDoc();
	void editDoc();

	/************************************************************************/
	/* 
	˫���ؼ��нڵ�ʱ����
	*/
	/************************************************************************/
	void treeDoubleClicked(const QModelIndex &index);

	/************************************************************************/
	/* 
	folder����doc���϶���ɺ󴥷���������ǵ�ǰ��Ŀ¼�����Ƿ��иı�
	*/
	/************************************************************************/
	void itemDragMoved(const QMimeData *data, Qt::DropAction action, int row, int column, const QModelIndex &parent);


protected:
	/************************************************************************/
	/* 
	�������οؼ��е�Menu������doc��folder����
	*/
	/************************************************************************/
	void createTreeMenu();

	/************************************************************************/
	/* 
	�����ĵ�ID
	*/
	/************************************************************************/
	QString makeDirId();

	/************************************************************************/
	/* 
	���folder�µ�����doc��id
	*/
	/************************************************************************/
	void getChildDocIds(QModelIndex parent, std::vector<std::string> &data);

	/************************************************************************/
	/* 
	��json��item�õݹ�ķ�ʽ��ӵ�QTreeView��
	setData�б�����
	*/
	/************************************************************************/
	void addNode(QJsonArray &arr, QStandardItem *parent);

	/************************************************************************/
	/* 
	��QTreeView��item�õݹ�ķ�ʽת��Ϊjson
	dirTreeToJson�б�����
	*/
	/************************************************************************/
	void childToJson(QJsonArray &json, QModelIndex &index);

protected:
	/************************************************************************/
	/* 
	��ʱ������������ʱ����
	*/
	/************************************************************************/
	virtual void timerEvent(QTimerEvent *event);

	

private:
	Ui::QtDirTree ui;

	//���οؼ�
	MDirTreeModel *m_model;
	QTreeView *m_docsTree;

	//doc��folder��menu
	QMenu *m_floderMenu;
	QMenu *m_docMenu;

	//��ѡ�е�item
	QModelIndex m_menuSelItem;

	//��ǰ������ʾ��doc
	MDocument *m_doc;
	
	//ʱ��ID
	int m_nTimerId;

	//��־λ��������¼Ŀ¼�����Ƿ��޸�
	bool m_changeFlag;
};
