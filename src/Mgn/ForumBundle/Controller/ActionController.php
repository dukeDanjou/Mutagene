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
use Mgn\ForumBundle\Form\PostType;
use Mgn\ForumBundle\Form\TopicTitreType;
use Mgn\ForumBundle\Form\MoveTopicType;


class ActionController extends Controller
{
    public function isActive()
    {
        $config = $this->container->get('mgn.config');
        
        if( $config->get('cmsForum') == false )
        {
            throw $this->createNotFoundException('Forum désactiver');
        }
    }

    public function addTopicAction($forum)
	{
		$this->isActive();

    	// Et pour vérifier que l'utilisateur est authentifié (et non un anonyme)
		if( ! is_object($user = $this->container->get('security.context')->getToken()->getUser()) )
		{
		    throw new AccessDeniedException('Vous n\'êtes pas authentifié.');
		}
		
		$topic = new Topic;
		
		$forum = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Forum')
                        ->find($forum);
						
		if( $forum == null )
        {
            return $this->render('MgnForumBundle:NotFound:forum.html.twig');
        }
		
		// acl
		$forumAclTopic = 0;

		$forumAclTopic = $forumAclTopic + $forum->getPublicAclTopic();

        if( $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $forumAclTopic = $forumAclTopic + 1;
        }

		if( $this->get('security.context')->isGranted('ROLE_FORUM_TOPIC_'.$forum->getId()))
        {
            $forumAclTopic = $forumAclTopic + 1;
        }
		
        if( $forumAclTopic == 0 )
        {
            throw $this->createNotFoundException('Acl insuffisant');
        }
						
		$topic->setForum($forum);
		
		// On crée le FormBuilder grâce à la méthode du contrôleur.
		$form = $this->createForm(new TopicType, $topic);
		
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
	            
	            $em = $this->getDoctrine()->getEntityManager();

	            $user = $this->container->get('security.context')->getToken()->getUser();
				
				$topic->getLastMessage()->setUserIp($request->getClientIp());
				$topic->getLastMessage()->setAuthor($user);
				
				$topic->setFirstMessage($topic->getLastMessage());
				$topic->getFirstMessage()->setTopic($topic);
				$topic->getFirstMessage()->setForum($forum);

				$user->setCountTopic($user->getCountTopic() + 1);
				$user->setCountPost($user->getCountPost() + 1);
				
				$forum->setLastMessage($topic->getLastMessage());
				$forum->setCountPosts($forum->getCountPosts() + 1);
				$forum->setCountTopics($forum->getCountTopics() + 1);
				$forum->setLastTopic($topic);

				$view = new View;
				
				$view->setDate(new \Datetime());
				$view->setForum($topic->getForum());
				$view->setUser($user);
				$view->setMessage($topic->getLastMessage());
				$view->setTopic($topic);
				$view->setStatus(1);

				$config = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

                $config->setTotalPosts($config->getTotalPosts()+1);	
                $config->setTotalTopics($config->getTotalTopics()+1);					

				$em->persist($view);
				$em->persist($topic);
				$em->persist($forum);
				$em->persist($config);
				$em->flush();
	
	            // On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $topic->getId(), 'slug' => $topic->getSlug())) );
	        }
	    }
		
		//on appel le template
        return $this->render('MgnForumBundle:Topics:readBlock.html.twig', array(
            'form' => $form->createView(),
            'forum' => $forum,
        ));
	}

    public function deletePostAction($postId, $page)
    {
    	$this->isActive();

        $em = $this->getDoctrine()->getManager();

		$user = $this->container->get('security.context')->getToken()->getUser();

		//on récupère les informations sur le message
		$message = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnMessageBundle:Message')
                        ->find($postId);
		
		// Si le message n'existe pas, on affiche une erreur 404
        if( $message == null )
        {
            throw $this->createNotFoundException('message[id='.$postId.'] inexistant');
        }

        //on récupère les informations sur le topic
		$topic = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Topic')
                        ->find($message->getTopic()->getId());
		
		// Si le topic n'existe pas, on affiche une erreur 404
        if( $topic == null )
        {
            throw $this->createNotFoundException('Topic[id='.$topicId.'] inexistant');
        }

        //on récupère les informations sur le forum
		$forum = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Forum')
                        ->find($topic->getForum()->getId());
		
		// Si le forum n'existe pas, on affiche une erreur 404
        if( $forum == null )
        {
            throw $this->createNotFoundException('Forum inexistant');
        }

         // On supprime les views associé au topic
		$views = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:View')
                        ->findByMessage($message);

        // acl
        $forumAclDelete = 0;

        if( $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $forumAclDelete = $forumAclDelete + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_DELETE_'.$topic->getForum()->getId()))
        {
            $forumAclDelete = $forumAclDelete + 1;
        }
        
        if( $forumAclDelete == 0 )
        {
            throw $this->createNotFoundException('Acl insuffisant');
        }

        // On vérifie que ce n'est pas le premier post du topic
        if( $message == $topic->getFirstMessage() )
        {
        	throw $this->createNotFoundException('Tentative de suppression du premier post dun topic.');
        }


    	$lastMessages = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('MgnMessageBundle:Message')
                    ->findBy(array('topic' => $topic),
                                 array('id' => 'desc'),
                                 1,
                                 1);

        $lastMessage = $lastMessages[0];

        if( $message == $topic->getLastMessage() )
        {
			$topic->setLastMessage($lastMessage);

        	$forum->setLastMessage($lastMessage);
        }

        // on met à jour les views
        if( $views != null )
        {
        	foreach($views as $view)
	    	{
	    		$em->remove($view);
	    	}
        }

        $em->flush();

        $message->getAuthor()->setCountPost($message->getAuthor()->getCountPost() - 1);

        $topic->setCountPosts($topic->getCountPosts() - 1);

        $forum->setCountPosts($forum->getCountPosts() - 1);

        $topic->setCountViews($topic->getCountViews() - 1);

        $em->remove($message);

        $em->flush();

        // on redirige vers la categorie
        $this->get('session')->getFlashBag()->add('success', 'Le message à bien été supprimé.');

        return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $topic->getId(), 'slug' => $topic->getSlug(), 'page' => $page)));
    }

    public function postitTopicAction($topicId)
    {
    	$this->isActive();

    	//on récupère les informations sur le topic
		$topic = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Topic')
                        ->find($topicId);
		
		// Si le topic n'existe pas, on affiche une erreur 404
        if( $topic == null )
        {
            throw $this->createNotFoundException('Topic[id='.$topicId.'] inexistant');
        }

        // acl
        $forumAclPostit = 0;

        if( $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $forumAclPostit = $forumAclPostit + 1;
        }

        if( $this->get('security.context')->isGranted('ROLE_FORUM_POSTIT_'.$topic->getForum()->getId()))
        {
            $forumAclPostit = $forumAclPostit + 1;
        }
        
        if( $forumAclPostit == 0 )
        {
            throw $this->createNotFoundException('Acl insuffisant');
        }

        $em = $this->getDoctrine()->getManager();

        if( $topic->getTypeTopic() == 'normal' )
        {
        	$topic->setTypeTopic('postit');

        	$topic->setCountViews($topic->getCountViews() - 1);

        	$this->get('session')->getFlashBag()->add('success', 'Le sujet à bien été épinglé.');
        }
        elseif( $topic->getTypeTopic() == 'postit' )
        {
        	$topic->setTypeTopic('normal');

        	$topic->setCountViews($topic->getCountViews() - 1);

        	$this->get('session')->getFlashBag()->add('success', 'Le sujet n\'est plus épinglé.');
        }

        $em->flush();

        // On confirme et on redirige
		return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $topic->getId(), 'slug' => $topic->getSlug())));
    }

    public function moveTopicAction($topicId, $forumId)
    {
    	$this->isActive();

    	$em = $this->getDoctrine()->getManager();

    	// acl
		$topicAclMove = 0;

        if( $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
            $topicAclMove = 1;
        }

        if( $topicAclMove == 0 )
        {
            throw $this->createNotFoundException('Acl insuffisant');
        }

    	//on récupère les informations sur le topic
		$topic = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Topic')
                        ->findOneByIdWithLastPost($topicId);
		
		// Si le topic n'existe pas, on affiche une erreur 404
        if( $topic == null )
        {
            throw $this->createNotFoundException('Topic[id='.$topicId.'] inexistant');
        }

        $forums = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnForumBundle:Forum')
                         ->findAllWithCategories();

        if( $forumId != NULL )
        {
        	$forumOld = $this->getDoctrine()
	                        ->getManager()
	                        ->getRepository('MgnForumBundle:Forum')
	                        ->find($topic->getForum()->getId());

	        // Si le topic n'existe pas, on affiche une erreur 404
	        if( $forumOld == null )
	        {
	            throw $this->createNotFoundException('Categorie[id='.$topic->getForum()->getId().'] inexistante');
	        }

	        $forumNew = $this->getDoctrine()
	                        ->getManager()
	                        ->getRepository('MgnForumBundle:Forum')
	                        ->findOneByIdWithLastMessage($forumId);

	        // Si le topic n'existe pas, on affiche une erreur 404
	        if( $forumNew == null )
	        {
	            throw $this->createNotFoundException('Categorie[slug='.$forumId.'] inexistante');
	        }

	        // On update l'ancienne catégorie
			$forumOld->setCountTopics($forumOld->getCountTopics() - 1);
			$forumOld->setCountPosts($forumOld->getCountPosts() - $topic->getCountPosts());
			if( $forumOld->getLastTopic() == $topic )
			{
				$forumOld->setLastTopic(null);
				$forumOld->setLastMessage(null);
			}

			$em->flush();

			// On update la nouvelle catégorie
			$forumNew->setCountTopics($forumNew->getCountTopics() + 1);
			$forumNew->setCountPosts($forumNew->getCountPosts() + $topic->getCountPosts());

			if( $forumNew->getLastMessage() != null )
			{
				if( $topic->getLastMessage()->getDate() >= $forumNew->getLastMessage()->getDate() )
				{
					$forumNew->setLastTopic($topic);
					$forumNew->setLastMessage($topic->getLastMessage());
				}
			}
			else
			{
				$forumNew->setLastTopic($topic);
				$forumNew->setLastMessage($topic->getLastMessage());
			}

			// On update le topic
			$topic->setForum($forumNew);

			// On supprime les views associé au topic
			$views = $this->getDoctrine()
	                        ->getManager()
	                        ->getRepository('MgnForumBundle:View')
	                        ->findByTopic($topic);

	        foreach($views as $view)
			{
				$em->remove($view);
			}

			// on valide le tout
        	$em->flush();

        	if( $forumOld->getLastTopic() == null )
        	{
        		$lastTopics = $this->getDoctrine()
		                        ->getManager()
		                        ->getRepository('MgnForumBundle:Topic')
		                        ->findBy(array('forum' => $forumOld),
		                                     array('id' => 'desc'),
		                                     1,
		                                     0);

		            if( $lastTopics == null )
		            {
		            	$forumOld->setLastTopic(null);

		            	$forumOld->setLastMessage(null);
		            }
		            else
		            {
		            	$forumOld->setLastTopic($lastTopics[0]);

			            $forumOld->setLastMessage($lastTopics[0]->getLastMessage());
		            }

				$em->flush();
        	}

        	// On confirme et on redirige
        	$this->get('session')->getFlashBag()->add('success', 'Le sujet à bien été déplacé.');
			return $this->redirect( $this->generateUrl('mgn_forum_topic_read', array('id' => $topic->getId(), 'slug' => $topic->getSlug())));
		}

        //on appel le template
        return $this->render('MgnForumBundle:Topics:move.html.twig', array(
            'topic' => $topic,
            'forums' => $forums,
        ));
    }

    public function deleteTopicAction($topic)
    {
    	$this->isActive();

    	//on récupère les informations sur le topic
		$topic = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Topic')
                        ->find($topic);
		
		// Si le topic n'existe pas, on affiche une erreur 404
        if( $topic == null )
        {
            throw $this->createNotFoundException('Topic[id='.$topic.'] inexistant');
        }

        //on récupère les informations sur le forum
		$forum = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Forum')
                        ->find($topic->getForum()->getId());
		
		// Si le forum n'existe pas, on affiche une erreur 404
        if( $forum == null )
        {
            throw $this->createNotFoundException('Forum inexistant');
        }

        $em = $this->getDoctrine()->getManager();

		$user = $this->container->get('security.context')->getToken()->getUser();

		// acl
        $forumAclDelete = 0;

		if( $this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
        	$forumAclDelete = $forumAclDelete + 1;
        }

		if( $this->get('security.context')->isGranted('ROLE_FORUM_DELETE_'.$topic->getForum()->getId()))
        {
            $forumAclDelete = $forumAclDelete + 1;
        }

        if( $forumAclDelete == 0 )
        {
            throw $this->createNotFoundException('Acl insuffisant');
        }

        // On supprime les views associé au topic
		$views = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:View')
                        ->findByTopic($topic);

        foreach($views as $view)
		{
			$em->remove($view);
		}

		$em->flush();

		$forum->setLastTopic(null);

        $forum->setLastMessage(null);

        $em->flush();


        $topic->getFirstMessage()->getAuthor()->setCountTopic($topic->getFirstMessage()->getAuthor()->getCountTopic() - 1);

        $topic->setLastMessage(null);
        $topic->setFirstMessage(null);

        // -1 nombre topic dans la forum
        $forum->setCountTopics($forum->getCountTopics() - 1);

        // on supprime les messages du topic
        $messages = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnMessageBundle:Message')
                        ->findByTopic($topic);

        foreach($messages as $message)
		{
			// -1 nombre message par user par message
			$message->getAuthor()->setCountPost($message->getAuthor()->getCountPost() - 1);
			
			// - nombre de message dans la forum
			$forum->setCountPosts($forum->getCountPosts() - 1);

			$em->remove($message);
		}
        $em->flush();

        // on supprime le topic
        $em->remove($topic);

        // on valide le tout
        $em->flush();

        // Si dernier topic du forum on le change
		$lastTopics = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Topic')
                        ->findBy(array('forum' => $forum),
                                     array('id' => 'desc'),
                                     1,
                                     0);

            if( $lastTopics == null )
            {
            	$forum->setLastTopic(null);

            	$forum->setLastMessage(null);
            }
            else
            {
            	$forum->setLastTopic($lastTopics[0]);

	            $forum->setLastMessage($lastTopics[0]->getLastMessage());
            }
        $em->flush();

        // on redirige vers le forum
        $this->get('session')->getFlashBag()->add('success', 'Le sujet à bien été supprimé.');

        return $this->redirect( $this->generateUrl('mgn_forum_view', array('id' => $forum->getId(), 'forum' => $forum->getSlug())));
    }
}