import {Component, EventEmitter, Input, OnChanges, OnDestroy, OnInit, Output, SimpleChanges} from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {EventModel} from '../../../../models/event.models';
import {UserModels} from '../../../../models/user/user.models';
import {ReferenceEventTypeModel} from '../../../../models/reference/reference.event.type.models';
import {PetModel} from '../../../../models/pet/pet.models';
import {EventFormInterface} from '../../../../interfaces/event-form.interface';
import {combineLatest, Observable, Subject} from 'rxjs';
import {NotifyService} from '../../../../services/notify.service';
import {takeUntil} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';

@Component({
  selector: 'app-event-form',
  templateUrl: './event-form.component.html',
  styleUrls: ['./event-form.component.css']
})
export class EventFormComponent implements OnInit, OnChanges, OnDestroy {
  @Input() model: EventModel;
  @Input() users: Observable<UserModels[]>;
  @Input() types: ReferenceEventTypeModel[];
  @Input() pets: Observable<PetModel[]>;
  @Input() ownerId: number;
  @Input() loading: boolean;
  @Input() title: string;
  @Output() cancelForm = new EventEmitter();
  @Output() submitForm = new EventEmitter<EventFormInterface>();

  crudType = CrudType;
  formGroup: FormGroup;
  showError = false;
  eventStatusDefault = 1;

  private destroy$ = new Subject<any>();

  constructor(private fb: FormBuilder,
              protected notify: NotifyService) {
  }

  ngOnInit() {
    
    this.formGroup = this.fb.group({
      data: ['', Validators.required],
      time: ['', Validators.required],
      date: [''],
      user: [null, Validators.required],
      type: this.fb.group({
        id: [null, Validators.required]
      }),
      comment: [''],
      status: this.fb.group({
        id: [this.model && this.model.status && this.model.status.id ? this.model.status.id : this.eventStatusDefault]
      }),
    });
    combineLatest(
      this.formGroup.controls['data'].valueChanges,
      this.formGroup.controls['time'].valueChanges,
    ).subscribe(
      ([data, time]) =>
        this.formGroup.controls['date'].setValue(
          [data, time].join(' ')
        )
    );

    if (this.pets) {
      this.pets
        .pipe(
          takeUntil(this.destroy$)
        )
        .subscribe(res => {
          if (res.length === 1) {
            this.formGroup.get('pet').setValue({id: res[0].id, name: res[0].name});
          }
        });
    }

    if (this.pets) {
      this.formGroup.addControl('pet', new FormControl('', Validators.required));
    }
  }
  isEdit() {
    return ['create', null, undefined].indexOf(this.formGroup.value['id']) < 0;
  }
  ngOnChanges(changes: SimpleChanges): void {
    if (this.formGroup) {
      if (!this.formGroup.get('pet') && changes['pets'] && !changes['pets'].previousValue) {
        this.formGroup.addControl('pet', new FormControl('', Validators.required));
      }
      if (changes['model'] && changes['model'].currentValue) {
        if (!this.formGroup.get('id')) {
          this.formGroup.addControl('id', this.fb.control(['']));
        }
        if (this.formGroup.value['id'] !== changes['model'].currentValue['id']) {
          const model = changes['model'].currentValue;
          if (model.date) {
            const datetime = model.date.split(' ');
            model['data'] = datetime[0];
            model['time'] = datetime[1];
          }
          this.formGroup.reset(model);
          this.formGroup.get('user').setValue({id: this.model.user.id, fullName: this.model.user.getFullName()});
        }
      }
    }
  }
 
  public goListUrl(): string {
    return '/owners/' + this.ownerId + ('/profile');
  }

  submit(): void {
    this.showError = true;
    if (this.formGroup.valid) {
      const value = this.formGroup.value;
      value.date = value.data + ' ' + value.time.substr(0, 5) + ':00';
      if (value.pet) {
        const petId = value.pet.id;
        delete value['pet'];
        value.pet = {id: petId};
      }
      if (new Date() < new Date(this.convertDate(value.data, value.time))) {
        value.status.id = 4;
      }
      if (value.status && value.status.id === null) {
        value.status.id = this.eventStatusDefault;
      }
      delete value['data'];
      delete value['time'];
      if (value.user && value.user.name) {
        delete value.user.name;
      }
      this.submitForm.emit(value);
      this.showError = false;
    } else {
      this.notify.handleMessage('Заполните обязательные поля', 'warning');
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  convertDate(date: string, time: string): string {
    let datePart = date.split('.');
    return datePart[2] + '-' + datePart[1] + '-' + datePart[0] + 'T' + time;
  }
}
