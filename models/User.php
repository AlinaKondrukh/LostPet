<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $role_id
 *
 * @property PetRequests[] $petRequests
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', 'role_id'], 'required', 'message'=> 'Поле не заполнено'],
            [['role_id'], 'integer'],
            [['name'], 'string', 'max' => 511],
            [['email', 'password'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['email'], 'email', 'message' => 'Электронная почта введена некорректно'],
            [['password'], 'match', 'pattern' => '/^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9!?\-\/\_\\\#\@]{8,}$/', 'message' => 'Пароль должен содержать минимум одну букву, одну цифру и быть не менее 8 символов (он может содержать только эти символы: !?-/_\#@).'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль (Может содержать цифры, латинские буквы и специальные символы (!?-/_\#@))',
            'password_confirmation' => 'Повторите пароль',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[PetRequests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPetRequests()
    {
        return $this->hasMany(PetRequests::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }
        /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }
    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return null;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    public static function login($email, $pass)
    {
        $user = self::findOne(['email'=> $email]);
        if ($user && $user->validatePassword($pass)) {
            return $user;
        }
        return null;
    }
    public function isAdmin()
    {
        return $this->role_id == Role::ADMIN_ROLE_ID;
    }
    public static function getInstance(): User|null
    {
        return Yii::$app->user->identity;
    }
    public function __toString()
    {
        return $this->email;
    }
}
