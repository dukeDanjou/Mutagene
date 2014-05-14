<?php
namespace Mgn\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mgn\ArticleBundle\Entity\Category;
use Mgn\ArticleBundle\Form\CategoryType;
use Mgn\ArticleBundle\Entity\Article;
use Mgn\ArticleBundle\Form\ArticleType;
use Mgn\ArticleBundle\Form\ArticlePublishType;
use Mgn\ArticleBundle\Form\ArticleEditType;
use Mgn\CoreBundle\Entity\Config;
use Mgn\MediaBundle\Entity\Picture;
use Mgn\MediaBundle\Form\PictureArticleType;

use Mgn\ArticleBundle\Entity\Contents;
use Mgn\ArticleBundle\Form\ContentsType;
use Mgn\ArticleBundle\Form\ContentsParagraphType;
use Mgn\ArticleBundle\Form\ArticleTitleType;
use Mgn\ArticleBundle\Form\ArticleHeaderType;
use Mgn\ArticleBundle\Form\ArticleIntroductionType;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use JMS\SecurityExtraBundle\Annotation\Secure;

class AdminController extends Controller
{
	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function articleListAction($status)
	{
		$repository = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article');

        if ( $status == 'publish' )
        {
        	$articles = $repository->findBy(
				            array('status' => 'publish'),                 // Pas de critère
				            array('dateTop' => 'DESC'), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
        }
        if ( $status == 'pending' )
        {
        	$articles = $repository->findBy(
				            array('status' => 'pending'),                 // Pas de critère
				            array('dateTop' => 'DESC'), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
        }
        if ( $status == 'draft' )
        {
        	$articles = $repository->findBy(
				            array('status' => 'draft'),                 // Pas de critère
				            array('dateTop' => 'DESC'), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
        }
			
		return $this->render('MgnArticleBundle:Admin:articleList.html.twig', array(
			'articles' => $articles,
			'status' => $status,
		));
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function categoryAction()
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
			
		$category = new Category;

        $form = $this->createForm(new CategoryType, $category);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if ($form->isValid())
            {
                // On l'enregistre notre objet $category dans la base de données.
	            $em = $this->getDoctrine()->getManager();

	            if ($category->getUrl() == null)
	            {
	            	$category->setUrl($category->getName());
	            }
				
				$em->persist($category);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre catégorie à bien été ajouté.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_article_category'));
            }
        }

		return $this->render('MgnArticleBundle:Admin:categories.html.twig', array(
			'form' => $form->createView(),
			'categories' => $categories,
		));
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function categoryEditAction($id)
	{
		$category = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
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

	            if ($category->getUrl() == null)
	            {
	            	$category->setUrl($category->getName());
	            }
				
				$em->persist($category);
				$em->flush();

				$category->setUrl($category->getSlug());
				
				$em->persist($category);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre galerie à bien été modifié.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_article_category'));
            }
        }
			
		return $this->render('MgnArticleBundle:Admin:categoryEdit.html.twig', array(
			'form' => $form->createView(),
			'category' => $category,
		));
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function categoryDeleteAction($id)
	{
		//on récupère les informations sur la catégorie
		$category = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->find($id);

        if( $category == null )
        {
            throw $this->createNotFoundException('Categorie [id='.$id.'] inexistant');
        }

        $categoryDefault = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->findCategoryDefault();

        //on récupère les articles de la catégorie
        $articles = $this->getDoctrine()
                        ->getEntityManager()
                        ->getRepository('MgnArticleBundle:Article')
                        ->findArticlesByCategory($id);

		$em = $this->getDoctrine()->getManager();

		foreach($articles as $article)
		{
			// -1 nombre post par user par post
			$article->setCategory($categoryDefault);

			$em->persist($article);
		}

        $categoryDefault->setCountNews($categoryDefault->getCountNews() + $category->getCountNews());

        $em->flush();

        // on supprime le topic
        $em->remove($category);

        // on valide le tout
        $em->flush();
			
		//message de confirmation
		$this->get('session')->getFlashBag()->add('success', 'Votre catégorie à bien été supprimé.');
		
		// On redirige vers la page d'accueil, par exemple.
        return $this->redirect( $this->generateUrl('mgn_admin_article_category'));
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function redactionAction()
	{
		// Création de l'article par défaut
		$categoryDefault = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->findCategoryDefault();

        $config = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

        $article = new Article;

		$article->setAuthor($this->container->get('security.context')->getToken()->getUser());
		$article->setUrl($article->getTitle());
		$article->setCategory($categoryDefault);

	    // Enregistrement en bdd pour générer un id    
	    $em = $this->getDoctrine()->getManager();
		$em->persist($article);

		// Ajout du comptage dans la config
		$config->setTotalArticlesDraft($config->getTotalArticlesDraft()+1);

		$em->flush();

		// Renvoi vers l'édition de l'article
		return $this->redirect( $this->generateUrl('mgn_admin_article_edition', array('id' => $article->getId())) );
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function editionAction($id)
	{
		// On récupère les entitées dont on aura besoin
		$categories = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->findAll();

        $config = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

       	$article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->findOneArticle($id);

		// On récupère le formulaire et on le traite
		$request = $this->get('request');

		// On vérifie qu'elle est de type « AJAX ».
	    if($request->isXmlHttpRequest())
	    {
	    	$form_article_title->bind($request);

	        if( $form_article_title->isValid() )
	        {
	        	$em = $this->container->get('doctrine')->getManager();

	        	if ($article->getTitle() == null)
	        	{
	        		$article->setTitle('Sans titre');
	        	}

		        $article->setUrl($article->getTitle());

		        $em->persist($article);
				$em->flush();
	        }

	    	$form_article_header->bind($request);

	        if( $form_article_header->isValid() )
	        {
	        	$em = $this->container->get('doctrine')->getManager();

		        $em->persist($article);
				$em->flush();
	        }

	    	$form_article_introduction->bind($request);

	        if( $form_article_introduction->isValid() )
	        {
	        	$em = $this->container->get('doctrine')->getManager();

		        $em->persist($article);
				$em->flush();
	        }

			$return=array("responseCode"=>400, "greeting"=>"You have to write your name!");
			$return=json_encode($return);//jscon encode the array
   			return new Response($return,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
	    }
	    else
	    {
	    	return $this->render('MgnArticleBundle:Admin:edition.html.twig', array(
	            'categories' => $categories,
	            'article' => $article,
			));
	    }
		
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function editTitleAction($article, $action)
	{
		// On récupère les entitées dont on aura besoin
		$article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->find($article);

        $em = $this->container->get('doctrine')->getManager();

        $form_article = $this->createForm(new ArticleTitleType, $article);

        // On récupère le formulaire et on le traite
		$request = $this->get('request');

		// On vérifie qu'elle est de type « AJAX ».
	    if($request->isXmlHttpRequest())
	    {
	    	if ($action == 'edit')
	    	{
	    		return $this->render('MgnArticleBundle:Forms:articleEditTitle.html.twig', array(
		            'form_article' => $form_article->createView(),
		            'article' => $article,
				));
	    	}
	    	elseif ($action == 'cancel')
	    	{
	    		return $this->render('MgnArticleBundle:Contents:title.html.twig', array(
		            'article' => $article,
				));
	    	}
	    	elseif ($action == 'save')
	    	{
	    		$form_article->bind($request);

		        if( $form_article->isValid() )
		        {
		        	$em = $this->container->get('doctrine')->getManager();

		        	if ($article->getTitle() == null)
		        	{
		        		$article->setTitle('Sans titre');
		        	}

			        $article->setUrl($article->getTitle());

			        $em->persist($article);
					$em->flush();
		        }

		        return $this->render('MgnArticleBundle:Contents:title.html.twig', array(
		            'article' => $article,
				));
	    	}
	    }
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function editIntroductionAction($article, $action)
	{
		// On récupère les entitées dont on aura besoin
		$article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->find($article);

        $em = $this->container->get('doctrine')->getManager();

        $form_article = $this->createForm(new ArticleIntroductionType, $article);

        // On récupère le formulaire et on le traite
		$request = $this->get('request');

		// On vérifie qu'elle est de type « AJAX ».
	    if($request->isXmlHttpRequest())
	    {
	    	if ($action == 'edit')
	    	{
	    		return $this->render('MgnArticleBundle:Forms:articleEditIntroduction.html.twig', array(
		            'form_article' => $form_article->createView(),
		            'article' => $article,
				));
	    	}
	    	elseif ($action == 'cancel')
	    	{
	    		return $this->render('MgnArticleBundle:Contents:introduction.html.twig', array(
		            'article' => $article,
				));
	    	}
	    	elseif ($action == 'save')
	    	{
	    		$form_article->bind($request);

		        if( $form_article->isValid() )
		        {
		        	$em = $this->container->get('doctrine')->getManager();

			        $em->persist($article);
					$em->flush();
		        }

		        return $this->render('MgnArticleBundle:Contents:introduction.html.twig', array(
		            'article' => $article,
				));
	    	}
	    }
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function deleteAction($id)
	{
		// On récupère les entitées dont on aura besoin
		$article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->find($id);

        $em = $this->container->get('doctrine')->getManager();
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function publishAction($id)
	{
		// On récupère les entitées dont on aura besoin
		$article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->find($id);

		$config = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

        $em = $this->container->get('doctrine')->getManager();

        $article2 = clone $article;

        $article->setUrl($article->getSlug());

        $form = $this->createForm(new ArticlePublishType, $article);

        // On récupère le formulaire et on le traite
		$request = $this->get('request');

		// On vérifie qu'elle est de type « POST ».
	    if( $request->getMethod() == 'POST' )
	    {
	        // On fait le lien Requête <-> Formulaire.
	        $form->bind($request);
	
	        // On vérifie que les valeurs rentrées sont correctes.
	        if( $form->isValid() )
	        {
	            // On l'enregistre notre objet $article dans la base de données.
	            $em = $this->getDoctrine()->getManager();

	            if ($article->getUrl() == null)
	            {
	            	$article->setUrl($article->getTitle());
	            }

	            // Gestion du comptage pour les auteurs des articles
				if ($article2->getAuthor() == $article->getAuthor())
				{
					if ($article2->getStatus() == 'publish' AND $article->getStatus() != 'publish')
					{
						$countArticle = $article->getAuthor()->getCountArticle();
						$article->getAuthor()->setCountArticle($countArticle-1);
					}
					elseif ($article2->getStatus() != 'publish' AND $article->getStatus() == 'publish')
					{
						$countArticle = $article->getAuthor()->getCountArticle();
						$article->getAuthor()->setCountArticle($countArticle+1);
					}
				}
				else
				{
					if ($article2->getStatus() == 'publish')
					{
						$countArticle = $article2->getAuthor()->getCountArticle();
						$article2->getAuthor()->setCountArticle($countArticle-1);
					}

					if ($article->getStatus() == 'publish')
					{
						$countArticle = $article->getAuthor()->getCountArticle();
						$article->getAuthor()->setCountArticle($countArticle+1);
					}
				}	

				// Gestion du comptage dans les catégories d'article
				if ($article2->getCategory() == $article->getCategory())
				{
					if ($article2->getStatus() == 'publish' AND $article->getStatus() != 'publish')
					{
						$countNews = $article->getCategory()->getCountNews();
						$article->getCategory()->setCountNews($countNews-1);
					}
					elseif ($article2->getStatus() != 'publish' AND $article->getStatus() == 'publish')
					{
						$countNews = $article->getCategory()->getCountNews();
						$article->getCategory()->setCountNews($countNews+1);
					}
				}
				else
				{
					if ($article2->getStatus() == 'publish')
					{
						$countNews = $article2->getCategory()->getCountNews();
						$article2->getCategory()->setCountNews($countNews-1);
					}

					if ($article->getStatus() == 'publish')
					{
						$countNews = $article->getCategory()->getCountNews();
						$article->getCategory()->setCountNews($countNews+1);
					}
				}

				// Gestion du comptage total des articles
				if ($article2->getStatus() != $article->getStatus())
				{
					
				}

				$em->persist($article);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre article à bien été publié.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_article_list'));
	        }
	    }

        return $this->render('MgnArticleBundle:Admin:publish.html.twig', array(
            'form' => $form->createView(),
            'article' => $article,
            //'categories' => $categories,
		));
	}
}