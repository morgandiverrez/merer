<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221127130002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_report DROP document');
        $this->addSql('ALTER TABLE expense_report_line ADD document VARCHAR(1024) DEFAULT NULL');
        $this->addSql('ALTER TABLE repay_grid ADD distance DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_report ADD document VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE expense_report_line DROP document');
        $this->addSql('ALTER TABLE repay_grid DROP distance');
    }
}
