import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {ListFilterElementInterface, ListFilterFieldInterface} from '../../../../../../shared/components/list-filter/list-filter.model';
import {ListFilterTypeEnum} from '../../../../../../shared/components/list-filter/list-filter.enum';
import {select, Store} from '@ngrx/store';
import {BehaviorSubject} from 'rxjs';
import {SearchModels} from '../../../../../../../models/search.models';
import {EditComponent} from '../edit/edit.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData, getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './list.component.html'})

export class ListComponent extends SearchModels implements OnInit {

  type = CrudType.ReferencePetLear;
  component = EditComponent;
  code = 'pets-type';
  currentTypes = new BehaviorSubject([]);
  filterFields: ListFilterFieldInterface[][];
  petTypesAttributes: ListFilterElementInterface = {options: []};
  breedsAttributes: ListFilterElementInterface = {options: []};
  breedField: ListFilterFieldInterface;
  crudType = CrudType;

  constructor(protected store: Store<CrudState>
  ) {
    super();
  }

  ngOnInit() {
    this.store.pipe(select(getCrudModelData, {type: CrudType.ReferenceBreed})).subscribe(item => this.breedsAttributes.options = item);
    this.breedField = {
      mutableSearchType: CrudType.Pet,
      type: ListFilterTypeEnum.multiSelect,
      head: {value: 'Порода животного'},
      prop: 'breed.id',
      attributes: this.breedsAttributes,
    };
    this.filterFields = [
      [
        {
          mutableSearchType: CrudType.Pet,
          type: ListFilterTypeEnum.multiSelect,
          head: {value: 'Вид животного'},
          prop: 'breed.type',
          attributes: this.petTypesAttributes,
        },
        this.breedField,
      ],
    ];
    this.store.dispatch(new LoadGetListAction({type: CrudType.ReferencePetType, params: {order: {sort: 'ASC', name: 'ASC'}}}));
    this.store.pipe(select(getCrudModelData, {type: CrudType.ReferencePetType}))
      .subscribe(data => this.petTypesAttributes.options = data);
    this.store.pipe(select(getCrudModelGetListLoading, {type: CrudType.ReferenceBreed}))
      .subscribe(loading => this.breedField.loading = loading);
  }

}
