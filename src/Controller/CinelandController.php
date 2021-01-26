<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Genre;
use App\Entity\Film;
use App\Entity\Acteur;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * @Route("/cineland")
*/
class CinelandController extends AbstractController
{
    /**
     * @Route("/", name="cineland_index")
     */
    public function index(): Response
    {
        return $this->render('cineland/index.html.twig', [
            'controller_name' => 'CinelandController',
        ]);
    }

    /**
     * @Route("/init", name="cineland_init")
     */
    public function init(): Response
    {
    	$em = $this->getDoctrine()->getManager();

    	//Genres
    	for ($i=0; $i < 5; $i++) {
			$genres[$i] = new Genre();
			$em->persist($genres[$i]);
		}
    	$genres[0]->setNom('animation');
    	$genres[1]->setNom('policier');
    	$genres[2]->setNom('drame');
    	$genres[3]->setNom('comédie');
    	$genres[4]->setNom('X');

    	//Acteurs
    	for ($i=0; $i < 5; $i++) {
			$acteurs[$i] = new Acteur();
			$em->persist($acteurs[$i]);
		}
		$acteurs[0]->setNomPrenom('Galabru Michel')
			->setDateNaissance(\DateTime::createFromFormat('d/m/Y', '27/10/1922'))
			->setNationalite('france');
		$acteurs[1]->setNomPrenom('Deneuve Catherine')
			->setDateNaissance(\DateTime::createFromFormat('d/m/Y', '22/10/1943'))
			->setNationalite('france');
		$acteurs[2]->setNomPrenom('Depardieu Gérard')
			->setDateNaissance(\DateTime::createFromFormat('d/m/Y', '27/12/1948'))
			->setNationalite('russie');
		$acteurs[3]->setNomPrenom('Lanvin Gérard')
			->setDateNaissance(\DateTime::createFromFormat('d/m/Y', '21/06/1950'))
			->setNationalite('france');
		$acteurs[4]->setNomPrenom('Désiré Dupond')
			->setDateNaissance(\DateTime::createFromFormat('d/m/Y', '23/12/2001'))
			->setNationalite('groland');

		//Films
		for ($i=0; $i < 5; $i++) {
			$films[$i] = new Film();
			$em->persist($films[$i]);
		}
		$films[0]->setTitre('Astérix aux jeux olympiques')
			->setDuree(117)
			->setDateSortie(\DateTime::createFromFormat('d/m/Y', '20/01/2008'))
			->setNote(8)
			->setAgeMinimal(0)
			->setGenre($genres[0]);
		$films[1]->setTitre('Le Dernier Métro')
			->setDuree(131)
			->setDateSortie(\DateTime::createFromFormat('d/m/Y', '17/09/1980'))
			->setNote(15)
			->setAgeMinimal(12)
			->setGenre($genres[2])
			->addActeur($acteurs[1])
			->addActeur($acteurs[2]);
		$films[2]->setTitre('Le choix des armes')
			->setDuree(135)
			->setDateSortie(\DateTime::createFromFormat('d/m/Y', '19/10/1981'))
			->setNote(13)
			->setAgeMinimal(18)
			->setGenre($genres[1])
			->addActeur($acteurs[1])
			->addActeur($acteurs[2])
			->addActeur($acteurs[3]);
		$films[3]->setTitre('Les Parapluies de Cherbourg')
			->setDuree(91)
			->setDateSortie(\DateTime::createFromFormat('d/m/Y', '19/02/1964'))
			->setNote(9)
			->setAgeMinimal(0)
			->setGenre($genres[2])
			->addActeur($acteurs[1]);
		$films[4]->setTitre('La Guerre des boutons')
			->setDuree(90)
			->setDateSortie(\DateTime::createFromFormat('d/m/Y', '18/04/1962'))
			->setNote(7)
			->setAgeMinimal(0)
			->setGenre($genres[3])
			->addActeur($acteurs[0]);

    	$em->flush();
        return new Response('<html><boby>Réussi</body><html>');
    }

    /**
     * @Route("/action13", name="cineland_action13")
     */
    public function action13(Request $request): Response 
    {
    	$form = $this->createFormBuilder()
    		->add('annee1',IntegerType::class)
    		->add('annee2',IntegerType::class)
    		->add('Recherche',SubmitType::class)
    		->getForm();
    	$form->handleRequest($request); 

    	if ($form->isSubmitted()) {
    		$data = $form->getData();
            $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
    		$films = $repo->findEntreAnnees($data['annee1'],$data['annee2']);
    		return $this->render('cineland/actionObjet.html.twig', [
            		'resultats' => $films,
            		'form' => $form->createView(),
        		]);
        }
        return $this->render('cineland/action.html.twig', [
            	'form' => $form->createView(),
        	]);
    }

    /**
     * @Route("/action14", name="cineland_action14")
     */
    public function action14(Request $request): Response 
    {
    	$form = $this->createFormBuilder()
    		->add('date', DateType::class, array( 'years' => range(date('Y')-150, date('Y')+10 ) ) )
    		->add('Recherche',SubmitType::class)
    		->getForm();
    	$form->handleRequest($request); 

    	if ($form->isSubmitted()) {
    		$data = $form->getData();
            $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
    		$films = $repo->findAnterieureDate($data['date']);
    		return $this->render('cineland/actionObjet.html.twig', [
            		'resultats' => $films,
            		'form' => $form->createView(),
        		]);
        }
        return $this->render('cineland/action.html.twig', [
            	'form' => $form->createView(),
        	]);
    }

    /**
     * @Route("/action16", name="cineland_action16")
     */
    public function action16(Request $request): Response 
    {
    	$repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
    		$acteurs = $repo->auMoins3Films();


    	return $this->render('cineland/actionObjet.html.twig', [
        		'resultats' => $acteurs,
        	]);
    }

    /**
     * @Route("/action17", name="cineland_action17")
     */
    public function action17(Request $request): Response 
    {
    	$form = $this->createFormBuilder()
    		->add('Acteur1',EntityType::class, array('class' => Acteur::class ) )
    		->add('Acteur2',EntityType::class, array('class' => Acteur::class ) )
    		->add('Recherche',SubmitType::class)
    		->getForm();
    	$form->handleRequest($request);

    	if ($form->isSubmitted()) {
    		$data = $form->getData();
            $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
    		$films = $repo->find2Acteurs($data['Acteur1'],$data['Acteur2']);
    		return $this->render('cineland/actionObjet.html.twig', [
            		'resultats' => $films,
            		'form' => $form->createView(),
        		]);
        }
        return $this->render('cineland/action.html.twig', [
            	'form' => $form->createView(),
        	]);
    }

    /**
     * @Route("/action18", name="cineland_action18")
     */
    public function action18(Request $request): Response 
    {
    	$form = $this->createFormBuilder()
    		->add('Acteur',EntityType::class, array('class' => Acteur::class ) )
    		->add('Recherche',SubmitType::class)
    		->getForm();
    	$form->handleRequest($request);

    	if ($form->isSubmitted()) {
    		$data = $form->getData();
            $repo = $this->getDoctrine()->getManager()->getRepository(Genre::class);
    		$genres = $repo->findActeur2Films($data['Acteur']);
    		return $this->render('cineland/actionObjet.html.twig', [
            		'resultats' => $genres,
            		'form' => $form->createView(),
        		]);
        }
        return $this->render('cineland/action.html.twig', [
            	'form' => $form->createView(),
        	]);
    }

    /**
     * @Route("/action19", name="cineland_action19")
     */
    public function action19(Request $request): Response 
    {
    	$form = $this->createFormBuilder()
    		->add('Acteur',EntityType::class, array('class' => Acteur::class ) )
    		->add('Recherche',SubmitType::class)
    		->getForm();
    	$form->handleRequest($request);

    	if ($form->isSubmitted()) {
    		$data = $form->getData();
            $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
    		$resultats = $repo->findDureeActeur($data['Acteur']);

    		return $this->render('cineland/actionScalar.html.twig', [
            		'resultats' => $resultats,
            		'form' => $form->createView(),
        		]);
        }
        return $this->render('cineland/action.html.twig', [
            	'form' => $form->createView(),
        	]);
    }

    /**
     * @Route("/action20", name="cineland_action20")
     */
    public function action20(): Response 
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
    	$resultats = $repo->listeFilms();
       	
       	return $this->render('cineland/actionScalar.html.twig', [
        		'resultats' => $resultats,
        	]);
    }

    /**
     * @Route("/action21", name="cineland_action21")
     */
    public function action21(): Response 
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Acteur::class);
    	$resultats = $repo->listeGenres();
       	
       	return $this->render('cineland/actionScalar.html.twig', [
        		'resultats' => $resultats,
        	]);
    }

    /**
     * @Route("/action22", name="cineland_action22")
     */
    public function action22(Request $request): Response 
    {
        $form = $this->createFormBuilder()
    		->add('Genre',EntityType::class, array('class' => Genre::class ) )
    		->add('Recherche',SubmitType::class)
    		->getForm();
    	$form->handleRequest($request);
       	
       	if ($form->isSubmitted()) {
    		$data = $form->getData();
            $repo = $this->getDoctrine()->getManager()->getRepository(Genre::class);
    		$resultats = $repo->dureeMoyenne($data['Genre']);

    		return $this->render('cineland/actionScalar.html.twig', [
            		'resultats' => $resultats,
            		'form' => $form->createView(),
        		]);
        }
        return $this->render('cineland/action.html.twig', [
            	'form' => $form->createView(),
        	]);
    }

    /**
     * @Route("/action23", name="cineland_action23")
     */
    public function action23(Request $request): Response 
    {
        $form = $this->createFormBuilder()
    		->add('Film',EntityType::class, array('class' => Film::class ) )
    		->add('Diminuer',SubmitType::class)
    		->add('Augmenter',SubmitType::class)
    		->getForm();
    	$form->handleRequest($request);
       	
       	if ($form->isSubmitted()) {
       		$em = $this->getDoctrine()->getManager(); 

       		$data = $form->getData();
    		$film = $data['Film'];

    		if($form->getClickedButton()->getName() == 'Augmenter') {
    			$film->setNote($film->getNote()+1);
    		} else {
    			$film->setNote($film->getNote()-1);
    		}

    		$em->flush();

        }
        return $this->render('cineland/action.html.twig', [
            	'form' => $form->createView(),
        	]);
    }

    /**
     * @Route("/action25", name="cineland_action25")
     */
    public function action25(Request $request): Response 
    {
        $form = $this->createFormBuilder()
    		->add('Partie',TextType::class)
    		->add('Recherche',SubmitType::class)
    		->getForm();
    	$form->handleRequest($request);
       	
       	if ($form->isSubmitted()) {
    		$data = $form->getData();
            $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
    		$resultats = $repo->findPartieTitre($data['Partie']);

    		return $this->render('cineland/actionObjet.html.twig', [
            		'resultats' => $resultats,
            		'form' => $form->createView(),
        		]);
        }
        return $this->render('cineland/action.html.twig', [
                'form' => $form->createView(),
            ]);   
    }

    /**
     * @Route("/action26", name="cineland_action26")
     */
    public function action26(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('acteur',EntityType::class, array('class' => Acteur::class ) )
            ->add('choixdunombre',IntegerType::class,['required'   => false, 'empty_data' => '1', ])
            ->add('Ajouter',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $repo = $this->getDoctrine()->getManager()->getRepository(Film::class);
            $age = $form->get('choixdunombre')->getData();
            $acteur = $form->get('acteur')->getData();
            $queryBuilder = $repo->augmenterAgeMin($acteur,$age);
            return $this->render('cineland/action.html.twig', [
                    'form' => $form->createView(),
                ]);

        }
         
         return $this->render('cineland/action.html.twig', [
                'form' => $form->createView(),
            ]);   
    }





}
