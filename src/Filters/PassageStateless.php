<?php

namespace Michalsn\CodeIgniterPassage\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Michalsn\CodeIgniterPassage\Exceptions\PassageException;

/**
 * Passage filter.
 *
 * This filter is not intended to be used from the command line.
 *
 * @codeCoverageIgnore
 */
class PassageStateless implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param array|null $arguments
     *
     * @return RedirectResponse|void
     *
     * @throws PassageException
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $request instanceof IncomingRequest) {
            return;
        }

        try {
            $userId = service('passage')->authenticateRequest($request);
            passageId($userId);
        } catch (PassageException $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'error'   => true,
                    'message' => $e->getMessage(),
                ]);
        }
    }

    /**
     * We don't have anything to do here.
     *
     * @param array|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
