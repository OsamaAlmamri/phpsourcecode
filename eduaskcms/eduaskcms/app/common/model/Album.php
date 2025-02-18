<?php
namespace app\common\model;

class Album extends App
{
    public $display ='title';  
   	public $parentModel  ='Menu';
    
    public $assoc   = array(
        'Menu'=>array(
            'type'=>'belongsTo'
        ),
        'User'=>array(
            'type'=>'belongsTo'
        ),
        'AlbumPicture'=>array(
            'type'=>'hasMany',
            'foreignKey'=>'foreign_id',
            'where'=>['module'=>'AlbumPicture']
        )
    );
    
     public function initialize()
     {
        $this->form = array(
            'id'=>array(
				'type'=>'integer',
				'name'=>'ID',
				'elem'=>'hidden',
			),			
			'menu_id'=>array(
				'type'=>'integer',
				'name'=>'所属栏目',
				'elem' => 'nest_select.Menu',
                'foreign'=>'Menu.title',
				'list'=>'assoc'
			),
            'user_id'=>array(
				'type'=>'integer',
				'name'=>'所属用户',
                'foreign'=>'User.username',
				'elem'=>0,
				'list'=>'assoc'
			),
            'title'=>array(
				'type'=>'string',
				'name'=>'标题',
				'elem'=>'text.title',
				'list'=>'show',
			),
            'ex_title'=>array(
				'type'=>'string',
				'name'=>'副标题',
				'elem'=>'text',
				'list'=>'show',
			),
			'date'=>array(
				'type'=>'date',
				'name'=>'发布日期',
				'elem'=>'date',
				'list'=>'date',
			),
			
            
            'image'=>array(
				'type'=>'string',
				'name'=>'封面图片',
				'elem'=>'image.upload',
				'list'=>'image',
				'image'=>array(
					'thumb'=>array(
						'field'=>'thumb'
					),
				),
                'upload'=>array(
                    'maxSize'=>2048*1024,
                    'validExt'=>array('jpg','png','gif')
                )
			),
            'thumb'=>array(
				'type'=>'string',
				'name'=>'缩略图',
				'elem'=>0,
				'list'=>0,
			),
            'view_style' => array(
                'type' => 'string',
                'name' => '显示样式',
                'elem' => 'select',
                'options' => [
                    'simpleLide' => '样式一',
                    'lightGallery' => '样式二'
                ]
            ),
            'is_verify'=>array(
				'type'=>'boolean',
				'name'=>'审核',
				'elem'=>'checker',
				'list'=>'checker',
                'sortable' => true
			),
            'is_title'=>array(
				'type'=>'boolean',
				'name'=>'显示标题',
				'elem'=>'checker',
				'list'=>'checker',
			),
            'is_content'=>array(
				'type'=>'boolean',
				'name'=>'显示内容',
				'elem'=>'checker',
				'list'=>'checker',
			),
            'content'=>array(
                'type'=>'text',
                'name'=>'内容',
                'elem'=>'editor',
                'length'=>80,
                'list'=>0,
                'auto_img'=>true
            ),
            'visit_count'=>array(
				'type'=>'integer',
				'name'=>'访问计数',
				'elem'=>0,
				'list'=>'show',
			),
            'summary'=>array(
				'type'=>'text',
				'name'=>'摘要说明',
				'elem'=>'textarea',
				'list'=>'show',
                'elem_group'=>'advanced',
			),
            'link'=>array(
				'type'=>'string',
				'name'=>'外部链接',
				'elem'=>'text',
				'list'=>'show',
                'elem_group'=>'advanced',
			),
            'is_index'=>array(
				'type'=>'boolean',
				'name'=>'首页优先',
				'elem'=>'checker',
				'list'=>'checker',
                'elem_group'=>'advanced',
			),
			'is_recommend'=>array(
				'type'=>'boolean',
				'name'=>'推荐',
				'elem'=>'checker',
				'list'=>'checker',
                'elem_group'=>'advanced',
			),
            'created'=>array(
				'type'=>'datetime',
				'name'=>'添加时间',
				'elem'=>0,
				'list'=>'datetime',
                'elem_group'=>'advanced',
			),
			'modified'=>array(
				'type'=>'datetime',
				'name'=>'修改时间',
				'elem'=>0,
				'list'=>'datetime',
                'elem_group'=>'advanced',
			),
			'list_order'=>array(
				'type'=>'integer',
				'name'=>'排序权重',
				'elem'=>'number',
				'list'=>'show',
                'elem_group'=>'advanced',
			),
            'picture_count'=>array(
				'type'=>'integer',
				'name'=>'图片数量',
				'elem'=>0,
				'list'=>'counter',
				'counter'=>'AlbumPicture',
                'elem_group'=>'advanced',
			),
			'keywords'=>array(
				'type'=>'string',
				'name'=>'SEO关键字',
				'elem'=>'text',
                'elem_group'=>'advanced',
			),
			'description'=>array(
				'type'=>'string',
				'name'=>'SEO描述',
				'elem'=>'textarea',
                'elem_group'=>'advanced',
			)
        );
        
        call_user_func_array(array('parent',__FUNCTION__),func_get_args());
     }
    
    public $formGroup = array(
        'advanced'=>'高级选项'
    );
    
    protected $validate = array(
        'title'=>array(
            'rule'=>'require',
            'message'=>'请填写标题'
        ),
        'menu_id'=>array(
            array(
                'rule'=>array('egt',1),
                'message'=>'请选择父级导航'
            ),
            array(
                'rule'=>array('call','checkTypeOfMenu')
            )
        )
    );
}
