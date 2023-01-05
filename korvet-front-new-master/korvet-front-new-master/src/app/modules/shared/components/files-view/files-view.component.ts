import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Store} from '@ngrx/store';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmComponent} from '../modal-confirm/modal-confirm.component';
import {FileModelType} from '../../../../models/file/file.models';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-files-view',
  templateUrl: './files-view.component.html',
  styleUrls: ['./files-view.component.css']
})
export class FilesViewComponent implements OnInit {

  @Input() fileType: CrudType.FileOwner | CrudType.File;
  @Input() files: FileModelType[];
  @Output() fileDelete = new EventEmitter<FileModelType>();
  @Output() fileAdd = new EventEmitter();

  constructor(
    private store: Store<CrudState>,
    private dialog: MatDialog,
  ) {
  }

  ngOnInit() {
  }

  deleteFile(file: FileModelType): void {
    const dialog = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы уверены, что хотите удалить файл ' + file.name + '?',
        actions: [
          {title: 'Отмена', class: 'btn-st btn-st--left btn-st--gray', action: false},
          {title: 'Удалить', class: 'btn-st btn-st--right btn-st--red', action: true},
        ]
      }
    });
    dialog.afterClosed().subscribe(answer => answer && this.store.dispatch(new LoadDeleteAction({
      type: this.fileType,
      params: {id: file.id},
      onSuccess: () => this.fileDelete.emit(file)
    })));
  }

  addFile(): void {
    this.fileAdd.emit();
  }

}
