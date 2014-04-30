<?php
// src/Mgn/ArticleBundle/DataFixtures/ORM/Article.php
 
namespace Mgn\MediaBundle\DataFixtures\ORM;
 
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Mgn\MediaBundle\Entity\Picture;
use Mgn\MediaBundle\Entity\Gallery;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
 
class Media implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $gallery = new Gallery;
    $gallery->setName('Non classé');
    $gallery->setUrl('non-classe');
    $gallery->setPublicAclView(false);

    $manager->persist($gallery);
 
    // On déclenche l'enregistrement
    $manager->flush();
  }
}