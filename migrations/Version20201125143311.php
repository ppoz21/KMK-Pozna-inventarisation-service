<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125143311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE train ADD station_id INT NOT NULL');
        $this->addSql('ALTER TABLE train ADD CONSTRAINT FK_5C66E4A321BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('CREATE INDEX IDX_5C66E4A321BDB235 ON train (station_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE train DROP FOREIGN KEY FK_5C66E4A321BDB235');
        $this->addSql('DROP INDEX IDX_5C66E4A321BDB235 ON train');
        $this->addSql('ALTER TABLE train DROP station_id');
    }
}
