<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417150545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration CHANGE status status VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE sport_match CHANGE status status VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE tournament CHANGE location location VARCHAR(100) DEFAULT NULL, CHANGE status status VARCHAR(100) DEFAULT NULL, CHANGE sport sport VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE status status VARCHAR(100) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email_address)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration CHANGE status status VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sport_match CHANGE status status VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE tournament CHANGE location location VARCHAR(100) DEFAULT \'NULL\', CHANGE status status VARCHAR(100) DEFAULT \'NULL\', CHANGE sport sport VARCHAR(100) DEFAULT \'NULL\'');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON `user`');
        $this->addSql('ALTER TABLE `user` CHANGE status status VARCHAR(100) DEFAULT \'NULL\'');
    }
}
