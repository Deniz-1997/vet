import {Component, Input, OnInit, ViewChild} from '@angular/core';
import {CrudType} from '../../../../../../../common/crud-types';
import {ListFilterFieldInterface} from 'src/app/modules/shared/components/list-filter/list-filter.model';
import {Store} from '@ngrx/store';
import {ListFilterViewComponent} from 'src/app/modules/shared/components/list-filter-view/list-filter-view.component';
import {MatDialog} from '@angular/material/dialog';
import {ModalConfirmComponent} from 'src/app/modules/shared/components/modal-confirm/modal-confirm.component';
import {Params, Router} from '@angular/router';
import {ModalFormTemplateViewComponent} from 'src/app/modules/shared/components/modal-form-template-view/modal-form-template-view.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadDeleteAction, LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({selector: 'app-reference-list', templateUrl: './list.component.html'})

export class ListComponent implements OnInit {
  @Input() templateReferenceId: number;
  @ViewChild(ListFilterViewComponent, {static: true}) listFilterView: ListFilterViewComponent;
  crudType = CrudType.TemplateReferenceValues;
  filterFields: ListFilterFieldInterface[][];
  fieldList: any;
  templateReference;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    private dialog: MatDialog
  ) {
  }

  ngOnInit() {
    this.listFilterView.basicFilter = {templateReference: {id: this.templateReferenceId}};
    this.loadValues();
  }

  loadValues() {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.TemplateReference,
      params: {
        order: {surname: 'ASC'},
        offset: 0,
        limit: 500,
        filter: {id: this.templateReferenceId}
      },
      onSuccess: response => {
        this.fieldList = response.response.items[0]['referenceValues'];
        this.templateReference = response.response.items[0];
      }
    }));
  }

  onDelete(id) {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: '???? ?????????? ???????????? ?????????????? ?????????????????',
        headComment: '???????????????? ????????????????????',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: '????????????'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: '??????????????'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.store.dispatch(new LoadDeleteAction({
          type: CrudType.TemplateReferenceValues,
          params: {id: id},
          onSuccess: () => {
            this.loadValues();
          }
        }));
      }
    });
  }

  onEdit(id: number = null) {
    const editDialogRef = this.dialog.open(ModalFormTemplateViewComponent, {
      data: {
        head: '???????????????? ??????????????????????',
        actions: [
          {
            class: 'btn btn-light',
            action: false,
            title: '????????????'
          },
          {
            class: 'btn btn-primary',
            action: true,
            title: '??????????????????'
          },
        ],
        formTemplate: {
          fields: [
            {
              isRequired: true,
              name: '????????????????????????',
              defaultValue: id ? (this.fieldList.find(element => element.id === id)).name : null,
              formTemplateField: {
                type: {
                  code: 'text'
                }
              }
            }
          ]
        },
      }
    });
    editDialogRef.afterClosed().subscribe((result: Array<string>) => {
      if (result) {
        const action = id ? LoadPatchAction : LoadCreateAction;
        const params: Params = {
          name: result[0],
          templateReference: this.templateReference,
        };
        if (id) {
          (this.fieldList.find(element => element.id === id)).name = result[0];
          params.id = id;
        } else {
          this.templateReference.referenceValues = undefined;
        }
        this.store.dispatch(new action({
          type: CrudType.TemplateReferenceValues,
          params: params,
          onSuccess: () => {
            this.loadValues();
          }
        }));
      }
    });
  }

}
