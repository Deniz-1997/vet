import {ChangeDetectorRef, Component, Inject, OnInit, Optional, ViewChild} from '@angular/core';
import {select, Store} from '@ngrx/store';
import {FormBuilder, FormGroup} from '@angular/forms';
import {Location} from '@angular/common';
import {PersonType} from '../../../../../../../utils/person-type';
import {MatchesListViewComponent} from '../../../../../../shared/components/matches-list-view/matches-list-view.component';
import {ActivatedRoute, Router} from '@angular/router';
import {RouterOutletService} from '../../../../../../../services/router-outlet.service';
import {ReferenceOwnerLegalFormModel} from '../../../../../../../models/reference/reference.owner.legal.form.models';
import {BreadcrumbsService} from '../../../../../../../services/breadcrumbs.service';
import {OwnerModel} from '../../../../../../../models/owner/owner.models';
import {FileOwnerModel} from '../../../../../../../models/file/file.models';
import {merge, Observable, Observer, of} from 'rxjs';
import {filter, map, take} from 'rxjs/operators';
import {ReferenceOwnerActivityModel} from '../../../../../../../models/reference/reference.owner.activity.models';
import {AppointmentModel} from '../../../../../../../models/appointment/appointment.models';
import {ReferenceFileTypeModel} from '../../../../../../../models/reference/reference.file.type.models';
import {ReferenceContractorModel} from '../../../../../../../models/reference/contractor.model';
import {CrudType} from '../../../../../../../common/crud-types';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadGetListAction, LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelMatches, getCrudModelMatchesLoading, getCrudModelGetLoading, getCrudModelData, getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './edit.component.html'})

export class EditComponent implements OnInit {

  // @ViewChild(ViewContainerDirective) appViewContainer: ViewContainerDirective;

  type = CrudType.ReferenceContractor;
  id: string;
  typeFormGroup: FormGroup;
  personType = PersonType;
  owner = new ReferenceContractorModel();
  owner$: Observable<OwnerModel>;
  ownerLegalForms$: Observable<ReferenceOwnerLegalFormModel[]>;
  loading$: Observable<boolean>;
  matches$: Observable<OwnerModel[]>;
  matchesLoading$: Observable<boolean>;
  ownerActivities$: Observable<ReferenceOwnerActivityModel[]>;
  appointments$: Observable<AppointmentModel[]>;
  fileTypes$: Observable<ReferenceFileTypeModel[]>;
  files$: Observable<FileOwnerModel[]>;
  private obs: Observer<OwnerModel>;

  @ViewChild(MatchesListViewComponent, {static: true})
  private matchesView: MatchesListViewComponent;

  constructor(
    private store: Store<CrudState>,
    private router: Router,
    private route: ActivatedRoute,
    private changeDetectorRef: ChangeDetectorRef,
    private breadcrumbs: BreadcrumbsService,
    private location: Location,
    private fb: FormBuilder,
    private routerOutlet: RouterOutletService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: {openDialog: boolean , id: string}
  ) {
    this.id = route.snapshot.paramMap.get('id');

    this.matches$ = store.pipe(select(getCrudModelMatches, {type: this.type}), map(res => <OwnerModel[]>res));
    this.matchesLoading$ = store.pipe(select(getCrudModelMatchesLoading, {type: this.type}));
    this.loading$ = store.pipe(select(getCrudModelGetLoading, {type: this.type}));
    this.ownerActivities$ = store.pipe(select(getCrudModelData, {type: CrudType.ReferenceOwnerActivity}));
    this.ownerLegalForms$ = store.pipe(select(getCrudModelData, {type: CrudType.ReferenceOwnerLegalForm}));
    this.appointments$ = store.pipe(select(getCrudModelData, {type: CrudType.Appointment}));
    // this.fileTypes$ = store.pipe(select(getCrudModelData, {type: CrudType.ReferenceFileType}));
    // this.files$ = store.pipe(select(getCrudModelData, {type: CrudType.FileOwner}));
  }

  ngOnInit() {
    if (this.data.openDialog === true) {
      this.id = this.data.id;
    }
    this.typeFormGroup = this.fb.group({
      type: [{value: PersonType.LEGAL_ENTITY, disabled: !!this.id}],
    });
    let owner$: Observable<OwnerModel>;
    if (this.id) {
      this.store.dispatch(new LoadGetAction({type: this.type, params: this.id}));
      owner$ = this.store.pipe(
        select(getCrudModelStoreId, {type: this.type, params: this.id}),
        filter(owner => !!owner),
      );
      owner$.pipe(take(1)).subscribe(owner => {
        this.typeFormGroup.controls['type'].setValue(owner.type);
        this.breadcrumbs.replaceLabelByIndex(owner.name, 4);
      });
    } else {
      owner$ = of();
    }
    this.owner$ = merge(owner$, new Observable((obs: Observer<OwnerModel>) => {
      this.obs = obs;
    }));
    this.store.dispatch(
      new LoadGetListAction({
        type: CrudType.ReferenceOwnerActivity,
        params: {order: {sort: 'ASC', name: 'ASC'}}
      }));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceOwnerLegalForm,
      params: {order: {id: 'DESC'}}
    }));
    /*this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceFileType
    }));
    if (this.id) {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.Appointment,
        params: {filter: {owner: {id: this.id}}}
      }));
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.FileOwner,
        params: {filter: {owner: {id: this.id}}}
      }));
    }*/
  }

  submit(formValue): void {
    const action = formValue.id ? LoadPatchAction : LoadCreateAction;
    this.store.dispatch(new action({
      type: this.type,
      params: <any>{
        type: this.typeFormGroup.value['type'],
        ...formValue,
      },
      onSuccess: (res) => {
        console.log(res.response);
        if (this.data.openDialog === true) {
          this.dialogRef.close(res.response);
        }
        this.router.navigate(['/admin', 'references', 'contractor'], {relativeTo: this.route.root}).then();
      }
    }));
  }

  cancel(): void {
    const previousUrl = this.routerOutlet.getPreviousUrl();
    if (previousUrl !== '') {
      this.router.navigate([previousUrl]).then();
    } else {
      this.router.navigate(['../'], {relativeTo: this.route.parent}).then();
    }
  }

  getMatches(formValue): void {
    /*if (this.id) {
      const type = this.typeFormGroup.value['type'];
      const matchesFilter = {
        '!id': this.id,
        type: type,
      };
      switch (type) {
        case PersonType.INDIVIDUAL_PERSON:
          Object.assign(matchesFilter, {email: formValue['email']});
          break;
        default:
          Object.assign(matchesFilter, {inn: formValue['inn']});
          break;
      }
      this.matchesView.getMatches({filter: matchesFilter});
    }*/
  }

  pickMatch(match: OwnerModel): void {
    this.obs.next(match);
  }
}
