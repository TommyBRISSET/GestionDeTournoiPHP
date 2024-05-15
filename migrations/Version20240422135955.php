<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422135955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration CHANGE status status VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE sport_match CHANGE score_player1 score_player1 INT DEFAULT NULL, CHANGE score_player2 score_player2 INT DEFAULT NULL, CHANGE status status VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE tournament CHANGE location location VARCHAR(100) DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE max_participants max_participants INT DEFAULT NULL, CHANGE status status VARCHAR(100) DEFAULT NULL, CHANGE sport sport VARCHAR(100) DEFAULT NULL, CHANGE organizer_id organizer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, CHANGE status status VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration CHANGE status status VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sport_match CHANGE score_player1 score_player1 INT NOT NULL, CHANGE score_player2 score_player2 INT NOT NULL, CHANGE status status VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tournament CHANGE location location VARCHAR(100) DEFAULT \'NULL\', CHANGE description description LONGTEXT NOT NULL, CHANGE max_participants max_participants INT NOT NULL, CHANGE status status VARCHAR(100) DEFAULT \'NULL\', CHANGE sport sport VARCHAR(100) DEFAULT \'NULL\', CHANGE organizer_id organizer_id INT NOT NULL');
        $this->addSql('ALTER TABLE `user` DROP roles, CHANGE status status VARCHAR(100) DEFAULT \'NULL\'');
    }
}
