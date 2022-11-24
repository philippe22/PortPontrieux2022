<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Ne pas oublier d'ajouter :
use App\Entity\Emplacement;
use App\Form\EmplacementType;

use Symfony\Component\HttpFoundation\Request; 

class EmplacementController extends AbstractController
{
    /**
     * @Route("/emplacement", name="emplacement")
     */
    public function inserer(Request $request)
    {
		// Création du formulaire
		$emplacement = new Emplacement();
		$form   = $this->createForm(EmplacementType::class, $emplacement);
		
		// Insertion dans la BDD si method POST ou affichage du formulaire dans le cas contraire
		if ($request->getMethod() == 'POST')
		{
			$form->handleRequest($request); // remplissage du formulaire à partir de la requête Http
			if ($form->isValid())
			{
				$em = $this->getDoctrine()->getManager();
				$em->persist($emplacement);
				$em->flush();
				
				$request->getSession()->getFlashBag()->add('info', 'Emplacement bien enregistré.');
				
				return $this->redirectToRoute('accueil');
			}
		}
		
		return $this->render('emplacement/index.html.twig', array('form' => $form->createView()));
    }
}
