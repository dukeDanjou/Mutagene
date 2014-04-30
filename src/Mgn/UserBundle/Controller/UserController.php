<?php

namespace Mgn\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mgn\UserBundle\Entity\User;
use Mgn\UserBundle\Entity\Group;
use Mgn\UserBundle\Entity\UserToGroup;
use Mgn\UserBundle\Form\EditProfileType;
use Mgn\UserBundle\Form\ChangePasswordType;

use Symfony\Component\HttpFoundation\Request;

use JMS\SecurityExtraBundle\Annotation\Secure;

class UserController extends Controller
{
    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function editProfileAction($username)
    {
        $user = $this->container->get('security.context')->getToken()->getUser()->getId();

        $user = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:User')
                         ->find($user);

        $form = $this->createForm(new EditProfileType($this->container), $user);

        // On récupère le formulaire et on le traite
        $request = $this->get('request');

        // On vérifie qu'elle est de type « POST ».
        if( $request->getMethod() == 'POST' )
        {
            // On fait le lien Requête <-> Formulaire.
            $form->bind($request);
    
            // On vérifie que les valeurs rentrées sont correctes.
            if( $form->isValid() )
            {
                // On l'enregistre notre objet $article dans la base de données.
                $em = $this->getDoctrine()->getManager();

                if ($user->getAvatarFile())
                {
                    $extension = $user->getExtension();

                    if ($extension != 'jpg' and $extension != 'jpeg' and $extension != 'gif' and $extension != 'png' and $extension != 'bmp')
                    {
                        $this->get('session')->getFlashBag()->add('info', 'L\'extension du fichier de votre avatar est incorrect.');

                        return $this->redirect( $this->generateUrl('mgn_user_edit_profile', array('username' => $username)));
                    }

                    $size = GetImageSize($user->getAvatarFile());

                    if ( $size[0] != 150 or $size[1] != 150 )
                    {
                        $this->get('session')->getFlashBag()->add('info', 'La taille de votre avatar doit être de 150px par 150px.');

                        return $this->redirect( $this->generateUrl('mgn_user_edit_profile', array('username' => $username)));
                    }

                    if ( $size[0] != $size[1] )
                    {
                        $this->get('session')->getFlashBag()->add('info', 'La taille de votre avatar doit être de forme carré.');

                        return $this->redirect( $this->generateUrl('mgn_user_edit_profile', array('username' => $username)));
                    }

                    $user->upload();
                }
                
                $em->persist($user);
                $em->flush();
                
                //message de confirmation
                $this->get('session')->getFlashBag()->add('success', 'Votre profil à bien été mis à jour.');
                
                // On redirige vers la page d'accueil, par exemple.
                return $this->redirect( $this->generateUrl('mgn_user_edit_profile', array('username' => $username)));
            }
        }

        return $this->render('MgnUserBundle:User:editProfile.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function editParametersAction($username)
    {
        $user = $this->container->get('security.context')->getToken()->getUser()->getId();

        $user = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:User')
                         ->find($user);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $formPassword = $this->createForm(new ChangePasswordType, $user);

        $request = $this->get('request');
        
        if( $request->getMethod() == 'POST' )
        {
            // On fait le lien Requête <-> Formulaire.
            $formPassword->bind($request);
    
            // On vérifie que les valeurs rentrées sont correctes.
            if( $formPassword->isValid() )
            {
                $em = $this->getDoctrine()->getManager();

                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
            
                $em->persist($user);
                $em->flush();

                
                $this->get('session')->getFlashBag()->add('successPassword', 'Changement de mot de passe réussi.');

                return $this->redirect($this->generateUrl('mgn_user_edit_parameters', array('username' => $username) ));
            }
        }
        
        return $this->render('MgnUserBundle:User:editParametre.html.twig', array(
            'user' => $user,
            'formPassword' => $formPassword->createView(),
        ));
    }

    public function profilAction($username)
    {
        $user = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:User')
                         ->findOneByUsernameCanonical($username);

        return $this->render('MgnUserBundle:User:profile.html.twig', array(
            'user' => $user,
        ));
    }
}
