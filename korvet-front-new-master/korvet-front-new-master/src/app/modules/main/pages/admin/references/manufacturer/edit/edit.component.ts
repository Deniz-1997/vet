import {Component, Inject, Optional} from '@angular/core';
import {CrudType} from 'src/app/common/crud-types';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceManufacturerModel} from '../../../../../../../models/reference/reference.manufacturer.models';
import {Observable} from 'rxjs';
import {ReferenceCountriesModel} from '../../../../../../../models/reference/reference.countries.models';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['store', 'reference-manufacturer'];
  protected titleName = 'Производителя';
  crudType = CrudType;
  public countriesItems: Observable<ReferenceCountriesModel[]>;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceManufacturer, ReferenceManufacturerModel, data.id, data.openDialog);
  }

  protected setModel() {

    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      countries: new FormControl(this.item.countries, [Validators.required]),
    });
  }


}
