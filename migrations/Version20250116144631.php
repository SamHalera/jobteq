<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116144631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE license (id INT AUTO_INCREMENT NOT NULL, license_config_id INT NOT NULL, name VARCHAR(255) NOT NULL, credits INT NOT NULL, INDEX IDX_5768F4199C9AF31D (license_config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE license_config (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE license ADD CONSTRAINT FK_5768F4199C9AF31D FOREIGN KEY (license_config_id) REFERENCES license_config (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE license DROP FOREIGN KEY FK_5768F4199C9AF31D');
        $this->addSql('DROP TABLE license');
        $this->addSql('DROP TABLE license_config');
    }
}
