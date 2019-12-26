<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;

class BannerController extends AbstractController
{
    /**
     * @Route("/api/banners", name="api_banner")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index()
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

        $response = $this->json($this->toJsonSerializable($files));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
