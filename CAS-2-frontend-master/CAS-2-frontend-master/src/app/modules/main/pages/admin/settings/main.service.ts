import {Injectable} from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class MainService {

  constructor() {
  }

  tr(item: string): string {
    switch (item) {
      case 'domain.code': {
        return 'Код площадки';
      }
      case 'contact.email': {
        return 'Контактный е-mail';
      }
      case 'contact.signature': {
        return 'Контактная подпись';
      }
      case 'contact.phone_number': {
        return 'Контактный номер телефона';
      }
      case 'settings.enable1c': {
        return 'Интеграция с 1С';
      }
      case 'map.center':
        return 'Центр карты';
      case 'map.apiKey':
        return 'Ключ API maps';
      case 'dadata.apiKey':
        return 'Сервис ДаДата';
      case 'map.boundedBy':
        return 'Ограничение поиска карт';
      default: {
        return item;
      }
    }
  }
}
