<?php
// src/Mgn/CoreBundle/Twig/Extension/ConfigExtension.php
 
namespace Mgn\CoreBundle\Twig\Extension;

use Mgn\CoreBundle\Entity\Config;
use Mgn\CoreBundle\Entity\ConfigRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\DoctrineBundle\Registry;
use Mgn\UserBundle\Entity\User;

use Symfony\Component\Security\Core\SecurityContextInterface;
 
class ThemeExtension extends \Twig_Extension
{
	protected $doctrine;
	protected $securityContext;
	protected $config;
	protected $theme;
	
	public function getName()
	{
		return 'mgn.theme';
	}
	
	public function getFunctions()
    {
        return array(
           'Theme' => new \Twig_Function_Method($this, 'Theme'),
        );
    }
	
	public function __construct(SecurityContextInterface $securityContext, $doctrine, $config)
	{
		$this->securityContext = $securityContext;
		$this->config = $config;
		$this->doctrine = $doctrine;

		if ( $this->securityContext->getToken() != null )
		{
			$user = $this->securityContext->getToken()->getUser();
		}
		
		if ( $this->securityContext->getToken() != null AND $this->securityContext->isGranted('ROLE_USER') )
		{
			if ( $this->config->get('themeDate') >= $this->securityContext->getToken()->getUser()->getThemeDate() )
			{
				$themeSelect = $this->config->get('theme');
			}
			else
			{
				$themeSelect = $this->securityContext->getToken()->getUser()->getTheme();
			}
		}
		else
		{
			$themeSelect = $this->config->get('theme');
		}

		$this->theme = $this->doctrine->getManager()->getRepository('MgnCoreBundle:Theme')->findOneBy(array('slug' => $themeSelect));
	}

	public function get($variable)
	{
		$get = 'get'.$variable;
		
    	return $this->theme->$get();
	}
	
	/**
	* @param string $test
	*/
	public function Theme($variable)
    {
    	$get = 'get'.$variable;
		
    	return $this->theme->$get();
    }
}