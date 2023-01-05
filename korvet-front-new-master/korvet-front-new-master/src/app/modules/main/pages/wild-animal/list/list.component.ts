import {Component, OnInit} from '@angular/core';
import {ListFilterFieldInterface} from '../../../../shared/components/list-filter/list-filter.model';
import {ListFilterTypeEnum} from '../../../../shared/components/list-filter/list-filter.enum';
import {CrudType} from 'src/app/common/crud-types';
import {Observable} from 'rxjs';
import {ReferenceContractorModel} from '../../../../../models/reference/contractor.model';
import {select, Store} from '@ngrx/store';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  templateUrl: './list.component.html',
})
export class ListComponent implements OnInit {
  crudType = CrudType;
  type = CrudType.WildAnimal;
  filterFields: ListFilterFieldInterface[][];
  public ReferenceContractorItems$: Observable<ReferenceContractorModel[]>;
  c = '#';
  g = '22';
  d = 'demo';

  constructor(private store: Store<CrudState>) {
  }

  ngOnInit() {
    this.filterFields = [
      [
        {
          type: ListFilterTypeEnum.autocomplete,
          prop: 'contractorId',
          field: 'name',
          head: {value: 'Компания-отловщик'},
          attributes: {
            options: this.ReferenceContractorItems$,
            optionsType: CrudType.ReferenceContractor
          },
        },
        {
          type: ListFilterTypeEnum.date,
          head: {value: 'Период отлова c'},
          prop: '>=date'
        },
        {
          type: ListFilterTypeEnum.date,
          head: {value: 'по'},
          prop: '<=date'
        }

      ],
      [
        {
          type: ListFilterTypeEnum.text,
          head: {value: 'Номер чипа'},
          prop: 'chipNumber'
        },
        {
          type: ListFilterTypeEnum.text,
          head: {value: 'Номер бирки'},
          prop: 'cullingRegistrationHistory.tagNumber'
        },
        {
          type: ListFilterTypeEnum.text,
          head: {value: '№ животного при поступлении'},
          prop: 'animalNumber'
        },
      ]
    ];

    this.ReferenceContractorItems$ = this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceContractor}));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceContractor,
      params: {
        fields: {0: 'id', 2: 'name'},
        order: {surname: 'ASC'},
        offset: 0,
        limit: 10
      }
    }));
  }

  public getGender(val: string): string {
    switch (val) {
      case 'MALE':
        return 'Самец';
      case 'FEMALE':
        return 'Самка';
      default:
        return '-';
    }
  }
}
