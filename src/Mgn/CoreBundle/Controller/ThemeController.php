<?php

namespace Mgn\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Mgn\CoreBundle\Entity\Theme;
use Mgn\CoreBundle\Form\EditorHeadType;
use Mgn\CoreBundle\Form\EditorLayoutType;
use Mgn\CoreBundle\Form\EditorMenuType;
use Mgn\CoreBundle\Form\EditorJavascriptType;
use Mgn\CoreBundle\Form\EditorType;

class ThemeController extends Controller
{
    public function listAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
    			throw new AccessDeniedHttpException('Vous devez disposer des droits SuperAdmin');
    		}

    		$themes = $this->getDoctrine()
                          	 ->getManager()
                          	 ->getRepository('MgnCoreBundle:Theme')
                          	 ->findAll();

    		return $this->render('MgnCoreBundle:Theme:list.html.twig', array(
    			'themes' => $themes,
    		));
    }

    public function editorHeadAction($theme)
    {
        if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
          throw new AccessDeniedHttpException('Vous devez disposer des droits SuperAdmin');
        }

        $theme = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->find($theme);

        $themes = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->findAll();

        $form = $this->createForm(new EditorHeadType, $theme);

        $request = $this->get('request');

        // On vérifie qu'elle est de type « POST ».
        if( $request->getMethod() == 'POST' )
        {
            // On fait le lien Requête <-> Formulaire.
            $form->bind($request);
    
            // On vérifie que les valeurs rentrées sont correctes.
            if( $form->isValid() )
            {
                // On l'enregistre notre objet $cateforie dans la base de données.
                $em = $this->getDoctrine()->getManager();

                $em->flush();
          
                //message de confirmation
                $this->get('session')->getFlashBag()->add('success', 'Le fichier Head à bien été mis à jour.');
                
                // On redirige vers la page d'accueil, par exemple.
                return $this->redirect( $this->generateUrl('mgn_core_admin_editor_head', array('theme' => $theme->getId())));
            }
        }

        return $this->render('MgnCoreBundle:Editor:head.html.twig', array(
          'theme' => $theme,
          'themes' => $themes,
          'form' => $form->createView(),
        ));
    }

    public function editorLayoutAction($theme)
    {
        if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
          throw new AccessDeniedHttpException('Vous devez disposer des droits SuperAdmin');
        }

        $theme = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->find($theme);

        $themes = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->findAll();

        $form = $this->createForm(new EditorLayoutType, $theme);

        $request = $this->get('request');

        // On vérifie qu'elle est de type « POST ».
        if( $request->getMethod() == 'POST' )
        {
            // On fait le lien Requête <-> Formulaire.
            $form->bind($request);
    
            // On vérifie que les valeurs rentrées sont correctes.
            if( $form->isValid() )
            {
                // On l'enregistre notre objet $cateforie dans la base de données.
                $em = $this->getDoctrine()->getManager();

                $em->flush();
          
                //message de confirmation
                $this->get('session')->getFlashBag()->add('success', 'Le fichier Layout.html.twig à bien été mis à jour.');
                
                // On redirige vers la page d'accueil, par exemple.
                return $this->redirect( $this->generateUrl('mgn_core_admin_editor_layout', array('theme' => $theme->getId())));
            }
        }

        return $this->render('MgnCoreBundle:Editor:layout.html.twig', array(
          'theme' => $theme,
          'themes' => $themes,
          'form' => $form->createView(),
        ));
    }

    public function editorMenuAction($theme)
    {
        if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
          throw new AccessDeniedHttpException('Vous devez disposer des droits SuperAdmin');
        }

        $theme = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->find($theme);

        $themes = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->findAll();

        $form = $this->createForm(new EditorMenuType, $theme);

        $request = $this->get('request');

        // On vérifie qu'elle est de type « POST ».
        if( $request->getMethod() == 'POST' )
        {
            // On fait le lien Requête <-> Formulaire.
            $form->bind($request);
    
            // On vérifie que les valeurs rentrées sont correctes.
            if( $form->isValid() )
            {
                // On l'enregistre notre objet $cateforie dans la base de données.
                $em = $this->getDoctrine()->getManager();

                $em->flush();
          
                //message de confirmation
                $this->get('session')->getFlashBag()->add('success', 'Le fichier Menu.html.twig à bien été mis à jour.');
                
                // On redirige vers la page d'accueil, par exemple.
                return $this->redirect( $this->generateUrl('mgn_core_admin_editor_menu', array('theme' => $theme->getId())));
            }
        }

        return $this->render('MgnCoreBundle:Editor:menu.html.twig', array(
          'theme' => $theme,
          'themes' => $themes,
          'form' => $form->createView(),
        ));
    }

    public function editorJavascriptAction($theme)
    {
        if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
          throw new AccessDeniedHttpException('Vous devez disposer des droits SuperAdmin');
        }

        $theme = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->find($theme);

        $themes = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->findAll();

        $form = $this->createForm(new EditorJavascriptType, $theme);

        $request = $this->get('request');

        // On vérifie qu'elle est de type « POST ».
        if( $request->getMethod() == 'POST' )
        {
            // On fait le lien Requête <-> Formulaire.
            $form->bind($request);
    
            // On vérifie que les valeurs rentrées sont correctes.
            if( $form->isValid() )
            {
                // On l'enregistre notre objet $cateforie dans la base de données.
                $em = $this->getDoctrine()->getManager();

                $em->flush();
          
                //message de confirmation
                $this->get('session')->getFlashBag()->add('success', 'Le fichier Javascript à bien été mis à jour.');
                
                // On redirige vers la page d'accueil, par exemple.
                return $this->redirect( $this->generateUrl('mgn_core_admin_editor_javascript', array('theme' => $theme->getId())));
            }
        }

        return $this->render('MgnCoreBundle:Editor:javascript.html.twig', array(
          'theme' => $theme,
          'themes' => $themes,
          'form' => $form->createView(),
        ));
    }

    public function editorAction($theme, $file)
    {
        if (!$this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
        {
          throw new AccessDeniedHttpException('Vous devez disposer des droits SuperAdmin');
        }

        $theme = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->find($theme);

        $themes = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('MgnCoreBundle:Theme')
                             ->findAll();

        $form = $this->createForm(new EditorType($file), $theme);

        $request = $this->get('request');

        // On vérifie qu'elle est de type « POST ».
        if( $request->getMethod() == 'POST' )
        {
            // On fait le lien Requête <-> Formulaire.
            $form->bind($request);
    
            // On vérifie que les valeurs rentrées sont correctes.
            if( $form->isValid() )
            {
                // On l'enregistre notre objet $cateforie dans la base de données.
                $em = $this->getDoctrine()->getManager();

                $em->flush();
          
                //message de confirmation
                $this->get('session')->getFlashBag()->add('success', 'Le fichier '.$file.' à bien été mis à jour.');
                
                // On redirige vers la page d'accueil, par exemple.
                return $this->redirect( $this->generateUrl('mgn_core_admin_editor', array('theme' => $theme->getId(), 'file' => $file)));
            }
        }

        return $this->render('MgnCoreBundle:Editor:editor.html.twig', array(
          'file' => $file,
          'theme' => $theme,
          'themes' => $themes,
          'form' => $form->createView(),
        ));
    }
}
