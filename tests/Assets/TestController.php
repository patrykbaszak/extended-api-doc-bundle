<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDoc\Tests\Assets;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * Return the status of the API.
     */
    #[OA\Tag(name: 'Status')]
    #[OA\Response(
        response: 200,
        description: 'Status of the API.',
        content: new OA\JsonContent(
            ref: new Model(
                type: Status::class,
            )
        )
    )]
    #[Route('/api/status', name: 'api_status', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->json(new Status());
    }
}
