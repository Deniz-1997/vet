import {Component, Inject} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';

@Component({
  selector: 'app-info-dialog',
  templateUrl: './info-dialog.component.html',
  styleUrls: ['./info-dialog.component.css']
})
export class InfoDialogComponent {
  objectKeys = Object.keys;

  translate = {
    registrationNumber: 'Регистрационный номер ККТ',
    fnsUrl: 'Адрес сайта ФНС',
    offlineMode: 'Признак автономного режима',
    machineInstallation: 'Признак установки принтера в автомате',
    bso: 'Признак АС БСО',
    encryption: 'Признак шифрования',
    autoMode: 'Признак автоматического режима',
    machineNumber: 'Номер автомата',
    internet: 'Признак ККТ для расчетов только в Интернет',
    service: 'Признак расчетов за услуги',
    excise: 'Продажа подакцизного товара',
    gambling: 'Признак проведения азартных игр',
    lottery: 'Признак проведения лотереи',
    defaultTaxationType: 'СНО по умолчанию',
    ofdChannel: 'Канал обмена с ОФД',
    ffdVersion: 'Версия ФФД',
    paymentsAddress: 'Место расчетов',
    name: 'Название ОФД',
    vatin: 'ИНН ОФД',
    host: 'Адрес ОФД',
    port: 'Порт ОФД',
    dns: 'DNS ОФД',

    email: 'E-mail организации (адрес отравителя электронных чеков)',
    address: 'Адрес расчетов',

    taxationTypes: 'Список систем налогообложения',
    agents: 'Признак агента',

    organization: 'Информация об организации',
    device: 'Параметры ККТ',
    ofd: 'Параметры ОФД',

    cashRegisterId: 'ID кассового аппарата',
    warnings: 'Флаги предупреждений',

    type: 'Тип задания',

    fiscalParams: 'Фискальные параметры отчета',
    fiscalDocumentNumber: 'Номер ФД отчета',
    fiscalDocumentSign: 'ФПД отчета',
    fiscalDocumentDateTime: 'Дата и время отчета',
    shiftNumber: 'Номер смены',
    fnNumber: 'Номер ФН',
    // registrationNumber: 'РНМ',
    receiptsCount: 'Количество чеков за смену',
    // fnsUrl: 'Адрес сайта ФНС',

    errors: 'Ошибки обмена',
    documentNumber: 'Номер ФД, на котором произошла ошибка',
    fnCommandCode: 'Команда ФН, на которой произошла ошибка',

    fn: 'ФН',
    network: 'Сеть',

    code: 'Код',
    description: 'Текст',

    status: 'Состояние',
    notSentCount: 'Количество неотправленных ФД',
    notSentFirstDocNumber: 'Номер первого неотправленного ФД',
    notSentFirstDocDateTime: 'Дата и время первого неотправленного ФД',

  };

  constructor(
    public dialogRef: MatDialogRef<InfoDialogComponent>,
    @Inject(MAT_DIALOG_DATA) public data: any) {
  }

  getKey(item) {
    if (this.translate[item]) {
      return this.translate[item];
    } else {
      return item;
    }
  }

  getValue(value) {
    if (value === true) {
      return 'Да';
    } else if (value === false) {
      return 'Нет';
    } else if (value === null || value === 'null') {
      return '';
    } else {
      return value;
    }

  }

  ttt(val) {
    return typeof val === 'object';
  }

}
