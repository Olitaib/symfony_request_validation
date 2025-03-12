<?php

namespace App\Controller\Api;

use App\DTO\User\CreateUserDTORequest;
use App\DTO\User\UpdateUserDTORequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/users')]
final class UserController extends AbstractController
{

    #[Route('/{id}', methods: ['put'])]
    public function update(int $id, UpdateUserDTORequest $request): JsonResponse
    {
        return $this->json([
            'id' => $id,
            'request' => $request->validated(),
        ]);
    }

    #[Route('', methods: ['post'])]
    public function store(CreateUserDTORequest $request): JsonResponse
    {
        return $this->json([
            'request' => $request->validated(),
        ]);
    }
}
