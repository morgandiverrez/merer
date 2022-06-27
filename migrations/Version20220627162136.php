<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627162136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE retour CHANGE note_contenu note_contenu INT NOT NULL, CHANGE note_animation note_animation INT NOT NULL, CHANGE note_implication note_implication INT NOT NULL, CHANGE note_reponse_atente note_reponse_atente INT NOT NULL, CHANGE note_niv_competence note_niv_competence INT NOT NULL, CHANGE note_utilite note_utilite INT NOT NULL, CHANGE note_generale note_generale INT NOT NULL, CHANGE apport_generale apport_generale VARCHAR(255) NOT NULL, CHANGE plus_aimer plus_aimer VARCHAR(255) NOT NULL, CHANGE moins_aimer moins_aimer VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE retour CHANGE note_contenu note_contenu INT DEFAULT NULL, CHANGE note_animation note_animation INT DEFAULT NULL, CHANGE note_implication note_implication VARCHAR(255) DEFAULT NULL, CHANGE note_reponse_atente note_reponse_atente INT DEFAULT NULL, CHANGE note_niv_competence note_niv_competence INT DEFAULT NULL, CHANGE note_utilite note_utilite INT DEFAULT NULL, CHANGE note_generale note_generale INT DEFAULT NULL, CHANGE apport_generale apport_generale VARCHAR(255) DEFAULT NULL, CHANGE plus_aimer plus_aimer VARCHAR(255) DEFAULT NULL, CHANGE moins_aimer moins_aimer VARCHAR(255) DEFAULT NULL');
    }
}
