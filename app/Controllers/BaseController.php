<?php

namespace App\Controllers;

use App\Libraries\DhonAuth;
use App\Libraries\DhonCurl;
use App\Libraries\DhonHit;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    protected $token;
    protected $assets;
    protected $data;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        $dhonauth = new DhonAuth();
        $dhonauth->auth();
        $this->token = $dhonauth->token;

        $dhoncurl = new DhonCurl();
        $user = $dhoncurl->get('me', [
            'Content-Type'  => 'application/json',
            'Authorization' => "Bearer " . $this->token,
        ]);

        $dhonhit = new DhonHit();
        $dhonhit->hit($this->token);

        $this->assets = getenv("ASSETS_URL");

        $this->data = [
            'assets' => $this->assets
        ];
    }
}
