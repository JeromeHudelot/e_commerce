<?php

	namespace App\Controller;
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Knp\Component\Pager\PaginatorInterface;
	use App\Repository\CategorieRepository;
	use App\Repository\ProductRepository;
	use App\Entity\Categorie;
	use App\Entity\Product;
	
	
	class ProductController extends AbstractController
	{
		
		private $categorieRepository;
		private $productRepository;
		
		public function __construct(CategorieRepository $cat, ProductRepository $product){
			
			$this->categorieRepository = $cat;
			$this->productRepository = $product;
			
		}
		
		/**
		 * @Route("/product/{slug?}/{id?}", name="product_list", requirements={"slug" : "[a-zA-Z0-9\-]*", "id" : "\d+"})
		 * @return Response
		 */
		public function indexAction(PaginatorInterface $paginator, Request $request, $slug = null, $id = null) : ?Response
		{
			
			$cats = $this->categorieRepository->findAll();
			
			if($id !== null and $slug !== null){
				
				$products = $paginator->paginate(
					$this->productRepository->findByProductIdPaginate($id),
					$request->query->getInt('page', 1),
					15);
				
			}
			else{
				
				$products = $paginator->paginate(
					$this->productRepository->findAllPaginate(),
					$request->query->getInt('page', 1),
					15);
				
			}
			
			return $this->render('Product/list.html.twig', ['cats' => $cats, 'current' => 'product', 'products' => $products]);
			
		}
		
		/**
		 * @Route("/product/show/{slug}/{id}", name="product_show", requirements={"slug" : "[a-zA-Z0-9\-]*", "id" : "\d+"})
		 * @return Response
		 */
		public function productShowAction(Request $request, $slug, $id) : ?Response
		{
			
			$cats = $this->categorieRepository->findAll();
			
			$product = $this->productRepository->find($id);
			
			return $this->render('Product/show.html.twig', ['cats' => $cats, 'current' => 'product', 'product' => $product]);
			
			
		}
	}