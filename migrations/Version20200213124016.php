<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200213124016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX uniq_5373c9669f75d7b0');
        $this->addSql('ALTER TABLE country DROP external_id');
        $this->addSql('DROP INDEX uniq_1b87fa9d9f75d7b0');
        $this->addSql('ALTER TABLE subdivision DROP external_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subdivision ADD external_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_1b87fa9d9f75d7b0 ON subdivision (external_id)');
        $this->addSql('ALTER TABLE country ADD external_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_5373c9669f75d7b0 ON country (external_id)');
    }
}
