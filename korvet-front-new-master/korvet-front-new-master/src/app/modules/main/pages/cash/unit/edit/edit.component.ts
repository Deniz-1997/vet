import {Component, Inject, Optional} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {ReferenceUnitModel} from '../../../../../../models/reference/reference.unit.models';
import {CrudType} from 'src/app/common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({templateUrl: './edit.component.html'})

export class EditComponent extends ReferenceItemModels {
  protected listNavigate = ['cash', 'unit'];
  protected titleName = 'Клинику';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.ReferenceUnit, ReferenceUnitModel, data.id, data.openDialog);
  }

  protected setModel() {

    this.item.is_around_clock = (typeof this.item.is_around_clock === 'undefined') ? false : this.item.is_around_clock;

    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      coordinates: new FormControl(this.item.coordinates, []),
      address: new FormControl(this.item.address, [Validators.required]),
      without_registry: new FormControl(this.item.without_registry, []),
      is_around_clock: new FormControl(this.item.is_around_clock, []),
      full_name: new FormControl(this.item.full_name, []),
      email: new FormControl(this.item.email, []),
      phone: new FormControl(this.item.phone, []),
      website_url: new FormControl(this.item.website_url, []),
    });
  }

}
