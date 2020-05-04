<?php

use core\entities\Task;

/** @var $task Task */

$this->title = $task->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <div class="body-content">
        <?= $task->title?>
    </div>
</div>