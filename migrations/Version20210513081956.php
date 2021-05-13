<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210513081956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, unit VARCHAR(30) NOT NULL)');
        $this->addSql('CREATE TABLE scheduled_meal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE scheduled_meal_meal (scheduled_meal_id INTEGER NOT NULL, meal_id INTEGER NOT NULL, PRIMARY KEY(scheduled_meal_id, meal_id))');
        $this->addSql('CREATE INDEX IDX_9235476F7513092 ON scheduled_meal_meal (scheduled_meal_id)');
        $this->addSql('CREATE INDEX IDX_9235476F639666D6 ON scheduled_meal_meal (meal_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ingredient AS SELECT id, quantity FROM ingredient');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('CREATE TABLE ingredient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, quantity DOUBLE PRECISION NOT NULL, CONSTRAINT FK_6BAF78704584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ingredient (id, quantity) SELECT id, quantity FROM __temp__ingredient');
        $this->addSql('DROP TABLE __temp__ingredient');
        $this->addSql('CREATE INDEX IDX_6BAF78704584665A ON ingredient (product_id)');
        $this->addSql('DROP INDEX IDX_FCC3CEFA639666D6');
        $this->addSql('DROP INDEX IDX_FCC3CEFA933FE08C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__meal_ingredient AS SELECT meal_id, ingredient_id FROM meal_ingredient');
        $this->addSql('DROP TABLE meal_ingredient');
        $this->addSql('CREATE TABLE meal_ingredient (meal_id INTEGER NOT NULL, ingredient_id INTEGER NOT NULL, PRIMARY KEY(meal_id, ingredient_id), CONSTRAINT FK_FCC3CEFA639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FCC3CEFA933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO meal_ingredient (meal_id, ingredient_id) SELECT meal_id, ingredient_id FROM __temp__meal_ingredient');
        $this->addSql('DROP TABLE __temp__meal_ingredient');
        $this->addSql('CREATE INDEX IDX_FCC3CEFA639666D6 ON meal_ingredient (meal_id)');
        $this->addSql('CREATE INDEX IDX_FCC3CEFA933FE08C ON meal_ingredient (ingredient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE scheduled_meal');
        $this->addSql('DROP TABLE scheduled_meal_meal');
        $this->addSql('DROP INDEX IDX_6BAF78704584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ingredient AS SELECT id, quantity FROM ingredient');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('CREATE TABLE ingredient (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantity DOUBLE PRECISION NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, unit VARCHAR(20) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO ingredient (id, quantity) SELECT id, quantity FROM __temp__ingredient');
        $this->addSql('DROP TABLE __temp__ingredient');
        $this->addSql('DROP INDEX IDX_FCC3CEFA639666D6');
        $this->addSql('DROP INDEX IDX_FCC3CEFA933FE08C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__meal_ingredient AS SELECT meal_id, ingredient_id FROM meal_ingredient');
        $this->addSql('DROP TABLE meal_ingredient');
        $this->addSql('CREATE TABLE meal_ingredient (meal_id INTEGER NOT NULL, ingredient_id INTEGER NOT NULL, PRIMARY KEY(meal_id, ingredient_id))');
        $this->addSql('INSERT INTO meal_ingredient (meal_id, ingredient_id) SELECT meal_id, ingredient_id FROM __temp__meal_ingredient');
        $this->addSql('DROP TABLE __temp__meal_ingredient');
        $this->addSql('CREATE INDEX IDX_FCC3CEFA639666D6 ON meal_ingredient (meal_id)');
        $this->addSql('CREATE INDEX IDX_FCC3CEFA933FE08C ON meal_ingredient (ingredient_id)');
    }
}
