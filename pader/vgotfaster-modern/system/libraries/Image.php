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

/**
 * VgotFaster Image Library
 *
 * @package VgotFaster
 * @author Pader
 */
class Image {

	protected $set = array();
	protected $resource;  //ͼ����Դ���
	protected $width;
	protected $height;
	protected $current = array(); //�洢���ڴ������Ϣ

	//֧�ִ�����ļ����Ͷ���,��ָ�������ͼƬ�ĺ���
	public $allowTypes = array(
		'jpg'  => 'imagejpeg',
		'gif'  => 'imagegif',
		'png'  => 'imagepng',
		'wbmp' => 'image2wbmp',
		'jpeg' => 'imagejpeg'
	);

	/**
	 * Image::__construct()
	 *
	 * @param $config
	 * @return void
	 */
	public function __construct($config=NULL)
	{
		if(!extension_loaded('gd') and !dl('gd.so')) {
			showError('PHP GD extension doesn\'t loaded, can not use Image library!');
		}

		$this->set = array(
			'srcImage'   => '',   //ͼƬԴ�ļ�
			'createPath' => '',   //ͼƬ����·��

			'quality'    => 100,       //����ͼƬ����
			'imageType'      => '',    //ͼƬ mines ����
			'autoMakeDir'    => FALSE, //ͼƬ����Ŀ¼������ʱ�Ƿ��Զ�����
			'transparency'   => FALSE, //Ĭ�ϲ��� gif �� png ����͸������

			'cornerCoverImage' => 'app/data/rounded_corner.gif',  //�趨Բ���ɰ�ͼƬ

			'markFontColor' => '#FFFFFF',
			'markFontShadowColor' => '#585858',
			'fontFile'      => 'C:\\Windows\\Fonts\\Arial.ttf' //����ˮӡʹ�õ�Ĭ������
		);

		if(is_array($config) and !empty($config)) {
			$this->initialize($config);
		}
	}

	/**
	 * ���ô�������
	 *
	 * @param array $config
	 * @return void
	 */
	public function initialize($config,$value=NULL)
	{
		if (is_array($config)) {
			foreach($config as $key => $val) {
				if(isset($this->set[$key])) {
					$this->set[$key] = $val;
				}
			}
		} else {
			if (isset($this->set[$config])) {
				$this->set[$config] = $value;
			}
		}

		//��鴦����Դ�ļ�
		if($this->set['srcImage'] != '') {
			$this->checkFileExists($this->set['srcImage']);
			$this->open($this->set['srcImage']);
		}

		//�Զ�����Ŀ¼
		if($this->set['createPath'] != '') {
			$path = dirname($this->set['createPath']);
			if(!is_dir($path)) {
				$VF =& getInstance();
				$VF->load->helper('directory');
				mkdirs($path);
			}
		}
	}

	/**
	 * ��ͼƬԴ
	 *
	 * ���ļ�,��ȡͼƬ��Դ���,����ȡ���
	 *
	 * @param string $filepath
	 * @param string $imageType ǿ�ƶ�ȡΪ��ʽ
	 * @return void
	 */
	public function open($file,$imageType=NULL)
	{
		$src = '';
		if(function_exists('file_get_contents')) {
			$src = file_get_contents($file);
		} else {
			$handle = fopen($file,'r');
			while(!feof($handle)) {
				$src .= fgets($handle,4096);
			}
			fclose($handle);
		}

		if(empty($src)) {
			exit('Image resource is empty!');
		}

		$this->resource = imagecreatefromstring($src);
		$this->width = $this->getImageWidth($this->resource);
		$this->height = $this->getImageHeight($this->resource);
		$this->current = array(); //���״̬

		if(!is_null($imageType)) {
			$this->current['imageType'] = $imageType;
		} elseif(empty($this->current['imageType'])) {
			$this->current['imageType'] = $this->getImageType($file);
		}

		if ($this->set['transparency'] AND $this->current['imageType'] == 'gif') {
			$otsc = imagecolortransparent($this->resource);
			if ($otsc >= 0 AND $otsc < imagecolorstotal($this->resource)) {
				$this->current['trans'] = imagecolorsforindex($this->resource,$otsc);
			} else {
				$this->current['trans'] = array('red'=>255,'green'=>255,'blue'=>255,'alpha'=>127);
			}
		}

		return $this->resource;
	}

	/**
	 * ͼ��Բ�Ǵ���
	 *
	 * @param int $radius Բ��ֵ��С
	 * @param array|bool $corner ����Ԫ�طֱ��Ӧ ����,����,����,���� Ϊ TRUE ʱȫԲ��, FALSE ��Բ��
	 * @param string $coverImage �趨�ɰ�ͼƬ
	 * @return
	 */
	public function corner($radius=20,$corner=array(1,1,1,1),$coverImage=NULL)
	{
		//�Ľ��趨
		if(is_bool($corner)) {
			if($corner == TRUE) {
				$topLeft = true;
				$bottomLeft = true;
				$bottomRight = true;
				$topRight = true;
			} else return;
		} elseif(is_array($corner) and count($corner) >= 4) {
			$topLeft = (bool)$corner[0];
			$topRight = (bool)$corner[1];
			$bottomLeft = (bool)$corner[2];
			$bottomRight = (bool)$corner[3];
		}

		if(is_null($coverImage)) {
			$coverImage = $this->set['cornerCoverImage'];
		}

		//ǿ��ʹ�� gif ��ʽ�ɰ�ͼƬ
		if(!file_exists($coverImage)) showError('The corner cover image doesn\'t exists!');
		elseif(!preg_match('/\.gif$/i',$coverImage)) showError('The corner cover image type not <b>gif</b>, please use gif image!');

		$cornerSource = imagecreatefromgif($coverImage);
		$cornerW = imagesx($cornerSource);
		$cornerH = imagesy($cornerSource);

		$cornerIm = imagecreatetruecolor($radius, $radius);
		imagecopyresampled($cornerIm, $cornerSource, 0, 0, 0, 0, $radius, $radius, $cornerW, $cornerH);

		$cornerW = imagesx($cornerIm);
		$cornerH = imagesy($cornerIm);

		$black = imagecolorallocate($this->resource,0,0,0);

		//����
		if($topLeft == true) {
			imagecolortransparent($cornerIm, $black);
			imagecopymerge($this->resource, $cornerIm, 0, 0, 0, 0, $cornerW, $cornerH, 100);
		}

		//����
		if ($bottomLeft == true) {
			$rotated = imagerotate($cornerIm, 90, 0);
			imagecolortransparent($rotated, $black);
			imagecopymerge($this->resource, $rotated, 0, ($this->height - $cornerH), 0, 0, $cornerW, $cornerH, 100);
		}

		//����
		if ($bottomRight == true) {
			$rotated = imagerotate($cornerIm, 180, 0);
			imagecolortransparent($rotated, $black);
			imagecopymerge($this->resource, $rotated, ($this->width - $cornerW), ($this->height - $cornerH), 0, 0, $cornerW, $cornerH, 100);
		}

		//����
		if ($topRight == true) {
			$rotated = imagerotate($cornerIm, 270, 0);
			imagecolortransparent($rotated, $black);
			imagecopymerge($this->resource, $rotated, ($this->width - $cornerW), 0, 0, 0, $cornerW, $cornerH, 100);
		}

		//$this->resource = $image;
	}

	/**
	 * ����ͼƬ
	 *
	 * �� X �� Y ��Ϊ��ʱ,���Զ�ȡͼ���м�������в���
	 *
	 * @param int $width
	 * @param int $height
	 * @param int $x Axis X, if is null, then crop will be horizontal center
	 * @param int $y Axis Y, if is null, then crop will be vertical middle
	 * @return void
	 */
	public function crop($width,$height,$x=NULL,$y=NULL)
	{
		if(is_null($x)) $x = round(($this->width - $width) / 2);
		if(is_null($y)) $y = round(($this->height - $height) / 2);

		$newIm = $this->imageCreate($width,$height);
		imagecopy($newIm,$this->resource,0,0,$x,$y,$width,$height);

		$this->width = $width;
		$this->height = $height;
		$this->resource = $newIm;
		unset($newIm);
	}

	/**
	 * ���߿�
	 *
	 * @param integer $size
	 * @param string $color
	 * @param string $pos 'in' or 'out' border
	 * @return void
	 */
	public function drawBorder($size=1,$color='#000000',$pos='in')
	{
		//�м�ͼ������Ŀ��
		$inWidth = $this->width - $size * 2;
		$inHeight = $this->height - $size * 2;

		if($pos == 'out') {
		$cWidth = $this->width + $size * 2;
		$cHeight = $this->height + $size * 2;
		$srcStart= 0;
			$inWidth = $this->width;
			$inHeight = $this->height;
		} else {
			$cWidth = $this->width;
			$cHeight = $this->height;
			$srcStart= $size;
			$inWidth = $this->width - $size * 2;
			$inHeight = $this->height - $size * 2;
		}

		//����һ����ɫ���ͼ
		$c = $this->parseColor($color);
		$newIm = $this->imageCreate($cWidth,$cHeight);

		$color = imagecolorallocate($newIm,$c[0],$c[1],$c[2]);
		imagefilledrectangle($newIm,0,0,$cWidth,$cHeight,$color);// ��䱳��ɫ

		imagecopyresampled($newIm,$this->resource,$size,$size,$srcStart,$srcStart,$inWidth,$inHeight,$inWidth,$inHeight);

		$this->resource = $newIm;
	}

	/**
	 * ˮƽ��ת
	 *
	 * @return void
	 */
	public function flipX()
	{
		$size = $this->getImageSize($this->resource);
		$newIm = $this->imageCreate($size['w'],$size['h']);

		for ($x=0; $x<$size['w']; $x++) {
			imagecopy($newIm, $this->resource, $size['w'] - $x - 1, 0, $x, 0, 1, $size['h']);
		}

		$this->resource = $newIm;
	}

	/**
	 * ��ֱ��ת
	 *
	 * @return void
	 */
	public function flipY()
	{
		$size = $this->getImageSize($this->resource);
		$newIm = $this->imageCreate($size['w'],$size['h']);

		for($y=0; $y<$size['h']; $y++) {
			imagecopy($newIm, $this->resource, 0, $size['h'] - $y - 1, 0, $y, $size['w'], 1);
		}

		$this->resource = $newIm;
	}

	/**
	 * ͼ��ɫ����
	 *
	 * @return void
	 */
	public function invertColor()
	{
		$bgColor = 0;
		$newIm = $this->imageCreate($this->width, $this->height);

		for($y=0; $y < $this->height; $y++) {
			for($x=0; $x < $this->width; $x++) {
				$colorPixel = imagecolorat($this->resource, $x, $y);
				$colorReverse = (~$colorPixel) & 0xFFFFFF ;  //php�õ�32λ��, ������Ҫȥ���ʼ8��1
				imagesetpixel($newIm,$x,$y,$colorReverse);
			}
		}

		$this->resource = $newIm;
	}

	/**
	 * ��ͼ��ʹ���˾�
	 *
	 * @param int �˾�Ч�����ͣ���������
	 *    IMG_FILTER_NEGATE: ��ͼ����������ɫ��ת��
	 *    IMG_FILTER_GRAYSCALE: ��ͼ��ת��Ϊ�Ҷȵġ�
	 *    IMG_FILTER_BRIGHTNESS: �ı�ͼ������ȡ��� arg1 �趨���ȼ���
	 *    IMG_FILTER_CONTRAST: �ı�ͼ��ĶԱȶȡ��� arg1 �趨�Աȶȼ���
	 *    IMG_FILTER_COLORIZE: �� IMG_FILTER_GRAYSCALE ���ƣ���������ָ����ɫ���� arg1��arg2 �� arg3 �ֱ�ָ�� red��blue �� green��ÿ����ɫ��Χ�� 0 �� 255��
	 *    IMG_FILTER_EDGEDETECT: �ñ�Ե�����ͻ��ͼ��ı�Ե��
	 *    IMG_FILTER_EMBOSS: ʹͼ�񸡵񻯡�
	 *    IMG_FILTER_GAUSSIAN_BLUR: �ø�˹�㷨ģ��ͼ��
	 *    IMG_FILTER_SELECTIVE_BLUR: ģ��ͼ��
	 *    IMG_FILTER_MEAN_REMOVAL: ��ƽ���Ƴ������ﵽ����Ч����
	 *    IMG_FILTER_SMOOTH: ʹͼ����Ử���� arg1 �趨�Ử����
	 * @return void
	 */
	public function filter($filterType) {
		$args = array_merge(array($this->resource, $filterType), func_get_args());
		call_user_func_array('imagefilter', $args);
	}

	/**
	 * ˮӡ
	 *
	 * @param string|array $createPath
	 * @return void
	 */
	public function writeText($words, $fontSize=10, $wordMargin=8, $fontFile=NULL) {
		$words = iconv('gb2312', 'utf-8', $words);
		if (is_null($fontFile)) $fontFile = $this->set['fontFile'];

		$wordBox = imagettfbbox($fontSize, 0, $fontFile, $words);  //��ȡ�ַ�����������
		$wordWidth = $wordBox[2] - $wordBox[0];  //�ַ������
		$wordHeight = $wordBox[1] - $wordBox[7];  //�ַ����߶�

		$wordRight = $this->width - $wordMargin;  //�ַ����ұ�����
		$wordLeft = $wordRight - $wordWidth;  //�ַ����������
		$wordBottom = $this->height - $wordMargin;  //�ַ����ײ�����
		$wordTop = $wordBottom - $wordHeight;  //�ַ�����������


		$fontColor = imagecolorallocatealpha($this->resource,255,255,255,0);  //������ɫ
		$shadowColor = imagecolorallocatealpha($this->resource,0,0,0,95);  //��Ӱ��ɫ

		imagettftext($this->resource,$fontSize,0,$wordLeft + 1,$wordBottom + 1,$shadowColor,$fontFile,$words);  //�����ַ���
		imagettftext($this->resource,$fontSize,0,$wordLeft,$wordBottom,$fontColor,$fontFile,$words);  //�����ַ�����Ӱ
	}

	public function markImage($markImageFile) {
		//��ȡˮӡͼƬ����Ϣ
		$src = '';
		if (function_exists('file_get_contents')) {
			$src = file_get_contents($markImageFile);
		} else {
			$handle = fopen($markImageFile, 'r');
			while(!feof($handle)) {
				$src .= fgets($handle,4096);
			}
			fclose($handle);
		}

		if (empty($src)) {
			exit('MarkImage resource is empty!');
		}

		$mark = imagecreatefromstring($src);
		list($mWidth, $mHeight) = getimagesize($markImageFile);

		if (!$size) {
			exit($this->ERROR['unalviable']);
		}

		//��Ŀ��ͼƬ����������ͼƬ��
		imagecopy($this->resource, $mark, 10, 100, 0, 0, $mWidth, $mHeight);
	}

	/**
	 * ���Ĵ�С
	 *
	 * �����÷� resize(123,456)
	 * ����Զ� resize('auto',386)
	 * �߶��Զ� resize(386[,'auto'])  Ĭ�ϸ߶��Զ�
	 *
	 * @param int|string $newWidth Number or 'auto', if this is 'auto' then height must be a number
	 * @param int|string $newHeight Number or 'auto'
	 * @return void
	 */
	public function resize($width,$height='auto')
	{
		if((is_numeric($width) and $width < 1) or (is_numeric($height) and $height < 1)) {
			showError('New image width or height can\'t be zero!');
		}

		//Auto adjust width or height
		if(strtolower($width) == 'auto') $width = round($height * ($this->width / $this->height));
		elseif(strtolower($height) == 'auto') $height = round($width / ($this->width / $this->height));

		$newIm = $this->imageCreate($width,$height);
		imagecopyresampled($newIm,$this->resource,0,0,0,0,$width,$height,$this->width,$this->height);

		$this->width = $width;
		$this->height = $height;
		$this->resource = $newIm;
	}

	/**
	 * ��תͼ��
	 *
	 * @param float $angle ��ת�Ƕ�
	 * @param string $direction ��ת����, 'left' or 'right'
	 * @return void
	 */
	public function rotate($angle=90.0,$direction='right')
	{
		$newIm = $this->imageCreate($this->width, $this->height);
		imagecopyresized($newIm, $this->resource, 0, 0, 0, 0, $this->width, $this->height, $this->width, $this->height);
		$colorWhite = imagecolorallocate($newIm, 255, 255, 255);

		//��Ϊ imagerotate ʼ������࿪ʼ�������ǳ��������Ҳ���ת,�������Ҳ�ʱ, ��Ҫһ������
		if($direction == 'right') {
			$angle = $angle + (180 - $angle) * 2;
		}

		$this->resource = imagerotate($newIm, $angle, $colorWhite, 1);

		//����ͼ���С
		$size = $this->getImageSize();
		$this->width = $size['w'];
		$this->height = $size['h'];
	}

	/**
	 * ��ͼ��
	 *
	 * ��������Ҫ������ش���,����ϵͳ��Դ,������ʹ��
	 *
	 * @param integer $sharp �񻯳̶� 0.1 - 1
	 * @return void
	 */
	public function sharp($sharp=0.7)
	{
		$newIm = $this->imageCreate($this->width, $this->height);
		$cnt = 0;
		for($x=0; $x<$this->width; $x++) {
			for($y=0; $y<$this->height; $y++) {
				$srcClr1 = imagecolorsforindex($newIm, imagecolorat($this->resource,($x - 1 < 0 ? 0 : $x - 1), ($y - 1 < 0 ? 0 : $y - 1)));
				$srcClr2 = imagecolorsforindex($newIm, imagecolorat($this->resource, $x, $y));
				$r = intval($srcClr2["red"] + $sharp * ($srcClr2["red"] - $srcClr1["red"]));
				$g = intval($srcClr2["green"] + $sharp * ($srcClr2["green"] - $srcClr1["green"]));
				$b = intval($srcClr2["blue"] + $sharp * ($srcClr2["blue"] - $srcClr1["blue"]));
				$r = min(255, max($r, 0));
				$g = min(255, max($g, 0));
				$b = min(255, max($b, 0));
				if(($dstClr = imagecolorexact($this->resource, $r, $g, $b)) == -1) $DST_CLR = imagecolorallocate($this->resource, $r, $g, $b);
				$cnt++;
				if($dstClr == -1) die("color allocate faile at $x, $y ($cnt).");
				imagesetpixel($newIm, $x, $y, $dstClr);
			}
		}

		$this->resource = $newIm;
	}

	/**
	 * ����ͼƬ
	 *
	 * @param string $path �ļ�·��
	 * @param int $quality 1 - 100
	 * @return void
	 */
	public function save($path,$quality=NULL)
	{
		return $this->createImage($path,$quality);
	}

	/**
	 * ����ͼƬ
	 *
	 * @param string $createPath
	 * @param int $quality 1 - 100
	 * @return void
	 */
	public function createImage($createPath='',$quality=NULL)
	{
		$imageType = $this->current['imageType'];
		$function = $this->allowTypes[$imageType];

		if(function_exists($function)) {
			!$createPath AND $createPath = $this->set['createPath'];
			is_null($quality) AND $quality = $this->set['quality'];

			//imagepng ������������Χ�� 0-9 ֮�䣬�����ᱨ��
			if ($imageType == 'png' AND (!$quality OR $quality > 9)) $quality = 9;

			call_user_func_array($function,array($this->resource,$createPath,$quality));

			return imagedestroy($this->resource);// �ͷ�

		} else {
			return FALSE;
		}
	}

	/**
	 * ��ȡ��Դ���ͼ��ĳߴ�
	 *
	 * @param resource $srcHandle
	 * @return
	 */
	public function getImageSize($src=NULL)
	{
		if(empty($src)) {
			$src =& $this->resource;
		}
		$size = array(
			$this->getImageWidth($src),
			$this->getImageHeight($src)
		);
		list($size['w'],$size['h']) = $size;
		return $size;
	}

	/**
	 * ��ȡ��Դ���ͼ����
	 *
	 * @param resource $src
	 * @return float|int
	 */
	public function getImageWidth($src)
	{
		return imagesx($src);
	}

	/**
	 * ��ȡ��Դ���ͼ��߶�
	 *
	 * @param resource $src
	 * @return float|int
	 */
	public function getImageHeight($src)
	{
		return imagesy($src);
	}

	/**
	 * ��ȡͼ��������չ��
	 *
	 * @param string $filepath
	 * @return string
	 */
	public function getImageType($filepath)
	{
		$typeList = array(
			1 => 'gif',
			2 => 'jpg',
			3 => 'png',
			//4 => 'swf',
			//5 => 'psd',
			6 => 'bmp',
			15 => 'wbmp'
		);

		$imgInfo = @getimagesize($filepath);
		return isset($typeList[$imgInfo[2]]) ? $typeList[$imgInfo[2]] : FALSE;
	}

	/**
	 * ���ͼƬ�����Ƿ�Ϸ�
	 *
	 * @param string $imageExt
	 * @return bool
	 */
	private function checkValid($imageExt)
	{
		return array_key_exists($imageExt,$this->allowTypes);
	}

	/**
	 * ���ͼƬ�ļ��Ƿ����
	 *
	 * @param mixed $path
	 * @return void
	 */
	private function checkFileExists($path)
	{
		if(!file_exists($path)) {
			showError('Source File: '.$path.' doesn\'t exists!');
		}
	}

	/**
	 * ������ɫ
	 *
	 * @param string $color ʮ��������ҳ��ʽ��ɫ
	 * @return array
	 */
	private function parseColor($color)
	{
		$arr = array();
		$l = strlen($color);
		for($i=1; $i<$l; $i++) {
			$arr[] = hexdec(substr($color,$i,2));
			$i++;
		}
		return $arr;
	}

	/**
	 * ������ͼ�����ڻ���
	 *
	 * @param int $width
	 * @param int $height
	 * @return Image Resource
	 */
	private function imageCreate($width,$height) {
		if ($this->current['imageType'] == 'gif') {
			if ($this->set['transparency']) {
				$newIm = imagecreate($width,$height);
				$color = imagecolorallocate($newIm,$this->current['trans']['red'],$this->current['trans']['green'],$this->current['trans']['blue']);
				imagefill($newIm,0,0,$color);
				imagecolortransparent($newIm,$color);
			} else {
				$newIm = imagecreatetruecolor($width,$height);
			}
		} else {
			$newIm = imagecreatetruecolor($width,$height);
			if ($this->current['imageType'] == 'png') {
				imagealphablending($newIm,FALSE);
				imagesavealpha($newIm,TRUE);
			}
		}

		return $newIm;
	}

}

//#