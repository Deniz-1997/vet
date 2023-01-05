import {Injectable} from '@angular/core';
import {CrudType} from '../common/crud-types';
import {select, Store} from '@ngrx/store';
import {getTemplateReferenceStore} from '../store/crud/crud.selectors';
import {CrudState} from '../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../api/api-connector/crud/crud.actions';

@Injectable({
  providedIn: 'root'
})
export class FormTemplateService {

  constructor(
    protected store: Store<CrudState>,
  ) {
  }

  findProperty(properties, name: string) {
    return properties.find(property => {
      if (property.formFieldProperty && property.formFieldProperty.code === name) {
        return true;
      } else if (property.code && property.code === name) {
        return true;
      }
    });
  }

  getDateExtraData(properties, type: string): object {
    const extraData = {};
    let limit;
    let range;
    let rangeValue;

    if (type === 'date') {
      limit = this.findProperty(properties, 'date_limit').value;
      range = this.findProperty(properties, 'date_range').value;
      rangeValue = this.findProperty(properties, 'date_range_value').value;
      extraData['date'] = this._createDateExtraData({limit, range, rangeValue});
    } else if (type === 'multi_date') {
      limit = this.findProperty(properties, 'date_min_limit').value;
      range = this.findProperty(properties, 'date_min_range').value;
      rangeValue = this.findProperty(properties, 'date_min_range_value').value;
      extraData['dateMin'] = this._createDateExtraData({limit, range, rangeValue});

      limit = this.findProperty(properties, 'date_max_limit').value;
      range = this.findProperty(properties, 'date_max_range').value;
      rangeValue = this.findProperty(properties, 'date_max_range_value').value;
      extraData['dateMax'] = this._createDateExtraData({limit, range, rangeValue});
    }

    return extraData;
  }

  _createDateExtraData(params: { limit: string, range: string, rangeValue: string }) {
    const extraData = {
      min: null,
      max: null,
    };

    if (params.limit === 'current') {
      extraData.min = new Date();
      extraData.max = new Date();

    } else if (params.limit === 'gth') {
      extraData.min = new Date();
      const maxDate = new Date();
      switch (params.range) {
        case 'day':
          maxDate.setDate(maxDate.getDate() + +params.rangeValue);
          break;
        case 'week':
          maxDate.setDate(maxDate.getDate() + +params.rangeValue * 7);
          break;
        case 'month':
          maxDate.setDate(maxDate.getDate() + +params.rangeValue * 30);
          break;
        case 'quarter':
          maxDate.setDate(maxDate.getDate() + +params.rangeValue * 90);
          break;
        case 'year_half':
          maxDate.setDate(maxDate.getDate() + +params.rangeValue * 182);
          break;
        case 'year':
          maxDate.setDate(maxDate.getDate() + +params.rangeValue * 365);
          break;
      }
      if (params.range !== '') {
        extraData.max = maxDate;
      }

    } else if (params.limit === 'lth') {
      extraData.max = new Date();
      const minDate = new Date();
      switch (params.range) {
        case 'day':
          minDate.setDate(minDate.getDate() - +params.rangeValue);
          break;
        case 'week':
          minDate.setDate(minDate.getDate() - +params.rangeValue * 7);
          break;
        case 'month':
          minDate.setDate(minDate.getDate() - +params.rangeValue * 30);
          break;
        case 'quarter':
          minDate.setDate(minDate.getDate() - +params.rangeValue * 90);
          break;
        case 'year_half':
          minDate.setDate(minDate.getDate() - +params.rangeValue * 182);
          break;
        case 'year':
          minDate.setDate(minDate.getDate() - +params.rangeValue * 365);
          break;
      }
      if (params.range !== '') {
        extraData.min = minDate;
      }
    }

    return extraData;
  }

  createTemplateArr(template: string) {
    return template.split(new RegExp(/{{{.*?}}}/g));
  }

  isJSON(str) {
    try {
      return (JSON.parse(str) && !!str);
    } catch (e) {
      return false;
    }
  }

  createDate(dateString: string, addDays = 0): Date {
    const dateArray = dateString.split('.');
    const date: Date = new Date(+dateArray[2], +dateArray[1] - 1, +dateArray[0]);
    date.setDate(date.getDate() + addDays);
    return date;
  }

  dateToString(date: Date): string {
    let month = (date.getMonth() + 1).toString();
    month = month.length === 1 ? '0' + month : month;
    let day = date.getDate().toString();
    day = day.length === 1 ? '0' + day : day;
    return day + '.' + month + '.' + date.getFullYear().toString();
  }

  getTemplateReferences() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.TemplateReference, params: {'order': {'name': 'ASC'}, limit: 500}
    }));
    return this.store.pipe(select(getTemplateReferenceStore));
  }

}
