<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212204317 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE city (id UUID NOT NULL, country_id UUID NOT NULL, subdivision_id UUID DEFAULT NULL, name VARCHAR(127) NOT NULL, timezone VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D5B0234F92F3E70 ON city (country_id)');
        $this->addSql('CREATE INDEX IDX_2D5B0234E05F13C ON city (subdivision_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D5B02345E237E06E05F13C ON city (name, subdivision_id)');
        $this->addSql('COMMENT ON COLUMN city.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN city.country_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN city.subdivision_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE country (id UUID NOT NULL, name VARCHAR(127) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5373C9665E237E06 ON country (name)');
        $this->addSql('COMMENT ON COLUMN country.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE subdivision (id UUID NOT NULL, name VARCHAR(127) NOT NULL, iso_code VARCHAR(127) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B87FA9D62B6A45E ON subdivision (iso_code)');
        $this->addSql('COMMENT ON COLUMN subdivision.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE link (id UUID NOT NULL, user_id UUID NOT NULL, url VARCHAR(4096) NOT NULL, hash VARCHAR(10) NOT NULL, expiration_datetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_36AC99F1D1B862B8 ON link (hash)');
        $this->addSql('CREATE INDEX IDX_36AC99F1A76ED395 ON link (user_id)');
        $this->addSql('COMMENT ON COLUMN link.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN link.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN link.expiration_datetime IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE link_statistic (id UUID NOT NULL, link_id UUID NOT NULL, city_id UUID DEFAULT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, meta JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_86EE96E8ADA40271 ON link_statistic (link_id)');
        $this->addSql('CREATE INDEX IDX_86EE96E88BAC62AF ON link_statistic (city_id)');
        $this->addSql('COMMENT ON COLUMN link_statistic.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN link_statistic.link_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN link_statistic.city_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN link_statistic.datetime IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(254) NOT NULL, password VARCHAR(97) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES subdivision (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234E05F13C FOREIGN KEY (subdivision_id) REFERENCES subdivision (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE link_statistic ADD CONSTRAINT FK_86EE96E8ADA40271 FOREIGN KEY (link_id) REFERENCES link (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE link_statistic ADD CONSTRAINT FK_86EE96E88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE link_statistic DROP CONSTRAINT FK_86EE96E88BAC62AF');
        $this->addSql('ALTER TABLE city DROP CONSTRAINT FK_2D5B0234F92F3E70');
        $this->addSql('ALTER TABLE city DROP CONSTRAINT FK_2D5B0234E05F13C');
        $this->addSql('ALTER TABLE link_statistic DROP CONSTRAINT FK_86EE96E8ADA40271');
        $this->addSql('ALTER TABLE link DROP CONSTRAINT FK_36AC99F1A76ED395');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE subdivision');
        $this->addSql('DROP TABLE link');
        $this->addSql('DROP TABLE link_statistic');
        $this->addSql('DROP TABLE "user"');
    }
}
