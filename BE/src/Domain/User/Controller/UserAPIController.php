<?php

declare(strict_types=1);

namespace App\Domain\User\Controller;

use App\Domain\User\DTO\UserRegistrationAPIDTO;
use App\Domain\User\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserAPIController extends AbstractController
{
    #[Route('/api/register', name: 'api_register_user', methods: ['POST'])]
    public function registerUser(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        $data = $serializer->deserialize(
            data: $request->getContent(),
            type: UserRegistrationAPIDTO::class,
            format: 'json',
        );
        $user = new User();
        $user
            ->setEmail(
                $data->getEmail()
            )
            ->setPassword(
                $passwordHasher->hashPassword(
                    user: $user,
                    plainPassword: $data->getPassword()
                ),
            );

        try {
            $entityManager
                ->persist($user);
            $entityManager
                ->flush();
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(
                [
                    'message' => 'User with this email already exists',
                ],
                400
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'message' => 'User registration failed',
                ],
                400
            );
        }

        return new JsonResponse(
            [
                'message' => 'User registered successfully',
            ],
            201
        );
    }
}
