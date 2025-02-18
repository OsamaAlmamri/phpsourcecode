<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {load_news} function plugin
 *
 * Type:     function<br>
 * Name:     load_news<br>
 *
 * @author   Tangqian
 * @param array                    $params   parameters
 * @param Smarty_Internal_Template $template template object
 * @return array|null
 */
function smarty_function_load_news($params, &$smarty)
{
    if (!empty($params['assign'])) {
        $orderby = 'id DESC';
        if (!empty($params['orderby'])) {
            $orderby = $params['orderby'];
        }
        $news_model = new \models\News;
        $news = $news_model->limit($params['limit'])
                        ->where($params['where'])
                        ->order($orderby)
                        ->asArray()->all();
        $smarty->assign($params['assign'], $news);
    }
}
