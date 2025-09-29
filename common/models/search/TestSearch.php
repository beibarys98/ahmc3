<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Test;

/**
 * TestSearch represents the model behind the search form of `common\models\Test`.
 */
class TestSearch extends Test
{
    public $cycle_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cycle_id'], 'integer'],
            [['title_kz', 'title_ru', 'status', 'duration', 'cycle_title'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Test::find()->joinWith(['cycle']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['cycle_title'] = [
            'asc' => ['cycle.title_kz' => SORT_ASC],
            'desc' => ['cycle.title_kz' => SORT_DESC],
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'test.id' => $this->id,
            'cycle_id' => $this->cycle_id,
            'duration' => $this->duration,
        ]);

        $query->andFilterWhere(['like', 'cycle.title_kz', $this->cycle_title])
            ->andFilterWhere(['like', 'test.title_kz', $this->title_kz])
            ->andFilterWhere(['like', 'test.title_ru', $this->title_ru])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
