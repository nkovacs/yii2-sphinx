<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
	public $username;
	public $password;
	public $rememberMe = true;
	private $_user;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			// username and password are both required
			[['username', 'password'], 'required'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
			// rememberMe must be a boolean value
			['rememberMe', 'boolean'],
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 */
	public function validatePassword()
	{
		$user = $this->getUserByUsername($this->username);
		if (!$user || !$user->validatePassword($this->password)) {
			$this->addError('password', 'Incorrect username or password.');
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function login()
	{
		if ($this->validate()) {
			$user = $this->getUserByUsername($this->username);
			Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return User|null
	 */
	private function getUserByUsername($username)
	{
		if ($this->_user === null) {
			$this->_user = User::findByUsername($username);
		}
		return $this->_user;
	}
}