<?php

namespace VF\Core;

/*
	VgotFaster PHP Framework
	Template Library
*/

class Template {

	private $var_regexp = "\@?\\\$[a-zA-Z_][\\\$\w]*[\$\-\>\w+]*(?:\[[\w\-\.\"\'\[\]\$]+\])*";
	private $vtag_regexp = "\<\?php echo (\@?\\\$[a-zA-Z_][\\\$\w]*[\$\-\>\w+]*(?:\[[\w\-\.\"\'\[\]\$]+\])*)\;\?\>";
	private $const_regexp = "\{([\w]+)\}";

	/**
	 *  ��ģ��ҳ�����滻��д�뵽cacheҳ��
	 *
	 * @param string $tplfile ��ģ��Դ�ļ���ַ
	 * @param string $objfile ��ģ��cache�ļ���ַ
	 * @return string
	 */
	function complie($tplfile, $objfile)
	{
		$template = file_get_contents($tplfile);
		$template = $this->parse($template);

		$VF =& getInstance();

		if ($VF->config->get('config','template_clean_blank')) {
			$template = preg_replace('#(\n|\r)[\s\t]+#',"\n",$template);
			$template = trim($template);
		}

		if (!is_dir($dir = dirname($objfile))) {
			$VF->load->helper('directory');
			mkdirs($dir);
		}

		if (!file_put_contents($objfile, $template, LOCK_EX)) {
			showError('Can\'t write template cache file !');
		}
	}

	/*
		����ģ���ǩ
		@param string $template ��ģ��Դ�ļ�����
		@return string
		@update 0:56 2009/12/11
	 */
	function parse($template)
	{
		$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);//ȥ��htmlע�ͷ���<!---->
		$template = preg_replace("/\{($this->var_regexp)\}/", "<?php echo \\1; ?>", $template);//�滻��{}�ı���
		$template = preg_replace("/\{($this->const_regexp)\}/", "<?php echo \\1; ?>", $template);//�滻��{}�ĳ���
		$template = preg_replace("/(?<!\<\?php echo |\\\\)$this->var_regexp/", "<?php echo \\0;?>", $template);//�滻�ظ���<?php echo
		$template = preg_replace("/\{php (.*?)\}/ies", "\$this->stripvTag('<?php \\1 ?>')", $template);//�滻php��ǩ
		$template = preg_replace("/\{echo (.*?)\}/ies", "\$this->stripvTag('<?php echo \\1; ?>')", $template);//�滻echo��ǩ
		$template = preg_replace("/\{for (.*?)\}/ies", "\$this->stripvTag('<?php for(\\1) {?>')", $template);//�滻for��ǩ
		$template = preg_replace("/\{elseif\s+(.+?)\}/ies", "\$this->stripvTag('<?php } elseif (\\1) { ?>')", $template);//�滻elseif��ǩ
		for($i=0; $i<3; $i++) {
			$template = preg_replace("/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopSection('\\1', '\\2', '\\3', '\\4')", $template);
			$template = preg_replace("/\{loop\s+$this->vtag_regexp\s+$this->vtag_regexp\}(.+?)\{\/loop\}/ies", "\$this->loopSection('\\1', '', '\\2', '\\3')", $template);
		}
		$template = preg_replace("/\{if\s+(.+?)\}/ies", "\$this->stripvTag('<?php if(\\1) { ?>')", $template);//�滻if��ǩ
		$template = preg_replace("/\{include\s+(.*?)\}/is", "<?php include \\1; ?>", $template);//�滻include��ǩ
		$template = preg_replace("/\{template\s+([\w\d\-_\/]+?)\}/is", "<?php \$this->load->template('\\1'); ?>", $template);//�滻template��ǩ
		$template = preg_replace("/\{else\}/is", "<?php } else { ?>", $template);//�滻else��ǩ
		$template = preg_replace("/\{\/if\}/is", "<?php } ?>", $template);//�滻/if��ǩ
		$template = preg_replace("/\{\/for\}/is", "<?php } ?>", $template);//�滻/for��ǩ
		$template = preg_replace("/$this->const_regexp/", "<?php echo \\1; ?>", $template);//note {else} Ҳ���ϳ�����ʽ���˴�Ҫע���Ⱥ�˳??
		$template = preg_replace("/(\\\$[a-zA-Z_]\w+\[)([a-zA-Z_]\w+)\]/i", "\\1'\\2']", $template);//����ά�����滻�ɴ������ŵı�׼ģʽ
		$template = preg_replace('/([\r\n]+)([\s\t]+)<\?php\s+foreach/','\\1<?php foreach',$template);  //��������ѭ��ǰ���Ʊ���Ϳո�
		$template = preg_replace('/([\r\n]+)([\s\t]+)<\?php\s+\}/','\\1<?php }',$template);  //�������Ľ�����ǰ���Ʊ���Ϳո�
		$template = preg_replace('/\s*\?><\?php\s*/',' ',$template);  //�������� php ��ǩ
		$template = preg_replace('/\s*\?>(\r|\n|\r\n)<\?php\s*/','\\1',$template);  //���������м任�� php ��ǩ
		$template = "<?php !defined('VGOTFASTER') && exit('Access Denied');?>\r\n$template";
		return $template;
	}

	/**
	 * ������ʽƥ���滻
	 *
	 * @param string $s ��
	 * @return string
	 */
	function stripvTag($s)
	{
		return preg_replace("/$this->vtag_regexp/is", "\\1", str_replace("\\\"", '"', $s));
	}

	function stripTagQuotes($expr)
	{
		$expr = preg_replace("/\<\?php echo (\\\$.+?);\?\>/s", "{\\1}", $expr);
		$expr = str_replace("\\\"", "\"", preg_replace("/\[\'([a-zA-Z0-9_\-\.\x7f-\xff]+)\'\]/s", "[\\1]", $expr));
		return $expr;
	}

	/**
	 * �滻ģ���е�LOOPѭ��
	 *
	 * @param string $arr ��
	 * @param string $k ��
	 * @param string $v ��
	 * @param string $statement ��
	 * @return string
	 */
	function loopSection($arr, $k, $v, $statement)
	{
		$arr = $this->stripvTag($arr);
		$k = $this->stripvTag($k);
		$v = $this->stripvTag($v);
		$statement = str_replace("\\\"", '"', $statement);
		return $k ? "<?php foreach((array)$arr as $k=>$v) { ?>$statement<?php } ?>" : "<?php foreach((array)$arr as $v) { ?>$statement<?php } ?>";
	}

}
