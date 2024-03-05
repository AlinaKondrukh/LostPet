<?php

namespace app\controllers;

use app\models\PetRequests;
use app\models\PetRequestsSearch;
use app\models\Status;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * PetRequestsController implements the CRUD actions for PetRequests model.
 */
class PetRequestsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all PetRequests models.
     *
     */
    public function actionIndex()
    {
        $user = User::getInstance();
        if (!$user) {
            return $this->goHome();
        }
        $searchModel = new PetRequestsSearch();
        if ($user->isAdmin()) {
            $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index_admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        }
        $dataProvider = $searchModel->search($this->request->queryParams, $user->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new PetRequests model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $user = User::getInstance();
        if (!$user) {
            return $this->goHome();
        }
        $model = new PetRequests();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->user_id = $user->id;
                $model->status_id = Status::NEW_STATUS;
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
                
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PetRequests model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = User::getInstance();
        if (!$user || !$user->isAdmin()) {
            return $this->goHome();
        }
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the PetRequests model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PetRequests the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PetRequests::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
