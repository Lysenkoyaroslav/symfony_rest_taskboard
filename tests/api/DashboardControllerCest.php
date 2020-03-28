<?php
namespace App\Tests\api;

use App\Entity\Dashboard;
use App\Tests\ApiTester;

class DashboardControllerCest
{
    public function getDashboardByIdViaAPI(ApiTester $I)
    {
        ApiTester::createDashboard($I);

        $id = $I->grabFromRepository(Dashboard::class, 'id', array('name' => 'TestDashboard'));
        $I->sendGET('/dashboard/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function getDashboardByNonexistentIdViaAPI(ApiTester $I)
    {
        $id = -1;
        $I->sendGET('/dashboard/' . $id);
        $I->seeResponseCodeIs(404);
    }

    public function getDashboardViaAPI(ApiTester $I)
    {
        $I->sendGET('/dashboards');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function createDashboardViaAPI(ApiTester $I)
    {
        $data = [
            'name' => 'TestDashboard',
        ];
        $json = json_encode($data);
        $I->sendPOST('/dashboard', $json);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":"ok"}');
    }

    public function changeDashboardViaAPI(ApiTester $I)
    {
        ApiTester::createDashboard($I);

        $data = [
            'name' => 'newTest',
        ];
        $json = json_encode($data);
        $id = $I->grabFromRepository(Dashboard::class, 'id', array('name' => 'TestDashboard'));
        $I->sendPUT('/dashboard/' . $id, $json);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseContains('{"status":"ok"}');
    }

    public function changeDashboardNonexistentIdViaAPI(ApiTester $I)
    {
        ApiTester::createDashboard($I);

        $data = [
            'name' => 'newTest',
        ];
        $json = json_encode($data);
        $id = -1;
        $I->sendPUT('/dashboard/' . $id, $json);
        $I->seeResponseCodeIs(404);
    }

    public function deleteDashboardViaAPI(ApiTester $I)
    {
        ApiTester::createDashboard($I);

        $id = $I->grabFromRepository(Dashboard::class, 'id', array('name' => 'TestDashboard'));
        $I->sendDELETE('/dashboard/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Dashboard removed!');
    }

    public function deleteDashboardNonexistentIdViaAPI(ApiTester $I)
    {
        ApiTester::createDashboard($I);

        $id = -1;
        $I->sendDELETE('/dashboard/' . $id);
        $I->seeResponseCodeIs(404);
    }
}
