import { Component, Input, OnInit } from '@angular/core';
import { ImportState } from '@ngrx/store-devtools/src/actions';
import { AppointmentModel } from 'src/app/models/appointment/appointment.models';

@Component({
  selector: 'app-appointment-history',
  templateUrl: './appointment-history.component.html',
  styleUrls: ['./appointment-history.component.css']
})
export class AppointmentHistoryComponent implements OnInit {

  @Input() appointment: AppointmentModel;
  @Input() disabled = true;
  constructor() { }

  ngOnInit(): void {
  }

}
