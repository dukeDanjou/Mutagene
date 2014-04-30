<?php
// src/Sdz/BlogBundle/Form/CategorieType.php

namespace Mgn\ArticleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentsParagraphType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('paragraph', 'textarea', array('required' => false))
      //->add('h1', 'text', array('required' => false, 'label' => 'Titre niveau 1', 'attr' => array('class' => 'form-control'), 'attr' => array('class' => 'form-control')))
      //->add('h2', 'text', array('required' => false, 'label' => 'Titre niveau 2', 'attr' => array('class' => 'form-control')))
      //->add('picture', 'text', array('required' => false, 'label' => 'Image', 'attr' => array('class' => 'form-control')))
      //->add('video', 'text', array('required' => false, 'label' => 'Vidéo', 'attr' => array('class' => 'form-control')))
      //->add('quote', 'textarea', array('required' => false, 'label' => 'Citation', 'attr' => array('class' => 'form-control')))
      //->add('quoteName', 'text', array('required' => false, 'label' => 'Citation nom', 'attr' => array('class' => 'form-control')))
      //->add('quoteLink', 'text', array('required' => false, 'label' => 'Citation lien', 'attr' => array('class' => 'form-control')))
      //->add('list', 'textarea', array('required' => false, 'label' => 'Liste', 'attr' => array('class' => 'form-control')))
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Mgn\ArticleBundle\Entity\Contents'
    ));
  }

  public function getName()
  {
    return 'mgn_articlebundle_contentstype';
  }
}