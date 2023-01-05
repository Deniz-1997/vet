import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from '@angular/material/dialog';
import {FormControl} from '@angular/forms';
import { AppointmentModel } from 'src/app/models/appointment/appointment.models';
import { ProbeSamplingModel } from 'src/app/models/laboratory/probe-sampling.mode';

@Component({
  selector: 'app-modal-probe-sampling-form',
  templateUrl: './modal-probe-sampling-form.component.html',
  styleUrls: ['./modal-probe-sampling-form.component.css'],
})
export class ModalProbeSamplingFormComponent implements OnInit {
  control = new FormControl('');

  constructor(
    @Inject(MAT_DIALOG_DATA) public data: {
      header: string,
      appointment: AppointmentModel,
      probeSampling: ProbeSamplingModel
    },
    private dialogRef: MatDialogRef<ModalProbeSamplingFormComponent>,
  ) {
    if (data.probeSampling && data.appointment) {
      data.probeSampling.appointment = data.appointment;
    }
  }

  ngOnInit() {
  }

  submit() {
  }

  afterSave($event) {
    this.dialogRef.close($event);
  }
}
