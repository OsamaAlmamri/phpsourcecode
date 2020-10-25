/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:������������Ŀ¼�ؼ���model����������������Ҫʵ��item����ק����
*/
/************************************************************************/
#include "MDirTreeModel.h"

MDirTreeModel::MDirTreeModel(QObject *parent)
	: QStandardItemModel(parent)
{
}

MDirTreeModel::~MDirTreeModel()
{
}


//��������item�Ƿ���Ա���ק
Qt::ItemFlags MDirTreeModel::flags(const QModelIndex &index) const
{
	if (!index.isValid())
		return 0;

	int type = data(index,ITEM_TYPE).value<int>();

	return Qt::ItemIsDragEnabled | Qt::ItemIsDropEnabled | QAbstractItemModel::flags(index);
}


bool MDirTreeModel::dropMimeData(const QMimeData *data, Qt::DropAction action, int row, int column, const QModelIndex &parent) 
{

	if (!canDropMimeData(data, action, row, column, parent))
		return false;

	if (action == Qt::IgnoreAction)
		return true;

	emit itemDragMoved(data,action,row,column,parent);

	return QStandardItemModel::dropMimeData(data, action, row, column, parent);
}


//��ǰItem�Ƿ���Խ�����ק������item
bool MDirTreeModel::canDropMimeData(const QMimeData *data, Qt::DropAction action, int row, int column, const QModelIndex &parent) const
{
	int type = parent.data(ITEM_TYPE).value<int>();
	if (type == ITEM_TYPE_DOC)
		return false;

	return QStandardItemModel::canDropMimeData(data, action, row, column, parent);
}