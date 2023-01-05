import {Injectable} from '@angular/core';

@Injectable()

export class DataNameService {
  public getDataName(property: string): string {
    switch (property.toLowerCase()) {
      case 'address':
        return 'Адрес';
      case 'owner':
        return 'Владелец';
      case 'ownername':
        return 'Имя владелеца';
      case 'ownerpatronymic':
        return 'Отчество владелеца';
      case 'doctor':
        return 'Врач';
      case 'kind':
        return 'Вид животного';
      case 'name':
        return 'Кличка';
      case 'gender':
        return 'Пол';
      case 'colour':
        return 'Масть';
      case 'breed':
        return 'Порода';
      case 'chip':
        return 'Чип';
      case 'stamps':
        return 'Клеймо';
      case 'birthdate':
        return 'Дата рождения';
      case 'vaccinationdate':
        return 'Дата вакцинации';
      case 'vaccine':
        return 'Наименование вакцины';
      case 'vaccineseria':
        return 'Серия вакцины';
      case 'vaccine_serial':
        return 'Серия вакцины';
      case 'vaccinedate':
        return 'Дата изготовления';
      case 'vaccineexpired':
        return 'Срок годности';
      default:
        return property;
    }
  }
}
