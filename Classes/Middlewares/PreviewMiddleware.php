<?php


namespace Mittwald\Typo3Forum\Middlewares;


use Countable;
use Pottkinder\Helit\Service\ImportSpreadsheetService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;


class PreviewMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getQueryParams();
        if (isset($params['typo3forum-action']) && $params['typo3forum-action'] === 'preview') {
            $response = $this->renderPreview($request);
            if ($response->getStatusCode() === 200) {
                return $response;
            }
        }
        return $handler->handle($request);
    }

    protected function renderPreview(ServerRequestInterface $request)
    {
        $responseBody = new Stream('php://temp', 'rw');
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->initializeView();
        //$view->getRenderingContext()->setTemplatePaths();
        $view->getRenderingContext()->setControllerName('Ajax');
        $view->getRenderingContext()->setControllerAction('Preview');
        $view->getRequest()->setControllerExtensionName('Typo3forum');
        $view->assign('text', file_get_contents('php://input'));
        $view->setTemplateRootPaths(['EXT:typo3_forum/Resources/Private/Templates/Bootstrap/']);
        $responseBody->write($view->render());
        return (new Response())
            ->withStatus(200)
            ->withBody($responseBody)
            ->withHeader('Content-Type', 'text/plain')
            ->withHeader('X-Pottkinder', 'HandleProducts')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ;

    }

}
