import { DatePipe } from '@angular/common';
import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormArray, FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { ActivatedRoute, Router } from '@angular/router';
import { select, Store } from '@ngrx/store';
import { combineLatest, Observable, Subscription } from 'rxjs';
import { map } from 'rxjs/operators';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetAction, LoadPatchAction, LoadCreateAction, LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import { CrudType } from 'src/app/common/crud-types';
import { AppointmentOwnerPetModels } from 'src/app/models/appointment/appointment-owner-pet.models';
import { AppointmentModel } from 'src/app/models/appointment/appointment.models';
import { ProbeItemModel } from 'src/app/models/laboratory/probe-item.model';
import { ProbeSamplingModel } from 'src/app/models/laboratory/probe-sampling.mode';
import { ResearchDocumentModel } from 'src/app/models/laboratory/research-document.model';
import { OwnerModel } from 'src/app/models/owner/owner.models';
import { ModalLaboratoryFormComponent } from 'src/app/modules/shared/components/modal-laboratory-form/modal-laboratory-form.component';
import { PetAddFormComponent } from 'src/app/modules/shared/modules/pet-add-form/pet-add-form.component';
import { AuthService } from 'src/app/services/auth.service';
import { NotifyService } from 'src/app/services/notify.service';
import { PetsService } from 'src/app/services/pets.service';
import { EditComponent } from '../../../../../main/pages/owners/edit/edit.component';

@Component({
  selector: 'app-probe-sampling-form',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css']
})

export class ProbeSamplingComponent extends AppointmentOwnerPetModels implements OnInit {

  @Input() model: ProbeSamplingModel = new ProbeSamplingModel();
  @Input() isModal: boolean = false;
  @Input() appointment: AppointmentModel;
  @Output() afterSave = new EventEmitter<boolean>();
  id: string;
  public formGroup: FormGroup;
  showError = false;
  crudType = CrudType;
  loading$: Observable<boolean>;
  type = CrudType.ProbeSampling;
  title: string;
  showExtraButton: boolean = true;
  probeFields = { 0: 'id', 1: 'name', 2: 'price', 3: 'packing' };
  time = this.datePipe.transform(new Date(), 'HH:mm');
  private queryRouteSubscription: Subscription;
  owner: OwnerModel;

  constructor(protected store: Store<CrudState>, private authService: AuthService,
    private router: Router, private route: ActivatedRoute, protected petsService: PetsService,
    protected notify: NotifyService, private datePipe: DatePipe, private dialog: MatDialog,
  ) {
    super(petsService, store);
    this.id = route.snapshot.paramMap.get('id');
    this.loading$ = combineLatest([
      this.store.pipe(select(getCrudModelGetLoading, { type: this.type })),
      this.store.pipe(select(getCrudModelGetLoading, { type: CrudType.Appointment })),
    ]).pipe(map(loading => loading.some(l => l)));
  }


  ngOnInit() {
    this.title = (this.id || (this.model && this.model.id) ? 'Редактировать' : 'Создать');
    this.title = this.isModal ? 'Отбор проб' : this.title + ' отбор проб';
    this.authService.user$.subscribe((res) => {
      if (!this.model) this.model = new ProbeSamplingModel();
      this.model.user = res?.user;
    });
    if (this.model && this.model.id) {
      this.id = this.model.id.toString();
      this.setModel();
    } else if (this.id) {
      this.loadProbeSampling();
    }
    else {
      if (this.appointment) {
        this.model.appointment = this.appointment;
        this.model.owner = this.appointment.owner;
        this.model.pet = this.appointment.pet;
      }
      this.setModel();
      this.queryRouteSubscription = this.route.queryParams.subscribe(
        (queryParam: any) => {
          let appointmentId = queryParam['appintmentId'];
          if (appointmentId) {
            this.store.dispatch(new LoadGetAction({
              type: CrudType.Appointment, params: appointmentId,
              onSuccess: (res) => {
                if (res.response && res.status == true) {
                  this.model.owner = res.response.owner;
                  this.model.pet = res.response.pet;
                  this.model.appointment = res.response;
                  this.setModel();
                }
              }
            }));
          }
        }
      );
    }
  }

  loadProbeSampling() {
    this.store.dispatch(new LoadGetAction({
      type: CrudType.ProbeSampling, params: this.id,
      onSuccess: (res) => {
        if (res.response && res.status == true) {
          this.model = res.response;
          this.setModel();
        }
      }
    }));
  }

  addAppointmentToTitle() {
    if (this.model.appointment && this.model.appointment.id) {
      this.title += ' - прием от ' + this.model.appointment.date + ' "'
        + this.model.appointment.pet.name + '"';
    }
  }

  goListUrl() {
    return '/laboratory/probe-sampling';
  }

  protected setModel() {
    if (this.model.date) {
      this.time = this.datePipe.transform(new Date(this.model.date.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1')), 'HH:mm');
    }
    this.addAppointmentToTitle();
    this.formGroup = new FormGroup({
      date: new FormControl(this.model.date ? this.model.date : this.datePipe.transform(new Date(), 'dd.MM.yyyy'), [Validators.required]),
      pet: new FormControl(this.model.pet, [Validators.required]),
      owner: new FormControl(this.model.owner, [Validators.required]),
      time: new FormControl(this.time, [Validators.required]),
      comment: new FormControl(this.model.comment ? this.model.comment : '', []),
      probeItems: new FormArray([], [Validators.required]),
      paymentType: new FormControl(this.model.paymentType && this.model.paymentType.code === 'ELECTRONICALLY' ? false : true),
    });
    if (this.id || this.model.id) {
      const probs = this.formGroup.get('probeItems') as FormArray;
      for (const i in this.model.probeItems) {
        probs.push(this.getDefaultProbe(this.model.probeItems[i]));
        this.model.probeItems[i].researchDocuments.map(doc => {
          if (doc.id) {
            this.addResearchDocument(+i, doc);
          }
        });
      }
    }
    else {
      this.addProbItem();
      this.addResearchDocument(0);
    }
    this.formGroup.controls.pet.valueChanges.subscribe((pet) => {
      if (pet && pet.id) {
        this.petId = pet.id;
        this.setOwnerByPet();
      } else {
        this.petId = null;
      }
    });
    this.formGroup.controls.owner.valueChanges.subscribe(res => {
      if (res && res.id) {
        this.ownerId = res.id;
        this.setPetByOwner();
      } else {
        this.petId = null;
        this.ownerId = null;
        this.formGroup.controls['pet'].setValue(null);
      }
    });
  }

  submit(sent = false, createCashReceipt = false) {
    this.showError = true;
    if (this.formGroup.valid) {
      const model = { ...this.formGroup.value };
      model.user = this.model.user;
      if (this.id) {
        model.id = +this.id;
      }
      model.paymentType = { code: this.formGroup.controls['paymentType'].value == false ? 'ELECTRONICALLY' : 'CASH' }
      if (sent) {
        for (const i in model.probeItems) {
          for (const j in model.probeItems[i].researchDocuments)
            model.probeItems[i].researchDocuments[j].status.code = 'SENT';
        }
      }
      model.date = this.datePipe.transform(new Date(this.formGroup.controls.date.value.replace(/(\d+).(\d+).(\d+)/, '$3/$2/$1')), 'dd.MM.yyyy') + ' ' + this.formGroup.controls.time.value + ':00';
      model.pet = { id: model.pet.id };
      if (this.model.appointment) {
        model.appointment = { id: this.model.appointment.id };
      }
      const action = this.model.id ? LoadPatchAction : LoadCreateAction;
      this.store.dispatch(new action({
        type: this.type,
        params: model,
        onSuccess: (res) => {
          this.model = res.response;
          if (createCashReceipt) {
            this.createReceipt();
          } else if (this.isModal) {
            this.afterSave.emit(true);
          } else {
            this.router.navigate(['laboratory', 'probe-sampling']);
          }
        }
      }));
    } else {
      if (!(this.formGroup.controls['probeItems'] as FormArray).length) {
        this.notify.handleMessage('Необходимо добавить пробу', 'warning');
      } else if (this.formGroup.controls['probeItems'].status === "INVALID" && this.researchIsEmpty()) {
        this.notify.handleMessage('Необходимо добавить исследование', 'warning');
      } else {
        this.notify.handleMessage('Заполните обязательные поля', 'warning');
      }
      this.showError = true;
    }
  }

  researchIsEmpty() {
    const probs = this.formGroup.get('probeItems') as FormArray;
    for (const i in probs.controls) {
      if (probs.status === "INVALID" && !(probs.controls[i].get('researchDocuments') as FormArray).length) {
        return true;
      }
    }
    return false;
  }

  addProbItem() {
    if (!this.model.probeItems) {
      this.model.probeItems = [];
    }
    this.model.probeItems.push(new ProbeItemModel());
    const documents = this.formGroup.get('probeItems') as FormArray;
    const item = this.getDefaultProbe();
    documents.push(item);

  }

  getDefaultProbe(probe: ProbeItemModel = null): FormGroup {
    const group = new FormGroup({
      amount: new FormControl(probe?.amount ? probe?.amount : 0, []),
      probe: new FormControl(probe?.probe, [Validators.required]),
      code: new FormControl(probe?.code, []),
      price: new FormControl(probe?.price ? probe?.price : 0, []),
      quantity: new FormControl(probe?.quantity ? probe?.quantity : 1, []),
      packing: new FormControl(probe?.packing, [Validators.required]),
      researchDocuments: new FormArray([], [Validators.required]),
      date: new FormControl(probe?.date ? probe?.date : new Date(), [Validators.required]),
    });

    if (probe) {
      group.addControl('id', new FormControl(probe?.id, []));
    }
    group.controls.probe.valueChanges.subscribe(res => {
      if (res && res.id) {
        group.controls.price.setValue(res['price']);
        group.controls.packing.setValue(res['packing']);
      }
    })
    return group;
  }

  addResearchDocument(index: number, doc: ResearchDocumentModel = null) {
    if (!this.model.probeItems[index].researchDocuments) {
      this.model.probeItems[index].researchDocuments = [];
    }
    if (!this.id || !this.model.id) {
      this.model.probeItems[index].researchDocuments.push(new ResearchDocumentModel());
    }
    const probs = this.formGroup.get('probeItems') as FormArray;
    const documents = probs.controls[index].get('researchDocuments') as FormArray;
    const item = this.getResearchDocument(doc);
    documents.push(item);
  }

  getResearchDocument(doc: ResearchDocumentModel = null): FormGroup {
    const group = new FormGroup({
      research: new FormControl(doc?.research, [Validators.required]),
      researchReason: new FormControl(doc?.researchReason, []),
      researchPriority: new FormControl(doc?.researchPriority, []),
      price: new FormControl(doc?.price ? doc?.price : 0, [Validators.required]),
      probeItems: new FormArray([]),
      status: new FormControl(doc?.status && doc?.status['code'] != null ? doc?.status : { code: "CREATE" }, []),
      date: new FormControl(doc?.date ? doc?.date : new Date(), [Validators.required]),
      laboratory: new FormControl(doc?.laboratory, [Validators.required]),
    });
    if (doc) {
      group.addControl('id', new FormControl(doc?.id, []));
    }
    return group;
  }

  removeResearch(docIndex, index) {
    const probs = this.formGroup.get('probeItems') as FormArray;
    const documents = probs.controls[docIndex].get('researchDocuments') as FormArray;
    documents.removeAt(index);
  }

  removeProbe(index) {
    const documents = this.formGroup.get('probeItems') as FormArray;
    documents.removeAt(index);
  }

  lockForm(index: number = 0) {
    this.showExtraButton = true;
    if (this.model.cashReceipt) return true;
    if (this.model.probeItems[index] && this.model.probeItems[index].researchDocuments) {
      for (const i in this.model.probeItems[index].researchDocuments) {
        if (this.model.probeItems[index].researchDocuments[i].status && this.model.probeItems[index].researchDocuments[i].status.code !== 'CREATE') {
          return true;
        }
      }
    }
    return false;
  }

  needToSent() {
    for (const i in this.model.probeItems) {
      for (const j in this.model.probeItems[i].researchDocuments) {
        if (this.model.probeItems[i].researchDocuments[j].status && this.model.probeItems[i].researchDocuments[j].status.code === 'CREATE') {
          return true;
        }
      }
    }
    return false;
  }

  getSumm(probe) {
    let sum = probe.get('price') && probe.get('quantity') ? +probe.get('price').value * +probe.get('quantity').value : 0;
    probe.get('amount').setValue(sum);
    return sum;
  }

  getAllAmount() {
    let amount = 0;
    const probs = this.formGroup.get('probeItems') as FormArray;
    for (const i in probs.controls) {
      if (probs.controls[i]) {
        amount += +probs.controls[i].get('quantity').value * probs.controls[i].get('price').value;
        const documents = probs.controls[i].get('researchDocuments') as FormArray;
        for (const j in documents.controls) {
          if (documents.controls[j]) {
            amount += +documents.controls[j].get('price').value;
          }
        }
      }
    }
    return amount;
  }

  createReceipt() {
    this.store.dispatch(new LoadCreateAction({
      type: CrudType.ProbeSamplingCashReceipt, params: { id: this.model.id },
      onSuccess: (res) => {
        if (res.response && res.response.id) {
          this.router.navigate(['/cash/cash-receipt/', res.response.id]).then();
        }
      },
      onError: _ => {
      }
    }));
  }

  createNewLaboratory(i: number, j: number) {
    const dialogRef = this.dialog.open(ModalLaboratoryFormComponent, {
      width: window.innerWidth > 960 ? '60%' : '90%',
      height: '100% -50px',
      data: {
        header: "Создать лабораторию",
        actions: [
          {
            class: 'btn-st btn-st--left btn-st--gray',
            action: false,
            title: 'Отмена'
          },
          {
            class: 'btn-st btn-st--right btn-st--red',
            action: true,
            title: 'Удалить'
          },
        ],
      }
    });

    dialogRef.afterClosed().subscribe((result) => {
      if (result) {
        this.store.dispatch(new LoadGetAction({
          type: CrudType.Laboratory, params: result,
          onSuccess: (res) => {
            if (res.response && res.status == true) {
              const probs = this.formGroup.get('probeItems') as FormArray;
              const documents = probs.controls[i].get('researchDocuments') as FormArray;
              documents.controls[j].get('laboratory').setValue(res.response);
            }
          }
        }));
      }
    });
  }

  getResearch(probeItem, researchDocument) {
    researchDocument.probeItem = probeItem;
    return researchDocument;
  }

  addOwner($event): void {
    if ($event) {
      $event.preventDefault();
    }
    const dialogRef = this.dialog.open(EditComponent, {
      data: {
        openDialog: true,
      }

    });
    dialogRef.afterClosed().subscribe(res => {
      if (res) {
        this.ownerId = res.id;
        this.owner = res;
        this.formGroup.get('owner').setValue({ id: res.id, name: res.name });
        if (!this.owner.pets || this.owner.pets.length == 0) {
          this.addPet($event);
        }
      }
    });
  }

  addPet($event): void {
    if ($event) {
      $event.preventDefault();
    }
    if (this.ownerId !== undefined) {
      const dialogRef = this.dialog.open(PetAddFormComponent, {
        data: {
          openDialog: true,
          owner: this.owner
        }
      });
      dialogRef.afterClosed().subscribe(res => {
        if (res) {
          this.petId = res.pet.id;
          this.formGroup.get('pet').setValue({ id: res.pet.id, name: res.pet.name });
        }
      });
    }
  }
  getId() {
    this.ownerId = this.formGroup.controls.owner.value?.id;
    this.getOwnerModel(this.ownerId);

  }
  getOwnerModel(ownerId) {
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.Owner,
      params: {
        filter: { id: ownerId }
      },
      onSuccess: (res) => {
        this.owner = res.response.items[0];
      }
    })
    );
  }

  public static getProbeSamplingAmount(model: ProbeSamplingModel) {
    let amount = 0;
    for (const i in model.probeItems) {
      if (model.probeItems[i] && model.probeItems[i].quantity && model.probeItems[i].price) {
        amount += +model.probeItems[i].quantity * model.probeItems[i].price;
        for (const j in model.probeItems[i].researchDocuments) {
          if (model.probeItems[i].researchDocuments[j] && model.probeItems[i].researchDocuments[j].price) {
            amount += +model.probeItems[i].researchDocuments[j].price;
          }
        }
      }
    }
    return amount;
  }
}
