<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230621181836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours_finish (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, user_id INT DEFAULT NULL, is_finish TINYINT(1) NOT NULL, INDEX IDX_4F49FA707ECF78B0 (cours_id), INDEX IDX_4F49FA70A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours_finish ADD CONSTRAINT FK_4F49FA707ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
        $this->addSql('ALTER TABLE cours_finish ADD CONSTRAINT FK_4F49FA70A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_finish DROP FOREIGN KEY FK_4F49FA707ECF78B0');
        $this->addSql('ALTER TABLE cours_finish DROP FOREIGN KEY FK_4F49FA70A76ED395');
        $this->addSql('DROP TABLE cours_finish');
    }
}
