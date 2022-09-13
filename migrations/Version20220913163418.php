<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220913163418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande ADD name VARCHAR(255) DEFAULT NULL, DROP name_demandeur, DROP sur_name_demandeur, DROP mail_demandeur, DROP telephone_demandeur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande ADD sur_name_demandeur VARCHAR(255) DEFAULT NULL, ADD mail_demandeur VARCHAR(255) DEFAULT NULL, ADD telephone_demandeur VARCHAR(255) DEFAULT NULL, CHANGE name name_demandeur VARCHAR(255) DEFAULT NULL');
    }
}
