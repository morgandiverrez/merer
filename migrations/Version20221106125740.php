<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106125740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bp (id INT AUTO_INCREMENT NOT NULL, exercice_id INT NOT NULL, categorie VARCHAR(255) NOT NULL, designation VARCHAR(1024) NOT NULL, expected_amount DOUBLE PRECISION NOT NULL, reallocate_amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_46176AE689D40298 (exercice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bp ADD CONSTRAINT FK_46176AE689D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE event ADD exercice_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA789D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA789D40298 ON event (exercice_id)');
        $this->addSql('ALTER TABLE expense_report ADD exercice_id INT NOT NULL');
        $this->addSql('ALTER TABLE expense_report ADD CONSTRAINT FK_280A69189D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('CREATE INDEX IDX_280A69189D40298 ON expense_report (exercice_id)');
        $this->addSql('ALTER TABLE invoice ADD exercice_id INT NOT NULL');
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
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D7EF6F8E');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA789D40298');
        $this->addSql('ALTER TABLE expense_report DROP FOREIGN KEY FK_280A69189D40298');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_9065174489D40298');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D189D40298');
        $this->addSql('ALTER TABLE bp DROP FOREIGN KEY FK_46176AE689D40298');
        $this->addSql('DROP TABLE bp');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP INDEX IDX_3BAE0AA789D40298 ON event');
        $this->addSql('ALTER TABLE event DROP exercice_id');
        $this->addSql('DROP INDEX IDX_280A69189D40298 ON expense_report');
        $this->addSql('ALTER TABLE expense_report DROP exercice_id');
        $this->addSql('DROP INDEX IDX_9065174489D40298 ON invoice');
        $this->addSql('ALTER TABLE invoice DROP exercice_id');
        $this->addSql('ALTER TABLE profil CHANGE pronom pronom LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('DROP INDEX IDX_723705D1D7EF6F8E ON transaction');
        $this->addSql('DROP INDEX IDX_723705D189D40298 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP bp_id, DROP exercice_id');
    }
}
