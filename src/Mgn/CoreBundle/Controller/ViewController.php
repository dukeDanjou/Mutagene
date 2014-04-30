<?php

namespace Mgn\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;


class ViewController extends Controller
{
    public function indexAction($type)
    {
    	// On récupère la requête.
    	$request = $this->get('request');
		
		// On vérifie qu'elle est de type « POST ».
		if( $request->getMethod() == 'POST' )
		{
		    return $this->render('MgnCoreBundle::view.html.twig', array(
				'request' => $this->getRequest()->request->get('string'),
				'type' => $type,
			));
		}
    }
}
