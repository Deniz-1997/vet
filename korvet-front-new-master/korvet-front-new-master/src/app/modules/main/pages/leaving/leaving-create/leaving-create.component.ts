import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelCreatePatchLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import {CrudType} from 'src/app/common/crud-types';

@Component({
  selector: 'app-leaving-create',
  templateUrl: './leaving-create.component.html'
})
export class LeavingCreateComponent implements OnInit {

  loading$: Observable<boolean>;
  type = CrudType.Leaving;

  constructor(private store: Store<CrudState>, private router: Router) {
    this.loading$ = store.pipe(select(getCrudModelCreatePatchLoading, {type: CrudType.Leaving}));
  }

  ngOnInit() {
  }

  cancel(): void {
    this.router.navigate(['/leaving/leaving-list']).then();
  }

}

