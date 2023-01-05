import {Component, Inject, OnInit, Optional} from '@angular/core';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from '../../../../../../../common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {ReferenceMeasurementUnitsModel} from '../../../../../../../models/reference/reference.measurement.units.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';


@Component({
  selector: 'app-measurement-units-edit',
  templateUrl: './measurement-units-edit.component.html',
  styleUrls: ['./measurement-units-edit.component.css']
})
export class MeasurementUnitsEditComponent extends ReferenceItemModels {
  protected listNavigate = ['store', 'reference-measurement-units'];
  protected titleName = 'Единицы измерения';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<MeasurementUnitsEditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceMeasurementUnits, ReferenceMeasurementUnitsModel, data.id, data.openDialog);
  }

  protected setModel() {

    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
    });
  }
}

