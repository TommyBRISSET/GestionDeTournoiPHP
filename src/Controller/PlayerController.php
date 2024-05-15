<?php
/**
 * PHP version 8.2.12
 *
 * @category           Controller
 * @package            App\Controller
 * @Entity
 * @Table(name="user")
 * @author             Tommy Brisset <tommy.brisset@supinfo.com>
 * @license            https://opensource.org/licenses/MIT MIT License
 * @link               src/Controller/PlayerController.php
 */

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerController extends AbstractController
{
    #[Route('/player', name: 'app_player')]
    public function index(): Response
    {
        return $this->render(
            'player/index.html.twig', [
            'controller_name' => 'PlayerController',
            ]
        );
    }

    //GET /api/players : récupère la liste des joueurs
    //Contraintes : Aucune.
    #[Route('/api/players', name: 'app_api_players')]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function getPlayers(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $playerRepository = $entityManager->getRepository(User::class);

        $players = $playerRepository->findAll();

        $json = $serializer->serialize(
            $players, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    //POST /register : créer un utilisateur
    //Contraintes : Les données de l’utilisateur doivent être fournies dans le corps de la requête au format JSON.
    // Au moins, le nom, le prénom, le nom d'utilisateur, l'adresse e-mail et le mot de passe doivent être fournis.
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function register(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['firstName']) || !isset($data['lastName']) || !isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            return $this->json(
                [
                'message' => 'Missing required data',
                ], 400
            );
        }

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $status = 'actif';

        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setUsername($username);
        $user->setEmailAddress($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setStatus($status);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(
            [
            'message' => 'User registered successfully',
            ], 201
        );
    }

    //GET /api/players/{id} : récupère les détails d'un joueur spécifique
    //Contraintes : {id} doit correspondre à un identifiant valide d'un joueur existant.
    #[Route('/api/players/{id}', name: 'app_api_players_id', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function getPlayer(EntityManagerInterface $entityManager, SerializerInterface $serializer, $id): JsonResponse
    {
        $playerRepository = $entityManager->getRepository(User::class);
        $player = $playerRepository->findOneBy(['id' => $id]);

        if (!$player) {
            return $this->json(
                [
                'message' => 'Player not found',
                ], 404
            );
        }

        $json = $serializer->serialize(
            $player, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    //PUT /api/players/{id} : mets à jour les informations d'un joueur spécifique
    //Contraintes : {id} doit correspondre à un identifiant valide d'un joueur existant. Les données mises à jour du
    // joueur doivent être fournies dans le corps de la requête au format JSON.
    #[Route('/api/players/{id}', name: 'app_api_players_id_put', methods: ['PUT'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function putPlayer(int $id, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = $request->getContent();
        $jsondata = json_decode($data, true);

        $firstName = $jsondata['firstName'] ?? null;
        $lastName = $jsondata['lastName'] ?? null;
        $username = $jsondata['username'] ?? null;
        $email = $jsondata['email'] ?? null;
        $password = $jsondata['password'] ?? null;
        $status = $jsondata['status'] ?? null;

        $playerRepository = $entityManager->getRepository(User::class)->find($id);

        if (!$playerRepository) {
            return $this->json(
                [
                'message' => 'Player not found',
                ], 404
            );
        }

        if ($firstName) {
            $playerRepository->setFirstName($firstName);
        }

        if ($lastName) {
            $playerRepository->setLastName($lastName);
        }

        if ($username) {
            $playerRepository->setUsername($username);
        }

        if ($email) {
            $playerRepository->setEmailAddress($email);
        }

        if ($password) {
            $playerRepository->setPassword(password_hash($password, PASSWORD_DEFAULT));
        }

        if ($status) {
            $playerRepository->setStatus($status);
        }

        $entityManager->persist($playerRepository);
        $entityManager->flush();

        return $this->json(
            [
            'message' => 'Player updated successfully',
            ]
        );
    }

    //DELETE /api/players/{id} : Supprime un joueur spécifique
    //Contraintes : {id} doit correspondre à un identifiant valide d'un joueur existant.
    #[Route('/api/players/{id}', name: 'app_api_players_id_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only admin users can access this route', statusCode: 401)]
    public function deletePlayer(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $playerRepository = $entityManager->getRepository(User::class);
        $player = $playerRepository->find($id);

        if (!$player) {
            return $this->json(
                [
                'message' => 'Player not found',
                ], 404
            );
        }

        $entityManager->remove($player);
        $entityManager->flush();

        return $this->json(
            [
            'message' => 'Player deleted successfully',
            ]
        );
    }
}
