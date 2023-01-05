import {ChangeDetectorRef, Component, Inject, Injector, OnInit, Optional, ViewChild} from '@angular/core';
import { select, Store } from '@ngrx/store';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { OwnerModel } from '../../../../../models/owner/owner.models';
import { PersonType } from '../../../../../utils/person-type';
import { merge, Observable, Observer, of } from 'rxjs';
import { filter, map, take } from 'rxjs/operators';
import { BreadcrumbsService } from '../../../../../services/breadcrumbs.service';
import { ReferenceOwnerActivityModel } from '../../../../../models/reference/reference.owner.activity.models';
import { Location } from '@angular/common';
import { ReferenceOwnerLegalFormModel } from '../../../../../models/reference/reference.owner.legal.form.models';
import { AppointmentModel } from '../../../../../models/appointment/appointment.models';
import { ReferenceFileTypeModel } from '../../../../../models/reference/reference.file.type.models';
import { FileOwnerModel } from '../../../../../models/file/file.models';
import { MatchesListViewComponent } from '../../../../shared/components/matches-list-view/matches-list-view.component';
import { RouterOutletService } from '../../../../../services/router-outlet.service';
import { CrudType } from 'src/app/common/crud-types';
import { EnumModel } from '../../../../../models/enum .models';
import { PetsService } from 'src/app/services/pets.service';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction, LoadGetAction, LoadPatchAction, LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelMatches, getCrudModelMatchesLoading, getCrudModelGetLoading, getCrudModelData, getCrudModelStoreId} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({ templateUrl: './edit.component.html' })

export class EditComponent implements OnInit {
  type = CrudType.Owner;
  id: string;
  petId: string;
  owner = new OwnerModel();
  typeFormGroup: FormGroup;
  personType = PersonType;
  owner$: Observable<OwnerModel>;
  ownerLegalForms$: Observable<ReferenceOwnerLegalFormModel[]>;
  loading$: Observable<boolean>;
  matches$: Observable<OwnerModel[]>;
  matchesLoading$: Observable<boolean>;
  ownerActivities$: Observable<ReferenceOwnerActivityModel[]>;
  appointments$: Observable<AppointmentModel[]>;
  fileTypes$: Observable<ReferenceFileTypeModel[]>;
  files$: Observable<FileOwnerModel[]>;
  DocumentTypeEnum: EnumModel[];
  private obs: Observer<OwnerModel>;
  @ViewChild(MatchesListViewComponent, { static: true })
  private matchesView: MatchesListViewComponent;
  openDialog = false;
  backButton = false;
  isDisabled = false;

  constructor(
    private store: Store<CrudState>,
    private router: Router,
    private route: ActivatedRoute,
    private changeDetectorRef: ChangeDetectorRef,
    private breadcrumbs: BreadcrumbsService,
    private location: Location,
    private fb: FormBuilder,
    private routerOutlet: RouterOutletService,
    private petsService: PetsService,
    @Optional() public dialogRef: MatDialogRef<EditComponent>,
    @Optional() @Inject(MAT_DIALOG_DATA) public data: { openDialog: boolean}

  ) {
    this.id = route.snapshot.paramMap.get('id');
    this.petId = route.snapshot.paramMap.get('petId');
    this.matches$ = store.pipe(select(getCrudModelMatches, { type: this.type }), map(data => <OwnerModel[]>data));
    this.matchesLoading$ = store.pipe(select(getCrudModelMatchesLoading, { type: this.type }));
    this.loading$ = store.pipe(select(getCrudModelGetLoading, { type: this.type }));
    this.ownerActivities$ = store.pipe(select(getCrudModelData, { type: CrudType.ReferenceOwnerActivity }));
    this.ownerLegalForms$ = store.pipe(select(getCrudModelData, { type: CrudType.ReferenceOwnerLegalForm }));
    this.appointments$ = store.pipe(select(getCrudModelData, { type: CrudType.Appointment }));
    this.fileTypes$ = store.pipe(select(getCrudModelData, { type: CrudType.ReferenceFileType }));
    this.files$ = store.pipe(select(getCrudModelData, { type: CrudType.FileOwner }));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Enum,
      params: {
        filter: {
          id: [
            'DocumentTypeEnum',
          ]
        }
      },
      onSuccess: (res) => {
        return res.response.map(
          item => {
            this[item.id] = item.items;
          }
        );
      }
    }));
  }

  ngOnInit() {
    if (this.data !== null) {
      this.backButton = true;
      this.openDialog = true;
      this.isDisabled = true;
    }
    this.typeFormGroup = this.fb.group({
      type: [{ value: PersonType.INDIVIDUAL_PERSON, disabled: !!this.id }],
    });
    let owner$: Observable<OwnerModel>;
    if (this.id) {
      this.store.dispatch(new LoadGetAction({ type: this.type,
        params: <any>{
          id: this.id,
          fields: {
            0: 'id',
            1: 'type',
            2: 'name',
            3: 'phone',
            4: 'email',
            5: 'additionalContacts',
            6: 'inn',
            7: 'fullName',
            8: 'passport',
            9: 'address',
            10: 'individualPerson',
            11: 'legalEntity',
            12: 'legalForm',
            13: 'entrepreneur',
            14: 'farm',
            15: 'farmMembers',
            16: 'activities',
            17: 'customActivities',
            18: 'status',
            19: 'contactPersons',
            20: 'contractDateTo',
            21: 'gender',
            23: 'deleted',
            'pets': {
              0: 'id', 1: 'mainOwner', pet: ['id', 'name', 'type', 'breed', 'lear', 'description',
                'aggressive', 'aggressiveType', 'isSterilized', 'gender', 'birthday', 'vaccinationDate', 'chipNumber',
                'address', 'isDead', 'dateOfDeath'], owner: ['id']
            }
          }
        },
      }));
      owner$ = this.store.pipe(
        select(getCrudModelStoreId, { type: this.type, params: this.id }),
        filter(owner => !!owner),
      );
      owner$.pipe(take(1)).subscribe(owner => {
        this.typeFormGroup.controls['type'].setValue(owner.type);
        this.breadcrumbs.replaceLabelByIndex(owner.name, 2);
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
        params: { order: { sort: 'ASC', name: 'ASC' } }
      }));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceOwnerLegalForm,
      params: { order: { id: 'DESC' } }
    }));
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceFileType
    }));
    if (this.id) {
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.Appointment,
        params: { filter: { owner: { id: this.id } } }
      }));
      this.store.dispatch(new LoadGetListAction({
        type: CrudType.FileOwner,
        params: { filter: { owner: { id: this.id } } }
      }));
    }
    if (this.petId) {
      this.typeFormGroup.addControl('mainOwner', new FormControl(true));
    }
  }

  submit(formValue): void {
    const action = formValue.id ? LoadPatchAction : LoadCreateAction;
    this.store.dispatch(new action({
      type: this.type,
      params: <any>{
        type: this.typeFormGroup.value['type'],
        ...formValue,
      },
      onSuccess: res => {
        if (this.petId) {
          const that = this;
          this.petsService.addOwner(+this.petId, +res.response.id, this.typeFormGroup.value['mainOwner'], function (r) {
            if (r.status === true) {
              const n = ['pets', that.petId];
              that.router.navigate(n).then();
            }
            return this;
          });
        } else {
          if (this.openDialog) {
            return this.closeDialog(res.response);
          }
          this.router.navigate(['owners', res.response.id], { relativeTo: this.route.root });
        }
      }
    }));
  }

  cancel(): void {
    const previousUrl = this.routerOutlet.getPreviousUrl();
    if (previousUrl !== '') {
      this.router.navigate([previousUrl]).then();
    } else {
      this.router.navigate(['../'], { relativeTo: this.route.parent }).then();
    }
  }

  getMatches(formValue): void {
    if (this.id) {
      const type = this.typeFormGroup.value['type'];
      const matchesFilter = {
        '!id': this.id,
        type: type,
      };
      switch (type) {
        case PersonType.INDIVIDUAL_PERSON:
          Object.assign(matchesFilter, { email: formValue['email'] });
          break;
        default:
          Object.assign(matchesFilter, { inn: formValue['inn'] });
          break;
      }
      this.matchesView.getMatches({ filter: matchesFilter });
    }
  }

  pickMatch(match: OwnerModel): void {
    this.obs.next(match);
  }
  closeDialog(ownerModel) {
    this.dialogRef.close(ownerModel);
  }
}
