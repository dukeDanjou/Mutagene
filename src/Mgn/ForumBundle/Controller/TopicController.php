<?php

namespace Mgn\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mgn\ForumBundle\Entity\Topic;
use Mgn\MessageBundle\Entity\Message;
use Mgn\ForumBundle\Entity\View;
use Mgn\ForumBundle\Entity\Mark;
use Mgn\CoreBundle\Entity\Config;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

use Mgn\ForumBundle\Form\TopicType;
use Mgn\MessageBundle\Form\MessageType;
use Mgn\ForumBundle\Form\TopicTitleType;
use Mgn\ForumBundle\Form\MoveTopicType;


class TopicController extends Controller
{
    public function isActive()
    {
        $config = $this->container->get('mgn.config');
        
        if( $config->get('cmsForum') == false )
        {
            throw $this->createNotFoundException('Forum désactiver');
        }
    }

    public function readAction($id, $slug, $page, $postediter, $topicediter, $topicfermer, $postmodo)
	{
		$this->isActive();

    	// On ne sait pas combien de pages il y a, mais on sait qu'une page
        // doit être supérieure ou égale à 1.
        if( $page < 1 )
        {
            $page = 1;
        }

        $config = $this->container->get('mgn.config');
        $nb_posts_page = $config->get('ForumPostPage');
		
		//on récupère les informations sur le topic
		$topic = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Topic')
                        ->findOneWithPosts($id, $page, $nb_posts_page);
		
		// Si le forum n'existe pas, on affiche une erreur 404
        if( $topic == null )
        {
            return $this->render('MgnForumBundle:NotFound:topic.html.twig');
        }
		
		// acl
		$forumAclView = 0;
		$forumAclPost = 0;
		$forumAclTopic = 0;
        $forumAclLock = 0;
        $forumAclPostit = 0;
        $forumAclEdit = 0;
        $forumAclMove = 0;
        $forumAclDelete = 0;
		
		$forumAclView = $forumAclView + $topic->getForum()->getPublicAclView();
		$forumAclPost = $forumAclPost + $topic->getForum()->getPublicAclPost();
		$forumAclTopic = $forumAclTopic + $topic->getForum()->getPublicAclTopic();

		if( $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $forumAclView = $forumAclView + 1;
			$forumAclPost = $forumAclPost + 1;
			$forumAclTopic = $forumAclTopic + 1;
        	$forumAclLock = $forumAclLock + 1;
        	$forumAclPostit = $forumAclPostit + 1;
        	$forumAclEdit = $forumAclEdit + 1;
        	$forumAclMove = $forumAclMove + 1;
        	$forumAclDelete = $forumAclDelete + 1;
        }

		if( $this->get('security.context')->isGranted('ROLE_FORUM_READ_'.$topic->getForum()->getId()))
        {
            $forumAclView = $forumAclView + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_POST_'.$topic->getForum()->getId()))
        {
            $forumAclPost = $forumAclPost + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_TOPIC_'.$topic->getForum()->getId()))
        {
            $forumAclTopic = $forumAclTopic + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_LOCK_'.$topic->getForum()->getId()))
        {
            $forumAclLock = $forumAclLock + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_POSTIT_'.$topic->getForum()->getId()))
        {
            $forumAclPostit = $forumAclPostit + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_EDIT_'.$topic->getForum()->getId()))
        {
            $forumAclEdit = $forumAclEdit + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_MOVE_'.$topic->getForum()->getId()))
        {
            $forumAclMove = $forumAclMove + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_DELETE_'.$topic->getForum()->getId()))
        {
            $forumAclDelete = $forumAclDelete + 1;
        }
		
        if( $forumAclView == 0 )
        {
            return $this->render('MgnForumBundle:NotAcl:forum.html.twig');
        }
		
		$em = $this->getDoctrine()->getManager();
		
		$user = $this->container->get('security.context')->getToken()->getUser();
		
		// On calcule le nombre total de pages
        $nb_pages = ceil($topic->getCountPosts()/$nb_posts_page);
		
		if( $page < 1 OR $page > $nb_pages )
        {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }

        if( $postediter != NULL )
        {
            $message = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnMessageBundle:Message')
                        ->find($postediter);

            // acl
			$postAclEdit = 0;

			if( $forumAclEdit != 0 )
	        {
	            $postAclEdit = $postAclEdit + 1;
	        }

			if( $message->getAuthor() == $user)
	        {
	            $postAclEdit = $postAclEdit + 1;
	        }

	        if( $postAclEdit == 0 )
	        {
	            throw $this->createNotFoundException('Acl insuffisant');
	        }
        }
        elseif( $topicediter != NULL )
        {
        	// acl
        	$topicAclEdit = 0;

			if( $forumAclEdit != 0 )
	        {
	            $topicAclEdit = $topicAclEdit + 1;
	        }

			if( $topic->getFirstMessage()->getAuthor() == $user)
	        {
	            $topicAclEdit = $topicAclEdit + 1;
	        }

	        if( $topicAclEdit == 0 )
	        {
	            return $this->render('MgnForumBundle:NotAcl:topicPost.html.twig');
	        }
        }
        elseif( $topicfermer != NULL )
        {
        	// acl
			if( $forumAclLock == 0 )
	        {
	            throw $this->createNotFoundException('Acl insuffisant');
	        }

        	if( $topic->getTopicLock() == true )
        	{
        		$topic->setTopicLock(false);

        		$topic->setCountViews($topic->getCountViews() - 1);

        		$this->get('session')->getFlashBag()->add('success', 'Le sujet à bien été dévérouillé.');
        	}
        	else
        	{
        		$topic->setTopicLock(true);

        		$topic->setCountViews($topic->getCountViews() - 1);

        		$this->get('session')->getFlashBag()->add('success', 'Le sujet à bien été vérouillé.');
        	}
        }
        elseif( $postmodo != NULL )
        {
        	// acl

	        if( $forumAclLock == 0 )
	        {
	            throw $this->createNotFoundException('Acl insuffisant');
	        }

	        $postlock = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnMessageBundle:Message')
                        ->find($postmodo);

            $postLink = $postmodo - 1;

            $topic->setCountViews($topic->getCountViews() - 2);

        	if( $postlock->getMessageLock() == true )
        	{
        		$postlock->setMessageLock(false);
		        
		        $em->flush();

        		return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $id, 'slug' => $topic->getSlug(), 'page' => $page)).'#'.$postLink.'' );
        	}
        	else
        	{
        		$postlock->setMessageLock(true);
		        
		        $em->flush();

        		return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $id, 'slug' => $topic->getSlug(), 'page' => $page)).'#'.$postLink.'' );
        	}
        }
        
        if( $postediter == NULL and $topicediter == NULL )
        {
        	//on cré le formulaire de réponse
			$message = new Message;
        }
		
		// On crée le FormBuilder grâce à la méthode du contrôleur.
		if( $topicediter != NULL )
		{
			$form = $this->createForm(new TopicTitleType, $topic);
		}
		else
		{
			$form = $this->createForm(new MessageType, $message);
		}

		$request = $this->get('request');

		// On vérifie qu'elle est de type « POST ».
	    if( $request->getMethod() == 'POST' )
	    {
	    	if( $forumAclPost == 0 )
	        {
	            return $this->render('MgnForumBundle:NotAcl:topicPost.html.twig');
	        }

	        // On fait le lien Requête <-> Formulaire.
	        $form->bind($request);
	
	        // On vérifie que les valeurs rentrées sont correctes.
	        // (Nous verrons la validation des objets en détail plus bas dans ce chapitre.)
	        if( $form->isValid() )
	        {
		        $date = new \Datetime();
		            
		        $em = $this->getDoctrine()->getManager();
						
				// On redirige vers la page d'accueil, par exemple.
				if( $postediter != NULL )
		        {
		        	//on met à jour le message
					$message->setDateEdit($date);
					$message->setCountEdit($message->getCountEdit() + 1);

					$topic->setCountViews($topic->getCountViews() - 2);

					$em->flush();

					$this->get('session')->getFlashBag()->add($postediter, 'Le message à bien été modifié.');

		            return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $id, 'slug' => $slug, 'page' => $page)).'#'.$message->getId().'' );
		        }
		        elseif( $topicediter != NULL )
		        {
		        	$topic->setCountViews($topic->getCountViews() - 2);
		        	
		        	$em->flush();

		        	$this->get('session')->getFlashBag()->add('success', 'Le titre du sujet à bien été modifié.');

		        	return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $topic->getId(), 'slug' => $topic->getSlug(), 'page' => $page)) );
		        }
		        elseif( $postediter == NULL and $topicediter == NULL )
		        {
		        	// On l'enregistre notre objet $article dans la base de données.
		            // On met à jour le message
					$message->setAuthor($user);
					$message->setTopic($topic);
					$message->setUserIp($request->getClientIp());
					$message->setForum($topic->getForum());

					// On met à jour le topic
					$topic->setCountPosts($topic->getCountPosts() + 1);
					$topic->setLastMessage($message);
					$topic->setCountViews($topic->getCountViews() - 2);
					
					// On met à jour le forum
					$topic->getForum()->setCountPosts($topic->getForum()->getCountPosts() + 1);
					$topic->getForum()->setLastMessage($message);
					$topic->getForum()->setLastTopic($topic);

					$user->setCountPost($user->getCountPost() + 1);

					if( is_object($user) )
					{
						$view = $this->getDoctrine()
				                           ->getManager()
				                           ->getRepository('MgnForumBundle:View')
				                           ->findViewsForTopic($user, $topic);
												
						if ($view != NULL)
						{
							$view->setDate(new \Datetime());
							$view->setMessage($topic->getLastMessage());
						}
						else
						{
							$view = new View;
							
							$view->setDate(new \Datetime());
							$view->setForum($topic->getForum());
							$view->setUser($user);
							$view->setMessage($topic->getLastMessage());
							$view->setTopic($topic);
						}

						$view->setStatus(1);

						$em->persist($view);
					}

					$config = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

                	$config->setTotalPosts($config->getTotalPosts()+1);	
					
					$em->persist($config);
					$em->persist($message);
					$em->persist($topic);
					$em->flush();
					
					$config = $this->container->get('mgn.config');

	        		$nb_posts_page = $config->get('ForumPostPage');

					$nb_posts = $topic->getCountPosts();
					// On calcule le nombre total de pages
			        $page = ceil($nb_posts/$nb_posts_page);

		        	return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $id, 'slug' => $slug, 'page' => $page)).'#'.$message->getId().'' );
		        }
	            
	        }
	    }

		$topic->setCountViews($topic->getCountViews() +1);

		if( is_object($user) )
		{
			$view = $this->getDoctrine()
	                           ->getManager()
	                           ->getRepository('MgnForumBundle:View')
	                           ->findViewsForTopic($user, $topic);
									
			if ($view != NULL)
			{
				$view->setDate(new \Datetime());
				$view->setMessage($topic->getLastMessage());
			}
			else
			{
				$view = new View;
				
				$view->setDate(new \Datetime());
				$view->setForum($topic->getForum());
				$view->setUser($user);
				$view->setMessage($topic->getLastMessage());
				$view->setTopic($topic);
				$view->setStatus(0);
			}

			$em->persist($view);
		}

		$em->persist($topic);
		$em->flush();

		//on appel le template
        return $this->render('MgnForumBundle:Topics:readBlock.html.twig', array(
            'form' => $form->createView(),
            'page'     => $page,
            'nb_pages' => $nb_pages,
            'topic' => $topic,
            'forumAclPost' => $forumAclPost,
            'forumAclTopic' => $forumAclTopic,
            'forumAclLock' => $forumAclLock,
            'forumAclPostit' => $forumAclPostit,
            'forumAclEdit' => $forumAclEdit,
            'forumAclMove' => $forumAclMove,
            'forumAclDelete' => $forumAclDelete,
            'postediter' => $postediter,
            'topicediter' => $topicediter,
        ));
	}
}