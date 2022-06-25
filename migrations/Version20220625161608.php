<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220625161608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seance_lieux (seance_id INT NOT NULL, lieux_id INT NOT NULL, INDEX IDX_8152BBE2E3797A94 (seance_id), INDEX IDX_8152BBE2A2C806AC (lieux_id), PRIMARY KEY(seance_id, lieux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seance_lieux ADD CONSTRAINT FK_8152BBE2E3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seance_lieux ADD CONSTRAINT FK_8152BBE2A2C806AC FOREIGN KEY (lieux_id) REFERENCES lieux (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE lieux_seance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lieux_seance (lieux_id INT NOT NULL, seance_id INT NOT NULL, INDEX IDX_34794923A2C806AC (lieux_id), INDEX IDX_34794923E3797A94 (seance_id), PRIMARY KEY(lieux_id, seance_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lieux_seance ADD CONSTRAINT FK_34794923A2C806AC FOREIGN KEY (lieux_id) REFERENCES lieux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lieux_seance ADD CONSTRAINT FK_34794923E3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE seance_lieux');
    }
}
