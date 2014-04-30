<?php

namespace Mgn\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Mgn\UserBundle\Entity\Group;
use Mgn\UserBundle\Entity\GroupRepository;
use Mgn\UserBundle\Entity\UserToGroup;
use Mgn\UserBundle\Form\GroupType;
use Mgn\UserBundle\Form\GroupAdministerType;
use Mgn\UserBundle\Form\GroupAddUserType;

use JMS\SecurityExtraBundle\Annotation\Secure;

class AdminController extends Controller
{
    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function listUsersAction()
    {
        $users_list = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:User')
                         ->findBy(array('isActive' => 1));

        return $this->render('MgnUserBundle:Admin:user_list.html.twig', array(
			'users_list' => $users_list,
        ));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function profileUserAction($id)
    {
        $user = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:User')
                         ->find($id);

        $groups = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:UserToGroup')
                         ->loadGroupForUser($id);

        return $this->render('MgnUserBundle:Admin:profile.html.twig', array(
            'user' => $user,
            'groups' => $groups,
        ));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function listGroupsAction()
    {
        $group = new Group;

        $form = $this->createForm(new GroupType, $group);

        $request = $this->get('request');

        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                $config = $this->container->get('mgn.config');

                $em->persist($group);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Votre nouveau groupe à bien été ajouté.');
            }
        }

        $groups_list = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:Group')
                         ->findAll();

        return $this->render('MgnUserBundle:Admin:group_list.html.twig', array(
            'groups_list' => $groups_list,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function profileGroupAction($id)
    {
        $group = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:Group')
                         ->find($id);

        return $this->render('MgnUserBundle:Admin:group.html.twig', array(
            'group' => $group,
        ));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function administerGroupAction($id)
    {
        $group = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:Group')
                         ->loadGroupWithUsers($id);

        $formgroup = $this->createForm(new GroupAdministerType, $group);

        $userToGroup = new UserToGroup;

        $formuser = $this->createForm(new GroupAddUserType, $userToGroup);

        $request = $this->get('request');

        if('POST' === $request->getMethod())
        {
            $em = $this->getDoctrine()->getManager();

            if ($request->request->has('mgn_userbundle_groupaddusertype'))
            {
                $formuser->bind($request);

                if ($formuser->isValid())
                {
                    $userToGroup->setGroup($group);
                    $group->setCountUsers($group->getCountUsers() + 1);

                    $em->persist($userToGroup);
                    $em->flush();

                    //message de confirmation
                    $this->get('session')->getFlashBag()->add('successUser', 'Le membre à bien été ajouté.');
                }
            }

            if ($request->request->has('mgn_userbundle_groupadministertype'))
            {
                $formgroup->bind($request);

                if ($formgroup->isValid())
                {
                    $em->persist($group);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('success', 'Vos modifications ont bien été prise en compte.');
                }
            }
        }

        return $this->render('MgnUserBundle:Admin:groupAdminister.html.twig', array(
            'group' => $group,
            'formgroup' => $formgroup->createView(),
            'formuser' => $formuser->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteUserToGroupAction($groupid, $userid)
    {
        $group = $this->getDoctrine()
                         ->getEntityManager()
                         ->getRepository('MgnUserBundle:Group')
                         ->find($groupid);

        if( $group == null )
        {
            throw $this->createNotFoundException('Group [id='.$groupid.'] inexistant');
        }

        $userToGroup = $this->getDoctrine()
                         ->getEntityManager()
                         ->getRepository('MgnUserBundle:UserToGroup')
                         ->findOneBy(
                            array('group' => $groupid, 'user' => $userid),                 // Pas de critère
                            array(), // On tri par date décroissante
                            NULL,       // On sélectionne $nb_articles_page articles
                            NULL                  // A partir du $offset ième
                        );

        if( $userToGroup == null )
        {
            throw $this->createNotFoundException('UserToGroup inexistant');
        }

        $group->setCountUsers($group->getCountUsers() - 1);

        $em = $this->getDoctrine()->getManager();

        $em->remove($userToGroup);

        $em->flush();

        //message de confirmation
        $this->get('session')->getFlashBag()->add('successUser', '' . $userToGroup->getUser()->getUsername() . ' à bien été expulsé du groupe.');
            
        return $this->redirect( $this->generateUrl('mgn_user_admin_group_users', array('id' => $groupid)));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteGroupAction($id)
    {
        $group = $this->getDoctrine()
                         ->getEntityManager()
                         ->getRepository('MgnUserBundle:Group')
                         ->find($id);

        if( $group == null )
        {
            throw $this->createNotFoundException('Group [id='.$id.'] inexistant');
        }

        //message de confirmation
        $this->get('session')->getFlashBag()->add('success', 'Le groupe ' . $group->getName() . ' à bien été supprimé.');

        $em = $this->getDoctrine()->getManager();

        $em->remove($group);

        $em->flush();
            
        return $this->redirect( $this->generateUrl('mgn_user_admin_list_group'));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function usersToGroupAction($id)
    {
        $group = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:Group')
                         ->loadGroupWithUsers($id);

        $userToGroup = new UserToGroup;

        $form = $this->createForm(new GroupAddUserType, $userToGroup);

        $request = $this->get('request');

        if('POST' === $request->getMethod())
        {
            $em = $this->getDoctrine()->getManager();

            $form->bind($request);

            if ($form->isValid())
            {
                $userToGroup->setGroup($group);
                $group->setCountUsers($group->getCountUsers() + 1);

                $em->persist($userToGroup);
                $em->flush();

                //message de confirmation
                $this->get('session')->getFlashBag()->add('successUser', 'Le membre à bien été ajouté.');

                return $this->redirect( $this->generateUrl('mgn_user_admin_group_users', array('id' => $id)));
            }
        }

        return $this->render('MgnUserBundle:Admin:groupUsers.html.twig', array(
            'group' => $group,
            'form' => $form->createView(),
        ));
    }
}
