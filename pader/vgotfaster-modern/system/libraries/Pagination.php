<?php
/**
 * VgotFaster PHP Framework
 *
 * @package VgotFaster
 * @author pader
 * @copyright Copyright (c) 2009-2010, VGOT.NET
 * @link http://www.vgot.net/ http://vgotfaster.googlecode.com
 * @filesource
 */

namespace VF\Library;

/*
Exmaple CSS:
.fpage {font-size:12px; padding:3px; margin:3px; text-align:center;}
.fpage a,.fpage b {background-color:#FFF; padding:5px 7px; margin:0 2px; color:#333; border:1px solid #C2D5E3; display:inline-block; vertical-align:middle; text-decoration:none;}
.fpage a:hover {border-color:#336699; text-decoration:none;}
.fpage b {background-color:#E5EDF2;}
.fpage input {border:#1586D6 1px solid; color:#036CB4; vertical-align:middle; text-align:center;}
*/

/**
 * VgotFaster Pagination Library
 *
 * �״������ 3:25 2009/11/27
 *
 * @package VgotFaster
 * @author Pader
 */
class Pagination {

	var $set;

	function __construct($set=array())
	{
		$this->set = array(
			//����������
			'totalRows' => 0,
			//��ǰҳ��
			'curPage' => 1,
			/*
				ҳ������
				ҳ�������У���ʹ�� [pageNumReplace] ������ָ�����ַ������ҳ���֣�Ĭ�Ϸ���Ϊ "*"
				����list/page/* ������� list/page/1, list/page/2 ��ҳ���ַ
			*/
			'pageUrl' => '',
			//ÿҳ�����б���ʾ����
			'perPage' => 20,
			//��ʾ���ٸ���������
			'pageLinks' => 8,
			//�����������滻�����ֵķ���
			'pageNumReplace' => '*',

			//������ҳ��ʼ��ǩ����
			'fullTagOpen' => '<div class="fpage">',
			'fullTagClose' => '</div>',

			//��һҳ���Ӽ���ʼ��ǩ����
			'firstLink' => '&laquo;',
			'firstTagOpen' => '',
			'firstTagClose' => '',

			//ҳ��ܶ�ʱ��������
			'transTag' => '...',

			//���һҳ���Ӽ���ʼ��ǩ����
			'lastLink' => '&raquo;',
			'lastTagOpen' => '',
			'lastTagClose' => '',

			//��һҳ���Ӽ���ʼ��ǩ����
			'nextLink' => '��һҳ',
			'nextTagOpen' => '',
			'nextTagClose' => '',

			//��һҳ���Ӽ���ʼ��ǩ����
			'prevLink' => '��һҳ',
			'prevTagOpen' => '',
			'prevTagClose' => '',

			//��ǰҳ��ʼ��ǩ����
			'curTagOpen' => '<b>',
			'curTagClose' => '</b>',

			//��������������ʼ��ǩ����
			'numTagOpen' => '',
			'numTagClose' => '',

			//�Ƿ���ʾҳ��ͳ��
			'showPageCount' => true
		);

		$VF =& getInstance();

		$config = getConfig('pagination', true);
		$config !== null && $this->initialize($config);

		if(count($set) > 0) {
			$this->initialize($set);
		}
	}

	/*
		��ʼ����ҳ����
		�ڷ�ҳǰ�޸�Ĭ�ϵ�����
	*/
	function initialize($config)
	{
		foreach($config as $key => $val) {
			if(isset($this->set[$key])) {
				$this->set[$key] = $val;
			}
		}
		//if($this
	}

	/*
		��ȡ��ѯ��ʼ��
		�������������ڻ�ȡ��ǰҳ���ѯ�п�ʼ��
	*/
	function getStart($page='')
	{
		if(empty($page)) {
			$page = $this->set['curPage'];
		}
		$page = intval($page);
		$page = $page > 1 ? $page : 1;
		return ($page - 1) * $this->set['perPage'];
	}

	/*
		������ҳ���� HTML
		�������������Թ����
	*/
	function makeLinks()
	{
		$total = $this->set['totalRows'];
		$curPage = intval($this->set['curPage']);
		$perPage = $this->set['perPage'];
		$pageLinks = $this->set['pageLinks'];

		$multiPage = '';

		if($total > $perPage) {
			$pagesAll = ceil($total / $perPage);  //���ʵ��ҳ��
			$curPage = $curPage < 1 ? 1 : ($curPage > $pagesAll ? $pagesAll : $curPage);  //��ǰҳ
			$halfLink = $pageLinks / 2;
			$halfDisplay = $halfLink % 2 == 0 ? floor($halfLink) : ceil($halfLink);  //����ʱ��ǰ�������м䣬˫��ʱ��ǰ�������м�ƫ��

			$start = $curPage - $halfDisplay + 1;  //�ó���߿�ʼ��ƫ��������������
			$start = $pagesAll - $start < $pageLinks ? $start - ($pageLinks - ($pagesAll - $start)) + 1 : $start;  //������βʱ�޲�ǰ��ҳ��
			$start = $start < 1 ? 1 : $start;

			$end = $start + $pageLinks - 1;  //�ó����ұ߽�����ҳ��
			//$end = ($curPage - 1) < $halfDisplay ? 1 + $halfDisplay * 2 : $end;
			$end = $end >= $pagesAll ? $pagesAll : $end;

			//����ÿһҳ������
			for($i=$start; $i<=$end; $i++) {
				$multiPage .= ($i == $curPage) ? $this->set['curTagOpen'].$i.$this->set['curTagClose'] : $this->set['numTagOpen'].'<a href="'.$this->pageLink($i).'">'.$i.'</a>'.$this->set['numTagClose'];
			}

			//��һҳ
			if($curPage > 1) {
				$multiPage = $this->set['prevTagOpen'].'<a href="'.$this->pageLink($curPage - 1).'" class="prev">'.$this->set['prevLink'].'</a>'.$this->set['prevTagClose'].$multiPage;
			}

			//��һҳ
			if($curPage > 1 && $start > 1) {
				$multiPage = $this->set['firstTagOpen'].'<a href="'.$this->pageLink(1).'" class="first">'.$this->set['firstLink'].'</a>'.$this->set['firstTagClose'].$multiPage;
			}

			//��һҳ
			if($curPage < $pagesAll) {
				$multiPage .= $this->set['nextTagOpen'].'<a href="'.$this->pageLink($curPage + 1).'" class="next">'.$this->set['nextLink'].'</a>'.$this->set['nextTagClose'];
			}

			//���һҳ
			if($curPage < $pagesAll && $end < $pagesAll) {
				$multiPage .=	$this->set['lastTagOpen'].'<a href="'.$this->pageLink($pagesAll).'" class="last">'.$this->set['lastLink'].'</a>'.$this->set['lastTagClose'];
			}

			$multiPage = !empty($multiPage) ? $this->set['fullTagOpen'].($this->set['showPageCount'] ? '<em>&nbsp;('.$pagesAll.')&nbsp;</em>' : '').$multiPage.$this->set['fullTagClose'] : '';
		}

		return $multiPage;
	}

	/*
		���ݷ�ҳ�������ҳ���ַ
	*/
	function pageLink($page)
	{
		return str_replace($this->set['pageNumReplace'],$page,$this->set['pageUrl']);
	}

}
