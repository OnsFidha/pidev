<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use App\Security\EmailVerifier;
use App\Form\RegisterType;
use App\Form\ProfileEditType;
use App\Form\User\EditProfileType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Vich\UploaderBundle\FileAbstraction\ReplacingFile;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\VarDumper\Cloner\Data;
use Doctrine\Persistence\ManagerRegistry;



class UserController extends AbstractController
{

    private $emailVerifier;
    /**
     * @var Security
     */
    private $security;
    public function __construct(EmailVerifier $emailVerifier, Security $security, EntityManagerInterface $entityManager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile()
    {
        return $this->render('user/profile.html.twig');
    }
    
    // #[Route('/{id}/edit-profile', name: 'app_edit_profile')]
    // public function editProfile(Request $request, User $user): Response
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $form = $this->createForm(ProfileEditType::class, $user);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // $file stores the uploaded image file.
    //         /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
    //         $file = $form['image']->getData();
           
    //         if ($file != null) {
    //             // The user selected a new image.
    //             $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
    //             // Move the file to the directory where brochures are stored
    //             try {
    //                 $file->move(
    //                     $file->move($this->getParameter('images_directory'),$file));
    //             } catch (FileException $e) {
    //             }
    //             // Update the image property to store the image file name instead of its contents.
    //             $user->setImage($fileName);
    //         }
    //         $em->persist($user);
    //         $em->flush();
    //         return $this->redirectToRoute('app_profile', [
    //             'id' => $user->getId(),
    //         ]);
    //     }
    //     return $this->render('user/profileEdit.html.twig', [
    //         'user' => $user,
    //         'form' => $form->createView()

    //     ]);
    // }

    // #[Route('/{id}/edit-profile', name: 'app_edit_profile', methods: ['GET', 'POST'])]    // #[Route('/{id}/edit-profile', name: 'app_edit_profile')]
    // public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(ProfileEditType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('user/profileEdit.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }
    // 
    #[Route('/edit-profile/{id}', name: 'app_edit_profile', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        // Créer le formulaire de modification avec l'utilisateur actuel
        $form = $this->createForm(ProfileEditType::class, $user);
    
        // Traiter la soumission du formulaire
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $profileEditData = $request->request->get('profile_edit');
            
            if (isset($profileEditData['name'])) {
                // Set user name
                $user->setName($profileEditData['name']);
            }
            if (isset($profileEditData['image'])) {
                // Set user image
                $user->setImage($profileEditData['image']);
            }
            if (isset($profileEditData['prename'])) {
                // Set user prename
                $user->setPrename($profileEditData['prename']);
            }
            if (isset($profileEditData['phone'])) {
                // Set user prename
                $user->setPhone($profileEditData['phone']);
            } 
            if (isset($profileEditData['email'])) {
                // Set user setEmail
                $user->setEmail($profileEditData['email']);
            } 
            // if (isset($profileEditData['birthday'])) {
            //     // Set user birthday
            //      $user->setBirthday($profileEditData['birthday']);
            //  }
            if (isset($profileEditData['birthday'])) {
                $birthday = DateTime::createFromFormat('Y-m-d', $profileEditData['birthday']);
                if ($birthday !== false) {
                    try {
                        $user->setBirthday($birthday);
                    } catch (\Exception $e) {
                        echo "Erreur lors de la définition de la date de naissance de l'utilisateur : " . $e->getMessage();
                    }
                } else {
                    echo "Format de date invalide.";
                }
            }
            
            
            $this->entityManager->flush();
            
            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }
    
        // Afficher le formulaire de modification
        return $this->renderForm('user/profileEdit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    



    

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager, GuardAuthenticatorHandler $guardHandler): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                // hashage
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $randomImageNumber = rand(1, 20);
            $randomImageFilename = $randomImageNumber . '.png';
            $user->setImage($randomImageFilename);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@security-demo.com', 'Security'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('security/register-done.html.twig')
            );
            return $guardHandler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'app_login');
        }
        return $this->render('security/register.html.twig', ['form' => $form->createView(),]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }
        $this->addFlash('success', 'Your email address has been verified.');
        return $this->redirectToRoute('app_homepage');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route('/userdelete/{id}', name: 'userdelete')]
        public function userdelete(Request $request, $id, ManagerRegistry $manager, UserRepository $UserRepository): Response
        {
            $em = $manager->getManager();
            $user= $UserRepository->find($id);
    
            $em->remove($user);
            $em->flush();
    
            return $this->redirectToRoute('app_login');
        }
}
