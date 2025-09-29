<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserCycle;

/**
 * UserCycleSearch represents the model behind the search form of `common\models\UserCycle`.
 */
class UserCycleSearch extends UserCycle
{
    public $user_name;
    public $organization;
    public $course_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'course_id', 'cycle_id'], 'integer'],
            [['type', 'status', 'user_name', 'organization', 'course_title'], 'safe'],
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
        $query = UserCycle::find()->joinWith('user')->joinWith('course');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['user_name'] = [
            'asc' => ['user.name' => SORT_ASC],
            'desc' => ['user.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['organization'] = [
            'asc' => ['user.organization' => SORT_ASC],
            'desc' => ['user.organization' => SORT_DESC],
        ];
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
            'user_cycle.id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
            'cycle_id' => $this->cycle_id,
        ]);

        $query->andFilterWhere(['like', 'course.title_kz', $this->course_title])
            ->andFilterWhere(['like', 'user.organization', $this->organization])
            ->andFilterWhere(['like', 'user.name', $this->user_name])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
