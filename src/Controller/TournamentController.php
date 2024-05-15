<?php
/**
 * PHP version 8.2.12
 *
 * @category                 Controller
 * @package                  App\Controller
 * @Entity
 * @Table(name="tournament")
 * @author                   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license                  https://opensource.org/licenses/MIT MIT License
 * @link                     src/Controller/TournamentController.php
 */

namespace App\Controller;

use App\Entity\Tournament;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class TournamentController extends AbstractController
{
    #[Route('/p/h/p/sport', name: 'app_p_h_p_sport')]
    public function index(): Response
    {
        return $this->render(
            'php_sport/index.html.twig', [
            'controller_name' => 'TournamentController',
            ]
        );
    }

    //GET /api/tournaments : récupère la liste des tournois
    //Contraintes : Aucune.
    #[Route('/api/tournaments', name: 'app_api_tournaments', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function getTournaments(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $repository = $entityManager->getRepository(Tournament::class);
        $tournament = $repository->findAll();

        $jsonContent = $serializer->serialize(
            $tournament, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
            ]
        );

        return new JsonResponse($jsonContent, json: true);
    }

    //POST /api/tournaments : crée un nouveau tournoi
    //Contraintes : Les données du tournoi doivent être fournies dans le corps de la requête au format JSON.
    // Au moins, le nom du tournoi, la date de début, la date de fin et la description doivent être fournis.
    #[Route('/api/tournaments', name: 'app_api_tournaments_post', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function postTournaments(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['startDate']) || !isset($data['endDate']) || !isset($data['description'])) {
            return $this->json(
                [
                'message' => 'Missing required data',
                ], 400
            );
        }

        $name = $data['name'];
        $startDate = new DateTime($data['startDate']);
        $endDate = new DateTime($data['endDate']);
        $description = $data['description'];
        $maxParticipants = $data['maxParticipants'] ?? null;
        $status = $data['status'] ?? null;
        $sport = $data['sport'] ?? null;
        $organizerName = $data['organizer'] ?? null;


        $tournament = new Tournament();
        $tournament->setTournamentName($name);
        $tournament->setStartDate($startDate);
        $tournament->setEndDate($endDate);
        $tournament->setDescription($description);
        $tournament->setMaxParticipants($maxParticipants);
        $tournament->setSport($sport);
        $tournament->setOrganizer($organizerName);

        $today = new DateTime();
        if ($today < $tournament->getStartDate()) {
            $tournament->setStatus('À venir');
        } elseif ($today > $tournament->getEndDate()) {
            $tournament->setStatus('Terminé');
        } else {
            $tournament->setStatus('En cours');
        }

        $entityManager->persist($tournament);
        $entityManager->flush();

        return $this->json(
            [
            'message' => 'Tournament created',
            'id' => $tournament->getId()
            ]
        );
    }

    //GET /api/tournaments/{id} : récupère les détails d'un tournoi spécifique
    // Contraintes : {id} doit correspondre à un identifiant valide d'un tournoi existant.
    #[Route('/api/tournaments/{id}', name: 'app_api_tournaments_id', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function getTournament(EntityManagerInterface $entityManager, SerializerInterface $serializer, $id): JsonResponse
    {
        $repository = $entityManager->getRepository(Tournament::class);
        $tournament = $repository->findOneBy(['id' => $id]);

        if (!$tournament) {
            return $this->json(
                [
                'message' => 'Tournament not found',
                ], 404
            );
        }

        $jsonContent = $serializer->serialize(
            $tournament, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
            ]
        );

        return new JsonResponse($jsonContent, json: true);
    }

    //PUT /api/tournaments/{id} : mets à jour les informations d'un tournoi spécifique
    //Contraintes : {id} doit correspondre à un identifiant valide d'un tournoi existant.
    // Les données mises à jour du tournoi doivent être fournies dans le corps de la requête au format JSON.
    #[Route('/api/tournaments/{id}', name: 'app_api_tournaments_id_put', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only admin users can access this route', statusCode: 401)]
    public function putTournament(int $id, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = $request->getContent();
        $jsondata = json_decode($data, true);

        $tournamentName = $jsondata['name'] ?? null;
        $startDate = $jsondata['startDate'] ?? null;
        $endDate = $jsondata['endDate'] ?? null;
        $description = $jsondata['description'] ?? null;
        $maxParticipants = $jsondata['maxParticipants'] ?? null;


        $tournamentRepository = $entityManager->getRepository(Tournament::class)->find($id);

        if (!$tournamentRepository) {
            return $this->json(
                [
                'message' => 'Tournament not found',
                ], 404
            );
        }

        if ($tournamentName) {
            $tournamentRepository->setTournamentName($tournamentName);
        }
        if ($startDate) {
            $tournamentRepository->setStartDate(new DateTime($startDate));
        }
        if ($endDate) {
            $tournamentRepository->setEndDate(new DateTime($endDate));
        }
        if ($description) {
            $tournamentRepository->setDescription($description);
        }
        if ($maxParticipants) {
            $tournamentRepository->setMaxParticipants($maxParticipants);
        }

        $entityManager->persist($tournamentRepository);
        $entityManager->flush();

        return $this->json(
            [
            'message' => 'Tournament updated',
            ]
        );
    }

    //DELETE /api/tournaments/{id} : Supprime un tournoi spécifique
    //Contraintes : {id} doit correspondre à un identifiant valide d'un tournoi existant.
    #[Route('/api/tournaments/{id}', name: 'app_api_tournaments_id_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only admin users can access this route', statusCode: 401)]
    public function deleteTournament(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $tournamentRepository = $entityManager->getRepository(Tournament::class);
        $tournament = $tournamentRepository->find($id);

        if (!$tournament) {
            return $this->json(
                [
                'message' => 'Tournament not found',
                ], 404
            );
        }

        $entityManager->remove($tournament);
        $entityManager->flush();

        return $this->json(
            [
            'message' => 'Tournament deleted',
            ]
        );
    }
}
