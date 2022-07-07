<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707142333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil ADD regime_alimentaire LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD allergie_alimentaire VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE seance_profil ADD autorisation_photo TINYINT(1) DEFAULT NULL, ADD mode_paiement VARCHAR(255) DEFAULT NULL, ADD covoiturage TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil DROP regime_alimentaire, DROP allergie_alimentaire');
        $this->addSql('ALTER TABLE seance_profil DROP autorisation_photo, DROP mode_paiement, DROP covoiturage');
    }
}
