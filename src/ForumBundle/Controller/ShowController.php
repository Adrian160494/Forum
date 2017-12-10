<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\Message;
use ForumBundle\Entity\Topic;
use ForumBundle\Entity\User;
use ForumBundle\Form\LoginType;
use ForumBundle\Form\PostType;
use ForumBundle\Form\RegisterType;
use ForumBundle\Form\TopicType;
use ForumBundle\ForumBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ShowController extends Controller
{

    const POST_PER_PAGE = 5;

    /**
     * @Route("/admin")
     */
    public function adminAction(){
        return new Response('<html><body>Admin page!</body></html>');
    }


    /**
     * @Route("/", name="showForum")
     * @Template()
     */
    public function showAction(Request $request)
    {
        $doctrine = $this->getDoctrine()->getManager();
        $categories = $doctrine->getRepository('ForumBundle:Category')->findAll();

        return array(
            'categories' => $categories,
        );
    }

    /**
     * @Route("/{category}", name="category")
     * @Template()
     */
    public function categoryAction($category, Request $request)
    {
        $doctrine = $this->getDoctrine()->getManager();
        $query = $doctrine->createQuery("SELECT t FROM ForumBundle:Topic t WHERE t.category = :category")->setParameter('category',$category);
        $topics = $query->getResult();

        $topic = new Topic();
        $topic->setCategory($category);

        $form = $this->createForm(new TopicType(),$topic);
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();

            return $this->redirect($category);
        }

        return $this->render("ForumBundle:Show:category.html.twig",array(
            'header' => $category,
            'topics' =>$topics,
            'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/{category}/{topic}/page={page}", name="topic", defaults={"page"=1})
     * @Template()
     */
    public function topicAction($category,$topic,$page,Request $request){
        $doctrine = $this->getDoctrine()->getManager();
        $query = $doctrine->createQuery("SELECT m FROM ForumBundle:Message m WHERE m.topic = :topic")->setParameter('topic',$topic);
        $posts = $query->getResult();
        $numberSites = (int) ceil(count($posts)/self::POST_PER_PAGE);
        $arraySites = [];

        for($i=0;$i<$numberSites;$i++){
            array_push($arraySites,$i);
        }

        $start = self::POST_PER_PAGE * ($page - 1);
        $postsPagination = array_slice($posts,$start,self::POST_PER_PAGE);

        $post = new Message();
        $user = $this->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $post->setAuthor($user->getUsername());
        } else{
            $post->setAuthor("Guest");
        }
        $post->setDate(date('Y-m-d H:i:s'));
        $post->setCategory($category);
        $post->setTopic($topic);

        $form = $this->createForm(new PostType(),$post);

        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirect("page=".$page);
        }
        return $this->render("ForumBundle:Show:topic.html.twig",array(
            'posts'=>$postsPagination,
            'category'=>$category,
            'topic'=>$topic,
            'page'=>$page,
            'numberSites'=> $arraySites,
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/register/create/newUser", name="register")
     */
    public function registerAction(Request $request2){

        $user = new User();

        $form2 = $this->createForm(new RegisterType(),$user);

        $form2->handleRequest($request2);
        if($form2->isSubmitted()){
            $validator = $this->get('validator');
            $errors = $validator->validate($user);
            if(count($errors)>0){
            } else{
                if($form2->isValid()){
                    $em = $this->getDoctrine()->getManager();
                    $password = $user->getPassword();
                    $passwordHash = password_hash($password,PASSWORD_BCRYPT);
                    $user->setPassword($passwordHash);
                    $em->persist($user);
                    $em->flush();
                    return $this->redirect("registerComplete");
                }
            }
        }
        return $this->render("ForumBundle:Show:register.html.twig",array(
            'form2'=>$form2->createView()
        ));

    }

    /**
     * @Route("/register/create/registerComplete", name="registerComplete")
     * @Template()
     */
    public function registerCompleteAction(Request $request){

        return $this->render("ForumBundle:Show:registerComplete.html.twig",array('formRegister' => $this->login($request)));
    }

}