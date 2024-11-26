<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125181957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, carpool_id INT DEFAULT NULL, user_id INT DEFAULT NULL, licence_plate VARCHAR(20) NOT NULL, first_registration DATE NOT NULL, model VARCHAR(100) NOT NULL, color VARCHAR(100) NOT NULL, energie VARCHAR(100) NOT NULL, INDEX IDX_773DE69D9A6F0DAE (carpool_id), INDEX IDX_773DE69DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carpool (id INT AUTO_INCREMENT NOT NULL, start_place VARCHAR(255) NOT NULL, end_place VARCHAR(255) NOT NULL, place_left INT NOT NULL, end_date DATETIME NOT NULL, start_date DATETIME NOT NULL, is_ecologique TINYINT(1) NOT NULL, is_great TINYINT(1) NOT NULL, is_start TINYINT(1) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carpool_participation (id INT AUTO_INCREMENT NOT NULL, carpool_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_6FB6E529A6F0DAE (carpool_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mark (id INT AUTO_INCREMENT NOT NULL, car_id INT DEFAULT NULL, label VARCHAR(100) NOT NULL, INDEX IDX_6674F271C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opinion (id INT AUTO_INCREMENT NOT NULL, opinion VARCHAR(500) NOT NULL, grade INT NOT NULL, is_valid TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, label VARCHAR(50) NOT NULL, INDEX IDX_B63E2EC7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, carpool_participation_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, surname VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, phone INT NOT NULL, pseudo VARCHAR(100) NOT NULL, nb_credit INT NOT NULL, img VARCHAR(255) NOT NULL, INDEX IDX_8D93D649D8685255 (carpool_participation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_opinion (user_id INT NOT NULL, opinion_id INT NOT NULL, INDEX IDX_F30FAE2DA76ED395 (user_id), INDEX IDX_F30FAE2D51885A6A (opinion_id), PRIMARY KEY(user_id, opinion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D9A6F0DAE FOREIGN KEY (carpool_id) REFERENCES carpool (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE carpool_participation ADD CONSTRAINT FK_6FB6E529A6F0DAE FOREIGN KEY (carpool_id) REFERENCES carpool (id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D8685255 FOREIGN KEY (carpool_participation_id) REFERENCES carpool_participation (id)');
        $this->addSql('ALTER TABLE user_opinion ADD CONSTRAINT FK_F30FAE2DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_opinion ADD CONSTRAINT FK_F30FAE2D51885A6A FOREIGN KEY (opinion_id) REFERENCES opinion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D9A6F0DAE');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA76ED395');
        $this->addSql('ALTER TABLE carpool_participation DROP FOREIGN KEY FK_6FB6E529A6F0DAE');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271C3C6F69F');
        $this->addSql('ALTER TABLE roles DROP FOREIGN KEY FK_B63E2EC7A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D8685255');
        $this->addSql('ALTER TABLE user_opinion DROP FOREIGN KEY FK_F30FAE2DA76ED395');
        $this->addSql('ALTER TABLE user_opinion DROP FOREIGN KEY FK_F30FAE2D51885A6A');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE carpool');
        $this->addSql('DROP TABLE carpool_participation');
        $this->addSql('DROP TABLE mark');
        $this->addSql('DROP TABLE opinion');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_opinion');
    }
}
