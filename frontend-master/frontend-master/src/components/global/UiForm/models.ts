/** Опции кастомного правила. */
type TValidationOptions = {
  /** Валидатор. Должен вернуть true, если значение валидно. */
  handler: <V, T>(value: V, args: T, attribute: string) => boolean;
  /** Текст ошибки. */
  message?: string;
};
/** Список кастомных правил валидации. */
export type TValidationRules = { [key: string]: TValidationOptions };
