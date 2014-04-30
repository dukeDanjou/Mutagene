<?php
// src/Mgn/CoreBundle/DataFixtures/ORM/User.php
 
namespace Mgn\UserBundle\DataFixtures\ORM;
 
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Mgn\UserBundle\Entity\User;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
 
class Users implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $user[0] = new User;
    $user[0]->setUsername('user');
    $user[0]->setSalt('rl8st4ji4sgwck0448okks8ow0ccgoo');
    $user[0]->setPassword('OeO19s5j6IRiNGN/OD1xuGYUy9n7AgPmZrPfUV8OoOkotuCxABisskP6KGEMGRA0fLQj9c9buKyoEayxZm9gkA==');
    $user[0]->setIsActive(true);
    $user[0]->setEmail('user@user.com');
    $user[0]->setTheme('mutagene');

    $manager->persist($user[0]);

    $user[1] = new User;
    $user[1]->setUsername('admin');
    $user[1]->setSalt('cchas2qwqp4o4800o848cs8coko00sc');
    $user[1]->setPassword('MiBOEj25q7m2dkQ7V8Ago1VoufQUoxY8MVkTUy0g7sq8NqP9gr2s2K2ivtRNikppM8ESOZy8JDizgSH90/LFRQ==');
    $user[1]->setIsActive(true);
    $user[1]->setEmail('admin@admin.com');
    $user[1]->addRole('ROLE_ADMIN');
    $user[1]->setTheme('mutagene');

    $manager->persist($user[1]);

    $user[2] = new User;
    $user[2]->setUsername('superadmin');
    $user[2]->setSalt('dxx4d8vuixkc8k4okkkgwow8wkkss8k');
    $user[2]->setPassword('2W1m1r3ZnjgtMqgpZqPdSBhjqFyyJKi0wWTFptVWwK4/kv7ELrXWvRP8EzAc5/xaMYEbRlkiAP16WagDIMlN0Q==');
    $user[2]->setIsActive(true);
    $user[2]->setEmail('superadmin@superadmin.com');
    $user[2]->addRole('ROLE_SUPER_ADMIN');
    $user[2]->setTheme('mutagene');

    $manager->persist($user[2]);
 
    // On déclenche l'enregistrement
    $manager->flush();
  }
}