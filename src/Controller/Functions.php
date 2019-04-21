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
	
	}
}
?>