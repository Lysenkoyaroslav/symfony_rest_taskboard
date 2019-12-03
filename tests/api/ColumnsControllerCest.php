<?php


namespace App\Tests\api;


use App\Entity\Columns;

use App\Tests\ApiTester;

class ColumnsControllerCest
{

    public function getColumnByIdViaAPI(ApiTester $I)
    {

        ApiTester::createColumn($I);

        $id = $I->grabFromRepository(Columns::class, 'id', array('name' => 'TestColumn'));
        $I->sendGET('/column/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();


    }


    public function getColumnByNonexistentIdViaAPI(ApiTester $I)
    {

        $id = -1;
        $I->sendGET('/Column/' . $id);
        $I->seeResponseCodeIs(404);

    }

    public function getColumnsViaAPI(ApiTester $I)
    {
        $I->sendGET('/columns');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function createColumnViaAPI(ApiTester $I)
    {
        $data = [
            'name' => 'TestColumn',
        ];
        $json = json_encode($data);


        $I->sendPOST('/column', $json);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":"ok"}');

    }

    public function changeColumnViaAPI(ApiTester $I)
    {
        ApiTester::createColumn($I);

        $data = [
            'name' => 'newTest',

        ];

        $json = json_encode($data);

        $id = $I->grabFromRepository(Columns::class, 'id', array('name' => 'TestColumn'));

        $I->sendPUT('/column/' . $id, $json);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseContains('{"status":"ok"}');
    }


    public function deleteColumnViaAPI(ApiTester $I)
    {
        ApiTester::createColumn($I);

        $id = $I->grabFromRepository(Columns::class, 'id', array('name' => 'TestColumn'));

        $I->sendDELETE('/column/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Column removed!');

    }

}