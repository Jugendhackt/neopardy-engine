<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191005091636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE question_player (question_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_9F44556D1E27F6BF (question_id), INDEX IDX_9F44556D99E6F5DF (player_id), PRIMARY KEY(question_id, player_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question_player ADD CONSTRAINT FK_9F44556D1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_player ADD CONSTRAINT FK_9F44556D99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE qto_p');
        $this->addSql('ALTER TABLE category CHANGE game_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player CHANGE game_id game_id INT DEFAULT NULL, CHANGE points points INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE qto_p (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, timestamp DATETIME NOT NULL, correct TINYINT(1) DEFAULT \'NULL\', UNIQUE INDEX UNIQ_C860B5131E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE qto_p ADD CONSTRAINT FK_C860B5131E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('DROP TABLE question_player');
        $this->addSql('ALTER TABLE category CHANGE game_id game_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player CHANGE game_id game_id INT DEFAULT NULL, CHANGE points points INT DEFAULT NULL');
    }
}
