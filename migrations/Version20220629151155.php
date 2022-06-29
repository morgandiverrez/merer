<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629151155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481D5200282E');
        $this->addSql('DROP INDEX IDX_FEF0481D5200282E ON badge');
        $this->addSql('ALTER TABLE badge DROP formation_id');
        $this->addSql('ALTER TABLE formation ADD badge_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFF7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id)');
        $this->addSql('CREATE INDEX IDX_404021BFF7A2C2FC ON formation (badge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge ADD formation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481D5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('CREATE INDEX IDX_FEF0481D5200282E ON badge (formation_id)');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFF7A2C2FC');
        $this->addSql('DROP INDEX IDX_404021BFF7A2C2FC ON formation');
        $this->addSql('ALTER TABLE formation DROP badge_id');
    }
}
