/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:������
*/
/************************************************************************/
#include "MMarkdown.h"
#include "MWebPage.h"
#include "MDocument.h"
#include <QWebChannel>
#include <QDir>
#include <QMessageBox>
#include <QFileDialog>
#include <QPushButton>
#include <QDebug>
#include <QCoreApplication>
#include <QSettings>
#include <shlobj.h>
#include <QProcess>
#include "MStringTrans.h"
#include <QWebEngineSettings>
#include <QSplitter>
#include "MDirTree.h"
#include "MHttp.h"
#include <QMenu>
#include "MSyncData.h"
#include "MSqldb.h"
#include "MAboutDlg.h"
#include <QVBoxLayout>
#include "MWebEngineView.h"

MMarkdown::MMarkdown(QWidget *parent)
	: QWidget(parent)
{
	m_closeFlag = false;
	m_nBorder = 5;
	m_optionMenu = NULL;
	m_exitCode = EXIT_CODE;

	//����ini�����ļ����ݵ�markdown�༭��
	loadInitData();

	ui.setupUi(this);

	
	initLayout();
	QString workPath = g_global->getWorkPath();
	QString appPath = QCoreApplication::applicationDirPath();
	m_webview->setUrl(QUrl(workPath + WEB_FILE_REL_PATH));

	//��������
	createTrayIcon();

	//����ͬ��
	m_syncData = new MSyncData;
	m_syncData->getDataFromServer();
	connect(m_syncData, &MSyncData::afterGetDataFromServer, this, &MMarkdown::onAfterGetDataFromServer);
	connect(m_syncData, &MSyncData::afterSetDataToServer, this, &MMarkdown::onAfterSetDataToServer);
	connect(m_syncData, &MSyncData::startSetDataToServer, this, &MMarkdown::onStartSetDataToServer);
	connect(m_syncData, &MSyncData::networkError, this, &MMarkdown::onNetworkError);

	connect(&m_doc, &MDocument::afterSave, this, &MMarkdown::onAfterSave);

	
}

MMarkdown::~MMarkdown()
{
	if (m_syncData)
	{
		delete m_syncData;
		m_syncData = NULL;
	}
}


void MMarkdown::initLayout()
{

	setWindowFlags(Qt::FramelessWindowHint);
	setWindowIcon(QIcon(":/FishMD/fish.icon"));

	QVBoxLayout *mainLayout = new QVBoxLayout(this);
	mainLayout->setObjectName("MainLayout");
	mainLayout->setContentsMargins(1, 1, 1, 1);
	mainLayout->setMargin(1);

	//����������
	m_spinImage = new MSpinLabel(this);
	m_spinImage->setGeometry(QRect(230, 8, 40, 40));
	m_spinImage->setSpinImage(":/FishMD/update.png", 40);
	m_spinImage->start(36*200);
	
	m_networkMsgLabel = new QLabel(this);
	m_networkMsgLabel->setObjectName("NetworkMsgLabel");
	m_networkMsgLabel->setGeometry(QRect(270,5,100,45));
	m_networkMsgLabel->setText("<img src = ':/FishMD/BadNetwork.png'/><span style='padding-botton:4px;font-size:10px;float:right;'>�����쳣</span>");
	m_networkMsgLabel->setVisible(false);


	//���ñ�����
	QHBoxLayout *titleLayout = new QHBoxLayout;
	//�õ���������ˮƽlayout��height
	QSpacerItem *vSpacer1 = new QSpacerItem(20, 50, QSizePolicy::Minimum, QSizePolicy::Fixed);

	menuButton = new QPushButton(this);
	minButton = new QPushButton(this);
	maxButton = new QPushButton(this);
	max2Button = new QPushButton(this);
	closeButton = new QPushButton(this);

	menuButton->setObjectName("OptionButton");
	menuButton->setFocusPolicy(Qt::NoFocus);
	menuButton->setFixedSize(27, 23);
	menuButton->setCursor(Qt::PointingHandCursor);

	minButton->setObjectName("MinimizeButton");
	minButton->setFocusPolicy(Qt::NoFocus);
	minButton->setFixedSize(27, 23);
	minButton->setCursor(Qt::PointingHandCursor);

	maxButton->setObjectName("MaximumButton");
	maxButton->setFocusPolicy(Qt::NoFocus);
	maxButton->setFixedSize(27, 23);
	maxButton->setCursor(Qt::PointingHandCursor);

	max2Button->setObjectName("Maximum2Button");
	max2Button->setFocusPolicy(Qt::NoFocus);
	max2Button->setFixedSize(27, 23);
	max2Button->setCursor(Qt::PointingHandCursor);

	max2Button->setVisible(false);

	closeButton->setObjectName("CloseButton");
	closeButton->setFocusPolicy(Qt::NoFocus);
	closeButton->setFixedSize(27, 23);
	closeButton->setCursor(Qt::PointingHandCursor);

	titleLayout->addItem(vSpacer1);
	titleLayout->addStretch();
	titleLayout->addWidget(menuButton);
	titleLayout->addWidget(minButton);
	titleLayout->addWidget(maxButton);
	titleLayout->addWidget(max2Button);
	titleLayout->addWidget(closeButton);
	titleLayout->addSpacing(5);

	titleLayout->setSpacing(0);
	titleLayout->setContentsMargins(0, 0, 0, 0);

	mainLayout->addLayout(titleLayout);

	//��������ͼ�����������÷ָ�ķ�ʽ�ֳɿ�����������������
	QSplitter *splitMain = new QSplitter(this);
	

	//Ŀ¼��
	m_dirTree = new MDirTree(splitMain, &m_doc);
	//markdown�༭����
	m_webview = new MWebEngineView(splitMain);

	splitMain->setStretchFactor(0, 3);
	splitMain->setStretchFactor(1, 4);

	MWebPage *page = new MWebPage(this);
	QWebChannel *channel = new QWebChannel(this);
	m_webview->setPage(page);
	//���ñ���ҳ����Կ���
	page->settings()->setAttribute(QWebEngineSettings::LocalContentCanAccessRemoteUrls, true);
	channel->registerObject("doc", &m_doc);
	page->setWebChannel(channel);

	mainLayout->addWidget(splitMain);

	setLayout(mainLayout);

	connect(menuButton, &QPushButton::clicked, this,&MMarkdown::onMenuButtonClick);
	connect(minButton, &QPushButton::clicked, this, &MMarkdown::onMinButtonClick);
	connect(maxButton, &QPushButton::clicked, this, &MMarkdown::onMaxButtonClick);
	connect(max2Button, &QPushButton::clicked, this, &MMarkdown::onMax2ButtonClick);
	connect(closeButton, &QPushButton::clicked, this, &MMarkdown::onCloseButtonClick);

}

//����ɱ��������Ļص�����
void MMarkdown::onAfterSave()
{
	if (m_closeFlag)
	{
		if (!m_syncData->setDataToServer())
			QApplication::exit();
	}
}

//�ӷ�������ȡ���ݺ�Ļص�����
void MMarkdown::onAfterGetDataFromServer()
{
	QString userName = g_global->getUserName();
	MDirDB *dirDB = g_global->getDirDB();

	MDirData dir;
	dirDB->getDir(userName.toUtf8().data(), dir);

	m_dirTree->setData(QString::fromUtf8(dir.cur_data.data()));
}

//�ͷ���������ͬ��֮�����
void MMarkdown::onAfterSetDataToServer()
{
	if (m_closeFlag)
	{
		QApplication::exit();
	}
}

//����ini�����ļ����ݵ�markdown�༭��
void MMarkdown::loadInitData()
{
	MIniFile *ini = g_global->getIniFile();
	m_doc.setInitData(str2qstr(ini->toJson()));
}

void MMarkdown::createTrayIcon()
{
	QSystemTrayIcon *systemTray = new QSystemTrayIcon(this);
	systemTray->setToolTip("Fish-MD 1.1");
	systemTray->setIcon(QIcon(":/FishMD/fish.icon"));

	QMenu *trayMenu = new QMenu(this);

	QAction *action = nullptr;
	action = new QAction("��ʾ������", this);
	connect(action, &QAction::triggered, this, &MMarkdown::onShowMainWnd);
	trayMenu->addAction(action);

	action = new QAction("�˳�", this);
	connect(action, &QAction::triggered, this, &MMarkdown::onCloseMainWnd);
	trayMenu->addAction(action);

	systemTray->setContextMenu(trayMenu);

	connect(systemTray, &QSystemTrayIcon::activated, this, &MMarkdown::onSystemTrayIconEvent);

	systemTray->show();
}

void MMarkdown::onActionAbout()
{
	MAboutDlg dlg(this);
	dlg.exec();
}

void MMarkdown::onShowMainWnd()
{
	showNormal();
}

void MMarkdown::onCloseMainWnd()
{
	//QApplication::exit();
	m_closeFlag = true;
	m_dirTree->save();
	m_doc.save();
}

void MMarkdown::onSignout()
{
	m_exitCode = SIGNOUT_CODE;
	onCloseMainWnd();
}

void MMarkdown::onSystemTrayIconEvent(QSystemTrayIcon::ActivationReason reason)
{
	switch (reason)
	{
	case QSystemTrayIcon::Unknown:
		break;
	case QSystemTrayIcon::Context:
		break;
	case QSystemTrayIcon::DoubleClick:
	{
		showMinimized();
		showNormal();
	}break;
	case QSystemTrayIcon::Trigger:
		break;
	case QSystemTrayIcon::MiddleClick:
		break;
	default:
		break;
	}
}

void MMarkdown::closeEvent(QCloseEvent *event)
{
	hide();
	event->ignore();
}

//HTCAPTION ��ʾ����ڱ������У����Խ�����ק
//m_nBorder ��������Ϊ5-8����
bool MMarkdown::nativeEvent(const QByteArray &eventType, void *message, long *result)
{
	Q_UNUSED(eventType)
		MSG *param = static_cast<MSG *>(message);
	switch (param->message)
	{
		case WM_NCHITTEST:
		{
			int nX = GET_X_LPARAM(param->lParam) - this->geometry().x();
			int nY = GET_Y_LPARAM(param->lParam) - this->geometry().y();
			// ������λ���ӿؼ��ϣ��򲻽��д���    
			//if (childAt(nX, nY) != NULL)
			//	return QWidget::nativeEvent(eventType, message, result);
			//*result = HTCAPTION;
			*result = HTCLIENT;
			// �������λ�ڴ���߿򣬽�������
			if (nY < 50)
			{
				QRect rc = menuButton->geometry();
				if (nX < rc.left() - 2)
					*result = HTCAPTION;
			}
			if ((nX > 0) && (nX < m_nBorder))
				*result = HTLEFT;
			if ((nX > this->width() - m_nBorder) && (nX < this->width()))
				*result = HTRIGHT;
			if ((nY > 0) && (nY < m_nBorder))
				*result = HTTOP;
			if ((nY > this->height() - m_nBorder) && (nY < this->height()))
				*result = HTBOTTOM;
			if ((nX > 0) && (nX < m_nBorder) && (nY > 0)
				&& (nY < m_nBorder))
				*result = HTTOPLEFT;
			if ((nX > this->width() - m_nBorder) && (nX < this->width())
				&& (nY > 0) && (nY < m_nBorder))
				*result = HTTOPRIGHT;
			if ((nX > 0) && (nX < m_nBorder)
				&& (nY > this->height() - m_nBorder) && (nY < this->height()))
				*result = HTBOTTOMLEFT;
			if ((nX > this->width() - m_nBorder) && (nX < this->width())
				&& (nY > this->height() - m_nBorder) && (nY < this->height()))
				*result = HTBOTTOMRIGHT;
			return true;
		}
		case WM_NCLBUTTONDBLCLK:
		{
			if (param->wParam == HTCAPTION)
			{
				if (this->windowState() != Qt::WindowMaximized)
					onMaxButtonClick();
				else
					onMax2ButtonClick();
					
				*result = 0;
				return true;
			}
		}
	}
	return QWidget::nativeEvent(eventType, message, result);
}

//���ϽǵĲ˵���ť
void MMarkdown::onMenuButtonClick()
{
	if (!m_optionMenu)
	{
		m_optionMenu = new QMenu(this);

		QAction *action = nullptr;
		action = new QAction("ע��", this);
		connect(action, &QAction::triggered, this, &MMarkdown::onSignout);
		m_optionMenu->addAction(action);

		m_optionMenu->addSeparator();

		action = new QAction("��������", this);
		connect(action, &QAction::triggered, this, &MMarkdown::onActionAbout);
		m_optionMenu->addAction(action);

		action = new QAction("�˳�", this);
		connect(action, &QAction::triggered, this, &MMarkdown::onCloseMainWnd);
		m_optionMenu->addAction(action);
	}
		
	QRect rc = menuButton->geometry();
	m_optionMenu->exec(mapToGlobal(QPoint(rc.left(), rc.bottom())));
}

void MMarkdown::onMinButtonClick()
{
	showMinimized();
}

//normal״̬��max��ť
void MMarkdown::onMaxButtonClick()
{
	showMaximized();
}

//max״̬��max��ť
void MMarkdown::onMax2ButtonClick()
{
	showNormal();
}

void MMarkdown::onCloseButtonClick()
{
	hide();
}

void MMarkdown::changeEvent(QEvent *event)
{
	if (event->type() != QEvent::WindowStateChange) return;
	//max��ť��ͼ��任��ͨ��������ť��ʵ��
	if (this->windowState() == Qt::WindowMaximized)
	{
		maxButton->setVisible(false);
		max2Button->setVisible(true);
	}
	else
	{
		max2Button->setVisible(false);
		maxButton->setVisible(true);
	}
}

void MMarkdown::paintEvent(QPaintEvent *event)
{
	//���Ƴ���ı߿�
 	QPainter painter(this);
	QColor color(0, 0, 0, 50);
	painter.setPen(color);
	painter.drawRect(QRect(0,0,this->width()-1,this->height()-1));

	painter.drawPixmap(QRect(0, 0, 1024, 80), QPixmap(":/FishMD/title2.png"));
}

void MMarkdown::onStartSetDataToServer()
{
	m_spinImage->start(36 * 100);
}

void MMarkdown::onNetworkError(bool b)
{
	m_networkMsgLabel->setVisible(b);
}