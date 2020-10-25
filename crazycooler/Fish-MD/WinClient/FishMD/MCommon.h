/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:����ͷ�ļ�
*/
/************************************************************************/
#pragma once
#pragma execution_character_set("utf-8")

#include "MGlobal.h"
extern MGlobal *g_global;

//ˢ��ʱ�䣬��λms
#define UPDATE_TIME_INTERVAL	10000

//�汾��Ϣ
#define SINGLE_QMD_SERVER_NAME  "Fish-MD-1.1.0"


#define ITEM_TYPE (256+2)
#define ITEM_ID (256+3)

//item���ͣ���json�ṹ�ж�Ӧ
#define ITEM_TYPE_FOLDER	1
#define ITEM_TYPE_DOC	2

//ע���URL
#define SIGN_UP_URL "http://mt.wtulip.com/qmd/sign-up.html"

#include <QJsonDocument>
#include <QJsonObject>
#include <QJsonArray>

//����Ӧ��Ĵ�����
#define RESPONSE_NOT_JSON 1000
#define RESPONSE_ERROR_FLAG 1001
#define RESPONSE_AUTH_ERROR 1002

//doc�ĵ�ǰģʽ
#define EDITOR_MODE_EDIT	1
#define EDITOR_MODE_PREVIEW 2

#ifndef GET_X_LPARAM
#define GET_X_LPARAM(lp)                        ((int)(short)LOWORD(lp))
#endif
#ifndef GET_Y_LPARAM
#define GET_Y_LPARAM(lp)                        ((int)(short)HIWORD(lp))
#endif

#define EXIT_CODE 0
#define SIGNOUT_CODE 1