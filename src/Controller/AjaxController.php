<?php

namespace App\Controller;

use App\Entity\Articles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{


    /**
     * @Route("/article/remove/", name="remove_article")
     * @param Request $request
     * @return JsonResponse
     */
    public function removeArticle(Request $request)
    {

        $articleId = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Articles::class)->find($articleId);

        if(!empty($article)){

            $em->remove($article);
            $em->flush();
            $this->addFlash('success', 'Статья успешно удалена!');
            return new JsonResponse(array('data' => 'success'));
        }
        else{
            return new JsonResponse(array('data' => 'error'));
        }
    }
}
