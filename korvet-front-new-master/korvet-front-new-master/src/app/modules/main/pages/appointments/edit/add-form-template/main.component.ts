import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import {CrudType} from '../../../../../../common/crud-types';
import {FormControl, FormGroup} from '@angular/forms';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {MatDialog} from '@angular/material/dialog';
import {MainComponent as TemplateComponent} from '../show-form-template/main.component';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({selector: 'app-appointments-add-form-template', templateUrl: './html.component.html'})

export class MainComponent implements OnInit {

  @Output() addFormTemplate: EventEmitter<any> = new EventEmitter();

  crudType = CrudType;
  control = new FormControl('');
  template$: Observable<{ id: number, fullName: string }[]>;
  public formGroup: FormGroup;
  public limit = 500;

  constructor(
    private store: Store<CrudState>,
    private dialog: MatDialog,
  ) {
  }

  ngOnInit() {
    this.template$ = this.store.pipe(select(getCrudModelData, {type: CrudType.FormTemplate}));
  }

  addTemplateTo(type?) {
    if (this.control.value && this.control.value.id) {
      this.store.dispatch(new LoadGetAction(
        {
          type: CrudType.FormTemplate, params: this.control.value.id,
          onSuccess: (res) => {
            if (res.response) {
              if (type === 'show') {
                this.showTemplate(res.response);
              } else {
                this.addFormTemplate.emit(res.response);
                this.control.setValue(null);
              }
            }
          }
        }));
    }
  }

  showTemplate(template) {
    const dialogRef = this.dialog.open(TemplateComponent, {
      width: window.innerWidth > 960 ? '60%' : '90%',
      height: '100% - 50px',
      data: {
        head: this.control.value.name,
        template: template,
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--blue',
            action: true,
            title: 'Добавить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this.addFormTemplate.emit(template);
        this.control.setValue(null);
      }
    });

  }

  hasTemplate(): boolean {
    return !(this.control.value instanceof Object);
  }

}
