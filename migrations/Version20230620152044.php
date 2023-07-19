<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620152044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar_user (calendar_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DF05551DA40A2C8 (calendar_id), INDEX IDX_DF05551DA76ED395 (user_id), PRIMARY KEY(calendar_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendar_user ADD CONSTRAINT FK_DF05551DA40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE calendar_user ADD CONSTRAINT FK_DF05551DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar_user DROP FOREIGN KEY FK_DF05551DA40A2C8');
        $this->addSql('ALTER TABLE calendar_user DROP FOREIGN KEY FK_DF05551DA76ED395');
        $this->addSql('DROP TABLE calendar_user');
    }
}
