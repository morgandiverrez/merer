<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006210733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalog_discount (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(1024) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, amount_ht DOUBLE PRECISION NOT NULL, tva_rate DOUBLE PRECISION NOT NULL, amount_ttc DOUBLE PRECISION NOT NULL, description VARCHAR(1024) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE impression (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, invoice_id INT DEFAULT NULL, datetime DATETIME NOT NULL, name VARCHAR(255) DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, recto_verso TINYINT(1) DEFAULT NULL, couleur TINYINT(1) DEFAULT NULL, quantite INT NOT NULL, facture_fin_du_mois TINYINT(1) NOT NULL, deja_paye TINYINT(1) NOT NULL, INDEX IDX_245BB1B1EFB9C8A5 (association_id), INDEX IDX_245BB1B12989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, creation_date DATE NOT NULL, acquitted TINYINT(1) DEFAULT NULL, ready TINYINT(1) DEFAULT NULL, confirm TINYINT(1) DEFAULT NULL, credit TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE invoice_line (id INT AUTO_INCREMENT NOT NULL, invoice_id INT DEFAULT NULL, catalog_discount_id INT DEFAULT NULL, catalog_service_id INT DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D3D1D6932989F1FD (invoice_id), INDEX IDX_D3D1D6935DC840B1 (catalog_discount_id), INDEX IDX_D3D1D69358765B3D (catalog_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_deadline (id INT AUTO_INCREMENT NOT NULL, invoice_id INT DEFAULT NULL, expected_payment_date DATE NOT NULL, extpected_amount DOUBLE PRECISION NOT NULL, expected_means VARCHAR(255) NOT NULL, actual_payment_date DATE NOT NULL, actual_amount DOUBLE PRECISION DEFAULT NULL, actual_means VARCHAR(255) DEFAULT NULL, INDEX IDX_F781369A2989F1FD (invoice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE impression ADD CONSTRAINT FK_245BB1B1EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('ALTER TABLE impression ADD CONSTRAINT FK_245BB1B12989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D6932989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D6935DC840B1 FOREIGN KEY (catalog_discount_id) REFERENCES catalog_discount (id)');
        $this->addSql('ALTER TABLE invoice_line ADD CONSTRAINT FK_D3D1D69358765B3D FOREIGN KEY (catalog_service_id) REFERENCES catalog_service (id)');
        $this->addSql('ALTER TABLE payment_deadline ADD CONSTRAINT FK_F781369A2989F1FD FOREIGN KEY (invoice_id) REFERENCES invoice (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D6935DC840B1');
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D69358765B3D');
        $this->addSql('ALTER TABLE impression DROP FOREIGN KEY FK_245BB1B12989F1FD');
        $this->addSql('ALTER TABLE invoice_line DROP FOREIGN KEY FK_D3D1D6932989F1FD');
        $this->addSql('ALTER TABLE payment_deadline DROP FOREIGN KEY FK_F781369A2989F1FD');
        $this->addSql('DROP TABLE catalog_discount');
        $this->addSql('DROP TABLE catalog_service');
        $this->addSql('DROP TABLE impression');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_line');
        $this->addSql('DROP TABLE payment_deadline');
    }
}
