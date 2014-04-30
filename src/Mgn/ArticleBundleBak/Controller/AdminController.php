<?php
namespace Mgn\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mgn\ArticleBundle\Entity\Category;
use Mgn\ArticleBundle\Form\CategoryType;
use Mgn\ArticleBundle\Entity\Article;
use Mgn\ArticleBundle\Form\ArticleType;
use Mgn\ArticleBundle\Form\ArticleEditType;
use Mgn\CoreBundle\Entity\Config;
use Mgn\MediaBundle\Entity\Picture;
use Mgn\MediaBundle\Form\PictureArticleType;

use Mgn\ArticleBundle\Entity\Articlenext;
use Mgn\ArticleBundle\Entity\Contents;
use Mgn\ArticleBundle\Form\ContentsType;
use Mgn\ArticleBundle\Form\ContentsParagraphType;
use Mgn\ArticleBundle\Form\ArticlenextTitleType;
use Mgn\ArticleBundle\Form\ArticlenextHeaderType;
use Mgn\ArticleBundle\Form\ArticlenextIntroductionType;

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

        if ( $status == 'all' )
        {
        	$articles = $repository->findBy(
				            array(),                 // Pas de critère
				            array('dateTop' => 'DESC'), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
        }
        if ( $status == 'publies' )
        {
        	$articles = $repository->findBy(
				            array('status' => 1),                 // Pas de critère
				            array('dateTop' => 'DESC'), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
        }
        if ( $status == 'brouillons' )
        {
        	$articles = $repository->findBy(
				            array('status' => 0),                 // Pas de critère
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
	public function redactionAction()
	{
		// On récupère les catégories pour vérifier leur existance
		$categories = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->findAll();

        $config = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

       	$article = new Article;
		$article->setAuthor($this->container->get('security.context')->getToken()->getUser());

		// On crée le FormBuilder grâce à la méthode du contrôleur.
		$form = $this->createForm(new ArticleType, $article);

		$picture = new Picture;

        $formPicture = $this->createForm(new PictureArticleType, $picture);

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
				
				if ($article->getStatus() == 1)
				{
					$countArticle = $article->getAuthor()->getCountArticle();
					$article->getAuthor()->setCountArticle($countArticle+1);

					$CategorieNews = $article->getCategory()->getCountNews();
					$article->getCategory()->setCountNews($CategorieNews+1);

					$config->setTotalArticles($config->getTotalArticles()+1);
				}
				
				$em->persist($article);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre article à bien été enregistré.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_article_list'));
	        }
	    }

		return $this->render('MgnArticleBundle:Admin:redaction.html.twig', array(
            'form' => $form->createView(),
            'formPicture' => $formPicture->createView(),
            'categories' => $categories,
		));
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function editionAction($id)
	{
		$categories = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->findAll();

        $article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->find($id);

		$config = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

		$article2 = clone $article;

        $article->setUrl($article->getSlug());

        // On crée le FormBuilder grâce à la méthode du contrôleur.
		$form = $this->createForm(new ArticleEditType, $article);

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

				if ($article2->getAuthor() == $article->getAuthor())
				{
					if ($article2->getStatus() == 1 AND $article->getStatus() == 0)
					{
						$countArticle = $article->getAuthor()->getCountArticle();
						$article->getAuthor()->setCountArticle($countArticle-1);
						$config->setTotalArticles($config->getTotalArticles()-1);
					}
					elseif ($article2->getStatus() == 0 AND $article->getStatus() == 1)
					{
						$countArticle = $article->getAuthor()->getCountArticle();
						$article->getAuthor()->setCountArticle($countArticle+1);
						$config->setTotalArticles($config->getTotalArticles()+1);
					}
				}
				else
				{
					if ($article2->getStatus() == 1)
					{
						$countArticle = $article2->getAuthor()->getCountArticle();
						$article2->getAuthor()->setCountArticle($countArticle-1);
						$config->setTotalArticles($config->getTotalArticles()-1);
					}

					if ($article->getStatus() == 1)
					{
						$countArticle = $article->getAuthor()->getCountArticle();
						$article->getAuthor()->setCountArticle($countArticle+1);
						$config->setTotalArticles($config->getTotalArticles()+1);
					}
				}	

				if ($article2->getCategory() == $article->getCategory())
				{
					if ($article2->getStatus() == 1 AND $article->getStatus() == 0)
					{
						$countNews = $article->getCategory()->getCountNews();
						$article->getCategory()->setCountNews($countNews-1);
					}
					elseif ($article2->getStatus() == 0 AND $article->getStatus() == 1)
					{
						$countNews = $article->getCategory()->getCountNews();
						$article->getCategory()->setCountNews($countNews+1);
					}
				}
				else
				{
					if ($article2->getStatus() == 1)
					{
						$countNews = $article2->getCategory()->getCountNews();
						$article2->getCategory()->setCountNews($countNews-1);
					}

					if ($article->getStatus() == 1)
					{
						$countNews = $article->getCategory()->getCountNews();
						$article->getCategory()->setCountNews($countNews+1);
					}
				}
				
				$em->persist($article);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre article à bien été enregistré.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_article_list'));
	        }
	    }

		return $this->render('MgnArticleBundle:Admin:edition.html.twig', array(
            'form' => $form->createView(),
            'categories' => $categories,
            'article' => $article,
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
	public function redactionnextAction()
	{
		// Création de l'article par défaut
		$categoryDefault = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Category')
                      	 ->findCategoryDefault();

        $article = new Articlenext;

		$article->setAuthor($this->container->get('security.context')->getToken()->getUser());
		$article->setUrl($article->getTitle());
		$article->setCategory($categoryDefault);

	    // Enregistrement en bdd pour générer un id    
	    $em = $this->getDoctrine()->getManager();
		$em->persist($article);
		$em->flush();

		// Renvoi vers l'édition de l'article
		return $this->redirect( $this->generateUrl('mgn_admin_article_editionnext', array('id' => $article->getId())) );
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function editionnextAction($id)
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
                      	 ->getRepository('MgnArticleBundle:Articlenext')
                      	 ->findOneArticle($id);

		// On crée le FormBuilder grâce à la méthode du contrôleur.
		$form_article_title = $this->createForm(new ArticlenextTitleType, $article);
		$form_article_header = $this->createForm(new ArticlenextHeaderType, $article);
		$form_article_introduction = $this->createForm(new ArticlenextIntroductionType, $article);

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
	    	return $this->render('MgnArticleBundle:Admin:editionnext.html.twig', array(
	            'form_article_title' => $form_article_title->createView(),
	            'form_article_header' => $form_article_header->createView(),
	            'form_article_introduction' => $form_article_introduction->createView(),
	            'categories' => $categories,
	            'article' => $article,
			));
	    }
		
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function contentsAction($article, $content, $type, $action)
	{
		// On récupère les entitées dont on aura besoin
		$article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Articlenext')
                      	 ->find($article);

        $em = $this->container->get('doctrine')->getManager();

        if ( $content == 'new' )
        {
        	$contents = new Contents;
			$contents->setArticle($article);
			$em->persist($contents);
			$em->flush();
        }
        else
        {
        	$contents = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Contents')
                      	 ->find($content);
        }

        // Création du FormType en fonction de $type
        if ( $type == 'paragraph' ){ $form_contents = $this->createForm(new ContentsParagraphType, $contents); }

		// On récupère le formulaire et on le traite
		$request = $this->get('request');

		// On vérifie qu'elle est de type « AJAX ».
	    if($request->isXmlHttpRequest())
	    {
		    if( $content == 'new' )
		    {
				return $this->render('MgnArticleBundle:Forms:contentsParagraph.html.twig', array(
		            'form_contents' => $form_contents->createView(),
		            'contents' => $contents,
		            'article' => $article,
				));
		    }
		    elseif( $action == 'edit' )
		    {
				return $this->render('MgnArticleBundle:Forms:contentsParagraph.html.twig', array(
		            'form_contents' => $form_contents->createView(),
		            'contents' => $contents,
		            'article' => $article,
				));
		    }
		    else
		    {
		    	$form_contents->bind($request);

		        if( $form_contents->isValid() )
		        {
		        	$em->persist($contents);
					$em->flush();

			        /*$return=array("responseCode"=>200, "id"=>$contents);
					$return=json_encode($return);//jscon encode the array
		   			return new Response($return,200,array('Content-Type'=>'application/json'));*/
		   			return $this->render('MgnArticleBundle:Contents:paragraph.html.twig', array(
		            'content' => $contents,
		            'article' => $article,
				));
		        }
		    }
		}
		
	}

	/**
   	* @Secure(roles="ROLE_ARTICLE_AUTHOR")
   	*/
	public function contentsDeleteAction($content)
	{
		// On récupère les entitées dont on aura besoin
        $contents = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Contents')
                      	 ->find($content);

        $request = $this->get('request');

		// On vérifie qu'elle est de type « AJAX ».
	    if($request->isXmlHttpRequest())
	    {
	    	$em = $this->container->get('doctrine')->getManager();

		    $em->remove($contents);
			$em->flush();

	        $return=array("responseCode"=>200, "id"=>$content);
			$return=json_encode($return);//jscon encode the array
   			return new Response($return,200,array('Content-Type'=>'application/json'));
		}
		
	}

	public function contentsSortableAction()
	{
		// On récupère les entitées dont on aura besoin
        $request = $this->get('request');

		// On vérifie qu'elle est de type « AJAX ».
	    if($request->isXmlHttpRequest())
	    {
	    	$em = $this->container->get('doctrine')->getManager();

	    	$order = explode(',', $request->request->get('order'));
	    	//$order = explode(',', $_POST['order']);

	    	
		    	$counter = 0;
			    foreach ($order as $item_id) {
		    		$content = $this->getDoctrine()
		                      	 ->getManager()
		                      	 ->getRepository('MgnArticleBundle:Contents')
		                      	 ->find($item_id);

	                $content->setPosition($counter);
					$em->persist($content);
					$em->flush();

			        $counter++;
			    }

			    $return=array("responseCode"=>200, "info"=>"success");
				$return=json_encode($return);//jscon encode the array
	   			return new Response($return,200,array('Content-Type'=>'application/json'));
		}
		else
		{
			$return=array("responseCode"=>400, "info"=>"non isXmlHttpRequest");
			$return=json_encode($return);//jscon encode the array
			return new Response($return,400,array('Content-Type'=>'application/json'));
		}
	}
}