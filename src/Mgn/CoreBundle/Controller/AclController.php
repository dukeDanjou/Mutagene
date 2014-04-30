<?php

namespace Mgn\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use JMS\SecurityExtraBundle\Annotation\Secure;

class AclController extends Controller
{
    /**
    * @Secure(roles="IS_AUTHENTICATED_REMEMBERED, ROLE_SUPER_ADMIN")
    */
  public function AclsAction($groupId, $groupSlug, $role, $forumId)
  {
    $groups = $this->getDoctrine()
              ->getManager()
              ->getRepository('MgnUserBundle:Group')
              ->findAll();

    $users = $this->getDoctrine()
              ->getManager()
              ->getRepository('MgnUserBundle:User')
              ->findAll();

    $forums = $this->getDoctrine()
              ->getManager()
              ->getRepository('MgnForumBundle:Forum')
              ->findAllWithCategories();

        if( $groupId != null and $groupSlug != null and $role != null and $forumId != null )
        {
          //on récupère les informations sur le groupe
          $group = $this->getDoctrine()
                              ->getManager()
                              ->getRepository('MgnUserBundle:Group')
                              ->find($groupId);
          
          // Si le topic n'existe pas, on affiche une erreur 404
              if( $group == null )
              {
                  throw $this->createNotFoundException('Groupe[id='.$groupId.'] inexistant');
              }

              //on récupère les informations sur la categorie
          $forum = $this->getDoctrine()
                              ->getManager()
                              ->getRepository('MgnForumBundle:Forum')
                              ->find($forumId);
          
          // Si le topic n'existe pas, on affiche une erreur 404
              if( $forum == null )
              {
                  throw $this->createNotFoundException('Forum [id='.$forumId.'] inexistante');
              }

              $em = $this->getDoctrine()->getManager();

              $role = strtoupper($role);

              if( $role == 'BASE' )
              {
                if( 
                  $group->hasRole('ROLE_FORUM_READ_'.$forum->getId()) == true and 
                  $group->hasRole('ROLE_FORUM_POST_'.$forum->getId()) == true and 
                  $group->hasRole('ROLE_FORUM_TOPIC_'.$forum->getId()) == true 
                )
                {
                  $group->removeRole('ROLE_FORUM_READ_'.$forum->getId());
                  $group->removeRole('ROLE_FORUM_POST_'.$forum->getId());
                  $group->removeRole('ROLE_FORUM_TOPIC_'.$forum->getId());
                }
                else
                {
                  if( $group->hasRole('ROLE_FORUM_READ_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_READ_'.$forum->getId());
                  }

                  if( $group->hasRole('ROLE_FORUM_POST_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_POST_'.$forum->getId());
                  }

                  if( $group->hasRole('ROLE_FORUM_TOPIC_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_TOPIC_'.$forum->getId());
                  }
                }
              }
              elseif( $role == 'MODO' )
              {
                if( 
                  $group->hasRole('ROLE_FORUM_LOCK_'.$forum->getId()) == true and 
                  $group->hasRole('ROLE_FORUM_POSTIT_'.$forum->getId()) == true and 
                  $group->hasRole('ROLE_FORUM_ANNONCE_'.$forum->getId()) == true and 
                  $group->hasRole('ROLE_FORUM_MOVE_'.$forum->getId()) == true 
                )
                {
                  $group->removeRole('ROLE_FORUM_LOCK_'.$forum->getId());
                  $group->removeRole('ROLE_FORUM_POSTIT_'.$forum->getId());
                  $group->removeRole('ROLE_FORUM_ANNONCE_'.$forum->getId());
                  $group->removeRole('ROLE_FORUM_MOVE_'.$forum->getId());
                }
                else
                {
                  if( $group->hasRole('ROLE_FORUM_LOCK_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_LOCK_'.$forum->getId());
                  }

                  if( $group->hasRole('ROLE_FORUM_POSTIT_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_POSTIT_'.$forum->getId());
                  }

                  if( $group->hasRole('ROLE_FORUM_ANNONCE_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_ANNONCE_'.$forum->getId());
                  }

                  if( $group->hasRole('ROLE_FORUM_MOVE_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_MOVE_'.$forum->getId());
                  }
                }
              }
              elseif( $role == 'ADMIN' )
              {
                if( 
                  $group->hasRole('ROLE_FORUM_EDIT_'.$forum->getId()) == true and 
                  $group->hasRole('ROLE_FORUM_DELETE_'.$forum->getId()) == true 
                )
                {
                  $group->removeRole('ROLE_FORUM_EDIT_'.$forum->getId());
                  $group->removeRole('ROLE_FORUM_DELETE_'.$forum->getId());
                }
                else
                {
                  if( $group->hasRole('ROLE_FORUM_EDIT_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_EDIT_'.$forum->getId());
                  }

                  if( $group->hasRole('ROLE_FORUM_DELETE_'.$forum->getId()) == false )
                  {
                    $group->addRole('ROLE_FORUM_DELETE_'.$forum->getId());
                  }
                }
              }
              else
              {
                if( $group->hasRole('ROLE_FORUM_'.$role.'_'.$forum->getId()) == true )
                {
                  $group->removeRole('ROLE_FORUM_'.$role.'_'.$forum->getId());
                }
                else
                {
                  $group->addRole('ROLE_FORUM_'.$role.'_'.$forum->getId());
                }
              } 

              $em->flush();

              return $this->redirect( $this->generateUrl('mgn_admin_core_acls_forGroup', array('groupId' => $group->getId(), 'groupSlug' => $group->getSlug())));
        }
        elseif( $groupId != null and $groupSlug != null and $role == null and $forumId == null )
        {
          //on récupère les informations sur le groupe
        $group = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('MgnUserBundle:Group')
                            ->find($groupId);
        
        // Si le topic n'existe pas, on affiche une erreur 404
            if( $group == null )
            {
                throw $this->createNotFoundException('Groupe[id='.$groupId.'] inexistant');
            }

              //on appel le template
            return $this->render('MgnCoreBundle:Acl:aclsForGroup.html.twig', array(
                'group' => $group,
                'forums' => $forums,
            ));
          }
        
      return $this->render('MgnCoreBundle:Acl:acls.html.twig', array(
        'groups' => $groups,
        'users' => $users,
        'forums' => $forums,
      ));
    }

  /**
    * @Secure(roles="IS_AUTHENTICATED_REMEMBERED, ROLE_SUPER_ADMIN")
    */
  public function AclsUserAction($userId, $usernameCanonical, $role, $forumId)
  {
    $groups = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:Group')
                         ->findBy(
                    array(),                 // Pas de critère
                    array(), // On tri par date décroissante
                    NULL,       // On sélectionne $nb_articles_page articles
                    NULL                  // A partir du $offset ième
                );

    $users = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:User')
                         ->findBy(
                    array(),                 // Pas de critère
                    array('username' => 'ASC'), // On tri par date décroissante
                    NULL,       // On sélectionne $nb_articles_page articles
                    NULL                  // A partir du $offset ième
                );

    $forums = $this->getDoctrine()
              ->getManager()
              ->getRepository('MgnForumBundle:Forum')
              ->findAllWithCategories();

        if( $userId != null and $usernameCanonical != null and $role != null and $forumId != null )
    {
      //on récupère les informations sur le groupe
      $user = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('MgnUserBundle:User')
                          ->find($userId);
      
      // Si le topic n'existe pas, on affiche une erreur 404
          if( $user == null )
          {
              throw $this->createNotFoundException('User[id='.$userId.'] inexistant');
          }

          //on récupère les informations sur la categorie
      $forum = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('MgnForumBundle:Forum')
                          ->find($forumId);
      
      // Si le topic n'existe pas, on affiche une erreur 404
          if( $forum == null )
          {
              throw $this->createNotFoundException('Forum[id='.$forumId.'] inexistante');
          }

          $em = $this->getDoctrine()->getManager();

          $role = strtoupper($role);

          if( $role == 'BASE' )
          {
            if( 
              $user->hasRole('ROLE_FORUM_READ_'.$forum->getId()) == true and 
              $user->hasRole('ROLE_FORUM_POST_'.$forum->getId()) == true and 
              $user->hasRole('ROLE_FORUM_TOPIC_'.$forum->getId()) == true 
            )
            {
              $user->removeRole('ROLE_FORUM_READ_'.$forum->getId());
              $user->removeRole('ROLE_FORUM_POST_'.$forum->getId());
              $user->removeRole('ROLE_FORUM_TOPIC_'.$forum->getId());
            }
            else
            {
              if( $user->hasRole('ROLE_FORUM_READ_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_READ_'.$forum->getId());
              }

              if( $user->hasRole('ROLE_FORUM_POST_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_POST_'.$forum->getId());
              }

              if( $user->hasRole('ROLE_FORUM_TOPIC_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_TOPIC_'.$forum->getId());
              }
            }
          }
          elseif( $role == 'MODO' )
          {
            if( 
              $user->hasRole('ROLE_FORUM_LOCK_'.$forum->getId()) == true and 
              $user->hasRole('ROLE_FORUM_POSTIT_'.$forum->getId()) == true and 
              $user->hasRole('ROLE_FORUM_ANNONCE_'.$forum->getId()) == true and 
              $user->hasRole('ROLE_FORUM_MOVE_'.$forum->getId()) == true 
            )
            {
              $user->removeRole('ROLE_FORUM_LOCK_'.$forum->getId());
              $user->removeRole('ROLE_FORUM_POSTIT_'.$forum->getId());
              $user->removeRole('ROLE_FORUM_ANNONCE_'.$forum->getId());
              $user->removeRole('ROLE_FORUM_MOVE_'.$forum->getId());
            }
            else
            {
              if( $user->hasRole('ROLE_FORUM_LOCK_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_LOCK_'.$forum->getId());
              }

              if( $user->hasRole('ROLE_FORUM_POSTIT_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_POSTIT_'.$forum->getId());
              }

              if( $user->hasRole('ROLE_FORUM_ANNONCE_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_ANNONCE_'.$forum->getId());
              }

              if( $user->hasRole('ROLE_FORUM_MOVE_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_MOVE_'.$forum->getId());
              }
            }
          }
          elseif( $role == 'ADMIN' )
          {
            if( 
              $user->hasRole('ROLE_FORUM_EDIT_'.$forum->getId()) == true and 
              $user->hasRole('ROLE_FORUM_DELETE_'.$forum->getId()) == true 
            )
            {
              $user->removeRole('ROLE_FORUM_EDIT_'.$forum->getId());
              $user->removeRole('ROLE_FORUM_DELETE_'.$forum->getId());
            }
            else
            {
              if( $user->hasRole('ROLE_FORUM_EDIT_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_EDIT_'.$forum->getId());
              }

              if( $user->hasRole('ROLE_FORUM_DELETE_'.$forum->getId()) == false )
              {
                $user->addRole('ROLE_FORUM_DELETE_'.$forum->getId());
              }
            }
          }
          else
          {
            if( $user->hasRole('ROLE_FORUM_'.$role.'_'.$forum->getId()) == true )
            {
              $user->removeRole('ROLE_FORUM_'.$role.'_'.$forum->getId());
            }
            else
            {
              $user->addRole('ROLE_FORUM_'.$role.'_'.$forum->getId());
            }
          }

          $em->flush();

          return $this->redirect( $this->generateUrl('mgn_admin_core_acls_forUser', array('userId' => $user->getId(), 'usernameCanonical' => $user->getUsernameCanonical())));
    }
        elseif( $userId != null and $usernameCanonical != null and $role == null and $forumId == null )
        {
          //on récupère les informations sur le groupe
      $user = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('MgnUserBundle:User')
                          ->find($userId);
      
      // Si le topic n'existe pas, on affiche une erreur 404
          if( $user == null )
          {
              throw $this->createNotFoundException('User[id='.$userId.'] inexistant');
          }

            //on appel le template
          return $this->render('MgnCoreBundle:Acl:aclsForUser.html.twig', array(
              'user' => $user,
              'forums' => $forums,
          ));
        }
  }

  /**
    * @Secure(roles="IS_AUTHENTICATED_REMEMBERED, ROLE_SUPER_ADMIN")
    */
  public function AclsForumAction($forumId, $forumSlug, $role)
  {
    //on récupère les informations sur la categorie
    $forum = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('MgnForumBundle:Forum')
                        ->find($forumId);
    
    // Si le topic n'existe pas, on affiche une erreur 404
        if( $forum == null )
        {
            throw $this->createNotFoundException('Categorie[id='.$forumId.'] inexistante');
        }

        $groups = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:Group')
                         ->findAll();

        $users = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('MgnUserBundle:User')
                         ->findAll();

        if( $forumId != null and $forumSlug != null and $role != null )
    {
      //on récupère les informations sur le groupe
      $user = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('MgnUserBundle:User')
                          ->find($userId);
      
      // Si le topic n'existe pas, on affiche une erreur 404
          if( $user == null )
          {
              throw $this->createNotFoundException('User[id='.$userId.'] inexistant');
          }

          $em = $this->getDoctrine()->getManager();

          $role = strtoupper($role);

          if( $user->hasRole('ROLE_FORUM_'.$role.'_'.$forum->getId()) == true )
          {
            $user->removeRole('ROLE_FORUM_'.$role.'_'.$forum->getId());
          }
          else
          {
            $user->addRole('ROLE_FORUM_'.$role.'_'.$forum->getId());
          }

          $em->flush();

          return $this->redirect( $this->generateUrl('mgn_admin_core_acls_forUser', array('userId' => $user->getId(), 'usernameCanonical' => $user->getUsernameCanonical())));
    }
        elseif( $forumId != null and $forumSlug != null and $role == null )
        {
          //on appel le template
          return $this->render('MgnCoreBundle:Acl:aclsForForum.html.twig', array(
              'groups' => $groups,
              'users' => $users,
              'forum' => $forum,
          ));
        }
  }
}
