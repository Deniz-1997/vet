import {Component, OnInit} from '@angular/core';
import {Observable} from 'rxjs';
import {ActivatedRoute, Router} from '@angular/router';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {RouterOutletService} from 'src/app/services/router-outlet.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelCreatePatchLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './add.component.html'})

export class AddComponent implements OnInit {
  loading$: Observable<boolean>;
  type = CrudType.Pet;
  petId: number;
  owner;

  constructor(
    private store: Store<CrudState>,
    private router: Router,
    private route: ActivatedRoute,
    private routerOutlet: RouterOutletService
  ) {
    this.loading$ = store.pipe(select(getCrudModelCreatePatchLoading, {type: CrudType.Appointment}));
  }

  ngOnInit() {
    const appointmentId = this.route.snapshot.paramMap.get('appointmentId');
    this.route.params.subscribe(params => {
      this.petId = +params['id'];
    });
  }

  cancel(): void {
    const previousUrl = this.routerOutlet.getPreviousUrl();
    if (previousUrl !== '') {
      this.router.navigate([previousUrl]);
    } else {
      this.router.navigate(['../'], {relativeTo: this.route.parent}).then();
    }
  }

  submit(value: Object): void {

  }
}
