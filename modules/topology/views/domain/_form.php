<?php 
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\ArrayHelper;
?>

<?php $form= ActiveForm::begin([
	'id'=>'domain-form',
	'enableAjaxValidation'=>false,
	'method' => 'post',
	'enableClientValidation' => false,
]); ?>

	<h4>
	<font color="#3a5879">
	
	<div class="form input">
		<?= $form->field($domain,'name')->textInput(['size'=>30]); ?>
	</div>
	
	<div class="form input">
		<?= $form->field($domain,'topology')->textInput(['size'=>30]); ?>
	</div>
	</font>
	</h4>
	<h1>OSCARS</h1>
	<font color="#3a5879">
	<h4>
	<div class="form input">
		<?= $form->field($domain,'nsa')->textInput(['size'=>50]); ?>
	</div>
	
	<div class="form input">
		<?= $form->field($domain,'oscars_version')->dropDownList(['0.6'=>'0.6']); ?>
	</div>
	
	<div class="form input">
		<?= $form->field($domain,'connection_url')->textInput(['size'=>50]); ?>
	</div>
	
	<div class="form input">
		<?php echo $form->field($domain,'default_policy')->dropDownList($domain->getPolicyOptions());
			if(Yii::$app->language == 'pt-BR') echo '<label style="padding-left: 5px" class="form-group">'.Yii::t("topology", "Overwritten by Workflows").'</label>';
			else echo '<label class="form-group">'.Yii::t("topology", "Overwritten by Workflows").'</label>'; ?>
	</div>
	
	</h4>
	</font>
	
	<div class="buttonsForm">
		<?= Html::submitButton(Yii::t("topology", 'Save')) ?>
		<a href="<?= Url::toRoute(['index']);?>"><?= Html::Button(Yii::t("topology", 'Cancel')); ?></a>
	</div>

	
	

	
<?php ActiveForm::end(); ?>
