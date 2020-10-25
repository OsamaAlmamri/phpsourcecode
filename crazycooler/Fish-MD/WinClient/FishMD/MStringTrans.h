/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:�ַ��������ʽ��ת��
*/
/************************************************************************/

#pragma once

/************************************************************************/
/* 
ansi ת unicode
*/
/************************************************************************/
inline QString str2qstr(const std::string str)
{
	return QString::fromLocal8Bit(str.data());
}


/************************************************************************/
/* 
unicode ת ansi
*/
/************************************************************************/
inline std::string qstr2str(const QString qstr)
{
	QByteArray cdata = qstr.toLocal8Bit();
	return std::string(cdata);
}