<?php
namespace Mgn\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mgn\UserBundle\Entity\User;
use Mgn\UserBundle\Form\UserType;
use Symfony\Component\Security\Core\Util\SecureRandom;

class RegistrationController extends Controller
{
    public function registerAction()
    {
    	$user = new User;

		$form = $this->createForm(new UserType, $user);

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

				$user->setConfirmationToken(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));

				$config = $this->container->get('mgn.config');

				$user->setTheme($config->get('theme'));

				$em->persist($user);
				$em->flush();

				$translator = $this->get('translator');

				$message = \Swift_Message::newInstance()
			        ->setSubject('['.$config->get('siteTitle').'] '.$translator->trans('user.mail.register.subject'))
			        ->setFrom($config->get('email'))
			        ->setTo($user->getEmail())
			        ->setBody($this->renderView('MgnUserBundle:Registration:email.txt.twig', array(
			        		'username' => $user->getUsername(),
			        		'confirmationToken' => $user->getConfirmationToken(),
			        	)))
			    ;
			    $this->get('mailer')->send($message);

				$this->get('session')->getFlashBag()->add('success', $translator->trans('registration.register.flash.confirm'));

				return $this->render('MgnUserBundle:Registration:register_confirm.html.twig', array(
		        ));
			}
		}

        return $this->render('MgnUserBundle:Registration:register.html.twig', array(
        	'form' => $form->createView(),
        ));
    }

    public function confirmationAction($token)
    {
    	$em = $this->getDoctrine()->getManager();
    	$translator = $this->get('translator');

    	$user = $em->getRepository('MgnUserBundle:User')
                   ->findOneByConfirmationToken($token);

        $user->setIsActive(true);
        $user->setConfirmationToken(null);

        $em->flush();

        $this->get('session')->getFlashBag()->add('success', $translator->trans('registration.confirmation.flash.confirm'));

        return $this->redirect( $this->generateUrl('user.connexion') );
    }
}
