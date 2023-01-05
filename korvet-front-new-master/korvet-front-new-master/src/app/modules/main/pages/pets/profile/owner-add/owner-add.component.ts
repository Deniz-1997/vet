import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {OwnerModel} from '../../../../../../models/owner/owner.models';
import {PersonType} from '../../../../../../utils/person-type';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {PetsService} from '../../../../../../services/pets.service';
import {NotifyService} from '../../../../../../services/notify.service';
import {OwnersService} from '../../../../../../services/owners.service';
import {select, Store} from '@ngrx/store';
import {PetModel} from '../../../../../../models/pet/pet.models';
import {BreadcrumbsService} from '../../../../../../services/breadcrumbs.service';
import {Observable} from 'rxjs';
import {ModalConfirmComponent} from '../../../../../shared/components/modal-confirm/modal-confirm.component';
import {MatDialog} from '@angular/material/dialog';
import {CrudType} from 'src/app/common/crud-types';
import {ApiResponse} from 'src/app/api/api-connector/api-connector.models';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {getCrudModelTotalCount, getCrudModelMatchesLoading} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({templateUrl: './owner-add.component.html'})

export class OwnerAddComponent implements OnInit {
  type = CrudType.Owner;
  id: string;
  idPets: string;
  pet = new PetModel();
  public formGroup: FormGroup;
  model = new OwnerModel();
  matchesLength = 0;
  matchesLoading$: Observable<boolean>;
  showError = false;

  constructor(private router: Router,
              protected route: ActivatedRoute,
              private petsService: PetsService,
              private notify: NotifyService,
              private ownerService: OwnersService,
              private store: Store<CrudState>,
              private brdSrv: BreadcrumbsService,
              private dialog: MatDialog
  ) {
    this.route.params.subscribe(params => {
      this.idPets = params['id'];
    });

    this.store.pipe(select(getCrudModelTotalCount, {type: this.type})).subscribe((item: number) => this.matchesLength = item);
    this.matchesLoading$ = store.pipe(select(getCrudModelMatchesLoading, {type: this.type}));
  }

  ngOnInit() {
    if (this.idPets !== 'create') {
      this.petsService.getById(this.idPets).subscribe(res => {
        if (res && res.status === true) {
          this.pet = res.response;
          /*заменяем хлебные крошки*/
          this.brdSrv.replaceLabelByIndex(this.pet.name, 2);
          /*заменяем хлебные крошки*/
        }
      });
    }
    this.setModel();
  }

  addOwner(owner: OwnerModel) {
    const that = this;
    this.petsService.addOwner(+this.idPets, owner.id, false, function (res) {
      if (res.status === true && res.response && res.response.id) {
        const n = ['pets', that.idPets];
        that.router.navigate(n).then();
      }
      return this;
    });
  }

  submit(): void {
    this.formGroup.markAsTouched();
    this.showError = true;
    if (this.formGroup.valid && this.matchesLength) {
      this.onAdd();
    } else if (this.formGroup.valid) {
      this.submitAddOwner();
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }

  submitAddOwner() {
    const that = this;
    const model = this.formGroup.value;
    model.type = PersonType.INDIVIDUAL_PERSON;
    model.name = ((this.formGroup.value['fullName']['lastName'] + ' ' + this.formGroup.value['fullName']['name']).trim()
      + ' ' + this.formGroup.value['fullName']['middleName']).trim();
    that.ownerService.add(model).subscribe((res) => {
      if (res && res.status === true) {
        that.petsService.addOwner(+this.idPets, +res.response.id, this.formGroup.value['mainOwner'], function (r: ApiResponse) {
          if (r.status === true && r.response && r.response.id) {
            const n = ['pets', that.idPets];
            that.router.navigate(n).then();
          }
          return this;
        });
      }
    });
  }

  onAdd() {
    const dialogRef = this.dialog.open(ModalConfirmComponent, {
      data: {
        head: 'Вы точно хотите добавить нового владелеца?',
        headComment: 'Подобный владелец уже есть <br> (' + this.formGroup.value.name + ')',
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right',
            action: true,
            title: 'Добавить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result: boolean) => {
      if (result) {
        this.submitAddOwner();
      }
    });
  }

  protected setModel() {
    this.formGroup = new FormGroup({
      fullName: new FormGroup({
        name: new FormControl('', [Validators.required]),
        lastName: new FormControl('', [Validators.required]),
        middleName: new FormControl(''),
      }),
      phone: new FormControl('', [Validators.required]),
      email: new FormControl('', [Validators.required, Validators.pattern('[^ @]*@[^ @]*')]),
      address: new FormGroup({
        full: new FormControl('', []),
        coordinates: new FormControl('', []),
      }),
      mainOwner: new FormControl(true)
    })
    ;
  }
}
