<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

// class RegistrationController extends AbstractController
// {
//     /**
//      * @Route("/register", name="app_register")
//      */
//     public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator): Response
//     {
//         $user = new Users();
//         $form = $this->createForm(RegistrationFormType::class, $user);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             // encode the plain password
//             $user->setPassword(
//                 $passwordEncoder->encodePassword(
//                     $user,
//                     $form->get('plainPassword')->getData()
//                 )
//             );

//             $entityManager = $this->getDoctrine()->getManager();
//             $entityManager->persist($user);
//             $entityManager->flush();
//             // do anything else you need here, like send an email

//             return $guardHandler->authenticateUserAndHandleSuccess(
//                 $user,
//                 $request,
//                 $authenticator,
//                 'main' // firewall name in security.yaml
//             );
//         }

//         return $this->render('registration/register.html.twig', [
//             'registrationForm' => $form->createView(),
//         ]);
//     }
// }

class RegistrationController extends AbstractController
{
    private $verifyEmailHelper;
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(): Response
    {
        $user = new User();

        // handle the user registration form and persist the new user...

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            $user->getId(),
            $user->getEmail()
        );

        $email = new TemplatedEmail();
        $email->from('send@example.com');
        $email->to($user->getEmail());
        $email->htmlTemplate('registration/confirmation_email.html.twig');
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

        $this->mailer->send($email);

        // generate and return a response for the browser
    }
}
