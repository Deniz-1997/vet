/**
 * Базовый класс сервиса. Из контекста доступны:
 * - $axios
 * - $route
 * - $router
 * - $service
 * - $store
 *
 * Если этого набора недостаточно, можно получить весь контекст обращением к $ctx.
 */
export class Service {
  constructor(protected $ctx: Vue) {}

  protected get $axios() {
    return this.$ctx.$axios;
  }

  protected get $route() {
    return this.$ctx.$route;
  }

  protected get $router() {
    return this.$ctx.$router;
  }

  protected get $service() {
    return this.$ctx.$service;
  }

  protected get $store() {
    return this.$ctx.$store;
  }

  protected get $config() {
    return this.$ctx.$config;
  }
}
