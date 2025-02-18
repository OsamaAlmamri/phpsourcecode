<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AuthPermissionSearch represents the model behind the search form of `app\models\AuthPermission`.
 */
class AuthPermissionSearch extends AuthPermission
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','child', 'parent', 'description', 'rule_name', 'data'], 'safe'],
            [['type', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AuthPermission::find()->select('*');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'data', $this->data]);
            
        $query->leftJoin(AuthItemChild::tableName(),self::tableName().'.name = ' . AuthItemChild::tableName() . '.child')
        ->andFilterWhere(['parent'=>isset($params['parent'])?$params['parent']:null]);

        return $dataProvider;
    }
}
