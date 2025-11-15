<?php


namespace Tests\Unit;

use webcraftdg\fractalCms\core\components\Constant;
use webcraftdg\fractalCms\core\models\Parameter;
use webcraftdg\fractalCms\core\models\User;
use Tests\Support\UnitTester;
use Yii;

class ModelTest extends \Codeception\Test\Unit
{


    protected UnitTester $tester;

    protected function _before()
    {
        Yii::setAlias('@test', dirname(__DIR__, 1).'/');
    }

    // tests
    public function testUser()
    {
        $user = User::createUser(
            Constant::ROLE_ADMIN,
        'test.fr',
        'test',
        'test',
        'test');
        $validate = $user->validate();
        $this->assertFalse($validate);
        $this->assertTrue($user->hasErrors());
        $this->assertArrayHasKey('email', $user->errors);
        $this->assertArrayHasKey('tmpPassword', $user->errors);
        $user = User::createUser(
            Constant::ROLE_ADMIN,
            'admin@webcraft.fr',
            'c4wL2wH0C2BydUwG',
            'admin',
            'web');
        $this->assertFalse($user->hasErrors());

        $userDb = User::find()->andWhere(['email' => 'c4wL2wH0C2BydUwG'])->one();
        $this->assertNull($userDb);

        $userDb = User::find()->andWhere(['email' => 'admin@webcraft.fr'])->one();
        $this->assertNotNull($userDb);
        $valid = $userDb->validatePassword('c4wL2wH0C2BydUwG');
        $this->assertTrue($valid);

        $id = $userDb->getId();
        $this->assertEquals($id, $userDb->id);
        $authKey = $userDb->getAuthKey();
        $this->assertIsString($authKey);
        $authkeyOk = $userDb->validateAuthKey('test');
        $this->assertFalse($authkeyOk);
        $identity = User::findIdentity($userDb->id);
        $this->assertNotNull($identity);
        $identity = User::findIdentityByAccessToken('test');
        $this->assertNull($identity);
        $name = $userDb->getInitials();
        $this->assertEquals('AW', $name);
        $userAttributes = [
          'email' => 'test@webcraft.fr',
          'tmpPassword' => 'c4wL2wH0C2BydUwG',
            'lastname' => 'test',
            'firstname' => 'test',
        ];

        $user = new User(['scenario' => User::SCENARIO_CREATE]);
        $user->buildAuthRules();
        $load = $user->load($userAttributes, '');
        $user->hashPassword();
        $passwordIsHash = preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $user->password);
        $this->assertEquals(1, $passwordIsHash);
        $this->assertTrue($load);

    }

    public function testParameter()
    {
        $parameter = new Parameter(['scenario' => Parameter::SCENARIO_CREATE]);
        $parameter->group = 'TEST';
        $parameter->name = 'TEST1';
        $parameter->value = 'test';
        $this->assertTrue($parameter->save());
        $value = Parameter::getParameter('TEST', 'TEST1');
        $this->assertEquals('test', $value);
        $parameter = new Parameter(['scenario' => Parameter::SCENARIO_CREATE]);
        $parameter->group = 'CONTENT';
        $parameter->name = 'MAIN';
        $parameter->value = 'test';
        $this->assertFalse($parameter->save());
    }

}
