/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:�������ǶԻ���
*/
/************************************************************************/

#pragma once
#include "MCommon.h"
#include <QDialog>
#include "ui_MAboutDlg.h"

class MAboutDlg : public QDialog
{
	Q_OBJECT

public:
	MAboutDlg(QWidget *parent = Q_NULLPTR);
	~MAboutDlg();

private:
	Ui::MAboutDlg ui;
};
