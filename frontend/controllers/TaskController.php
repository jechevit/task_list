<?php

namespace frontend\controllers;

use Assert\AssertionFailedException;
use core\entities\Task;
use core\forms\TaskForm;
use core\readModels\TaskReadRepository;
use core\repositories\TagRepository;
use core\repositories\TaskNotFoundException;
use core\services\TaskService;
use DomainException;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
     * @var ActiveDataProvider
     */
    private $tasks;
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * TaskController constructor.
     * @param $id
     * @param $module
     * @param TaskService $taskService
     * @param TaskReadRepository $readRepository
     * @param TagRepository $tagRepository
     * @param array $config
     */
    public function __construct(
        $id,
        $module,
        TaskService $taskService,
        TaskReadRepository $readRepository,
        TagRepository $tagRepository,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->taskService = $taskService;
        $this->readRepository = $readRepository;

        $this->tasks = $this->readRepository->getAll();
        $this->tagRepository = $tagRepository;
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        if (isset(Yii::$app->request->queryParams['tagId'])){
            $tagId = Yii::$app->request->queryParams['tagId'];
            if (!$tag = $this->tagRepository->get($tagId)){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            $tasks = $this->readRepository->getAllByTag($tag);
        } else {
            $tasks = $this->readRepository->getAll();
        }


        return $this->render('index', [
            'tasks' => $tasks,
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
     * @throws Throwable
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
        $this->checkTask($task);

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
        try {
            $this->taskService->remove($id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
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
        $task = $this->findModel($id);
        $this->checkTask($task);

        try {
            $this->taskService->complete($task->id);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
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
        $task = $this->findModel($id);
        $this->checkTask($task);

        if (Yii::$app->request->isAjax) {
            try {
                $this->taskService->toLow($task->id);
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->renderPartial('index', ['tasks' => $this->tasks,]);
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
        $task = $this->findModel($id);
        $this->checkTask($task);

        if (Yii::$app->request->isAjax) {
            try {
                $this->taskService->toMiddle($task->id);
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->renderPartial('index', ['tasks' => $this->tasks,]);
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
        $task = $this->findModel($id);
        $this->checkTask($task);

        if (Yii::$app->request->isAjax) {
            try {
                $this->taskService->toHigh($task->id);
            } catch (DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
            return $this->renderPartial('index', ['tasks' => $this->tasks,]);
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
        $this->checkTask($task);

        try {
            $this->taskService->deleteTag($task->id, $tagId);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * @return Response
     * @throws Throwable
     */
    public function actionAddTag()
    {
        if (!isset(Yii::$app->request->bodyParams['TagsForm'])) {
            return $this->redirect(['index', ['tasks' => $this->tasks]]);
        }

        $tagNewName = Yii::$app->request->bodyParams['TagsForm']['textNew'];
        $taskId = Yii::$app->request->bodyParams['TagsForm']['taskId'];
        $tagsId = Yii::$app->request->bodyParams['TagsForm']['existing'];

        $task = $this->findModel($taskId);

        $this->checkTask($task);

        try {
            $this->taskService->addTag($task->id, $tagNewName ?: null, $tagsId ?: null);
        } catch (DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @param Task $task
     */
    private function checkTask(Task $task): void
    {
        if ($task->isCompleted()) {
            throw new DomainException('Задача уже выполнена');
        }
    }

    protected function findModel($id): Task
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }
        throw new TaskNotFoundException('The requested page does not exist.');
    }
}