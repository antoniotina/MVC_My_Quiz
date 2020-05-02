<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200502154621 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie RENAME INDEX name TO UNIQ_497DD6345E237E06');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704B1BFA63C8');
        $this->addSql('DROP INDEX IDX_27BA704B1BFA63C8 ON history');
        $this->addSql('DROP INDEX IDX_27BA704B3256915B ON history');
        $this->addSql('ALTER TABLE history ADD user_id INT DEFAULT NULL, ADD date DATETIME NOT NULL, DROP relations_id, CHANGE relation_id categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_27BA704BA76ED395 ON history (user_id)');
        $this->addSql('CREATE INDEX IDX_27BA704BBCF5E72D ON history (categorie_id)');
        $this->addSql('ALTER TABLE user CHANGE validated_at validated_at DATETIME DEFAULT NULL, CHANGE last_connection last_connection DATETIME DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categorie RENAME INDEX uniq_497dd6345e237e06 TO name');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BA76ED395');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BBCF5E72D');
        $this->addSql('DROP INDEX IDX_27BA704BA76ED395 ON history');
        $this->addSql('DROP INDEX IDX_27BA704BBCF5E72D ON history');
        $this->addSql('ALTER TABLE history ADD relations_id INT DEFAULT NULL, DROP user_id, DROP date, CHANGE categorie_id relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704B1BFA63C8 FOREIGN KEY (relations_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_27BA704B1BFA63C8 ON history (relations_id)');
        $this->addSql('CREATE INDEX IDX_27BA704B3256915B ON history (relation_id)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBCF5E72D');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE user CHANGE validated_at validated_at DATETIME DEFAULT \'NULL\', CHANGE last_connection last_connection DATETIME DEFAULT \'NULL\', CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
