<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Annonce;
use App\Form\AnnonceType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType; 
use Symfony\Component\Form\Extension\Core\Type\RepeatedType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/** @Route("/admin") */
class AdminController extends AbstractController
{
    /**
     * @Route("/accueilAdmin", name="accueilAdmin")
     * 
     */
    public function index(): Response
    {
        return $this->render('admin/accueil.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    // Méthode pour afficher la liste des comptes et permettre une suppression ou modification
    /**
     * @Route("/listeCompteAdmin", name="listeCompteAdmin")
     * 
     */
    public function listeUtilisateur(): Response
    {
        $manager = $this->getDoctrine()->getManager();
		$rep = $manager->getRepository(User::class);
		$lesUtilisateurs = $rep->findAll();     
        return $this->render('admin/listeCompte.html.twig', Array('lesUtilisateurs'=>$lesUtilisateurs));
    }

    // Affichage du formulaire de modification d'un utilisateur pour modification
    /**
     * @Route("/modifCompteAdmin/{id}", name="modifCompteAdmin")
     * 
     */
	public function modifUtilisateur($id, Request $request, UserPasswordEncoderInterface $passEncoder)
    {
        // Récupération de l'utilisateur d'identifiant $id
		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository(User::class);
		$user = $rep->find($id);
		
		
		// Création du formulaire à partir du compte récupéré
		$form = $this->createForm(UserType::class, $user);
		
		// Mise à jour de la bdd si method POST ou affichage du formulaire dans le cas contraire
		if ($request->getMethod() == 'POST')
		{
            $form->handleRequest($request); // permet de récupérer les valeurs des champs dans les inputs du formulaire.
            
			if ($form->isValid())
			{
                // Cryptage du mot de passe
                $data=$form->getData();
                $user->setUsername($data->getUsername());
                $user->setPassword($passEncoder->encodePassword($user,$data->getPassword()));
                $user->setRoles($data->getRoles());

                // mise à jour de la bdd
				$em->persist($user);
                $em->flush();
                
				// Réaffichage de la liste des utilisateurss
				$lesUtilisateurs = $rep->findAll();
				return $this->render('admin/listeCompte.html.twig', Array('lesUtilisateurs' => $lesUtilisateurs));
			}
		}
		// Si formulaire pas encore soumis ou pas valide (affichage du formulaire)
		return $this->render('Admin/formUserAdmin.html.twig', array('form' => $form->createView(), 'action' => 'modification', 'id' => $id));
    }

    // Affichage du formulaire de modification d'un utilisateur pour suppression
    /**
     * @Route("/suppCompteAdmin/{id}", name="suppCompteAdmin")
     * 
     */
    public function suppUtilisateur($id, Request $request) // Affichage du formulaire de suppression d'un utilisateur
    {
        // Récupération de l'utilisateur d'identifiant $id
		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository(User::class);
		$user = $rep->find($id);
		
		// Création du formulaire à partir de l'utilisateur récupéré
		$form = $this->createForm(UserType::class, $user);
		
		// Mise à jour de la bdd si method POST ou affichage du formulaire dans le cas contraire
		if ($request->getMethod() == 'POST')
		{
			$form->handleRequest($request); // permet de récupérer les valeurs des champs dans les inputs du formulaire.
			
			// mise à jour de la bdd
			$res = $rep->suppUtilisateur($id);
			$em->persist($user);
			$em->flush();
				
			// Réaffichage de la liste des utilisateurs (comptes)
            $lesUtilisateurs = $rep->findAll();
            return $this->render('admin/listeCompte.html.twig', Array('lesUtilisateurs' => $lesUtilisateurs));
		}
		// Si formulaire pas encore soumis ou pas valide (affichage du formulaire)
		return $this->render('Admin/formUserAdmin.html.twig', array('form' => $form->createView(), 'action' => 'modification', 'id' => $id));
    }

    /**  
	* @Route("/insereAnnonce", name="insereAnnonceAdmin") 
	* 
	*/
	public function insertionAnnonce(Request $request)
	{
        // Création du formulaire
		$annonce = new Annonce();
        $form   = $this->createForm(AnnonceType::class, $annonce);
        

        // Insertion dans la BDD si method POST ou affichage du formulaire dans le cas contraire
        if ($request->getMethod() == 'POST')
        {
            $form->handleRequest($request); // remplissage du formulaire à partir de la requête Http
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($annonce);
                $em->flush();
                
                $request->getSession()->getFlashBag()->add('info', 'Annonce bien enregistrée.');
                
                return $this->redirectToRoute('accueilAdmin');
            }
        }

        return $this->render('admin/formAnnonceAdmin.html.twig', array('form'=>$form->createView(), 'action'=>'création'));
    }


    // Méthode pour afficher la liste des annonces et permettre une suppression ou modification
    /**
     * @Route("/listeAnnonceAdmin", name="listeAnnonceAdmin")
     * 
     */
    public function listeAnnonce(): Response
    {
        $manager = $this->getDoctrine()->getManager();
		$rep = $manager->getRepository(Annonce::class);
		$lesAnnonces = $rep->findAll();     
        return $this->render('admin/listeAnnonce.html.twig', Array('lesAnnonces'=>$lesAnnonces));
    }

    // Affichage du formulaire de modification d'une annonce pour modification
    /**
     * @Route("/modifAnnonceAdmin/{id}", name="modifAnnonceAdmin")
     * 
     */
	public function modifAnnonce($id, Request $request, UserPasswordEncoderInterface $passEncoder)
    {
        // Récupération de l'utilisateur d'identifiant $id
		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository(Annonce::class);
		$annonce = $rep->find($id);
		
		
		// Création du formulaire à partir de l'annonce récupérée
		$form = $this->createForm(AnnonceType::class, $annonce);
		
		// Mise à jour de la bdd si method POST ou affichage du formulaire dans le cas contraire
		if ($request->getMethod() == 'POST')
		{
            $form->handleRequest($request); // permet de récupérer les valeurs des champs dans les inputs du formulaire.
            
			if ($form->isValid())
			{
                // mise à jour de la bdd
				$em->persist($annonce);
                $em->flush();
                
				// Réaffichage de la liste des utilisateurss
				$lesAnnonces = $rep->findAll();
				return $this->render('admin/listeAnnonce.html.twig', Array('lesAnnonces' => $lesAnnonces));
			}
		}
		// Si formulaire pas encore soumis ou pas valide (affichage du formulaire)
		return $this->render('Admin/formAnnonceAdmin.html.twig', array('form' => $form->createView(), 'action' => 'modification', 'id' => $id));
    }

    // Affichage du formulaire de modification d'une annonce pour suppression
    /**
     * @Route("/suppAnnonceAdmin/{id}", name="suppAnnonceAdmin")
     * 
     */
    public function suppAnnonce($id, Request $request) // Affichage du formulaire de suppression d'une annonce
    {
        // Récupération de l'utilisateur d'identifiant $id
		$em = $this->getDoctrine()->getManager();
		$rep = $em->getRepository(Annonce::class);
		$annonce = $rep->find($id);
		
		// Création du formulaire à partir de l'annonce récupérée
		$form = $this->createForm(AnnonceType::class, $annonce);
		
		// Mise à jour de la bdd si method POST ou affichage du formulaire dans le cas contraire
		if ($request->getMethod() == 'POST')
		{
			$form->handleRequest($request); // permet de récupérer les valeurs des champs dans les inputs du formulaire.
			
			// mise à jour de la bdd
			$res = $rep->suppAnnonce($id);
			$em->persist($annonce);
			$em->flush();
				
			// Réaffichage de la liste des utilisateurs
            $lesAnnonces = $rep->findAll();
            return $this->render('admin/listeAnnonce.html.twig', Array('lesAnnonces' => $lesAnnonces));
		}
		// Si formulaire pas encore soumis ou pas valide (affichage du formulaire)
		return $this->render('Admin/formAnnonceAdmin.html.twig', array('form' => $form->createView(), 'action' => 'modification', 'id' => $id));
    }
}
