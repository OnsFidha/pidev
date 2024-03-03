<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303144943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, date_creation DATE NOT NULL, generate_with_ai TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, relation_id INT DEFAULT NULL, reponse VARCHAR(255) NOT NULL, date_reponse DATE NOT NULL, UNIQUE INDEX UNIQ_5FB6DEC73256915B (relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC73256915B FOREIGN KEY (relation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE whatsapp_notif ADD CONSTRAINT FK_1A32C2F41114195D FOREIGN KEY (id_reclam_id) REFERENCES reclamation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE whatsapp_notif DROP FOREIGN KEY FK_1A32C2F41114195D');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC73256915B');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
    }
}
