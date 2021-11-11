<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\updateForm;

use App\Entity\User;
class UserController extends AbstractController
{
    #[Route('/user', name: 'user_page')]
    public function index(): Response
    {
        $repo=$this->getDoctrine()->getRepository(User::class);
        $users=$repo->findAll();
        return $this->render('user/index.html.twig', ['User' => $users]);
    }
    #[Route('/register', name: 'register')]
    public function RegisterPage(): Response
    {
        return $this->render('user/register.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
     /**
* @Route("/user/detail/{id}", name="detailuser")
*/
public function detail($id): Response
{
    $repo=$this->getDoctrine()->getRepository(User::class);
    $user =$repo->find($id);
    return $this->render('user/detail.html.twig', ['user' => $user]);
} /**
* @Route("/user/delete/{id}", name="deleteuser")
*/
public function delete($id): Response
{
    $repo=$this->getDoctrine()->getRepository(User::class);
    $user =$repo->find($id);
    $manager=$this->getDoctrine()->getManager();
    $manager->remove($user); 
    $manager->flush();
    #return $this->render('product/index.html.twig', ['product' => $product]);
    return new Response("suppresion valide");
}
/**
* @Route("/user/update/{id}", name="updateuser")
*/

public function edit(User $User,Request $request,$id): Response
    {
        $form = $this->createForm(updateForm::class, $User);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original $task variable has also been updated
            $User = $form->getData();


            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($User); 
            $entityManager->flush();
            $repo=$this->getDoctrine()->getRepository(User::class);
            $User=$repo->find($id);
            return $this->render('user/detail.html.twig', [
                'user' => $User,
            ]);
        }
        return $this->renderForm('user/update.html.twig', [
            'formpro' => $form,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
