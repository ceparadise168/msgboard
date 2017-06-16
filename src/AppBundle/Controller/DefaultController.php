<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\PostType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Message;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
class DefaultController extends Controller
{
    /**
     * Get page then redirect list to list/page
     * @Route("page/{page}", name="page")
     * @Method("GET")
     */
    public function pageAction($page)
    {
        $returnArray = ['page'=>$page ];

        return $this->redirectToRoute('list', $returnArray);
    }

    /**
     * get message list and setting page
     * index
     * @Route("/{page}", name="list", requirements={"page": "\d+"})
     */
    public function indexAction($page =1)
    {
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('AppBundle:Message');

        $thisPage = $page;
        $limit = 5;
        $offset = $limit * ($page-1);
        $maxPages = ceil(count($messages->findAll()) / $limit);

        $qb = $messages->createQueryBuilder('m')
            ->orderBy('m.updatedAt', 'DESC')
            ->setFirstResult($limit * ($page -1))
            ->setMaxResults($limit);

        $query = $qb->getQuery();

        $result = $query->getResult();

        $messages = $result;

        $renderArray = [
            'messages' => $messages,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
                ];

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($renderArray,'json');

        //        $response = new Response(json_encode($renderArray));
        //        $response->headers->set('Content-Type', 'application/json');

        $dejson = json_decode($json);

        $renderArray0 = $renderArray["messages"][0]->getId();
        $json2 = $serializer->serialize($renderArray0,'json');


        if (isset($messages)) {
            // return new Response($json);
            //   return $this->json($json);
            //               return new JsonResponse($renderArray);
            //                return $response;
            // return $this->json($renderArray);
            return $this->render('index.html.twig', $renderArray);
        } else {
            return new Response('QwQ something worng with messages');
        }
    }

    /**
     * Add New Message
     * @Route("add", name="add")
     */
    public function createAction(Request $request)
    {
        $message = new Message();
        $form = $this->createForm('AppBundle\Form\MessageType', $message);
        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($message);

            $em->flush();

            $redirectArray = ['id' => $message->getId()];

            return $this->redirectToRoute('list', $redirectArray);
        }
        $renderArray = [
            'message' => $message,
            'form' => $form->createView()
                ];

        return $this->render('/add.html.twig', $renderArray);
    }

    /**
     * Show the Message
     * @Route("show/{id}", name="show")
     * @Method("GET")
     */
    public function showAction(Message $message)
    {
        $deleteForm = $this-> createDeleteForm($message);

        $renderArray = ['message' => $message, 'delete_form' => $deleteForm->createView()];

        return $this->render('show.html.twig', $renderArray);
    }

    /**
     * check status by show all Messages 
     * @Route("showall", name="showall")
     */
    public function showAllAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Message');

        $Msgs = $repository->findAll();

        $renderArray = ['messages' => $Msgs];

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($renderArray,'json');

        return new Response($json);

        //        return $this->render('/showall.html.twig',$renderArray);
    }

    /**
     * Update the Message
     * @Route("edit/{id}", name="edit")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request, Message $message)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($message);
        $updateForm = $this->createForm('AppBundle\Form\MessageType', $message);
        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {

            $message->setupdatedAt(new \DateTime('now', new \DateTimeZone('Asia/Taipei')));
            $em->flush();

            return $this->redirectToRoute('list');
        }

        $renderArray = [
            'message' => $message,
            'edit_form' => $updateForm->createView(),
            'delete_form' => $deleteForm->createView()
                ];

        return $this->render('edit.html.twig', $renderArray);
    }

    /**
     * Delete the Message
     * @Route("delete/{id}", name="delete")
     */
    public function deleteAction(Request $request, Message $message)
    {
        $form = $this->createDeleteForm($message);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $em->remove($message);

        $em->flush();

        return $this->redirectToRoute('list');
    }

    /**
     * Create a form to delete a message entity
     *
     * @param Message $message The message entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Message $message)
    {
        $returnArray = ['id' => $message->getId()];

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete', $returnArray))
            ->setMethod('DELETE')
            ->getForm();
    }



    /**
     * Get page then redirect list to list/page
     * @Route("p/{page}", name="apiage")
     * @Method("GET")
     */
    public function pageActionAPI($page)
    {
        $returnArray = ['page'=>$page ];
        return $this->redirectToRoute('apiList', $returnArray);
    }

    /**
     * get message list and setting page
     * index
     * @Route("/api/list/{page}", name="apiList", requirements={"page": "\d+"})
     */
    public function indexActionAPI($page =2)
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message');

        $thisPage = $page;
        $limit = 5;
        $offset = $limit * ($page-1);
        $maxPages = ceil(count($messages->findAll()) / $limit);

        $qb = $messages->createQueryBuilder('m')
            ->orderBy('m.updatedAt', 'DESC')
            ->setFirstResult($limit * ($page -1))
            ->setMaxResults($limit);
        $query = $qb->getQuery();
        $result = $query->getResult();
        $messages = $result;
        $renderArray = [
            'messages' => $messages,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
                ];

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($renderArray,'json');

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        if (isset($messages)) {
            return $response;
        } else {
            return new Response('QwQ something worng with messages');
        }
    }

    /**
     * Add New Message
     * @Route("api/add", name="apiAdd")
     * @Method("POST")
     */
    public function createActionAPI(Request $request)
    {
        $content = $request->getContent();
        $data = json_decode($content, true);
        $u = $data['userName'];
        $m = $data['msg'];
        $publishAt = new \DateTime('now', new \DateTimeZone('Asia/Taipei'));

        $message = new Message();
        $message->setUserName($u);
        $message->setMsg($m);
        $message->setPublishedAt($publishAt);

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($message, 'json');

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        $redirectArray = ['id' => $message->getId()];
        return $response;
    }

    /**
     * Update the Message
     * @Route("api/edit/{id}", name="api/edit")
     * @Method("PUT")
     */
    public function updateActionAPI(Request $request, $id)
    {
        $paramMsg = $request->request->get('msg');
        $paramName = $request->request->get('userName');
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message');
        $message = $messages->find($id);

        if ($message && $paramMsg !== null && $paramName !== null) {
            $message->setUserName($paramName);
            $message->setMsg($paramMsg);
            $message->setupdatedAt(new \DateTime('now', new \DateTimeZone('Asia/Taipei')));

            $encodersArray = [
                new XmlEncoder(),
                new JsonEncoder()
            ];
            $normalizersArray = [new ObjectNormalizer()];
            $encoders = $encodersArray;
            $normalizers = $normalizersArray;
            $serializer = new Serializer($normalizers, $encoders);
            $json = $serializer->serialize($message, 'json');
            $dejson = json_decode($json, true);
            $json = $serializer->serialize($dejson, 'json');

            $response = new Response();
            $response->setContent($json);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            $em->flush();
            return $response;
        } else {
            return new Response("GG");
        }

/*
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message');
        $message = $messages->find($id);
        $temp  = $message;
        $content = $request->getContent();

        $paramMsg = $request->request->get('msg');
        // dump($paramMsg);
        $paramName = $request->request->get('userName');
        // dump($paramName);

        // $data = json_decode($content, true);
        // $u = $data['userName'];
        // $m = $data['msg'];
        // $message->setUserName($u);
        // $message->setMsg($m);

        $message->setUserName($paramName);
        $message->setMsg($paramMsg);
        $message->setupdatedAt(new \DateTime('now', new \DateTimeZone('Asia/Taipei')));

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($message, 'json');
        $dejson = json_decode($json, true);
        $json = $serializer->serialize($dejson, 'json');

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $em->flush();

        return $response;
*/
    }

    /**
     * Delete the Message
     * @Route("api/delete/{id}", name="deleteAPI")
     */
    public function deleteActionAPI(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message');
        // $message = $messages->find($id);
        if ($message = $messages->find($id)){
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $json = $serializer->serialize($message, 'json');

            $em->remove($message);
            $em->flush();

            return JsonResponse::create(json_decode($json, true), 200);
            //        return $this->redirectToRoute('list');
        } else {
            return JsonResponse::create("ID NOT FOUND", 404 );
        }
    }
}
