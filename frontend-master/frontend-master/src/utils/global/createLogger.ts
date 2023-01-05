import get from 'lodash/get';
import set from 'lodash/set';
import memoize from 'lodash/memoize';

/** Опции логгера. */
type TLoggerOptions = {
  /** Если включено, то сообщения отображаются вне зависимости от окружения. */
  permanent?: boolean;
  /** Определяет ключ, через который отображение сообщений можно будет принудительно включить или отключить. */
  key?: string;
  /** Строка, которая будет выводиться перед сообщением в формате `[prefix]`. */
  prefix?: string;
};

/** Создаёт новый логгер, позваляющий гибко настраивать видимость сообщений и передавать префикс. */
export const createLogger = memoize((options: TLoggerOptions = {}, defaultLogger: Console = console) => {
  const isDev = process.env.NODE_ENV === 'development';
  if (options.key && !get(window, `_loggerFlags.${options.key}`)) {
    set(window, `_loggerFlags.${options.key}`, isDev);
  }

  return new Proxy(defaultLogger, {
    get(target, prop) {
      if (target[prop]) {
        return (...args) => {
          const isEnabled = !options.key || get(window, `_loggerFlags.${options.key}`, false);
          if (isEnabled && (options.permanent || isDev)) {
            if (options.prefix) {
              args.unshift(`[${options.prefix}]`);
            }
            target[prop].call(this, ...args);
          }
        };
      }

      return target[prop];
    },
  });
});
