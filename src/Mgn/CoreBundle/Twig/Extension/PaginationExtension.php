<?php

namespace Mgn\CoreBundle\Twig\Extension;

class PaginationExtension extends \Twig_Extension
{
	protected $config;

	public function __construct($config)
	{
		$this->config    = $config;
	}

	public function getName()
	{
		return 'mgn_pagination';
	}
	
	public function getFilters()
    {
        return array(
           'pagination' => new \Twig_Filter_Method($this, 'pagination'),
        );
    }
 
    public function pagination($nbPost)
    {
       	$nb_posts_page = $this->config->get('ForumPostPage');
		
		$nb_posts = $nbPost;
		
		// On calcule le nombre total de pages
        $page = ceil($nb_posts/$nb_posts_page);
		
		return $page;
    }
}