import debounce from 'lodash/debounce';
import throttle from 'lodash/throttle';

/** Отслеживание состояния пользовательской активности. */
export class UserActivity {
  /** Статус инициализации. */
  private static pending = false;
  /** Время последней активности. */
  private static lastActivity = Date.now();
  /** Находится ли вкладка в фокусе. */
  private static inFocus = document.hasFocus();
  /** Флаг активности пользователя. */
  static get isActive() {
    return UserActivity.inFocus && Date.now() - UserActivity.lastActivity <= 5000;
  }

  private $timerId: any = null;
  private $listeners = {
    online: new Set<(lastActivity?: number) => void>(),
    offline: new Set<(lastActivity?: number) => void>(),
  };

  constructor() {
    if (!UserActivity.pending) {
      // Отслеживаем клики, движения мыши, скролл и нахождение на вкладке.
      window.addEventListener('click', this.$updateActivity);
      window.addEventListener('mousemove', this.$updateActivity);
      window.addEventListener('scroll', this.$updateActivity);
      window.onfocus = () => this.$updateFocus(true);
      window.onblur = () => this.$updateFocus(false);
      UserActivity.pending = true;
    }
  }

  /** Флаг активности пользователя. */
  get isActive() {
    return UserActivity.isActive;
  }

  /** Время последней активности. */
  get lastActivity() {
    return new Date(UserActivity.lastActivity);
  }

  /** Пометить пользователя неактивным, если не было взаимодействий в течение минуты. */
  private $runInactiveTimer() {
    if (this.$timerId) {
      clearTimeout(this.$timerId);
    }
    this.$timerId = setTimeout(() => {
      this.$runListeners('offline');
    }, 60000);
  }

  /** Задать время последней активности пользователя. */
  private $updateActivity = throttle(() => {
    if (!this.isActive) {
      this.$runListeners('online', UserActivity.lastActivity);
    }
    UserActivity.lastActivity = Date.now();
    this.$runInactiveTimer();
  }, 60000);

  /** Задать состояния фокуса на вкладке. */
  private $updateFocus(isInFocus: boolean) {
    if (isInFocus) {
      if (!this.isActive) {
        this.$runListeners('online', UserActivity.lastActivity);
      }
      UserActivity.lastActivity = Date.now();
    } else if (this.isActive) {
      this.$runListeners('offline');
    }

    UserActivity.inFocus = isInFocus;
  }

  /** Запустить зарегистрированные обработчики по типу события. */
  private $runListeners = debounce((type: keyof UserActivity['$listeners'], lastActivity?: number) => {
    this.$listeners[type].forEach((callback) => callback(lastActivity));
  }, 300);

  /** Зарегистрировать обработчик события. */
  on(type: keyof UserActivity['$listeners'], callback: (lastActivity?: number) => void) {
    this.$listeners[type].add(callback);
  }

  /** Удалить обработчик события. */
  off(type: keyof UserActivity['$listeners'], callback: () => void) {
    this.$listeners[type].delete(callback);
  }
}

export default new UserActivity();
