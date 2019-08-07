<?php

namespace App\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use App\Service\MessageGenerator;
	use App\Updates\SiteUpdateManager;
	Use App\Entity\Contact;
	Use App\Entity\User;
	Use Symfony\Component\HttpFoundation\Response;

	Use Symfony\Component\Routing\Annotation\Route;
	Use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	
	Use Symfony\Component\Form\Extension\Core\Type\TextType;
	Use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	Use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	

	Class ContactController extends AbstractController {
		/**
		*@Route("/", name="contact_list")
		*@Method({"GET"})
		*/
		public function index() {
			// return new Response('<html><body>Sawubona</body></html>');
			$arrayName = $this->getDoctrine()->getRepository(Contact::class)->findAll();
			return $this->render('contacts/index.html.twig',array('contacts' => $arrayName));
		}


		/**
		*@Route("/contact/registration", name="new_user")
		*@Method({"GET" , "POST"})
		*/
		public function registration() {
			// return new Response('<html><body>Sawubona</body></html>');
			// $arrayName = $this->getDoctrine()->getRepository(Contact::class)->findAll();
			return $this->render('/registration.html.twig');
		}


		/**
		*@Route("/contact/new", name="new_contact")
		*@Method({"GET" , "POST"})
		*/ 
		public function new(Request $request,MessageGenerator $messageGenerator,SiteUpdateManager $siteUpdateManager){
			$contact = new Contact();

			$message = $messageGenerator->getHappyMessage();
		echo	$this->getParameter('msg_email');exit;

			$form = $this->createFormBuilder($contact)
			->add('title', TextType::class, array('attr' => array('class'=>'form-control')))

			->add('Manager', TextType::class, array('attr' => array('class'=>'form-control')))

			->add('body',TextareaType::class,array('required'=> false,'attr' => array('class'=>'form-control')))
			->add('save',SubmitType::class,array(
				'label' => 'Create',
				'attr' => array('class' => 'btn btn-primary mt-3','disabled' => 'true')
			))
			->getForm();

			$form->handleRequest($request);


			if($form->isSubmitted() && $form->isValid()) {
				if ($siteUpdateManager->notifyOfSiteUpdate()) {
					
					$this->addFlash('success', 'Notification mail was sent successfully.');
			}
//echo 'fail';exit;
				$contact = $form->getData();
				$user = $this->getDoctrine()->getRepository(User::class)->findAll();
				 $name = 'Test';//$contact->getManager();
				$res_man = $contact->getManager();
				// echo $user->getEmail();exit;
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($contact);
				$entityManager->flush();

				

				return $this->redirectToRoute('contact_list');
			}

			return $this->render('/contacts/new.html.twig', array('form' => $form->createView()));
		}

		


		/**
		*@Route("/contact/edit/{id}", name="edit_contact")
		*@Method({"GET" , "POST"})
		*/ 
		public function edit(Request $request, $id){
			$contact = new Contact();
			$contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);

			$form = $this->createFormBuilder($contact)
			->add('title', TextType::class, array('attr' => array('class'=>'form-control')))
			->add('body',TextareaType::class,array('required'=> false,'attr' => array('class'=>'form-control')))
			->add('save',SubmitType::class,array(
				'label' => 'Update',
				'attr' => array('class' => 'btn btn-primary mt-3')
			))
			->getForm();

			$form->handleRequest($request);

			if($form->isSubmitted() && $form->isValid()) {
				
				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->flush();

				return $this->redirectToRoute('contact_list');
			}

			return $this->render('/contacts/edit.html.twig', array('form' => $form->createView()));
		}
		

		/**
		*@Route("/contact/{id}", name="contact_show")
		*/
		public function show($id){
			$contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);

			return $this->render('/contacts/show.html.twig', array('contact' => $contact));
		}

		/**
		 *@Route("/contact/scrap")
		 */
		public function scrap(){
			// $html = file_get_contents('menu.php');
			$html = file_get_contents('http://srsbsns.co.za/contact.html');
			echo $html;exit;
		//	show_source('menu.php');exit;
			$data = preg_match('/com">(.*?)<\/option>/s', $html, $matches);
			//print_r($matches);exit;
			//Create a new DOM document
			$dom = new DOMDocument;
			$emails = [];
			//Parse the HTML. The @ is used to suppress any parsing errors
			//that will be thrown if the $html string isn't valid XHTML.
			@$dom->loadHTML($html);
			
			//Get all links. You could also use any other tag name here,
			//like 'img' or 'table', to extract other tags.
			$links = $dom->getElementsByTagName('option');
			
			//print_r($h1);exit;

			//Iterate over the extracted links and display their URLs
			foreach ($links as $link){
					//Extract and show the "href" attribute. 
					
					$arrayName[] = array('email' => $link->getAttribute('value'),
							'name' => $link->nodeValue);

					$name = $link->nodeValue;
					$email = $link->getAttribute('value');
			}
		}


		/**
		*@Route("/contact/delete/{id}")
		*@Method({"DELETE"})
		*/
		public function delete(Request $request, $id){
			$contact = $this->getDoctrine()->getRepository(Contact::class)->find($id);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($contact);
			$entityManager->flush();
			
			$response = new Response();
			$response->send();
		}
	}
?>