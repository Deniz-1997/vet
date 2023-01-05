# Сервисы

Сервис -- это удобный способ выноса бизнес-логики из компонентов. Каждый из сервисов имеет доступ к контексту Vue, что позволяет изнутри его методов взаимодействовать с xhr, vuex, router, etc.

## Использование сервисов

Сервисы доступны глобально через контекст Vue. Чтобы использовать метод сервиса, надо просто к нему обратиться через его название и название сервиса.

```typescript
async handleClick() {
   await this.$service.auth.login(this.form);
   this.isLoggedIn = true;
}
```

## Добавление нового сервиса

Сервис добавляется в [/services](../../services) и представляет собой класс, наследуемый от базового класса [Service](./utils.ts).

```typescript
class Auth extends Service {
  async login(credentials: TAuthCredentials): Promise<any> {
    const response = await this.$axios.post('/api/auth/login', credentials);

    this.setTokens(response.data);

    return response;
  }

  async logout(): Promise<any> {
    const response = await this.$axios('/api/auth/logout');
    Cookie.remove('access_token');
    Cookie.remove('refresh_token');

    this.goLoginPage();

    return response;
  }

  goLoginPage() {
    this.$router.push({
      path: '/login',
      query: { returnTo: this.$route.path },
    });
  }
}
```

После написания сервис должен быть подключен в [index.ts](../../services/index.ts). Далее реализации и типизация подтянутся автоматически.

## Требования к сервису

- Код полностью написан и функционирует;
- Код в полной мере типизирован;
- Код в необходимом количестве покрыт комментариями (jsdoc);
- Код покрыт тестами минимум на 80%;
