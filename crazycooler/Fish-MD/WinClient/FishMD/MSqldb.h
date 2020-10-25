/************************************************************************/
/*
Copyright 2017 wtulip Inc.
License GPL
Author:crazycooler
Description:sqlite�Ĳ�����װ
*/
/************************************************************************/

#pragma once

#include <string>
#include <vector>
#include "sqlite3pp.h"
#include <boost/lexical_cast.hpp>

class MSqldb;

/************************************************************************/
/* 
key-value ���ݿ�
*/
/************************************************************************/
class MKVdb
{
public:
	MKVdb(sqlite3pp::database *db);
	~MKVdb();

	void set(const std::string &key,const std::string &value);
	std::string getWithDefault(const std::string &key, const std::string &dft = "");
	bool get(const std::string &key, std::string &value);
	void del(const std::string &key);
	bool has(const std::string &key);

	template<typename T>
	void setT(const std::string &key, const T &value)
	{
		std::stringstream ss;
		ss << value;
		set(key, ss.str());
	}

	template<typename T>
	T getT(const std::string &key, const T &value)
	{
		std::string s;
		if (get(key, s))
		{
			return boost::lexical_cast<T>(s);
		}
		else
		{
			return value;
		} 
	}

private:
	sqlite3pp::database *m_db;
};

/************************************************************************/
/*
doc���ݿ������ݽṹ
*/
/************************************************************************/
struct MDocData 
{
	std::string id;
	std::string src_data;
	std::string cur_data;
	int version;
	int change_flag;
	int create_flag;
};

/************************************************************************/
/*
dir���ݿ������ݽṹ
*/
/************************************************************************/
struct MDirData
{
	std::string id;
	std::string src_data;
	std::string cur_data;
	int version;
	int change_flag;
};

/************************************************************************/
/*
doc���ݿ������װ
�����ڱ��ض�doc������
*/
/************************************************************************/
class MDocdb
{
public:
	MDocdb(sqlite3pp::database *db);
	~MDocdb();
	/************************************************************************/
	/* 
	��������doc����ʱ���docû�кͷ�����ͬ��
	@param docId	�ĵ�ID
	@param curData	�ĵ�����
	*/
	/************************************************************************/
	void createDocLocal(const std::string &docId, const std::string &curData);

	/************************************************************************/
	/* 
	ɾ�������ĵ�
	@param docIds	�ĵ�IDs
	*/
	/************************************************************************/
	void delDocsLocal(const std::vector<std::string> &docIds);

	/************************************************************************/
	/* 
	���±����ĵ�������doc��û�кͷ�����ͬ��
	@param docID	�ĵ�ID
	@param curData	�ĵ�����
	*/
	/************************************************************************/
	void updateDocLocal(const std::string &docId, const std::string &curData);

	/************************************************************************/
	/* 
	���޸ı�־����
	@param docIds �ĵ�IDs
	*/
	/************************************************************************/
	void resetChangeFlag(const std::string &docIds);

	/************************************************************************/
	/* 
	��ȡ���޸ģ���û��ͬ�������������ĵ�
	@param docs ���÷��ر��޸ĵ��ĵ�
	*/
	/************************************************************************/
	void getChangeDocs(std::vector<MDocData> &docs);

	/************************************************************************/
	/* 
	���޸ĵ�docͬ�������������������ø�doc
	��Ϊdoc��src_data��cur_data������
	src_dataһ���¼���������һ�θ��µ����ݣ�
	cur_dataΪ�����޸Ĺ���Ļ���ֵ
	@param docId �ĵ�ID
	@param content �ĵ�����
	@param version �ĵ��汾
	*/
	/************************************************************************/
	void updateDocAfterUpload(const std::string &docId, const std::string &content, int version);

	/************************************************************************/
	/*
	�������޸��˵�doc���޸ı�־��������Ϊ1
	*/
	/************************************************************************/
	void refreshChangeFlag();

	/************************************************************************/
	/* 
	��ȡdocֵ
	@param docId �ĵ�ID
	@param data  �ĵ�����
	*/
	/************************************************************************/
	bool getDoc(const std::string &docId, MDocData &data);

	/************************************************************************/
	/* 
	����docֵ
	@param data �ĵ�����
	*/
	/************************************************************************/
	void setDoc(const MDocData &data);

private:
	sqlite3pp::database *m_db;
};


/************************************************************************/
/* 
dir��Ŀ¼�����ݿ������װ
�����ڱ��ض�dir������
*/
/************************************************************************/
class MDirDB
{
public:
	MDirDB(sqlite3pp::database *db);
	~MDirDB();
	/************************************************************************/
	/* 
	�޸ı��ص�dir����û��ͬ����������
	*/
	/************************************************************************/
	void changeDirLocal(const std::string &dirId, const std::string &content);

	/************************************************************************/
	/* 
	���޸ı�־����
	@param dirId Ŀ¼ID
	*/
	/************************************************************************/
 	void resetChangeFlag(const std::string &dirId);


	/************************************************************************/
	/*
	Ŀ¼�Ƿ��޸�
	@param dirId Ŀ¼ID
	*/
	/************************************************************************/
	bool isChange(const std::string &dirId);

	/************************************************************************/
	/*
	���޸ĵ�dirͬ�������������������ø�dir
	��Ϊdir��src_data��cur_data������
	src_dataһ���¼���������һ�θ��µ����ݣ�
	cur_dataΪ�����޸Ĺ���Ļ���ֵ
	@param dirId Ŀ¼ID
	@param content Ŀ¼����
	@param version Ŀ¼�汾
	*/
	/************************************************************************/
	void updateDocAfterUpload(const std::string &dirId, const std::string &content, int version);

	/************************************************************************/
	/* 
	�������޸��˵�dir���޸ı�־��������Ϊ1
	@param dirId Ŀ¼ID
	*/
	/************************************************************************/
	void refreshChangeFlag(const std::string &dirId);

	/************************************************************************/
	/* 
	��ȡdir����
	@param dirId Ŀ¼ID
	@param data Ŀ¼����
	*/
	/************************************************************************/
	bool getDir(const std::string &dirId, MDirData &data);

	/************************************************************************/
	/*
	����dir����
	@param data Ŀ¼����
	*/
	/************************************************************************/
	void setDir(const MDirData &data);

private:
	sqlite3pp::database *m_db;
};

class MSqldb
{
public:
	MSqldb();
	~MSqldb();

	bool instance(const std::string &workPath);

	MKVdb *getKVDB() { return m_kvdb; }
	MDocdb *getDocDB() { return m_docdb; }
	MDirDB *getDirDB() { return m_dirdb; }
private:
	MKVdb *m_kvdb;
	MDocdb *m_docdb;
	MDirDB *m_dirdb;

	sqlite3pp::database m_db;
};

