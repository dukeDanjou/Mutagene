<?php
namespace Mgn\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
			->add('siteTitle',    'text')
            ->add('siteDescription',    'text')
            ->add('email',    'text')
            ->add('forumPostPage', 'choice', array(
                    'choices'   => array('5' => '5', '10' => '10', '15' => '15', '20' => '20'),
                    'multiple'  => false,
                ))
            ->add('forumTopicPage', 'choice', array(
                    'choices'   => array('5' => '5', '10' => '10', '15' => '15', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '35' => '35'),
                    'multiple'  => false,
                ))
            ->add('forumIndexLigne', 'choice', array(
                    'choices'   => array('1' => '1', '2' => '2', '3' => '3', '4' => '4'),
                    'multiple'  => false,
                ))
            ->add('profileFirstName', 'checkbox', array('required'  => false,))
            ->add('profileLastName', 'checkbox', array('required'  => false,))
            ->add('profileBirthday', 'checkbox', array('required'  => false,))
            ->add('profileSignature', 'checkbox', array('required'  => false,))
            ->add('profileTwitter', 'checkbox', array('required'  => false,))
            ->add('profileFacebook', 'checkbox', array('required'  => false,))
            ->add('profileGoogleplus', 'checkbox', array('required'  => false,))
            ->add('profileSteam', 'checkbox', array('required'  => false,))
            ;
    }

    public function getName()
    {
        return 'mgn_corebundle_configtype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Mgn\CoreBundle\Entity\Config',
        );
    }
}
