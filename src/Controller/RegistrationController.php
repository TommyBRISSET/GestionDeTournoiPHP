<?php
/**
 * PHP version 8.2.12
 *
 * @category                   Controller
 * @package                    App\Controller
 * @Entity
 * @Table(name="registration")
 * @author                     Tommy Brisset <tommy.brisset@supinfo.com>
 * @license                    https://opensource.org/licenses/MIT MIT License
 * @link                       src/Controller/RegistrationController.php
 */

namespace App\Controller;

use App\Entity\Registration;
use App\Entity\Tournament;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration')]
    public function index(): Response
    {
        return $this->render(
            'registration/index.html.twig', [
            'controller_name' => 'RegistrationController',
            ]
        );
    }

    //GET /api/tournaments/{id}/registrations : récupère la liste des inscriptions pour un tournoi spécifique
    //Contraintes : {id} doit correspondre à un identifiant valide d'un tournoi existant.
    #[Route('/api/tournaments/{id}/registrations', name: 'app_api_tournaments_registrations', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function getRegistrations(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): JsonResponse
    {
        $repository = $entityManager->getRepository(Registration::class);
        $registrations = $repository->findBy(['tournament' => $id]);

        if (empty($registrations)) {
            return new JsonResponse(['message' => 'No registrations found for the specified tournament.'], 404);
        }

        $jsonContent = $serializer->serialize(
            $registrations, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
            ]
        );

        return new JsonResponse($jsonContent, json: true);
    }

    //POST /api/tournaments/{id}/registrations : inscris un joueur à un tournoi spécifique
    //Contraintes : {id} doit correspondre à un identifiant valide d'un tournoi existant. Les données de l'inscription
    // doivent être fournies dans le corps de la requête au format JSON, contenant l'identifiant du joueur à inscrire.
    #[Route('/api/tournaments/{id}/registrations', name: 'app_api_tournaments_registrations_post', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function postRegistrations(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['playerId'])) {
            return $this->json(
                [
                'message' => 'Missing required data',
                ], 400
            );
        }

        $playerId = $data['playerId'];

        // Check if the tournament exists
        $tournamentRepository = $entityManager->getRepository(Tournament::class);
        $tournament = $tournamentRepository->find($id);

        if (!$tournament) {
            return new JsonResponse(['message' => 'Tournament not found.'], 404);
        }

        // Check if the player exists
        $playerRepository = $entityManager->getRepository(User::class);
        $player = $playerRepository->find($playerId);

        if (!$player) {
            return new JsonResponse(['message' => 'Player not found.'], 404);
        }

        // Check if the player is already registered
        $existingRegistration = $entityManager->getRepository(Registration::class)->findOneBy(
            [
            'tournament' => $tournament,
            'player' => $player,
            ]
        );

        if ($existingRegistration) {
            return new JsonResponse(['message' => 'Player is already registered for this tournament.'], 400);
        }

        $registration = new Registration();
        $registration->setTournament($tournament);
        $registration->setRegistrationDate(new \DateTime());
        $registration->setStatus('inscrit');
        $registration->setPlayer($player);

        $entityManager->persist($registration);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Player successfully registered for the tournament.'], 201);
    }

    //DELETE /api/tournaments/{idTournament}/registrations/{idRegistration} :
    // Annule l'inscription d'un joueur à un tournoi spécifique
    //contraint : {idTournament} doit correspondre à un identifiant valide d'un tournoi existant. {idRegistration}
    // dois correspondre à un identifiant valide d'une inscription existante pour ce tournoi.
    #[Route('/api/tournaments/{idTournament}/registrations/{idRegistration}', name: 'app_api_tournaments_registrations_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function deleteRegistration(EntityManagerInterface $entityManager, int $idTournament, int $idRegistration): JsonResponse
    {
        $registrationRepository = $entityManager->getRepository(Registration::class);
        $registration = $registrationRepository->find($idRegistration);

        if (!$registration) {
            return new JsonResponse(['message' => 'Registration not found.'], 404);
        }

        $tournamentRepository = $entityManager->getRepository(Tournament::class);
        $tournament = $tournamentRepository->find($idTournament);

        if (!$tournament) {
            return new JsonResponse(['message' => 'Tournament not found.'], 404);
        }

        if ($registration->getTournament() !== $tournament) {
            return new JsonResponse(['message' => 'Registration does not belong to the specified tournament.'], 400);
        }

        $entityManager->remove($registration);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Registration successfully deleted.']);
    }
}
