<?php 
namespace App\Controller; 
use App\Entity\User; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType; 
use Symfony\Component\Form\Extension\Core\Type\RepeatedType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
 
/** @Route("/admin")  
*
*/
class RegisterController extends AbstractController
{
	/**  
	* @Route("/register", name="register") 
	*  
	* @Security("is_granted('ROLE_ADMIN')") 
	*/
	public function register(Request $request, UserPasswordEncoderInterface $passEncoder)
	{
	$form=$this->createFormBuilder() 
		->add('username')
		->add('password', RepeatedType::class, [
            'type'=>PasswordType::class,
            'required'=>true,
            'first_options'=>['label'=>'Mot de passe'],
            'second_options'=>['label'=>'Confirmation Mot de passe'],
        ])
		->add('roles', ChoiceType::class, [
		'choices' => [
			'ROLE_USER' => 'ROLE_USER',
			'ROLE_ADMIN' => 'ROLE_ADMIN',
			'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
			],
		'multiple'=>true
        ])
        ->add('enregistrer', SubmitType::class, ['attr'=>['class'=>'btn btn-success', ]])
		->getForm();

		$form->handleRequest($request); 
        if($request->isMethod('post') && $form->isValid())
        { 
			$data=$form->getData(); 
			$user=new User; 
			$user->setUsername($data['username']); 
			$user->setPassword($passEncoder->encodePassword($user,$data['password']));
			$user->setRoles($data['roles']); 
			$em=$this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush(); 
			return $this->redirect($this->generateUrl('listeCompteAdmin'));
		}
		return $this->render('register/index.html.twig', ['form'=>$form->createView()]);
	}
}