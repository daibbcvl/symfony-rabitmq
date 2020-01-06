<?php

namespace App\Controller\Api;

use App\Exception\FormException;
use App\Form\Site\ContactType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/api/contact", name="api_contact", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \SendGrid\Mail\TypeException
     */
    public function contact(Request $request)
    {
        $json = $this->getJson($request);
        $form = $this->createForm(ContactType::class);
        $form->submit($json);
        if ($form->isValid()) {
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("admin@laptravels.com", "Admin LapTravels");
            $email->setSubject("A request from customer");
            $email->addTo("cunxu6789@gmail.com", "Customer Support");
            $email->addContent("text/html", $this->renderView('front/contact.html.twig', $form->getData()));
            $sendgrid = new \SendGrid('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');
            try {
                $mailResponse = $sendgrid->send($email);
                $response =  new JsonResponse(
                    ['message' => 'Email was sent'],
                    Response::HTTP_OK
                );
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');

                return $response;
            } catch (\Exception $e) {
                $response =  new JsonResponse(
                    ['errors' => $e->getMessage()],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Access-Control-Allow-Origin', '*');

                return $response;
            }
        } else {
            $response =  new JsonResponse(
                ['errors' => $this->getErrorsFromForm($form)],
                Response::HTTP_BAD_REQUEST
            );
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
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
