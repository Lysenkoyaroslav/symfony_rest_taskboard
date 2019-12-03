<?php

namespace App\Tests;

use App\Entity\Columns;
use App\Entity\Dashboard;
use App\Entity\Tasks;
use App\Entity\Users;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    public static function createAuthUser(ApiTester $I)
    {
        $I->haveInRepository(Users::class, array(
            'userName' => 'Davert',
            'password' => '12345',
            'email' => 'test@mail.com',
            'apiToken' => '023asda11nkld1lkdqlknff',
            'roles' => array(
                'roles' => 'Admin',
            ),
            'status' => array(
                'name' => 'Verified'
            ),
            'temporaryToken' => null));

        $apiToken = $I->grabFromRepository(Users::class, 'apiToken', array('userName' => 'Davert'));
        $I->haveHttpHeader('apiToken', $apiToken);
    }

    public static function createUserNoToken(ApiTester $I)
    {
        $I->haveInRepository(Users::class, array(
            'userName' => 'Davert',
            'password' => '12345',
            'email' => 'test@mail.com',
            'apiToken' => null,
            'roles' => array(
                'roles' => 'Admin',
            ),
            'status' => array(
                'name' => 'Verified'
            ),
            'temporaryToken' => null));
    }

    public static function createTask(ApiTester $I)
    {
        $I->haveInRepository(Tasks::class, array(
                'name' => 'TestTask',
                'description' => 'some text',
                'columns' => array(
                    'name' => 'TestColumn',
                    'dashboard' => array(
                        'name' => 'TestDashboard'
                    )
                ))
        );
    }

    public static function createColumn(ApiTester $I)
    {
        $I->haveInRepository(Columns::class, array(
                'name' => 'TestColumn',
                'dashboard' => array(
                    'name' => 'TestDashboard',
                ))
        );
    }

    public static function createDashboard(ApiTester $I)
    {
        $I->haveInRepository(Dashboard::class, array(
                'name' => 'TestDashboard',
            )
        );
    }
}
