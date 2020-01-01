<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Exception\FormException;
use App\Form\Site\CommentType;
use App\Form\Site\ContactType;
use App\Model\Article;
use App\Model\CategoryItem;
use App\Model\DomesticCategory;
use App\Repository\CategoryRepository;
use App\Repository\CityRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @Route("/api/contact", name="api_contact", methods={"GET", "POST"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $manager
     * @param CommentRepository      $commentRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(Request $request, EntityManagerInterface $manager, CommentRepository $commentRepository)
    {
        $json = $this->getJson($request);
        $form = $this->createForm(ContactType::class);
        $form->submit($json);
        if ($form->isValid()) {
            $email = (new Email())
                ->from('test@example.com')
                ->to('daibbcvl@yahoo.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');

            /** @var Symfony\Component\Mailer\SentMessage $sentEmail */
            //$sentEmail = $mailer->send($email);

            return new JsonResponse(
                ['message' => 'Email was sent'],
                Response::HTTP_OK
            );

        } else {
            return new JsonResponse(
                ['errors' => $this->getErrorsFromForm($form)],
                Response::HTTP_BAD_REQUEST
            );


        }

    }

    /**
     * @param Request $request
     *
     * @return mixed
     *
     * @throws HttpException
     */
    private function getJson(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }
        return $data;
    }
}
