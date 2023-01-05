import { Service } from '@/plugins/service';

/** Сообщение нотификатора. */
type TNotifyMessage<T = 'error' | 'message'> = {
  /** Идентификатор сообщения. */
  id: string;
  /** Тип сообщения. */
  type: T;
  /** Текст сообщения. */
  text: string;
  /** Параметры сообщения. */
  params?: { [key: string]: any };
};

/** Обработчик события message. */
type TNotifyMessageListener = (options: TNotifyMessage<'message'>) => void;
/** Обработчик события error. */
type TNotifyErrorListener = (options: TNotifyMessage<'error'>) => void;
/** Обработчик событий hide и flush. */
type TNotifyEmptyListener = () => void;

/** Хранилище обработчиков событий. */
type TNotifyListeners = {
  /** Обработчики error. */
  error: Set<TNotifyErrorListener>;
  /** Обработчики error. */
  message: Set<TNotifyMessageListener>;
  /** Обработчики error. */
  hide: Set<TNotifyEmptyListener>;
  /** Обработчики error. */
  flush: Set<TNotifyEmptyListener>;
};

type TNotifyListener = TNotifyMessageListener | TNotifyEmptyListener | TNotifyErrorListener;
type TNotifyListenerEvent = keyof TNotifyListeners;

export default class Notify extends Service {
  constructor(ctx) {
    super(ctx);

    // TODO: Dirtyhack, пока не переедем
    this.$store.commit('errors/init', this);
  }

  /** Хранилище уведомлений. */
  private $messages: TNotifyMessage[] = [];
  /** Хранилище обработчиков событий. */
  private $listeners: TNotifyListeners = {
    error: new Set(),
    message: new Set(),
    hide: new Set(),
    flush: new Set(),
  };

  /** Количество активных сообщений. */
  get length(): number {
    return this.$messages.length;
  }

  /** Получить самое старое сообщение. */
  get head(): TNotifyMessage | undefined {
    return this.$messages[0];
  }

  /** Получить последнее сообщенеие. */
  get tail(): TNotifyMessage | undefined {
    return [...this.$messages].pop();
  }

  /** Запустить обработчики события message */
  private $runListeners(event: 'message', options: TNotifyMessage<'message'>);
  /** Запустить обработчики события error */
  private $runListeners(event: 'error', options: TNotifyMessage<'error'>);
  /** Запустить обработчики события hide */
  private $runListeners(event: 'hide');
  /** Запустить обработчики события flush */
  private $runListeners(event: 'flush');
  private $runListeners(event: string, options?: any) {
    this.$listeners[event].forEach((listener) => {
      listener(options);
    });
  }

  /** Найти все активные сообщения типа error. */
  find(type: 'error'): TNotifyMessage<'error'>[];
  /** Найти все активные сообщения типа message. */
  find(type: 'message'): TNotifyMessage<'message'>[];
  /** Найти все активные сообщения. */
  find(): TNotifyMessage[];
  find(type?: 'error' | 'message'): TNotifyMessage[] {
    if (!type) {
      return [...this.$messages];
    }

    return this.$messages.filter((message) => message.type === type);
  }

  /** Послать новое уведомление. */
  push(type: 'error' | 'message', options: Pick<TNotifyMessage, 'text' | 'params'>): string {
    const id = Math.random().toString(36).slice(2);

    const index = this.$messages.push({ id, type, ...options }) - 1;
    (this.$runListeners as any).call(this, type, this.$messages[index]);

    return id;
  }

  /** Скрыть уведомление по идентификатору. */
  hide(id: string) {
    this.$messages = this.$messages.filter((message) => id !== message.id);
    this.$runListeners('hide');
  }

  /** Скрыть все уведомления. */
  flush() {
    this.$messages = [];
    this.$runListeners('flush');
  }

  /** Подписаться на событие message */
  on(event: 'message', callback: TNotifyMessageListener);
  /** Подписаться на событие error */
  on(event: 'error', callback: TNotifyErrorListener);
  /** Подписаться на событие hide */
  on(event: 'hide', callback: TNotifyEmptyListener);
  /** Подписаться на событие flush */
  on(event: 'flush', callback: TNotifyEmptyListener);
  on(event: TNotifyListenerEvent, callback: TNotifyListener) {
    (this.$listeners[event] as any).add(callback);
  }

  /** Отписаться от события message */
  off(event: 'message', callback: TNotifyMessageListener);
  /** Отписаться от события error */
  off(event: 'error', callback: TNotifyErrorListener);
  /** Отписаться от события hide */
  off(event: 'hide', callback: TNotifyEmptyListener);
  /** Отписаться от события flush */
  off(event: 'flush', callback: TNotifyEmptyListener);
  off(event: TNotifyListenerEvent, callback: TNotifyListener) {
    (this.$listeners[event] as any).delete(callback);
  }
}
