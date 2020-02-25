<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Location;
use App\Entity\Tags;
use App\Entity\User;
use App\Form\ArticlesType;
use App\Form\CommentType;
use App\Form\EditArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository(Articles::class)->findBy(array(), array('dateCr' => 'DESC'));
        $form = $this->createForm(ArticlesType::class);
        $form->handleRequest($request);
        $tags = $em->getRepository(User::class)->findAll();
        if($form->isSubmitted() && $form->isValid()){

           $this->createArticle($form);
            $this->addFlash('success', 'Статья успешно создана!');
           return $this->redirect($request->getUri());
        }
       // dump( $form->createView()); die;
        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
            'articles' => $articles,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{id}", name="article")
     * @param Articles $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexArticle(Articles $article, Request $request)
    {

        //dump($article->getComments()[0]); die();
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->get('user')->getData();
            $comment = $form->getData();
            $comment->setArticle($article);
            $comment->setCreatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Ваш коментарий успешно добавлен!');
            return $this->redirect($request->getUri());
        }
        return $this->render('articles/article.html.twig', [
            'article' => $article,
            'form'    => $form->createView()
        ]);
    }

    private function createArticle(FormInterface $form): void
    {

        $em = $this->getDoctrine()->getManager();
        $tagsArr = preg_split("/[\s,]+/", $form->get('tags')->getData());
        $userName = $form->get('user')->getData();
        $user = $em->getRepository(User::class )
            ->findOneBy( array('name' => $userName) );

        if(empty($user)){
            $locX = rand(20,40).'.'.rand(0,99);
            $locY = rand(45,52).'.'.rand(0,99);
            $location = new Location();
            $location->setLocX((float)$locX)->setLocY((float)$locY);
            $user = new User();
            $user->setName( $userName )->setLocation($location);
            $em->persist($location);
        }
        $articles = $form->getData();
        $articles->setDateCr(new \DateTime('now'));
        $articles = $this->setArticleTags($articles, $tagsArr);
        $user->addArticle($articles);
        $em->persist($user);
        $em->persist($articles);
        $em->flush();
    }

    /**
     * @Route("/article/edit/{id}", name="edit_article")
     * @param Articles $article
     * @param Request $request
     */
    public function editArticle(Articles $article, Request $request)
    {
        //dump($article->getTags()->toArray()); die();
        $form = $this->createForm(EditArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){


            $newTags = preg_split("/[\s,]+/", $form->get('tags')->getData());
            $article->removeAllTag();
            $article->setDateUp(new \DateTime('now'));
            $article = $this->setArticleTags($article, $newTags);
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            $this->addFlash('success', 'Изменения успешно сохранены!');



            return $this->redirectToRoute('article', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('articles/editArticle.html.twig', [
            'form'    => $form->createView()
        ]);
    }

    private function setArticleTags(Articles $articles, array $tagsArray)
    {
        $em = $this->getDoctrine()->getManager();
        $tagsDb = $em->getRepository(Tags::class )
            ->findBy( array('name' => $tagsArray) );

        if(!empty($tagsDb)){

            $arr = array();
            foreach ($tagsDb as $tags){

                $articles->addTag($tags);
                $arr[] = $tags->getName();
            }
            $newTags = array_diff($tagsArray, $arr);
        }
        else{ $newTags = $tagsArray; }

        if(!empty($newTags)){

            foreach ($newTags as $tagName){

                $tags = new Tags();
                $tags->setName($tagName);
                $tags->addArticle($articles);
            }
        }
        $em->persist($tags);
        return $articles;
    }
}
