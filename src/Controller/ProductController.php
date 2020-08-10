<?php

	namespace App\Controller;
	
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Knp\Component\Pager\PaginatorInterface;
	use App\Entity\Categorie;
	use App\Entity\Product;
	
	
	class ProductController extends AbstractController
	{
		
		/**
		 * @Route("/product/{slug?}/{id?}", name="product_list", requirements={"slug" : "[a-zA-Z0-9\-]*", "id" : "\d+"})
		 * @return Response
		 */
		public function indexAction(PaginatorInterface $paginator, Request $request, $slug = null, $id = null) : ?Response
		{
			
			$cats = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
			
			if($id !== null and $slug !== null){
				
				$products = $paginator->paginate(
					$this->getDoctrine()->getRepository(Product::class)->findByProductIdPaginate($id),
					$request->query->getInt('page', 1),
					15);
				
			}
			else{
				
				$products = $paginator->paginate(
					$this->getDoctrine()->getRepository(Product::class)->findAllPaginate(),
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
			
			$cats = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
			
			$product = $this->getDoctrine()->getRepository(Product::class)->find($id);
			
			return $this->render('Product/show.html.twig', ['cats' => $cats, 'current' => 'product', 'product' => $product]);
			
			
		}
	}