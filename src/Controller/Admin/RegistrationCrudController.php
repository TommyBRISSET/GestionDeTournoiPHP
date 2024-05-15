<?php
/**
 * PHP version 8.2.12
 *
 * @category Controller
 * @package  App\Controller\Admin
 * @author   Tommy Brisset <tommy.brisset@supinfo.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     src/Controller/Admin/RegistrationCrudController.php
 */

namespace App\Controller\Admin;

use App\Entity\Registration;
use App\Entity\Tournament;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RegistrationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Registration::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Registrations')
            ->setEntityLabelInSingular('Registration')
            ->setPageTitle('index', 'Administration - Registrations');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            dateField::new('registrationDate'),
            TextField::new('status'),
            AssociationField::new('player')
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
