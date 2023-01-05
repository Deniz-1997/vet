import { userActivity } from '@/utils';
import isEqual from 'lodash/isEqual';
import isUndefined from 'lodash/isUndefined';
import { createLogger } from './createLogger';

/** Настройки для short-polling. */
type TShortPollingOptions<T> = {
  /** Идентификатор полинга, используется для отображения в отладочных сообщениях. */
  id: string;
  /** Интервал, с которым будут происходить запросы полинга. */
  delay: number;
  /** Запрос, который будет выполнятся с заданным промежутком. */
  callback(): Promise<T>;
};

/** Настройким для long-polling. */
type TLongPollingOptions<T> = {
  /** Идентификатор полинга, используется для отображения в отладочных сообщениях. */
  id: string;
  /** Запрос, ответ от которого будет ожидать полинг. */
  callback(): Promise<T>;
};

/** Текущий статус соединения. */
enum EPollingStatus {
  /** Соединение неактивно. */
  IDLE,
  /** Поллинг приостановлен. */
  PAUSE,
  /** Полинг в процессе. */
  PENDING,
  /** Полинг остановлен. */
  STOP,
}

/**
 * Создание полинга для получения инфорамации от внешнего источника во времени, приближенном к реальному.
 * Доступны два типа полинга:
 * - `short` -- запрашивает данные по переданному в `callback` методу каждые `delay` секунд.
 * - `long` -- ожидает ответа от переданного метода `callback` и сразу же делает новый запрос после ошибки или получения ответа.
 *
 * LongPolling при ошибке пытается восстановить соединение после задержки в `n * 20`, где `n` -- количество попыток.
 * Если соединение не восстановлено в течении 15 попыток, полинг останавливается и выбрасывает исключение.
 *
 * ShortPolling в случае ошибки просто повторяет запрос через указанное время.
 *
 * Для получения информации по полингу следует использовать обработчики, которые можно зарегистрировать через метод [`on`]{@link Polling#on}.
 * Поллинг можно принудительно прервать и возобновить с помощью методов [`stop`]{@link Polling#stop} и [`run`]{@link Polling#run}.
 */
export class Polling<T = any> {
  static polls: Map<string, Polling> = new Map();

  static stop() {
    Polling.polls.forEach((poll) => poll.stop());
  }

  static start() {
    Polling.polls.forEach((poll) => poll.run());
  }

  private readonly $logger: ReturnType<typeof createLogger>;
  private readonly $initPolling: (delay?: number) => void;
  private readonly $id: string;
  private $callbacks = new Set<(data: T) => void>();
  private $status: EPollingStatus = EPollingStatus.IDLE;
  private $timerId!: number | NodeJS.Timeout;
  private $prevData!: T;
  private $timer = {
    start: (...args) => setTimeout.apply(window, args as any),
    clear: () => {
      if (this.$timerId) {
        clearTimeout.call(window, this.$timerId as any);
      }
    },
  };

  constructor(type: 'short', options: TShortPollingOptions<T>, autorun?: boolean);
  constructor(type: 'long', options: TLongPollingOptions<T>, autorun?: boolean);
  constructor(type, options, autorun = true) {
    this.$id = options.id;
    this.$logger = createLogger({ prefix: `${type}-polling`, key: 'polling' });
    this.$initPolling = (delay?: number) => {
      if (type === 'short') {
        this.$initShortPolling(options, delay);
      }
      if (type === 'long') {
        this.$initLongPolling(options);
      }
    };

    // Регистрируем обработчики на активность пользователя, чтобы не спамить запросами, если пользователь неактивен.
    userActivity.on('online', (lastActivity = Date.now()) => {
      if (this.$status === EPollingStatus.PAUSE) {
        const delay = Date.now() - lastActivity > options.delay * 1000 ? 0 : Date.now() - lastActivity;
        this.run(delay);
      }
    });
    userActivity.on('offline', () => this.pause());

    if (autorun) {
      this.run();
    }

    Polling.polls.set(options.id, this);
  }

  private $runListeners(data: T) {
    if (!this.$prevData || !isEqual(data, this.$prevData)) {
      this.$callbacks.forEach((callback) => callback(data));
      this.$prevData = data;
    }
  }

  private async $initLongPolling(options: TShortPollingOptions<T>, delay = 0) {
    if (this.$status === EPollingStatus.PENDING) {
      if (delay) {
        this.$logger.warn(`(${options.id}) Connection was lost. Try to reconnect in ${delay} seconds.`);
      }

      this.$timer.clear();
      this.$timerId = this.$timer.start(async () => {
        try {
          const data = await options.callback();
          this.$runListeners(data);
          if (delay) {
            this.$logger.info(`(${options.id}) Connection is active.`);
          }
          this.$initLongPolling(options);
        } catch (err) {
          if (delay / 20 < 15) {
            this.$initLongPolling(options, delay + 20);
          } else {
            this.pause();
            throw err;
          }
        }
      }, delay * 1000);
    }
  }

  private $initShortPolling(options: TShortPollingOptions<T>, delay?: number) {
    if (this.$status === EPollingStatus.PENDING) {
      this.$timer.clear();
      this.$timerId = this.$timer.start(
        async () => {
          try {
            const data = await options.callback();
            this.$runListeners(data);
            this.$initShortPolling(options);
          } catch (_err) {
            this.$logger.warn(`(${options.id}) Something gone wrong. Next try in ${options.delay} seconds.`);
            this.$initShortPolling(options);
          }
        },
        isUndefined(delay) ? options.delay * 1000 : delay
      );
    }
  }

  /** Регистрирует обработчик получения обновления. */
  on(callback: (data: T) => void) {
    this.$callbacks.add(callback);
    this.run();
  }

  /** Удаляет обработчик обновления. */
  off(callback: (data: T) => void) {
    this.$callbacks.delete(callback);
    if (!this.$callbacks.size) {
      this.pause();
    }
  }

  /** Принудительно запускает поллинг, если не запущен. */
  run(delay = 0) {
    if ([EPollingStatus.IDLE, EPollingStatus.PAUSE].includes(this.$status)) {
      this.$status = EPollingStatus.PENDING;
      this.$initPolling(delay);
      this.$logger.warn(`(${this.$id}) Polling was succesfully runned.`);
    }
  }

  /** Принудительно приостанавливает поллинг, если запущен. */
  pause() {
    if ([EPollingStatus.PENDING].includes(this.$status)) {
      this.$status = EPollingStatus.PAUSE;
      this.$timer.clear();
      this.$logger.warn(`(${this.$id}) Polling was succesfully stopped.`);
    }
  }

  /** Принудительно останавливает поллинг, если запущен. */
  stop() {
    this.$status = EPollingStatus.STOP;
    this.$timer.clear();
    this.$logger.warn(`(${this.$id}) Polling was succesfully stopped.`);
  }
}
