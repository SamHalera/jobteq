<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116231750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, license_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_4FBF094F460F904B (license_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F460F904B FOREIGN KEY (license_id) REFERENCES license (id)');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(255) DEFAULT NULL, ADD lastname VARCHAR(255) DEFAULT NULL, ADD thumbnail VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F460F904B');
        $this->addSql('DROP TABLE company');
        $this->addSql('ALTER TABLE user DROP firstname, DROP lastname, DROP thumbnail');
    }
}
