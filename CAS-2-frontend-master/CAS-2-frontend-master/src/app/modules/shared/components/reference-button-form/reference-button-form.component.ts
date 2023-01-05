import {Component, EventEmitter, Input, OnInit, Optional, Output} from '@angular/core';
import {Observable} from 'rxjs';
import {select, Store} from '@ngrx/store';
import {Router} from '@angular/router';
import {CrudType} from 'src/app/common/crud-types';
import {MatDialogRef} from '@angular/material/dialog';
import {
  getCrudModelCreateLoading,
  getCrudModelCreatePatchLoading,
  getCrudModelDeleteLoading
} from '../../../../api/api-connector/crud/crud.selectors';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';
import {LoadDeleteAction} from '../../../../api/api-connector/crud/crud.actions';
import {ApiResponse} from '../../../../api/api-connector/api-connector.models';

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
  @Output() extraButtonClick: EventEmitter<any> = new EventEmitter();
  @Output() buttonClick: EventEmitter<any> = new EventEmitter();


  loadingRemove$: Observable<boolean>;
  getPatchLoading$: Observable<boolean>;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    @Optional() public dialogRef: MatDialogRef<ReferenceButtonFormComponent>
  ) {
  }

  ngOnInit(): void {
    if (typeof this.type !== 'undefined') {
      this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: this.type}));
      this.getPatchLoading$ = this.store.pipe(select(getCrudModelCreatePatchLoading, {type: this.type}));
      if (this.type) {
        this.loadingRemove$ = this.store.pipe(select(getCrudModelDeleteLoading, {type: this.type}));
      }
    }
  }

  remove($event?: any): any {
    if ($event) {
      $event.preventDefault();
    }
    const that = this;
    return this.store.dispatch(new LoadDeleteAction({
      type: this.type,
      params: {id: that.id},
      onSuccess(res: ApiResponse): void {
        $('[data-fancybox-close]').trigger('click');
        const n = (that.goListUrlAfterRemove || that.goListUrl).split('/');
        n.splice(0, 1);
        that.router.navigate(n).then();
      }
    }));

  }

  isExtraButtonClick($event: any): void {
    this.extraButtonClick.emit($event);
  }

  isButtonClick($event: any): void {
    this.buttonClick.emit($event);
  }

  exit($event: any): void {
    this.dialogRef.close();
  }
}
