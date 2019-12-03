<?php


namespace App\Tests\api\UsersControllerTests;


use App\Entity\Users;
use App\Tests\ApiTester;

class UsersControllerCest
{

    public function getUserByIdViaAPI(ApiTester $I)
    {

        ApiTester::createAuthUser($I);

        $id = $I->grabFromRepository(Users::class, 'id', array('userName' => 'Davert'));

        $I->sendGET('/api/users/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

    }

    public function getUserByIdNonexistentIdViaAPI(ApiTester $I)
    {

        ApiTester::createAuthUser($I);

        $id = -1;
        $I->sendGET('/api/users/' . $id);
        $I->seeResponseCodeIs(404);

    }

    public function getUsersViaAPI(ApiTester $I)
    {
        ApiTester::createAuthUser($I);


        $I->sendGET('/api/users');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function createUserViaAPI(ApiTester $I)
    {
        ApiTester::createAuthUser($I);

        $data = [
            'userName' => 'davert',
            'password' => 12344321,
            'email' => 'davert@codeception.com'
        ];
        $json = json_encode($data);


        $I->sendPOST('/api/signup', $json);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":"ok"}');

    }

    public function requireFieldsErrorViaApi(ApiTester $I)
    {
        ApiTester::createAuthUser($I);

        $data = [
            'userName' => 'davert',

            'email' => 'davert@codeception.com'
        ];
        $json = json_encode($data);


        $I->sendPOST('/api/signup', $json);

        $I->seeResponseContains('Fill in required fields: userName, password, email!');
    }

}