<?php
namespace Mgn\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

use Mgn\MediaBundle\Entity\Gallery;
use Mgn\MediaBundle\Form\GalleryType;
use Mgn\MediaBundle\Entity\Picture;
use Mgn\MediaBundle\Form\PictureType;

use Mgn\CoreBundle\Entity\Config;

use JMS\SecurityExtraBundle\Annotation\Secure;

class AdminController extends Controller
{
	public function galleryAction()
	{
		$galleries = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnMediaBundle:Gallery')
                      	 ->findBy(
				            array(),                 // Pas de critère
				            array('name' => 'ASC'), // On tri par date décroissante
				            NULL,       // On sélectionne $nb_articles_page articles
				            NULL                  // A partir du $offset ième
				        );
			
		$gallery = new Gallery;

        $form = $this->createForm(new GalleryType, $gallery);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if ($form->isValid())
            {
                // On l'enregistre notre objet $gallery dans la base de données.
	            $em = $this->getDoctrine()->getManager();

	            if ($gallery->getUrl() == null)
	            {
	            	$gallery->setUrl($gallery->getName());
	            }
				
				$em->persist($gallery);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre galerie à bien été ajouté.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_media_gallery'));
            }
        }

		return $this->render('MgnMediaBundle:Admin:galleries.html.twig', array(
			'form' => $form->createView(),
			'galleries' => $galleries,
		));
	}

	public function galleryEditAction($id)
	{
		$gallery = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnMediaBundle:Gallery')
                      	 ->find($id);

        $form = $this->createForm(new GalleryType, $gallery);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if ($form->isValid())
            {
                // On l'enregistre notre objet $category dans la base de données.
	            $em = $this->getDoctrine()->getManager();

	            if ($gallery->getUrl() == null)
	            {
	            	$gallery->setUrl($gallery->getName());
	            }
				
				$em->persist($gallery);
				$em->flush();

				$gallery->setUrl($gallery->getSlug());
				
				$em->persist($gallery);
				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Votre catégorie à bien été modifié.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_media_gallery'));
            }
        }
			
		return $this->render('MgnMediaBundle:Admin:galeryEdit.html.twig', array(
			'form' => $form->createView(),
			'gallery' => $gallery,
		));
	}

	public function galleryDeleteAction($id)
	{
		//on récupère les informations sur la catégorie
		$gallery = $this->getDoctrine()
                      	 ->getManager()
                      	 ->getRepository('MgnMediaBundle:Gallery')
                      	 ->find($id);

        if( $gallery == null )
        {
            throw $this->createNotFoundException('Galerie [id='.$id.'] inexistant');
        }

        //on récupère les pictures de la galerie
        $pictures = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnMediaBundle:Picture')
                        ->findPicturesByGallery($id);

		$em = $this->getDoctrine()->getManager();

		if( $pictures != null )
        {
        	foreach($pictures as $picture)
	    	{
	    		$em->remove($picture);
	    	}
        }

        $em->flush();

        // on supprime le topic
        $em->remove($gallery);

        // on valide le tout
        $em->flush();
			
		//message de confirmation
		$this->get('session')->getFlashBag()->add('success', 'Votre galerie à bien été supprimé.');
		
		// On redirige vers la page d'accueil, par exemple.
        return $this->redirect( $this->generateUrl('mgn_admin_media_gallery'));
	}

	public function addPictureAction()
	{
		$em = $this->getDoctrine()->getManager();

		$user = $this->container->get('security.context')->getToken()->getUser();

		$picture = new Picture;

        $form = $this->createForm(new PictureType, $picture);

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
	            if ($picture->getExtension() == null)
	            {
	            	$this->get('session')->getFlashBag()->add('error', 'Vous devez sélectionner une image.');

	            	return $this->render('MgnMediaBundle:Admin:addPicture.html.twig', array(
			            'form' => $form->createView(),
					));
	            }

	            $picture->setUserIdForName($user->getId());
	            $picture->setUserUpload($user->getId());
	            $picture->setUserIp($request->getClientIp());

	            $extension = $picture->getExtension();

	            if ($extension != 'jpg' and $extension != 'jpeg' and $extension != 'gif' and $extension != 'png' and $extension != 'bmp')
	            {
				    throw $this->createNotFoundException('Extension incorrect');
				}

				$picture->getGallery()->setCountPicture($picture->getGallery()->getCountPicture()+1);

	            $em->persist($picture);
	            $em->flush();

	            $baseurl = $request->getScheme().'://'.$request->getHttpHost().'/';

	            $url = $baseurl.$picture->getWebPath();

	            $this->get('session')->getFlashBag()->add('success', 'L\'image à bien été uploadé.');

				return $this->render('MgnMediaBundle:Admin:addPicture.html.twig', array(
					'form' => $form->createView(),
		            'url' => $url,
				));
	        }
	    }

        return $this->render('MgnMediaBundle:Admin:addPicture.html.twig', array(
			'form' => $form->createView(),
		));
	}
}