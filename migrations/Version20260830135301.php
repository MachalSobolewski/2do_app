<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260830135301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board_column ADD CONSTRAINT FK_D14DC3D9E7EC5785 FOREIGN KEY (board_id) REFERENCES board (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25CA372FE FOREIGN KEY (board_column_id) REFERENCES board_column (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE board_column DROP FOREIGN KEY FK_D14DC3D9E7EC5785');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25CA372FE');
    }
}
