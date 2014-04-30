<?php
namespace Mgn\UserBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
 
class GroupType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', 'text', array(
                'required' => false,
            ))
    ;
  }
 
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Mgn\UserBundle\Entity\Group'
    ));
  }
 
  public function getName()
  {
    return 'mgn_userbundle_grouptype';
  }
}