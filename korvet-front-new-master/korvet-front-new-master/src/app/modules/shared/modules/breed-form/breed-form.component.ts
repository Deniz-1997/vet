import {Component, EventEmitter, Input, OnChanges, OnInit, Output, SimpleChanges} from '@angular/core';
import {FormBuilder, FormGroup} from '@angular/forms';
import {ReferenceBreedModel} from '../../../../models/reference/reference.breed.models';
import {ReferencePetTypeModel} from '../../../../models/reference/reference.pet.type.models';

@Component({
  selector: 'app-breed-form',
  templateUrl: './breed-form.component.html',
  styleUrls: ['./breed-form.component.css']
})
export class BreedFormComponent implements OnInit, OnChanges {

  @Input() title: string;
  @Input() petTypes: ReferencePetTypeModel[];
  @Input() model: ReferenceBreedModel;
  @Output() submitForm = new EventEmitter();
  @Output() cancelForm = new EventEmitter();
  @Output() removeForm = new EventEmitter();

  formGroup: FormGroup;

  constructor(
    private fb: FormBuilder
  ) {
  }

  ngOnInit() {
    this.formGroup = this.fb.group({
      id: [''],
      name: [''],
      type: this.fb.group({id: ['']})
    });
    if (this.model) {
      this.formGroup.reset(this.model);
    }
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (this.formGroup) {
      if (changes['model'] && this.formGroup.value['id'] !== changes['model'].currentValue['id']) {
        this.formGroup.reset(changes['model'].currentValue);
      }
      if (changes['petTypes'] && changes['petTypes'].currentValue.length && this.model) {
        // console.log(this.model)
        this.formGroup.reset({name: '123123', type: {id: 3}});
      }
    }
  }

  submit(): void {
    if (this.formGroup.valid) {

    }
  }
}
