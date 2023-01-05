import { AfterContentChecked, ChangeDetectorRef, Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import {FormGroup} from '@angular/forms';
@Component({
  selector: 'app-personal-contact',
  templateUrl: './personal-contact.component.html',
  styleUrls: ['./personal-contact.component.css']
})
export class PersonalContactComponent implements OnInit, AfterContentChecked {

  @Input() lastNameRequired: boolean = true;
  @Input() nameRequired: boolean = true;
  @Input() phoneRequired: boolean = true;
  @Input() emailRequired: boolean = true;
  @Input() formGroup: FormGroup;
  @Output() getMatches = new EventEmitter();
  @Input() additionFieldName: string;
  @Input() additionFieldType: string;
  loaded = false;

  constructor(private cdref: ChangeDetectorRef
  ) {
  }

  ngOnInit(): void {
  }

  ngAfterContentChecked() {
    this.cdref.detectChanges();
  }
  getGender(gender) {
    this.formGroup.get('gender').setValue(gender);
  }
}


