<?php

namespace Mgn\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$config = $this->container->get('mgn.config');

        $web_path = ''.$this->container->getParameter('kernel.root_dir').'/../web';

    	if ( $this->get('templating')->exists($web_path.'/themes/'.$config->get('theme').'/twig/edition.html.twig') )
    	{
    		$test = 'exist';
    	}
    	else
    	{
    		$test = $web_path.'/themes/'.$config->get('theme').'/twig/edition.html.twig';
    	}

        return $this->render('MgnAdminBundle:Default:index.html.twig', array(
        ));
    }
}
