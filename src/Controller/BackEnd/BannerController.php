<?php

namespace App\Controller\BackEnd;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/banners")
 */
class BannerController extends AbstractController
{
    /**
     * @Route("", name="banner_index", methods={"GET", "POST"})
     *
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $files = [];
        if (is_dir($dir = $this->getParameter('banner_dir'))) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $files[] = $file;
                    }
                }
                closedir($dh);
            }
        }


        $form = $this->createFormBuilder()
            ->add('url', FileType::class, [
                'data' => null,
                'required' => true,
                'label' => 'Select file'
            ])->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('url')->getData();
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $file->move($this->getParameter('banner_dir'), $fileName);
            }

            $this->addFlash('success', 'upload file successfully.');
            return $this->redirectToRoute('banner_index');
        }


        return $this->render('backend/banner/index.html.twig', [
            'files' => $files,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{fileName}", name="banner_delete", methods={"DELETE"})
     *
     * @param string $fileName
     * @return Response
     */
    public function delete(string $fileName): Response
    {
        $filePath = $this->getParameter('banner_dir') . '/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->addFlash('success', 'Delete file successfully.');

        return $this->redirectToRoute('banner_index');
    }


}
