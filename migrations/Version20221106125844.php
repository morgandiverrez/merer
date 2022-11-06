<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106125844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_9065174489D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('CREATE INDEX IDX_9065174489D40298 ON invoice (exercice_id)');
        $this->addSql('ALTER TABLE profil CHANGE pronom pronom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD bp_id INT DEFAULT NULL, ADD exercice_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D7EF6F8E FOREIGN KEY (bp_id) REFERENCES bp (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D189D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('CREATE INDEX IDX_723705D1D7EF6F8E ON transaction (bp_id)');
        $this->addSql('CREATE INDEX IDX_723705D189D40298 ON transaction (exercice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_9065174489D40298');
        $this->addSql('DROP INDEX IDX_9065174489D40298 ON invoice');
        $this->addSql('ALTER TABLE profil CHANGE pronom pronom LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D7EF6F8E');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D189D40298');
        $this->addSql('DROP INDEX IDX_723705D1D7EF6F8E ON transaction');
        $this->addSql('DROP INDEX IDX_723705D189D40298 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP bp_id, DROP exercice_id');
    }
}
