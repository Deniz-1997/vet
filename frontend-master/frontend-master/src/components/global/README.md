# Компоненты с глобальной областью видимости

### [Список компонентов](#components)

<br>

Когда компоненты часто переиспользуются в различных участках кода приложения, возникает вопрос о необходимости их постоянного импорта в места использования. Чтобы избежать такого импорта, можно зарегистрировать их как глобальные.

---

## Критерии выноса

### Когда надо

- Компонент или группа компонентов предоставляют базовую функциональность (валидация, uikit, переводы, etc.);
- Функциональность используется во всех разделах приположения или должна в них внедрится.

### Когда не надо

- Компонент или группа компонентов относится к конкретным разделам приложения;
- Функциональность, реализуемая компонентами, зависит от бизнес-логики или реализует её;
- Компоненты невозможно использовать вне контекста приложения.

---

## Добавление глобального компонента

1. В текущей папке создаётся новая директория с названием, отражающим функционал компонента или их группы. Директория и все компоненты внутри неё должны начинаться с префикса `Ui`. (e.g. `UiForm`, `UiControl`).
2. Все компоненты, которые должны быть доступны глобально, должны быть зарегистрированы в файле [index.ts](./index.ts). Название компонента должно соответствовать названию файла, в котором он описан и начинаться с префикса `Ui`.
3. Новый функционал должен соответствовать каждому из следующих критериев приёмки:
   - Код полностью написан и функционирует;
   - Код в полной мере типизирован;
   - Код в необходимом количестве покрыт коментариями (jsdoc);
   - Каждая новая директория содержит файл README.md с описанием предоставляемого функционала, примерами работы и описанием API компонентов в принятом формате;
   - Код покрыт тестами минимум на 80%;
   - Информация о новом функционале добавлена в раздел [Компоненты](#components) корневого README.md;
4. Создан отдельный коммит с описанием изменения.

---

## Компоненты <a href="#components"></a>

- [UiForm](./UiForm/README.md) - Компоненты для описания валидируемых форм на основе библиотеки [ValidatorJS](https://github.com/mikeerickson/validatorjs);
