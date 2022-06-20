<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620104503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE association CHANGE mail adresse_mail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE equipe_elu CHANGE mail adresse_mail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE identification ADD identifiant VARCHAR(255) NOT NULL, DROP email, DROP username, DROP date_creation');
        $this->addSql('ALTER TABLE lieux DROP description');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE association CHANGE adresse_mail mail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE equipe_elu CHANGE adresse_mail mail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE identification ADD username VARCHAR(255) NOT NULL, ADD date_creation DATETIME DEFAULT NULL, CHANGE identifiant email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lieux ADD description VARCHAR(255) DEFAULT NULL');
    }
}
