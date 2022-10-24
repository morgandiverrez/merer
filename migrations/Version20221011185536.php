<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221011185536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chart_of_accounts (id INT AUTO_INCREMENT NOT NULL, code INT NOT NULL, name VARCHAR(1024) NOT NULL, movable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE federation (id INT AUTO_INCREMENT NOT NULL, social_reason VARCHAR(255) DEFAULT NULL, statutory_object VARCHAR(2048) DEFAULT NULL, creation_date DATE DEFAULT NULL, represented_by VARCHAR(255) DEFAULT NULL, rna VARCHAR(15) DEFAULT NULL, vat_number VARCHAR(15) DEFAULT NULL, currency VARCHAR(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, code BIGINT NOT NULL, closure TINYINT(1) NOT NULL, quote VARCHAR(1024) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_line (id INT AUTO_INCREMENT NOT NULL, transaction_id INT DEFAULT NULL, chart_of_accounts_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, date DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, url_proof VARCHAR(255) NOT NULL, quote VARCHAR(1024) DEFAULT NULL, INDEX IDX_33578A572FC0CB0F (transaction_id), INDEX IDX_33578A57196B9FF2 (chart_of_accounts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction_line ADD CONSTRAINT FK_33578A572FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE transaction_line ADD CONSTRAINT FK_33578A57196B9FF2 FOREIGN KEY (chart_of_accounts_id) REFERENCES chart_of_accounts (id)');
        $this->addSql('ALTER TABLE invoice ADD transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517442FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('CREATE INDEX IDX_906517442FC0CB0F ON invoice (transaction_id)');
        $this->addSql('ALTER TABLE profil CHANGE pronom pronom VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517442FC0CB0F');
        $this->addSql('ALTER TABLE transaction_line DROP FOREIGN KEY FK_33578A572FC0CB0F');
        $this->addSql('ALTER TABLE transaction_line DROP FOREIGN KEY FK_33578A57196B9FF2');
        $this->addSql('DROP TABLE chart_of_accounts');
        $this->addSql('DROP TABLE federation');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_line');
        $this->addSql('DROP INDEX IDX_906517442FC0CB0F ON invoice');
        $this->addSql('ALTER TABLE invoice DROP transaction_id');
        $this->addSql('ALTER TABLE profil CHANGE pronom pronom LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
