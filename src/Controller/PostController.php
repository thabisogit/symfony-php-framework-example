<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/post", name="post.")
     */
    
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     
     */
    public function index(PostRepository $postRepository)
    {
        $posts =$postRepository->findAll();
        return $this->render('post/index.html.twig', ['welcom'=>'Thabiso Ngubane','posts'=>$posts]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request){
        $post = new Post();

        $post->setTitle('Save testing');

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        return new Response('Post created');

    }
    
    
    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(PostRepository $postRepository,$id){
       $posts =$postRepository->find($id);

       return $this->render('post/show.html.twig', ['welcom'=>'Details','posts'=>$posts]);

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(PostRepository $postRepository,$id){
        $post =$postRepository->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
		$entityManager->flush();
        return $this->redirectToRoute('post.index');


 
     }
}
