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
     * @var \yii\data\ActiveDataProvider
     */
    private $tasks;

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

        $this->tasks = $this->readRepository->getAll();
    }

    public function actionIndex()
    {
        $tasks = $this->readRepository->getAll();

        return $this->render('index', [
            'tasks' => $this->tasks,
        ]);
    }

    public function actionView(int $id)
    {
        $task = $this->findModel($id);

        return $this->render('view', [
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

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionDelete(int $id)
    {
        if(Yii::$app->request->isAjax){
            try {
                $this->taskService->remove($id);
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->renderPartial('index',['tasks' => $this->tasks,]);
        }
        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws AssertionFailedException
     */
    public function actionComplete(int $id)
    {
        if(Yii::$app->request->isAjax){
            try {
                $this->taskService->complete($id);
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->renderPartial('index',['tasks' => $this->tasks,]);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Response | string
     * @throws AssertionFailedException
     */
    public function actionLow(int $id)
    {
        if(Yii::$app->request->isAjax){
            try {
                $this->taskService->toLow($id);
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->renderPartial('index',['tasks' => $this->tasks,]);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Response | string
     * @throws AssertionFailedException
     */
    public function actionMiddle(int $id)
    {
        if(Yii::$app->request->isAjax){
            try {
                $this->taskService->toMiddle($id);
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->renderPartial('index',['tasks' => $this->tasks,]);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Response | string
     * @throws AssertionFailedException
     */
    public function actionHigh(int $id)
    {
        if(Yii::$app->request->isAjax){
            try {
                $this->taskService->toHigh($id);
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->renderPartial('index',['tasks' => $this->tasks,]);
        }

        return $this->redirect(['index']);
    }


    /**
     * @param int $id
     * @param int $tagId
     * @return Response
     */
    public function actionDeleteTag(int $id, int $tagId)
    {
        $task = $this->findModel($id);

        try {
            $this->taskService->deleteTag($task->id, $tagId);
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