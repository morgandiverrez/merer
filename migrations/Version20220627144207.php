<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627144207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance_profil ADD lieu_id INT NOT NULL, ADD attente VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE seance_profil ADD CONSTRAINT FK_99A3A93F6AB213CC FOREIGN KEY (lieu_id) REFERENCES lieux (id)');
        $this->addSql('CREATE INDEX IDX_99A3A93F6AB213CC ON seance_profil (lieu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance_profil DROP FOREIGN KEY FK_99A3A93F6AB213CC');
        $this->addSql('DROP INDEX IDX_99A3A93F6AB213CC ON seance_profil');
        $this->addSql('ALTER TABLE seance_profil DROP lieu_id, DROP attente');
    }
}
