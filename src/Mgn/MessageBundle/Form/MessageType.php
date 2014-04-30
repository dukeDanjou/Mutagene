<?php
namespace Mgn\MessageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Mgn\MessageBundle\Entity\Message;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
			->add('contents',    'textarea')
            ;
    }

    public function getName()
    {
        return 'mgn_messagebundle_messagetype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mgn\MessageBundle\Entity\Message',
            'cascade_validation' => true,
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Mgn\MessageBundle\Entity\Message',
        );
    }
}
