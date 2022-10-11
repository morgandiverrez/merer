<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007103011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalog_service ADD code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD association_id INT DEFAULT NULL, ADD code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_90651744EFB9C8A5 ON invoice (association_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalog_service DROP code');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744EFB9C8A5');
        $this->addSql('DROP INDEX IDX_90651744EFB9C8A5 ON invoice');
        $this->addSql('ALTER TABLE invoice DROP association_id, DROP code');
    }
}
