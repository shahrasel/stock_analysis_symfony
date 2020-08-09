<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new")
     */
    public function new(Request $request, EntityManagerInterface $entity, UserPasswordEncoderInterface $encoder)
    {
        if($request->isMethod('POST')) {
            $token = $request->request->get('_csrf_token');
            $csrf_token = new CsrfToken('authenticate', $token);
            if($this->get('security.csrf.token_manager')->isTokenValid($csrf_token)) {
                //dd($request->request);
                $user = new User();
                $user->setFirstname($request->request->get('firstname'));
                $user->setLastname($request->request->get('lastname'));
                $user->setEmail($request->request->get('email'));
                $user->setUsertype($request->request->get('user_type'));
                $user->setStatus($request->request->get('status'));
                $user->setBalance('100000');
                $user->setRoles($request->request->get('role'));
                $user->setCreatedAt(new \DateTime());
                $user->setUpdatedAt(new \DateTime());

                if(!empty($request->request->get('password'))) {
                    $encoded = $encoder->encodePassword($user, $request->request->get('password'));
                    $user->setPassword($encoded);
                }

                $entity->persist($user);

                $entity->flush();

                return $this->redirectToRoute('app_login');
            }
            else {
                dd('exit;');
            }
        }

        return $this->render('user/new.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="admin_user_edit")
     */
    public function edit($id, Request $request, EntityManagerInterface $entity, UserPasswordEncoderInterface $encoder)
    {
        $user = $entity->getRepository(User::class)->find($id);
        if($request->isMethod('POST')) {
            $token = $request->request->get('_csrf_token');
            $csrf_token = new CsrfToken('authenticate', $token);
            if($this->get('security.csrf.token_manager')->isTokenValid($csrf_token)) {
                //dd($request->request);
                $user->setFirstname($request->request->get('firstname'));
                $user->setLastname($request->request->get('lastname'));
                $user->setEmail($request->request->get('email'));
                $user->setUsertype($request->request->get('user_type'));
                $user->setStatus($request->request->get('status'));
                $user->setRoles($request->request->get('role'));
                $user->setUpdatedAt(new \DateTime());

                if(!empty($request->request->get('password'))) {
                    $encoded = $encoder->encodePassword($user, $request->request->get('password'));
                    $user->setPassword($encoded);
                }

                $entity->persist($user);

                $entity->flush();
            }
            else {
                dd('exit;');
            }
        }
        //dd($user);
        return $this->render('user/edit.html.twig', [
            'controller_name' => 'UserController',
            'user_info'=>$user,
        ]);
    }

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function dashboard()
    {


        return $this->render('admin/user/dashboard.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

}
