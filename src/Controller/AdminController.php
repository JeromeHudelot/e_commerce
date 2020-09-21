<?php

	namespace App\Controller;
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Knp\Component\Pager\PaginatorInterface;
	use App\Form\CategorieType;
	use App\Form\CommandeType;
	use App\Form\ProductType;
	use App\Repository\CommandeRepository;
	use App\Repository\CategorieRepository;
	use App\Repository\ProductRepository;
	use App\Entity\Categorie;
	use App\Entity\Product;
	use App\Entity\Commande;
	
	
	class AdminController extends AbstractController
	{
		
		private $commandeRepository;
		private $categorieRepository;
		private $productRepository;
		
		public function __construct(CommandeRepository $commande, CategorieRepository $cat, ProductRepository $product, EntityManagerInterface $em){
			
			$this->commandeRepository = $commande;
			$this->categorieRepository = $cat;
			$this->productRepository = $product;
			$this->em = $em;
			
		}
		/**
		 * @Route("/admin/", name="admin.index", methods={"GET"})
		 * @return Response
		 */
		public function indexAction(PaginatorInterface $paginator, Request $request){
			
			$commandes = $paginator->paginate(
					$this->commandeRepository->findAllPaginate(),
					$request->query->getInt('page', 1),
					15);
					
			return $this->render('Admin/index.html.twig', ['current' => 'index', 'commandes' => $commandes]);
			
		}
		
		/**
		 * @Route("/admin/categories", name="admin.categories")
		 * @return Response
		 */
		public function categoriesAction(PaginatorInterface $paginator, Request $request){
			
			$cats = $this->categorieRepository->findAll();
					
			return $this->render('Admin/categories.html.twig', ['cats' => $cats, 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/categories/show/{id}", name="admin.categorie.show")
		 * @return Response
		 */
		public function categoriesShowAction(PaginatorInterface $paginator, Request $request, $id){
			
			$cat = $this->categorieRepository->find($id);
			
			
			$products = $paginator->paginate(
					$this->productRepository->findByProductIdPaginate($id),
					$request->query->getInt('page', 1),
					30);			
					
			return $this->render('Admin/categorie.show.html.twig', ['cat' => $cat, 'products' => $products, 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/categories/new", name="admin.categorie.new")
		 * @return Response
		 */
		public function categorieNewAction(Request $request){
			
			$cat = new Categorie();
			
			$form = $this->createForm(CategorieType::class, $cat);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				
				$this->em->persist($cat);
				$this->em->flush();
				$this->addFlash('success', 'Catégorie créée avec succè');
				
			}
					
			return $this->render('Admin/categorie.new.html.twig', ['form' => $form->createView(), 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/categories/edit/{id}", name="admin.categorie.edit")
		 * @return Response
		 */
		public function categorieEditAction(Request $request, $id){
			
			$cat = $this->categorieRepository->find($id);
			
			$form = $this->createForm(CategorieType::class, $cat);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				
				$this->em->flush();
				$this->addFlash('success', 'Catégorie édité avec succè');
				
			}
					
			return $this->render('Admin/categorie.edit.html.twig', ['form' => $form->createView(), 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/categorie/delete/{id}", name="admin.categorie.delete")
		 * @return Response
		 */
		public function categorieDeleteAction(Request $request, $id){
			
			$cat = $this->categorieRepository->find($id);
			$this->em->remove($cat);
			$this->em->flush();
			$cats = $this->categorieRepository->findAll();
			$this->addFlash('success', 'Catégorie supprimé avec succè');
			return $this->render('Admin/categories.html.twig', ['cats' => $cats, 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/produits", name="admin.products")
		 * @return Response
		 */
		public function productAction(PaginatorInterface $paginator, Request $request){
			
			$products = $paginator->paginate(
					$this->productRepository->findAllPaginate(),
					$request->query->getInt('page', 1),
					30);
					
			return $this->render('Admin/products.html.twig', ['current' => 'admin.products', 'products' => $products]);
			
		}
		
		/**
		 * @Route("/admin/product/new", name="admin.product.new")
		 * @return Response
		 */
		public function productNewAction(Request $request, $id){
			
			$product = new Product();
			
			$form = $this->createForm(ProductType::class, $product);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				$this->em->persist($product);
				$this->em->flush();
				$this->addFlash('success', 'Produit créer avec succè');
				
			}
					
			return $this->render('Admin/product.new.html.twig', ['form' => $form->createView(), 'current' => 'admin.products']);
			
		}
		
		/**
		 * @Route("/admin/product/edit/{id}", name="admin.product.edit")
		 * @return Response
		 */
		public function productEditAction(Request $request, $id){
			
			$product = $this->productRepository->find($id);
			
			$form = $this->createForm(ProductType::class, $product);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				
				$this->em->flush();
				$this->addFlash('success', 'Produit édité avec succè');
				
			}
					
			return $this->render('Admin/product.edit.html.twig', ['form' => $form->createView(), 'current' => 'admin.products']);
			
		}
		
		/**
		 * @Route("/admin/product/delte/{id}", name="admin.product.delete")
		 * @return Response
		 */
		public function productDeleteAction(PaginatorInterface $paginator, Request $request, $id){
			
			$product = $this->productRepository->find($id);
			$this->em->remove($product);
			$this->em->flush();
			$products = $this->productRepository->findAll();
			$this->addFlash('success', 'Produit supprimé avec succè');
			return $this->render('Admin/product.html.twig', ['products' => $products, 'current' => 'admin.products']);
			
		}
		
		/**
		 * @Route("/admin/commandes", name="admin.commandes")
		 * @return Response
		 */
		public function commandesAction(PaginatorInterface $paginator, Request $request){
			
			$commandes = $paginator->paginate(
					$this->commandeRepository->findAllPaginate(),
					$request->query->getInt('page', 1),
					30);
					
			return $this->render('Admin/commandes.html.twig', ['current' => 'admin.commandes', 'commandes' => $commandes]);
			
		}
		
		/**
		 * @Route("/admin/commande/show/{id}", name="admin.commande.show")
		 * @return Response
		 */
		public function commandeShowAction(Request $request, $id){
			
			$commande = $this->commandeRepository->find($id);
			
			$form = $this->createForm(CommandeType::class, $commande);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				
				$this->em->flush();
				$this->addFlash('success', 'Commande validé avec succès');
				
			}
					
			return $this->render('Admin/commande.show.html.twig', ['form' => $form->createView(), 'current' => 'admin.commandes', 'commande' => $commande]);
			
		}
		
	}