<?php
$atts=self::resolveAtts($atts);
$id=self::getUUID4Tag(isset($atts['id'])?$atts['id']:null,true,self::RADIOBOXFIELD);

$xtype=self::RADIOBOXFIELD;
?>
<?php ##注册事件监听器 ?>
<?php require dirname(__FILE__).'/../subvm/listeners.php';?>

<?php ##RadioboxField定义?>
var <?php echo $id?>_cfg = {
<?php require dirname(__FILE__).'/../common/formItemTagSupport.php';?>
<?php if(isset($atts['boxLabel'])){?>
boxLabel:'<?php echo $atts['boxLabel']?>',
<?php }?>
<?php if(isset($atts['boxLabelAlign'])){?>
boxLabelAlign:'<?php echo $atts['boxLabelAlign']?>',
<?php }?>
<?php if(isset($atts['inputValue'])){?>
inputValue:'<?php echo $atts['inputValue']?>',
<?php }?>
<?php if(isset($atts['checked'])){?>
checked:<?php echo $atts['checked']?>,
<?php }?>
    app:169	
};
<?php ##RadioboxField实例化?>
<?php if($atts['instance'] == "true"){?>
var <?php echo $id?> = Ext.create('Ext.form.field.Radio',<?php echo $id?>_cfg);
<?php }?>

<?php ##组件常用事件绑定 ?>
<?php require dirname(__FILE__).'/../subvm/events.php';?>