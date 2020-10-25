<?php

/*
�������÷���

��������
$imagehandler->set($configArray);

���ü�ʹ�÷�������

����ͼ

����

ˮӡͼ

ˮӡ����



*/

/**
 * ͼƬ������ʾ������
 *
 * @package VgotFaster
 * @author pader
 * @copyright 2010
 * @version $Id$
 * @access public
 */
class ImageController extends Controller {

	var $srcImage = 'app/data/demo.jpg';
	var $toImage = 'app/data/demo_maked.jpg';
	var $toImage2 = 'app/data/ee.jpg';
	var $imagehandler;
	var $image;

	function __construct()
	{
		parent::Controller();
		$this->load->library('imagehandler');
		$this->load->library('image');

		if(!file_exists($this->srcImage)) {
			echo '��ʾͼƬ�ļ������ڣ����� app/data Ŀ¼����һ�� demo.jpg �ļ�<br />';
			exit;
		}

	}

	function index()
	{
		echo '<h3>ͼ��������ʾ</h3>';
		echo anchor('test/image/MakeThumb','��������ͼ'),'<br />';
		echo anchor('test/image/MakeCut','ͼƬ����'),'<br />';
		echo anchor('test/image/mineMake','���ؿ�');
	}

	function MakeThumb()
	{
		$this->imagehandler->setCutType(0);
		$this->imagehandler->setSrcImg($this->srcImage);
		$this->imagehandler->setDstImg($this->toImage);
		//$this->imagehandler->setDstImgBorder(1);
		$r = $this->imagehandler->createImg(800,500);

		echo $r ? 'ͼƬ��С�ɹ�' : 'ͼƬ��Сʧ��';
		echo '<p><img src="'.siteUrl('test/image/show').'" />';
	}

	function MakeCut()
	{
		$this->imagehandler->setCutType(2);
		$this->imagehandler->setSrcImg($this->srcImage);
		$this->imagehandler->setRectangleCut(817,703);
		$this->imagehandler->setSrcCutPosition(395,106);
		//$this->imagehandler->setDstImgBorder(10,'#FF00FF');
		//$this->imagehandler->flipH();
		$this->imagehandler->setDstImg($this->toImage);
		$r = $this->imagehandler->createImg(817,703);

		echo $r ? 'ͼƬ���гɹ�' : 'ͼƬ��ʧ��';
		echo '<p><img src="'.siteUrl('test/image/show').'" /></p>';
	}

	function mineMake()
	{
        $this->image->open($this->srcImage);

		//$this->image->resize(380,'auto');
		$this->image->crop(200,200,300,280);
		//$this->image->drawBorder(1,'#000000');


		$r = $this->image->createImage($this->toImage);

		echo $r ? 'ͼƬ����ɹ�' : 'ͼƬ����ʧ��';

		echo '<p><img src="'.siteUrl('test/image/show').'" /></p>';
	}

	function demoSharp()
	{
		$this->image->open($this->toImage);
		$this->image->sharp(1);
		$r = $this->image->createImage($this->toImage2);
	}

	function demoRotate()
	{
		$this->image->open('app/data/m.jpg');
		$this->image->corner(50,TRUE);
		$this->image->resize(200,'auto');
		$r = $this->image->createImage($this->toImage2);
	}

	function fixtransparency()
	{
		$this->image->initialize('transparency',TRUE);

		$this->image->open('static/example.jpg');
		$this->image->resize(290);
		$this->image->save('static/example_result.jpg');

		$this->image->open('static/example.png');
		$this->image->resize(200);
		$this->image->save('static/example_result.png');

		$this->image->open('static/example.png');
		$this->image->resize(120);
		$this->image->save('static/example_result_2.png');

		$base = baseUrl();

		echo "<div style='background:url({$base}static/canvasbg.gif);text-align:center;padding:10px;'>
			<p>GIF</p><p><img src='{$base}static/example_result.jpg' /></p>
			<p>PNG</p><p><img src='{$base}static/example_result.png' /></p>
			<p>PNG</p><p><img src='{$base}static/example_result_2.png' /></p>
			</div>";
	}

	/**
	 * Ԥ��ͼƬ���ɽ��
	 *
	 * @return void
	 */
	function show()
	{
		$image = file_get_contents($this->toImage);
		echo $image;
		unset($image);
	}

	function qrcode()
	{
		$this->load->library('qrcode');
		QRcode::png('http://vgotfaster.googlecode.com/');
	}

	public function convert()
	{
		$this->image->initialize(array('quality'=>90));

		$this->image->open(APPLICATION_PATH.'/data/test.gif', 'jpg');
		$this->image->save(APPLICATION_PATH.'/data/test_convert.jpg');
	}

	public function drawText() {
		$this->image->open(APPLICATION_PATH.'/data/000.jpg');

		$this->image->markImage(APPLICATION_PATH.'/data/mark.png');

		$this->image->save(APPLICATION_PATH.'/data/000_1.jpg');
	}

}
