<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Admin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;  
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class LoginController extends AbstractController
{

    /**
     * @Route("/login", name="login")  methods={"POST"}
     */
    public function login(SessionInterface $session)
    {
        $request = Request::createFromGlobals(); // the envelope, and were looking inside it.
        // get the username and password
        $username = $request->request->get('username', 'none');            
		$password = $request->request->get('password', 'none');
            
            
        $repo = $this->getDoctrine()->getRepository(Admin::class); // the type of the entity
             
             
        $admin = $repo->findOneBy(['username' => $username, 'password' => $password]);
                
                
        if($admin){ // if we find the record, then we can redirect them to the homepage
            $session->set('username', $username);
            $session->set('auth', '1'); // 1 is basically true, 0 is false.
                    
            return $this->redirectToRoute('dashboard');
			} 
			else { // if we don't then, throw an error back
                return $this->redirectToRoute('index', ['error' => '*** Invalid username or password! ***']);                    
                }       
    }
	
	/**
	* @Route("/dashboard", name="dashboard")
	*/
	public function dashboard(SessionInterface $session)
	{
		if($session->get('auth') == "1") {
			
		    return $this->render('index.html.twig');
		}
		else{
			return $this->redirectToRoute('index', ['error' => '*** You must be logged in to view the dashboard! ***']);                    
                } 
	}   
}
