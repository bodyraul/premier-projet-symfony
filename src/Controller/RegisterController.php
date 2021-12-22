<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request,EntityManagerInterface $doctrine,UserPasswordHasherInterface $encodage) : response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            
            $passworld =$encodage->hashPassword($user, $user->getPassword());
            $user->setPassword($passworld);

            $doctrine->persist($user);
            $doctrine->flush();
            
        }

        return $this->render('register/index.html.twig',[
            'form' => $form ->createView()
        ]);
    }
}
