<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product2;
use App\Form\ProductType;
class ProductController extends AbstractController
{
    #[Route('/product', name: 'product')]
    /**
* @Route("/product", name="ListProd")
*/
    public function index(): Response
    {
        $repo=$this->getDoctrine()->getRepository(Product2::class);
        $products=$repo->findAll();
        #$products=["article 1","article 2","article 3"];
        return $this->render('product/index.html.twig', ['products' => $products]);
        
    }
    /**
* @Route("/product/add", name="add")
*/
    public function add(): Response
    {
        $manager=$this->getDoctrine()->getManager();
        $product = new Product2();
        $product->setLib("lib test")
            ->setPU(500)
            ->setDescription("test description de l'article ")
            ->setImage("http://placehold.it/350*150");
        $manager->persist($product);
        $manager->flush();
        return new Response ("ajout valide".$product->getId());
        
    }
    /**
* @Route("/product/detail/{id}", name="detail")
*/
public function detail($id): Response
{
    $repo=$this->getDoctrine()->getRepository(Product2::class);
    $product =$repo->find($id);
    return $this->render('product/detail.html.twig', ['product' => $product]);
}
 /**
* @Route("/product/delete/{id}", name="delete")
*/
public function delete($id): Response
{
    $repo=$this->getDoctrine()->getRepository(Product2::class);
    $product =$repo->find($id);
    $manager=$this->getDoctrine()->getManager();
    $manager->remove($product); 
    $manager->flush();
    #return $this->render('product/index.html.twig', ['product' => $product]);
    return new Response("suppresion valide");
}
/**
* @Route("/product/add2", name="add2")
*/
public function new(Request $request): Response
    {
        $prod = new Product2();
        // ...

        $form = $this->createForm(ProductType::class, $prod);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            #$task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prod);
            $entityManager->flush();

            return $this->redirectToRoute('ListProd');
        }
        return $this->renderForm('product/new.html.twig', [
            'formpro' => $form,
        ]);
    }
}
