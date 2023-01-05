import {Component, Input, OnInit} from '@angular/core';
import {FormGroup, Validators} from '@angular/forms';
import {EnumModel} from '../../../../models/enum .models';

@Component({
  selector: 'app-contact-document',
  templateUrl: './contact-document.component.html',
  styleUrls: ['./contact-document.component.css']
})
export class ContactDocumentComponent implements OnInit {

  @Input() formGroup: FormGroup;
  @Input() DocumentTypeEnum: EnumModel[];
  masks = [
    {
      id: 1,
      name: 'R-ББ 999999',
      mask: '(I|V|X|L|C|1|У|Х|Л|С)-[А-Я]{2} [0-9]{6}',
      series: '(I|V|X|L|C|1|У|Х|Л|С)-[А-Я]{2}',
      number: '[0-9]{6}',
      seriesTextMask: [/(I|V|X|L|C|1|У|Х|Л|С)/, '-', /[А-Я]/, /[А-Я]/],
      numberTextMask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/],
    },
    {
      id: 2,
      name: '99 0999999',
      mask: '[0-9]{2} [0-9]{6,7}',
      series: '[0-9]{2}',
      number: '(_?)([0-9]{6,7})(_?)',
      seriesTextMask: [/\d/, /\d/],
      numberTextMask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/],
    },
    {
      id: 3,
      name: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
      mask: '(.*){0,25}',
      series: '(.*){0,25}',
      number: '(.*){0,25}',
      maskLimit: 25,
      seriesTextMask: [],
      numberTextMask: [],
    },
    {
      id: 4,
      name: 'ББ 0999999',
      mask: '[А-Я]{2} [0-9]{6,7}',
      series: '[А-Я]{2}',
      number: '(_?)([0-9]{6,7})(_?)',
      seriesTextMask: [/[А-Я]/, /[А-Я]/],
      numberTextMask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/],
    },
    {
      id: 5,
      name: 'ББ 999999',
      mask: '[А-Я]{2} [0-9]{6}',
      series: '[А-Я]{2}',
      number: '[0-9]{6}',
      seriesTextMask: [/[А-Я]/, /[А-Я]/],
      numberTextMask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/],
    },
    {
      id: 6,
      name: 'ББ 9999999',
      mask: '[А-Я]{2} [0-9]{7}',
      series: '[А-Я]{2}',
      number: '[0-9]{7}',
      seriesTextMask: [/[А-Я]/, /[А-Я]/],
      numberTextMask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/],
    },
    {
      id: 7,
      name: '99 9999999',
      mask: '[0-9]{2} [0-9]{7}',
      series: '[0-9]{2}',
      number: '[0-9]{7}',
      seriesTextMask: [/\d/, /\d/],
      numberTextMask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/],
    },
    {
      id: 8,
      name: 'ББ-999 9999999',
      mask: '[А-Я]{2}-[0-9]{3} [0-9]{7}',
      series: '[А-Я]{2}-[0-9]{3}',
      number: '[0-9]{7}',
      seriesTextMask: [/[А-Я]/, /[А-Я]/, '-', /\d/, /\d/, /\d/],
      numberTextMask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/],
    },
    {
      id: 9,
      name: '99 99 9999990',
      mask: '[0-9]{2} [0-9]{2} [0-9]{s}',
      series: '[0-9]{2} [0-9]{2}',
      number: '(_?)([0-9]{6,7})(_?)',
      seriesTextMask: [/\d/, /\d/, ' ', /\d/, /\d/],
      numberTextMask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/],
    },
  ];
  seriesAndNumberMaskNames = {
    BIRTH_CERTIFICATE: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    CERTIFICATE_APPLICATION_REFUGEE: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    DEATH_CERTIFICATE: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    DIPLOMATIC_PASSPORT: '99 9999999',
    FOREIGN_CITIZEN_PASSPORT: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    FOREIGN_CITIZEN_RESIDENCE_PERMIT: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    FOREIGN_STATE_BIRTH_CERTIFICATE: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    MILITARY_ID: 'ББ 0999999',
    NAVY_MINISTRY_PASSPORT: 'ББ 999999',
    OFFICER_IDENTITY_CARD: 'ББ 0999999',
    OTHER_DOCS: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    REFUGEE_CERTIFICATE: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    RELEASE_FROM_PRISON_CERTIFICATE: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    RESIDENCE_PERMIT: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    RESIDENCE_PLACE_CONFIRM_DOCS: '',
    RESIDENCE_PLACE_REGISTRATION_CERTIFICATE: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    RF_CITIZEN_INTERNATIONAL_PASSPORT: '99 9999999',
    RF_CITIZEN_PASSPORT: '99 99 9999990',
    RF_CITIZEN_TEMPORARY_IDENTITY_CARD: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    RF_SERVICEMAN_IDENTITY_CARD: 'ББ 9999999',
    SAILOR_PASSPORT: 'ББ 0999999',
    STOCK_OFFICER_MILITARY_CARD: 'ББ 0999999',
    TEMPORARY_ASYLUM_CERTIFICATE: 'ББ-999 9999999',
    TEMPORARY_ID_INSTEAD_OF_MILITARY_ID: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    TEMPORARY_RESIDENCE_PERMIT: 'SSSSSSSSSSSSSSSSSSSSSSSSS',
    USSR_CITIZEN_INTERNATIONAL_PASSPORT: '99 0999999',
    USSR_CITIZEN_PASSPORT: 'R-ББ 999999',
    null: ''
  };

  constructor() {

  }

  private _seriesTextMask = {};

  get seriesTextMask(): {} {
    return this._seriesTextMask;
  }

  set seriesTextMask(mask: {}) {
    this._seriesTextMask = mask;
  }

  private _numberTextMask = {};

  get numberTextMask(): {} {
    return this._numberTextMask;
  }

  set numberTextMask(mask: {}) {
    this._numberTextMask = mask;
  }

  ngOnInit() {
    this._seriesTextMask = {mask: false};
    this._numberTextMask = {mask: false};
    const documentCode = this.formGroup.get('passport.documentType.code');
    if (documentCode) {
      this.setSeriesAndNumberMask(this.getCurrentMask(documentCode.value), true);
      documentCode.valueChanges.subscribe(code => {
        const currentMask = this.getCurrentMask(code);
        this.setSeriesAndNumberMask(currentMask, false);
      });
    }
  }

  public getSeriesAndMaskFormat() {
    const documentCode = this.formGroup.get('passport.documentType.code').value;
    const mask = this.getCurrentMask(documentCode);
    if (!mask) {
      return '';
    }
    return mask['name'];
  }

  public getNumberFormat() {

  }

  private getCurrentMask(code) {
    if (!this.seriesAndNumberMaskNames[code]) {
      return false;
    }
    const maskName = this.seriesAndNumberMaskNames[code];
    return this.masks.find(mask => mask.name === maskName);
  }

  private setSeriesAndNumberMask(currentMask, init = false) {
    if (!currentMask) {
      return false;
    }
    if (currentMask.seriesTextMask.length > 0) {
      this.seriesTextMask = {mask: currentMask.seriesTextMask, showMask: true};
    } else {
      this.seriesTextMask = {mask: false, showMask: false};
      const seriesElement = (<HTMLInputElement>document.querySelector('input[formcontrolname="series"]'));
      if (seriesElement && seriesElement.value) {
        if (!init) {
          this.formGroup.get('passport.series').setValue('');
          seriesElement.value = '';
        }
      }
    }
    if (currentMask.numberTextMask.length > 0) {
      this.numberTextMask = {mask: currentMask.numberTextMask, showMask: true};
    } else {
      this.numberTextMask = {mask: false, showMask: false};
      const numberElement = (<HTMLInputElement>document.querySelector('input[formcontrolname="number"]'));
      if (numberElement && numberElement.value) {
        if (!init) {
          this.formGroup.get('passport.number').setValue('');
          numberElement.value = '';
        }
      }
    }

    if (currentMask.series) {
      this.formGroup.get('passport.series').setValidators(Validators.pattern(currentMask.series));
    } else {
      this.formGroup.get('passport.series').setValidators([]);
    }
    if (currentMask.number) {
      this.formGroup.get('passport.number').setValidators(Validators.pattern(currentMask.number));
    } else {
      this.formGroup.get('passport.number').setValidators([]);
    }
  }
}
