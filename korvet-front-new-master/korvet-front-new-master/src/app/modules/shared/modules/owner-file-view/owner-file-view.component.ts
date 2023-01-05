import {Component, Input, OnInit} from '@angular/core';
import {OwnerModel} from '../../../../models/owner/owner.models';
import {AppointmentModel} from '../../../../models/appointment/appointment.models';
import {FileOwnerModel} from '../../../../models/file/file.models';
import {Observable} from 'rxjs';
import {Store} from '@ngrx/store';
import {MatDialog} from '@angular/material/dialog';
import {ModalFileAddFormComponent} from '../../components/modal-file-add-form/modal-file-add-form.component';
import {FileMonitoredObjectModel} from '../../../../models/file/file.monitored.object.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

@Component({
  selector: 'app-owner-file-view',
  templateUrl: './owner-file-view.component.html',
  styleUrls: ['./owner-file-view.component.css']
})
export class OwnerFileViewComponent implements OnInit {

  @Input() owner: OwnerModel;
  @Input() fileTypes$: Observable<FileMonitoredObjectModel[]>;
  @Input() appointments: AppointmentModel[];
  @Input() files: FileOwnerModel[];

  files$: Observable<FileOwnerModel[]>;
  type = CrudType.FileOwner;

  constructor(
    private store: Store<CrudState>,
    private dialog: MatDialog,
  ) {

  }

  ngOnInit() {
  }

  addFile(): void {
    const dialog = this.dialog.open(ModalFileAddFormComponent, {
      data: {
        subject: this.owner,
        fileTypes$: this.fileTypes$,
        appointments: this.appointments,
      }
    });
    /*dialog.afterClosed().subscribe(answer => answer && this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {filter: {owner: {id: this.owner.id}}}
    })));*/
  }

}
