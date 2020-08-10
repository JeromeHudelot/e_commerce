<?php

	namespace App\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
	use App\Entity\Categorie;

	class UserController extends AbstractController
	{
		/**
		 * @Route("/login", name="app_login")
		 */
		public function login(AuthenticationUtils $authenticationUtils): Response
		{
			// if ($this->getUser()) {
			//     return $this->redirectToRoute('target_path');
			// }

			$cats = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
			// get the login error if there is one
			$error = $authenticationUtils->getLastAuthenticationError();
			// last username entered by the user
			$lastUsername = $authenticationUtils->getLastUsername();

			return $this->render('security/login.html.twig', ['current' => 'login', 'cats' => $cats, 'last_username' => $lastUsername, 'error' => $error]);
		}

		/**
		 * @Route("/logout", name="app_logout")
		 */
		public function logout()
		{
			throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
		}
	}
