import { constructByInterface } from '@/utils/construct-by-interface';

export enum PrototypeSdizEnum {
  IN_RUSSIA = 1,
  IMPORT_TO_RUSSIA,
  EXPORT_FROM_RUSSIA,
}

export enum SdizTypeLot {
  IN_RUSSIA = 1,
  IMPORT_TO_RUSSIA,
  EXPORT_FROM_RUSSIA,
  ELEVATOR,
}

export interface OperationsVueDetailInterface {
  shipment: boolean;
  shipping: boolean;
  acceptance: boolean;
  realization: boolean;
}

export interface OperationsVueInterface {
  prototypes: Array<{ id: number; name: string }>;
  prototype_sdiz: number | null;
  sdiz_type: number | null;
  types: Array<string>;
  detail: OperationsVueDetailInterface;
}

export class OperationsVueModel implements OperationsVueInterface {
  prototypes: Array<{ id: number; name: string }> = [
    { id: 1, name: 'На территории РФ' },
    { id: 2, name: 'Ввоз на территорию РФ' },
    { id: 3, name: 'Вывоз с территории РФ' },
  ];
  /** 1 На территории, 2 Ввод, 3 Вывоз */
  prototype_sdiz: PrototypeSdizEnum | null = PrototypeSdizEnum.IN_RUSSIA;
  types: Array<string> = [];
  detail: OperationsVueDetailInterface = {
    shipment: false,
    shipping: false,
    acceptance: false,
    realization: false,
  };

  get sdiz_type() {
    let sdiz = 0;

    Object.keys(this.detail).forEach((key) => {
      if (this.detail[key]) {
        sdiz += this.getCodeByType(key);
      }
    });

    return sdiz;
  }

  set sdiz_type(value) {
    if (value >= 1000) {
      this.setDetail(1000);
      value -= 1000;
    }

    if (value >= 100) {
      this.setDetail(100);
      value -= 100;
    }

    if (value >= 10) {
      this.setDetail(10);
      value -= 10;
    }

    if (value >= 1) {
      this.setDetail(1);
    }
  }

  constructor(o?) {
    if (o) {
      constructByInterface(o, this, {}, true);
      if (typeof o.operations !== 'undefined') {
        this.detail = o.operations.info;
      }
    }
  }

  setDetail(code) {
    const type = this.getOprByCode(code);
    this.detail[type] = true;
  }

  getCodeByType(type: string): number {
    switch (type) {
      case 'shipment': // Отгрузка
        return 1;
      case 'shipping': // Перевозка
        return 10;
      case 'acceptance': // Приемка
        return 100;
      case 'realization': // Реализация
        return 1000;

      default:
        return -1;
    }
  }

  getType(s: number): string {
    switch (s) {
      case 1: // Отгрузка
        return 'shipment';
      case 10: // Перевозка
        return 'shipping';
      case 100: // Приемка
        return 'acceptance';
      case 1000: // Реализация
        return 'realization';

      default:
        return 'undefined';
    }
  }

  getOprByCode(code): string {
    const type: string = this.getType(code);

    switch (code) {
      case 1:
        this.types.push(' Отгрузка');
        break;
      case 10:
        this.types.push(' Перевозка');
        break;
      case 100:
        this.types.push(' Приемка');
        break;
      case 1000:
        this.types.push(' Реализация');
        break;
    }

    return type;
  }
}
