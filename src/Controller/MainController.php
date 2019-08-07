<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        $msg = 'Welcome Thabiso';
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController','msg' => $msg,
        ]);

    }

    /**
     * @Route("/custom/{para?}", name="custom")
     */
    public function custom(Request $request){
        $name = $request->get('para');

        return $this->render('main/custom.html.twig', [
            'controller_name' => 'MainController','msg' => $name,
        ]);
        
    }

    
}
