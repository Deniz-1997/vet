import {Component, EventEmitter, Input, OnInit, Optional, Output} from '@angular/core';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {Router} from '@angular/router';
import {CrudType} from 'src/app/common/crud-types';
import {MatDialog, MatDialogRef} from '@angular/material/dialog';
import {ModalConfirmSumComponent} from '../modal-confirm-sum/modal-confirm-sum.component';
import {ApiResponse} from 'src/app/api/api-connector/api-connector.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading, getCrudModelCreatePatchLoading, getCrudModelDeleteLoading} from 'src/app/api/api-connector/crud/crud.selectors';

declare var $: any;

@Component({
  selector: 'app-reference-button-form',
  templateUrl: './reference-button-form.component.html',
  styleUrls: ['./reference-button-form.component.css']
})
export class ReferenceButtonFormComponent implements OnInit {
  @Input() showExtraButton = false;
  @Input() isEdit = false;
  @Input() type: CrudType;
  @Input() showSaveButton = true;

  @Input() goListUrl = '';
  @Input() goListUrlAfterRemove = '';
  @Input() id;
  @Input() hidden = false ? 'visibility: hidden' : '';
  @Input() disabled = false;
  @Input() removeAble = true;
  @Input() backButton = true;
  @Input() cancelButton = false;
  @Input() saveButtonName = 'Сохранить';
  @Input() removeButtonName = 'Удалить';
  @Input() ExtraButtonName = 'Провести';
  @Input() getLoading$: Observable<boolean>;
  @Input() closeWithCapture = false;
  @Output() extraButtonClick: EventEmitter<any> = new EventEmitter();
  @Output() buttonClick: EventEmitter<any> = new EventEmitter();



  loadingRemove$: Observable<boolean>;
  getPatchLoading$: Observable<boolean>;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    @Optional() public dialogRef: MatDialogRef<ReferenceButtonFormComponent>,
    protected dialog: MatDialog
  ) {
  }

  ngOnInit() {
    if (typeof this.type !== 'undefined') {
      this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: this.type}));
      this.getPatchLoading$ = this.store.pipe(select(getCrudModelCreatePatchLoading, {type: this.type}));
      if (this.type) {
        this.loadingRemove$ = this.store.pipe(select(getCrudModelDeleteLoading, {type: this.type}));
      }
    }
  }

  remove($event?) {
    if ($event) {
      $event.preventDefault();
    }
    const that = this;
    return this.store.dispatch(new LoadDeleteAction({
      type: this.type,
      params: {id: that.id},
      onSuccess(res: ApiResponse) {
        $('[data-fancybox-close]').trigger('click');
        const n = (that.goListUrlAfterRemove || that.goListUrl).split('/');
        n.splice(0, 1);
        that.router.navigate(n).then();
      }
    }));

  }

  onDelete($event) {
    const dialogRef = this.dialog.open(ModalConfirmSumComponent, {
      data: {
        head: `Вы уверены, что хотите удалить чек?`,
        body: 'Чек будет удален, действие необратимо.',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--blue',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Удалить'
          },
        ],
        numbersTitle: 'Введите сумму чисел для продолжения',
      },
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.remove($event);
      }
    });
  }

  isExtraButtonClick($event) {
    this.extraButtonClick.emit($event);
  }

  isButtonClick($event) {
    this.buttonClick.emit($event);
  }

  exit($event) {
    this.dialogRef.close();
  }

}