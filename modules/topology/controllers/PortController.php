<?php

namespace app\modules\topology\controllers;

use yii\web\Controller;
use app\controllers\RbacController;
use yii\data\ActiveDataProvider;

use app\models\Device;
use app\models\Network;
use app\models\Domain;
use app\models\Port;
use Yii;
use app\modules\topology\models\DomainForm;

use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\grid\ActionColumn;
use app\components\LinkColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\data\ArrayDataProvider;
use yii\i18n\Formatter;

use yii\helpers\Json;

class PortController extends RbacController {
	
	public function actionIndex($id = null){
		self::canRedir("topology/read");
		 
		//Pega os dominios que o usuário tem permissão
		$domains = self::whichDomainsCan("topology/read");
		 
		return $this->render('index', array(
				'domains' =>self::whichDomainsCan("topology/read"),
				'selected_domain' => $id,
		));
	}
	
	public function actionCreate(){
		$port = new Port;
	
		if(isset($_POST['name'])) {

			$domainId = Network::find()->where(['name' => $_POST['network']])->one()->domain_id;
			$permission = self::can('topology/create', $domainId);
	
			if($permission){
				$port->type = 'NSI';
				$port->directionality = 'BI';
				
				$port->name = $_POST['name'];
				$port->urn = $_POST['urn'];
				$port->max_capacity = $_POST['max_capacity'];
				$port->min_capacity = $_POST['min_capacity'];
				$port->granularity = $_POST['granularity'];
	
				$port->network_id = Network::find()->where(['name' => $_POST['network']])->andWhere(['domain_id' => $domainId])->one()->id;
				
				$port->device_id = Device::find()->where(['name' => $_POST['device']])->andWhere(['domain_id' => $domainId])->one()->id;
	
				if ($port->save()) {
					$port->updateVlans($_POST['vlan']);
					echo "ok";
				}
				else{
					echo "error";
				}
			}
	
		}
		else echo "error";
		 
	}
	
	public function actionUpdate(){
		if(isset($_POST['name'])) {
			$port = Port::find()->where(['id' => $_POST['id']])->one();
	
			$domainId = Network::find()->where(['name' => $_POST['network']])->one()->domain_id;
			$permission = self::can('topology/update', $domainId);
	
			if($permission){
				$port->type = 'NSI';
				$port->directionality = 'BI';
				
				$port->name = $_POST['name'];
				$port->urn = $_POST['urn'];
				$port->max_capacity = $_POST['max_capacity'];
				$port->min_capacity = $_POST['min_capacity'];
				$port->granularity = $_POST['granularity'];
	
				$port->network_id = Network::find()->where(['name' => $_POST['network']])->andWhere(['domain_id' => $domainId])->one()->id;
				
				$port->device_id = Device::find()->where(['name' => $_POST['device']])->andWhere(['domain_id' => $domainId])->one()->id;
	
				if ($port->save()) {
					$port->updateVlans($_POST['vlan']);
					echo "ok";
				}
				else{
					echo "error";
				}
			}
			else echo false;
		}
	}
	
	public function actionDeleteOne(){
		if(isset($_POST['id'])){
			$ids = $_POST['id'];
			$port = Port::findOne(['id' => $_POST['id']]);
			if(self::can('topology/delete', $port->getDevice()->one()->domain_id)){
				$domain_id = $port->getDevice()->one()->domain_id;
				$port->delete();
				echo ($domain_id);
			}
			else echo false;
		}
	}
	
	public function actionDelete(){
		$ids = $_REQUEST['itens'];
		if(isset($_REQUEST['itens'])){
			$ids = $_REQUEST['itens'];
			foreach($ids as $id){
				$port = Port::findOne(['id' => $id]);
				if(self::can('topology/delete', $port->getDevice()->one()->domain_id)){
					Yii::$app->getSession()->addFlash('success', Yii::t('topology', 'Successful delete port {port} from domain {domain}', ['port' => $port->name, 'domain' => $port->getDevice()->one()->getDomain()->one()->name]));
					$domain_id = $port->getDevice()->one()->domain_id;
					$port->delete();
				}
				else{
					Yii::$app->getSession()->addFlash('warning', Yii::t('topology', 'Port {port} not deleted. You are not allowed for delete on domain {domain}', ['port' => $port->name, 'domain' => $port->getDevice()->one()->getDomain()->one()->name]));
				}
			}
		}
	}
	
	public function actionGetDomainId(){
		$portId = $_POST['portId'];
		echo Port::find()->where(['id' => $portId])->one()->getDevice()->one()->getDomain()->select(['id'])->one()->id;
	}
	
	public function actionGetDomainName(){
		echo json_encode(Domain::find()->where(['id' => $_GET['id']])->select(['name'])->one()->name);
	}
	
	public function actionGetDomainsId(){
		$domains = self::whichDomainsCan("topology/read");
	
		foreach ($domains as $dom):
		$array[] = $dom->id;
		endforeach;
		 
		echo json_encode($array);
	}
	
	public function actionGetPort(){
		$port = Port::findOne($_GET['id']);
		
		$data = [];
		$data['name'] = $port->name;
		$data['urn'] = $port->urn;
		$data['max_capacity'] = $port->max_capacity;
		$data['min_capacity'] = $port->min_capacity;
		$data['granularity'] = $port->granularity;
		
		return json_encode($data);
	}
	
	public function actionGetPortDevice(){
		$data = Port::findOne($_GET['id'])->getDevice()->one()->name;
		return json_encode($data);
	}
	
	public function actionGetPortNetwork(){
		$data = Port::findOne($_GET['id'])->getNetwork()->one()->name;
		return json_encode($data);
	}

	public function actionGetVlan(){
		$port = Port::findOne($_GET['id']);
		$data = $port->getVlanRanges()->asArray()->all();
	
		$temp = Json::encode($data);
		Yii::error($temp);
		return $temp;
	}
	
	public function actionCanUpdate(){
		if(isset($_POST['id'])) {
			$id = $_POST['id'];
			echo self::can('topology/update', Port::findOne(['id' => $id])->getDevice()->one()->domain_id);
		}
		else echo false;
	}
	
	public function actionCanCreate(){
		if(isset($_POST['id'])) {
			echo self::can('topology/create', $_POST['id']);
		}
		else echo false;
	}
	
	public function actionGetDevicesNew(){
		$domId = $_GET['domainId'];
	
		$arrayDevices = Device::find()->where(['domain_id' => $domId])->all();
	
		$array = array(Yii::t('topology', 'select'));
		foreach ($arrayDevices as $dev):
		$array[] = $dev->name;
		endforeach;
	
		echo json_encode($array);
	}
	
	public function actionGetNetworksNew(){
		$domId = $_GET['domainId'];
		$arrayNetworks = Network::find()->where(['domain_id' => $domId])->all();
	
		$array = array(Yii::t('topology', 'select'));
		foreach ($arrayNetworks as $net):
		$array[] = $net->name;
		endforeach;
	
		echo json_encode($array);
	}
    
    public function actionGetByDevice($id, $type, $cols=null){
        $query = Port::find()->where(['device_id'=>$id, 'type'=>$type, 'directionality'=> 'BI'])->asArray();
        
        $cols ? $data = $query->select(json_decode($cols))->all() : $data = $query->all();

        $temp = Json::encode($data);
        Yii::trace($temp);
        return $temp;
    }
    
    public function actionGet($id, $cols=null) {
        $cols ? $port = Port::find()->where(
                ['id'=>$id])->select($col)->asArray()->one() : $port = Port::find()->where(['id'=>$id])->asArray()->one();
    
        $temp = Json::encode($port);
        Yii::trace($temp);
        return $temp;
    }
    
    public function actionGetVlanRanges($id){
        $port = Port::findOne($id);
        $data = $port->getVlanRanges()->all();
        if(!$data) $data = $port->getInboundPortVlanRanges()->asArray()->all();

        $temp = Json::encode($data);
        Yii::trace($temp);
        return $temp;
    }
}