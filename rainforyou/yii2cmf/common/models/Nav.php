<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%nav}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $title
 */
class Nav extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nav}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'title'], 'required'],
            [['key', 'title'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'key' => 'key',
            'title' => '标题',
        ];
    }

    public function getActiveItem()
    {
        return $this->hasMany(NavItem::className(), ['nav_id' => 'id'])->where(['status' => 1]);
    }
}
