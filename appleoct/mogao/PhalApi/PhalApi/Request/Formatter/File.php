<?php
/**
 * PhalApi_Request_Formatter_File 格式化上传文件
 *
 * @package     PhalApi\Request
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-11-07
 */

class PhalApi_Request_Formatter_File extends PhalApi_Request_Formatter_Base implements PhalApi_Request_Formatter {

	/**
	 * 格式化文件类型
     * @param array $rule array('name' => '', 'type' => 'file', 'default' => array(...), 'min' => '', 'max' => '', 'range' => array(...))
     * @throws PhalApi_Exception_BadRequest
	 */
    public function parse($value, $rule) {
        $default = isset($rule['default']) ? $rule['default'] : NULL;

        $index = $rule['name'];
        if (!isset($_FILES[$index]) && $default !== NULL) {
            return $default;
        }

        if (!isset($_FILES[$index]) || !isset($_FILES[$index]['error']) || is_array($_FILES[$index]['error'])) {
            throw new PhalApi_Exception_BadRequest(
                T('miss upload file: {file}', array('file' => $index)));
        }

        if ($_FILES[$index]['error'] != UPLOAD_ERR_OK) {
            throw new PhalApi_Exception_BadRequest(
                T('fail to upload file with error = {error}', array('error' => $_FILES[$index]['error'])));
        }

        $sizeRule = $rule;
        $sizeRule['name'] = $sizeRule['name'] . '.size';
        $this->filterByRange($_FILES[$index]['size'], $sizeRule);

        if (!empty($rule['range']) && is_array($rule['range'])) {
		    $rule['range'] = array_map('strtolower', $rule['range']);
            $this->formatEnumValue(strtolower($_FILES[$index]['type']), $rule);
        }

        return $_FILES[$index];
    }
}
