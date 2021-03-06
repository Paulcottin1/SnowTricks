<?php

namespace App\Controller;

use App\Form\ForgotPasswordType;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

Class UserController extends AbstractController
{

    /**
     * @Route("/register", name="register", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $password);
            $user->setRole('ROLE_USER')
                ->setAvatar('default-avatar.png')
                ->setPassword($encoded)
                ->setCreatedDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Votre compte a bien été créé !'
            );
            return $this->redirectToRoute('home');
        }
        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout() {}


    /**
     * @Route("/profil/{slug}", name="profil", methods={"GET","POST"})
     * @param Request $request
     * @param User $user
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function profile(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $oldPassword = $user->getPassword();
        $oldAvatar = $user->getAvatar();
        $form = $this->createForm(ProfileType::class, $user, array('user' => $user));
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $formAvatar = $form->get('avatar')->getData();
            $formPassword = $form->get('password')->getData();

            if($formAvatar !== $oldAvatar) {
                $path = $this->getParameter('images_directory');
                $avatarName = $formAvatar->getClientOriginalName();
                $formAvatar->move($path, $avatarName);
                $user->setAvatar($avatarName);

                if($user->getAvatar() !== 'default-avatar.png') {
                    unlink($path . $oldAvatar);
                }
            }

            if($formPassword !== $oldPassword) {
                $encoded = $encoder->encodePassword($user, $formPassword);
                $user->setPassword($encoded);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Votre compte a été mis à jour'
            );
            return $this->redirectToRoute('profil', ['slug' => $user->getSlug()]);
        }

        return $this->render('user/profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route ("/mot-de-passe-oublie", name="forgot_password", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepository $userRepository
     * @param MailerInterface $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function forgotPassword(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository, MailerInterface $mailer)
    {
        $user = new User;
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if($user === null) {
                $this->addFlash('warning', 'Aucun compte lié à cette adresse e-mail');
                $this->redirectToRoute('forgot_password');
            } else {
                $randomPassword = sha1(random_bytes(10));
                $email = (new Email())
                    ->from('snowtricks@contact.fr')
                    ->to($user->getEmail())
                    ->subject('Mot de passe oublié')
                    ->html(
                        $this->renderView(
                            'email/forgot.html.twig',
                            array( 'user' => $user, 'password' => $randomPassword)
                        ),
                        'text/html'
                    );
                $mailer->send($email);

                $encoded = $encoder->encodePassword($user, $randomPassword);
                $user->setPassword($encoded);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('notice', 'Un nouveau mot de passe vous a été envoyé sur votre boite mail.');
                $this->redirectToRoute('forgot_password');
            }
        }

        return $this->render('user/forgot-password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
