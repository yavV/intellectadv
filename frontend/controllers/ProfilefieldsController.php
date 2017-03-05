<?php

namespace frontend\controllers;

use common\models\Profile;
use Yii;
use common\models\ProfileFields;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProfileFieldsController implements the CRUD actions for ProfileFields model.
 */
class ProfilefieldsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProfileFields models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ProfileFields::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProfileFields model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProfileFields model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {


            $profile = Profile::findOne($id);
        if (!$profile){
            return $this->redirect(Yii::$app->request->referrer);
        }
        $_fileds = ProfileFields::findAll(['profile_id' => $id]);
        if (count($_fileds) > 9){
            return $this->redirect(Yii::$app->request->referrer);

        }
        $model = new ProfileFields;

        $model->profile_id = $id;
        $_pst = Yii::$app->request->post();
            $model->save(false);
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates an existing ProfileFields model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null)
    {

        $_profile= Profile::findOne($id);
        if (!$_profile){
            return false;
        }
        $_fields = Yii::$app->request->post();
        foreach ($_fields['ProfileFields'] as $_fieldForm){
            $model = ProfileFields::findOne($_fieldForm['id']);
            $model->profile_id =$_profile->id;
            $model->field_name = $_fieldForm['field_name'];
            $model->field_value = $_fieldForm['field_value'];
            $model->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing ProfileFields model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProfileFields model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProfileFields the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfileFields::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
