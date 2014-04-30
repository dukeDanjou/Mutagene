<?php

namespace Mgn\CoreBundle\Twig\Extension;

class DatetimeExtension extends \Twig_Extension
{
	public function getName()
	{
		return 'mgn_datetime';
	}
	
	public function getFilters()
    {
        return array(
           'datetime' => new \Twig_Filter_Method($this, 'datetime'),
        );
    }
 
    public function datetime($date)
    {
    	$lessThanOneMinute = new \Datetime();
    	$lessThanOneMinute = $lessThanOneMinute->modify('-1 minutes');
    	$lessThanOneHour = new \Datetime();
    	$lessThanOneHour = $lessThanOneHour->modify('-1 hours');
    	$lessThanOneDay = new \Datetime();
    	$lessThanOneDay = $lessThanOneDay->modify('-1 days');

    	$dateNow = new \Datetime();
    	$interval = $dateNow->diff($date);

        if ( $dateNow->format('Y/m/d') == $date->format('Y/m/d') )
        {
        	if ( $interval->y == 0 and $interval->m == 0 and $interval->d == 0 and $interval->h == 0 and $interval->i == 0 and $interval->s <= 59  )
        	{
        		$date = "il y a moins d'une minute";
        	}
            elseif ( $interval->y == 0 and $interval->m == 0 and $interval->d == 0 and $interval->h == 0 and $interval->i <= 59  )
            {
                if ( $interval->i == 1 )
                {
                    $date = "il y a environ ".$interval->i." minute";
                }
                else
                {
                    $date = "il y a environ ".$interval->i." minutes";
                }
            }
            elseif ( $interval->y == 0 and $interval->m == 0 and $interval->d == 0 and $interval->h <= 23  )
            {
                if ( $interval->i == 1 )
                {
                    $date = "il y a environ ".$interval->h." heure";
                }
                else
                {
                    $date = "il y a environ ".$interval->h." heures";
                }
            }
        }
        elseif ( $interval->y == 0 and $interval->m == 0 and $dateNow->format('d') == $date->format('d') + 1 )
        {
            $date = "hier, à ".$date->format('H:i');
        }
        elseif ( $interval->y == 0 and $interval->m == 0 and $interval->d <= 6 )
        {
            if ( $date->format('w') == 0 ){ $jour = 'dimanche'; }
            if ( $date->format('w') == 1 ){ $jour = 'lundi'; }
            if ( $date->format('w') == 2 ){ $jour = 'mardi'; }
            if ( $date->format('w') == 3 ){ $jour = 'mercredi'; }
            if ( $date->format('w') == 4 ){ $jour = 'jeudi'; }
            if ( $date->format('w') == 5 ){ $jour = 'vendredi'; }
            if ( $date->format('w') == 6 ){ $jour = 'samedi'; }

            $date = $jour .", à ".$date->format('H:i');
        }
        else
        {
            if ( $date->format('m') == 01 ){ $mois = 'janvier'; }
            if ( $date->format('m') == 02 ){ $mois = 'février'; }
            if ( $date->format('m') == 03 ){ $mois = 'mars'; }
            if ( $date->format('m') == 04 ){ $mois = 'avril'; }
            if ( $date->format('m') == 05 ){ $mois = 'mai'; }
            if ( $date->format('m') == 06 ){ $mois = 'juin'; }
            if ( $date->format('m') == 07 ){ $mois = 'juillet'; }
            if ( $date->format('m') == 08 ){ $mois = 'août'; }
            if ( $date->format('m') == 09 ){ $mois = 'septembre'; }
            if ( $date->format('m') == 10 ){ $mois = 'octobre'; }
            if ( $date->format('m') == 11 ){ $mois = 'novembre'; }
            if ( $date->format('m') == 12 ){ $mois = 'décembre'; }

            $date = "le ".$date->format('j')." ".$mois." ".$date->format('Y')." à ".$date->format('H:i');
        }
		
		return $date;
    }
}