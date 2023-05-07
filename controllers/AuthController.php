<?php

namespace LanguageLearning\Controller;

use LanguageLearning\Helper\FileHelper;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class LanguageController
{
    private $view;
    private $fileHelper;

    public function __construct(Container $container)
    {
        $this->view = $container->get(Twig::class);
        $this->fileHelper = new FileHelper();
    }

    public function index(Request $request, Response $response)
    {
        $languageFiles = $this->fileHelper->getLanguageFiles();
        $languageData = [];

        foreach ($languageFiles as $languageFile) {
            $languageData[$languageFile] = $this->fileHelper->getLanguageData($languageFile);
        }

        return $this->view->render($response, 'dashboard.twig', ['languages' => $languageData]);
    }

    public function getTrain(Request $request, Response $response, $args)
    {
        $language = $args['language'];

        $languageData = $this->fileHelper->getLanguageData($language);

        return $this->view->render($response, 'train.twig', ['language' => $language, 'languageData' => $languageData]);
    }

    public function postTrain(Request $request, Response $response)
    {
        $post = $request->getParsedBody();
        $language = $post['language'];
        $languageData = $this->fileHelper->getLanguage
