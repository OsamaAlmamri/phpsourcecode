<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parentid
 * @property string $name
 * @property integer $level
 * @property string $key
 * @property string $gourl
 * @property string $tpl_list
 * @property string $tpl_show
 * @property string $seo_keywords
 * @property string $seo_description
 * @property integer $sorting
 * @property integer $status
 */
class Category extends \wskm\db\AdminAR
{
    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }
	
	public static function getListStatus()
	{
		return \wskm\Status::getEnableOrDisable();
	}
	
	public function primaryName() {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentid', 'sorting', 'status', 'level'], 'integer'],
            [['name', 'key'], 'required'],
            [['name', 'key', 'gourl', 'tpl_list', 'tpl_show', 'seo_keywords', 'seo_description'], 'string', 'max' => 255],
			[['sorting'], 'integer', 'max' => 99],
			['sorting', 'default', 'value' => 0],
			[['name'], 'unique'],
			[['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('category', 'ID'),
            'parentid' => Yii::t('category', 'Parent category'),
            'name' => Yii::t('app', 'Name'),
            'key' => Yii::t('category', 'Key'),
            'gourl' => Yii::t('category', 'Go Url'),
            'tpl_list' => Yii::t('category', 'Tpl List'),
            'tpl_show' => Yii::t('category', 'Tpl Show'),
            'sorting' => Yii::t('app', 'Sorting'),
            'seo_keywords' => Yii::t('admin', 'Seo Keywords'),
            'seo_description' => Yii::t('admin', 'Seo Description'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

}
