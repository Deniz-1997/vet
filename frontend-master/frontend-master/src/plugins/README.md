# Plugins

Обычные плагины подключаются в стандартном режиме. Добавлена возможность подключать плагины с доступом к контексту.

## Контекстные плагины

Представляют собой метод, принимающий Vue-контекст и возвращающий обычный плагин. (e.g. [Services](./service/index.ts)).
Подключаются через утилиту PluginChain в [App.vue](../App.vue).

```typescript
export default class App extends Vue {
  created() {
    new PluginChain(this).use(AxiosPlugin).use(ServicePlugin, services);
  }
}
```

### Требования к плагину

- Код полностью написан и функционирует;
- Код в полной мере типизирован;
- Код в необходимом количестве покрыт комментариями (jsdoc);
- Код покрыт тестами минимум на 80% (для тестирования подключения можно использовать утилиту [checkPluginRegistration](../../__tests__/plugins/__utils__/checkPluginRegistration.ts));
