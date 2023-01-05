<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200424090758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_TEMPLATE_REFERENCE', 'TemplateReference - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_TEMPLATE_REFERENCE', 'TemplateReference - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_TEMPLATE_REFERENCE', 'TemplateReference - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_TEMPLATE_REFERENCE', 'TemplateReference - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_TEMPLATE_REFERENCE', 'TemplateReference - Удаление', false) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_TEMPLATE_REFERENCE_VALUE', 'TemplateReferenceValue - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_TEMPLATE_REFERENCE_VALUE', 'TemplateReferenceValue - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_TEMPLATE_REFERENCE_VALUE', 'TemplateReferenceValue - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_TEMPLATE_REFERENCE_VALUE', 'TemplateReferenceValue - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_TEMPLATE_REFERENCE_VALUE', 'TemplateReferenceValue - Удаление', false) ON CONFLICT DO NOTHING");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
