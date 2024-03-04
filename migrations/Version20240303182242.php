<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303182242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement CHANGE nbre_participants nbre_participants INT NOT NULL');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944582C115A61');
        $this->addSql('ALTER TABLE feedback ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445879F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944582C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_D229445879F37AE5 ON feedback (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement CHANGE nbre_participants nbre_participants INT DEFAULT NULL');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D229445879F37AE5');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944582C115A61');
        $this->addSql('DROP INDEX IDX_D229445879F37AE5 ON feedback');
        $this->addSql('ALTER TABLE feedback DROP id_user_id');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944582C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
    }
}
