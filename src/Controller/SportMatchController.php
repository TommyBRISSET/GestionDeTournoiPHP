<?php
/**
 * PHP version 8.2.12
 *
 * @category                  Controller
 * @package                   App\Controller
 * @Entity
 * @Table(name="sport_match")
 * @author                    Tommy Brisset <tommy.brisset@supinfo.com>
 * @license                   https://opensource.org/licenses/MIT MIT License
 * @link                      src/Controller/SportMatchController.php
 */

namespace App\Controller;

use App\Entity\SportMatch;
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

class SportMatchController extends AbstractController
{
    #[Route('/sport/match', name: 'app_sport_match')]
    public function index(): Response
    {
        return $this->render(
            'sport_match/index.html.twig', [
            'controller_name' => 'SportMatchController',
            ]
        );
    }

    //GET /api/tournaments/{id}/sport-matchs : récupère la liste des parties pour un tournoi spécifique
    //contraint : {id} doit correspondre à un identifiant valide d'un tournoi existant.
    #[Route('/api/tournaments/{id}/sport-matchs', name: 'app_api_tournaments_sport_matchs', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function getSportMatchs(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): JsonResponse
    {
        $repository = $entityManager->getRepository(SportMatch::class);
        $sportMatchs = $repository->findBy(['tournament' => $id]);

        if (empty($sportMatchs)) {
            return new JsonResponse(['message' => 'No sport matchs found for the specified tournament.'], 404);
        }

        $jsonContent = $serializer->serialize(
            $sportMatchs, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
            ]
        );

        return new JsonResponse($jsonContent, json: true);
    }

    //POST /api/tournaments/{id}/sport-matchs : crée une nouvelle partie pour un tournoi spécifique
    //Contraintes : {id} doit correspondre à un identifiant valide d'un tournoi existant. Les données du match doivent
    // être fournies dans le corps de la requête au format JSON, contenant les identifiants des joueurs participants et
    //la date du match.
    #[Route('/api/tournaments/{id}/sport-matchs', name: 'app_api_tournaments_sport_matchs_post', methods: ['POST'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function postSportMatchs(EntityManagerInterface $entityManager, Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['player1Id']) || !isset($data['player2Id']) || !isset($data['date'])) {
            return $this->json(
                [
                'message' => 'Missing required data',
                ], 400
            );
        }

        $player1Id = $data['player1Id'];
        $player2Id = $data['player2Id'];
        $date = new \DateTime($data['date']);

        // Check if players exist
        $player1 = $entityManager->getRepository(User::class)->find($player1Id);
        $player2 = $entityManager->getRepository(User::class)->find($player2Id);

        if (!$player1 || !$player2) {
            return $this->json(
                [
                'message' => 'One or both players do not exist',
                ], 404
            );
        }

        // Check if tournament exists
        $tournament = $entityManager->getRepository(Tournament::class)->find($id);

        if (!$tournament) {
            return $this->json(
                [
                    'message' => 'Tournament not found',
                ], 404
            );
        }
        // Check if players are registered in the tournament
        $registrations = $tournament->getRegistrationsTournament();

        // Check if players have confirmed registrations
        $player1Registration = $registrations->filter(
            function ($registration) use ($player1) {
                return $registration->getPlayer() === $player1 && $registration->getStatus() === 'confirmé';
            }
        );

        $player2Registration = $registrations->filter(
            function ($registration) use ($player2) {
                return $registration->getPlayer() === $player2 && $registration->getStatus() === 'confirmé';
            }
        );

        if (count($player1Registration) === 0 || count($player2Registration) === 0) {
            return $this->json(
                [
                    'message' => 'One or both players do not have confirmed registrations',
                ], 400
            );
        }

        $sportMatch = new SportMatch();
        $sportMatch->setPlayer1($player1);
        $sportMatch->setPlayer2($player2);
        $sportMatch->setMatchDate($date);
        $sportMatch->setTournament($tournament);

        $entityManager->persist($sportMatch);
        $entityManager->flush();

        return $this->json(
            [
            'message' => 'Sport match created successfully',
            ], 201
        );
    }

    //GET /api/tournaments/{idTournament}/sport-matchs/{idSportMatchs} : récupère les détails d'une partie spécifique
    //Contraintes : {idTournament} doit correspondre à un identifiant valide d'un tournoi existant. {idSportMatchs}
    // dois correspondre à un identifiant valide d'une partie pour ce tournoi.
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'app_api_tournaments_sport_matchs_id', methods: ['GET'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function getSportMatch(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $idTournament, int $idSportMatchs): JsonResponse
    {
        $repository = $entityManager->getRepository(SportMatch::class);
        $sportMatch = $repository->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        if (!$sportMatch) {
            return new JsonResponse(['message' => 'Sport match not found.'], 404);
        }

        $jsonContent = $serializer->serialize(
            $sportMatch, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
            ]
        );

        return new JsonResponse($jsonContent, json: true);
    }

    //PUT /api/tournaments/{idTournament}/sport-matchs/{idSportMatchs} :
    // mets à jour les résultats d'une partie spécifique
    //Contraintes : {idTournament} doit correspondre à un identifiant valide d'un tournoi existant. {idSportMatchs}
    // dois correspondre à un identifiant valide d'une partie pour ce tournoi. Les données mises à jour de la partie
    // doivent être fournies dans le corps de la requête au format JSON, contenant les scores des joueurs.
    //Attention lors de la mise à jour des scores, seul le joueur concerné peut mettre son score à jour, ou une admin.
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'app_api_tournaments_sport_matchs_id_put', methods: ['PUT'])]
    #[IsGranted('ROLE_USER', message: 'Only authenticated users can access this route', statusCode: 401)]
    public function putSportMatch(int $idTournament, int $idSportMatchs, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['playerId']) || !isset($data['score'])) {
            return $this->json(
                [
                'message' => 'Missing required data',
                ], 400
            );
        }

        $playerId = $data['playerId'];
        $score = $data['score'];

        $sportMatch = $entityManager->getRepository(SportMatch::class)->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        if (!$sportMatch) {
            return $this->json(
                [
                'message' => 'Sport match not found',
                ], 404
            );
        }

        $player1 = $sportMatch->getPlayer1();
        $player2 = $sportMatch->getPlayer2();

        if ($player1->getId() != $playerId && $player2->getId() != $playerId) {
            return $this->json(
                [
                'message' => 'You are not allowed to update this sport match',
                ], 403
            );
        }

        if ($player1->getId() == $playerId) {
            $sportMatch->setScorePlayer1($score);
        } else {
            $sportMatch->setScorePlayer2($score);
        }

        $entityManager->flush();

        return $this->json(
            [
            'message' => 'Sport match updated successfully',
            ]
        );
    }

    //DELETE /api/tournaments/{idTournament}/sport-matchs/{idSportMatchs} : Supprime une partie spécifique
    //Contraintes : {idTournament} doit correspondre à un identifiant valide d'un tournoi existant. {idSportMatchs}
    //dois correspondre à un identifiant valide d'une partie pour ce tournoi.
    #[Route('/api/tournaments/{idTournament}/sport-matchs/{idSportMatchs}', name: 'app_api_tournaments_sport_matchs_id_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only admin users can access this route', statusCode: 401)]
    public function deleteSportMatch(int $idTournament, int $idSportMatchs, EntityManagerInterface $entityManager): JsonResponse
    {
        $sportMatch = $entityManager->getRepository(SportMatch::class)->findOneBy(['tournament' => $idTournament, 'id' => $idSportMatchs]);

        if (!$sportMatch) {
            return $this->json(
                [
                'message' => 'Sport match not found',
                ], 404
            );
        }

        $entityManager->remove($sportMatch);
        $entityManager->flush();

        return $this->json(
            [
            'message' => 'Sport match deleted successfully',
            ]
        );
    }
}
