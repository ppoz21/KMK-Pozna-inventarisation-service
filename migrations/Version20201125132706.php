<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125132706 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, train_id INT NOT NULL, number VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, comments VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_773DE69D23BCD4D0 (train_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locomotive (id INT AUTO_INCREMENT NOT NULL, type_and_number VARCHAR(255) NOT NULL, painting VARCHAR(255) NOT NULL, short_name VARCHAR(255) DEFAULT NULL, owner VARCHAR(255) NOT NULL, comments VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE train (id INT AUTO_INCREMENT NOT NULL, locomotive_id INT NOT NULL, UNIQUE INDEX UNIQ_5C66E4A3587009A8 (locomotive_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D23BCD4D0 FOREIGN KEY (train_id) REFERENCES train (id)');
        $this->addSql('ALTER TABLE train ADD CONSTRAINT FK_5C66E4A3587009A8 FOREIGN KEY (locomotive_id) REFERENCES locomotive (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE train DROP FOREIGN KEY FK_5C66E4A3587009A8');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D23BCD4D0');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE locomotive');
        $this->addSql('DROP TABLE train');
    }
}
