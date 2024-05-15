<?php
/**
 * PHP version 8.2.12
 *
 * @category Controller
 * @package  App\Controller\Admin
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Controller/Admin/SportMatchCrudController.php
 */

namespace App\Controller\Admin;

use App\Entity\SportMatch;
use App\Entity\Tournament;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Date;

class SportMatchCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SportMatch::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('SportMatches')
            ->setEntityLabelInSingular('SportMatch')
            ->setPageTitle('index', 'Administration - SportMatches');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            dateField::new('matchDate'),
            IntegerField::new('scorePlayer1'),
            IntegerField::new('scorePlayer2'),
            TextField::new('status'),
            AssociationField::new('player1')
                ->formatValue(
                    function ($value, $entity) {
                        if ($value instanceof User) {
                            return $value->getFirstName() . ' ' . $value->getLastName();
                        }
                        return 'Utilisateur inconnu';
                    }
                ),
            AssociationField::new('player2')
                ->formatValue(
                    function ($value, $entity) {
                        if ($value instanceof User) {
                            return $value->getFirstName() . ' ' . $value->getLastName();
                        }
                        return 'Utilisateur inconnu';
                    }
                ),
            AssociationField::new('tournament')
                ->formatValue(
                    function ($value, $entity) {
                        if ($value instanceof Tournament) {
                            return $value->getTournamentName();
                        }
                        return 'Tournoi inconnu';
                    }
                ),
        ];
    }
}
