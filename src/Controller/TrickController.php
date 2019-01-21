<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\TrickVideo;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tricks")
 */
class TrickController extends AbstractController
{
    public function __construct()
    {

    }

    /**
     * @Route("/", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Trick $trick): Response
    {
        // Checks if the user is logged in or not
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('trick/show.html.twig', [
                'trick' => $trick
            ]);
        }

        $comment = new Comment();
        $commentForm = $this->get('form.factory')->create(CommentType::class, $comment);

        if ($request->isMethod('POST') && $commentForm->handleRequest($request)->isValid()) {
            $comment->setTrick($trick);
            $comment->setCreationDate(new \DateTime('now'));
            $comment->setLastEditionDate(null);
            $comment->setUser($this->getUser());
            $comment->setStatus(Comment::COMMENT_STATUS_PUBLISHED);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            // Resets the form
            $comment = new Comment();
            $commentForm = $this->get('form.factory')->create(CommentType::class, $comment);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick): Response
    {
        foreach ($trick->getTrickImages() as $img) {
            $img->setPath(new File($this->getParameter('trick_images_directory') . "/" . $img->getPath()));
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($trick->getTrickImages() as &$img) {
                if (is_null($img->getId())) {
                    $file = $img->getPath();
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                    try {
                        $file->move(
                            $this->getParameter('trick_images_directory'),
                            $fileName
                        );
                    } catch (FileException $e) {
                        die($e->getMessage());
                    }
                    $img->setPath($fileName);
                    $img->setTrick($trick);
                    $this->getDoctrine()->getManager()->persist($img);
                }
                unset($img);
            }

            // FIXME: The video links do not get added to the database. They are submitted with the form, but not within $trick->trickVideos.
            /*foreach ($trick->getTrickVideos() as $vid) {
                //$video = $vid->getData();
                $video = new TrickVideo();
                $video->setUrl($vid['url']);
                $video->setTrick($trick);
                $this->getDoctrine()->getManager()->persist($vid);
            }*/


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_show', [
                'id' => $trick->getId(),
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index');
    }

    public function commentForm(Trick $trick): Response
    {
        $commentForm = $this->createForm(CommentType::class);
        return $this->render('comment/_form.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
