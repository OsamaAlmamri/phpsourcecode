<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 水月居 <singliang@163.com> <http://www.zjttwgyxx.com>
// +----------------------------------------------------------------------

namespace Xbkc\Controller;
use Think\Controller;
class ExcelStudentController extends Controller{
	/*导出excel文件类
	public function __construct(){
		vendor('PHPExcel');
	}

	*/


	/*导出excel文件*/

	public function exportExcel($cid=56){
		//获取课程信息
        $curriculum=D("XbCurriculum");
        $where['year']=I('year')?I('year'):C('YEAR');
        $clist=$curriculum->field("cid, cname")->where($where)->select();
        //获取学生信息
         $map['cid'] =I('cid')?I('cid'):$cid;//获取cid
        $student=D('XbStudent');
        $order=array('class'=>'asc','sid'=>'asc');       
        $data=$student->field("class,name,sex")->order($order)->where($map)->limit(60)->select();
		//获取当前课程信息
        $cur=$curriculum->where($map)->find();	
       //设置顶行标题
	   $toptitle="天台县外国语学校校本课程学员名单";
        //设置课程标题行
       $title = "课程名称：".$cur['cname']."　教师：".$cur['teacher']."　面向年级：".$cur['grade'];
	   $title.= "　总人数：".$cur['count']."　场地：".$cur['room'];
		//dump($title);
		 //exit;
        $xlsTitle = iconv('utf-8', 'gb2312', $cid.$cur['cname']);//设置文件名称
        $fileName = $cid.$cur['cname'].date('_Ymd');//or $xlsTitle 文件名称可根据自己情况设定

        vendor("PHPExcel");  
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
		//设置当前工作表为$objActSheet
		$objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet ->setTitle($cur['cname']);//设置表标题  
        $objActSheet ->setCellValue('A1',$toptitle)->mergeCells('A1:Y1');//mergeCells合并单元格
        $objActSheet ->setCellValue('A2',$title)->mergeCells('A2:Y2');
		$objActSheet ->getStyle( 'A1:A2')->getFont()->setBold(true);//设置顶端标题为粗体
		$objActSheet ->getStyle( 'A1')->getFont()->setSize(20);
		$objActSheet ->getStyle( 'A2')->getAlignment()->setShrinkToFit(true);//设置自动缩小字体填充;
		$objActSheet ->getStyle('A1:A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//设置水平、垂直居中
		
		//设置单元格宽度
		$objActSheet ->getColumnDimension('A')->setWidth(3);//序号宽度
		$objActSheet ->getColumnDimension('B')->setWidth(11);//班级宽度
		$objActSheet ->getColumnDimension('C')->setWidth(9);//姓名宽度
		$objActSheet ->getColumnDimension('D')->setWidth(3);
		$objActSheet ->getColumnDimension('E')->setWidth(3);
		$objActSheet ->getColumnDimension('F')->setWidth(3);
		$objActSheet ->getColumnDimension('G')->setWidth(3);
		$objActSheet ->getColumnDimension('H')->setWidth(3);
		$objActSheet ->getColumnDimension('I')->setWidth(3);
		$objActSheet ->getColumnDimension('J')->setWidth(3);
		$objActSheet ->getColumnDimension('K')->setWidth(3);
		$objActSheet ->getColumnDimension('L')->setWidth(3);
		$objActSheet ->getColumnDimension('M')->setWidth(3);
		$objActSheet ->getColumnDimension('N')->setWidth(3);
		$objActSheet ->getColumnDimension('O')->setWidth(3);
		$objActSheet ->getColumnDimension('P')->setWidth(3);
		$objActSheet ->getColumnDimension('Q')->setWidth(3);
		$objActSheet ->getColumnDimension('R')->setWidth(3);
		$objActSheet ->getColumnDimension('S')->setWidth(3);
		$objActSheet ->getColumnDimension('T')->setWidth(3);
		$objActSheet ->getColumnDimension('U')->setWidth(3);
		$objActSheet ->getColumnDimension('V')->setWidth(3);
		$objActSheet ->getColumnDimension('W')->setWidth(3);
		$objActSheet ->getColumnDimension('X')->setWidth(3);
		$objActSheet ->getColumnDimension('Y')->setWidth(3);        
		$objActSheet ->getStyle('A:Y')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			                          ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//设置水平、垂直居中

		//标题行设置
        $objActSheet ->getStyle('A3')->getAlignment()->setWrapText(true);
		$objActSheet ->getStyle('D3')->getAlignment()->setWrapText(true);
		$objActSheet ->setCellValue('A3',"序号")->mergeCells("A3:A4");
        $objActSheet ->setCellValue('B3',"班级")->mergeCells("B3:B4");
        $objActSheet ->setCellValue('C3',"姓名")->mergeCells('C3:C4');
         $objActSheet ->setCellValue('D3',"性别")->mergeCells('D3:D4');
		$objActSheet ->setCellValue('E3',"周    次")->mergeCells('E3:Y3');

		$objActSheet ->setCellValue('E4',"1")->setCellValue('F4',"2")->setCellValue('G4',"3")
			->setCellValue('H4',"4")->setCellValue('I4',"5")->setCellValue('J4',"6")->setCellValue('K4',"7")
			->setCellValue('L4',"8")->setCellValue('M4',"9")->setCellValue('N4',"10")->setCellValue('O4',"11")
			->setCellValue('P4',"12")->setCellValue('Q4',"13")->setCellValue('R4',"14")->setCellValue('S4',"15")
			->setCellValue('T4',"16")->setCellValue('U4',"17")->setCellValue('V4',"18")->setCellValue('W4',"19")
			->setCellValue('X4',"20")->setCellValue('Y4',"21");


        //以下输出内容
		$baseRow = 5;      //指定插入到第5行后
        foreach($data as $k => $v){
		$key=$k+1;         //$key是输出顺序号
        $num=$baseRow+$k; //$num是循环操作行的行号
        //Excel的第A列，uid是你查出数组的键值，下面以此类推
        $objActSheet ->setCellValue('A'.$num, $key)
						              ->setCellValue('B'.$num, $v['class'])
						              ->setCellValue('C'.$num, $v['name'])
						              ->setCellValue('D'.$num, $v['sex']);
         }
		
		 /*设置页脚备注*/
		 
		 //设置备注下移几行
		 $bgRow=46;
         $objActSheet ->setCellValue('B'.$bgRow,"备注：此名单学期结束时须上交教学处")
			 ->mergeCells('B' . $bgRow.':L' . $bgRow);

/*设置表格线为细线--细边框BORDER_THICK边框是粗的*/
     $styleArray = array(  
            'borders' => array(  
               'allborders' => array(                 
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
		       ),
             ), 
           );  
    $objActSheet ->getStyle('A3:Y45')->applyFromArray($styleArray );

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        header("Content-Transfer-Encoding:binary");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');         
        $objWriter->save('php://output'); 
        exit;   
    }





	/*导入excel类未开发*/
	public function importExcel($filename,$encode='utf8'){

      echo "程序开发中………………";
      exit;


	}
}
