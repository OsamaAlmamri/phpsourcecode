<?php

class ImglibController extends Controller {

	function __construct()
	{
		parent::Controller();
	}

	function index()
	{
		$this->title();
		
		$this->load->library('imagehandler');
		
		$this->imagehandler->setSrcImg("testfiles/test.jpg");
		$this->imagehandler->setDstImg("testfiles/new_test.jpg");
		$this->imagehandler->setMaskImg("testfiles/mark.gif");
		$this->imagehandler->setMaskPosition(1);
		$this->imagehandler->setMaskImgPct(80);
		$this->imagehandler->setDstImgBorder(4,"#dddddd");
		$this->imagehandler->createImg(1024,768); 
		
		echo '�������.';
	}

	private function title($title='ͼ���������')
	{
		echo '<title>'.$title.'</title>';
	}
	
	function mooimage()
	{
		$this->load->library('image');
		
		echo '<title>MooPHPͼ������</title>';
		
		
	}

}
