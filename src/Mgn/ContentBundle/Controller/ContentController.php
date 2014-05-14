<?php
namespace Mgn\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Mgn\ArticleBundle\Entity\Article;
use Mgn\ContentBundle\Entity\Content;
use Mgn\ContentBundle\Form\ContentParagraphType;
use Mgn\ContentBundle\Form\ContentTitleType;
use Mgn\ContentBundle\Form\ContentSubtitleType;
use Mgn\ContentBundle\Form\ContentPictureType;
use Mgn\ContentBundle\Form\ContentVideoType;
use Mgn\ContentBundle\Form\ContentQuoteType;

use Mgn\ArticleBundle\Entity\Category;
use Mgn\ArticleBundle\Form\CategoryType;
use Mgn\ArticleBundle\Form\ArticleType;
use Mgn\ArticleBundle\Form\ArticleEditType;
use Mgn\CoreBundle\Entity\Config;
use Mgn\MediaBundle\Entity\Picture;
use Mgn\MediaBundle\Form\PictureArticleType;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use JMS\SecurityExtraBundle\Annotation\Secure;

class ContentController extends Controller
{
	public function contentAction($container, $id, $type, $content, $action)
	{
		// On récupère les entitées dont on aura besoin
		$article = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnArticleBundle:Article')
                      	 ->find($id);

        $em = $this->container->get('doctrine')->getManager();

        if ( $content == 'new' )
        {
        	$contentGenerated = new Content;
			$contentGenerated->setArticle($article);

			if( $type == 'paragraph' ){	$contentGenerated->setType('paragraph'); }
			if( $type == 'title' ){	$contentGenerated->setType('title'); }
			if( $type == 'subtitle' ){	$contentGenerated->setType('subtitle'); }
			if( $type == 'picture' ){	$contentGenerated->setType('picture'); }
			if( $type == 'video' ){	$contentGenerated->setType('video'); }
			if( $type == 'quote' ){	$contentGenerated->setType('quote'); }

			$em->persist($contentGenerated);
			$em->flush();
        }
        else
        {
        	$contentGenerated = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnContentBundle:Content')
                      	 ->find($content);
        }

        // Création du FormType en fonction de $type
        if ( $type == 'paragraph' ){ $form_content = $this->createForm(new ContentParagraphType, $contentGenerated); }
        if ( $type == 'title' ){ $form_content = $this->createForm(new ContentTitleType, $contentGenerated); }
        if ( $type == 'subtitle' ){ $form_content = $this->createForm(new ContentSubtitleType, $contentGenerated); }
        if ( $type == 'picture' ){ $form_content = $this->createForm(new ContentPictureType, $contentGenerated); }
        if ( $type == 'video' ){ $form_content = $this->createForm(new ContentVideoType, $contentGenerated); }
        if ( $type == 'quote' ){ $form_content = $this->createForm(new ContentQuoteType, $contentGenerated); }

		// On récupère le formulaire et on le traite
		$request = $this->get('request');

		// On vérifie qu'elle est de type « AJAX ».
	    if($request->isXmlHttpRequest())
	    {
		    if( $content == 'new' or $action == 'edit' )
		    {
				if( $type == 'paragraph' )
				{
					return $this->render('MgnContentBundle:Forms:contentParagraph.html.twig', array(
			            'form_content' => $form_content->createView(),
			            'content' => $contentGenerated,
			            'article' => $article,
					));
				}
				elseif( $type == 'title' )
				{
					return $this->render('MgnContentBundle:Forms:contentTitle.html.twig', array(
			            'form_content' => $form_content->createView(),
			            'content' => $contentGenerated,
			            'article' => $article,
					));
				}
				elseif( $type == 'subtitle' )
				{
					return $this->render('MgnContentBundle:Forms:contentSubtitle.html.twig', array(
			            'form_content' => $form_content->createView(),
			            'content' => $contentGenerated,
			            'article' => $article,
					));
				}
				elseif( $type == 'picture' )
				{
					return $this->render('MgnContentBundle:Forms:contentPicture.html.twig', array(
			            'form_content' => $form_content->createView(),
			            'content' => $contentGenerated,
			            'article' => $article,
					));
				}
				elseif( $type == 'video' )
				{
					return $this->render('MgnContentBundle:Forms:contentVideo.html.twig', array(
			            'form_content' => $form_content->createView(),
			            'content' => $contentGenerated,
			            'article' => $article,
					));
				}
				elseif( $type == 'quote' )
				{
					return $this->render('MgnContentBundle:Forms:contentQuote.html.twig', array(
			            'form_content' => $form_content->createView(),
			            'content' => $contentGenerated,
			            'article' => $article,
					));
				}
		    }
		    else
		    {
		    	$form_content->bind($request);

		        if( $form_content->isValid() )
		        {
		        	if( $article->getStatus() != 'publish' )
		        	{
		        		$article->setDate(new \Datetime());

		        		$em->persist($article);
		        	}

		        	$em->persist($contentGenerated);
					$em->flush();

			        /*$return=array("responseCode"=>200, "id"=>$contents);
					$return=json_encode($return);//jscon encode the array
		   			return new Response($return,200,array('Content-Type'=>'application/json'));*/
		   			if( $type == 'paragraph' )
					{
						return $this->render('MgnContentBundle:Contents:paragraph.html.twig', array(
				            'content' => $contentGenerated,
				            'article' => $article,
						));
					}
					elseif( $type == 'title' )
					{
						return $this->render('MgnContentBundle:Contents:title.html.twig', array(
				            'content' => $contentGenerated,
				            'article' => $article,
						));
					}
					elseif( $type == 'subtitle' )
					{
						return $this->render('MgnContentBundle:Contents:subtitle.html.twig', array(
				            'content' => $contentGenerated,
				            'article' => $article,
						));
					}
					elseif( $type == 'picture' )
					{
						return $this->render('MgnContentBundle:Contents:picture.html.twig', array(
				            'content' => $contentGenerated,
				            'article' => $article,
						));
					}
					elseif( $type == 'video' )
					{
						return $this->render('MgnContentBundle:Contents:video.html.twig', array(
				            'content' => $contentGenerated,
				            'article' => $article,
						));
					}
					elseif( $type == 'quote' )
					{
						return $this->render('MgnContentBundle:Contents:quote.html.twig', array(
				            'content' => $contentGenerated,
				            'article' => $article,
						));
					}
		        }
		    }
		}
		
	}

	public function contentDeleteAction($content)
	{
		// On récupère les entitées dont on aura besoin
        $contents = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnContentBundle:Content')
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

	public function contentSortableAction()
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
		                      	 ->getRepository('MgnContentBundle:Content')
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