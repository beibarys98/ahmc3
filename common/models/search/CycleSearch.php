<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Cycle;

/**
 * CycleSearch represents the model behind the search form of `common\models\Cycle`.
 */
class CycleSearch extends Cycle
{
    public $course_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'course_id'], 'integer'],
            [['title_kz', 'title_ru', 'month', 'duration', 'course_title'], 'safe'],
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
        $query = Cycle::find()->joinWith('course');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['course_title'] = [
            'asc' => ['course.title_kz' => SORT_ASC],
            'desc' => ['course.title_kz' => SORT_DESC],
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'cycle.id' => $this->id,
            'course_id' => $this->course_id,
        ]);

        $query->andFilterWhere(['like', 'course.title_kz', $this->course_title])
            ->andFilterWhere(['like', 'cycle.title_kz', $this->title_kz])
            ->andFilterWhere(['like', 'cycle.title_ru', $this->title_ru])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'duration', $this->duration]);

        return $dataProvider;
    }
}
