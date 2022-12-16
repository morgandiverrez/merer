<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221203173044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A446D52326E94372 ON administrative_identifier (siret)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A446D523A4B39424 ON administrative_identifier (ape)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CC77153098 ON association (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CC5E237E06 ON association (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CC8776B952 ON association (sigle)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD8521CCA1207B9E ON association (adresse_mail)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEF0481D77153098 ON badge (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEF0481D5E237E06 ON badge (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53A23E0AB1A4D127 ON bank_account (account_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53A23E0ABD1BC4FE ON bank_account (rib_account_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_53A23E0AFAD56E62 ON bank_account (iban)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D3376A105E237E06 ON catalog_discount (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D3376A1077153098 ON catalog_discount (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EE2F67FE5E237E06 ON catalog_service (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EE2F67FE77153098 ON catalog_service (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A700CD85E237E06 ON chart_of_accounts (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7A700CD877153098 ON chart_of_accounts (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_824E6B6A5E237E06 ON cheque_box (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E6385126AC48 ON contact (mail)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C62E638444F97DD ON contact (phone)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E095E237E06 ON customer (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A7A7A4CD77153098 ON equipe_elu (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A7A7A4CD5E237E06 ON equipe_elu (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A7A7A4CDA1207B9E ON equipe_elu (adresse_mail)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B26681E5E237E06 ON evenement (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA777153098 ON event (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E418C74DDE92C5CF ON exercice (annee)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_280A69177153098 ON expense_report (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD241BCD25F3EAD0 ON federation (social_reason)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD241BCD69FF72CB ON federation (rna)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD241BCD8910B08D ON federation (vat_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_404021BF77153098 ON formation (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F73CB8A25E237E06 ON fund_box (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_72F5D9AF5E237E06 ON fund_type (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_72F5D9AF8EA17042 ON fund_type (amount)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9065174477153098 ON invoice (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9E44A8AE77153098 ON lieux (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9E44A8AE5E237E06 ON lieux (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B29777153098 ON profil (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B297450FF010 ON profil (telephone)');
        $this->addSql('ALTER TABLE repay_grid CHANGE travel_mean travel_mean VARCHAR(255) NOT NULL, CHANGE start start VARCHAR(255) NOT NULL, CHANGE end end VARCHAR(255) NOT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE distance distance DOUBLE PRECISION NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DF7DFD0E77153098 ON seance (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B2A6C7E5E237E06 ON supplier (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_723705D177153098 ON transaction (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_A446D52326E94372 ON administrative_identifier');
        $this->addSql('DROP INDEX UNIQ_A446D523A4B39424 ON administrative_identifier');
        $this->addSql('DROP INDEX UNIQ_FD8521CC77153098 ON association');
        $this->addSql('DROP INDEX UNIQ_FD8521CC5E237E06 ON association');
        $this->addSql('DROP INDEX UNIQ_FD8521CC8776B952 ON association');
        $this->addSql('DROP INDEX UNIQ_FD8521CCA1207B9E ON association');
        $this->addSql('DROP INDEX UNIQ_FEF0481D77153098 ON badge');
        $this->addSql('DROP INDEX UNIQ_FEF0481D5E237E06 ON badge');
        $this->addSql('DROP INDEX UNIQ_53A23E0AB1A4D127 ON bank_account');
        $this->addSql('DROP INDEX UNIQ_53A23E0ABD1BC4FE ON bank_account');
        $this->addSql('DROP INDEX UNIQ_53A23E0AFAD56E62 ON bank_account');
        $this->addSql('DROP INDEX UNIQ_D3376A105E237E06 ON catalog_discount');
        $this->addSql('DROP INDEX UNIQ_D3376A1077153098 ON catalog_discount');
        $this->addSql('DROP INDEX UNIQ_EE2F67FE5E237E06 ON catalog_service');
        $this->addSql('DROP INDEX UNIQ_EE2F67FE77153098 ON catalog_service');
        $this->addSql('DROP INDEX UNIQ_7A700CD85E237E06 ON chart_of_accounts');
        $this->addSql('DROP INDEX UNIQ_7A700CD877153098 ON chart_of_accounts');
        $this->addSql('DROP INDEX UNIQ_824E6B6A5E237E06 ON cheque_box');
        $this->addSql('DROP INDEX UNIQ_4C62E6385126AC48 ON contact');
        $this->addSql('DROP INDEX UNIQ_4C62E638444F97DD ON contact');
        $this->addSql('DROP INDEX UNIQ_81398E095E237E06 ON customer');
        $this->addSql('DROP INDEX UNIQ_A7A7A4CD77153098 ON equipe_elu');
        $this->addSql('DROP INDEX UNIQ_A7A7A4CD5E237E06 ON equipe_elu');
        $this->addSql('DROP INDEX UNIQ_A7A7A4CDA1207B9E ON equipe_elu');
        $this->addSql('DROP INDEX UNIQ_B26681E5E237E06 ON evenement');
        $this->addSql('DROP INDEX UNIQ_3BAE0AA777153098 ON event');
        $this->addSql('DROP INDEX UNIQ_E418C74DDE92C5CF ON exercice');
        $this->addSql('DROP INDEX UNIQ_280A69177153098 ON expense_report');
        $this->addSql('DROP INDEX UNIQ_AD241BCD25F3EAD0 ON federation');
        $this->addSql('DROP INDEX UNIQ_AD241BCD69FF72CB ON federation');
        $this->addSql('DROP INDEX UNIQ_AD241BCD8910B08D ON federation');
        $this->addSql('DROP INDEX UNIQ_404021BF77153098 ON formation');
        $this->addSql('DROP INDEX UNIQ_F73CB8A25E237E06 ON fund_box');
        $this->addSql('DROP INDEX UNIQ_72F5D9AF5E237E06 ON fund_type');
        $this->addSql('DROP INDEX UNIQ_72F5D9AF8EA17042 ON fund_type');
        $this->addSql('DROP INDEX UNIQ_9065174477153098 ON invoice');
        $this->addSql('DROP INDEX UNIQ_9E44A8AE77153098 ON lieux');
        $this->addSql('DROP INDEX UNIQ_9E44A8AE5E237E06 ON lieux');
        $this->addSql('DROP INDEX UNIQ_E6D6B29777153098 ON profil');
        $this->addSql('DROP INDEX UNIQ_E6D6B297450FF010 ON profil');
        $this->addSql('ALTER TABLE repay_grid CHANGE travel_mean travel_mean VARCHAR(255) DEFAULT NULL, CHANGE start start VARCHAR(255) DEFAULT NULL, CHANGE end end VARCHAR(255) DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION DEFAULT NULL, CHANGE distance distance DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_DF7DFD0E77153098 ON seance');
        $this->addSql('DROP INDEX UNIQ_9B2A6C7E5E237E06 ON supplier');
        $this->addSql('DROP INDEX UNIQ_723705D177153098 ON transaction');
    }
}
