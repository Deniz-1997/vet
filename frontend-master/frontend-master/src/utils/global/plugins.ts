import Vue, { PluginObject } from 'vue';

/** Регистрация контекстных плагинов. */
export class PluginChain {
  constructor(private readonly $ctx: Vue) {
    if (process.env.NODE_ENV === 'development') {
      window['$ctx'] = $ctx;
    }
  }

  /** Подключить контекстный плагин. */
  async use<T>(plugin: (ctx: Vue) => PluginObject<T>, options?: T) {
    Vue.use(await plugin(this.$ctx), options);

    return this;
  }
}
