<?php
namespace App\Tests\api;

use App\Entity\Columns;
use App\Entity\Tasks;
use App\Tests\ApiTester;

class TaskControllerCest
{
    public function getTaskByIdViaAPI(ApiTester $I)
    {
        ApiTester::createTask($I);

        $id = $I->grabFromRepository(Tasks::class, 'id', array('name' => 'TestTask'));
        $I->sendGET('/task/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function getTaskByNonexistentIdViaAPI(ApiTester $I)
    {
        $id = -1;
        $I->sendGET('/task/' . $id);
        $I->seeResponseCodeIs(404);
    }

    public function getTasksViaAPI(ApiTester $I)
    {
        $I->sendGET('/tasks');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function createTaskViaAPI(ApiTester $I)
    {
        $data = [
            'name' => 'TestTask',
            'description' => 'Some text',
        ];
        $json = json_encode($data);
        $I->sendPOST('/task', $json);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"status":"ok"}');
    }

    public function changeTaskViaAPI(ApiTester $I)
    {
        ApiTester::createTask($I);

        $data = [
            'name' => 'newTest',
            'description' => 'Some  new text',
        ];
        $json = json_encode($data);
        $id = $I->grabFromRepository(Tasks::class, 'id', array('name' => 'TestTask'));
        $I->sendPUT('/task/' . $id, $json);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseContains('{"status":"ok"}');
    }
    
    public function changeTaskNonexistentIdViaAPI(ApiTester $I)
    {
        ApiTester::createTask($I);

        $data = [
            'name' => 'newTest',
            'description' => 'Some  new text',
        ];
        $json = json_encode($data);
        $id = -1;
        $I->sendPUT('/task/' . $id, $json);
        $I->seeResponseCodeIs(404);
    }

    public function deleteTaskViaAPI(ApiTester $I)
    {
        ApiTester::createTask($I);

        $id = $I->grabFromRepository(Tasks::class, 'id', array('name' => 'TestTask'));
        $I->sendDELETE('/task/' . $id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('Task removed!');
    }

    public function deleteTaskNonexistentIdViaAPI(ApiTester $I)
    {
        ApiTester::createTask($I);

        $id = -1;
        $I->sendDELETE('/task/' . $id);
        $I->seeResponseCodeIs(404);
    }

    public function changeTaskColumnViaAPI(ApiTester $I)
    {
        ApiTester::createTask($I);

        $taskId = $I->grabFromRepository(Tasks::class, 'id', array('name' => 'TestTask'));
        $columnId = $I->grabFromRepository(Columns::class, 'id', array('name' => 'TestColumn'));
        $I->sendPUT('/move/'.$taskId.'/to/'.$columnId);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function changeColumnNonexistentTaskIdViaAPI(ApiTester $I)
    {
        ApiTester::createTask($I);
        
        $taskId = -1;
        $columnId = $I->grabFromRepository(Columns::class, 'id', array('name' => 'TestColumn'));
        $I->sendPUT('/move/'.$taskId.'/to/'.$columnId);
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('Task id not found');
    }

    public function changeColumnNonexistentColumnIdViaAPI(ApiTester $I)
    {
        ApiTester::createTask($I);

        $taskId = $I->grabFromRepository(Tasks::class, 'id', array('name' => 'TestTask'));
        $columnId = -1;
        $I->sendPUT('/move/'.$taskId.'/to/'.$columnId);
        $I->seeResponseCodeIs(401);
        $I->seeResponseContains('Column id not found');
    }
}
