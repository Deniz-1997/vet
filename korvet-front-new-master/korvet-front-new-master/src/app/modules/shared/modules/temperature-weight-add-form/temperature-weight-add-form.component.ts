import {AfterViewInit, ChangeDetectorRef, Component, ElementRef, EventEmitter, Input, OnInit, Output, ViewChild} from '@angular/core';
import {FormControl, FormGroup, Validators} from '@angular/forms';
import {CrudType} from 'src/app/common/crud-types';
import {PetModel} from 'src/app/models/pet/pet.models';
import {Store} from '@ngrx/store';
import {ViewService} from 'src/app/modules/main/pages/pets/view/view.service';
import {NotifyService} from 'src/app/services/notify.service';
import {MatDialogRef} from '@angular/material/dialog';
import {AddComponent as PetsViewCardTemperatureAddComponent} from 'src/app/modules/main/pages/pets/view/card/temperature/add/add.component';
import {DatePipe} from '@angular/common';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadCreateAction} from 'src/app/api/api-connector/crud/crud.actions';

@Component({
  selector: 'app-temperature-weight-add-form',
  templateUrl: './temperature-weight-add-form.component.html',
  styleUrls: ['./temperature-weight-add-form.component.css']
})
export class TemperatureWeightAddFormComponent implements OnInit, AfterViewInit {

  @Input() pet: PetModel = new PetModel();
  @Input() title: string;
  @Input() type = CrudType.PetTemperature;
  @Output() submitForm: EventEmitter<any> = new EventEmitter<any>();
  @Output() afterSubmitForm: EventEmitter<any> = new EventEmitter<any>();
  @Output() cancelForm: EventEmitter<void> = new EventEmitter<void>();

  @ViewChild('headTemplate') headTemplate: ElementRef;
  public formGroup: FormGroup;
  showError = false;
  btnLoader = false;
  types = CrudType;

  constructor(private store: Store<CrudState>,
              private cdRef: ChangeDetectorRef,
              private datePipe: DatePipe,
              public petsViewService: ViewService,
              private notify: NotifyService,
              public dialogRef: MatDialogRef<PetsViewCardTemperatureAddComponent>) {

    if (!this.title && this.type === CrudType.PetTemperature) {
      this.title = 'Добавить измерение температуры';
    } else if (!this.title && this.type === CrudType.PetWeight) {
      this.title = 'Добавить измерение веса';
    }

  }

  ngOnInit() {
    this.setModel();
  }

  ngAfterViewInit() {
    if (this.headTemplate.nativeElement && this.headTemplate.nativeElement.innerHTML) {
      delete this.title;
      this.cdRef.detectChanges();
    }
  }

  setModel() {
    this.formGroup = new FormGroup({
      date: new FormControl(this.datePipe.transform(new Date(), 'dd.MM.yyyy'), [Validators.required]),
      time: new FormControl(this.datePipe.transform(new Date(), 'HH:mm'), [Validators.required]),
      value: new FormControl('', [Validators.required]),
    });
  }

  submit($event): void {
    if ($event) {
      $event.preventDefault();
    }
    this.showError = true;
    if (this.formGroup.valid) {
      if (this.submitForm.observers.length > 0) {
        this.submitForm.emit($event);
        return;
      }
      this.btnLoader = true;
      const model = {...this.formGroup.value};
      if (this.pet && this.pet.id) {
        model.pet = {id: parseInt(this.pet.id.toString(), 10)};
      }
      if (this.type === CrudType.PetWeight) {
        model.value = parseInt((parseFloat(model.value) * 1000).toString(), 10);
      } else {
        model.value = parseFloat(model.value);
      }
      model.date = model.date + ' ' + model.time + ':00';
      this.store.dispatch(new LoadCreateAction({
        type: this.type,
        params: model,
        onSuccess: (res) => {
          if (res.status === true && res.response && res.response.id) {
            this.showError = false;
            if (this.afterSubmitForm.observers.length > 0) {
              this.afterSubmitForm.emit(res);
            }
          }
        },
        onError: () => {
          this.btnLoader = false;
        }
      }));
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }

  cancel() {
    if (this.cancelForm.observers.length > 0) {
      this.cancelForm.emit();
    }
  }
}
