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
		
		//return raw data in table for all registered clocks
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
		
		//select today's clocks
		else if($type == 'today') {
			$date = date("d/m/Y"); //today's date
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$today = $repo->findAll();
			
			$table = "<table><thead><tr><th>Date</th><th>Employee ID</th><th>Time</th>";
			
			foreach($today as $row) {
				$time = $row->getTime(); //gets the value for 'time' in database
				$date_str = $time->format('d/m/Y'); //creates variable using only date part of the $time variable
				$time_str = $time->format('H:i:s'); //creates variable using only the time part of the $time variable
				
				if($date == $date_str) {
				
				    $table .= "<tr>";
				    $table .= "<td>".$date_str."</td>";
				    $table .= "<td>".$row->getEmpId()."</td>";
				    $table .= "<td>".$row->getDirection()." - ".$time_str."</td>";
				    $table .= "</tr>";
				}
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}
		
		else if($type == 'MOST RECENT') {
			$date = date("d/m/Y"); //today's date
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$today = $repo->findAll();
			
			$table = "<table><thead><tr><th>Employee</th><th>Department</th>";
			
			foreach($today as $row) {
				$time = $row->getTime();
				$date_str = $time->format('d/m/Y');
				
				if($date == $date_str) {
					if($row->getDirection() == "in") {
						$id =$row->getEmpId();
						foreach($today as $row2) {
							$time = $row2->getTime();
							$date_str = $time->format('d/m/Y');
							if($date == $date_str) {
								if($row2->getEmpId() == $id) {          
								    if($row2->getEmpId() == $id && $row2->getDirection() == 'out') {
										continue;
									}
									else {
										$table .= "<tr>";
									
										$table .= "<td>".$row->getEmpId()."</td>";
										
										$table .= "</tr>";
									}
								}
				                
								
								
							}
						}
					}
				}
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}	

		else if($type == '') {
			$date = date("d/m/Y"); //today's date
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$today = $repo->findAll();
			
			$table = "<table><thead><tr><th>Employee</th><th>Department</th>";
			
			$length = 0;
			foreach($today as $row) {
				
				    $length++;
				
			}
			
			for($x=0; $x < $length; $x++) {
				$time = $today[$x]->getTime();
				$date_str = $time->format('d/m/Y');				
				if($date == $date_str) {
					if($today[$x]->getDirection() == 'in') {
						$emp = $today[$x]->getEmpId();
						for($x=0; $x < $length; $x++) {
							if($date == $date_str) {
								if($today[$x]->getDirection() == 'out') {
									if($today[$x]->getEmpId() == $emp) {
										continue;
									}
									else {
										$table.= "<tr>";
										$table.="<td>".$today[$x]->getEmpId()."</td>";
										$table.="</tr>";
									}
								}
							}
						}
					}
				}									
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}
		
		else if($type == '') {
			$date = date("d/m/Y"); //today's date
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$today = $repo->findAll();
			
			$table = "<table><thead><tr><th>Date</th><th>Employee ID</th><th>Time</th>";
			
			foreach($today as $row) {
				$time = $row->getTime(); //gets the value for 'time' in database
				$date_str = $time->format('d/m/Y'); //creates variable using only date part of the $time variable
				$time_str = $time->format('H:i:s'); //creates variable using only the time part of the $time variable
				
				if($date == $date_str) {				
				    
				}
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}
		
		else if($type == 'onsite') {
			$date = date("d/m/Y"); //today's date
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$today = $repo->findAll();
			
			$table = "<table><thead><tr><th>Employee</th><th>Department</th>";
			
			foreach($today as $row) {
				$time = $row->getTime();
				$date_str = $time->format('d/m/Y');
				
				if($date == $date_str) {
					if($row->getDirection() == "in") {
						$id =$row->getEmpId();
						
						if($this->haveLeft($id) == true) {
							continue;
						}
						else {
							$table.= "<tr>";
							$table.="<td>".$row->getEmpId()."</td>";
							$table.="</tr>";
						}
						
					}
				}
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}	
				
	}
	
	public function haveLeft($id) {	
		$date = date("d/m/Y"); //today's date
		$request = Request::createFromGlobals(); 
		
		$repo = $this->getDoctrine()->getRepository(Clockings::class);
		$today = $repo->findAll();
		
		foreach($today as $row2) {
		    $time = $row2->getTime();
		    $date_str = $time->format('d/m/Y');
		    if($date == $date_str) {
				if($row2->getEmpId() == $id) {          
					if($row2->getEmpId() == $id && $row2->getDirection() == 'out') {
					return true;
					}
					else {
						continue;
					}
				}
			}
		}		
	}
	
	
}
?>