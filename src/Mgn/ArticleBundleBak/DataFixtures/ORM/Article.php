<?php
// src/Mgn/ArticleBundle/DataFixtures/ORM/Article.php
 
namespace Mgn\ArticleBundle\DataFixtures\ORM;
 
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Mgn\ArticleBundle\Entity\Category;
use Mgn\ArticleBundle\Entity\Article;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
 
class Articles implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $category = new Category;
    $category->setName('Non classé');
    $category->setUrl('non-classe');

    $manager->persist($category);
 
    // On déclenche l'enregistrement
    $manager->flush();
  }
}