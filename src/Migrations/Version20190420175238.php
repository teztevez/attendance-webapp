<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190420175238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE clockings');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE test1');
        $this->addSql('DROP TABLE time');
        $this->addSql('DROP TABLE time_records');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE clockings (id INT AUTO_INCREMENT NOT NULL, emp_id INT NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, direction VARCHAR(30) DEFAULT \'unknown\' NOT NULL COLLATE utf8_general_ci, punctual VARCHAR(30) DEFAULT \'unknown\' NOT NULL COLLATE utf8_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE employee (emp_id INT NOT NULL, first_name VARCHAR(50) NOT NULL COLLATE utf8_general_ci, last_name VARCHAR(50) NOT NULL COLLATE utf8_general_ci, PRIMARY KEY(emp_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE test1 (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE time (id INT AUTO_INCREMENT NOT NULL, emp_id INT NOT NULL, clock DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE time_records (id INT AUTO_INCREMENT NOT NULL, emp_id VARCHAR(512) DEFAULT NULL COLLATE utf8_general_ci, clock VARCHAR(512) DEFAULT NULL COLLATE utf8_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE admin');
    }
}
