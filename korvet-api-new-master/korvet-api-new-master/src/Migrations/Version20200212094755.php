<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200212094755 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_READ_FORM_TEMPLATE', 'Форма приема - Чтение', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_ADD_FORM_TEMPLATE', 'Форма приема - Добавление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_UPDATE_FORM_TEMPLATE', 'Форма приема - Обновление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_DELETE_FORM_TEMPLATE', 'Форма приема - Удаление', null) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_READ_FORM_FIELD', 'Поля формы - Чтение', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_ADD_FORM_FIELD', 'Поля формы - Добавление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_UPDATE_FORM_FIELD', 'Поля формы - Обновление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_DELETE_FORM_FIELD', 'Поля формы - Удаление', null) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_READ_FORM_FIELD_PROPERTY', 'Характеристика поля - Чтение', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_ADD_FORM_FIELD_PROPERTY', 'Характеристика поля - Добавление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_UPDATE_FORM_FIELD_PROPERTY', 'Характеристика поля - Обновление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_DELETE_FORM_FIELD_PROPERTY', 'Характеристика поля - Удаление', null) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_FORM_TEMPLATE', 'Форма приема -  Чтение + Обновление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_WRITE_FORM_TEMPLATE', 'Форма приема -  Добавление + Обновление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_FORM_FIELD', 'Поля формы - Чтение + Обновление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_WRITE_FORM_FIELD', 'Поля формы -  Добавление + Обновление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_FORM_FIELD_PROPERTY', 'Характеристика поля - Чтение + Обновление', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_WRITE_FORM_FIELD_PROPERTY', 'Характеристика поля -  Добавление + Обновление', null) ON CONFLICT DO NOTHING");

        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_MENU_APPOINTMENT_BUILDER', 'Пункт меню - Конструктор приемов', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_MENU_APPOINTMENT_BUILDER_FORM_TEMPLATE', 'Пункт меню - Конструктор форм', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_MENU_APPOINTMENT_BUILDER_FORM_FIELD', 'Пункт меню - Поля формы', null) ON CONFLICT DO NOTHING");
        $this->addSql("INSERT INTO roles (code, name, parent_id) VALUES ('ROLE_MENU_APPOINTMENT_BUILDER_FORM_FIELD_PROPERTY', 'Пункт меню - Свойства полей шаблонов', null) ON CONFLICT DO NOTHING");
    }

    public function down(Schema $schema) : void
    {
         $this->addSql("DELETE FROM roles WHERE code IN (
            'ROLE_READ_FORM_TEMPLATE',
            'ROLE_ADD_FORM_TEMPLATE',
            'ROLE_UPDATE_FORM_TEMPLATE',
            'ROLE_DELETE_FORM_TEMPLATE',
            'ROLE_READ_FORM_FIELD',
            'ROLE_ADD_FORM_FIELD',
            'ROLE_UPDATE_FORM_FIELD',
            'ROLE_DELETE_FORM_FIELD',
            'ROLE_READ_FORM_FIELD_PROPERTY',
            'ROLE_ADD_FORM_FIELD_PROPERTY',
            'ROLE_UPDATE_FORM_FIELD_PROPERTY',
            'ROLE_DELETE_FORM_FIELD_PROPERTY',
            'ROLE_FORM_TEMPLATE',
            'ROLE_WRITE_FORM_TEMPLATE',
            'ROLE_FORM_FIELD',
            'ROLE_WRITE_FORM_FIELD',
            'ROLE_FORM_FIELD_PROPERTY',
            'ROLE_WRITE_FORM_FIELD_PROPERTY',
            'ROLE_MENU_APPOINTMENT_BUILDER',
            'ROLE_MENU_APPOINTMENT_BUILDER_FORM_TEMPLATE',
            'ROLE_MENU_APPOINTMENT_BUILDER_FORM_FIELD',
            'ROLE_MENU_APPOINTMENT_BUILDER_FORM_FIELD_PROPERTY')");
    }
}
