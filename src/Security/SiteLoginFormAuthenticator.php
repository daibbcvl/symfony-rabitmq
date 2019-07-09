<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;

class SiteLoginFormAuthenticator extends LoginFormAuthenticator
{
    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
