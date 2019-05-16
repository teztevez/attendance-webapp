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
			
			$table = "<h2>Full Clocking History</h2><br>";
			$table .= "<table><thead><tr><th>Date</th><th>Employee ID</th><th>Time</th>";
			
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
			
			$table = "<h2>Today's Clocks</h2><br>";
			$table .= "<table><thead><tr><th>Date</th><th>Employee ID</th><th>Direction</th><th>Time</th>";
			
			foreach($today as $row) {
				$time = $row->getTime(); //gets the value for 'time' in database
				$date_str = $time->format('d/m/Y'); //creates variable using only date part of the $time variable
				$time_str = $time->format('H:i:s'); //creates variable using only the time part of the $time variable
				
				if($date == $date_str) {
				
				    $table .= "<tr>";
				    $table .= "<td>".$date_str."</td>";
				    $table .= "<td>".$row->getEmpId()."</td>";
				    $table .= "<td>".$row->getDirection()."</td>";
					$table .= "<td>".$time_str."</td>";
				    $table .= "</tr>";
				}
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}		
		
		//function to gather data for onsite report
		else if($type == 'onsite') {
			$date = date("d/m/Y"); //today's date
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$empRepo = $this->getDoctrine()->getRepository(Employee::class);
			$today = $repo->findAll();
			
			$table = "<h2>Current Employees Onsite</h2><br>";
			$table .= "<table><thead><tr><th>Employee #</th><th>Name</th><th>Department</th>";
			
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
							$emp = $empRepo->findOneBy(['id' => $id]);
							$table.= "<tr>";
							$table.="<td>".$id."</td>";
							$table.="<td>".$emp->getFname()." ".$emp->getLname()."</td>";
							$table.="<td>".$emp->getDept()."</td>";
							$table.="</tr>";
						}
						
					}
				}
			}
			
			$table .= "</tbody></table>";
			
			
			return new Response($table);
							
		}		
		
		//function to gather data for selected date
	    else if($type == 'selectDate') {
			$date = $request->request->get('date', 'none');
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$result = $repo->findAll();
			
			$table = "<h2>Clocking Data for ".$date."</h2><br>";
			$table .= "<table><thead><tr><th>Date</th><th>Employee ID</th><th>Direction</th><th>Time</th>";
			
			foreach($result as $row) {	
				$time = $row->getTime();			
				$date_str = $time->format('Y-m-d'); //creates variable using only date part of the $time variable
				$time_str = $time->format('H:i:s'); //creates variable using only the time part of the $time variable
				
				if($date == $date_str) {
				
				    $table .= "<tr>";
				    $table .= "<td>".$date_str."</td>";
				    $table .= "<td>".$row->getEmpId()."</td>";
				    $table .= "<td>".$row->getDirection()."</td>";
					$table .= "<td>".$time_str."</td>";
				    $table .= "</tr>";
				}
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}
		
		//function to show late arrivals
		else if($type == 'selectLate') {
			$date = $request->request->get('date', 'none');
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$empRepo = $this->getDoctrine()->getRepository(Employee::class);
			$result = $repo->findAll();
			
			$table = "<h2>Late Arrivals on ".$date."</h2><br>";
			$table .= "<table><thead><tr><th>Employee ID</th><th>Name</th><th>Time</th>";
			
			foreach($result as $row) {	
			    $id = $row->getEmpId();
			    $emp = $empRepo->findOneBy(['id' => $id]);
				$time = $row->getTime();			
				$date_str = $time->format('Y-m-d'); //creates variable using only date part of the $time variable
				$time_str = $time->format('H:i:s'); //creates variable using only the time part of the $time variable
				
				if($date == $date_str) {
					if($row->getDirection() == "in" && $row->getPunctual() == "no") {
				
				        $table .= "<tr>";				        				        
				        $table .= "<td>".$id."</td>";
				        $table .= "<td>".$emp->getFname()." ".$emp->getLname()."</td>";
					    $table .= "<td>".$time_str."</td>";
				        $table .= "</tr>";
				    }
				}
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}
		
		//function to display early leavings
		else if($type == 'selectEarly') {
			$date = $request->request->get('date', 'none');			
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
			$empRepo = $this->getDoctrine()->getRepository(Employee::class);
			$result = $repo->findAll();
			
			$table = "<h2>Early Leavers on ".$date."</h2><br>";
			$table .= "<table><thead><tr><th>Employee ID</th><th>Name</th><th>Time</th>";
			
			foreach($result as $row) {
				$id = $row->getEmpId();
			    $emp = $empRepo->findOneBy(['id' => $id]);
				$time = $row->getTime();			
				$date_str = $time->format('Y-m-d'); //creates variable using only date part of the $time variable
				$time_str = $time->format('H:i:s'); //creates variable using only the time part of the $time variable
				
				if($date == $date_str) {
					if($row->getDirection() == "out" && $row->getPunctual() == "no") {
				
				        $table .= "<tr>";				        
				        $table .= "<td>".$id."</td>";
				        $table .= "<td>".$emp->getFname()." ".$emp->getLname()."</td>";
					    $table .= "<td>".$time_str."</td>";
				        $table .= "</tr>";
				    }
				}
			}
			
			$table .= "</tbody></table>";
			
			return new Response($table);	
		}
		
		//function to create new employees
		else if($type == 'create')
		{			
			//get the variables
			$fname = $request->request->get('fname', 'none');
			$lname = $request->request->get('lname', 'none');
			$dept = $request->request->get('dept', 'none');
			
			//put it in the database
			$entityManager = $this->getDoctrine()->getManager(); //make a manager
			
			$emp = new Employee(); //make object
			$emp->setFname($fname); 
			$emp->setLname($lname);
			$emp->setDept($dept);
			
			$entityManager->persist($emp);
			//execute query and insert into db
			$entityManager->flush();
			
			return new Response("Employee added successfully!");
		}
		
		//function to update records
		else if($type == 'update') { 
			
			$repository = $this->getDoctrine()->getRepository(Clockings::class);
            $anomolies = $repository->findAll();
			
			//inserted javascript here because wasn't functioning otherwise
			$table = '<script>
			          $("[id^=change").click(function(){
                      console.log("Test");
                      var id = $(this).attr("clockId");           
	
				      $.post( "/functions", { type: "updateRecord", id: id}) 
					  .done(function(data) {
				      alert(data);				
				      document.getElementById("showRecords").click(); //automatically clicks button to refresh this tab				
				      });    
				      });	
					 </script>';
			
			$table .= '<table data-role="table"><thead><tr><th>Record ID</th><th>Employee ID</th><th>Date</th><th>Time</th><th>Direction</th><th>Punctual</th></tr></tr></thead><tbody>';
			
			foreach($anomolies as $x) {
				$time = $x->getTime();			
				$date_str = $time->format('Y-m-d'); //creates variable using only date part of the $time variable
				$time_str = $time->format('H:i:s');
				if($x->getPunctual() == "no") {
				    $table .= "<tr>";
				    $table .= "<td>".$x->getId()."</td>";
				    $table .= "<td>".$x->getEmpId()."</td>";
				    $table .= "<td>".$date_str."</td>";
					$table .= "<td>".$time_str."</td>";
				    $table .= "<td>".$x->getDirection()."</td>";
				    $table .= "<td>".$x->getPunctual()."</td>";				    
				    $table .= '<td><button id="change'.$x->getId().'" clockId="'.$x->getId().'">Update</button></td>';
				    $table .= "</tr>";
				}
		    }
			
			$table .= "</tbody></table>";
		
					
			return new Response($table);
		}
		
		//function to commit change to the database
		else if($type == 'updateRecord') {
			$id = $request->request->get('id', 'none'); 
			
			$entityManager = $this->getDoctrine()->getManager();
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
            $currentid = $repo->findOneBy(['id' => $id]);			
			
			$currentid->setPunctual("yes");
			
			$entityManager->flush();
			
			return new Response("Record Updated!"); 
		}
		
		//function to update unknown records
		else if($type == 'updateUnknown') { 
			
			$repository = $this->getDoctrine()->getRepository(Clockings::class);
            $anomolies = $repository->findAll();
			
			//inserted javascript here because wasn't functioning otherwise
			$table = '<script>$("[id^=change").click(function(){
                      console.log("Test");
                      var id = $(this).attr("clockId");  
					  var dir = $( "input[name=dir]:checked" ).val(); //grabs value of checked radio button
					  var pun = $( "input[name=pun]:checked" ).val(); 
	
				      $.post( "/functions", { type: "rectify", id: id, dir: dir, pun: pun }) 
					  .done(function(data) {
				      alert(data);				
				      document.getElementById("showUnknown").click(); //automatically clicks button to refresh this tab				
				      });    
				      });	
					 </script>';
			
			$table .= '<table data-role="table"><thead><tr><th>Record ID</th><th>Employee ID</th><th>Date</th><th>Time</th><th>Direction</th><th>Punctual</th><th>Update</th></tr></tr></thead><tbody>';
			
			foreach($anomolies as $x) {
				$time = $x->getTime();			
				$date_str = $time->format('Y-m-d'); //creates variable using only date part of the $time variable
				$time_str = $time->format('H:i:s');
				if($x->getPunctual() == "unknown") {
				    $table .= "<tr>";
				    $table .= "<td>".$x->getId()."</td>";
				    $table .= "<td>".$x->getEmpId()."</td>";
				    $table .= "<td>".$date_str."</td>";
					$table .= "<td>".$time_str."</td>";
				    $table .= '<td><fieldset data-role="controlgroup" data-type="horizontal"><input type="radio" name="dir" value="in" checked="checked">
							   <label for="dir1">In</label>
							<input type="radio" name="dir" value="out">
							<label for="dir2">Out</label></td>';
				    $table .= '<td><fieldset data-role="controlgroup" data-type="horizontal"><input type="radio" name="pun" value="yes" checked="checked">
							   <label for="p1">Yes</label>
							<input type="radio" name="pun" value="no">
							<label for="pun2">No</label></td>';			    
				    $table .= '<td><button id="change'.$x->getId().'" clockId="'.$x->getId().'">Update</button></td>';
				    $table .= "</tr>";
				}
		    }
			
			$table .= "</tbody></table>";
		
					
			return new Response($table);
		}
		
		//function to commit changes based on function above
		else if($type == 'rectify') {
			$id = $request->request->get('id', 'none'); 
			$direction = $request->request->get('dir', 'none'); 
			$punctual = $request->request->get('pun', 'none'); 
			
			$entityManager = $this->getDoctrine()->getManager();
			
			$repo = $this->getDoctrine()->getRepository(Clockings::class);
            $currentid = $repo->findOneBy(['id' => $id]);			
			
			$currentid->setDirection($direction);
			$currentid->setPunctual($punctual);
			
			$entityManager->flush();
			
			return new Response("Record Updated!"); 
		}
	}

		
	
	//function called in previous onsite function
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
	
	/**
	* @Route("/stats", name="stats")
	*/
	public function stats()
	{
		return $this->render('stats.html.twig');
	}
	
	/**
	* @Route("/punctual-or-not", name="punctual-or-not")
	*/
	public function punctual_or_not()
	{
		$repo = $this->getDoctrine()->getRepository(Clockings::class);
		$all = $repo->findAll();
		
		$tardy = 0; //overall figures for non-punctual
		$punctual = 0; //overall figures for punctual
		$unknown = 0; //overall figures for unknown directions
		$p_arrival = 0; //punctual arrival figures
		$t_arrival = 0; //tardy arrival figures
		$p_leave = 0; //punctual leaving figures
		$t_leave = 0; //tardy leaving figures
		
		foreach($all as $row) {
			if($row->getPunctual() == "yes") { //if record is punctual
				$punctual++;
				if($row->getDirection() == "in") {
					$p_arrival++; //if direction is "in", mark punctual arrival as +1
				}
				else{
					$p_leave++;
				}
			}
			else if($row->getPunctual() == "no") {
				$tardy++;
				if($row->getDirection() == "in") {
					$t_arrival++;
				}
				else{
					$t_leave++;
				}
			}
			else {
				$unknown++;
			}
		}
		
		$stats = [$punctual, $tardy, $unknown, $p_arrival, $t_arrival, $p_leave, $t_leave]; //array of various stats
		
		return $this->render('punctual.html.twig', array("stats" => $stats));
	}
	
	/**
	* @Route("/range", name="range")
	*/
	//public function range()
	//{
	//	$repo = $this->getDoctrine()->getRepository(Clockings::class);
	//	$all = $repo->findAll();
		
		
		
		
	//	return $this->render('range.html.twig', array("all" => $all));
	//}
	
	/**
	* @Route("/emp", name="emp")
	*/
	public function emp()
	{
		$repo = $this->getDoctrine()->getRepository(Employee::class);
		$all = $repo->findAll();		
		
		
		return $this->render('emp.html.twig', array("all" => $all));
	}
	
	/**
	* @Route("/empstat", name="empstat")
	*/
	public function empstat()
	{
		$request = Request::createFromGlobals(); 
		
		$id = $request->request->get('id', 'none');	
		
		
		$repo = $this->getDoctrine()->getRepository(Clockings::class);
		$emp = $repo->findBy(['emp_id' => $id]);
		$repo1 = $this->getDoctrine()->getRepository(Employee::class);
		$name = $repo1->findOneBy(['id' => $id]);
		$fname = $name->getFname();
		$lname = $name->getLname();
		
		$punctual = 0; 
		$tardy = 0;
		$unknown = 0;
		$total = 0;
		$p_entry = 0;
		$t_entry = 0;
		$p_exit = 0;
		$t_exit = 0;		
		$stat = "";
		
		foreach ($emp as $row) {
			$total++;
			if($row->getPunctual() == "yes") {
				$punctual++;
				if($row->getDirection() == "in") {
					$p_entry++;
				}
				else {
					$p_exit++;
				}
			}
			else {
				$tardy++;
				if($row->getDirection() == "in") {
					$t_entry++;
				}
				else {
					$t_exit++;
				}
			}				
		}
		$stat.= "<h1>Stats for ".$fname." ".$lname."</h1>";
		$stat.= "<table>";
		$stat.= "<tr><td>Total Interactions:</td><td>".$total."</td></tr>";
		$stat.= "<tr><td>Total Punctual:</td><td>".$punctual."</td></tr>";
		$stat.= "<tr><td>Total Tardy:</td><td>".$tardy."</td></tr>";
		$stat.= "<tr><td>Punctual Entries:</td><td>".$p_entry."</td></tr>";
		$stat.= "<tr><td>Tardy Entries:</td><td>".$t_entry."</td></tr>";
		$stat.= "<tr><td>Punctual Exits:</td><td>".$p_exit."</td></tr>";
		$stat.= "<tr><td>Tardy Exits:</td><td>".$t_exit."</td></tr>";
		$stat.= "<tr><td>Unknown Interactions:</td><td>".$unknown."</td></tr>";
		$stat.= "</table>";
		
		return new Response($stat);
	}
	
	/**
	* @Route("/status", name="status")
	*/
	public function status()
	{
		$repo = $this->getDoctrine()->getRepository(Employee::class);
		$all = $repo->findAll();		
		
		
		return $this->render('status.html.twig', array("all" => $all));
	}
	
	/**
	* @Route("/updatestatus", name="updatestatus")
	*/
	public function updatestatus()
	{
		$entityManager = $this->getDoctrine()->getManager();
		$request = Request::createFromGlobals(); 		
		$id = $request->request->get('id', 'none');
		
		$repo = $this->getDoctrine()->getRepository(Employee::class);
		$emp = $repo->findOneBy(['id' => $id]);		
		
		if($emp->getStatus() == "active") {			
			$emp->setStatus("inactive");
		}
		else {
			$emp->setStatus("active");
		}			
			$entityManager->flush();
		
		return new Response("Status Changed!");
	}
	
	
	
	
}
?>