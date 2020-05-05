<?php


namespace frontend\widgets;


use core\entities\Tag;
use core\entities\Task;
use core\forms\TagForm;
use yii\base\Widget;
use yii\helpers\Html;

class TagsWidget extends Widget
{
    /**
     * @var Task
     */
    public $task;

    /**
     * @var Tag[]|mixed
     */
    private $tags;

    /**
     * @var TagForm|mixed
     */
    private $form;

    public function init()
    {
        $this->form = new TagForm();
        $this->tags = $this->task->tags;
    }

    public function run()
    {
        if (count($this->tags) >= 1){
            return $this->renderTags() . $this->addTag();
        }
        return $this->addTag();
    }

    private function renderTags()
    {
        $tags = '';
        foreach ($this->tags as $tag){
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

        return Html::a($view, ['index'], ['class' => 'label label-light']);
    }

    private function deleteButton(int $tagId)
    {
        return Html::a('<span aria-hidden="true" class="glyphicon glyphicon-remove"></span>', ['delete-tag', 'id' => $this->task->id, 'tagId' => $tagId]);
    }

    private function addTag()
    {
        return  '&nbsp;&nbsp;' . $this->render('add-tag', [
            'model' => $this->form,
        ]);
    }
}