<?php

namespace Mgn\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityManager;
use Mgn\CoreBundle\Form\ConfigType;
use Mgn\CoreBundle\Form\ModulesType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SiteController extends Controller
{
    public function parametersAction()
    {
    	if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
			throw new AccessDeniedHttpException('Vous devez disposer des droits SuperAdmin');
		}

		$config = $this->getDoctrine()
                      	 ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

		// On crée le FormBuilder grâce à la méthode du contrôleur.
		$form = $this->createForm(new ConfigType, $config);

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
	            // On l'enregistre notre objet $cateforie dans la base de données.
	            $em = $this->getDoctrine()->getManager();

				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'Les paramètres du site on bien été mis à jour.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_core_parameters'));
	        }
	    }

		return $this->render('MgnCoreBundle:Site:parameters.html.twig', array(
			'form' => $form->createView(),
		));
    }

    public function modulesAction()
    {
    	if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
			throw new AccessDeniedHttpException('Vous devez disposer des droits SuperAdmin');
		}

		$config = $this->getDoctrine()
                      	 ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));

		// On crée le FormBuilder grâce à la méthode du contrôleur.
		$form = $this->createForm(new ModulesType, $config);

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
	            // On l'enregistre notre objet $cateforie dans la base de données.
	            $em = $this->getDoctrine()->getManager();

				$em->flush();
				
				//message de confirmation
				$this->get('session')->getFlashBag()->add('success', 'La configuration des modules à bien été mis à jour.');
				
				// On redirige vers la page d'accueil, par exemple.
	            return $this->redirect( $this->generateUrl('mgn_admin_core_modules'));
	        }
	    }

		return $this->render('MgnCoreBundle:Site:modules.html.twig', array(
			'form' => $form->createView(),
		));
    }
}
