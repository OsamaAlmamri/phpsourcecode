<?php 
$tag=self::getPairTag(self::CONTAINER);
$id=$tag['tagId'];
$atts=$tag['atts'];
$xtype=self::CONTAINER;
?>
<?php ##注册事件监听器 ?>
<?php require dirname(__FILE__).'/subvm/listeners.php';?>
<?php ##Component定义 ?>
var <?php echo $id?>_cfg = {
<?php require dirname(__FILE__).'/common/containerTagSupport.php';?>
	app: 169
};
<?php ##Component实例化?>
var <?php echo $id?> = Ext.create('Ext.container.Container', <?php echo $id?>_cfg);
<?php ##注册Items子组件?>
<?php require dirname(__FILE__).'/subvm/items.php';?>
<?php ##组件常用事件绑定 ?>
<?php require dirname(__FILE__).'/../subvm/events.php';?>