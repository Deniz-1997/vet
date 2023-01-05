import {Component, OnInit} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {Observable} from 'rxjs';
import {ViewService} from '../../view.service';
import {ActivatedRoute, Router} from '@angular/router';
import {CrudType} from 'src/app/common/crud-types';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelCreatePatchLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './form.component.html'})

export class FormComponent implements OnInit {

  loading$: Observable<boolean>;
  type = CrudType.Owner;
  ownerId: number;
  petId: number;

  constructor(
    private store: Store<CrudState>,
    private service: ViewService,
    private router: Router,
    private route: ActivatedRoute,
  ) {
    this.route.params.subscribe(params => {
      this.petId = +params['id'];
    });

    this.loading$ = store.pipe(select(getCrudModelCreatePatchLoading, {type: CrudType.Appointment}));

    this.owner$.subscribe(item => {
      if (item.id) {
        this.ownerId = item.id;
      }
    });
  }

  get owner$() {
    return this.service.owner$;
  }

  ngOnInit() {

  }

  cancel(): void {
    this.router.navigate(['/owners', this.ownerId, 'profile']).then();
  }
}
