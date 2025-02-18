<?php
final class MobilePagination {
	public $total = 0;
	public $page = 1;
	public $limit = 7;
	public $num_links = 10;
	public $url = '';
//	public $text = 'Showing {start} to {end} of {total} ({pages} Pages)';
	public $text = '显示 {start} - {end} 之 {total} (共计 {pages} 页)';
	public $text_first = '|&lt;';
	public $text_last = '&gt;|';
	public $text_next = '&gt;';
	public $text_prev = '&lt;';
	public $style_links = 'pageNumber';
	public $style_results = 'results';
	 
	public function render() {
		$total = $this->total;
		
		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}
		
		if (!$this->limit) {
			$limit = 10;
		} else {
			$limit = $this->limit;
		}
		
		$num_links = $this->num_links;
		$num_pages = ceil($total / $limit);
		
		$output = '';
		
		if ($page > 1) {
			$output .= ' <a class="'.$this->style_links.'" href="' . str_replace('{page}', 1, $this->url) . '">首页</a> <a class="'.$this->style_links.'" href="' . str_replace('{page}', $page - 1, $this->url) . '">上一页</a> ';
    	}

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);
			
				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}
						
				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}
		}
		
   		if ($page < $num_pages) {
			$output .= ' <a class="'.$this->style_links.'" href="' . str_replace('{page}', $page + 1, $this->url) . '">下一页</a> ';
		}
		
		$find = array(
			'{start}',
			'{end}',
			'{total}',
			'{pages}'
		);
		
		$replace = array(
			($total) ? (($page - 1) * $limit) + 1 : 0,
			((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),
			$total, 
			$num_pages
		);
		
		return $output;
	}
}
?>