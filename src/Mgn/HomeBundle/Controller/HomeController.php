<?php

namespace Mgn\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
	public function indexAction()
	{
		$config = $this->container->get('mgn.config');
		
		$homepage = $config->get('homepage');

		if ($homepage == 'forum')
		{
			$response = $this->forward('MgnForumBundle:Forum:all', array( 'mark' => false));
		}
		else
		{	
			$response = $this->forward('MgnArticleBundle:Article:index');
		}

		return $response;
	}
}
