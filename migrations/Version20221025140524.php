<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025140524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE expense_report_line (id INT AUTO_INCREMENT NOT NULL, expense_report_id INT NOT NULL, date DATE NOT NULL, object VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_DB54F5968F758FBA (expense_report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense_report_route_line (id INT AUTO_INCREMENT NOT NULL, expense_report_id INT NOT NULL, repay_grid_id INT DEFAULT NULL, date DATE NOT NULL, start VARCHAR(255) NOT NULL, end VARCHAR(255) NOT NULL, distance DOUBLE PRECISION NOT NULL, travel_means VARCHAR(255) NOT NULL, amount VARCHAR(255) NOT NULL, INDEX IDX_87B5163B8F758FBA (expense_report_id), INDEX IDX_87B5163BC4B18AE7 (repay_grid_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repay_grid (id INT AUTO_INCREMENT NOT NULL, travel_mean VARCHAR(255) NOT NULL, start VARCHAR(255) DEFAULT NULL, end VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE expense_report_line ADD CONSTRAINT FK_DB54F5968F758FBA FOREIGN KEY (expense_report_id) REFERENCES expense_report (id)');
        $this->addSql('ALTER TABLE expense_report_route_line ADD CONSTRAINT FK_87B5163B8F758FBA FOREIGN KEY (expense_report_id) REFERENCES expense_report (id)');
        $this->addSql('ALTER TABLE expense_report_route_line ADD CONSTRAINT FK_87B5163BC4B18AE7 FOREIGN KEY (repay_grid_id) REFERENCES repay_grid (id)');
        $this->addSql('ALTER TABLE expense_report ADD transaction_id INT DEFAULT NULL, ADD customer_id INT NOT NULL, ADD comfirm TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE expense_report ADD CONSTRAINT FK_280A6912FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE expense_report ADD CONSTRAINT FK_280A6919395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_280A6912FC0CB0F ON expense_report (transaction_id)');
        $this->addSql('CREATE INDEX IDX_280A6919395C3F3 ON expense_report (customer_id)');
        $this->addSql('ALTER TABLE impression DROP deja_paye');
        $this->addSql('ALTER TABLE institution ADD federation_id INT DEFAULT NULL, ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE institution ADD CONSTRAINT FK_3A9F98E56A03EFC5 FOREIGN KEY (federation_id) REFERENCES federation (id)');
        $this->addSql('ALTER TABLE institution ADD CONSTRAINT FK_3A9F98E564D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE INDEX IDX_3A9F98E56A03EFC5 ON institution (federation_id)');
        $this->addSql('CREATE INDEX IDX_3A9F98E564D218E ON institution (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_report_line DROP FOREIGN KEY FK_DB54F5968F758FBA');
        $this->addSql('ALTER TABLE expense_report_route_line DROP FOREIGN KEY FK_87B5163B8F758FBA');
        $this->addSql('ALTER TABLE expense_report_route_line DROP FOREIGN KEY FK_87B5163BC4B18AE7');
        $this->addSql('DROP TABLE expense_report_line');
        $this->addSql('DROP TABLE expense_report_route_line');
        $this->addSql('DROP TABLE repay_grid');
        $this->addSql('ALTER TABLE expense_report DROP FOREIGN KEY FK_280A6912FC0CB0F');
        $this->addSql('ALTER TABLE expense_report DROP FOREIGN KEY FK_280A6919395C3F3');
        $this->addSql('DROP INDEX IDX_280A6912FC0CB0F ON expense_report');
        $this->addSql('DROP INDEX IDX_280A6919395C3F3 ON expense_report');
        $this->addSql('ALTER TABLE expense_report DROP transaction_id, DROP customer_id, DROP comfirm');
        $this->addSql('ALTER TABLE impression ADD deja_paye TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE institution DROP FOREIGN KEY FK_3A9F98E56A03EFC5');
        $this->addSql('ALTER TABLE institution DROP FOREIGN KEY FK_3A9F98E564D218E');
        $this->addSql('DROP INDEX IDX_3A9F98E56A03EFC5 ON institution');
        $this->addSql('DROP INDEX IDX_3A9F98E564D218E ON institution');
        $this->addSql('ALTER TABLE institution DROP federation_id, DROP location_id');
    }
}
