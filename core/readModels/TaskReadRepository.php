<?php

namespace core\readModels;

use core\entities\Tag;
use core\entities\Task;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;

class TaskReadRepository
{
    const PAGE_SIZE = 15;

    /**
     * @return DataProviderInterface
     */
    public function getAll(): DataProviderInterface
    {
        $query = Task::find()->orderBy(['current_status' => SORT_ASC]);
        return $this->getProvider($query, self::PAGE_SIZE);
    }

    /**
     * @param Tag $tag
     * @return DataProviderInterface
     */
    public function getAllByTag(Tag $tag): DataProviderInterface
    {
        $query = Task::find()->alias('t');
        $query->joinWith(['tagAssignments ta'], false);
        $query->andWhere(['ta.tag_id' => $tag->id]);
        $query->orderBy(['current_status' => SORT_ASC]);

        return $this->getProvider($query, self::PAGE_SIZE);
    }

    /**
     * @param ActiveQuery $query
     * @param int $pageSize
     * @return ActiveDataProvider
     */
    private function getProvider(ActiveQuery $query, int $pageSize): ActiveDataProvider
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