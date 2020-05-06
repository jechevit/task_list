<?php


namespace frontend\widgets;


use core\entities\Tag;
use core\entities\Task;
use core\forms\TagsForm;
use yii\base\Widget;
use yii\helpers\Html;

class TagsWidget extends Widget
{
    /**
     * @var string[]
     */
    private $labelClass = ['class' => 'label label-light'];

    /**
     * @var Task
     */
    public $task;

    /**
     * @var Tag[]|mixed
     */
    private $tags;

    /**
     * @var TagsForm|mixed
     */
    private $form;

    public function init()
    {
        $this->form = new TagsForm($this->task);
        $this->tags = $this->task->tags;
    }

    /**
     * @return string
     */
    public function run()
    {
        $tags = '';
        if (count($this->tags) >= 1) {
            $tags .= $this->renderTags();
        }

        if ($this->task->isInWork()) {
            $tags .= $this->addTag();
        }
        return $tags;
    }

    /**
     * @return string
     */
    private function renderTags(): string
    {
        $tags = '';
        foreach ($this->tags as $tag) {
            $tags .= $this->renderTag($tag);
        }
        return $tags;
    }

    /**
     * @param Tag $tag
     * @return string
     */
    private function renderTag(Tag $tag): string
    {
        $view = '<span class="badge">#';

        $view .= $tag->name;

        if ($this->task->isInWork()) {
            $view .= $this->deleteButton($tag->id);
        }
        $view .= '</span>';

        return Html::a($view, ['task/index', 'tagId' => $tag->id], $this->labelClass);
    }

    /**
     * @param int $tagId
     * @return string
     */
    private function deleteButton(int $tagId): string
    {
        return Html::a(
            '<span aria-hidden="true" class="glyphicon glyphicon-remove"></span>',
            ['delete-tag', 'id' => $this->task->id, 'tagId' => $tagId],
            ['title' => 'Открепить тег']
        );
    }

    /**
     * @return string
     */
    private function addTag(): string
    {
        return '&nbsp;&nbsp;' . $this->render('add-tag', [
                'model' => $this->form,
                'task' => $this->task
            ]);
    }
}