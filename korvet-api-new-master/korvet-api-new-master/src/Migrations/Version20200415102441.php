<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200415102441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_FORM_TEMPLATE', 'FormTemplate - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_FORM_TEMPLATE', 'FormTemplate - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_FORM_TEMPLATE', 'FormTemplate - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_FORM_TEMPLATE', 'FormTemplate - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_FORM_TEMPLATE', 'FormTemplate - Удаление', false) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_FORM_TEMPLATE_FIELD', 'FormTemplateField - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_FORM_TEMPLATE_FIELD', 'FormTemplateField - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_FORM_TEMPLATE_FIELD', 'FormTemplateField - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_FORM_TEMPLATE_FIELD', 'FormTemplateField - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_FORM_TEMPLATE_FIELD', 'FormTemplateField - Удаление', false) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_FORM_FIELD', 'FormField - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_FORM_FIELD', 'FormField - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_FORM_FIELD', 'FormField - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_FORM_FIELD', 'FormField - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_FORM_FIELD', 'FormField - Удаление', false) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_FORM_FIELD_VALUE', 'FormFieldValue - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_FORM_FIELD_VALUE', 'FormFieldValue - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_FORM_FIELD_VALUE', 'FormFieldValue - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_FORM_FIELD_VALUE', 'FormFieldValue - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_FORM_FIELD_VALUE', 'FormFieldValue - Удаление', false) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_FORM_FIELD_PROPERTY_VALUE', 'FormFieldPropertyValue - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_FORM_FIELD_PROPERTY_VALUE', 'FormFieldPropertyValue - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_FORM_FIELD_PROPERTY_VALUE', 'FormFieldPropertyValue - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_FORM_FIELD_PROPERTY_VALUE', 'FormFieldPropertyValue - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_FORM_FIELD_PROPERTY_VALUE', 'FormFieldPropertyValue - Удаление', false) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_FORM_FIELD_PROPERTY', 'FormFieldProperty - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_FORM_FIELD_PROPERTY', 'FormFieldProperty - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_FORM_FIELD_PROPERTY', 'FormFieldProperty - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_FORM_FIELD_PROPERTY', 'FormFieldProperty - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_FORM_FIELD_PROPERTY', 'FormFieldProperty - Удаление', false) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_FORM_FIELD_TYPE', 'FormFieldType - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_FORM_FIELD_TYPE', 'FormFieldType - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_FORM_FIELD_TYPE', 'FormFieldType - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_FORM_FIELD_TYPE', 'FormFieldType - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_FORM_FIELD_TYPE', 'FormFieldType - Удаление', false) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_WRITE_APPOINTMENT_FORM_TEMPLATE', 'AppointmentFormTemplate - Добавление + Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_READ_APPOINTMENT_FORM_TEMPLATE', 'AppointmentFormTemplate - Чтение', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_ADD_APPOINTMENT_FORM_TEMPLATE', 'AppointmentFormTemplate - Добавление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_UPDATE_APPOINTMENT_FORM_TEMPLATE', 'AppointmentFormTemplate - Обновление', false) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO public.roles (parent_id, code, name, deleted) VALUES (NULL, 'ROLE_DELETE_APPOINTMENT_FORM_TEMPLATE', 'AppointmentFormTemplate - Удаление', false) ON CONFLICT DO NOTHING");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

    }
}
