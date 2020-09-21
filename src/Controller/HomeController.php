<?php

	namespace App\Controller;
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Session\Session;
	use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
	use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
	use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\HttpFoundation\Cookie;
	use App\Repository\CategorieRepository;
	use App\Repository\ProductRepository;
	use App\Form\ClientType;
	use App\Entity\Categorie;
	use App\Entity\Product;
	use App\Entity\Commande;
	use App\Entity\Client;
	use App\Entity\CommandeProduct;
	
	
	
	class HomeController extends AbstractController
	{
		
		private $categorieRepository;
		private $productRepository;
		private $em;
		
		public function __construct(CategorieRepository $cat, ProductRepository $product, EntityManagerInterface $em){
			
			$this->categorieRepository = $cat;
			$this->productRepository = $product;
			$this->em = $em;
			
		}
		
		/**
		 * @Route("/", name="home")
		 * @return Response
		 */
		public function indexAction(Request $request) : Response
		{
			
			$cats = $this->categorieRepository->findAll();
			
			return $this->render('home.html.twig', ['cats' => $cats, 'current' => 'home']);
			
		}
		
		/**
		 * @Route("/panier", name="panier")
		 * @return Response
		 */
		public function panierAction(Request $request) : Response
		{
			
			$cats = $this->categorieRepository->findAll();
			
			$client = new Client();
			
			$form = $this->createForm(ClientType::class, $client);
			$response = new Response(
				'Content',
				Response::HTTP_OK,
				['content-type' => 'text/html']
			);
			$response->setContent($this->renderView('panier.html.twig', ['cats' => $cats, 'current' => 'panier', 'form' => $form->createView()]));
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				$session = new Session();
				$commande = $session->get('commande');
				$client->setCommande($commande);
				$this->em->merge($client);
				$this->em->flush();
				
				$response->setContent($this->renderView('paiement.html.twig', ['cats' => $cats, 'current' => 'panier']));
				
			}
			
			return $response;
			
		}
		
		/**
		 * @Route("/information", name="information")
		 * @return Response
		 */
		public function informationAction(Request $request) : Response
		{
			
			$cats = $this->categorieRepository->findAll();
			
			$commande_json  = json_decode(stripslashes($request->request->get('choices')[0]));
			$commande = new Commande();
			
			foreach($commande_json as $key => $val){
				
				$product = $this->productRepository->find($val->id);
				
				if($product->getPrice() == $val->price && $product->getWeight() == $val->weight){
					
					if($val->qt <= $product->getStock()){
						$commandeProduct = new CommandeProduct();
						$commande->addCommandeProduct($commandeProduct);
						$product->addCommandeProduct($commandeProduct);
						$commandeProduct->setQuantity($val->qt);
						$commande->setPrice($commande->getPrice() + $val->price * $val->qt);
						$commande->setWeight($commande->getWeight() + $val->weight * $val->qt);
						$commande->setNbElement($commande->getNbElement() + $val->qt);
					
						$this->em->persist($commandeProduct);
					}
					else{
						
						$response = new Response();
						$response->setContent(json_encode([
							'data' => 3,
							'product' => $product->getName(),
						]));
						$response->headers->set('Content-Type', 'application/json');
			
						return $response;
						
					}
					$session = new Session();
					$session->set('commande', $commande);
					$this->em->persist($commande);
					$this->em->flush();
				}
				else{
						
					$response = new Response();
					$response->setContent(json_encode([
						'data' => 2,
					]));
					$response->headers->set('Content-Type', 'application/json');
		
					return $response;
					
				}
				
			}
						
			$response = new Response();
			$response->setContent(json_encode([
				'data' => 1,
			]));
			$response->headers->set('Content-Type', 'application/json');
			
			return $response;
		}
		
		/**
		 * @Route("/contact", name="contact")
		 * @return Response
		 */
		public function contactAction(){
			
			
			
		}
		
		/**
		 * @Route("/paiement", name="paiement")
		 * @return Response
		 */
		public function paiementAction(Request $request){
			
			$session = new Session();
			$commande = $session->get('commande');
			
			foreach($commande->getCommandeProducts() as $key => $elem){
				
				$elem->getProduct()->setStock($elem->getStock() - $elem->getQuantity());
				
			}
			$response->headers->clearCookie('inCartItemsNum');
			$response->headers->clearCookie('cartArticles');
			
		}
		
		
	}
	
	
	