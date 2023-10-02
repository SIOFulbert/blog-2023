<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\Article1Type;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
       // $categorie = new Categorie();
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/result', name: 'app_article_result', methods: ['GET'])]
    public function result(ArticleRepository $articleRepository, Request $request): Response
    {
       $form = ($request->get('form'));
       $elt = $form['elt'];
       $articles=  $articleRepository->findByElement($elt);
       return $this->render('article/index.html.twig', [
           'articles' => $articles,
       ]);
    }
    #[Route('/recherche', name: 'app_article_search', methods: ['GET'])]
    public function recherche(ArticleRepository $articleRepository, Request $request): Response
    {
        $form = $this->createFormBuilder(null, [
            'attr' => ['class' => 'd-flex'],
        ])
        ->setAction($this->generateUrl('app_article_result'))
        ->add('elt',TextType::class, ['label'=>false,  
                    'attr' => ['placeholder' => 'Rechercher...',
                    'class'=>'form-control me-2'
                    ]])
        ->add('submit', SubmitType::class,[
            'attr' => [
                    'class'=>'btn btn-outline-success'
            ]
        ] )
        ->setMethod('GET')
        ->getForm();
      
       return $this->render('article/_form_recherche.html.twig', [
           'form' => $form->createView(),
       ]);

    }
    
    #[Route('/side-articles/{max}', name: 'app_article_side', methods: ['GET'])]
    public function side(ArticleRepository $articleRepository, int $max ): Response
    {
       // $categorie = new Categorie();
        return $this->render('article/side.html.twig', [
            'articles' => $articleRepository->findBy([],['crea'=>'desc'],$max,0),
        ]);
    }
    
    #[Route('/categorie/{slug}/articles', name: 'app_article_categorie', methods: ['GET'])]
    public function articlescategorie(ArticleRepository $articleRepository, Categorie $categorie): Response
    {
       // $categorie = new Categorie();
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findByCategorie($categorie),
        ]);
    }
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(Article1Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        $nbAvis = count($article->getAvis());
       
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'nbAvis' =>$nbAvis
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Article1Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
