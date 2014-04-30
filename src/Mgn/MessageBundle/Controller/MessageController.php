<?php

namespace Mgn\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

use Mgn\ArticleBundle\Entity\Categorie;
use Mgn\ArticleBundle\Entity\Article;
use Mgn\MessageBundle\Entity\Message;
use Mgn\MessageBundle\Form\MessageType;

use JMS\SecurityExtraBundle\Annotation\Secure;

class MessageController extends Controller
{
	/**
   	* @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
   	*/
	public function addToArticleAction($id)
	{
		$article = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnArticleBundle:Article')
                        ->findOneArticle($id);
						
		if( $article == null )
        {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }
		
        if( $article->getStatus() != 1 )
        {
            throw $this->createNotFoundException('Article non publié');
        }
		
        if( $article->getComments() == false )
        {
            throw $this->createNotFoundException('Commentaires désactivés');
        }

        $message = new Message;

		// On crée le FormBuilder grâce à la méthode du contrôleur.
		$form = $this->createForm(new MessageType, $message);
		
		$request = $this->get('request');

	    // On vérifie qu'elle est de type « POST ».
	    if( $request->getMethod() == 'POST' )
	    {
	        // On fait le lien Requête <-> Formulaire.
	        $form->bind($request);
	
	        // On vérifie que les valeurs rentrées sont correctes.
	        // (Nous verrons la validation des objets en détail plus bas dans ce chapitre.)
	        if( $form->isValid() )
	        {
	            // On l'enregistre notre objet $article dans la base de données.
	            $date = new \Datetime();
	            $user = $this->container->get('security.context')->getToken()->getUser();
	            
	            $em = $this->getDoctrine()->getManager();
				
				// On hydrate commentaire
				$message->setArticle($article);
				$message->setAuthor($user);
				$message->setUserIp($request->getClientIp());
				
				// On hydrate article
				$article->setCountComments($article->getCountComments() + 1);
				$article->setIdLastComment($message);
				$article->setDateLastComment($date);
				$article->setUserLastComment($user);
				// On diminue le compteur de lu pour éviter un recompte lors du post
				$article->setCountViews($article->getCountViews() -1);
				
				// On hydrate user
				$user->setCountMessage($user->getCountMessage() + 1);
				
				$em->persist($message);
				$em->persist($article);
				$em->flush();
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_article_read', array('id' => $article->getId(), 'category' => $article->getCategory()->getSlug(), 'slug' => $article->getSlug(), 'date' => $article->getDate()->format('d-m-Y') )).'#'.$message->getId().'' );
	        }
	    }

	    // On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_article_read', array('id' => $article->getId(), 'category' => $article->getCategory()->getSlug(), 'slug' => $article->getSlug(), 'date' => $article->getDate()->format('d-m-Y') )));
	}
}