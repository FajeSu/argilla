<?php
/**
 * @author Nikita Melnikov <melnikov@shogo.ru>
 * @link https://github.com/shogodev/argilla/
 * @copyright Copyright &copy; 2003-2013 Shogo
 * @license http://argilla.ru/LICENSE
 * @package backend.modules.rbac
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $visible
 * @property array $roles
 *
 * @method static BUser model(string $class = __CLASS__)
 */
class BUser extends BActiveRecord
{
  /**
   * @var string
   */
  protected $passwordNew;

  /**
   * @return string
   */
  public function tableName()
  {
    return '{{auth_user}}';
  }

  /**
   * @return array
   */
  public function rules()
  {
    return array(
      array('username', 'required'),
      array('username', 'unique'),
      array('visible', 'numerical', 'integerOnly' => true),
      array('username', 'length', 'max' => 64),
      array('password, passwordNew, roles', 'safe'),
    );
  }

  /**
   * Используется для отображения пустого поля пароля в форме
   *
   * @return string
   */
  public function getPasswordNew()
  {
    return '';
  }

  /**
   * @param string $value
   */
  public function setNewPassword($value)
  {
    $this->passwordNew = $value;
  }

  /**
   * @param array $roles
   */
  public function setRoles($roles)
  {
    $this->clearRoles();

    if( empty($roles) )
      return;

    foreach( $roles as $role )
    {
      Yii::app()->authManager->assign($role, $this->id);
    }
  }

  /**
   * Получение всех ролей пользователя
   *
   * @return array
   */
  public function getRoles()
  {
    $role = array();
    $data = Yii::app()->authManager->getRoles($this->id);

    foreach( $data as $item )
    {
      $role[$item->name] = $item->name;
    }

    return $role;
  }

  /**
   * Удаление всех ролей пользователя
   */
  public function clearRoles()
  {
    foreach( $this->roles as $role )
    {
      Yii::app()->authManager->revoke($role, $this->id);
    }
  }

  /**
   * @return array
   */
  public function attributeLabels()
  {
    return CMap::mergeArray(parent::attributeLabels(), array(
      'username'    => 'Имя пользователя',
      'password'    => 'Пароль',
      'roles'       => 'Роли',
      'passwordNew' => 'Пароль',
    ));
  }

  /**
   * @param CDbCriteria $criteria
   *
   * @return CDbCriteria
   */
  protected function getSearchCriteria(CDbCriteria $criteria)
  {
    $criteria->compare('visible', $this->visible);
    $criteria->compare('username', $this->username, true);

    return $criteria;
  }

  /**
   * Перед сохранением проверяем необходимость создать пароль
   *
   * @return boolean
   */
  protected function beforeSave()
  {
    if( $this->isNewRecord || !empty($this->passwordNew) )
      $this->password = BUserIdentity::createPassword($this->username, $this->passwordNew);

    return parent::beforeSave();
  }
}