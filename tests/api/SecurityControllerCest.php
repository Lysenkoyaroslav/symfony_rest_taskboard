<?php


namespace App\Tests\api;


use App\Entity\Users;
use App\Tests\ApiTester;

class SecurityControllerCest
{
    public function loginViaAPI(ApiTester $I)
    {
        ApiTester::createUserNoToken($I);


        $data = [
            'userName' => 'Davert',
            'password' => '12345',
            'email' => 'test@mail.com'
        ];
        $json = json_encode($data);


        $I->sendPOST('/login', $json);

        $apiToken=$I->grabFromRepository(Users::class, 'apiToken', array('userName' => 'Davert'));

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseContains($apiToken);
    }

    public function loginWithEmptyField(ApiTester $I)
    {
        $data = [
            'userName' => null,
            'password' => '12345',
            'email' => 'test@mail.com'
        ];
        $json = json_encode($data);


        $I->sendPOST('/login', $json);

        $I->seeResponseContains('"userName" and "password" are required fields!');
    }

    public function loginWrongData(ApiTester $I)
    {
        ApiTester::createUserNoToken($I);
        $data = [
            'userName' => 'Davert',
            'password' => 'aaa12345',
            'email' => 'test@mail.com'
        ];
        $json = json_encode($data);


        $I->sendPOST('/login', $json);

        $I->seeResponseContains('Access denied!');
    }
}