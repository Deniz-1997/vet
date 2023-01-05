import {Injectable} from '@angular/core';

@Injectable()
export class BrdService {
  static brdSrv = [
    {'reproduction': 'Воcпроизводство'},
    {'livestock-of-animals': 'Поголовье животных'},
    {'3-vet': '3-Вет'},
    {'1-vet-a': '1-Вет А'},
    {'disinfectants': 'Дез. средства'},
    {'2-vet': '2-Вет'},
    {'1-vet-g': '1-Вет Г'},
    {'leukemia': 'Лейкоз'},
    {'pigs-move': 'Движение свиней'},
  ];


  constructor() {
  }

}
