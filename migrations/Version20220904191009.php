<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220904191009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance_profil DROP autorisation_photo, DROP mode_paiement, DROP covoiturage');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance_profil ADD autorisation_photo TINYINT(1) DEFAULT NULL, ADD mode_paiement VARCHAR(255) DEFAULT NULL, ADD covoiturage TINYINT(1) DEFAULT NULL');
    }
}
