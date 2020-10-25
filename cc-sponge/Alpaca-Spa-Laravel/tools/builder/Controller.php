<?php

namespace Tools\Builder;

use Tools\Builder\View\View;
use Illuminate\Support\Facades\DB;

class Controller
{
    public $ignoreField = ['update_at', 'audit_at', 'create_at', 'delete_at', 'creator', 'updater', 'ip', 'level'];

    public function index()
    {
        $view         = View::tbl('index', ['name' => 'test']);
        $view->layout = false;
        echo $view->html();
        die();
    }

    public function view()
    {
        $view         = View::tbl('view', ['name' => 'test']);
        $view->layout = false;
        echo $view->html();
        die();
    }

    public function builder()
    {

        $table_name           = request()->input('table_name');
        $table_prefix         = request()->input('table_prefix', 'jjj_');
        $table_name_cn        = request()->input('table_name_cn');
        $sub_module_name      = request()->input('sub_module_name');
        $b_module_name        = request()->input('b_module_name', 'main');
        $f_module_name        = request()->input('f_module_name', 'admin');
        $is_init_model        = request()->input('is_init_model');
        $is_init_b_controller = request()->input('is_init_b_controller');
        $is_init_b_router     = request()->input('is_init_b_router');
        $is_init_list         = request()->input('is_init_list');
        $is_init_edit         = request()->input('is_init_edit');
        $is_init_f_controller = request()->input('is_init_f_controller');
        $is_init_inter        = request()->input('is_init_inter');
        $is_auto_copy         = request()->input('is_auto_copy', false);
        $is_md_list           = request()->input('is_md_list', false);
        $is_md_edit           = request()->input('is_md_edit', false);

        if (empty($table_name)) {
            die('Table Name Is Null!');
        }

        if (empty($table_name_cn)) {
            $table_name_cn = $table_name;
        }

        $orgName = $table_name;
        $table   = str_replace(' ', '', $orgName);

        $className  = ucwords(str_replace(array('.', '-', '_'), ' ', $table));
        $moduleName = explode(',', $className)[0];
        $moduleName = str_replace(' ', '', $moduleName);

        if (!empty($sub_module_name)) {
            $moduleName = $sub_module_name;
        }

        $b_module_name = ucwords($b_module_name);
        $f_module_name = ucwords($f_module_name);

        $b_module_name_lc = lcfirst($b_module_name);
        $f_module_name_lc = lcfirst($f_module_name);

        $className    = str_replace(' ', '', $className);
        $classNameLc  = lcfirst($className);
        $moduleNameLc = lcfirst($moduleName);
        $result       = DB::select("SHOW FULL COLUMNS FROM " . $table_prefix . $table);

        $fields = [];
        foreach ($result as $r) {

            if (in_array($r->Field, $this->ignoreField)) {
                continue;
            }

            $field            = [];
            $field['db_type'] = $r->Type;
            $field['field']   = $r->Field;
            $field['comment'] = $r->Comment;
            $field['in_type'] = 'string';
            $field['in_name'] = $r->Comment;

            $comment            = str_replace('，', ',', $r->Comment);
            $comment            = preg_replace("# #", '', $comment);
            $array              = explode(',', $comment);
            $field['in_common'] = $array[0];

            if (!empty($array[1])) {
                $typeContent = explode('|', $array[1]);
                switch ($typeContent[0]) {
                    case '枚举':
                        $field['in_type'] = 'enum';
                        $in_value         = [];
                        foreach ($typeContent as $index => $typeContentValue) {
                            if ($index == 0) {
                                continue;
                            }
                            $key             = explode('-', $typeContentValue);
                            $value           = [];
                            $value['value']  = $key[0];
                            $value['label']  = (isset($key[1]) ? $key[1] : $key[0]);
                            $value['key']    = $field['field'] . "_" . (isset($key[2]) ? $key[2] : (isset($key[1]) ? $key[1] : $key[0]));
                            $value['common'] = '//' . $field['in_common'] . ":" . (isset($key[1]) ? $key[1] : $key[0]);
                            $value['const']  = 'const ' . strtoupper($value['key']) . ' = ' . $key[0] . ';    ' . $value['common'];
                            array_push($in_value, $value);
                        }
                        $field['in_value'] = $in_value;
                        break;
                    case '开关':
                        $field['in_type'] = 'switch';
                        $in_value         = [];
                        foreach ($typeContent as $index => $typeContentValue) {
                            if ($index == 0) {
                                continue;
                            }
                            $key             = explode('-', $typeContentValue);
                            $value           = [];
                            $value['value']  = $key[0];
                            $value['label']  = (isset($key[1]) ? $key[1] : $key[0]);
                            $value['key']    = $field['field'] . "_" . (isset($key[2]) ? $key[2] : (isset($key[1]) ? $key[1] : $key[0]));
                            $value['common'] = '//' . $field['in_common'] . ":" . (isset($key[1]) ? $key[1] : $key[0]);
                            $value['const']  = 'const ' . strtoupper($value['key']) . ' = ' . $key[0] . ';    ' . $value['common'];
                            array_push($in_value, $value);
                        }
                        $field['in_value'] = $in_value;
                        break;
                    case '富文本':
                        $field['in_type'] = 'rtext';
                        break;
                    default: {
                        $field['in_type'] = 'string';
                        break;
                    }
                }
            }

            array_push($fields, $field);
        }

        $viewResult = [];

        //创建目录
        $filePath = base_path() . "\\tools\\Builder\\output\\{$table}\\";
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $data                 = [];
        $data['tableName']    = $table_prefix . $table;
        $data['fields']       = $fields;
        $data['className']    = $className;
        $data['moduleName']   = $moduleName;
        $data['classNameLc']  = $classNameLc;
        $data['moduleNameLc'] = $moduleNameLc;

        $data['b_module_name']    = $b_module_name;
        $data['f_module_name']    = $f_module_name;
        $data['b_module_name_lc'] = $b_module_name_lc;
        $data['f_module_name_lc'] = $f_module_name_lc;

        $data['table_name_cn'] = $table_name_cn;

        $data['orgName'] = $orgName;

        //创建 - model
        if (!empty($is_init_model)) {
            $view         = View::tbl('model', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_M';

            $resultItem             = [];
            $resultItem['title']    = 'model-后端';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
            /*拷贝到指定目录*/
            /*后端模型，目录*/
            if ($is_auto_copy) {
                $models_dir       = base_path() . "\\app\\Models\\";
                $models_file_name = $className;
                if (!file_exists($models_dir . $models_file_name . '.php')) {
                    copy($fileName, $models_dir . $models_file_name . '.php');
                } else {
                    copy($fileName, $models_dir . $models_file_name . '');
                }
            }
        }

        //创建 - controller
        if (!empty($is_init_b_controller)) {
            $view         = View::tbl('controller', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_Controller';

            $resultItem             = [];
            $resultItem['title']    = 'Controller-后端';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
            /*拷贝到指定目录*/
            /*后端Controller，目录*/
            if ($is_auto_copy) {
                $controller_dir       = base_path() . "\\app\\Http\\Controllers\\{$b_module_name}\\";
                $controller_file_name = $className . 'Controller';
                if (!file_exists($controller_dir . $controller_file_name . '.php')) {
                    copy($fileName, $controller_dir . $controller_file_name . '.php');
                } else {
                    copy($fileName, $controller_dir . $controller_file_name . '');
                }
            }
        }

        //创建 - 后台路由
        if (!empty($is_init_b_router)) {
            //创建 - Router
            $view         = View::tbl('router', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_Router';

            $resultItem             = [];
            $resultItem['title']    = 'router_路由-后端';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
            /*拷贝到指定目录*/
            /*前端Controller，目录*/
            if ($is_auto_copy) {
                $router_name = base_path() . "\\routes\\main.php";;
                if (!file_exists($router_name)) {

                } else {
                    file_put_contents($router_name, $html, FILE_APPEND | LOCK_EX);
                }
            }
        }

        //创建 - list
        if (!empty($is_init_list)) {
            //创建 - list_view
            $view         = View::tbl('list_view', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_ListView';

            $resultItem             = [];
            $resultItem['title']    = 'list页面-前端';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
            /*拷贝到指定目录*/
            /*前端Controller，目录*/
            if ($is_auto_copy) {
                $display_dir = base_path() . "\\public\\{$f_module_name_lc}\\main\\view\\" . $classNameLc . "\\";
                if (!is_dir($display_dir)) {
                    mkdir($display_dir, 0777, true);
                }
                $display_file_name = $classNameLc . 'ListView';
                if (!file_exists($display_dir . $display_file_name . '.html')) {
                    copy($fileName, $display_dir . $display_file_name . '.html');
                } else {
                    copy($fileName, $display_dir . $display_file_name . '');
                }
            }

            //创建 - list_display
            $view         = View::tbl('list_display', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_ListDisplay';

            $resultItem             = [];
            $resultItem['title']    = 'list-table页面-前端';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
            /*拷贝到指定目录*/
            /*前端Controller，目录*/
            if ($is_auto_copy) {
                $display_dir = base_path() . "\\public\\{$f_module_name_lc}\\main\\view\\" . $classNameLc . "\\";
                if (!is_dir($display_dir)) {
                    mkdir($display_dir, 0777, true);
                }
                $display_file_name = $classNameLc . 'ListDisplay';
                if (!file_exists($display_dir . $display_file_name . '.html')) {
                    copy($fileName, $display_dir . $display_file_name . '.html');
                } else {
                    copy($fileName, $display_dir . $display_file_name . '');
                }
            }
        }

        //创建 - Edit-html
        if (!empty($is_init_edit)) {
            $view         = View::tbl('edit_html', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_EditView';

            $resultItem             = [];
            $resultItem['title']    = 'edit-编辑页面-前端';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
            /*拷贝到指定目录*/
            /*前端Controller，目录*/
            if ($is_auto_copy) {
                $edit_dir = base_path() . "\\public\\{$f_module_name_lc}\\main\\view\\" . $classNameLc . "\\";
                if (!is_dir($edit_dir)) {
                    mkdir($edit_dir, 0777, true);
                }
                $edit_file_name = $classNameLc . 'EditView';
                if (!file_exists($edit_dir . $edit_file_name . '.html')) {
                    copy($fileName, $edit_dir . $edit_file_name . '.html');
                } else {
                    copy($fileName, $edit_dir . $edit_file_name . '');
                }
            }
        }

        //创建 - Controller - JS
        if (!empty($is_init_f_controller)) {
            $view         = View::tbl('controller_js', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_Controller_JS';

            $resultItem             = [];
            $resultItem['title']    = 'controller_js-前端';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
            /*拷贝到指定目录*/
            /*前端Controller，目录*/
            if ($is_auto_copy) {
                $controller_js_dir       = base_path() . "\\public\\{$f_module_name_lc}\\main\\controller\\";
                $controller_js_file_name = $classNameLc . '';
                if (!file_exists($controller_js_dir . $controller_js_file_name . '.js')) {
                    copy($fileName, $controller_js_dir . $controller_js_file_name . '.js');
                } else {
                    copy($fileName, $controller_js_dir . $controller_js_file_name . '');
                }
            }

        }

        //创建 - config_js
        if (!empty($is_init_inter)) {
            $view         = View::tbl('config_js', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_Config_js';

            $resultItem             = [];
            $resultItem['title']    = 'config_接口配置文件-前端';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
        }

        //文档md - 列表
        if (!empty($is_md_list)) {
            $view         = View::tbl('doc_list', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_md_list';

            $resultItem             = [];
            $resultItem['title']    = 'md_文档-列表';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
        }

        //文档md - 编辑
        if (!empty($is_md_edit)) {
            $view         = View::tbl('doc_edit', $data);
            $view->layout = false;
            $html         = $view->html();
            $fileName     = $filePath . $className . '_md_edit';

            $resultItem             = [];
            $resultItem['title']    = 'md_文档-编辑';
            $resultItem['fileName'] = $fileName;
            array_push($viewResult, $resultItem);

            file_put_contents($fileName, $html, LOCK_EX);
        }


        //显示结果
        $result['result'] = $viewResult;
        $view             = View::tbl('result', $result);
        $view->layout     = false;
        echo $view->html();
        die();
    }
}
