<?php
namespace Mgn\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',    'text')
            ->add('description',    'text')
            ->add('sort',    'text')
            ->add('category', 'entity', array(
                                        'class' => 'MgnForumBundle:Category', 
                                        'property' => 'name',
                                        'query_builder' => function ($repository) 
                                        {
                                            $qb = $repository->createQueryBuilder('c');
                                            $qb->add('orderBy', 'c.sort'); 
                                            return $qb;
                                        },
                                        'preferred_choices' => array(),
                                        ))
            ->add('publicAclView', 'checkbox', array('required'  => false,))
            ->add('publicAclPost', 'checkbox', array('required'  => false,))
            ->add('publicAclTopic', 'checkbox', array('required'  => false,))
            ;
    }

    public function getName()
    {
        return 'mgn_forumbundle_forumtype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Mgn\ForumBundle\Entity\forum',
        );
    }
}
