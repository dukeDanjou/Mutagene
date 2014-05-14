<?php

namespace Mgn\CoreBundle\Twig\Extension;

class MCodeVideoExtension extends \Twig_Extension
{
	protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getName()
	{
		return 'mgn_mcode_video';
	}
	
	public function getFilters()
    {
        return array(
           'mcodevideo' => new \Twig_Filter_Method($this, 'mcodevideo'),
        );
    }
 
    public function mcodevideo($code)
    {
    	$content = array(
            //security contre les iframes
            '`iframe`',
            //youtube
            '`http:\/\/youtu.be\/(.+)`',
            '`https:\/\/www.youtube.com\/watch\?v=(.+)`',
            //dailymotion
            '`http:\/\/www.dailymotion.com\/video\/(.+)_(.+)`',
            '`http:\/\/dai.ly\/(.+)`',
            /*'`\[dailymotion\]&lt;iframe frameborder=&quot;0&quot; width=&quot;(.+)&quot; height=&quot;(.+)&quot; src=&quot;http:\/\/www.dailymotion.com\/embed\/video\/(.+)&quot;&gt;&lt;\/iframe&gt;(.+)&lt;\/a&gt;&lt;\/i&gt;\[\/dailymotion\]`isU',
            '`\[vimeo\]http://vimeo.com/(.+)\[\/vimeo\]`isU',
            '`\[img\](.+)\[\/img\]`isU',
            '`&lt;iframe width=&quot;(.+)&quot; height=&quot;(.+)&quot; src=&quot;http:\/\/www.youtube.com\/embed\/(.+)&quot; frameborder=&quot;0&quot; allowfullscreen&gt;&lt;\/iframe&gt;`isU',
            '`&lt;iframe frameborder=&quot;0&quot; width=&quot;(.+)&quot; height=&quot;(.+)&quot; src=&quot;http:\/\/www.dailymotion.com\/embed\/video\/(.+)&quot;&gt;&lt;\/iframe&gt;`isU',
            '`&lt;iframe src=&quot;http:\/\/store.steampowered.com\/widget\/(.+)&quot;&gt;&lt;\/iframe&gt;`isU',*/
        );

        $html = array(
            //security contre les iframes
            '',
            //youtube
            '<iframe width="640" height="360" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
            '<iframe width="640" height="360" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
            //dailymotion
            '<iframe frameborder="0" width="640" height="360" src="//www.dailymotion.com/embed/video/$1" allowfullscreen></iframe>',
            '<iframe frameborder="0" width="640" height="360" src="//www.dailymotion.com/embed/video/$1" allowfullscreen></iframe>',
            /*'<p style="text-align: center;"><iframe frameborder="0" width="$1" height="$2" src="http://www.dailymotion.com/embed/video/$3"></iframe></p>',
            '<p style="text-align: center;"><iframe src="http://player.vimeo.com/video/$1" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></p>',
            '<a target="_blank" title="Voir en taille r&#233;el" href="$1" ><img style="max-width: 100%;" src="$1" alt="Image" /></a>',
            '<br /><iframe width="$1" height="$2" src="http://www.youtube.com/embed/$3" frameborder="0" allowfullscreen></iframe><br />',
            '<br /><iframe frameborder="0" width="$1" height="$2" src="http://www.dailymotion.com/embed/video/$3"></iframe><br />',
            '<br /><iframe src="http://store.steampowered.com/widget/$1" frameborder="0" width="646" height="190"></iframe><br />',*/
        );

        $code = preg_replace($content, $html, $code);

        return $code;
    }
}