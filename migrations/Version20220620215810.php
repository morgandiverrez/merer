<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620215810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) DEFAULT NULL, CHANGE extension image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE badge DROP extension');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association CHANGE sigle sigle VARCHAR(255) NOT NULL, CHANGE image extension VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE badge ADD extension VARCHAR(255) DEFAULT NULL');
    }
}
