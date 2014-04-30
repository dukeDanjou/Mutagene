<?php

namespace Mgn\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Mgn\ForumBundle\Entity\Forum;
use Mgn\ForumBundle\Entity\Category;

use Mgn\ForumBundle\Form\ForumType;
use Mgn\ForumBundle\Form\CategoryType;

class AdminController extends Controller
{
	public function forumAction()
	{
		$categories = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnForumBundle:Category')
                      	 ->findAllWithForums();

        $category = new Category;

        $formCategory = $this->createForm(new CategoryType, $category);

        $forum = new Forum;

        $formForum = $this->createForm(new ForumType, $forum);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST')
        {
        	if ($request->request->has('mgn_forumbundle_categorytype'))
            {
                $formCategory->bind($request);

                if ($formCategory->isValid())
                {
                    // On l'enregistre notre objet $category dans la base de données.
		            $em = $this->getDoctrine()->getManager();
					
					$em->persist($category);
					$em->flush();
					
					//message de confirmation
					$this->get('session')->getFlashBag()->add('success', 'La catégorie "'.$category->getName().'" à bien été ajouté.');
					
					// On redirige vers la page d'accueil, par exemple.
		            return $this->redirect( $this->generateUrl('mgn_admin_forum'));
                }
            }

            if ($request->request->has('mgn_forumbundle_forumtype'))
            {
                $formForum->bind($request);

                if ($formForum->isValid())
                {
                    // On l'enregistre notre objet $forum dans la base de données.
		            $em = $this->getDoctrine()->getManager();
					
					$em->persist($forum);
					$em->flush();
					
					//message de confirmation
					$this->get('session')->getFlashBag()->add('success', 'Le forum "'.$forum->getName().'" à bien été ajouté.');
					
					// On redirige vers la page d'accueil, par exemple.
		            return $this->redirect( $this->generateUrl('mgn_admin_forum'));
                }
            }
        }
			
		return $this->render('MgnForumBundle:Admin:forums.html.twig', array(
			'formCategory' => $formCategory->createView(),
			'formForum' => $formForum->createView(),
			'categories' => $categories,
		));
	}

	public function categoryEditAction($id)
	{
		$category = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnForumBundle:Category')
                      	 ->find($id);

        $form = $this->createForm(new CategoryType, $category);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if ($form->isValid())
            {
                // On l'enregistre notre objet $category dans la base de données.
	            $em = $this->getDoctrine()->getManager();
				
				$em->persist($category);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre catégorie à bien été modifié.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_forum'));
            }
        }
			
		return $this->render('MgnForumBundle:Admin:categoryEdit.html.twig', array(
			'form' => $form->createView(),
			'category' => $category,
		));
	}

	public function categoryDeleteAction($id)
	{

	}

	public function forumEditAction($id)
	{
		$forum = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnForumBundle:Forum')
                      	 ->find($id);

        $form = $this->createForm(new ForumType, $forum);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if ($form->isValid())
            {
                // On l'enregistre notre objet $forum dans la base de données.
	            $em = $this->getDoctrine()->getManager();
				
				$em->persist($forum);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre forum à bien été modifié.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_forum'));
            }
        }
			
		return $this->render('MgnForumBundle:Admin:forumEdit.html.twig', array(
			'form' => $form->createView(),
			'forum' => $forum,
		));
	}

	public function forumDeleteAction($id)
	{
		
	}
}