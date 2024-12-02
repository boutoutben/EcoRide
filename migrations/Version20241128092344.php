<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128092344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_roles (id INT AUTO_INCREMENT NOT NULL, roles_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_54FCD59F38C751C4 (roles_id), INDEX IDX_54FCD59FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59F38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE roles DROP FOREIGN KEY FK_B63E2EC7A76ED395');
        $this->addSql('DROP INDEX IDX_B63E2EC7A76ED395 ON roles');
        $this->addSql('ALTER TABLE roles DROP user_id');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(180) NOT NULL, DROP pseudo');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59F38C751C4');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FA76ED395');
        $this->addSql('DROP TABLE user_roles');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_USERNAME ON user');
        $this->addSql('ALTER TABLE user ADD pseudo VARCHAR(100) NOT NULL, DROP username');
        $this->addSql('ALTER TABLE roles ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B63E2EC7A76ED395 ON roles (user_id)');
    }
}
