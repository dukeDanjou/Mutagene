<?php
namespace Mgn\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ModulesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
			->add('cmsForum', 'choice', array(
                            'choices' => array(true => 'Activé', false => 'Désactivé')
                        ))
            ->add('cmsArticle', 'choice', array(
                            'choices' => array(true => 'Activé', false => 'Désactivé')
                        ))
            ;
    }

    public function getName()
    {
        return 'mgn_corebundle_modulestype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Mgn\CoreBundle\Entity\Config',
        );
    }
}
