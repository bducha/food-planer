<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509153745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_FCC3CEFA933FE08C');
        $this->addSql('DROP INDEX IDX_FCC3CEFA639666D6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__meal_ingredient AS SELECT meal_id, ingredient_id FROM meal_ingredient');
        $this->addSql('DROP TABLE meal_ingredient');
        $this->addSql('CREATE TABLE meal_ingredient (meal_id INTEGER NOT NULL, ingredient_id INTEGER NOT NULL, PRIMARY KEY(meal_id, ingredient_id), CONSTRAINT FK_FCC3CEFA639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FCC3CEFA933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO meal_ingredient (meal_id, ingredient_id) SELECT meal_id, ingredient_id FROM __temp__meal_ingredient');
        $this->addSql('DROP TABLE __temp__meal_ingredient');
        $this->addSql('CREATE INDEX IDX_FCC3CEFA933FE08C ON meal_ingredient (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_FCC3CEFA639666D6 ON meal_ingredient (meal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
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
