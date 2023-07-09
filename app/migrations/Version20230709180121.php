<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230709180121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'result_file migration for files and saves';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE result_file (id INT AUTO_INCREMENT NOT NULL,
             created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
              updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
               result_file VARCHAR(255) NOT NULL, file_name VARCHAR(60) NOT NULL,
                extension VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE result_file');
    }
}
