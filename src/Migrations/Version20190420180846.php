<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190420180846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, fname VARCHAR(255) NOT NULL, lname VARCHAR(255) NOT NULL, dept VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clockings CHANGE time time DATETIME NOT NULL, CHANGE direction direction VARCHAR(255) NOT NULL, CHANGE punctual punctual VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE employee');
        $this->addSql('ALTER TABLE clockings CHANGE time time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE direction direction VARCHAR(255) DEFAULT \'unknown\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE punctual punctual VARCHAR(255) DEFAULT \'unknown\' NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
