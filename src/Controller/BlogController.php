<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Form\Forms;
//use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Doctrine\Persistence\ObjectManager;
use App\Form\ArticleType;
use App\Entity\Comment;
use App\Form\CommentType;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(ArticleRepository $repo)
    {
//        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }
    
    
    /**
     * @Route("/", name="home")
     * 
     */
    public function home(){
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue dans ce blog !'
        ]);
    }
    
    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */
    //, OjectManager $manager
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager){
        
        if(!$article){
            $article = new Article();
        }
        
//        $form = $this->createFormBuilder($article)
//                ->add('title')
//                ->add('content')
//                ->add('image')
//
////                ->add('image', TextType::class, ['attr' => [
////                    'placeholder' => "Image de l'article"
////                    ]])
////                ->add('save', SubmitType::class, [ 
////                    'label' => 'Enregistrer'
////                    ])
//                ->getForm();
        
        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();
            
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }
        
        return $this->render('blog/create.html.twig',[
            'formArticle' => $form->createView(),
            'editmode' => $article->getId() !== null,
        ]);
    }
    
    /**
     * @Route("/blog/{id}", name="blog_show")
     *
     */
//    public function show(ArticleRepository $repo, $id){
        public function show(Article $article, Request $request, EntityManagerInterface $manager){
//        $repo = $this->getDoctrine()->getRepository(Article::class);
//        $article = $repo->find($id);
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $comment->setCreatedAt(new \DateTime())
                        ->setArticle($article);
                
                $manager->persist($comment);
                $manager->flush();
                
                return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
            }
            
        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }
    
  
}
