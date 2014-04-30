<?php
namespace Mgn\ArticleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArticleEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
	    	->add('title',    'text')
            ->add('url',    'text', array('required'  => false,))
            ->add('header',    'text')
            ->add('contents',    'textarea')
            ->add('comments', 'checkbox', array('required'  => false,))
            ->add('status', 'choice', array(
                                            'choices'   => array('0' => 'Brouillon', '1' => 'Publier'),
                                            
                                            'required'  => true,
                                        ))
            ->add('date',    'datetime', array(
                                            'date_widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'required' => true,
                                            'time_widget' => 'single_text', 'format' => 'hh:mm', 'required' => true
                                            ))
            ->add('category', 'entity', array(
                                        'class' => 'MgnArticleBundle:Category', 
                                        'property' => 'name',
                                        'query_builder' => function ($repository) 
                                        {
                                            $qb = $repository->createQueryBuilder('c'); 
                                            $qb->add('orderBy', 'c.name'); 
                                            return $qb;
                                        },
                                        'preferred_choices' => array(),
                                        ))
            ->add('author', 'entity', array(
                                        'class' => 'MgnUserBundle:User', 
                                        'property' => 'username',
                                        'query_builder' => function ($repository) 
                                        {
                                            $qb = $repository->createQueryBuilder('u');
                                            $qb->add('where', 'u.isActive = 1'); 
                                            $qb->add('orderBy', 'u.username'); 
                                            return $qb;
                                        },
                                        'preferred_choices' => array(),
                                        ))
            ;
    }

    public function getName()
    {
        return 'mgn_articlebundle_articletype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Mgn\ArticleBundle\Entity\Article',
        );
    }
}
