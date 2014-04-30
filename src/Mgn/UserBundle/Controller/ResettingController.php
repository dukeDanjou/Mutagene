<?php

namespace Mgn\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\HttpFoundation\Request;
use Mgn\UserBundle\Entity\User;
use Mgn\UserBundle\Form\ResetType;
use Symfony\Component\Security\Core\Util\SecureRandom;

class ResettingController extends Controller
{
    public function requestAction()
    {
        return $this->container->get('templating')->renderResponse('MgnUserBundle:Resetting:request.html.twig');
    }

    public function sendEmailAction(Request $request)
    {
    	$translator = $this->get('translator');

    	$config = $this->container->get('mgn.config');

    	$em = $this->getDoctrine()->getManager();

    	$username = $request->request->get('username');

    	$user = $em->getRepository('MgnUserBundle:User')
                	->findUserByUsernameOrEmail($username);

        if (null === $user)
        {
            return $this->container->get('templating')->renderResponse('MgnUserBundle:Resetting:request.html.twig', array('invalid_username' => $username));
        }

        if ($user->getIsActive() == false)
        {
        	$user->setConfirmationToken(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

        	$em->flush();

			$message = \Swift_Message::newInstance()
		        ->setSubject('['.$config->get('siteTitre').'] '.$translator->trans('user.mail.register.subject'))
		        ->setFrom($config->get('email'))
		        ->setTo($user->getEmail())
		        ->setBody($this->renderView('MgnUserBundle:Registration:email.txt.twig', array(
		        		'username' => $user->getUsername(),
		        		'confirmationToken' => $user->getConfirmationToken(),
		        	)))
		    ;
		    $this->get('mailer')->send($message);

        	$this->get('session')->getFlashBag()->add('danger', $translator->trans('user.flash.danger.not_active'));

            return $this->redirect( $this->generateUrl('user.connexion') );
        }

        $user->setConfirmationToken(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        $user->setPasswordRequestedAt(new \DateTime());
        $em->flush();

        $message = \Swift_Message::newInstance()
	        ->setSubject('['.$config->get('siteTitre').'] '.$translator->trans('user.mail.resetting.subject'))
	        ->setFrom($config->get('email'))
	        ->setTo($user->getEmail())
	        ->setBody($this->renderView('MgnUserBundle:Resetting:email.txt.twig', array(
	        		'username' => $user->getUsername(),
	        		'email' => $user->getEmail(),
	        		'confirmationToken' => $user->getConfirmationToken(),
	        	)))
	    ;

	    $this->get('mailer')->send($message);

	    return $this->container->get('templating')->renderResponse('MgnUserBundle:Resetting:check_email.html.twig', array('username' => $user->getUsername()));
    }

    public function resetAction($email, $token)
    {
    	$em = $this->getDoctrine()->getManager();

    	$user = $em->getRepository('MgnUserBundle:User')
                	->findUserByEmailAndToken($email, $token);

        if (null === $user)
        {
            return $this->container->get('templating')->renderResponse('MgnUserBundle:Resetting:request.html.twig', array('invalid_reset' => $email));
        }

        $form = $this->createForm(new ResetType, $user);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST')
		{
			$form->bind($request);

			if ($form->isValid())
			{
				$em = $this->getDoctrine()->getManager();

				$factory = $this->get('security.encoder_factory');

				$encoder = $factory->getEncoder($user);
				$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
				$user->setPassword($password);

				$user->setConfirmationToken(null);

				$em->flush();

				$translator = $this->get('translator');

				$this->get('session')->getFlashBag()->add('success', $translator->trans('resetting.reset.flash.confirm'));

				return $this->redirect( $this->generateUrl('user.connexion') );
			}
		}

        return $this->render('MgnUserBundle:Resetting:reset.html.twig', array(
            'token' => $token,
            'email' => $email,
            'form' => $form->createView(),
        ));
    }
}