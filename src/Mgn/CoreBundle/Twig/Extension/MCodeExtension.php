<?php

namespace Mgn\CoreBundle\Twig\Extension;

class MCodeExtension extends \Twig_Extension
{
	protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getName()
	{
		return 'mgn_mcode';
	}
	
	public function getFilters()
    {
        return array(
           'mcode' => new \Twig_Filter_Method($this, 'mcode'),
        );
    }
 
    public function mcode($cible, $action, $type, $url)
    {
    	//$cible = preg_replace('`\n`isU', '<br />', $cible);

        if ($action == 'menu')
    	{
    		return $this->menu($cible, $type, $url);
    	}
    	elseif ($action == 'parser')
    	{
    		return $this->parsermcode($cible, $type, $url);
    	}
    }

    public function menu($cible, $type, $url)
    {
    	if ($type == 'comments')
    	{
    		$menu = '
	    	<div class="help-block">
		    	<div class="btn-group">'.$this->menuTypo($cible).'</div>
                <div class="btn-group">'.$this->menuTypoPlus($cible).'</div>
		    	<div class="btn-group">'.$this->menuPos($cible).'</div>
		    	<div class="btn-group">'.$this->menuMedia($cible).'</div>
		    	<div class="btn-group">'.$this->menuInter($cible).'</div>
	    	</div>
            <div class="help-block">
                <div class="btn-group">'.$this->menuSmileys($cible, $url).'</div>
            </div>
	    	';

	    	return $menu;
    	}
    	elseif ($type == 'article')
    	{
    		$menu = '
	    	<div class="btn-toolbar toolbar" role="toolbar">

                <div class="btn-group">
                    <a class="btn btn-default" onclick="insertTag(\'[b]\', \'[/b]\', \''.$cible.'\')"><i class="fa fa-bold"></i></a>
                    <a class="btn btn-default" onclick="insertTag(\'[i]\', \'[/i]\', \''.$cible.'\')"><i class="fa fa-italic"></i></a>
                    <a class="btn btn-default" onclick="insertTag(\'[u]\', \'[/u]\', \''.$cible.'\')"><i class="fa fa-underline"></i></a>
                    <a class="btn btn-default" onclick="insertTag(\'[s]\', \'[/s]\', \''.$cible.'\')"><i class="fa fa-strikethrough"></i></a>
                </div>

                <div class="btn-group">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-text-height"></i> <i class="fa fa-caret-down"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a onclick="insertTag(\'[header1]\', \'[/header1]\', \''.$cible.'\')">Titre niveau 1</a></li>
                        <li><a onclick="insertTag(\'[header2]\', \'[/header2]\', \''.$cible.'\')">Titre niveau 2</a></li>
                        <li class="divider"></li>
                        <li><a onclick="insertTag(\'[p]\', \'[/p]\', \''.$cible.'\')">Paragraphe</a></li>
                        <li><a onclick="insertTag(\'[f]\', \'[/f]\', \''.$cible.'\')">Fieldset</a></li>
                        <li class="divider"></li>
                        <li><a onclick="insertTag(\'[list]\n[*]..\n[*]..\n[/list]\', \'\', \''.$cible.'\')">Liste simple</a></li>
                        <li><a onclick="insertTag(\'[list=1]\n[*]..\n[*]..\n[/list]\', \'\', \''.$cible.'\')">Liste ordonné</a></li>
                    </ul>
                </div>

                <div class="btn-group">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-code"></i> <i class="fa fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a onclick="insertTag(\'[c]\', \'[/c]\', \''.$cible.'\')">Ligne</a></li>
                        <li><a onclick="insertTag(\'[code]\', \'[/code]\', \''.$cible.'\')">Block</a></li>
                    </ul>
                </div>

                <div class="btn-group">
                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tint"></i> <i class="fa fa-caret-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a onclick="insertTag(\'[color=fuchsia]\', \'[/color]\', \''.$cible.'\')"><span style="color: fuchsia;">Rose</span></a></li>
                        <li><a onclick="insertTag(\'[color=purple]\', \'[/color]\', \''.$cible.'\')"><span style="color: purple;">Violet</span></a></li>
                        <li><a onclick="insertTag(\'[color=red]\', \'[/color]\', \''.$cible.'\')"><span style="color: red;">Rouge</span></a></li>
                        <li><a onclick="insertTag(\'[color=#EC7600]\', \'[/color]\', \''.$cible.'\')"><span style="color: #EC7600;">Orange</span></a></li>
                        <li><a onclick="insertTag(\'[color=yellow]\', \'[/color]\', \''.$cible.'\')"><span style="color: yellow;">Jaune</span></a></li>
                        <li><a onclick="insertTag(\'[color=lime]\', \'[/color]\', \''.$cible.'\')"><span style="color: lime;">Vert clair</span></a></li>
                        <li><a onclick="insertTag(\'[color=green]\', \'[/color]\', \''.$cible.'\')"><span style="color: green;">Vert foncé</span></a></li>
                        <li><a onclick="insertTag(\'[color=olive]\', \'[/color]\', \''.$cible.'\')"><span style="color: olive;">Olive</span></a></li>
                        <li><a onclick="insertTag(\'[color=aqua]\', \'[/color]\', \''.$cible.'\')"><span style="color: aqua;">Turquoise</span></a></li>
                        <li><a onclick="insertTag(\'[color=teal]\', \'[/color]\', \''.$cible.'\')"><span style="color: teal;">Bleu gris</span></a></li>
                        <li><a onclick="insertTag(\'[color=blue]\', \'[/color]\', \''.$cible.'\')"><span style="color: blue;">Bleu</span></a></li>
                        <li><a onclick="insertTag(\'[color=navy]\', \'[/color]\', \''.$cible.'\')"><span style="color: navy;">Marine</span></a></li>
                        <li><a onclick="insertTag(\'[color=maroon]\', \'[/color]\', \''.$cible.'\')"><span style="color: maroon;">Marron</span></a></li>
                        <li><a onclick="insertTag(\'[color=black]\', \'[/color]\', \''.$cible.'\')"><span style="color: black;">Noir</span></a></li>
                        <li><a onclick="insertTag(\'[color=gray]\', \'[/color]\', \''.$cible.'\')"><span style="color: gray;">Gris</span></a></li>
                        <li><a onclick="insertTag(\'[color=silver]\', \'[/color]\', \''.$cible.'\')"><span style="color: silver;">Argent</span></a></li>
                    </ul>
                </div>

                <div class="btn-group">
                    <a class="btn btn-default" onclick="insertTag(\'[left]\', \'[/left]\', \''.$cible.'\')"><i class="fa fa-align-left"></i></a>
                    <a class="btn btn-default" onclick="insertTag(\'[center]\', \'[/center]\', \''.$cible.'\')"><i class="fa fa-align-center"></i></a>
                    <a class="btn btn-default" onclick="insertTag(\'[right]\', \'[/right]\', \''.$cible.'\')"><i class="fa fa-align-right"></i></a>
                    <a class="btn btn-default" onclick="insertTag(\'[justify]\', \'[/justify]\', \''.$cible.'\')"><i class="fa fa-align-justify"></i></a>
                </div>

                <div class="btn-group">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <i class="fa fa-caret-down"></i></span>
                    <ul class="dropdown-menu">
                        <li><a onclick="insertTag(\'[tips=info]\', \'[/tips]\', \''.$cible.'\')">Information</a></li>
                        <li><a onclick="insertTag(\'[tips=error]\', \'[/tips]\', \''.$cible.'\')">Erreur</a></li>
                        <li><a onclick="insertTag(\'[tips=alert]\', \'[/tips]\', \''.$cible.'\')">Attention</a></li>
                        <li><a onclick="insertTag(\'[tips=question]\', \'[/tips]\', \''.$cible.'\')">Question</a></li>
                    </ul>
                </div>

		    	<div class="btn-group">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-film"></i> <i class="fa fa-caret-down"></i></span>
                    <ul class="dropdown-menu">
                        <li><a onclick="insertTag(\'[youtube=560,315]\', \'[/youtube]\', \''.$cible.'\')">YouTube</a></li>
                        <li><a onclick="insertTag(\'[dailymotion]\', \'[/dailymotion]\', \''.$cible.'\')">DailyMotion</a></li>
                        <li><a onclick="insertTag(\'[vimeo]\', \'[/vimeo]\', \''.$cible.'\')">Viméo</a></li>
                    </ul>
                </div>

                <div class="btn-group">
                    <span class="btn btn-default" onclick="insertTag(\'[img]\', \'[/img]\', \''.$cible.'\')" title="insérer une image"><i class="fa fa-picture-o"></i></span>
                </div>

		    	<div class="btn-group">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-link"></i> <i class="fa fa-caret-down"></i></span>
                    <ul class="dropdown-menu">
                        <li><a onclick="insertTag(\'[url]\', \'[/url]\', \''.$cible.'\')">Url</a></li>
                        <li><a onclick="insertTag(\'[url=http://]\', \'[/url]\', \''.$cible.'\')">Url + nom</a></li>
                    </ul>
                </div>

                <div class="btn-group">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-comment"></i> <i class="fa fa-caret-down"></i></span>
                    <ul class="dropdown-menu">
                        <li><a onclick="insertTag(\'[quote=nom]\', \'[/quote]\', \''.$cible.'\')" class="bouton_cliquable">Nom + citation</a></li>
                        <li><a onclick="insertTag(\'[quote=http://]\', \'[/quote]\', \''.$cible.'\')" class="bouton_cliquable">Lien + citation</a></li>
                        <li><a onclick="insertTag(\'[quote]\', \'[/quote]\', \''.$cible.'\')" class="bouton_cliquable">Citation</a></li>
                    </ul>
                </div>

            </div>
	    	';

	    	return $menu;
    	}
    }

    public function parsermcode($cible, $type, $url)
    {
        //" = &quot;
        //' = &#39;
        //< = &lt;
        //> = &gt;
        //& = &amp;

        //$text = preg_replace("#[^]]http://[a-z0-9._\#\!\?/-]+#i", "<a target=\"_blank\" href=\"$0\">$0</a>", $text);

        if ($type == 'comments')
        {
            $cible = $this->parserTypo($cible);
            $cible = $this->parserTypoPlus($cible);
            $cible = $this->parserPos($cible);
            $cible = $this->parserMedia($cible);
            $cible = $this->parserInter($cible);
            $cible = $this->parserSmileys($cible, $url);

            return $cible;
        }
        elseif ($type == 'article')
        {
            $cible = $this->parserTypo($cible);
            $cible = $this->parserTypoPlus($cible);
            $cible = $this->parserPos($cible);
            $cible = $this->parserTips($cible);
            $cible = $this->parserMedia($cible);
            $cible = $this->parserInter($cible);
            $cible = $this->parserSmileys($cible, $url);

            return $cible;
        }
    }

    public function parserSmileys($cible, $url)
    {
        $cible = str_replace(':)', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/smile.png" title="Content" alt="Content" /> ', $cible);
        $cible = str_replace(':D', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/heureux.png" title="Très content" alt="Très content" /> ', $cible);
        $cible = str_replace('xD', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/rire.gif" title="Lol" alt="Lol" /> ', $cible);
        $cible = str_replace(':.', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/unsure.gif" title="Incertain" alt="Incertain" /> ', $cible);
        $cible = str_replace('B)', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/soleil.png" title="Classe" alt="Classe" /> ', $cible);
        $cible = str_replace(':o', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/huh.png" title="Huh" alt="Huh" /> ', $cible);
        $cible = str_replace(':(', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/triste.png" title="Triste" alt="Triste" /> ', $cible);
        $cible = str_replace(':@', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/mechant.png" title="Méchant" alt="Méchant" /> ', $cible);
        $cible = str_replace(':f', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/rouge.png" title="Rougit" alt="Rougit" /> ', $cible);
        $cible = str_replace('oO', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/blink.gif" title="oO" alt="oO" /> ', $cible);
        $cible = str_replace(':d', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/siffle.png" title="Siflote" alt="Siflote" /> ', $cible);
        $cible = str_replace(':p', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/langue.png" title="Tire la langue" alt="Tire la langue" /> ', $cible);
        $cible = str_replace(';)', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/clin.png" title="Clin d\'oeil" alt="Clin d\'oeil" /> ', $cible);
        $cible = str_replace('^^', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/hihi.png" title="Hihi" alt="Hihi" /> ', $cible);
        $cible = str_replace(';(', ' <img src="'.$url.'/themes/'.$this->config->get('theme').'/img/smileys/pleure.png" title="Pleure" alt="Pleure" /> ', $cible);

        return $cible;
    }

    public function parserTypo($cible)
    {
        $bbcode = array(
            '`\[b\](.+)\[\/b\]`isU',
            '`\[i\](.+)\[\/i\]`isU',
            '`\[u\](.+)\[\/u\]`isU',
            '`\[s\](.+)\[\/s\]`isU'
        );

        $html = array(
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<span style="text-decoration: underline;">$1</span>',
            '<span style="text-decoration: line-through;">$1</span>'
        );

        $cible = preg_replace($bbcode, $html, $cible);

        return $cible;
    }

    public function parserPos($cible)
    {
        $bbcode = array(
            '`\[center\](.+)\[\/center\]`isU',
            '`\[left\](.+)\[\/left\]`isU',
            '`\[right\](.+)\[\/right\]`isU',
            '`\[justify\](.+)\[\/justify\]`isU'
        );

        $html = array(
            '<p style="text-align: center;">$1</p>',
            '<p style="text-align: left;">$1</p>',
            '<p style="text-align: right;">$1</p>',
            '<p style="text-align: justify;">$1</p>',
        );

        $cible = preg_replace($bbcode, $html, $cible);

        return $cible;
    }

    public function parserMedia($cible)
    {
        $bbcode = array(
            '`\[youtube=(.+),(.+)\]http://youtu.be/(.+)\[\/youtube\]`isU',
            '`\[dailymotion\]&lt;iframe frameborder=&quot;0&quot; width=&quot;(.+)&quot; height=&quot;(.+)&quot; src=&quot;http:\/\/www.dailymotion.com\/embed\/video\/(.+)&quot;&gt;&lt;\/iframe&gt;(.+)&lt;\/a&gt;&lt;\/i&gt;\[\/dailymotion\]`isU',
            '`\[vimeo\]http://vimeo.com/(.+)\[\/vimeo\]`isU',
            '`\[img\](.+)\[\/img\]`isU',
            '`&lt;iframe width=&quot;(.+)&quot; height=&quot;(.+)&quot; src=&quot;http:\/\/www.youtube.com\/embed\/(.+)&quot; frameborder=&quot;0&quot; allowfullscreen&gt;&lt;\/iframe&gt;`isU',
            '`&lt;iframe frameborder=&quot;0&quot; width=&quot;(.+)&quot; height=&quot;(.+)&quot; src=&quot;http:\/\/www.dailymotion.com\/embed\/video\/(.+)&quot;&gt;&lt;\/iframe&gt;`isU',
            '`&lt;iframe src=&quot;http:\/\/store.steampowered.com\/widget\/(.+)&quot;&gt;&lt;\/iframe&gt;`isU',
        );

        $html = array(
            '<p style="text-align: center;"><iframe width="$1" height="$2" src="http://www.youtube.com/embed/$3" frameborder="0" allowfullscreen></iframe></p>',
            '<p style="text-align: center;"><iframe frameborder="0" width="$1" height="$2" src="http://www.dailymotion.com/embed/video/$3"></iframe></p>',
            '<p style="text-align: center;"><iframe src="http://player.vimeo.com/video/$1" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></p>',
            '<a target="_blank" title="Voir en taille r&#233;el" href="$1" ><img style="max-width: 100%;" src="$1" alt="Image" /></a>',
            '<br /><iframe width="$1" height="$2" src="http://www.youtube.com/embed/$3" frameborder="0" allowfullscreen></iframe><br />',
            '<br /><iframe frameborder="0" width="$1" height="$2" src="http://www.dailymotion.com/embed/video/$3"></iframe><br />',
            '<br /><iframe src="http://store.steampowered.com/widget/$1" frameborder="0" width="646" height="190"></iframe><br />',
        );

        $cible = preg_replace($bbcode, $html, $cible);

        return $cible;
    }

    public function parserInter($cible)
    {
        $bbcode = array(
            '`\[quote\](.+)\[\/quote\]`isU',
            '`\[quote=http(.+)\](.+)\[\/quote\]`isU',
            '`\[quote=(.+)\](.+)\[\/quote\]`isU',
            '`\[url\](.+)\[\/url\]`isU',
            '`\[url=(.+)\](.+)\[\/url\]`isU',
            '`\[email\](.+)\[\/email\]`isU',
        );

        $html = array(
            '<blockquote><p>$1</p></blockquote>',
            '<blockquote><p>$2</p><small>http$1</small></blockquote>',
            '<blockquote><p>$2</p><small>$1</small></blockquote>',
            '<a target="_blank" href="$1">$1</a>',
            '<a target="_blank" href="$1">$2</a>',
            '<a href="mailto:$1">$1</a>',
        );

        $cible = preg_replace($bbcode, $html, $cible);

        return $cible;
    }

    public function parserTypoPlus($cible)
    {
        $bbcode = array(
            '`\[header1\](.+)\[\/header1\]`isU',
            '`\[header2\](.+)\[\/header2\]`isU',
            '`\[p\](.+)\[\/p\]`isU',
            '`\[f\](.+)\[\/f\]`isU',
            '`\[list\](.+)\[\/list\]`isU',
            '`\[list=1\](.+)\[\/list\]`isU',
            '`\[\*\](.+)\n`isU',
            '`\[color=(.+)\]`isU',
            '`\[\/color\]`isU',
            '`\[c\](.+)\[\/c\]`isU',
            '`\[code\](.+)\[\/code\]`isU',
        );

        $html = array(
            '<h2>$1</h2>', 
            '<h3>$1</h3>', 
            '<p>$1</p>', 
            '<fieldset><legend>$1</legend></fieldset>', 
            '<ul>$1</ul>', 
            '<ol>$1</ol>', 
            '<li>$1</li>', 
            '<span style="color: $1;">', 
            '</span>', 
            '<code>$1</code>', 
            '<pre>$1</pre>', 
        );

        $cible = preg_replace($bbcode, $html, $cible);

        return $cible;
    }

    public function parserTips($cible)
    {
        $bbcode = array(
            '`\[tips=info\](.+)\[\/tips\]`isU',
            '`\[tips=error\](.+)\[\/tips\]`isU',
            '`\[tips=alert\](.+)\[\/tips\]`isU',
            '`\[tips=question\](.+)\[\/tips\]`isU',
        );

        $html = array(
            '<div class="alert alert-success">
                $1
            </div>', 
            '<div class="alert alert-error">
                $1
            </div>', 
            '<div class="alert alert">
                $1
            </div>', 
            '<div class="alert alert-info">
                $1
            </div>'
        );

        $cible = preg_replace($bbcode, $html, $cible);

        return $cible;
    }
}