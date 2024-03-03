<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301222930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE whatsapp_notif (id INT AUTO_INCREMENT NOT NULL, id_reclam_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1A32C2F41114195D (id_reclam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE whatsapp_notif ADD CONSTRAINT FK_1A32C2F41114195D FOREIGN KEY (id_reclam_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE whatsapp_notif DROP FOREIGN KEY FK_1A32C2F41114195D');
        $this->addSql('DROP TABLE whatsapp_notif');
    }
}
