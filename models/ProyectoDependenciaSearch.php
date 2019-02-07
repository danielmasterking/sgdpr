<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProyectoDependencia;

/**
 * ProyectoDependenciaSearch represents the model behind the search form about `app\models\ProyectoDependencia`.
 */
class ProyectoDependenciaSearch extends ProyectoDependencia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fecha_apertura'], 'integer'],
            [['codigo_dependencia', 'fecha_creacion', 'usuario'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = ProyectoDependencia::find();

        // add conditions that should always apply here

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
            'id' => $this->id,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_apertura' => $this->fecha_apertura,
        ]);

        $query->andFilterWhere(['like', 'codigo_dependencia', $this->codigo_dependencia])
            ->andFilterWhere(['like', 'usuario', $this->usuario]);

        return $dataProvider;
    }
}
