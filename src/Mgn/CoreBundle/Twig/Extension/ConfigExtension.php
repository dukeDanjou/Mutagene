<?php
// src/Mgn/CoreBundle/Twig/Extension/ConfigExtension.php
 
namespace Mgn\CoreBundle\Twig\Extension;

use Mgn\CoreBundle\Entity\Config;
use Mgn\CoreBundle\Entity\ConfigRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\DoctrineBundle\Registry;
 
class ConfigExtension extends \Twig_Extension
{
	protected $doctrine;
	protected $config;
	
	public function getName()
	{
		return 'mgn.config';
	}
	
	public function getFunctions()
    {
        return array(
           'Config' => new \Twig_Function_Method($this, 'Config'),
        );
    }
	
	public function __construct($doctrine)
	{
		$this->doctrine = $doctrine;
		
		if ($this->config === null) {
			$this->config = $this->doctrine
                         ->getManager()
                         ->getRepository('MgnCoreBundle:Config')
                         ->findOneBy(array('cms' => 'mutagene'));
		}
	}

	public function get($variable)
	{
		$get = 'get'.$variable;
		
    	return $this->config->$get();
	}
	
	/**
	* @param string $test
	*/
	public function Config($variable)
    {
    	$get = 'get'.$variable;
		
    	return $this->config->$get();
    }
}