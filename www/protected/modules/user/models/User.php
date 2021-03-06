<?php

class User extends CActiveRecord
{
    public $messageCount;

    const STATUS_NOACTIVE=0;
    const STATUS_ACTIVE=1;
    const STATUS_BANNED=-1;

    //TODO: Delete for next version (backward compatibility)
    const STATUS_BANED=-1;

    /**
     * The followings are the available columns in table 'users':
     * @var integer $id
     * @var string $username
     * @var string $password
     * @var string $email
     * @var string $activkey
     * @var integer $createtime
     * @var integer $lastvisit
     * @var integer $superuser
     * @var integer $status
     * @var timestamp $create_at
     * @var timestamp $lastvisit_at
     * @var integer $internal_purse
     * @var string $perfect_purse
     * @var string $secret
     * @var integer $type
     */

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return Yii::app()->getModule('user')->tableUsers;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.CConsoleApplication
        return ((get_class(Yii::app())=='CConsoleApplication' || (get_class(Yii::app())!='CConsoleApplication' && Yii::app()->getModule('user')->isAdmin()))?array(
            array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
            array('password', 'length', 'max'=>128, 'min' => 6,'message' => UserModule::t("Incorrect password (minimal length 6 symbols).")),
            array('secret', 'length', 'max'=>128, 'min' => 3 ),
            array('email', 'email'),
            array('internal_purse', 'unique'),
            array('perfect_purse, type','safe'),
            array('phone', 'unique', 'message' => UserModule::t("This user's phone already exists.")),
            array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
            array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
            array('status', 'in', 'range'=>array(self::STATUS_NOACTIVE,self::STATUS_ACTIVE,self::STATUS_BANNED)),
            array('superuser', 'in', 'range'=>array(0,1)),
            array('create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('birthday', 'default', 'value' => date('Y-m-d'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('lastvisit_at', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
            array('username, email, superuser, status', 'required'),
            array('superuser, status', 'numerical', 'integerOnly'=>true),
            array('id, username, password, email, activkey, create_at, lastvisit_at, superuser, status', 'safe', 'on'=>'search'),
        ):((Yii::app()->user->id==$this->id)?array(
            array('username, email, secret, phone', 'required'),
            array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
            array('email', 'email'),
            array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
            array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
        ):array()));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        $relations = Yii::app()->getModule('user')->relations;
        if (!isset($relations['profile']))
            $relations['profile'] = array(self::HAS_ONE, 'Profile', 'user_id');
        $relations['refs'] = array(self::HAS_MANY, 'Referral', 'user_id');
        $relations['deposit'] = array(self::HAS_MANY, 'Deposit', 'user_id');
        $relations['transaction'] = array(self::HAS_MANY, 'UserTransaction', 'user_id');
        $relations['messages'] = array(self::STAT, 'Message', 'user_id', 'condition'=>'status=' . Message::MESSAGE_STATUS_NEW );
        return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => UserModule::t("Id"),
            'username'=>UserModule::t("username"),
            'password'=>UserModule::t("password"),
            'verifyPassword'=>UserModule::t("Retype Password"),
            'email'=>UserModule::t("E-mail"),
            'verifyCode'=>UserModule::t("Verification Code"),
            'activkey' => UserModule::t("activation key"),
            'createtime' => UserModule::t("Registration date"),
            'create_at' => UserModule::t("Registration date"),
            'internal_purse' => UserModule::t("Internal purse"),
            'perfect_purse' => UserModule::t("Perfect purse"),
            'lastvisit_at' => UserModule::t("Last visit"),
            'superuser' => UserModule::t("Superuser"),
            'status' => UserModule::t("Status"),
            'secret' => UserModule::t("Secret"),
            'phone' => UserModule::t("Phone"),
            'birthday' => UserModule::t("birthday"),
            'type' => UserModule::t("type"),
        );
    }

    public function scopes()
    {
        return array(
            'active'=>array(
                'condition'=>'status='.self::STATUS_ACTIVE,
            ),
            'notactive'=>array(
                'condition'=>'status='.self::STATUS_NOACTIVE,
            ),
            'banned'=>array(
                'condition'=>'status='.self::STATUS_BANNED,
            ),
            'superuser'=>array(
                'condition'=>'superuser=1',
            ),
            'notsafe'=>array(
                'select' => 'id, username, password, phone, email, activkey, create_at, lastvisit_at, internal_purse, perfect_purse, superuser, status, type',
            ),
        );
    }

    public function defaultScope()
    {
        return CMap::mergeArray(Yii::app()->getModule('user')->defaultScope,array(
            'alias'=>'user',
            'select' => 'user.id, user.username, user.email, user.create_at, user.lastvisit_at, user.internal_purse, user.perfect_purse, user.superuser, user.status, secret, phone,type',
        ));
    }

    public static function itemAlias($type,$code=NULL) {
        $_items = array(
            'UserStatus' => array(
                self::STATUS_NOACTIVE => UserModule::t('Not active'),
                self::STATUS_ACTIVE => UserModule::t('Active'),
                self::STATUS_BANNED => UserModule::t('Banned'),
            ),
            'AdminStatus' => array(
                '0' => UserModule::t('No'),
                '1' => UserModule::t('Yes'),
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('activkey',$this->activkey);
        $criteria->compare('create_at',$this->create_at);
        $criteria->compare('lastvisit_at',$this->lastvisit_at);
        $criteria->compare('internal_purse',$this->internal_purse);
        $criteria->compare('superuser',$this->superuser);
        $criteria->compare('status',$this->status);
        $criteria->compare('secret',$this->secret);
        $criteria->compare('phone',$this->phone);
        $criteria->compare('birthday',$this->birthday);
        $criteria->compare('type',$this->type);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>Yii::app()->getModule('user')->user_page_size,
            ),
        ));
    }

    public function getAmount() {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT amount_after
                FROM " . UserTransaction::model()->tableName() . "
                WHERE user_id=" . Yii::app()->user->id . "
                ORDER BY id DESC
                LIMIT 1
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    //Вывод баланса пользователя по ID
    public function userAmount($user_id) {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT amount_after
                FROM " . UserTransaction::model()->tableName() . "
                WHERE user_id=" . $user_id . "
                ORDER BY id DESC
                LIMIT 1
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }


    public function getAllAmount(){
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(amount)
                AS amount
                FROM " . UserTransaction::model()->tableName() . "

                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function getPaymentAmount() {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(amount)
                AS amount
                FROM " . UserTransaction::model()->tableName() . "
                WHERE user_id=" . Yii::app()->user->id . "
                AND amount_type=" . UserTransaction::AMOUNT_TYPE_RECHARGE . "
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function getInvestmentAmount() {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(deposit_amount)
                AS deposit_amount
                FROM " . Deposit::model()->tableName() . "
                WHERE user_id=" . Yii::app()->user->id . "
                AND status= 1
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    //Вывод суммы депозитов пользователя по ID
    public function adminInvestmentAmount($user_id) {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(deposit_amount)
                AS deposit_amount
                FROM " . Deposit::model()->tableName() . "
                WHERE user_id=" . $user_id . "
                AND status= 1
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function countReferral($user_id){
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT COUNT(ref_id)
                AS ref_id
                FROM " . Referral::model()->tableName() . "
                WHERE user_id=" . $user_id . "
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function referralInvestment($referral_id) {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(amount)
                AS amount
                FROM " . UserTransaction::model()->tableName() . "
                WHERE user_id=" . $referral_id . "
               AND amount_type=" . UserTransaction::AMOUNT_TYPE_EARNINGS . "
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function getOutputAmount() {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(amount)
                AS amount
                FROM " . UserTransaction::model()->tableName() . "
                WHERE user_id=" . Yii::app()->user->id . "
                AND amount_type=" . UserTransaction::AMOUNT_TYPE_OUTPUT . "
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function getReferralAmount() {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(amount)
                AS amount
                FROM " . UserTransaction::model()->tableName() . "
                WHERE user_id=" . Yii::app()->user->id . "
                AND amount_type=" . UserTransaction::AMOUNT_TYPE_REFERRAL . "
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function referralDeposit($referral_id) {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(deposit_amount)
                AS deposit_amount
                FROM " . Deposit::model()->tableName() . "
                WHERE user_id=" . $referral_id . "
                AND status=1
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function getEarningsAmount() {
        if ( !$this->isNewRecord ) {
            $result = Yii::app()->db->createCommand("
                SELECT SUM(amount)
                AS amount
                FROM " . UserTransaction::model()->tableName() . "
                WHERE user_id=" . Yii::app()->user->id . "
                AND amount_type=" . UserTransaction::AMOUNT_TYPE_EARNINGS . "
                ")->queryScalar();

            return $result ?: 0;
        } else {
            return 0;
        }
    }

    public function getCreatetime() {
        return strtotime($this->create_at);
    }

    public function setCreatetime($value) {
        $this->create_at=date('Y-m-d H:i:s',$value);
    }

    public function getLastvisit() {
        return strtotime($this->lastvisit_at);
    }

    public function setLastvisit($value) {
        $this->lastvisit_at=date('Y-m-d H:i:s',$value);
    }
    //проверяем есть ли реферер
    public function referralUser($user_name) {
        $user = User::model()->findByAttributes(array('username' => $user_name));
        if ( $user != null ) {
            return $user;
        } else {
            Yii::app()->user->setState('ref',null);
            return false;
        }
    }
    // Отправка сообщений
    public function sendMessage($user_id, $subject, $msg, $importance,  $methods = array(), $sender = null) {

        if ( empty($methods) )
            $methods = array('system');

        if (in_array('system', $methods)) {
            $message = new Message();
            $message->subject = $subject;
            $message->message = $msg;
            $message->user_id = $user_id;
            $message->sender = $sender;
            $message->importance = $importance;
            $message->status = Message::MESSAGE_STATUS_NEW;
            if ( $message->save() ) {
                return false;
            }
        }

        return true;

    }
    //Функция склонения слов в зависимости от чисел
    //(Число, 'день', 'дня', 'дней')
    public function declension($n, $form1, $form2, $form5)
    {
        $n = abs ($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $form5;
        if ($n1 > 1 && $n1 < 5) return $form2;
        if ($n1 == 1) return $form1;
        return $form5;
    }
    //счетчик сообщений
    public function countMessages() {

        if ( $this->messageCount != null ) {
            return $this->messageCount;
        }

        $messages = Message::model()->findAllByAttributes(array('user_id' => Yii::app()->user->id, 'status'=>Message::MESSAGE_STATUS_NEW));

        $this->messageCount = count($messages);

        return $this->messageCount;
    }
}