<?php


namespace core\readModels;


use core\entities\Task;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class TaskReadRepository
{
    public function getAll()
    {
        $query = Task::find();
        return $this->getProvider($query, false);
    }

    private function getProvider(ActiveQuery $query, $pageSize): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'priority' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
    }
}