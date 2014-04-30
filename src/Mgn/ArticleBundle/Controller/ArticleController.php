<?php

namespace Mgn\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mgn\ArticleBundle\Entity\Article;
use Mgn\MessageBundle\Entity\Message;
use Mgn\MessageBundle\Form\MessageType;

class ArticleController extends Controller
{
	public function indexAction()
	{
		$config = $this->container->get('mgn.config');
		
		$articleCountIndex = $config->get('articleCountIndex');
			
		$articles = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->findBy(
				            array('status' => true),                 // Pas de critère
				            array('dateTop' => 'DESC'), // On tri par date décroissante
				            $articleCountIndex,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
			
		return $this->render('MgnArticleBundle:Article:index.html.twig', array(
			'articles' => $articles,
		));
	}
	
	public function readAction($id)
	{
		$article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->findOneArticle($id);
		
		if( $article == null )
        {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }

        $messages = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnMessageBundle:Message')
                      	 ->findBy(
				            array('article' => $article->getId()),                 // Pas de critère
				            array(), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );

        $countView = $article->getCountViews() +1;
		$article->setCountViews($countView);
		
		$em = $this->getDoctrine()->getManager();
		
		$em->persist($article);
		
		$em->flush();
		
		$message = new Message;

		// On crée le FormBuilder grâce à la méthode du contrôleur.
		$form = $this->createForm(new MessageType, $message);
						 
		return $this->render('MgnArticleBundle:Article:lire.html.twig', array(
            'form' => $form->createView(),
			'article' => $article,
			'messages' => $messages,
		));
	}

	public function listCategoriesAction()
	{
		$categories = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->findBy(
				            array(),                 // Pas de critère
				            array('name' => 'ASC'), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
		 
		return $this->render('MgnArticleBundle:Categories:list.html.twig', array(
		'categories' => $categories // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
		));
	}

	public function archivesAction($filtre)
	{
		if ($filtre == 'all')
		{
			$articles = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->findBy(
				            array('status' => 1),                 // Pas de critère
				            array('dateTop' => 'DESC'), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
		}
		else
		{
			$articles = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->findArticlesByCategorySlug($filtre);
		}

        $categories = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->findAll();
			
		return $this->render('MgnArticleBundle:Article:archives.html.twig', array(
			'articles' => $articles,
			'categories' => $categories,
			'filtre' => $filtre,
		));
	}
}
