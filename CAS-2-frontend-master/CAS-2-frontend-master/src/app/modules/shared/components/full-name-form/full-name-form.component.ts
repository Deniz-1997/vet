import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormControl, FormGroup} from '@angular/forms';

@Component({
  selector: 'app-full-name-form',
  templateUrl: './full-name-form.component.html',
  styleUrls: ['./full-name-form.component.css']
})

export class FullNameFormComponent implements OnInit {

  formGroup: FormGroup;
  @Input() fullName: string;
  @Output() changeInput: EventEmitter<any> = new EventEmitter();
  surname: string;
  name: string;
  patronymic: string;
  modelFullName: string;

  constructor() {
  }

  ngOnInit(): void {
    const separatedFullNameArray = this.fullName.split( ' ');
    this.surname = separatedFullNameArray[0];
    this.name = separatedFullNameArray[1];
    this.patronymic = separatedFullNameArray[2];
    this.formGroup = new FormGroup({});
    this.formGroup.addControl('LastName', new FormControl(this.surname));
    this.formGroup.addControl('FirstName', new FormControl(this.name));
    this.formGroup.addControl('PatronymicName', new FormControl(this.patronymic));
    let model = {...this.formGroup.value};
    this.emitFunc(model);
    this.formGroup.valueChanges.subscribe((value) => {
      model = {...this.formGroup.value};
      this.emitFunc(model);
    });
  }

  emitFunc(item: any): void {
    this.modelFullName = Object.values(item).join(' ');
    this.changeInput.emit(this.modelFullName);
  }
}
