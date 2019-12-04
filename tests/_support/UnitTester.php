<?php
namespace App\Tests;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    public static function createUser()
    {
       $user=new Users();

       $user->setUserName('Davert');
       $user->setPassword('12345');
       $user->setEmail('davert@mail.com');
       $user->setApiToken('149030joifqjd');

       return $user;

    }

}
