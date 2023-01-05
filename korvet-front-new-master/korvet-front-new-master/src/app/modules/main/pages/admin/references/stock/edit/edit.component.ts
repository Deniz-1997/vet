import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from 'src/app/common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceStockModel} from '../../../../../../../models/reference/stock';
import {Observable} from 'rxjs';
import {UnitsService} from '../../../../../../../services/units.service';
import {ReferenceUnitModel} from '../../../../../../../models/reference/reference.unit.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  crudType = CrudType;
  unitItems: Observable<ReferenceUnitModel[]>;
  protected listNavigate = ['admin', 'references', 'stock'];
  protected titleName = 'Склады';

  constructor(
    protected unitsService: UnitsService,
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceStock, ReferenceStockModel, data?.id, data?.openDialog);
    this.unitItems = unitsService.getUnits();
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      unit: new FormControl(this.item.unit, []),
      externalId: new FormControl(this.item.externalId, []),
      showInAppointment: new FormControl(this.item.showInAppointment ? this.item.showInAppointment : false, []),
    });
  }

}
