<?php

namespace frontend\controllers;

use Assert\AssertionFailedException;
use core\entities\Task;
use core\forms\TaskForm;
use core\readModels\TaskReadRepository;
use core\repositories\TaskNotFoundException;
use core\services\TaskService;
use DomainException;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class TaskController extends Controller
{
    /**
     * @var TaskService
     */
    private $taskService;
    /**
     * @var TaskReadRepository
     */
    private $readRepository;

    /**
     * TaskController constructor.
     * @param $id
     * @param $module
     * @param TaskService $taskService
     * @param TaskReadRepository $readRepository
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        TaskService $taskService,
        TaskReadRepository $readRepository,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->taskService = $taskService;
        $this->readRepository = $readRepository;
    }

    public function actionIndex()
    {
        $tasks = $this->readRepository->getAll();

        $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

    public function actionView(int $id)
    {
        $task = $this->findModel($id);

        $this->render('view', [
            'task' => $task
        ]);
    }

    /**
     * @return string|Response
     * @throws AssertionFailedException
     */
    public function actionCreate()
    {
        $form = new TaskForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $task = $this->taskService->create($form);
                return $this->redirect(['view', 'id' => $task->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $task = $this->findModel($id);

        $form = new TaskForm($task);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->taskService->update($task->id, $form);
                return $this->redirect(['view', 'id' => $task->id]);
            } catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'task' => $task,
        ]);
    }

    public function remove(int $id)
    {
        try {
            $this->taskService->remove($id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }


    protected function findModel($id): Task
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }
        throw new TaskNotFoundException('The requested page does not exist.');
    }
}