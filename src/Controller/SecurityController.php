<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function forgetPassword(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, string $hash = null): Response
    {
        dump($hash);
        $error = null;
        $success = null;

        if ($hash)
        {
            $form = null;

            $user = $em->getRepository(User::class)->findOneBy(['forgetPasswordHash' => $hash]);

            if ($user)
            {
                $form = $this->createForm(ResetPasswordFormType::class, $user);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    // encode the plain password
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );

//                    $user->removeForgetPasswordHash();
                    $em->persist($user);
                    $em->flush();

                    return $this->redirectToRoute('index');
                }
                $form = $form->createView();
            }
            else
            {
                $error = 'Niepoprawny link resetujący!';
            }

            return $this->render('security/reset-password.html.twig', [
                'reset_form' => $form,
                'error' => $error,
                'success' => $success
            ]);
        }
        else
        {
            $form = $this->createFormBuilder()
                ->add('email', EmailType::class, [
                    'required' => true,
                    'label'=> false
                ])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $email = $form->get('email')->getData();
                $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($user)
                {
                    $user->setForgetPasswordHash();
                    $hash = $user->getForgetPasswordHash();
                    $success = 'Poprawnie wysłano e-mail z linkiem do zresetowania hasła';
                    dump($hash);
                    $em->persist($user);
                    $em->flush();
                    // TODO: Email z linkiem do resetu
                }
                else
                {
                    $error = 'Nie znaleziono użytkownika z takim adresem e-mail!';
                }
            }

            return $this->render('security/forget_password.html.twig', [
                'forget_form' => $form->createView(),
                'error' => $error,
                'success' => $success
            ]);
        }
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}