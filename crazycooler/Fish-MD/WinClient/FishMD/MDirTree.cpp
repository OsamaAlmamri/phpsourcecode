/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:������������Ŀ¼�ؼ�
*/
/************************************************************************/
#include "MDirTree.h"
#include <QHBoxLayout>
#include <QStandardItem>
#include <QMenu>
#include <QInputDialog>
#include <MQtCommon.h>
#include "MStringTrans.h"
#include "MHttp.h"
#include <ctime>
#include "MDocument.h"
#include "utils/base64.h"
#include <QMimeData>
#include "MSqldb.h"



MDirTree::MDirTree(QWidget *parent, MDocument *doc)
	: QWidget(parent)
{
	ui.setupUi(this);

	m_doc = doc;
	m_changeFlag = false;

	QHBoxLayout *horizontalLayout = new QHBoxLayout(this);
	horizontalLayout->setMargin(0);
	m_docsTree = new QTreeView(this);
	horizontalLayout->addWidget(m_docsTree);
	m_model = new MDirTreeModel();

	m_docsTree->setModel(m_model);
	m_model->setHorizontalHeaderItem(0, new QStandardItem("markdown�ĵ�"));
	m_docsTree->setContextMenuPolicy(Qt::CustomContextMenu);
	m_docsTree->setEditTriggers(QAbstractItemView::NoEditTriggers);
	m_docsTree->setDragDropMode(QAbstractItemView::InternalMove);
	createTreeMenu();

	connect(m_docsTree, &QTreeView::customContextMenuRequested, this, &MDirTree::treeViewMenu);
	connect(m_docsTree, &QTreeView::doubleClicked, this, &MDirTree::treeDoubleClicked);

	connect(m_model,&MDirTreeModel::itemDragMoved,this,&MDirTree::itemDragMoved);

	m_nTimerId = startTimer(UPDATE_TIME_INTERVAL);

}

MDirTree::~MDirTree()
{
	if (m_nTimerId != 0)
	{
		killTimer(m_nTimerId);
	}	
}

//��json��item�õݹ�ķ�ʽ��ӵ�QTreeView��
void MDirTree::addNode(QJsonArray &arr, QStandardItem *parent)
{
	for (auto it = arr.begin(); it != arr.end(); ++it)
	{
		QJsonObject &node = it->toObject();
		QString name = node["name"].toString();
		int type = node["type"].toInt();

		QStandardItem *child = nullptr;
		if (type == ITEM_TYPE_FOLDER)
		{
			child = new QStandardItem(QIcon(":/FishMD/folder.png"), name);
			child->setData(ITEM_TYPE_FOLDER, ITEM_TYPE);
			parent->appendRow(child);
		}
		else if (type == ITEM_TYPE_DOC)
		{
			//doc��folder����id
			QString id = node["id"].toString();
			child = new QStandardItem(QIcon(":/FishMD/markdown.png"), name);
			child->setData(ITEM_TYPE_DOC, ITEM_TYPE);
			child->setData(id, ITEM_ID);
			parent->appendRow(child);
		}
		else
		{
			qCritical() << "[MDirTree::addNode] unknow type at node ( " << MQtCommon::JsonObjectToString(node) << " )";
			continue;
		}
			

		if (node["children"].isArray())
		{
			addNode(node["children"].toArray(), child);
		}
	}
}

//����Ŀ¼����
bool MDirTree::setData(const QString& strData)
{
	QJsonArray root;
	if (!MQtCommon::StringToJsonArray(strData, root, "[MDirTree::setData] dir json parse failed"))
		return false;

	QStandardItem *treeRoot = new QStandardItem(QIcon(":/FishMD/folder.png"), "�ҵ��ĵ�");
	treeRoot->setData(ITEM_TYPE_FOLDER, ITEM_TYPE);

	addNode(root,treeRoot);

	m_model->setItem(0, 0, treeRoot);

	m_docsTree->setExpanded(m_model->indexFromItem(treeRoot), true);

	return true;
}

//��������Ŀ¼�е��Ҽ��˵�
void MDirTree::createTreeMenu()
{
	m_floderMenu = new QMenu(this);

	QAction* ac = nullptr;
	//folder
	ac = new QAction("�½��ĵ�", this);
	m_floderMenu->addAction(ac);
	connect(ac, &QAction::triggered,this, &MDirTree::newDoc);

	ac = new QAction("�½��ļ���", this);
	m_floderMenu->addAction(ac);
	connect(ac, &QAction::triggered, this, &MDirTree::newDir);

	m_floderMenu->addSeparator();

	ac = new QAction("������", this);
	m_floderMenu->addAction(ac);
	connect(ac, &QAction::triggered, this, &MDirTree::rename);

	ac = new QAction("ɾ��", this);
	m_floderMenu->addAction(ac);
	connect(ac, &QAction::triggered, this, &MDirTree::del);

	//doc
	m_docMenu = new QMenu(this);

	ac = new QAction("�鿴", this);
	m_docMenu->addAction(ac);
	connect(ac, &QAction::triggered, this, &MDirTree::viewDoc);

	ac = new QAction("�༭", this);
	m_docMenu->addAction(ac);
	connect(ac, &QAction::triggered, this, &MDirTree::editDoc);

	m_docMenu->addSeparator();

	ac = new QAction("������", this);
	m_docMenu->addAction(ac);
	connect(ac, &QAction::triggered, this, &MDirTree::rename);

	ac = new QAction("ɾ��", this);
	m_docMenu->addAction(ac);
	connect(ac, &QAction::triggered, this, &MDirTree::del);

}

//��ʾ�˵�������doc��folder���ֲ�ͬ��ʽ������ʾ
void MDirTree::treeViewMenu(const QPoint& pos)
{
	QModelIndex index = m_docsTree->indexAt(pos);
	QVariant var = index.data();
	if (var.isValid())
	{
		int type = index.data(ITEM_TYPE).value<int>();
		if (type == ITEM_TYPE_DOC) {
			m_menuSelItem = index;
			m_docMenu->exec(QCursor::pos());
		}
		else if (type == ITEM_TYPE_FOLDER) {
			m_menuSelItem = index;
			m_floderMenu->exec(QCursor::pos());
		}
	}
}

//����Doc
void MDirTree::newDoc()
{
	bool isOk;
	QString strDocName = MQtCommon::getText(this, "�½��ĵ�",
		"�������ĵ���",
		QLineEdit::Normal,
		"",
		&isOk);
	if (!isOk)
		return;

	if (m_model->columnCount(m_menuSelItem) == 0) {
		if (!m_model->insertColumn(0, m_menuSelItem))
			return;
	}

	if (!m_model->insertRow(0, m_menuSelItem))
		return;

	QString docId = makeDirId();
	QModelIndex doc = m_model->index(0, 0, m_menuSelItem);
	m_model->setData(doc, strDocName, Qt::EditRole);
	m_model->setData(doc, ITEM_TYPE_DOC, ITEM_TYPE);
	m_model->setData(doc, docId,ITEM_ID);

	m_model->itemFromIndex(doc)->setIcon(QIcon(":/FishMD/markdown.png"));

	m_docsTree->selectionModel()->setCurrentIndex(m_model->index(0, 0, m_menuSelItem),
		QItemSelectionModel::ClearAndSelect);

	m_changeFlag = true;

	//���ĵ�������Ϊ�����
	QString content = "# " + strDocName;
	MDocdb *docDB = g_global->getDocDB();
	docDB->createDocLocal(docId.toUtf8().data(), content.toUtf8().data());

	m_doc->loadNewDoc(docId, "# " + strDocName);
}

//����Ŀ¼
void MDirTree::newDir()
{
	bool isOk;
	QString text = MQtCommon::getText(this, "�½�Ŀ¼",
		"������Ŀ¼��",
		QLineEdit::Normal,
		"",
		&isOk);
	if (!isOk)
		return;

	if (m_model->columnCount(m_menuSelItem) == 0) {
		if (!m_model->insertColumn(0, m_menuSelItem))
			return;
	}

	if (!m_model->insertRow(0, m_menuSelItem))
		return;
	QModelIndex dir = m_model->index(0, 0, m_menuSelItem);
	m_model->setData(dir, text, Qt::EditRole);
	m_model->setData(dir,ITEM_TYPE_FOLDER, ITEM_TYPE);
	m_model->itemFromIndex(dir)->setIcon(QIcon(":/FishMD/folder.png"));

	m_docsTree->selectionModel()->setCurrentIndex(m_model->index(0, 0, m_menuSelItem),
		QItemSelectionModel::ClearAndSelect);

	m_changeFlag = true;
}

//������
void MDirTree::rename()
{
	QString name = m_menuSelItem.data(Qt::EditRole).value<QString>();

	bool isOk;
	QString text = MQtCommon::getText(this, "������",
		"�������µ���",
		QLineEdit::Normal,
		name,
		&isOk);
	if (!isOk)
		return;

	m_model->setData(m_menuSelItem, text, Qt::EditRole);
	m_changeFlag = true;
}

//ɾ��
void MDirTree::del()
{
	int type = m_menuSelItem.data(ITEM_TYPE).value<int>();
	if (type == ITEM_TYPE_DOC)
	{
		if (MQtCommon::question(this, "ɾ��", "�Ƿ�ȷ��ɾ�����ĵ���") == QMessageBox::Yes)
		{
			QString id = m_menuSelItem.data(ITEM_ID).value<QString>();

			m_model->removeRow(m_menuSelItem.row(), m_menuSelItem.parent());
			m_changeFlag = true;


			std::vector<std::string> docIds;
			docIds.push_back(id.toUtf8().data());

			MDocdb *docDB = g_global->getDocDB();
			docDB->delDocsLocal(docIds);
		}
	}
	else if(type == ITEM_TYPE_FOLDER)
	{
		if(MQtCommon::question(this, "ɾ��","��Ŀ¼�µ��ĵ�Ҳ�ᱻɾ�����Ƿ�ȷ��ɾ����" )==QMessageBox::Yes)
		{
			std::vector<std::string> docIds;
			getChildDocIds(m_menuSelItem, docIds);

			MDocdb *docDB = g_global->getDocDB();
			docDB->delDocsLocal(docIds);
		}
	}
}

//�Ҽ��˵���ʾdocʱ������
void MDirTree::viewDoc()
{
	int type = m_menuSelItem.data(ITEM_TYPE).value<int>();
	if (type != ITEM_TYPE_DOC)
		return;

	QString id = m_menuSelItem.data(ITEM_ID).value<QString>();

	m_doc->loadDoc(id);
}

//�Ҽ��˵��༭docʱ������
void MDirTree::editDoc()
{
	int type = m_menuSelItem.data(ITEM_TYPE).value<int>();
	if (type != ITEM_TYPE_DOC)
		return;

	QString id = m_menuSelItem.data(ITEM_ID).value<QString>();

	m_doc->loadDoc(id,true);
}

//����ļ����µ������ĵ�id
void MDirTree::getChildDocIds(QModelIndex parent, std::vector<std::string> &data)
{
	int count = m_model->rowCount(parent);
	for (int i = 0; i < count; i++)
	{
		QModelIndex child = m_model->index(i, 0, parent);
		int type = child.data(ITEM_TYPE).value<int>();
		if (type == ITEM_TYPE_DOC)
		{
			QString id = child.data(ITEM_ID).value<QString>();
			//data.append(qstr2str(id));
			data.push_back(id.toUtf8().data());
		}
		if (m_model->rowCount(child) > 0)
		{
			getChildDocIds(child,data);
		}
	}
}



QString MDirTree::makeDirId()
{
	int t[2] = { 0 };
	t[0] = time(0);
	t[1] = rand();
	return base64_encode((unsigned char *)&t, 6).c_str();
}

void MDirTree::childToJson(QJsonArray &json, QModelIndex &parent)
{
	int count = m_model->rowCount(parent);
	for (int i = 0; i < count; i++)
	{
		QModelIndex child = m_model->index(i,0,parent);
		QString name = child.data(Qt::EditRole).value<QString>();
		int type = child.data(ITEM_TYPE).value<int>();
		QJsonObject node;
		node["name"] = name;
		node["type"] = type;
		if (type == ITEM_TYPE_DOC)
		{
			node["id"] = child.data(ITEM_ID).value<QString>();
		}
		if (m_model->rowCount(child) > 0)
		{
			 QJsonArray arr;
			childToJson(arr, child);
			node["children"] = arr;
		}
		json.append(node);
	}
}

//����Ŀ¼ת��Ϊjson
QString MDirTree::dirTreeToJson()
{
	QModelIndex root = m_model->index(0, 0);
	QString name = root.data(Qt::EditRole).value<QString>();
	int type = root.data(ITEM_TYPE).value<int>();

	QJsonArray json;
	childToJson(json,root);

	QJsonDocument d;
	d.setArray(json);
	return d.toJson(QJsonDocument::Compact);
}

void MDirTree::timerEvent(QTimerEvent *event)
{
	if (m_nTimerId == event->timerId())
	{
		if (m_changeFlag)
		{
			MDirDB *dirDB = g_global->getDirDB();
			std::string userName = g_global->getUserName().toUtf8().data();
			dirDB->changeDirLocal(userName, dirTreeToJson().toUtf8().data());
			m_changeFlag = false;
		}
	}
}

//�����˳�ʱ���ã������޸ĵ�dir����
void MDirTree::save()
{
	if (m_changeFlag)
	{
		MDirDB *dirDB = g_global->getDirDB();
		std::string userName = g_global->getUserName().toUtf8().data();
		dirDB->changeDirLocal(userName, dirTreeToJson().toUtf8().data());
		m_changeFlag = false;
	}
}

//˫��ʱ����ʾdoc
void MDirTree::treeDoubleClicked(const QModelIndex &index)
{
	int type = index.data(ITEM_TYPE).value<int>();
	if (type != ITEM_TYPE_DOC)
		return;

	QString id = index.data(ITEM_ID).value<QString>();

	m_doc->loadDoc(id);
}

//������Ŀ¼����ק���������޸ı��
void MDirTree::itemDragMoved(const QMimeData *data, Qt::DropAction action, int row, int column, const QModelIndex &parent)
{
	m_changeFlag = true;
}
