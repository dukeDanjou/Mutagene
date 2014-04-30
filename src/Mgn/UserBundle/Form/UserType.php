<?php
namespace Mgn\UserBundle\Form;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
 
class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('username', 'text', array(
                'required' => false,
            ))
      ->add('email', 'text', array(
                'required' => false,
            ))
      ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array('label' => 'form.registration.password'),
                'second_options' => array('label' => 'form.registration.password_confirmation'),
                'required' => false,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
            ))
    ;
  }
 
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Mgn\UserBundle\Entity\User'
    ));
  }
 
  public function getName()
  {
    return 'mgn_userbundle_usertype';
  }
}