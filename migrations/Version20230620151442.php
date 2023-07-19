<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620151442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_calendar (user_id INT NOT NULL, calendar_id INT NOT NULL, INDEX IDX_8E244546A76ED395 (user_id), INDEX IDX_8E244546A40A2C8 (calendar_id), PRIMARY KEY(user_id, calendar_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_calendar ADD CONSTRAINT FK_8E244546A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_calendar ADD CONSTRAINT FK_8E244546A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_calendar DROP FOREIGN KEY FK_8E244546A76ED395');
        $this->addSql('ALTER TABLE user_calendar DROP FOREIGN KEY FK_8E244546A40A2C8');
        $this->addSql('DROP TABLE user_calendar');
    }
}
