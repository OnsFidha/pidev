<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227102117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation_user (participation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_51E769006ACE3B73 (participation_id), INDEX IDX_51E76900A76ED395 (user_id), PRIMARY KEY(participation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation_evenement (participation_id INT NOT NULL, evenement_id INT NOT NULL, INDEX IDX_65A146756ACE3B73 (participation_id), INDEX IDX_65A14675FD02F13 (evenement_id), PRIMARY KEY(participation_id, evenement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation_user ADD CONSTRAINT FK_51E769006ACE3B73 FOREIGN KEY (participation_id) REFERENCES participation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation_user ADD CONSTRAINT FK_51E76900A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation_evenement ADD CONSTRAINT FK_65A146756ACE3B73 FOREIGN KEY (participation_id) REFERENCES participation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation_evenement ADD CONSTRAINT FK_65A14675FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement CHANGE nbre_participants nbre_participants INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation_user DROP FOREIGN KEY FK_51E769006ACE3B73');
        $this->addSql('ALTER TABLE participation_user DROP FOREIGN KEY FK_51E76900A76ED395');
        $this->addSql('ALTER TABLE participation_evenement DROP FOREIGN KEY FK_65A146756ACE3B73');
        $this->addSql('ALTER TABLE participation_evenement DROP FOREIGN KEY FK_65A14675FD02F13');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE participation_user');
        $this->addSql('DROP TABLE participation_evenement');
        $this->addSql('ALTER TABLE evenement CHANGE nbre_participants nbre_participants INT DEFAULT NULL');
    }
}
