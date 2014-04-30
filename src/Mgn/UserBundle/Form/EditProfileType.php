<?php
namespace Mgn\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Mgn\CoreBundle\Twig\Extension\ConfigExtension;

class EditProfileType extends AbstractType
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $config = $this->container->get('mgn.config');
        
        if( $config->get('profileFirstName') == true )
        {
            $builder->add('firstName',    'text', array('required' => false));
        }
        
        if( $config->get('profileLastName') == true )
        {
            $builder->add('lastName',    'text', array('required' => false));
        }
        
        if( $config->get('profileBirthday') == true )
        {
            $builder->add('birthday', 'date', array('widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'required' => false));
        }
        
        if( $config->get('profileSignature') == true )
        {
           $builder->add('signature', 'textarea', array('required' => false));
        }
        
        if( $config->get('profileTwitter') == true )
        {
            $builder->add('twitter',    'text', array('required' => false));
        }
        
        if( $config->get('profileFacebook') == true )
        {
            $builder->add('facebook',    'text', array('required' => false));
        }
        
        if( $config->get('profileGoogleplus') == true )
        {
            $builder->add('googleplus',    'text', array('required' => false));
        }
        
        if( $config->get('profileSteam') == true )
        {
            $builder->add('steam',    'text', array('required' => false));
        }

        $builder->add('avatar', 'choice', array(
                        'choices'   => array(
                            'default'   => 'Défaut',
                            'gravatar' => 'Gravatar',
                            'personal'   => 'Personnalisé',
                        ),
                        'multiple'  => false,
                        'expanded'  => true
                    ));
        $builder->add('avatarFile',    'file', array('required' => false));
    }

    public function getName()
    {
        return 'mgn_userbundle_editprofiletype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Mgn\UserBundle\Entity\User',
        );
    }
}
