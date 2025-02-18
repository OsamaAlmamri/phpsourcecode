<?php /*a:2:{s:60:"D:\phpstudy_pro\WWW\git\DSMall\app\admin\view\seo\index.html";i:1591845922;s:64:"D:\phpstudy_pro\WWW\git\DSMall\app\admin\view\public\header.html";i:1591845922;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>DSMall<?php echo htmlentities(lang('system_backend')); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="<?php echo htmlentities(ADMIN_SITE_ROOT); ?>/css/admin.css">
        <link rel="stylesheet" href="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/js/jquery-ui/jquery-ui.min.css">
        <script src="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/jquery-2.1.4.min.js"></script>
        <script src="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/jquery.validate.min.js"></script>
        <script src="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/jquery.cookie.js"></script>
        <script src="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/common.js"></script>
        <script src="<?php echo htmlentities(ADMIN_SITE_ROOT); ?>/js/admin.js"></script>
        <script src="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/js/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/js/jquery-ui/jquery.ui.datepicker-zh-CN.js"></script>
        <script src="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/perfect-scrollbar.min.js"></script>
        <script src="<?php echo htmlentities(PLUGINS_SITE_ROOT); ?>/layer/layer.js"></script>
        <script type="text/javascript">
            var BASESITEROOT = "<?php echo htmlentities(BASE_SITE_ROOT); ?>";
            var ADMINSITEROOT = "<?php echo htmlentities(ADMIN_SITE_ROOT); ?>";
            var BASESITEURL = "<?php echo htmlentities(BASE_SITE_URL); ?>";
            var HOMESITEURL = "<?php echo htmlentities(HOME_SITE_URL); ?>";
            var ADMINSITEURL = "<?php echo htmlentities(ADMIN_SITE_URL); ?>";
        </script>
    </head>
    <body>
        <div id="append_parent"></div>
        <div id="ajaxwaitid"></div>










<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo htmlentities(lang('ds_seo_set')); ?></h3>
                <h5></h5>
            </div>
            <ul class="tab-base ds-row">
                <li><a href="JavaScript:void(0);" dstype="index" class="current"><span><?php echo htmlentities(lang('seo_set_index')); ?></span></a></li>
                <li><a href="JavaScript:void(0);" dstype="group"><span><?php echo htmlentities(lang('seo_set_group')); ?></span></a></li>
                <li><a href="JavaScript:void(0);" dstype="brand"><span><?php echo htmlentities(lang('seo_set_brand')); ?></span></a></li>
                <li><a href="JavaScript:void(0);" dstype="point"><span><?php echo htmlentities(lang('seo_set_point')); ?></span></a></li>
                <li><a href="JavaScript:void(0);" dstype="article"><span><?php echo htmlentities(lang('seo_set_article')); ?></span></a></li>
                <li><a href="JavaScript:void(0);" dstype="shop"><span><?php echo htmlentities(lang('seo_set_shop')); ?></span></a></li>
                <li><a href="JavaScript:void(0);" dstype="product"><span><?php echo htmlentities(lang('seo_set_product')); ?></span></a></li>
            </ul>
        </div>
    </div>

    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom">
            <h4 title="<?php echo htmlentities(lang('ds_explanation_tip')); ?>"><?php echo htmlentities(lang('ds_explanation')); ?></h4>
            <span id="explanationZoom" title="<?php echo htmlentities(lang('ds_explanation_close')); ?>" class="arrow"></span>
        </div>
        <ul>
            <li><?php echo htmlentities(lang('seo_set_prompt')); ?></li>
            <li><?php echo htmlentities(lang('seo_set_tips1')); ?></li>
            <li><?php echo htmlentities(lang('seo_set_tips2')); ?></li>
            <li><?php echo htmlentities(lang('seo_set_tips3')); ?></li>
            <li><?php echo htmlentities(lang('seo_set_tips4')); ?></li>
            <li><?php echo htmlentities(lang('seo_set_tips5')); ?></li>
            <li><?php echo htmlentities(lang('seo_set_tips6')); ?></li>
        </ul>
    </div>

    <form method="post" name="form_index" id="form_index">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_index')); ?></dt>
                <dd>
                    <span><a>{sitename}</a></span>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[index][seo_title]" name="SEO[index][seo_title]" value="<?php echo htmlentities($seo['index']['seo_title']); ?>" class="w830" type="text"/>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[index][seo_keywords]" name="SEO[index][seo_keywords]" value="<?php echo htmlentities($seo['index']['seo_keywords']); ?>" class="w830" type="text" maxlength="200" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[index][seo_description]" name="SEO[index][seo_description]" value="<?php echo htmlentities($seo['index']['seo_description']); ?>" class="w830" type="text" maxlength="200"/>
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input class="btn" type="submit" value="<?php echo htmlentities(lang('ds_submit')); ?>"/>
                </dd>
            </dl>
        </div>
    </form>

    <form method="post" name="form_group" id="form_group">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_group')); ?></dt>
                <dd>
                    <span><a>{sitename}</a><a>{name}</a></span>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[group][seo_title]" name="SEO[group][seo_title]" value="<?php echo htmlentities($seo['group']['seo_title']); ?>" class="w830" type="text"/>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[group][seo_keywords]" name="SEO[group][seo_keywords]" value="<?php echo htmlentities($seo['group']['seo_keywords']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[group][seo_description]" name="SEO[group][seo_description]" value="<?php echo htmlentities($seo['group']['seo_description']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_group_content')); ?></dt>
                <dd>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[group_content][seo_title]" name="SEO[group_content][seo_title]" value="<?php echo htmlentities($seo['group_content']['seo_title']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[group_content][seo_keywords]" name="SEO[group_content][seo_keywords]" value="<?php echo htmlentities($seo['group_content']['seo_keywords']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[group_content][seo_description]" name="SEO[group_content][seo_description]" value="<?php echo htmlentities($seo['group_content']['seo_description']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input class="btn" type="submit" value="<?php echo htmlentities(lang('ds_submit')); ?>"/>
                </dd>
            </dl>
        </div>
    </form>


    <form method="post" name="form_brand" id="form_brand">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_brand')); ?></dt>
                <dd>
                    <span><a>{sitename}</a><a>{name}</a></span>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[brand][seo_title]" name="SEO[brand][seo_title]" value="<?php echo htmlentities($seo['brand']['seo_title']); ?>" class="w830" type="text"/>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[brand][seo_keywords]" name="SEO[brand][seo_keywords]" value="<?php echo htmlentities($seo['brand']['seo_keywords']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[brand][seo_description]" name="SEO[brand][seo_description]" value="<?php echo htmlentities($seo['brand']['seo_description']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_brand_list')); ?></dt>
                <dd>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[brand_list][seo_title]" name="SEO[brand_list][seo_title]" value="<?php echo htmlentities($seo['brand_list']['seo_title']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[brand_list][seo_keywords]" name="SEO[brand_list][seo_keywords]" value="<?php echo htmlentities($seo['brand_list']['seo_keywords']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[brand_list][seo_description]" name="SEO[brand_list][seo_description]" value="<?php echo htmlentities($seo['brand_list']['seo_description']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input class="btn" type="submit" value="<?php echo htmlentities(lang('ds_submit')); ?>"/>
                </dd>
            </dl>
        </div>
    </form>


    <form method="post" name="form_point" id="form_point">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_point')); ?></dt>
                <dd>
                    <span><a>{sitename}</a><a>{name}</a><a>{key}</a><a>{description}</a></span>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[point][seo_title]" name="SEO[point][seo_title]" value="<?php echo htmlentities($seo['point']['seo_title']); ?>" class="w830" type="text"/>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[point][seo_keywords]" name="SEO[point][seo_keywords]" value="<?php echo htmlentities($seo['point']['seo_keywords']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[point][seo_description]" name="SEO[point][seo_description]" value="<?php echo htmlentities($seo['point']['seo_description']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_point_content')); ?></dt>
                <dd>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[point_content][seo_title]" name="SEO[point_content][seo_title]" value="<?php echo htmlentities($seo['point_content']['seo_title']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[point_content][seo_keywords]" name="SEO[point_content][seo_keywords]" value="<?php echo htmlentities($seo['point_content']['seo_keywords']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[point_content][seo_description]" name="SEO[point_content][seo_description]" value="<?php echo htmlentities($seo['point_content']['seo_description']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input class="btn" type="submit" value="<?php echo htmlentities(lang('ds_submit')); ?>"/>
                </dd>
            </dl>
        </div>
    </form>

    <form method="post" name="form_article" id="form_article">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_article')); ?></dt>
                <dd>
                    <span><a>{sitename}</a><a>{article_class}</a><a>{name}</a><a>{description}</a></span>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[article][seo_title]" name="SEO[article][seo_title]" value="<?php echo htmlentities($seo['article']['seo_title']); ?>" class="w830" type="text"/>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[article][seo_keywords]" name="SEO[article][seo_keywords]" value="<?php echo htmlentities($seo['article']['seo_keywords']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[article][seo_description]" name="SEO[article][seo_description]" value="<?php echo htmlentities($seo['article']['seo_description']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_article_content')); ?></dt>
                <dd>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[article_content][seo_title]" name="SEO[article_content][seo_title]" value="<?php echo htmlentities($seo['article_content']['seo_title']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[article_content][seo_keywords]" name="SEO[article_content][seo_keywords]" value="<?php echo htmlentities($seo['article_content']['seo_keywords']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[article_content][seo_description]" name="SEO[article_content][seo_description]" value="<?php echo htmlentities($seo['article_content']['seo_description']); ?>" class="w830" type="text" />
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input class="btn" type="submit" value="<?php echo htmlentities(lang('ds_submit')); ?>"/>
                </dd>
            </dl>
        </div>
    </form>


    <form method="post" name="form_shop" id="form_shop">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_shop')); ?></dt>
                <dd>
                    <span><a>{sitename}</a><a>{shopname}</a><a>{key}</a><a>{description}</a></span>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[shop][seo_title]" name="SEO[shop][seo_title]" value="<?php echo htmlentities($seo['shop']['seo_title']); ?>" class="w830" type="text"/>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[shop][seo_keywords]" name="SEO[shop][seo_keywords]" value="<?php echo htmlentities($seo['shop']['seo_keywords']); ?>" class="w830" type="text" maxlength="200" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[shop][seo_description]" name="SEO[shop][seo_description]" value="<?php echo htmlentities($seo['shop']['seo_description']); ?>" class="w830" type="text" maxlength="200"/>
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input class="btn" type="submit" value="<?php echo htmlentities(lang('ds_submit')); ?>"/>
                </dd>
            </dl>
        </div>
    </form>


    <form method="post" name="form_product" id="form_product">
        <div class="ncap-form-default">
            <dl>
                <dt><?php echo htmlentities(lang('seo_set_product')); ?></dt>
                <dd>
                    <span><a>{sitename}</a><a>{name}</a><a>{description}</a></span>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('title')); ?></dt>
                <dd>
                    <input id="SEO[product][seo_title]" name="SEO[product][seo_title]" value="<?php echo htmlentities($seo['product']['seo_title']); ?>" class="w830" type="text"/>
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('keywords')); ?></dt>
                <dd>
                    <input id="SEO[product][seo_keywords]" name="SEO[product][seo_keywords]" value="<?php echo htmlentities($seo['product']['seo_keywords']); ?>" class="w830" type="text" maxlength="200" />
                </dd>
            </dl>
            <dl>
                <dt><?php echo htmlentities(lang('description')); ?></dt>
                <dd>
                    <input id="SEO[product][seo_description]" name="SEO[product][seo_description]" value="<?php echo htmlentities($seo['product']['seo_description']); ?>" class="w830" type="text" maxlength="200"/>
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input class="btn" type="submit" value="<?php echo htmlentities(lang('ds_submit')); ?>"/>
                </dd>
            </dl>
        </div>
    </form>
</div>
<script>
    $(document).ready(function(){
        $('#form_index').validate({
            submitHandler:function(form){
                ds_ajaxpost('form_index');
            },
        });
        $('#form_group').validate({
            submitHandler:function(form){
                ds_ajaxpost('form_group');
            },
        });
        $('#form_brand').validate({
            submitHandler:function(form){
                ds_ajaxpost('form_brand');
            },
        });
        $('#form_point').validate({
            submitHandler:function(form){
                ds_ajaxpost('form_point');
            },
        });
        $('#form_article').validate({
            submitHandler:function(form){
                ds_ajaxpost('form_article');
            },
        });
        $('#form_shop').validate({
            submitHandler:function(form){
                ds_ajaxpost('form_shop');
            },
        });
        $('#form_product').validate({
            submitHandler:function(form){
                ds_ajaxpost('form_product');
            },
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        $('.tab-base').find('a').bind('click', function() {
            $('.tab-base').find('a').removeClass('current');
            $(this).addClass('current');
            $('form').css('display', 'none');
            $('form[name="form_' + $(this).attr('dstype') + '"]').css('display', '');
            $('span[dstype="hide_tag"]').find('a').css('padding-left', '5px');
        });

        $('form').css('display', 'none');
        $('form[name="form_index"]').css('display', '');

        $('#toggmore').bind('click', function() {
            $('li[dstype="vmore"]').toggle();
        });
        $('li[dstype="vmore"]').hide();

    });
</script>