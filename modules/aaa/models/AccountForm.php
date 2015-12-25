<?php

namespace meican\modules\aaa\models;

use yii\base\Model;
use Yii;
use meican\models\User;

/**
 */
class AccountForm extends Model {
	
	public $login;
	public $isChangedPass;
	public $currentPass;
	public $newPass;
	public $newPassConfirm;
	public $email;
	public $name;
	public $language;
	public $dateFormat;
	public $timeFormat;
	public $timeZone;

	/**
	 */
	public function rules()	{
		return [
			[['name', 'language', 'email', 'dateFormat', 'timeFormat', 'timeZone'], 'required'],
			['newPass', 'compare', 'compareAttribute'=> 'newPassConfirm'],
			[['isChangedPass','currentPass','newPass', 'newPassConfirm'], 'validatePass'],
			[['login'], 'safe']
		];
	}
	
	public function attributeLabels() {
		return [
			'login'=>Yii::t('aaa', 'Login'),
			"password"=>Yii::t('aaa', 'Password'),
			"isChangedPass" => Yii::t('aaa', 'I want change my password'),
			"newPass" => Yii::t('aaa', 'New password'),
			"newPassConfirm"=> Yii::t('aaa', "Confirm new password"),
			'currentPass' => Yii::t('aaa', 'Current password'),
			'language' => Yii::t('aaa', 'Language'),
			'name' => Yii::t('aaa', 'Name'),
			'email' => Yii::t('aaa', 'Email'),
			'dateFormat' => Yii::t('aaa', 'Date Format'),
			'timeZone' => Yii::t('aaa', 'Time Zone'),
			'timeFormat' => Yii::t("aaa", "Time Format")
		];
	}
	
	public function setFromRecord($record) {
		$this->login = $record->login;
		$this->name = $record->name;
		$this->email = $record->email;
		$this->language = $record->language;
		$this->dateFormat = $record->date_format;
		$this->timeZone = $record->time_zone;
	}
	
	public function validatePass($attr, $params) {
		if ($this->isChangedPass) {
			
			if ($this->currentPass == '' || $this->newPass == '' || $this->newPassConfirm == '') {
				$this->addError('', 'All password fields are required');
				
			} else {
				$user = User::findOne(Yii::$app->user->id);
				
				if ($user->isValidPassword($this->currentPass)) {
					return true;
				} else {
					$this->addError('currentPass', Yii::t('aaa', 'Current password does not match'));
				}
			}
		}
		return false;
	}
	
	public function updateUser($user) {
		if ($this->isChangedPass) {
			$user->password = Yii::$app->getSecurity()->generatePasswordHash($this->newPass);
		}

		$user->name = $this->name;
		$user->email = $this->email;
		$user->language = $this->language;
		$user->date_format = $this->dateFormat;
		$user->time_format = $this->timeFormat;
		$user->time_zone = $this->timeZone;
		
		return $user->save();
	}
	
	public function clearPass() {
		$this->isChangedPass = false;
		$this->newPass = '';
		$this->newPassConfirm = '';
		$this->currentPass = '';
	}
}
