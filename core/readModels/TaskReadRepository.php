<?php


namespace core\readModels;


use core\entities\Task;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class TaskReadRepository
{
    const PAGE_SIZE = 25;

    public function getAll()
    {
        $query = Task::find()->orderBy(['current_status' => SORT_ASC]);
        return $this->getProvider($query, self::PAGE_SIZE);
    }

    private function getProvider(ActiveQuery $query, int $pageSize): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'priority' => SORT_ASC,
                ],
            ],
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
    }
}