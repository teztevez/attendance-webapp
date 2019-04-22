<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Admin; //use our entities
use App\Entity\Clockings;
use App\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Functions extends AbstractController
{	
	/**
	* @Route("/functions", name="catch") methods=("GET", "POST")
	*/
	public function index()
	{
		$request = Request::createFromGlobals(); 
		
		$type = $request->request->get('type', 'none');
		
		//login function
		if($type == 'login') {
				//get username and password
			$username = $request->request->get('username', 'none');
			$password = $request->request->get('password', 'none');		
			
			$repo = $this->getDoctrine()->getRepository(Admin::class); //type of entityManager
			
			$admin = $repo->findOneBy(['username' => $username, 'password' => $password]);			
			
			return new Response("Welcome " .$admin->getUsername());	
		}
		
		else if($type == 'allClock') {
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$all = $repo->findAll();
			
			$table = "<table><thead><tr><th>Date</th><th>Employee ID</th><th>Time</th>";
			
			foreach($all as $row) {
				$time = $row->getTime();
				$date_str = $time->format('d/m/Y');
				$time_str = $time->format('H:i:s');
				
				$table .= "<tr>";
				$table .= "<td>".$date_str."</td>";
				$table .= "<td>".$row->getEmpId()."</td>";
				$table .= "<td>".$row->getDirection()." - ".$time_str."</td>";
				$table .= "</tr>";
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}
				
	}
}
?>