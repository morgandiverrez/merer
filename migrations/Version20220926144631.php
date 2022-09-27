<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220926144631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE impression');
        $this->addSql('ALTER TABLE profil DROP regime_alimentaire, DROP allergie_alimentaire, DROP we2_f');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE impression (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, format VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, recto_verso TINYINT(1) DEFAULT NULL, couleur TINYINT(1) DEFAULT NULL, quantité INT NOT NULL, fin_du_mois TINYINT(1) NOT NULL, deja_paye TINYINT(1) NOT NULL, INDEX IDX_245BB1B1EFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE impression ADD CONSTRAINT FK_245BB1B1EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE profil ADD regime_alimentaire LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD allergie_alimentaire VARCHAR(255) DEFAULT NULL, ADD we2_f DATE DEFAULT NULL');
    }
}
