import {Component, Input, OnInit} from '@angular/core';
import {MainService} from '../../../../main/pages/admin/settings/main.service';
import {CrudType, CrudTypes} from '../../../../../common/crud-types';
import {ListFilterFieldInterface} from '../../../components/list/list-filter/list-filter.model';
import {
  getAllProperties,
  getPropertyesType,
  PropertyViewObjectType,
} from '../../../decorators/property-type.decorator';
import {Store} from '@ngrx/store';
import {CrudState} from '../../../../../api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from '../../../../../api/api-connector/crud/crud.actions';





@Component({
  selector: 'app-reference-view',
  templateUrl: './reference-view.component.html',
  styleUrls: ['./reference-view.scss']
})
export class ReferenceViewComponent implements OnInit {
  @Input() item;
  private _type: CrudType;
  @Input()
  get type(): CrudType {
    return this._type;
  }
  set type(value: CrudType) {
    this._type = value;
    this._model = CrudTypes[value].model;
  }
  private _model: any;
  crudType = CrudType;
  filterFields: Array<Array<ListFilterFieldInterface>>;
  properties = [];
  propertyViewObjectType = PropertyViewObjectType;

  constructor(public settingsService: MainService,
              protected store: Store<CrudState>) {
  }

  ngOnInit(): void {
    if (this.type === this.crudType.User) {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.ReferenceBusinessEntity,
        params: {
          fields: {0: 'id', 1: 'name'},
          filter: {
            users: {id: this.item.id}
          }
        },
        onSuccess: response => {
          this.item.businessEntity = response.response.items;
        }}));
    }
    this.getClassMetadata();
  }

  getClassMetadata(): void {
    const propertiesName = getAllProperties(new this._model());
    for (const i in propertiesName) {
      if (propertiesName.hasOwnProperty(i)) {
        this.properties.push(getPropertyesType(new this._model(), propertiesName[i]));
      }
    }
  }
}
