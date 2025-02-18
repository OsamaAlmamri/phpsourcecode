<?php

namespace CigoAdminLib\Lib;

class CigoPage {
	public $firstRow; // 起始行数
	public $listRows; // 列表每页显示行数
	public $parameter; // 分页跳转时要带的参数
	public $totalRows; // 总行数
	public $totalPages; // 分页总页面数
	public $rollPage = 11;// 分页栏每页显示的页数
	public $lastSuffix = false; // 最后一页是否显示总页数

	protected $p = 'p'; //分页参数名
	protected $url = ''; //当前链接URL
	protected $nowPage = 1;

	// 分页显示定制
	protected $config = array(
		'header' => '<li class="disabled pageItem"><a href="#" onclick="return false;">共 %TOTAL_PAGE%页, %TOTAL_ROW% 条记录</a></li>',
		'prev' => '<<',
		'next' => '>>',
		'first' => '1...',
		'last' => '...%TOTAL_PAGE%',
		'theme' => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%',
	);

	/**
	 * 架构函数
	 * @param array $totalRows 总的记录数
	 * @param array $listRows 每页显示记录数
	 * @param array $parameter 分页跳转的参数
	 */
	public function __construct($totalRows, $listRows = 20, $parameter = array()) {
		C('VAR_PAGE') && $this->p = C('VAR_PAGE'); //设置分页参数名称
		/* 基础设置 */
		$this->totalRows = $totalRows; //设置总记录数
		$this->listRows = $listRows;  //设置每页显示行数
		$this->parameter = empty($parameter) ? $_GET : $parameter;
		$this->nowPage = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
		$this->nowPage = $this->nowPage > 0 ? $this->nowPage : 1;
		$this->firstRow = $this->listRows * ($this->nowPage - 1);
	}

	/**
	 * 定制分页链接设置
	 * @param string $name 设置名称
	 * @param string $value 设置值
	 */
	public function setConfig($name, $value) {
		if (isset($this->config[$name])) {
			$this->config[$name] = $value;
		}
	}

	/**
	 * 生成链接URL
	 * @param  integer $page 页码
	 * @return string
	 */
	protected function url($page) {
		return str_replace(urlencode('[PAGE]'), $page, $this->url);
	}

	/**
	 * 组装分页链接
	 * @return string
	 */
	public function show() {
		if (0 == $this->totalRows) return '';

		/* 生成URL */
		$this->parameter[$this->p] = '[PAGE]';
		$this->url = U(ACTION_NAME, $this->parameter);
		/* 计算分页信息 */
		$this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
		if (!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
			$this->nowPage = $this->totalPages;
		}

		/* 计算分页临时变量 */
		$now_cool_page = $this->rollPage / 2;
		$now_cool_page_ceil = ceil($now_cool_page);
		$this->lastSuffix && $this->config['last'] = $this->totalPages;

		//上一页
		$up_row = $this->nowPage - 1;
		$up_page = $up_row > 0 ? '<li class="pageItem"><a aria-label="Previous" href="' . $this->url($up_row) . '"><span aria-hidden="true">' . $this->config['prev'] . '</span></a></li>' : '';

		//下一页
		$down_row = $this->nowPage + 1;
		$down_page = ($down_row <= $this->totalPages) ? '<li class="pageItem"><a aria-label="Next" href="' . $this->url($down_row) . '"><span aria-hidden="true">' . $this->config['next'] . '</span></a></li>' : '';

		//第一页
		$the_first = '';
		if ($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1) {
			$the_first = '<li class="pageItem"><a href="' . $this->url(1) . '">' . $this->config['first'] . '</a></li>';
		}

		//最后一页
		$the_end = '';
		if ($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages) {
			$the_end = '<li class="pageItem"><a href="' . $this->url($this->totalPages) . '">' . $this->config['last'] . '</a></li>';
		}

		//数字连接
		$link_page = "";
		for ($i = 1; $i <= $this->rollPage; $i++) {
			if (($this->nowPage - $now_cool_page) <= 0) {
				$page = $i;
			} elseif (($this->nowPage + $now_cool_page - 1) >= $this->totalPages) {
				$page = $this->totalPages - $this->rollPage + $i;
			} else {
				$page = $this->nowPage - $now_cool_page_ceil + $i;
			}
			if ($page > 0 && $page != $this->nowPage) {

				if ($page <= $this->totalPages) {
					$link_page .= '<li class="pageItem"><a href="' . $this->url($page) . '">' . $page . '</a></li>';
				} else {
					break;
				}
			} else {
				if ($page > 0 && $this->totalPages != 1) {
					$link_page .= '<li class="active pageItem"><a href="#" onclick="return false;">' . $page . '</a></li>';
				}
			}
		}

		//替换分页内容
		$page_str = str_replace(
			array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
			array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages),
			$this->config['theme']);
		return "<nav><ul class=\"pagination\">{$page_str}</ul></nav>";
	}
}

