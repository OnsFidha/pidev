<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226195328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collaboration (id INT AUTO_INCREMENT NOT NULL, disponibilite VARCHAR(255) NOT NULL, competence VARCHAR(255) NOT NULL, cv VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collaboration_publication (collaboration_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_DE01C07FEF1544CE (collaboration_id), INDEX IDX_DE01C07F38B217A7 (publication_id), PRIMARY KEY(collaboration_id, publication_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collaboration_user (collaboration_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C0365083EF1544CE (collaboration_id), INDEX IDX_C0365083A76ED395 (user_id), PRIMARY KEY(collaboration_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE collaboration_publication ADD CONSTRAINT FK_DE01C07FEF1544CE FOREIGN KEY (collaboration_id) REFERENCES collaboration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaboration_publication ADD CONSTRAINT FK_DE01C07F38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaboration_user ADD CONSTRAINT FK_C0365083EF1544CE FOREIGN KEY (collaboration_id) REFERENCES collaboration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaboration_user ADD CONSTRAINT FK_C0365083A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE collaboration_publication DROP FOREIGN KEY FK_DE01C07FEF1544CE');
        $this->addSql('ALTER TABLE collaboration_publication DROP FOREIGN KEY FK_DE01C07F38B217A7');
        $this->addSql('ALTER TABLE collaboration_user DROP FOREIGN KEY FK_C0365083EF1544CE');
        $this->addSql('ALTER TABLE collaboration_user DROP FOREIGN KEY FK_C0365083A76ED395');
        $this->addSql('DROP TABLE collaboration');
        $this->addSql('DROP TABLE collaboration_publication');
        $this->addSql('DROP TABLE collaboration_user');
    }
}
