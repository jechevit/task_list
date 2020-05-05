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
        $this->form = new TagsForm();
        $this->tags = $this->task->tags;
    }

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

    private function renderTags()
    {
        $tags = '';
        foreach ($this->tags as $tag) {
            $tags .= $this->renderTag($tag);
        }
        return $tags;
    }

    private function renderTag(Tag $tag)
    {
        $view = '<span class="badge">#';

        $view .= $tag->name;

        if ($this->task->isInWork()) {
            $view .= $this->deleteButton($tag->id);
        }
        $view .= '</span>';

        if ($this->task->isInWork()){
            return Html::a($view, ['index'], $this->labelClass);
        } else {
            return Html::tag('div', $view, $this->labelClass);

        }
    }

    private function deleteButton(int $tagId)
    {
        return Html::a(
            '<span aria-hidden="true" class="glyphicon glyphicon-remove"></span>',
            ['delete-tag', 'id' => $this->task->id, 'tagId' => $tagId],
            ['title' => 'Открепить тег']
        );
    }

    private function addTag()
    {
        return '&nbsp;&nbsp;' . $this->render('add-tag', [
                'model' => $this->form,
                'task' => $this->task
            ]);
    }
}