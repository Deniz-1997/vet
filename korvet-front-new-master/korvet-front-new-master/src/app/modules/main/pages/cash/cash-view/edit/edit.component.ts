import {Component, OnInit} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {CashRegisterShiftDocumentsModel} from '../../../../../../models/cash/cash.register.shift.documents .models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels implements OnInit {
  protected listNavigate = ['cash', 'cash-receipt'];
  protected titleName = 'кассовый чек';

  constructor(
    protected store: Store<CrudState>,
  ) {
    super(CrudType.CashRegisterShiftDocuments, CashRegisterShiftDocumentsModel);
  }

  ngOnInit() {
  }

}
