import Validator from 'validatorjs';
import rules from './rules';

export const messages = Validator.getMessages('ru');
messages.accepted = 'Вы должны принять, чтобы продолжить';
messages.after = 'Выберите дату не ранее :after';
messages.after_or_equal = 'Выберите дату от :after_or_equal';
messages.alpha = 'Недопустимые символы';
messages.alpha_dash = 'Недопустимые символы';
messages.alpha_num = 'Недопустимые символы';
messages.array = 'Недопустимый формат';
messages.before = 'Выберите дату не позднее :before';
messages.before_or_equal = 'Выберите дату до :before_or_equal';
messages.between = 'Некорректная дата';
messages.boolean = 'Недопустимый формат';
messages.confirmed = '';
messages.date = 'Недопустимый формат';
messages.digits = 'Должно содержать :digits знаков после запятой';
messages.digits_between = 'Недопустимый формат';
messages.different = 'Значение должно отличаться от :different';
messages.email = 'Недопустимый формат';
messages.hex = 'Недопустимый формат';
messages.in = 'Недопустимый формат';
messages.integer = 'Введите целое число';
messages.max = 'Не может быть больше :max';
messages.min = 'Не может быть меньше :min';
messages.not_in = 'Недопустимый формат';
messages.numeric = 'Введите число';
messages.present = 'Обязательно для заполнения';
messages.required = 'Обязательно для заполнения';
messages.required_if = 'Обязательно для заполнения';
messages.required_unless = 'Обязательно для заполнения';
messages.required_with = 'Обязательно для заполнения';
messages.required_with_all = 'Обязательно для заполнения';
messages.required_without = 'Обязательно для заполнения';
messages.required_without_all = 'Обязательно для заполнения';
messages.same = 'Значение должно совпадать с :same';
messages.size = 'Недопустимый формат';
messages.string = 'Недопустимый формат';
messages.url = 'Недопустимый формат';
messages.regex = 'Недопустимый формат';

Validator.setMessages('ru_RU', messages);
Validator.useLang('ru_RU');

Object.entries(rules).forEach(([name, { handler, message }]) => {
  Validator.register(name, handler, message);
});
