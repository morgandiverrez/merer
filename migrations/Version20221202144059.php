<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221202144059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account CHANGE account_number account_number INT DEFAULT NULL, CHANGE rib_bank_code rib_bank_code INT DEFAULT NULL, CHANGE rib_branch_code rib_branch_code INT DEFAULT NULL, CHANGE rib_account_number rib_account_number BIGINT DEFAULT NULL, CHANGE rib_key rib_key INT DEFAULT NULL, CHANGE iban iban VARCHAR(64) DEFAULT NULL, CHANGE bic bic VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE bp CHANGE categorie categorie VARCHAR(255) DEFAULT NULL, CHANGE designation designation VARCHAR(1024) DEFAULT NULL, CHANGE expected_amount expected_amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE catalog_discount CHANGE name name VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE catalog_service CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE amount_ht amount_ht DOUBLE PRECISION DEFAULT NULL, CHANGE tva_rate tva_rate DOUBLE PRECISION DEFAULT NULL, CHANGE amount_ttc amount_ttc DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE chart_of_accounts CHANGE name name VARCHAR(1024) DEFAULT NULL, CHANGE code code INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cheque ADD quote VARCHAR(510) DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL, CHANGE date_of_collection date_of_collection DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE cheque_box CHANGE description description VARCHAR(1024) DEFAULT NULL, CHANGE last_count_date last_count_date DATE DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contact CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE last_name last_name VARCHAR(255) DEFAULT NULL, CHANGE mail mail VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE impression_access impression_access TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE equipe_elu CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE amount amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE exercice ADD annee INT NOT NULL, DROP date, DROP name');
        $this->addSql('ALTER TABLE expense_report CHANGE motif motif VARCHAR(1024) DEFAULT NULL, CHANGE code code INT DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE comfirm comfirm TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE expense_report_line CHANGE date date DATE DEFAULT NULL, CHANGE object object VARCHAR(255) DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE expense_report_route_line CHANGE date date DATE DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL, CHANGE travel_means travel_mean VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE financement CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE financement_line CHANGE libellee libellee VARCHAR(255) DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE impression CHANGE format format VARCHAR(255) NOT NULL, CHANGE recto_verso recto_verso TINYINT(1) NOT NULL, CHANGE couleur couleur TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE invoice CHANGE acquitted acquitted TINYINT(1) NOT NULL, CHANGE ready ready TINYINT(1) NOT NULL, CHANGE comfirm comfirm TINYINT(1) NOT NULL, CHANGE credit credit TINYINT(1) NOT NULL, CHANGE code code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE location CHANGE adress adress VARCHAR(255) DEFAULT NULL, CHANGE zip_code zip_code INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE profil CHANGE name name VARCHAR(255) NOT NULL, CHANGE pronom pronom VARCHAR(255) NOT NULL, CHANGE date_of_birth date_of_birth DATE NOT NULL');
        $this->addSql('ALTER TABLE repay_grid CHANGE travel_mean travel_mean VARCHAR(255) DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE retour CHANGE apport_generale apport_generale VARCHAR(255) DEFAULT NULL, CHANGE plus_aimer plus_aimer VARCHAR(255) DEFAULT NULL, CHANGE moins_aimer moins_aimer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE seance CHANGE datetime datetime DATETIME NOT NULL, CHANGE nombreplace nombreplace INT NOT NULL, CHANGE visible visible TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bank_account CHANGE account_number account_number INT NOT NULL, CHANGE rib_bank_code rib_bank_code INT NOT NULL, CHANGE rib_branch_code rib_branch_code INT NOT NULL, CHANGE rib_account_number rib_account_number BIGINT NOT NULL, CHANGE rib_key rib_key INT NOT NULL, CHANGE iban iban VARCHAR(64) NOT NULL, CHANGE bic bic VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE bp CHANGE categorie categorie VARCHAR(255) NOT NULL, CHANGE designation designation VARCHAR(1024) NOT NULL, CHANGE expected_amount expected_amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE catalog_discount CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE catalog_service CHANGE name name VARCHAR(255) NOT NULL, CHANGE amount_ht amount_ht DOUBLE PRECISION NOT NULL, CHANGE tva_rate tva_rate DOUBLE PRECISION NOT NULL, CHANGE amount_ttc amount_ttc DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE chart_of_accounts CHANGE name name VARCHAR(1024) NOT NULL, CHANGE code code INT NOT NULL');
        $this->addSql('ALTER TABLE cheque DROP quote, CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE date_of_collection date_of_collection DATE NOT NULL');
        $this->addSql('ALTER TABLE cheque_box CHANGE description description VARCHAR(1024) NOT NULL, CHANGE last_count_date last_count_date DATE NOT NULL, CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE contact CHANGE name name VARCHAR(255) NOT NULL, CHANGE last_name last_name VARCHAR(255) NOT NULL, CHANGE mail mail VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer CHANGE name name VARCHAR(255) NOT NULL, CHANGE impression_access impression_access TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE equipe_elu CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE exercice ADD date DATE NOT NULL, ADD name VARCHAR(255) NOT NULL, DROP annee');
        $this->addSql('ALTER TABLE expense_report CHANGE motif motif VARCHAR(1024) NOT NULL, CHANGE code code INT NOT NULL, CHANGE date date DATE NOT NULL, CHANGE comfirm comfirm TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE expense_report_line CHANGE date date DATE NOT NULL, CHANGE object object VARCHAR(255) NOT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE expense_report_route_line CHANGE date date DATE NOT NULL, CHANGE amount amount VARCHAR(255) DEFAULT NULL, CHANGE travel_mean travel_means VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE financement CHANGE name name VARCHAR(255) NOT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE financement_line CHANGE libellee libellee VARCHAR(255) NOT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE impression CHANGE format format VARCHAR(255) DEFAULT NULL, CHANGE recto_verso recto_verso TINYINT(1) DEFAULT NULL, CHANGE couleur couleur TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice CHANGE acquitted acquitted TINYINT(1) DEFAULT NULL, CHANGE ready ready TINYINT(1) DEFAULT NULL, CHANGE comfirm comfirm TINYINT(1) DEFAULT NULL, CHANGE credit credit TINYINT(1) DEFAULT NULL, CHANGE code code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE location CHANGE adress adress VARCHAR(255) NOT NULL, CHANGE zip_code zip_code INT NOT NULL, CHANGE city city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE profil CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE pronom pronom VARCHAR(255) DEFAULT NULL, CHANGE date_of_birth date_of_birth DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE repay_grid CHANGE travel_mean travel_mean VARCHAR(255) NOT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE retour CHANGE apport_generale apport_generale VARCHAR(255) NOT NULL, CHANGE plus_aimer plus_aimer VARCHAR(255) NOT NULL, CHANGE moins_aimer moins_aimer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE seance CHANGE datetime datetime DATETIME DEFAULT NULL, CHANGE nombreplace nombreplace INT DEFAULT NULL, CHANGE visible visible TINYINT(1) DEFAULT NULL');
    }
}
