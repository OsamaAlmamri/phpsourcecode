<?php
// +----------------------------------------------------------------------
// | Author: Zaker <49007623@qq.com>
// +----------------------------------------------------------------------

//配置文件

return [
    
    /* 模板常量替换配置 */
		'view_replace_str'  =>  [
				'__ROOT__' => WEB_URL,
				'__INDEX__' => WEB_URL . '/index.php',
				'__UPLOAD__' => WEB_URL . '/uploads',
				'__PUBLIC__' =>WEB_URL. '/public',
					
		],
    
    /* 带分页接口附加字段 */
    'page_attach_field' => [
            [
                'field_name'        => 'page',
                'data_type'         => '字符',
                'is_require'        => '否',
                'field_describe'    => "访问页码【分页附加参数】",
            ],
            [
                'field_name'        => 'list_rows',
                'data_type'         => '字符',
                'is_require'        => '否',
                'field_describe'    => "每页记录数量【分页附加参数】",
            ],
    ],
    
    /* 带user_token接口附加字段 */
    'user_token_attach_field' => [
        'field_name'        => 'user_token',
        'data_type'         => '字符',
        'is_require'        => '是',
        'field_describe'    => "用户Token【Token附加参数】",
    ],
    
    /* access_token 附加字段 */
    'access_token_attach_field' => [
        'field_name'        => 'access_token',
        'data_type'         => '字符',
        'is_require'        => '是',
        'field_describe'    => "访问Token【Token附加参数】",
    ],
    
    /* data_sign 附加字段 */
    'data_sign_attach_field' => [
        'field_name'        => 'data_sign',
        'data_type'         => '字符',
        'field_describe'    => "数据签名【数据验证附加字段】",
    ],
];
