<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210416131441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add restaurant name on history';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE history ADD restaurant_name VARCHAR(255) NOT NULL DEFAULT "n/a"');
        $this->addSql('ALTER TABLE history ALTER COLUMN restaurant_name DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE history DROP restaurant_name');
    }
}
