import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Router} from '@angular/router';
import {CrudType} from '../../../../common/crud-types';
import {Observable} from 'rxjs';
import {NotifyService} from '../../../../services/notify.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadDeleteAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelCreateLoading, getCrudModelCreatePatchLoading, getCrudModelDeleteLoading} from 'src/app/api/api-connector/crud/crud.selectors';

declare var $: any;

@Component({
  selector: 'app-store-button-view',
  templateUrl: './store-button-view.component.html',
  styleUrls: ['./store-button-view.component.css']
})
export class StoreButtonViewComponent implements OnInit {

  @Input() state: { code: 'DRAFT' };
  @Input() goListUrl = '';
  @Input() id;
  @Input() type: CrudType;
  @Input() typeRemove: CrudType;

  @Output() changeStatus: EventEmitter<any> = new EventEmitter();
  @Input() disabled = false;

  loadingRemove$: Observable<boolean>;
  getLoading$: Observable<boolean>;
  getPatchLoading$: Observable<boolean>;

  constructor(
    protected store: Store<CrudState>,
    protected router: Router,
    protected notify: NotifyService
  ) {
  }

  ngOnInit() {
    this.getLoading$ = this.store.pipe(select(getCrudModelCreateLoading, {type: this.type}));
    this.getPatchLoading$ = this.store.pipe(select(getCrudModelCreatePatchLoading, {type: this.type}));
    if (this.type) {
      this.loadingRemove$ = this.store.pipe(select(getCrudModelDeleteLoading, {type: this.type}));
    }
  }

  remove($event?) {
    if ($event) {
      $event.preventDefault();
    }
    const that = this;
    return this.store.dispatch(new LoadDeleteAction({
      type: this.typeRemove || this.type,
      params: {id: that.id},
      onSuccess() {
        $('[data-fancybox-close]').trigger('click');
        const n = that.goListUrl.split('/');
        n.splice(0, 1);
        that.router.navigate(n).then();
      },
      onError: (err) => this.notify.handleErrors(err.errors)
    }));

  }

  changeStatusEmit($event: Event, model): void {
    $event.preventDefault();
    this.changeStatus.emit(model);
  }

}
