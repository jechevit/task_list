<?php

namespace frontend\widgets;

use core\entities\Priority;
use core\entities\Task;
use core\helpers\PriorityHelper;
use yii\base\Widget;
use yii\helpers\Html;

class IndexModerationButtons extends Widget
{
    const DIV = 'div';
    const LI = 'li';
    const UL = 'ul';
    /**
     * @var Task
     */
    public $task;
    /**
     * @var Priority|mixed
     */
    private $priority;

    public function init()
    {
        $this->priority = $this->task->getCurrentPriority();
    }

    public function run()
    {
        if ($this->task->isInWork()){
            return Html::tag(self::DIV, $this->renderButtons());
        } else {
            return Html::a('<span aria-hidden="true">&times;</span>', ['delete', 'id' => $this->task->id], ['class' => 'close', 'data-method' => 'post',]);
        }
    }

    private function renderButtons()
    {
        $statusButton = Html::a('Завершить', ['complete', 'id' => $this->task->id], ['class' => 'btn btn-success']);

        $priorityButton = $this->renderPriority();

        return $priorityButton . ' '. $statusButton;
    }

    private function renderPriority()
    {
        $button = Html::button(PriorityHelper::priorityName($this->task->getCurrentPriority()->getValue()) . ' <span class="caret"></span>', [
            'class' => "btn btn-". $this->getClass() ." dropdown-toggle",
            'data-toggle' => "dropdown",
            'aria-haspopup' => "true",
            'aria-expanded' => "false"
        ]);

        return Html::tag(
            self::DIV,
            $button. Html::tag(self::UL, $this->buttons(), ['class' => 'dropdown-menu']),
            ['class' => 'btn-group']
        );
    }

    private function buttons()
    {
        $buttons = [];
        switch ($this->priority) {
            case $this->priority->isMiddle():
                $buttons[] = $this->getButtonLow();
                $buttons[] = $this->getButtonHigh();
                break;
            case $this->priority->isHigh():
                $buttons[] = $this->getButtonLow();
                $buttons[] = $this->getButtonMiddle();
                break;
            default:
                $buttons[] = $this->getButtonMiddle();
                $buttons[] = $this->getButtonHigh();
        }

        return implode(' ', $buttons);
    }

    private function getButtonLow()
    {
        return Html::tag(self::LI, Html::a('Низкий ', ['low', 'id' => $this->task->id]));
    }

    private function getButtonMiddle()
    {
        return Html::tag(self::LI, Html::a('Средний ', ['middle', 'id' => $this->task->id]));
    }

    private function getButtonHigh()
    {
        return Html::tag(self::LI, Html::a('Высокий ', ['high', 'id' => $this->task->id]));
    }

    private function getClass(): string
    {
        switch ($this->priority) {
            case $this->priority->isMiddle():
                $class = 'warning';
                break;
            case $this->priority->isHigh():
                $class = 'danger';
                break;
            default:
                $class = 'info';
        }

        return $class;
    }
}