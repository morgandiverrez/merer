<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615194321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge ADD extension VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE equipe_elu CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE profil CHANGE pronom pronom TINYTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP extension');
        $this->addSql('ALTER TABLE equipe_elu CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE profil CHANGE pronom pronom VARCHAR(255) DEFAULT NULL');
    }
}
