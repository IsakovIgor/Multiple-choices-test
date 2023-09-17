<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230917084217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE question (id UUID NOT NULL, question VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN question.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE question_choice (id UUID NOT NULL, question_id UUID DEFAULT NULL, choice_text VARCHAR(255) NOT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C6F6759A1E27F6BF ON question_choice (question_id)');
        $this->addSql('COMMENT ON COLUMN question_choice.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN question_choice.question_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE result (id UUID NOT NULL, question_id UUID DEFAULT NULL, created DATE NOT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_136AC1131E27F6BF ON result (question_id)');
        $this->addSql('COMMENT ON COLUMN result.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN result.question_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE question_choice ADD CONSTRAINT FK_C6F6759A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1131E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE question_choice DROP CONSTRAINT FK_C6F6759A1E27F6BF');
        $this->addSql('ALTER TABLE result DROP CONSTRAINT FK_136AC1131E27F6BF');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_choice');
        $this->addSql('DROP TABLE result');
    }
}
