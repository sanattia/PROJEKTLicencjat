<?php
/**
 * Reset Password controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\HomeType;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @Route("/reset-password")
 */
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;

    /**
     * BookController constructor.
     *
     * @param ResetPasswordHelperInterface $resetPasswordHelper ResetPasswordHelper
     */
    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    /**
     * Display & process form to request a password reset.
     *
     * @Route("", name="app_forgot_password_request")
     *
     * @param Request         $request
     * @param MailerInterface $mailer
     *
     * @return Response
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(), 'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     *
     * @Route("/check-email", name="app_check_email")
     *
     * @param Request         $request
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     */
    public function checkEmail(Request $request): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken, 'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     *
     * @Route("/reset/{token}", name="app_reset_password")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param string|null                  $token
     *
     * @return Response
     */
    public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
    {
        $searchForm = $this->createForm(HomeType::class);
        $searchForm->handleRequest($request);

        if ($token) {
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->resetPasswordHelper->removeResetRequest($token);

            $encodedPassword = $passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('home');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(), 'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * Returns an Password Reset Email
     * @param string          $emailFormData
     * @param MailerInterface $mailer
     *
     * @return RedirectResponse
     *
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            return $this->redirectToRoute('app_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('agsymmail@gmail.com', 'Apka Mail Bot'))
            ->to($user->getEmail())
            ->subject('Reset')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
}
