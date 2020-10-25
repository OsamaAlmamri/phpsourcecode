/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:������������Ŀ¼�ؼ���model����������������Ҫʵ��item����ק����
*/
/************************************************************************/
#pragma once
#include "MCommon.h"
#include <QStandardItemModel>

class MDirTreeModel : public QStandardItemModel
{
	Q_OBJECT

public:
	MDirTreeModel(QObject *parent = nullptr);
	~MDirTreeModel();

protected:
	/************************************************************************/
	/* 
	��������item�Ƿ���Ա���ק
	*/
	/************************************************************************/
	virtual Qt::ItemFlags flags(const QModelIndex &index) const;

	/************************************************************************/
	/* 
	�����жϺͷ��ͣ���ק�ɹ�����ź�itemDragMoved
	*/
	/************************************************************************/
	virtual bool dropMimeData(const QMimeData *data, Qt::DropAction action, int row, int column, const QModelIndex &parent);

	/************************************************************************/
	/* 
	��ǰItem�Ƿ���Խ�����ק������item
	���磺doc��item�ǲ��ܽ���child��
	*/
	/************************************************************************/
	virtual bool canDropMimeData(const QMimeData *data, Qt::DropAction action, int row, int column, const QModelIndex &parent) const;

signals:
	void itemDragMoved(const QMimeData *data, Qt::DropAction action, int row, int column, const QModelIndex &parent);
};
