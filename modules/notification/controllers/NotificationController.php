<?php

namespace app\modules\notification\controllers;

use Yii;
use app\controllers\RbacController;
use app\models\Notification;
use app\models\Connection;
use app\models\ConnectionAuth;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;

class NotificationController extends RbacController {
	
	const TYPE_AUTHORIZATION = 	"AUTHORIZATION";
	const TYPE_RESERVATION = 	"RESERVATION";
	const TYPE_TOPOLOGY = 		"TOPOLOGY";
	
	public $enableCsrfValidation = false;
	
	public function actionIndex(){
		$dataProvider = new ActiveDataProvider([
				'query' => Notification::find()->where(['user_id' => Yii::$app->user->getId()])->orderBy(['date' => SORT_DESC]),
				'sort' => false,
				'pagination' => false,
		]);
		
		return $this->render('/index', array(
				'data' => $dataProvider,
		));
	}
	
	public function actionGetNumberNotifications(){
		echo Notification::getNumberNotifications();
	}
	
	public function actionGetNumberAuthorizations(){
		echo Notification::getNumberAuthorizations();
	}
	
	public function actionGetNotifications(){
		if(isset($_POST['date'])) echo json_encode(Notification::getNotifications($_POST['date']));
		else echo json_encode(Notification::getNotifications(null));
	}
	
}