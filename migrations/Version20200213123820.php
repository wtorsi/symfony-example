<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200213123820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('TRUNCATE link_statistic, city, subdivision, country');
        $this->addSql('ALTER TABLE city ADD external_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D5B02349F75D7B0 ON city (external_id)');
        $this->addSql('ALTER TABLE country ADD external_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5373C9669F75D7B0 ON country (external_id)');
        $this->addSql('ALTER TABLE subdivision ADD external_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B87FA9D9F75D7B0 ON subdivision (external_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_5373C9669F75D7B0');
        $this->addSql('ALTER TABLE country DROP external_id');
        $this->addSql('DROP INDEX UNIQ_1B87FA9D9F75D7B0');
        $this->addSql('ALTER TABLE subdivision DROP external_id');
        $this->addSql('DROP INDEX UNIQ_2D5B02349F75D7B0');
        $this->addSql('ALTER TABLE city DROP external_id');
    }
}
