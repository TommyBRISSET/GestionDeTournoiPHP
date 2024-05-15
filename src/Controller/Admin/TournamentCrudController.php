<?php
/**
 * PHP version 8.2.12
 *
 * @category Controller
 * @package  App\Controller\Admin
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Controller/Admin/TournamentCrudController.php
 */

namespace App\Controller\Admin;

use App\Entity\Tournament;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TournamentCrudController extends AbstractCrudController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Tournament::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Tournaments')
            ->setEntityLabelInSingular('Tournament')
            ->setPageTitle('index', 'Administration - Tournaments');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('TournamentName'),
            DateField::new('startDate'),
            DateField::new('endDate'),
            TextField::new('location'),
            IntegerField::new('maxParticipants'),
            TextField::new('status'),
            AssociationField::new('organizer')
                ->formatValue(
                    function ($value, $entity) {
                        if ($value instanceof User) {
                            return $value->getUsername() ?? 'Utilisateur inconnu';
                        }

                        return 'Utilisateur inconnu';
                    }
                ),
            AssociationField::new('winner')
                ->formatValue(
                    function ($value, $entity) {
                        if ($value instanceof User) {
                            return $value->getUsername() ?? 'Utilisateur inconnu';
                        }

                        return 'Aucun vainqueur';
                    }
                ),
        ];
    }
}
