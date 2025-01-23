<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114104728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE super_admin_job_config (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD super_admin_job_config_id INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C14D78E00D FOREIGN KEY (super_admin_job_config_id) REFERENCES super_admin_job_config (id)');
        $this->addSql('CREATE INDEX IDX_64C19C14D78E00D ON category (super_admin_job_config_id)');
        $this->addSql('ALTER TABLE tag ADD super_admin_job_config_id INT NOT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7834D78E00D FOREIGN KEY (super_admin_job_config_id) REFERENCES super_admin_job_config (id)');
        $this->addSql('CREATE INDEX IDX_389B7834D78E00D ON tag (super_admin_job_config_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C14D78E00D');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7834D78E00D');
        $this->addSql('DROP TABLE super_admin_job_config');
        $this->addSql('DROP INDEX IDX_389B7834D78E00D ON tag');
        $this->addSql('ALTER TABLE tag DROP super_admin_job_config_id');
        $this->addSql('DROP INDEX IDX_64C19C14D78E00D ON category');
        $this->addSql('ALTER TABLE category DROP super_admin_job_config_id');
    }
}
