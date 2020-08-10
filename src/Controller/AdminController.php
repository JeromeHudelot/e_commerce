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
	use App\Entity\Categorie;
	use App\Entity\Product;
	use App\Entity\Commande;
	
	
	class AdminController extends AbstractController
	{
		/**
		 * @Route("/admin/", name="admin.index", methods={"GET"})
		 * @return Response
		 */
		public function indexAction(PaginatorInterface $paginator, Request $request){
			
			$cats = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
			
			$products = $paginator->paginate(
					$this->getDoctrine()->getRepository(Product::class)->findAllPaginate(),
					$request->query->getInt('page', 1),
					15);
					
			return $this->render('Admin/index.html.twig', ['cats' => $cats, 'current' => 'index', 'products' => $products]);
			
		}
		
		/**
		 * @Route("/admin/categories", name="admin.categories")
		 * @return Response
		 */
		public function categoriesAction(PaginatorInterface $paginator, Request $request){
			
			$cats = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
					
			return $this->render('Admin/categories.html.twig', ['cats' => $cats, 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/categories/show/{id}", name="admin.categorie.show")
		 * @return Response
		 */
		public function categoriesShowAction(PaginatorInterface $paginator, Request $request, $id){
			
			$cat = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
			
			
			$products = $paginator->paginate(
					$this->getDoctrine()->getRepository(Product::class)->findByProductIdPaginate($id),
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
				
				$this->getDoctrine->getManager()->persist($cat);
				$this->getDoctrine()->getManager()->flush();
			$this->addFlash('success', 'Catégorie créée avec succè');
				
			}
					
			return $this->render('Admin/categorie.new.html.twig', ['form' => $form->createView(), 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/categories/edit/{id}", name="admin.categorie.edit")
		 * @return Response
		 */
		public function categorieEditAction(Request $request, $id){
			
			$cat = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
			
			$form = $this->createForm(CategorieType::class, $cat);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				
				$this->getDoctrine()->getManager()->flush();
				$this->addFlash('success', 'Catégorie édité avec succè');
				
			}
					
			return $this->render('Admin/categorie.edit.html.twig', ['form' => $form->createView(), 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/categorie/delete/{id}", name="admin.categorie.delete")
		 * @return Response
		 */
		public function categorieDeleteAction(Request $request, $id){
			
			$cat = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
			$cats = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
			$this->getDoctrine->getManager()->remove($cat);
			$this->getDoctrine()->getManager()->flush();
			$this->addFlash('success', 'Catégorie supprimé avec succè');
			return $this->render('Admin/categories.html.twig', ['cats' => $cats, 'current' => 'admin.categories']);
			
		}
		
		/**
		 * @Route("/admin/produits", name="admin.products")
		 * @return Response
		 */
		public function productAction(PaginatorInterface $paginator, Request $request){
			
			$products = $paginator->paginate(
					$this->getDoctrine()->getRepository(Product::class)->findAllPaginate(),
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
				$this->getDoctrine()->getManager()->persist($product);
				$this->getDoctrine()->getManager()->flush();
				$this->addFlash('success', 'Produit créer avec succè');
				
			}
					
			return $this->render('Admin/product.new.html.twig', ['form' => $form->createView(), 'current' => 'admin.products']);
			
		}
		
		/**
		 * @Route("/admin/product/edit/{id}", name="admin.product.edit")
		 * @return Response
		 */
		public function productEditAction(Request $request, $id){
			
			$product = $this->getDoctrine()->getRepository(Product::class)->find($id);
			
			$form = $this->createForm(ProductType::class, $product);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				
				$this->getDoctrine()->getManager()->flush();
				$this->addFlash('success', 'Produit édité avec succè');
				
			}
					
			return $this->render('Admin/product.edit.html.twig', ['form' => $form->createView(), 'current' => 'admin.products']);
			
		}
		
		/**
		 * @Route("/admin/product/delte/{id}", name="admin.product.delete")
		 * @return Response
		 */
		public function productDeleteAction(PaginatorInterface $paginator, Request $request, $id){
			
			$product = $this->getDoctrine()->getRepository(Product::class)->find($id);
			$products = $this->getDoctrine()->getRepository(Product::class)->findAll();
			$this->getDoctrine()->getManager()->remove($product);
			$this->getDoctrine()->getManager()->flush();
				$this->addFlash('success', 'Produit supprimé avec succè');
			return $this->render('Admin/product.html.twig', ['products' => $products, 'current' => 'admin.products']);
			
		}
		
		/**
		 * @Route("/admin/commandes", name="admin.commandes")
		 * @return Response
		 */
		public function commandesAction(PaginatorInterface $paginator, Request $request){
			
			$commandes = $paginator->paginate(
					$this->getDoctrine()->getRepository(Commande::class)->findAllPaginate(),
					$request->query->getInt('page', 1),
					30);
					
			return $this->render('Admin/commandes.html.twig', ['current' => 'admin.commandes', 'commandes' => $commandes]);
			
		}
		
		/**
		 * @Route("/admin/commande/show/{id}", name="admin.commande.show")
		 * @return Response
		 */
		public function commandeShowAction(Request $request, $id){
			
			$commande = $this->getDoctrine()->getRepository(Commande::class)->find($id);
			
			$form = $this->createForm(CommandeType::class, $commande);
			
			$form->handleRequest($request);
			
			if($form->isSubmitted() && $form->isValid())
			{
				
				$this->getDoctrine()->getManager()->flush();
				$this->addFlash('success', 'Commande validé avec succès');
				
			}
					
			return $this->render('Admin/commande.show.html.twig', ['form' => $form->createView(), 'current' => 'admin.commandes', 'commande' => $commande]);
			
		}
		
	}