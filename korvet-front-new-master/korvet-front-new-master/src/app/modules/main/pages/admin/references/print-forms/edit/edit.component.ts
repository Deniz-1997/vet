import {Component, ElementRef, Inject, Optional, ViewChild} from '@angular/core';
import {ReferenceItemModels} from '../../../../../../../models/reference/reference.item.models';
import {Store} from '@ngrx/store';
import {ActivatedRoute, Router} from '@angular/router';
import {NotifyService} from '../../../../../../../services/notify.service';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {CrudType} from 'src/app/common/crud-types';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {PrintFormsModel} from '../../../../../../../models/print/print-forms.models';
import {DataType} from '../../../../../../../common/data-type';
import {allowedFileTypes} from '../../../../../../../common/config';
import {MAT_DIALOG_DATA, MatDialog, MatDialogRef} from '@angular/material/dialog';
import {ModalFileAddFormComponent} from 'src/app/modules/shared/components/modal-file-add-form/modal-file-add-form.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({templateUrl: './edit.component.html', styleUrls: ['./edit.component.css']})

export class EditComponent extends ReferenceItemModels {
  @ViewChild('file') fileElement: ElementRef;
  acceptTypes: string;
  productFields = {0: 'id', 1: 'name'};
  printForms = {
    files: [],
    outputDir: ''
  };
  protected listNavigate = ['admin', 'references', 'print-forms'];
  protected titleName = 'Печатная форма';

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected route: ActivatedRoute,
    protected notify: NotifyService,
    protected brdSrv: BreadcrumbsService,
    protected dialog: MatDialog,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    super(CrudType.PrintForms, PrintFormsModel, data.id, data.openDialog);
    this.acceptTypes = Object.keys(allowedFileTypes).join(',');
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.PrintFormsList,
      onSuccess: (res) => {
        this.printForms = res.response;
      }
    }));
  }

  async uploadPrintFile(event: Event) {
    event.preventDefault();
    const documentName = this.formGroup.get('selectedPrintForm').value;
    const url = this.printForms['outputDir'] + documentName;
    const blob = await fetch(url).then(r => r.blob());
    const file = new File([blob], documentName);
    this.formGroup.get('originFileName').setValue(documentName);
    this.uploadFile(file);
  }

  uploadNewFile(event: Event): void {
    const dialogRef = this.dialog.open(ModalFileAddFormComponent, {
      data: {}
    });

    dialogRef.afterClosed().subscribe(() => {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.PrintFormsList,
        onSuccess: (data) => {
          this.printForms = data.response;
        }
      }));
    });
  }

  changeFile(event: Event): void {
    const files: FileList = event.target['files'];
    this.uploadFile(files.item(0));
  }

  uploadFile(file): void {
    return this.store.dispatch(new LoadCreateAction({
      type: CrudType.UploadedFile,
      params: {file: file},
      dataType: DataType.formData,
      onError: (err) => this.notify.handleErrors(err.errors),
      onSuccess: (res) => {
        this.formGroup.controls.file['controls']['id'].setValue(res.response.id);
        this.formGroup.controls.file['controls']['mimeType'].setValue(res.response.mimeType);
        this.formGroup.controls.file['controls']['name'].setValue(res.response.name);
        this.formGroup.controls.file['controls']['size'].setValue(res.response.size);
      }
    }));
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      name: new FormControl(this.item.name, [Validators.required]),
      enabled: new FormControl(!!this.item.enabled, [Validators.required]),
      file: new FormGroup({
        id: new FormControl(this.item.file ? this.item.file.id : null, [Validators.required]),
        mimeType: new FormControl(this.item.file ? this.item.file.mimeType : null),
        name: new FormControl(this.item.file ? this.item.file.name : null),
        size: new FormControl(this.item.file ? this.item.file.size : null),
      }),
      originFileName: new FormControl(this.item.originFileName ? this.item.originFileName : ''),
      type: new FormControl(this.item.type ? this.item.type : 'Owner', [Validators.required]),
      selectedPrintForm: new FormControl(this.item.originFileName ? this.item.originFileName : ''),
      actionsSearch: new FormControl(''),
    });
  }
}
