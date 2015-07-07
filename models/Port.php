<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%port}}".
 *
 * @property integer $id
 *
 * O tipo da porta identifica o formato da URN que a
 * representa. NMWG e NSI são suportados.
 *
 * @property string $type
 *
 * Portas podem ser bidirecionais, unidirecionais apenas de saída
 * ou unidirecionais apenas de entrada. 
 *
 * @property string $directionality
 * 
 * URN que representa a Porta de forma global. 
 * O valor persistido não deve conter o prefixo 
 * standard da OGF: 'urn:ogf:network:'. Ele é inserido
 * quando mostrado ao usuário ou enviado a agentes externos.
 *
 * @property string $urn
 * @property string $name
 * @property integer $max_capacity
 * @property integer $min_capacity
 * @property integer $granularity
 *
 * Portas unidirecionais do tipo NSI devem ter associadas
 * portas bidirecionais.
 *
 * @property integer $biport_id
 *
 * Alias é o nome dado a uma porta com a qual 
 * existe um Link.
 *
 * @property integer $alias_id
 *
 * Toda porta deve possuir um Device (Nodo) associado.
 * Esse device pode não ter nome representativo, mas
 * deve existir para a correta aglomeração no mapa.
 *
 * @property integer $device_id
 *
 * Apenas portas do tipo NSI possuem redes associadas, 
 * uma vez que essas redes só existem na topologia NSI.
 *
 * @property integer $network_id
 *
 * @property Port $biport
 * @property Port[] $ports
 * @property Device $device
 * @property Network $network
 * @property Port $alias
 * @property VlanRange[] $vlanRanges
 */
class Port extends \yii\db\ActiveRecord
{
    const TYPE_NMWG = "NMWG";
    const TYPE_NSI = "NSI";

    //directionality
    const DIR_BI = "BI";
    const DIR_UNI_IN = "UNI_IN";
    const DIR_UNI_OUT = "UNI_OUT";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%port}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'directionality', 'urn', 'name', 'device_id'], 'required'],
            [['type', 'directionality'], 'string'],
            [['max_capacity', 'min_capacity', 'granularity', 'biport_id', 'alias_id', 'device_id', 'network_id'], 'integer'],
            [['urn'], 'string', 'max' => 250],
            [['name'], 'string', 'max' => 100],
            [['urn'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('circuits', 'ID'),
            'type' => Yii::t('circuits', 'Type'),
            'directionality' => Yii::t('circuits', 'Directionality'),
            'urn' => Yii::t('circuits', 'Urn'),
            'name' => Yii::t('circuits', 'Name'),
            'max_capacity' => Yii::t('circuits', 'Max Capacity'),
            'min_capacity' => Yii::t('circuits', 'Min Capacity'),
            'granularity' => Yii::t('circuits', 'Granularity'),
            'biport_id' => Yii::t('circuits', 'Biport ID'),
            'alias_id' => Yii::t('circuits', 'Alias ID'),
            'device_id' => Yii::t('circuits', 'Device ID'),
            'network_id' => Yii::t('circuits', 'Network ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiPort()
    {
        return $this->hasOne(Port::className(), ['id' => 'biport_id']);
    }

    public function getUniPorts() {
        return $this->hasMany(Port::className(), ['biport_id'=> 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNetwork()
    {
        return $this->hasOne(Network::className(), ['id' => 'network_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlias()
    {
        return $this->hasOne(Port::className(), ['id' => 'alias_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVlanRanges() {
        return $this->hasMany(VlanRange::className(), ['port_id' => 'id']);
    }

    public function updateVlans($vlanRanges) {
        $this->removeVlans();
         
        $rangesArray = explode(",", $vlanRanges);
        foreach ($rangesArray as $range) {
                $vlan = new VlanRange;
                $vlan->value = $range;
                $vlan->port_id = $this->id;
                if(!$vlan->save()) {
                    Yii::trace("Erro ao salvar vlan range");
                }
        }
    }
    
    public function removeVlans() {
        $vlans = VlanRange::findAll(['port_id' => $this->id]);
        foreach ($vlans as $vlan) {
            $vlan->delete();
        }
    }
    
    public static function findByUrn($urn) {
        return self::find()->where(['urn'=>$urn]);
    }
    
    public function setAlias($port) {
        $this->alias_id = $port->id;
    }
    
    public function setDevice($dev) {
        $this->device_id = $dev->id;
    }

    public function getInboundPortVlanRanges() {
        $inboundPort = $this->getUniPorts()->andWhere(['directionality'=>self::DIR_UNI_IN])->one();
        if ($inboundPort) return $inboundPort->getVlanRanges();
        return null;
    }
}