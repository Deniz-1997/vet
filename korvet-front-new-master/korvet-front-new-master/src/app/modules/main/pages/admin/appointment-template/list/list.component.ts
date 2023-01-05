import {Component, OnInit} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';
import {Store} from '@ngrx/store';
import {SearchModels} from '../../../../../../models/search.models';
import {EditComponent} from '../edit/edit.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './list.component.html'})
export class ListComponent extends SearchModels implements OnInit {
  type = CrudType.AppointmentTemplate;
  component = EditComponent;
  code = 'referenceAppointmentTemplate';


  constructor(
    protected store: Store<CrudState>
  ) {
    super();
  }

  ngOnInit() {
  }

  lengthProducts(item): number {
    let count = item.products.length;

    if (count) {
      item.products.map(
        product => {
          count += product.children.length;
          return product;
        }
      );
    }

    return count;
  }

}
