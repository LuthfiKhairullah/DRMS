<?php

namespace App\Middleware;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Middleware\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();
        $isLoggedIn = $session->get('isLoggedIn');
        $category = $session->get('category');

        if (!$isLoggedIn) {
            return redirect()->to('/login');
        }

        if ($category !== 'admin') {
            return redirect()->to('/forbidden');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}