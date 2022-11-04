<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210205142745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the history table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql', 
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE history (
            id INT AUTO_INCREMENT NOT NULL, 
            user_id INT NOT NULL, 
            restaurant_id VARCHAR(255) NOT NULL, 
            created_at DATETIME NOT NULL, 
            INDEX IDX_27BA704BA76ED395 (user_id), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql', 
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE history');
    }
}
